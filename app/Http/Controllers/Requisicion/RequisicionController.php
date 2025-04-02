<?php

namespace App\Http\Controllers\Requisicion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Almacen;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Requisicion;
use App\Models\Sucursal;
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
        $requisiciones = Requisicion::with(['producto', 'sucursalOrigen', 'sucursalDestino'])->get();
        return view('requisicion.index',compact('requisiciones'));
    }
    public function getLotes($idProducto, $idSucursal)
    {
        // $lotes = Lote::where('id_producto', $idProducto)->get();
        // return response()->json($lotes);
         // Obtener los lotes disponibles en el inventario de la sucursal de origen
        $lotes = Inventario::where('id_producto', $idProducto)
        ->where('id_sucursal', $idSucursal)
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
        $productos = Producto::activos()->where('tipo',1)->get();
        $sucursales = Sucursal::activos()->get();
        $inventario = Inventario::all();
        return view('requisicion.create',compact('productos','sucursales','inventario'));
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
            'id_sucursal_origen' => 'required',
            'id_sucursal_destino' => 'required',
            'id_producto' => 'required',
            'cantidad' => 'required|integer|min:1',
            
        ]);

        DB::beginTransaction();
        try {
            $producto = $request->id_producto;
            // $sucursal_origen = $request->id_sucursal_origen;
            $sucursal_origen = 1;
            $sucursal_destino = $request->id_sucursal_destino;
            $cantidad = $request->cantidad;

            Log::info('Iniciando proceso de requisicion...');
            Log::info('Producto:', ['id_producto' => $producto]);
            Log::info('Sucursal Origen:', ['id_sucursal_origen' => $sucursal_origen]);
            Log::info('Sucursal Destino:', ['id_sucursal_destino' => $sucursal_destino]);
            Log::info('Cantidad:', ['cantidad' => $cantidad]);

            // Obtener los lotes disponibles en la sucursal de origen, ordenados por fecha de vencimiento
            $lotesDisponibles = Inventario::where('id_producto', $producto)
                ->where('id_sucursal', $sucursal_origen)
                ->where('cantidad', '>', 0)
                ->with(['lote' => function($query) {
                    $query->orderBy('fecha_vencimiento', 'asc');
                }])
                ->get();

            Log::info('Lotes disponibles:', $lotesDisponibles->toArray());

            // Verificar si hay suficiente inventario en la sucursal de origen
            $cantidadTotalDisponible = $lotesDisponibles->sum('cantidad');
            Log::info('Cantidad total disponible:', ['cantidad_total_disponible' => $cantidadTotalDisponible]);

            if ($cantidadTotalDisponible < $cantidad) {
                Log::error('No hay suficiente inventario en la sucursal de origen.');
                return redirect()->back()->with('error', 'No hay suficiente inventario en la sucursal de origen.');
            }

            // Distribuir la cantidad solicitada entre los lotes disponibles
            $cantidadRestante = $cantidad;
            Log::info('Cantidad restante a trasladar:', ['cantidad_restante' => $cantidadRestante]);

            foreach ($lotesDisponibles as $inventarioOrigen) {
                if ($cantidadRestante <= 0) {
                    Log::info('Cantidad restante es 0, terminando el proceso.');
                    break;
                }

                $cantidadATrasladar = min($inventarioOrigen->cantidad, $cantidadRestante);
                Log::info('Cantidad a trasladar desde el lote:', [
                    'id_lote' => $inventarioOrigen->id_lote,
                    'cantidad_a_trasladar' => $cantidadATrasladar
                ]);

                // Restar la cantidad del inventario de origen
                $inventarioOrigen->cantidad -= $cantidadATrasladar;
                $inventarioOrigen->save();
                Log::info('Inventario de origen actualizado:', $inventarioOrigen->toArray());

                $cantidadRestante -= $cantidadATrasladar;
                Log::info('Cantidad restante después del traslado:', ['cantidad_restante' => $cantidadRestante]);
            }

            // Actualizar el almacén de origen
            $almacenOrigen = Almacen::where('id_sucursal', $sucursal_origen)
                ->where('id_producto', $producto)
                ->first();

            if ($almacenOrigen) {
                $almacenOrigen->cantidad -= $cantidad;
                $almacenOrigen->save();
                Log::info('Almacén de origen actualizado:', $almacenOrigen->toArray());
            }

            // Actualizar el almacén de destino
            $almacenDestino = Almacen::where('id_sucursal', $sucursal_destino)
                ->where('id_producto', $producto)
                ->first();

                $fechaVencimiento = optional($lotesDisponibles->first())->lote->fecha_vencimiento ?? now();

            if ($almacenDestino) {
                $almacenDestino->cantidad += $cantidad;
                $almacenDestino->save();
                Log::info('Almacén de destino actualizado:', $almacenDestino->toArray());
            } else {
                $almacenDestino = Almacen::create([
                    'id_sucursal' => $sucursal_destino,
                    'id_producto' => $producto,
                    'cantidad' => $cantidad,
                    'fecha_vencimiento' => $fechaVencimiento, // Fecha de vencimiento por defecto
                    'id_user' => 1,
                    'estado' => 1, // Estado activo
                ]);
                Log::info('Nuevo almacén de destino creado:', $almacenDestino->toArray());
            }

            // Crear el registro de traslado
            $traslado = Requisicion::create([
                'id_sucursal_origen' => $sucursal_origen,
                'id_sucursal_destino' => $sucursal_destino,
                'id_producto' => $producto,
                'id_lote' => $lotesDisponibles->first()->id_lote, // Tomar el primer lote como referencia
                'cantidad' => $cantidad,
                'fecha_traslado' => now(),
                'id_usuario' => 1, // Aquí deberías usar el ID del usuario autenticado
            ]);

            Log::info('Traslado registrado:', $traslado->toArray());

            DB::commit();
            Log::info('Traslado realizado exitosamente.');
            return redirect()->route('requisiciones.index')->with('success', 'Traslado realizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al realizar el traslado: ' . $e->getMessage());
            Log::error('Trace del error:', ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Error al realizar el traslado: ' . $e->getMessage());
        }
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
