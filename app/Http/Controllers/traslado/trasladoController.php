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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // Usamos la relación correcta (almacen en lugar de almacenes)
        $productos = Producto::whereHas('almacen') // Relación en singular
            ->where('tipo', 1)
            ->with(['almacen' => function($query) { // Relación en singular
                $query->select('id_sucursal', 'id_producto', 'cantidad');
            }])
            ->get()
            ->map(function($producto) {
                // Agregar stock_actual para mostrar en el select (opcional)
                $producto->stock_actual = $producto->almacen->sum('cantidad'); // Relación en singular
                return $producto;
            });

    return view('traslado.create', compact('sucursales', 'productos'));
    }
    public function obtenerProductos($id_sucursal)
    {
        $productos = Almacen::with('producto')
            ->where('id_sucursal', $id_sucursal)
            ->where('cantidad', '>', 0)
            ->get()
            ->map(function($item) {
                return [
                    'id_producto' => $item->id_producto,
                    'producto' => [
                        'nombre' => $item->producto->nombre,
                        'tipo' => $item->producto->tipo
                    ],
                    'cantidad' => $item->cantidad
                ];
            });

        return response()->json($productos);
    }

    public function obtenerStock($sucursalId, $productoId)
    {
        $almacen = Almacen::where('id_sucursal', $sucursalId)
            ->where('id_producto', $productoId)
            ->first();

        return response()->json([
            'stock' => $almacen ? $almacen->cantidad : 0
        ]);
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

    // Obtener productos con stock en la sucursal de origen
    $productos = Producto::whereHas('almacen', function($query) use ($traslado) {
            $query->where('id_sucursal', $traslado->id_sucursal_origen);
        })
        ->where('tipo', 1)
        ->with(['almacen' => function($query) use ($traslado) {
            $query->where('id_sucursal', $traslado->id_sucursal_origen)
                  ->select('id_sucursal', 'id_producto', 'cantidad');
        }])
        ->get()
        ->map(function($producto) {
            $producto->stock_actual = $producto->almacen->sum('cantidad');
            return $producto;
        });

    return view('traslado.edit', compact('traslado', 'sucursales', 'productos'));
}

public function update(Request $request, traslado $traslado)
{
    $this->validate($request, [
        'id_sucursal_1' => ['required'],
        'id_sucursal_2' => ['required'],
        'id_producto' => ['required'],
        'cantidad' => ['required', 'numeric'],
        'idUsuario' => ['required']
    ]);

    // Iniciamos la transacción
    DB::beginTransaction();

    try {
        // 1. Revertir el traslado original
        $this->revertirTraslado($traslado);

        // 2. Validar stock en nueva sucursal de origen
        $almacen_origen = Almacen::where('id_producto', $request->id_producto)
            ->where('id_sucursal', $request->id_sucursal_1)
            ->lockForUpdate() // Bloqueamos el registro para evitar race conditions
            ->first();

        if (!$almacen_origen || $almacen_origen->cantidad < $request->cantidad) {
            // Si no hay stock, revertimos la reversión
            $this->aplicarTraslado($traslado);
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['cantidad' => 'Stock insuficiente en la sucursal de origen'])
                ->withInput();
        }

        // 3. Aplicar nuevo traslado
        $this->aplicarTraslado($request);

        // 4. Actualizar registro del traslado
        $traslado->update([
            "id_sucursal_origen" => $request->id_sucursal_1,
            "id_sucursal_destino" => $request->id_sucursal_2,
            "id_producto" => $request->id_producto,
            "cantidad" => $request->cantidad,
            "id_user" => $request->idUsuario
        ]);

        // 5. Registrar en bitácora
        $usuario = User::find($request->idUsuario);
        Bitacora::create([
            'id_usuario' => $request->idUsuario,
            'name_usuario' => $usuario->name,
            'accion' => 'Actualización',
            'tabla_afectada' => 'Traslado',
            'detalles' => "Se actualizó el traslado del producto {$request->id_producto} con la cantidad de {$request->cantidad}",
            'fecha_hora' => now(),
        ]);


        DB::commit();

        return redirect()->route('traslado.index')->with('success', "¡Traslado actualizado!");

    } catch (\Exception $e) {
        // Algo falló, hacemos rollback
        DB::rollBack();
        Log::error('Error al actualizar traslado: ' . $e->getMessage());

        return redirect()->back()
            ->withErrors(['error' => 'Ocurrió un error al actualizar el traslado'])
            ->withInput();
    }
}

// Métodos auxiliares (sin cambios)
private function revertirTraslado($traslado)
{
    // Devolver cantidad a sucursal de origen
    $almacen_origen = Almacen::where('id_producto', $traslado->id_producto)
        ->where('id_sucursal', $traslado->id_sucursal_origen)
        ->first();

    if ($almacen_origen) {
        $almacen_origen->cantidad += $traslado->cantidad;
        $almacen_origen->save();
    }

    // Quitar cantidad de sucursal de destino
    $almacen_destino = Almacen::where('id_producto', $traslado->id_producto)
        ->where('id_sucursal', $traslado->id_sucursal_destino)
        ->first();

    if ($almacen_destino) {
        $almacen_destino->cantidad -= $traslado->cantidad;
        if ($almacen_destino->cantidad <= 0) {
            $almacen_destino->delete();
        } else {
            $almacen_destino->save();
        }
    }
}

private function aplicarTraslado($request, $traslado = null)
{
    $producto_id = $request->id_producto ?? $traslado->id_producto;
    $sucursal_origen_id = $request->id_sucursal_1 ?? $traslado->id_sucursal_origen;
    $sucursal_destino_id = $request->id_sucursal_2 ?? $traslado->id_sucursal_destino;
    $cantidad = $request->cantidad ?? $traslado->cantidad;

    // Quitar cantidad de sucursal de origen
    $almacen_origen = Almacen::where('id_producto', $producto_id)
        ->where('id_sucursal', $sucursal_origen_id)
        ->first();

    if ($almacen_origen) {
        $almacen_origen->cantidad -= $cantidad;
        $almacen_origen->save();
    }

    // Agregar cantidad a sucursal de destino
    $almacen_destino = Almacen::firstOrCreate(
        ['id_producto' => $producto_id, 'id_sucursal' => $sucursal_destino_id],
        ['cantidad' => 0, 'id_user' => $request->idUsuario ?? $traslado->id_user]
    );

    $almacen_destino->cantidad += $cantidad;
    $almacen_destino->save();
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

    // public function obtenerProductos($id_sucursal)
    // {
    //     // Obtener los productos disponibles en la sucursal seleccionada
    //     $productos = Almacen::where('id_sucursal', $id_sucursal)
    //         ->with('producto') // Relación con productos
    //         ->get();

    //     return response()->json($productos);
    // }

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
