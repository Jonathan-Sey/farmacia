<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\detalleSolicitud;
use App\Models\Solicitud;
use App\Models\Sucursal;
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
        $productos = Almacen::activos()->get();
        return view('solicitud.create', compact('sucursales', 'productos'));
    }

    public function store(Request $request)
    {

      
        $request->validate([
            'arraySucursal1' => 'required|array',
            'arraySucursal2' => 'required|array',
            'arrayIdProducto' => 'required|array',
            'arraycantidad' => 'required|array',
            'arrayDescripcion' => 'required|array',
        ]);

        // Obtener los datos del formulario
        $sucursal1 = $request->input('arraySucursal1');
        $sucursal2 = $request->input('arraySucursal2');
        $idProductos = $request->input('arrayIdProducto');
        $cantidades = $request->input('arraycantidad');
        $descripciones = $request->input('arrayDescripcion');


        for ($i = 0; $i < count($idProductos); $i++) {
            detalleSolicitud::create([
                'solicitud_salida' => $sucursal1[$i],
                'solicitud_entrada' => $sucursal2[$i],
                'producto' => $idProductos[$i],
                'id_solicitud' => 1,
                'cantidad' => $cantidades[$i],
                'Id_usuario' => 1,
                'estado' => 1
            ]);
        }

        return redirect()->route('solicitud.index')->with('success', 'solicitud creada exitosamente');
    }
}
