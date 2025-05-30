<?php

namespace App\Http\Controllers\devoluciones;

use App\Http\Controllers\Controller;
use App\Mail\validacion;
use App\Models\Almacen;
use App\Models\Bitacora;
use App\Models\DetalleVenta;
use App\Models\Devoluciones;
use App\Models\Lote;
use App\Models\Notificaciones;
use App\Models\Persona;
use App\Models\Sucursal;
use App\Models\Venta;
use App\Models\producto;
use App\Models\Requisicion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class devolucionesController extends Controller
{
    public function index()
    {
        $devoluciones = Devoluciones::with(['sucursal', 'usuario', 'persona'])->where('estado', 1)
            ->latest()
            ->get();
        return view('devoluciones.index', compact('devoluciones'));
    }

    public function create()
    {
        $sucursales = Sucursal::all();
        $personas = Persona::all();
        $productos = producto::all();
        $ventas = Venta::all();

        return view('devoluciones.create', compact('sucursales', 'personas', 'productos', 'ventas'));
    }

    public function show($devoluciones)
    {
        $devolucion = Devoluciones::with([
            'usuario',
            'venta',
            'detalles.producto'
        ])->findOrFail($devoluciones);

        return view('devoluciones.show', compact('devolucion'));
    }

    public function store(Request $request)
    {
        //   dd($request);   
        $validate = $request->validate([
            'id_venta' => 'required',
            'id_sucursal' => 'required',
            'id_persona' => 'required',
            'observaciones' => 'nullable|string|max:255',
            'idUsuario' => 'required',
            'motivo' => 'required|string|max:255',

        ]);


        if (!$validate) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $nombreUsuario = User::find($request->idUsuario)->name;






        if (!$request->fecha_vencimineto) {
        } else {
            if ($request->fecha_vencimineto < now()) {
                $productosVencidos = Lote::where('fecha_vencimiento', '=', $request->fecha_vencimineto)->get();
                foreach ($productosVencidos as $producto) {
                    // Obtener todas las requisiciones relacionadas con este lote
                    $requisiciones = Requisicion::where('id_lote', $producto->id)->get();

                    // Eliminar todas las requisiciones asociadas
                    foreach ($requisiciones as $requisicion) {
                        $requisicion->delete();
                    }

                    // Insertar en la tabla correcta (revisa que el nombre esté bien escrito)
                    DB::table('producto__vecidos')->insert([
                        "id_producto" => $producto->id_producto,
                        'fecha_vencimiento' => $producto->fecha_vencimiento,
                        "id_compra" => $producto->id_compra,
                        'cantidad' => $producto->cantidad,
                        'motivo' => 'Producto vencido',
                        'fecha_devolucion' => now()
                    ]);

                    // Eliminar el producto de la tabla original
                    $producto->delete();
                }

                // borrar productos vencidos de la tabla almacen

                $almacenVencido =  Almacen::where('fecha_vencimiento', '<', Carbon::now())->get();

                foreach ($almacenVencido as $almacen) {

                    // Insertar en la tabla correcta (revisa que el nombre esté bien escrito)
                    DB::table('almacen_vencidos')->insert([
                        'id_sucursal' => $almacen->id_sucursal,
                        "id_producto" => $almacen->id_producto,
                        'cantidad' => $almacen->cantidad,
                        'fecha_vencimiento' => $almacen->fecha_vencimiento,
                        'id_user' => $almacen->id_user,
                        'estado' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Eliminar el producto de la tabla original
                    $almacen->delete();
                }

                // notificar al usuario
                DB::table('notificaciones')->insert([
                    'tipo' => 'producto vencidos',
                    'mensaje' => 'Se han encontrado productos vencidos y se han movido a la tabla correspondiente.',
                    'leido' => false,
                    'accion' => 'ver productos vencidos',
                    'url' => '/productos-vencidos',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }





        $devolucion = Devoluciones::create([
            'venta_id' => $request->id_venta,
            'usuario_id' => $request->idUsuario,
            'persona_id'  => $request->id_persona,
            'sucursal_id' => $request->id_sucursal,
            'total' => $request->total,

            'motivo' => $request->motivo,
            'estado' => false,
            'observaciones' => $request->observaciones,
            'fecha_devolucion' => now(),
        ]);

        foreach ($request->detalles as $detalle) {
            DB::table('devoluciones_detalles')->insert([
                'devolucion_id' => $devolucion->id,
                'producto_id' => $detalle['producto_id'],
                'cantidad' => $detalle['cantidad'],
                'precio' => $detalle['precio'],
                'estado' => true,
                'subtotal' => $detalle['precio'] * $detalle['cantidad'],
                'fecha_caducidad' => $request->fecha_vencimineto ?? null, // Se guarda si existe
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $venta = Venta::find($request->id_venta);

        $totalDevuelto = 0;

        foreach ($request->detalles as $detalle) {
            $subtotalDevuelto = $detalle['precio'] * $detalle['cantidad'];
            $totalDevuelto += $subtotalDevuelto;
        }

        if ($venta) {
            $venta->total = max(0, $venta->total - $totalDevuelto); // Evita que sea negativo
            $venta->save();
        }


        foreach ($request->detalles as $detalle) {
            $detalleVenta = DetalleVenta::where('id_venta', $request->id_venta)
                ->where('id_producto', $detalle['producto_id'])
                ->first();

            if ($detalleVenta) {
                $detalleVenta->cantidad -= $detalle['cantidad'];

                if ($detalleVenta->cantidad <= 0) {
                    $detalleVenta->delete(); // Si ya no hay nada vendido, eliminar el detalle
                } else {
                    $detalleVenta->save(); // Guardar nueva cantidad
                }
            }
        }
        //notificacion 
        $notificacion = Notificaciones::create([
            'tipo' => 'Devolución',
            'mensaje' => 'Se ha registrado una nueva devolución, si esta aprobada se mostrara en el inventario.',
            'accion' => 'ver detalles de la devolución',
            'url' => route('devoluciones.index'),
            'leido' => true,
        ]);

        Mail::to('admin@tucorreo.com')->send(new validacion($devolucion, $notificacion));

        $bitacora = Bitacora::create([
            'id_usuario' => $request->idUsuario,
            'name_usuario' =>  $nombreUsuario,
            'accion' => 'se registro una Devolución',
            'tabla_afectada' => 'devoluciones',
            'detalles' => 'Se ha registrado una nueva devolución de la venta con: ' . $request->id_venta,
            'fecha_hora' => now(),
        ]);




        return redirect()->route('devoluciones.index')->with('success', 'Devolución crada correctamente.');
    }

    public function autorizar($id, $idNot)
    {
        $devolucion = Devoluciones::findOrFail($id);
        $devolucion->estado = true;
        $devolucion->save();

        // Cambiar el estado de la notificación a leída
        $notificacion = Notificaciones::findOrFail($idNot);
        $notificacion->leido = false;
        $notificacion->save();


        return redirect()->route('devoluciones.index')->with('success', 'Devolución autorizada correctamente.');
    }

    public function getVenta($id)
    {
        $venta = Venta::with(['sucursal', 'usuario', 'persona'])->where('id', $id)->first();
        $detalleVenta = DetalleVenta::with(['producto'])->where('id_venta', $id)->get();
        $venta->detalles = $detalleVenta;
        $venta->total = $detalleVenta->sum(function ($detalle) {
            return $detalle->cantidad * $detalle->precio_venta;
        });
        if (!$venta) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }

        return response()->json($venta);
    }
}
