<?php

namespace App\Http\Controllers\Compra;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImagenController;
use App\Models\Bitacora;
use App\Models\Bodega;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Inventario;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\ReporteKardex;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // se manda unicamente los datos necesarios de la compra
        $compras = Compra::with('proveedor', 'detalleCompras', 'usuario', 'sucursal')
            ->latest()
            ->activos()
            ->get();
        return view('compra.index', compact('compras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // obtener solo los registros con estados activos
        $sucursales = Sucursal::activos()->get();
        $proveedores = Proveedor::activos()->get();
        $productos = Producto::activos()->where('tipo', 1)->get();

        // nuevo valo, esto para mandar la url de la img
        $productos->each(function ($producto) {
            $producto->imagen_url = asset('uploads/' . $producto->imagen);
        });

        return view('compra.create', compact('proveedores', 'productos', 'sucursales'));
    }


    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'arrayprecio' => 'required|array',
            'arraycantidad.*' => 'integer|min:1',
            'arrayprecio.*' => 'numeric|min:0',
            'arrayvencimiento.*' => 'required|date',
            'estado' => 'integer',
            'imagen_comprobante' => 'nullable|string',
            'observaciones_comprobante' => 'nullable|string'
        ]);


        try {
            DB::beginTransaction();

            // Obtener la bodega principal
            $bodegaPrincipal = Bodega::principal()->firstOrFail();


            // Mover imagen temporal si existe esto es para el caso de comprobante
            $imagenComprobante = null;
            if (!empty($request->imagen_comprobante)) {
                $imagenController = new ImagenController();
                $imagenComprobante = $imagenController->moverDefinitiva($request->imagen_comprobante)
                    ? $request->imagen_comprobante
                    : null;
            }
            // generacion de codigo
            $ultimoId = Compra::max('id') ?? 0;
            $codigo = 'CR-' . str_pad($ultimoId + 1, 5, '0', STR_PAD_LEFT);
            //creando el registro de compra
            $compra = Compra::create([
                'numero_compra' => $codigo,
                'id_proveedor' => $request->id_proveedor,
                'id_sucursal' => $request->id_sucursal,
                'id_usuario' => 1,
                'impuesto' => $request->impuesto,
                //'fecha_compra'=>$request->fecha_compra,
                'fecha_compra' => Carbon::now()->format('Y-m-d'),
                'total' => $request->input('total'),
                'estado' => 1,
                'imagen_comprobante' => $imagenComprobante,
                'observaciones_comprobante' => $request->observaciones_comprobante
            ]);

            // obtener los arrays de detalles
            $arrayProducto_id = $request->get('arrayIdProducto');
            $arrayCantidad = $request->get('arraycantidad');
            $arrayprecio = $request->get('arrayprecio');
            $arrayvencimiento = $request->get('arrayvencimiento');

            //insertar los detalles
            foreach ($arrayProducto_id as $index => $idPoducto) {
                DetalleCompra::create([
                    'id_compra' => $compra->id,
                    'id_producto' => $idPoducto,
                    'cantidad' => $arrayCantidad[$index],
                    'precio' => $arrayprecio[$index]
                ]);

                $reporteKardex = ReporteKardex::create([
                    'producto_id' => $idPoducto,
                    'nombre_sucursal' => "Bodega principal", // Sucursal principal
                    'cantidad' => $arrayCantidad[$index],
                    'Cantidad_anterior' => 0, // Asumiendo que es la primera compra
                    'Cantidad_nueva' => $arrayCantidad[$index],
                    'usuario_id' => 1,
                    'tipo_movimiento' => 'Compra',
                    'fecha_movimiento' => Carbon::now(),
                ]);

                //  manejo de lotes
                $fechaVencimiento = $arrayvencimiento[$index];
                $numeroLote = 'LOTE-' . date('Ymd', strtotime($fechaVencimiento)) . '-' . str_pad($compra->id, 3, '0', STR_PAD_LEFT);
                // intento de registro por lote, esto en la tabla lote
                $lote = Lote::create([
                    'id_producto' => $idPoducto,
                    'numero_lote' => $numeroLote,
                    'fecha_vencimiento' => $fechaVencimiento,
                    'cantidad' => $arrayCantidad[$index],
                    'precio_compra' => $arrayprecio[$index], // guardamos en lote tambien
                    'id_compra' => $compra->id,
                    //'estado' => 1,
                ]);

                $producto = Producto::find($idPoducto);
                $producto->ultimo_precio_compra = $arrayprecio[$index];
                $producto->save();

                //Cantidad anterior de los lotes
                $lotes = Lote::where('id_producto', $idPoducto)->get();
                $cantidad = $lotes->sum('cantidad');


                // Verificar si ya existe un registro en el inventario para este producto y lote
                $inventarioExistente = Inventario::where('id_producto', $idPoducto)
                    ->where('id_lote', $lote->id)
                    ->where('id_bodega', $bodegaPrincipal->id) // Sucursal principal
                    ->first();


                if ($inventarioExistente) {
                    // Si existe, actualizar la cantidad
                    $inventarioExistente->cantidad += $arrayCantidad[$index];
                    $inventarioExistente->save();

                } else {

                    //  proceso de inventario
                    Inventario::create([
                        'id_producto' => $idPoducto,
                        'id_bodega' => $bodegaPrincipal->id,
                        'id_lote' => $lote->id,
                        'cantidad' => $arrayCantidad[$index],
                    ]);


                }
            }

            $usuario = User::find($request->idUsuario);
            Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' => $usuario->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Compras',
                'detalles' => "Se creó la compra: {$compra->numero_compra}",
                'fecha_hora' => now(),
            ]);

            DB::commit();
            return redirect()->route('compras.index')->with('success', 'Compra creado exitosamente');
        } catch (Exception $e) {
            // cancelar transaccion
            Log::error('Error al crear la compra: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->route('compras.create')->with('error', 'Error al crear la compra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Compra $compra)
    {
        // $productos = $compra->productos();
        if ($compra->imagen_comprobante) {
            $compra->imagen_comprobante_url = asset('uploads/' . $compra->imagen_comprobante);
        }
        return view('compra.show', compact('compra'));
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
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Compra $compra)
    {
        $estado = $request->input('status', 0);
        if ($estado == 0) {
            $compra->update(['estado' => 0]);
            return redirect()->route('compras.index')->with('success', 'Compra eliminado con éxito!');
        } else {
            $compra->estado = $estado;
            $compra->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
