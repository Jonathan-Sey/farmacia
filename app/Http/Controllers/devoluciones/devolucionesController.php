<?php

namespace App\Http\Controllers\devoluciones;

use App\Http\Controllers\Controller;
use App\Models\Devoluciones;
use App\Models\Persona;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class devolucionesController extends Controller
{
    public function index()
    {
        $devoluciones = Devoluciones::with(['sucursal','productos','usuario'])->where('estado',1)
        ->latest()
        ->get();
        return view('devoluciones.index', compact('devoluciones'));
    }

    public function create()
    {
        $sucursales = Sucursal::all();
        $personas = Persona::all();
        return view('devoluciones.create',compact('sucursales', 'personas'));
    }

    public function store(Request $request){
        $validate = $request->validate([
            'id_venta' => 'required',
            'producto' => 'required',
            'cantidad' => 'required|integer|min:1',
            'monto' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:255',
            'estado' => 'required',
            'usuario' => 'required',
            'sucursal' => 'required',
            'motivo' => 'required|string|max:255',
           
        ]);

        if(!$validate){
            return redirect()->back()->withErrors($validate)->withInput();
        }

        Devoluciones::create([
            'venta_id' => $request->id_venta,
            'producto_id' => $request->producto,
            'cantidad' => $request->cantidad,
            'monto' => $request->monto,
            'observaciones' => $request->observaciones,
            'estado' => $request->estado,
            'usuario_id' => $request->usuario,
            'sucursal_id' => $request->sucursal,
            'motivo' => $request->motivo,
            'fecha_devolucion' => now()
        ]);
        return redirect()->route('devoluciones.index')->with('success', 'Devolución registrada correctamente.');
    }
}
