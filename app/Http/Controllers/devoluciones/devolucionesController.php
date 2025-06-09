<?php

namespace App\Http\Controllers\devoluciones;

use App\Http\Controllers\Controller;
use App\Mail\validacion;
use App\Models\Almacen;
use App\Models\Bitacora;
use App\Models\DetalleDevolucion;
use App\Models\DetalleVenta;
use App\Models\Devoluciones;
use App\Models\Lote;
use App\Models\Notificaciones;
use App\Models\Persona;
use App\Models\Sucursal;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Requisicion;
use App\Models\SolicitudDevolucion;
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
        $validate = $request->validate([
            'id_venta' => 'required',
            'id_sucursal' => 'required',
            'id_persona' => 'required',
            'observaciones' => 'nullable|string|max:255',
            'idUsuario' => 'required',
            'motivo' => 'required|string|max:255',
            'detalles' => 'required|array',
        ]);

        $nombreUsuario = User::find($request->idUsuario)->name;

        $solicitud = SolicitudDevolucion::create([
            'venta_id' => $request->id_venta,
            'usuario_id' => $request->idUsuario,
            'persona_id' => $request->id_persona,
            'sucursal_id' => $request->id_sucursal,
            'total' => $request->total,
            'motivo' => $request->motivo,
            'observaciones' => $request->observaciones,
            'fecha_solicitud' => now(),
            'detalles' => json_encode($request->detalles),
        ]);

        $notificacion = Notificaciones::create([
            'tipo' => 'Devolución',
            'mensaje' => 'Hay una nueva solicitud de devolución pendiente de autorización.',
            'accion' => 'Revisar correo',
            'url' => "Gmail.com",
            'leido' => false,
        ]);

        $solicitud['detalles'] = is_string($solicitud['detalles'])
            ? json_decode($solicitud['detalles'], true)
            : $solicitud['detalles'];

        Mail::to('admin@tucorreo.com')->send(new validacion($solicitud, $notificacion));

        Bitacora::create([
            'id_usuario' => $request->idUsuario,
            'name_usuario' =>  $nombreUsuario,
            'accion' => 'Se registró una solicitud de devolución',
            'tabla_afectada' => 'solicitudes_devolucion',
            'detalles' => 'Solicitud de devolución de la venta ID: ' . $request->id_venta,
            'fecha_hora' => now(),
        ]);

        return redirect()->route('devoluciones.index')->with('success', 'Solicitud enviada para autorización.');
    }

    public function autorizar($id, $idNot)
    {
        $solicitud = SolicitudDevolucion::findOrFail($id);
        $detalles = json_decode($solicitud->detalles, true);

        $devolucion = Devoluciones::create([
            'venta_id' => $solicitud->venta_id,
            'usuario_id' => $solicitud->usuario_id,
            'persona_id'  => $solicitud->persona_id,
            'sucursal_id' => $solicitud->sucursal_id,
            'total' => $solicitud->total,
            'motivo' => $solicitud->motivo,
            'estado' => true,
            'observaciones' => $solicitud->observaciones,
            'fecha_devolucion' => now(),
        ]);

        foreach ($detalles as $detalle) {
            DB::table('devoluciones_detalles')->insert([
                'devolucion_id' => $devolucion->id,
                'producto_id' => $detalle['producto_id'],
                'cantidad' => $detalle['cantidad'],
                'precio' => $detalle['precio'],
                'subtotal' => $detalle['precio'] * $detalle['cantidad'],
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Buscar el detalle de venta correspondiente
            $detalleVenta = DetalleVenta::where('id_venta', $solicitud->venta_id)
                ->where('id_producto', $detalle['producto_id'])
                ->first();

            if ($detalleVenta) {
                $detalleVenta->cantidad -= $detalle['cantidad'];

                if ($detalleVenta->cantidad <= 0) {
                    $detalleVenta->delete(); // Eliminar si la cantidad queda en 0 o menos
                } else {
                    $detalleVenta->save(); // Guardar si aún queda cantidad
                }
            }
        }




        Venta::find($solicitud->venta_id)->update([
            'total' => max(0, Venta::find($solicitud->venta_id)->total - $solicitud->total),
        ]);

        //borrar la venta si el total es 0
        if (Venta::find($solicitud->venta_id)->total <= 0) {
            Venta::find($solicitud->venta_id)->delete();
        }

        
        $nuevaNotificacion = Notificaciones::create([
            'tipo' => 'Devolución',
            'mensaje' => 'La solicitud de devolución ha sido autorizada.',
            'accion' => 'Ver detalles',
            'url' => route('devoluciones.show', $devolucion->id),
            'leido' => false,
        ]);

        $solicitud->delete(); // Ya fue procesada

        return redirect()->route('devoluciones.index')->with('success', 'Devolución autorizada y registrada correctamente.');
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
