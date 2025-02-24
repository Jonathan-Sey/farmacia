<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Lote;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Sucursal;
use App\Models\Traslado;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$traslados = Traslado::with(['producto', 'sucursalOrigen', 'sucursalDestino'])->get();
        $almacenes = Almacen::with('producto:id,codigo,nombre,tipo')
        ->where('estado', '!=', 0)
        ->get();
        //return($almacenes);
        return view('almacen.index',compact('almacenes'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productos = Producto::activos()->where('tipo',2)->get();
        $sucursales = Sucursal::activos()->get();
        return view('almacen.create',compact('productos','sucursales'));
    }

//     public function getProductosPorSucursal($idSucursal)
// {
//     $almacenes = Almacen::activos()
//         ->where('id_sucursal', $idSucursal)
//         ->with('producto') // Relación con el modelo Producto
//         ->get();

//     $productos = $almacenes->map(function ($almacen) {
//         return [
//             'id' => $almacen->producto->id,
//             'nombre' => $almacen->producto->nombre,
//             'precio_venta' => $almacen->producto->precio_venta,
//             'tipo' => $almacen->producto->tipo,
//             'stock' => $almacen->cantidad,
//         ];
//     });

//     return response()->json([
//         'success' => true,
//         'productos' => $productos,
//     ]);
// }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'id_sucursal' => ['required'],
            'id_producto' => ['required',
            function ($attribute, $value, $fail) use ($request) {
            $existe = Almacen::where('id_sucursal', $request->id_sucursal)
                             ->where('id_producto', $value)
                             ->exists();
            if ($existe) {
                $fail('El servicio ya existe en esta sucursal.');
            }
        }],
            // 'cantidad' => ['required','numeric'],

        ]);

        Almacen::create([
            'id_producto' => $request->id_producto,
            'id_sucursal' => $request->id_sucursal,
            'cantidad'=> 1,
            'id_user' => 1,
        ]);

        return redirect()->route('almacenes.index')->with('success', '¡Registro exitoso!');

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
    public function edit(Almacen $almacen)
    {
        // $productos = Producto::activos()->get();
        $productos = Producto::activos()->where('tipo',2)->get();
        $sucursales = Sucursal::activos()->get();
        return view('almacen.edit',compact('almacen','productos','sucursales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Almacen $almacen)
    {

        $this->validate($request,[
            'id_sucursal' => ['required'],
            'id_producto' => ['required',
            function ($attribute, $value, $fail) use ($request) {
            $existe = Almacen::where('id_sucursal', $request->id_sucursal)
                             ->where('id_producto', $value)
                             ->exists();
            if ($existe) {
                $fail('El servicio ya existe en esta sucursal.');
            }
        }],
            // 'cantidad' => ['required','numeric'],

        ]);

        $datosActualizados = $request->only(['id_sucursal','id_producto','cantidad']);
        $datosSinCambios = $almacen->only(['id_sucursal','id_producto','cantidad']);

        if($datosActualizados == $datosSinCambios){
            return redirect()->route('almacenes.index');
        }
        $almacen->update($datosActualizados);
        return redirect()->route('almacenes.index')->with('success','¡Almacen actualizado!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Almacen $almacen)
    {

            $estado = $request->input('status', 0);
            if($estado == 0){
                $almacen->update(['estado' => 0]);
                return redirect()->route('almacenes.index')->with('success','Almacen eliminado con éxito!');
            }else{
                $almacen->estado = $estado;
                $almacen->save();
                return response()->json(['success' => true]);
            }
            return response()->json(['success'=> false]);

    }
}
