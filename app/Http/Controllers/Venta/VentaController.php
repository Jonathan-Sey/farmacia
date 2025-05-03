<?php

namespace App\Http\Controllers\Venta;

use App\Http\Controllers\Controller;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Persona;
use App\Models\Sucursal;
use App\Models\Venta;
use App\Models\Almacen;
use App\Models\Bitacora;
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
     //   $productos = collect();
        $productos = Producto::activos()->get();
        $almacenesActivos = Almacen::activos()->get();

         // Filtrar los productos disponibles en almacenes activos
         //$productos = Producto::whereIn('id', $almacenesActivos->pluck('id_producto'))->get();
        // Obtener las sucursales relacionadas a los almacenes activos
        $sucursales = Sucursal::whereIn('id', $almacenesActivos->pluck('id_sucursal'))->get();

//        $productos = Producto::whereIn('id', $almacenesActivos->pluck('id_producto'))->get();

        $personas = Persona::activos()->get();

        return view('venta.create',compact('productos','sucursales','personas','almacenesActivos'));
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

       // dd($request->all());
        //dd($request);
        $this->validate($request,[
          
            'estado'=>'integer',
            'arraycantidad.*' => 'integer|min:1',
           'arrayprecio.*' => ['nullable', 'numeric', 'min:0'],
            'imagen_receta' => 'nullable|string',
            'numero_reserva' => 'nullable|string|max:50',
            'justificacion' => 'nullable|string|max:255',
            'id_sucursal' => 'nullable|integer|exists:sucursal,id',
            'cliente_especial' => 'nullable|in:on', // Precio de cliente especial

        ]);


       

        //         // Verificar que el total enviado coincida con el recalculado
        // $subtotal = array_sum($request->get('arrayprecio'));
        // $impuesto = round(($subtotal * $request->impuesto) / 100, 2);
        // $totalCalculado = round($subtotal + $impuesto, 2);

        // if ($totalCalculado != $request->total) {
        //     throw new Exception("El total enviado no coincide con el cálculo en el servidor.");
        // }

    try {
        DB::beginTransaction();

        // Redondear el impuesto y total
        $subtotal = array_sum($request->get('arrayprecio' ,'arrayprecioespecial')); // Calcular subtotal
        $impuesto = round(($subtotal * $request->impuesto) / 100, 2); // Redondear impuesto
        $total = round($subtotal + $impuesto, 2); // Redondear total

        // Crear el registro de venta
        $venta = Venta::create([
            'id_sucursal' => $request->id_sucursal,
            'fecha_venta' => $request->fecha_venta,
            'impuesto' => $impuesto,
            'total' => $request->total,
            'id_usuario' => $request->idUsuario, // Usar el usuario actual o el correcto
            'id_persona' => $request->id_persona,
            'estado' => 1,
            'es_prescrito' => $request->has('es_prescrito'),
            'imagen_receta' => $request->imagen_receta,
            'numero_reserva' => $request->numero_reserva,
            'descripcion'=> $request->justificacion,
        ]);

        // Obtener los arrays de detalles
        $arrayProducto_id = $request->get('arrayIdProducto');
        $arrayCantidad = $request->get('arraycantidad');
        $arrayprecio = $request->get('arrayprecio');
        $arrayprecioespecial = $request->get('arrayprecioespecial');

        foreach ($arrayProducto_id as $index => $idProducto) {
            $producto = Producto::findOrFail($idProducto);
        
            // Validar inventario si es producto físico
            if ($producto->tipo == 1) {
                $almacen = Almacen::where('id_sucursal', $request->id_sucursal)
                    ->where('id_producto', $idProducto)
                    ->first();
        
                if (!$almacen || $almacen->cantidad < $arrayCantidad[$index]) {
                    throw new Exception("No hay suficiente inventario para el producto: {$producto->nombre}");
                }
        
                $almacen->cantidad -= $arrayCantidad[$index];
                $almacen->save();
            }
        
            // Escoger precio especial si viene
            $precioVenta = isset($arrayprecioespecial[$index]) && $request->cliente_especial
                ? $arrayprecioespecial[$index]
                : $arrayprecio[$index];
        
            DetalleVenta::create([
                'id_venta' => $venta->id,
                'id_producto' => $idProducto,
                'cantidad' => $arrayCantidad[$index],
                'precio' => round($precioVenta, 2),
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
