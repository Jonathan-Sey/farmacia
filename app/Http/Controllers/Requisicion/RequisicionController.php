<?php

namespace App\Http\Controllers\Requisicion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Almacen;
use App\Models\Bitacora;
use App\Models\Bodega;
use App\Models\Inventario;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\ReporteKardex;
use App\Models\Requisicion;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class RequisicionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requisiciones = Requisicion::with(['producto', 'bodegaOrigen', 'sucursalDestino'])->get();
        return view('requisicion.index',compact('requisiciones'));
    }
    public function getLotes($idProducto, $idBodegaPrincipal)
    {
        // $lotes = Lote::where('id_producto', $idProducto)->get();
        // return response()->json($lotes);
         // Obtener los lotes disponibles en el inventario de la sucursal de origen
        $lotes = Inventario::where('id_producto', $idProducto)
        ->where('id_bodega', $idBodegaPrincipal)
        ->where('cantidad', '>', 0)
        ->with(['lote' => function($query) {
            $query->orderBy('fecha_vencimiento', 'asc'); // Ordenar por fecha de vencimiento
        }])
        ->get();

        $cantidadTotal = $lotes->sum('cantidad');

        Log::info('Lotes encontrados:', $lotes->toArray());

    return response()->json([
        'lotes' => $lotes,
        'cantidadTotal' => $cantidadTotal

    ]);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$productos = Producto::activos()->where('tipo',1)->get();
        $bodegaPrincipal  = Bodega::principal()->firstOrFail();; // almacen principal

        // Obtener solo los productos que tienen inventario en la sucursal de origen
        $productos = Producto::whereHas('inventarios', function($query) use ($bodegaPrincipal) {
            $query->where('id_bodega', $bodegaPrincipal->id)
                  ->where('cantidad', '>', 0);
        })
        ->with(['inventarios' => function($query) use ($bodegaPrincipal) {
            $query->where('id_bodega', $bodegaPrincipal->id)
                  ->where('cantidad', '>', 0)
                  ->with(['lote' => function($q) {
                    $q->orderBy('fecha_vencimiento', 'asc');
                }]);
        }])
        ->where('tipo', 1)
        ->where('estado', 1)
        ->get();


        $sucursales = Sucursal::activos()->get();
        //$inventario = Inventario::all();
        return view('requisicion.create',compact('productos','sucursales','bodegaPrincipal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Datos recibidos en el request:', $request->all());
        $this->validate($request, [
            'id_sucursal_destino' => 'required',
            'id_producto' => 'required',
            'cantidad' => 'required|integer|min:1',
        ]);
         // Bitacora
         $usuario = User::find($request->idUsuario);
         $producto = Producto::find($request->id_producto);
         $sucursal = Sucursal::find($request->id_sucursal_destino);
         Bitacora::create([
             'id_usuario' => $request->idUsuario,
             'name_usuario' => $usuario->name,
             'accion' => 'Creación',
             'tabla_afectada' => 'Traslado',
             'detalles' => "Se creo la solicitud de: {$producto->nombre} con la cantidad de: {$request->cantidad} hacia la sucursal: {$sucursal->nombre}", 
             'fecha_hora' => now(),
         ]);


        DB::beginTransaction();
        try {
            $productoId = $request->id_producto;
            $sucursalDestinoId = $request->id_sucursal_destino;
            $cantidadRequerida = $request->cantidad;
            $usuarioId = $request->idUsuario ?? 1;

            // 1. Obtener bodega principal
            $bodegaPrincipal = Bodega::principal()->firstOrFail();

            // 2. Obtener lotes disponibles ordenados por fecha de vencimiento
            $lotes = Lote::whereHas('inventarios', function($query) use ($bodegaPrincipal, $productoId) {
                    $query->where('id_bodega', $bodegaPrincipal->id)
                          ->where('id_producto', $productoId)
                          ->where('cantidad', '>', 0);
                })
                ->with(['inventarios' => function($query) use ($bodegaPrincipal) {
                    $query->where('id_bodega', $bodegaPrincipal->id);
                }])
                ->orderBy('fecha_vencimiento', 'asc')
                ->get();

            // 3. Verificar stock suficiente
            $stockTotal = $lotes->sum(function($lote) {
                return $lote->inventarios->sum('cantidad');
            });

            if ($stockTotal < $cantidadRequerida) {
                throw new \Exception("No hay suficiente stock en la bodega principal. Disponible: $stockTotal, Requerido: $cantidadRequerida");
            }

            // 4. Procesar transferencia lote por lote
            $cantidadRestante = $cantidadRequerida;
            $lotePrincipal = null;
            $almacenDestino = null;

            foreach ($lotes as $lote) {
                if ($cantidadRestante <= 0) break;

                $inventario = $lote->inventarios->firstWhere('id_bodega', $bodegaPrincipal->id);
                if (!$inventario) continue;

                $cantidadDisponible = $inventario->cantidad;
                $cantidadATransferir = min($cantidadDisponible, $cantidadRestante);

                // Guardar el primer lote para registrar en la requisición
                if (!$lotePrincipal) {
                    $lotePrincipal = $lote;
                }

                // Restar de inventario (bodega principal)
                $inventario->decrement('cantidad', $cantidadATransferir);

                // Sumar a almacén (sucursal destino) usando la función actualizarAlmacen
                $almacenDestino = $this->actualizarAlmacen(
                    $sucursalDestinoId,
                    $productoId,
                    $cantidadATransferir,
                    $lote->fecha_vencimiento,
                    $usuarioId
                );


                $cantidadRestante -= $cantidadATransferir;
            }

            // Registrar en el kardex
            $reporte = ReporteKardex::create([
                'producto_id' => $productoId,
                'sucursal_id' => $sucursalDestinoId,
                'tipo_movimiento' => 'traslado',
                'cantidad' => $cantidadATransferir, // Cambiado a la cantidad transferida en este lote
                'Cantidad_anterior' => $almacenDestino->cantidad - $cantidadATransferir,
                'Cantidad_nueva' => $almacenDestino->cantidad,
                'usuario_id' => $usuarioId,
                'fecha_movimiento' => now(),
            ]);

            // 5. Registrar la requisición
            $requisicion = Requisicion::create([
                'id_bodega_origen' => $bodegaPrincipal->id,
                'id_sucursal_destino' => $sucursalDestinoId,
                'id_producto' => $productoId,
                'id_lote' => $lotePrincipal->id,
                'cantidad' => $cantidadRequerida,
                'fecha_traslado' => now(),
                'id_usuario' => $usuarioId,
            ]);

            DB::commit();
            Log::info('Requisición creada exitosamente:', $requisicion->toArray());
            return redirect()->route('requisiciones.index')->with('success', 'Requisición realizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al realizar el traslado: ' . $e->getMessage());
            Log::error('Trace del error:', ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Error al realizar el traslado: ' . $e->getMessage());
        }
    }

    private function actualizarAlmacen($sucursalId, $productoId, $cantidad, $fechaVencimiento, $usuarioId)
    {
        // Actualizar o crear registro en almacén
    // Primero obtener o crear el registro
    $almacen = Almacen::firstOrNew([
        'id_sucursal' => $sucursalId,
        'id_producto' => $productoId
    ]);

    // Actualizar manualmente la cantidad
    $almacen->cantidad = ($almacen->cantidad ?? 0) + $cantidad;
    $almacen->fecha_vencimiento = $fechaVencimiento;
    $almacen->id_user = $usuarioId;
    $almacen->estado = 1;
    $almacen->save();

    return $almacen;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
