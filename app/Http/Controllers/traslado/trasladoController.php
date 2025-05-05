<?php

namespace App\Http\Controllers\traslado;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Bitacora;
use App\Models\Producto;
use App\Models\Solicitud;
use App\Models\Sucursal;
use App\Models\traslado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Builder\Trait_;

class trasladoController extends Controller
{
    public function index()
    {
        $traslado = traslado::with('producto:id,nombre', "sucursal1:id,nombre")->where('estado', '!=', 0)->get();
        $cantidadDeSolicitudes = Solicitud::where('estado', 1)->count();
        return view('traslado.index', compact('traslado', "cantidadDeSolicitudes"));
    }

    public function create()
    {
        $sucursales = Sucursal::activos()->get();
        $productos = Almacen::activos()
        ->whereHas('producto', function ($query) {
            $query->where('tipo', 1);
            })
            ->with('producto:id,nombre,tipo')
        ->get();

        return view('traslado.create', compact('sucursales', 'productos'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_sucursal_1' => ['required'],
            'id_sucursal_2' => ['required'],
            'id_producto' => ['required'],
            'idUsuario' => ['required'],
            'cantidad' => ['required', 'numeric']
        ]);

        $producto_id = $request->id_producto;
        $sucursal_origen_id = $request->id_sucursal_1;
        $sucursal_destino_id = $request->id_sucursal_2;
     
        $cantidad = $request->cantidad;

        $almacen_origen = Almacen::where('id_producto', $producto_id)
            ->where('id_sucursal', $sucursal_origen_id)
            ->first();

        if (!$almacen_origen || $almacen_origen->cantidad < $cantidad) {
            return redirect()->back()->withErrors(['cantidad' => 'Stock insuficiente en la sucursal de origen']);
        }

        $almacen_origen->cantidad -= $cantidad;
        $almacen_origen->save();

        $almacen_destino = Almacen::firstOrCreate(
            ['id_producto' => $producto_id, 'id_sucursal' => $sucursal_destino_id],
            ['cantidad' => 0, 'id_user' => 1, 'fecha_vencimiento' =>$almacen_origen->fecha_vencimiento ]
        );

        $almacen_destino->cantidad += $cantidad;
        $almacen_destino->save();

        traslado::create(
            [
                "id_sucursal_origen" => $request->id_sucursal_1,
                "id_sucursal_destino" => $request->id_sucursal_2,
                "id_producto" => $request->id_producto,
                "cantidad" => $request->cantidad,
                "id_user" => $request->idUsuario,
                "estado" => 1
            ]
        );

         // Bitacora
         $usuario = User::find($request->idUsuario);
         Bitacora::create([
             'id_usuario' => $request->idUsuario,
             'name_usuario' => $usuario->name,
             'accion' => 'Creación',
             'tabla_afectada' => 'Traslado',
             'detalles' => "Se creo el traslado del producto {$request->id_producto} con la cantidad de {$request->cantidad}", // Se usa el nombre de la sucursal
             'fecha_hora' => now(),
         ]);



        return redirect()->route("traslado.index")->with('success', '¡Transferencia realizada !');
    }

    public function edit(traslado $traslado)
    {

        $sucursales = Sucursal::activos()->get();
        $productos = Producto::activos()->get();
        return view('traslado.edit', compact('traslado', 'sucursales', 'productos'));
    }

    public function update(Request $request, traslado $traslado)
    {
        $this->validate($request, [
            'id_sucursal_1' => ['required'],
            'id_sucursal_2' => ['required'],
            'id_producto' => ['required'],
            'cantidad' => ['required', 'numeric']
        ]);

        $datosActualizados = $request->only(['id_sucursal_1', 'id_sucursal_2', 'id_producto', 'cantidad']);
        $datosSinCambios = $traslado->only(['id_sucursal_1', 'id_sucursal_2', 'id_producto', 'cantidad']);

        // Bitacora
        $usuario=User::find($request->idUsuario);
        $sucursal_origen = Sucursal::find($request->id_sucursal_origen);
        $sucursal_destino = Sucursal::find($request->id_sucursal_destino);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Actualización',
                'tabla_afectada' => 'Traslado',
                'detalles' => "Se actualizo el traslado del producto {$request->id_producto} con la cantidad de {$request->cantidad}",
                'fecha_hora' => now(),
        ]);

        if ($datosActualizados == $datosSinCambios) {
            return redirect()->route('traslado.index');
        } else {
            $traslado->update($datosActualizados);
            return redirect()->route('traslado.index')->with('success', "¡Traslado actualizado!");
        }
    }

    public function destroy(Request $request, traslado $traslado)
    {

        $estado = $request->input('status', 0);
        if ($estado == 0) {
            $traslado->update(['estado' => 0]);
            return redirect()->route('traslado.index')->with('success', 'transaccion eliminada con éxito!');
        } else {
            $traslado->estado = $estado;
            $traslado->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function obtenerProductos($id_sucursal)
    {
        // Obtener los productos disponibles en la sucursal seleccionada
        $productos = Almacen::where('id_sucursal', $id_sucursal)
            ->with('producto') // Relación con productos
            ->get();

        return response()->json($productos);
    }

    public function cambiarEstado($id)
    {
        $traslado = traslado::find($id);

        if ($traslado) {
            $traslado->estado = $traslado->estado == 1 ? 2 : 1; // Cambiar el estado (activo <-> inactivo)
            $traslado->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

}
