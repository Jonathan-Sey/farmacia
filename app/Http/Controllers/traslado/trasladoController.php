<?php

namespace App\Http\Controllers\traslado;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\traslado;
use Illuminate\Http\Request;
use PhpParser\Builder\Trait_;

class trasladoController extends Controller
{
    public function index()
    {
        $traslado = traslado::with('producto:id,nombre',"sucursal1:id,nombre")->where('estado', '!=', 0)->get();
        return view('traslado.index', compact('traslado'));
    }

    public function create()
    {
        $sucursales = Sucursal::activos()->get();
        $productos = Producto::activos()->get();
        return view('traslado.create', compact('sucursales', 'productos'));
    }

    public function store(Request $request )  {
        $this->validate($request, [
            'id_sucursal_1' =>[ 'required'],
            'id_sucursal_2' => ['required'],
            'id_producto' => ['required'],
            'cantidad' => ['required','numeric']
        ]);

        $producto_id = $request->id_producto;
        $sucursal_origen_id = $request->id_sucursal_1;
        $sucursal_destino_id = $request->id_sucursal_2;
        $cantidad = $request->cantidad;

       $almacen_origen = Almacen::where('id_producto', $producto_id)
            ->where('id_sucursal', $sucursal_origen_id)
            ->first();

            if (!$almacen_origen || $almacen_origen->cantidad < $cantidad) {
                return response()->json(['error' => 'Stock insuficiente en la sucursal de origen'], 400);
            }
    
            $almacen_origen->cantidad -= $cantidad;
             $almacen_origen->save();

             $almacen_destino = Almacen::firstOrCreate(
                ['id_producto' => $producto_id, 'id_sucursal' => $sucursal_destino_id],
                ['cantidad' => 0,'id_user' => 1]
            );

            $almacen_destino->cantidad += $cantidad;
            $almacen_destino->save();

        traslado::create(
            [
                "id_sucursal_origen" => $request->id_sucursal_1,
                "id_sucursal_destino" => $request->id_sucursal_2,
                "id_producto" => $request->id_producto,
                "cantidad" => $request->cantidad,
                "id_user" => 1,
                "estado" =>1
            ]
            );

    

            return redirect()->route("traslado.index")->with('success', '¡Transferencia realizada !');
    }

    public function edit(traslado $traslado)  {

        $sucursales = Sucursal::activos()->get();
        $productos = Producto::activos()->get();
        return view('traslado.edit', compact('traslado', 'sucursales', 'productos'));
    }

    public function update(Request $request, traslado $traslado)  {
        $this->validate($request, [
            'id_sucursal_1' =>[ 'required'],
            'id_sucursal_2' => ['required'],
            'id_producto' => ['required'],
            'cantidad' => ['required','numeric']
        ]);

        $datosActualizados = $request->only(['id_sucursal_1', 'id_sucursal_2', 'id_producto', 'cantidad']);
        $datosSinCambios = $traslado->only(['id_sucursal_1', 'id_sucursal_2', 'id_producto', 'cantidad']);

        if ($datosActualizados == $datosSinCambios) {
            return redirect()->route('traslado.index');
        }else {
            $traslado->update($datosActualizados);
            return redirect()->route('traslado.index')->with('success', "¡Traslado actualizado!");
        }
    }

    public function destroy(Request $request, traslado $traslado)  {
        
        $estado = $request->input('status', 0);
        if($estado == 0){
            $traslado->update(['estado' => 0]);
            return redirect()->route('traslado.index')->with('success','transaccion eliminada con éxito!');
        }else{
            $traslado->estado = $estado;
            $traslado->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success'=> false]);
    }
}

