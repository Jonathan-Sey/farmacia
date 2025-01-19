<?php

namespace App\Http\Controllers\Venta;

use App\Http\Controllers\Controller;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Persona;
use App\Models\Sucursal;
use App\Models\Venta;
use App\Models\Almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productos = collect();
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
            ->where('cantidad', '>', 0) // Solo productos con cantidad disponible
            ->with('producto')  // Obtener la relación con el producto
            ->get()
            ->map(function($almacen) {
                return [
                    'id' => $almacen->producto->id,
                    'nombre' => $almacen->producto->nombre,
                    'precio_venta' => $almacen->producto->precio_venta,
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


        $this->validate($request,[
            'arrayprecio' => 'required|array',
            'estado'=>'integer',
            'arraycantidad.*' => 'integer|min:1',
            'arrayprecio.*' => 'numeric|min:0',

        ]);

        try{
            DB::beginTransaction();
            //creando el registro de venta
            $venta = Venta::create([
                'id_sucursal'=> $request->id_sucursal,
                'fecha_venta'=>$request->fecha_venta,
                'impuesto'=>$request->impuesto,
                'total'=>$request->input('total'),
                'id_usuario' => 1,
                'id_persona'=> $request->id_persona,
                'estado' => 1,
            ]);

            // obtener los arrays de detalles
            $arrayProducto_id = $request->get('arrayIdProducto');
            $arrayCantidad = $request->get('arraycantidad');
            $arrayprecio= $request->get('arrayprecio');

            //insertar los detalels
            foreach($arrayProducto_id as $index => $idProducto){
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
                }


                DetalleVenta::create([
                    'id_venta' => $venta->id,
                    'id_producto' => $idProducto,
                    'cantidad' => $arrayCantidad[$index],
                    'precio'=> $arrayprecio[$index]
                ]);
            }

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta creado exitosamente');
        }catch(Exception $e){
            // cancelar transaccion
            DB::rollBack();

            return redirect()->route('ventas.create')->with('error', 'Error al crear la compra: ' . $e->getMessage());
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
        //
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
