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

        //verificar que solo se pueda hacer la devolucin de 8am a 4pm

        $horaActual = Carbon::now()->format('H:i');
        $horaApertura = '08:00';
        $horaCierre = '16:00';

        if ($horaActual < $horaApertura || $horaActual > $horaCierre) {
           return redirect()->route('devoluciones.index')->with('error', 'Las devoluciones solo se pueden realizar de 8:00 a 16:00 horas.');
        }

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

        $solicitud1 = SolicitudDevolucion::create([
            'venta_id' => $request->id_venta,
            'usuario_id' => $request->idUsuario,
            'persona_id' => $request->id_persona,
            'sucursal_id' => $request->id_sucursal,
            'total' => $request->total,
            'motivo' => $request->motivo,
            'observaciones' => $request->observaciones,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'fecha_solicitud' => now(),
            'detalles' => json_encode($request->detalles),
        ]);

        $solicitud = SolicitudDevolucion::with(['venta', 'usuario', 'persona', 'sucursal'])
            ->where('id', $solicitud1->id)
            ->first();

        $notificacion = Notificaciones::create([
            'tipo' => 'Devolución',
            'mensaje' => 'Hay una nueva solicitud de devolución pendiente de autorización.',
            'accion' => 'Revisar correo',
            'url' => "https://mail.google.com/",
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
            'sucursal_id' => $solicitud->sucursal_id,
            'persona_id' => $solicitud->persona_id,
            'observaciones' => $solicitud->observaciones,
            'motivo' => $solicitud->motivo,
            'total' => $solicitud->total,
            'fecha_devolucion' => now(),
            'usuario_id' => $solicitud->usuario_id,
            'estado' => 1, // Estado 1 para autorizado
        ]);

        foreach ($detalles as $detalle) {
            DetalleDevolucion::create([
                'devolucion_id' => $devolucion->id,
                'producto_id' => $detalle['producto_id'],
                'cantidad' => $detalle['cantidad'],
                'precio' => $detalle['precio'],
                'subtotal' => $detalle['precio'] * $detalle['cantidad'],
                'fecha_caducidad' => $solicitud->fecha_vencimiento,
            ]);

          
        }

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
