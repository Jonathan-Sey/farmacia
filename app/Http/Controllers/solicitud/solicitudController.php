<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Solicitud;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class solicitudController extends Controller
{

    public function index()  {
        $solicitudes = Solicitud::all()->where('estado', 1);
    
        return view('solicitud.index', compact('solicitudes'));
        
    }

    public function cantidadDeSolicitudes()  {
        $cantidadDeSolicitudes = Solicitud::where('estado', 1)->count();
        return response()->json(['cantidad' => $cantidadDeSolicitudes]);
    }


    public function create()  {
        $sucursales = Sucursal::activos()->get();
        $productos = Almacen::activos()->get();
        return view('solicitud.create', compact('sucursales', 'productos'));
    }

    public function store(Request $request)  {
       
  
    }
}
