<?php

namespace App\Http\Controllers\Venta;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImagenController;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Persona;
use App\Models\Sucursal;
use App\Models\Venta;
use App\Models\Almacen;
use App\Models\Bitacora;
use App\Models\ReporteKardex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\User;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $ventas = Venta::with(['sucursal','persona','usuario'])
        ->where('estado',1)
        ->latest()
        ->get();

        return view('venta.index',compact('ventas'));
    }
    public function obtenerStock($id, $sucursal) {
        Log::info('Solicitud para obtener stock:', ['id_producto' => $id, 'id_sucursal' => $sucursal]);

        // Buscar el almacén que contiene el producto en la sucursal específica
        $almacen = Almacen::where('id_producto', $id)
                          ->where('id_sucursal', $sucursal)
                          ->first();

        if ($almacen) {
            Log::info('Stock encontrado:', ['stock' => $almacen->cantidad]);
            return response()->json(['stock' => $almacen->cantidad]);
        } else {
            Log::error('Producto no encontrado en el almacén:', ['id_producto' => $id, 'id_sucursal' => $sucursal]);
            return response()->json(['error' => 'Producto no encontrado en el almacén de la sucursal seleccionada'], 404);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $persona = request()->has('id_persona') ? Persona::find(request('id_persona')) : null;

     //   $productos = collect();
        $productos = Producto::activos()->get();
        $almacenesActivos = Almacen::activos()->get();

         // Filtrar los productos disponibles en almacenes activos
         //$productos = Producto::whereIn('id', $almacenesActivos->pluck('id_producto'))->get();
        // Obtener las sucursales relacionadas a los almacenes activos
        $sucursales = Sucursal::whereIn('id', $almacenesActivos->pluck('id_sucursal'))->get();

//        $productos = Producto::whereIn('id', $almacenesActivos->pluck('id_producto'))->get();

        $personas = Persona::activos()->orderBy('nombre')->get();

        return view('venta.create',compact('sucursales','personas','almacenesActivos','persona'));
    }

    public function productosPorSucursal($id)
    {
        // Obtener los productos disponibles en la sucursal con stock > 0
        $productos = Almacen::where('id_sucursal', $id)
            ->where('cantidad', '>', 0)
            ->where('estado', 1) // validar estado
            ->with('producto')  // Obtener la relación con el producto
            ->get()
            ->map(function($almacen) {
                return [
                    'id' => $almacen->producto->id,
                    'imagen' => asset('uploads/' . $almacen->producto->imagen),
                    'nombre' => $almacen->producto->nombre,
                    'precio_venta' => $almacen->producto->precio_porcentaje,
                    'tipo' => $almacen->producto->tipo,
                    'stock' => $almacen->cantidad,
                ];
            });

        return response()->json($productos);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       //dd($request->all());
        //dd($request);
        $this->validate($request,[
            'arrayprecio' => 'required|array',
            'estado'=>'integer',
            'arraycantidad.*' => 'integer|min:1',
            'arrayprecio.*' => 'numeric|min:0',
            'arrayPrecioOriginal.*' => 'numeric|min:0',
            'arrayJustificacion.*' => 'nullable|string|max:255',
            'imagen_receta' => 'nullable|string',
            'numero_reserva' => 'nullable|string|max:50',
            'observaciones_receta' => 'nullable|string|max:500'

        ]);

        // validamos el control de compora por cliente
        $persona = Persona::find($request->id_persona);

        // Verificar restricciones
        if ($persona->tieneRestriccion()) {
            $mensaje = 'Esta persona tiene restricciones de compra: ';

            if ($persona->restriccion_activa) {
                $mensaje .= 'Restricción manual activada';
            } else {
                $mensaje .= "Excedió el límite de compras ({$persona->comprasRecientes()}/{$persona->limite_compras})";
            }

            return back()
            ->with('error', $mensaje)
            ->withInput();;
        }

        //         // Verificar que el total enviado coincida con el recalculado
        // $subtotal = array_sum($request->get('arrayprecio'));
        // $impuesto = round(($subtotal * $request->impuesto) / 100, 2);
        // $totalCalculado = round($subtotal + $impuesto, 2);

        // if ($totalCalculado != $request->total) {
        //     throw new Exception("El total enviado no coincide con el cálculo en el servidor.");
        // }

    try {
        DB::beginTransaction();

         // Mover imagen temporal a definitiva si existe
         $imagenReceta = null;
         if (!empty($request->imagen_receta)) {
             $imagenController = new ImagenController();
             $imagenReceta = $imagenController->moverDefinitiva($request->imagen_receta)
                 ? $request->imagen_receta
                 : null;
         }



        // Redondear el impuesto y total
        // $subtotal = array_sum($request->get('arrayprecio')); // Calcular subtotal
        // $impuesto = round(($subtotal * $request->impuesto) / 100, 2);  // Redondear impuesto
        // $total = round($subtotal + $impuesto, 2); // Redondear total

        // Crear el registro de venta
        $venta = Venta::create([
            'id_sucursal' => $request->id_sucursal,
            'fecha_venta' => $request->fecha_venta,
            'impuesto' => $request-> impuesto,
            'total' => $request-> total,
            'id_usuario' => $request->idUsuario, // Usar el usuario actual o el correcto
            'id_persona' => $request->id_persona,
            'estado' => 1,
            'es_prescrito' => $request->has('es_prescrito'),
            'imagen_receta' => $imagenReceta,
            'numero_reserva' => $request->numero_reserva,
            'observaciones_receta' => $request->observaciones_receta
        ]);

        // Obtener los arrays de detalles
        $arrayProducto_id = $request->get('arrayIdProducto');
        $arrayCantidad = $request->get('arraycantidad');
        $arrayprecio = $request->get('arrayprecio');
        $arrayPrecioOriginal = $request->get('arrayPrecioOriginal');
        $arrayJustificacion = $request->get('arrayJustificacion');

        foreach ($arrayProducto_id as $index => $idProducto) {
            $producto = Producto::findOrFail($idProducto);

            // Validar productos físicos (tipo = 1)
            if ($producto->tipo == 1) {
                $almacen = Almacen::where('id_sucursal', $request->id_sucursal)
                    ->where('id_producto', $idProducto)
                    ->first();

                if (!$almacen || $almacen->cantidad < $arrayCantidad[$index]) {
                    throw new Exception("No hay suficiente inventario para el producto: {$producto->nombre}");
                }

                // Descontar inventario
                $almacen->cantidad -= $arrayCantidad[$index];
                $almacen->save();

                $nombreSucursal = Sucursal::find($request->id_sucursal)->nombre;
                $reportekardex = ReporteKardex::create([
                    'producto_id' => $idProducto,
                    'nombre_sucursal' => $nombreSucursal,
                    'tipo_movimiento' => 'Venta',
                    'cantidad' => $arrayCantidad[$index],
                    'Cantidad_anterior' => $almacen->cantidad + $arrayCantidad[$index], // Cantidad antes de la venta
                    'Cantidad_nueva' => $almacen->cantidad, // Cantidad después de la venta
                    'usuario_id' => $request->idUsuario, // Aquí deberías usar el ID del usuario autenticado
                    'fecha_movimiento' => now()
                ]);
            }

                    // Crear el detalle de venta
                    DetalleVenta::create([
                       'id_venta' => $venta->id,
                       'id_producto' => $idProducto,
                       'cantidad' => $arrayCantidad[$index],
                       'precio' => round($arrayprecio[$index], 2), // Redondear el precio
                       'precio_original' => round($arrayPrecioOriginal[$index], 2),
                       'justificacion_descuento' => $arrayJustificacion[$index],
                     ]);
            }

        DB::commit();

        $usuario=User::find($request->idUsuario);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Venta',
                'detalles' => "Se creó la venta: {$usuario->id}", //detalles especificos
                'fecha_hora' => now(),
        ]);

        return redirect()->route('ventas.index')->with('success', 'Venta creada exitosamente');
    } catch (Exception $e) {
        // Cancelar transacción en caso de error
        DB::rollBack();

        return redirect()->route('ventas.create')->with('error', 'Error al crear la venta: ' . $e->getMessage());
    }
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {
        $venta->load('detalles.producto');
         //$venta->load('productos');
         if($venta->imagen_receta){
            $venta->imagen_receta_url = asset('uploads/' . $venta->imagen_receta);
         }
        return view('venta.show',compact('venta'));

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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Venta $venta)
    {
        $estado = $request->input('status', 0);
        if($estado == 0){
            $venta->update(['estado' => 0]);
            return redirect()->route('ventas.index')->with('success','Venta eliminado con éxito!');
        }else{
            $venta->estado = $estado;
            $venta->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success'=> false]);

    }
}
