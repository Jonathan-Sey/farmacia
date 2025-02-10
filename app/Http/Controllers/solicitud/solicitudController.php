<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Solicitud;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class solicitudController extends Controller
{
    public function create()  {
        $sucursales = Sucursal::activos()->get();
        $productos = Almacen::activos()->get();
        return view('solicitud.create', compact('sucursales', 'productos'));
    }

    public function store(Request $request)  {
        $this->validate($request, [
            'id_sucursal_1' => ['required'],
            'id_sucursal_2' => ['required'],
            'id_producto' => ['required'],
            'cantidad' => ['required', 'numeric'],
            'descripcion' => ['required']
        ]);

        Solicitud::create([
            'id_sucursal_origen' => $request->id_sucursal_1,
            'id_sucursal_destino' => $request->id_sucursal_2,
            'id_producto' => $request->id_producto,
            'cantidad' => $request->cantidad,
            'descripcion' => $request->descripcion,
            'estado' => 1
        ]);

        return redirect()->route('solicitud.index')->with('success', '¡Transferencia realizada !');
    }
}
