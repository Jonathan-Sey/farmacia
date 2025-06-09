<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\detalleSolicitud;
use App\Models\Solicitud;
use App\Models\Sucursal;
use App\Models\Bitacora;
use App\Models\User; // asegúrate de importar si es necesario
use Illuminate\Http\Request;

class solicitudController extends Controller
{

    public function index()
    {
        $solicitudes = detalleSolicitud::all()->where('estado', 1);

        return view('solicitud.index', compact('solicitudes'));
    }

    public function cantidadDeSolicitudes()
    {
        $cantidadDeSolicitudes = detalleSolicitud::where('estado', 1)->count();
        return response()->json(['cantidad' => $cantidadDeSolicitudes]);
    }


    public function create()
    {
        $sucursales = Sucursal::activos()->get();
         // Obtener solo productos (no servicios) con stock disponible
        $productos = Almacen::with(['producto' => function($query) {
            $query->where('tipo', 1) // 1 para productos, 2 para servicios
                ->where('estado', 1); // Solo activos
        }])
        ->whereHas('producto', function($query) {
            $query->where('tipo', 1)
                ->where('estado', 1);
        })
        ->where('cantidad', '>', 0) // Solo con stock disponible
        ->get()
        ->pluck('producto.nombre', 'producto.id')
        ->unique(); // Evitar duplicados

    return view('solicitud.create', compact('sucursales', 'productos'));
    }

    public function store(Request $request)
    {
        // Registrar en bitácora
        $usuario = User::find($request->idUsuario);


        $request->validate([
            'arraySucursal1' => 'required|array|min:1',
            'arraySucursal2' => 'required|array|min:1',
            'arrayIdProducto' => 'required|array|min:1',
            'arraycantidad' => 'required|array|min:1',
            'arrayDescripcion' => 'required|array|min:1',
            'arraycantidad.*' => 'integer|min:1',
        ], [
            'arraySucursal1.required' => 'Debe agregar al menos un producto a la solicitud',
            'arraySucursal2.required' => 'Debe agregar al menos un producto a la solicitud',
            'arrayIdProducto.required' => 'Debe agregar al menos un producto a la solicitud',
            'arraycantidad.required' => 'Debe agregar al menos un producto a la solicitud',
            'arrayDescripcion.required' => 'Debe agregar al menos un producto a la solicitud',
            'arraycantidad.*.integer' => 'La cantidad debe ser un número entero',
            'arraycantidad.*.min' => 'La cantidad debe ser al menos 1',
        ]);

        // Obtener datos del formulario
        $sucursal1 = $request->input('arraySucursal1');
        $sucursal2 = $request->input('arraySucursal2');
        $idProductos = $request->input('arrayIdProducto');
        $cantidades = $request->input('arraycantidad');
        $descripciones = $request->input('arrayDescripcion');

        // Crear la solicitud principal
        $solicitud = Solicitud::create([
            'id_sucursal_origen' => $sucursal1[0], // Primera sucursal del array
            'id_sucursal_destino' => $sucursal2[0], // Primera sucursal del array
            'id_producto' => $idProductos[0], // Primer producto del array
            'cantidad' => array_sum($cantidades), // Suma total de cantidades
            'descripcion' => 'Solicitud múltiple de productos', // Descripción general
            'estado' => 1,
            'id_user' => $request->idUsuario, // ID del usuario autenticado o 1 por defecto
        ]);

        // Crear detalles de solicitud
        foreach ($idProductos as $index => $idProducto) {
            detalleSolicitud::create([
                'solicitud_salida' => $sucursal1[$index],
                'solicitud_entrada' => $sucursal2[$index],
                'producto_id' => $idProducto,
                'id_solicitud' => $solicitud->id,
                'cantidad' => $cantidades[$index],
                'descripcion' => $descripciones[$index],
                'Id_usuario' => $request->idUsuario,
                'estado' => 1
            ]);
        }



        Bitacora::create([
            'id_usuario' => $request->idUsuario,
            'name_usuario' => $usuario->name,
            'accion' => 'Creación',
            'tabla_afectada' => 'Solicitudes',
            'detalles' => "Se creó la solicitud #{$solicitud->id} con " . count($idProductos) . " productos",
            'fecha_hora' => now(),
        ]);

        return redirect()->route('solicitud.index')->with('success', 'Solicitud creada exitosamente');
    }
}
