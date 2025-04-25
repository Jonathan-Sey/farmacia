<?php

namespace App\Http\Controllers\devoluciones;

use App\Http\Controllers\Controller;
use App\Mail\validacion;
use App\Models\Devoluciones;
use App\Models\Persona;
use App\Models\Sucursal;
use App\Models\Venta;
use App\Models\producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        $productos = producto::all();
        
        return view('devoluciones.create',compact('sucursales', 'personas', 'productos'));
    }

    public function store(Request $request){
        $validate = $request->validate([
            'id_venta' => 'required',
            'producto' => 'required',
            'cantidad' => 'required|integer|min:1',
            'monto' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:255',
            'usuario' => 'required',
            'sucursal' => 'required',
            'motivo' => 'required|string|max:255',
           
        ]);

        if(!$validate){
            return redirect()->back()->withErrors($validate)->withInput();
        }
      

        $devolucion = Devoluciones::create([
            'venta_id' => $request->id_venta,
            'producto_id' => $request->producto,
            'cantidad' => $request->cantidad,
            'monto' => $request->monto,
            'observaciones' => $request->observaciones,
            'usuario_id' => $request->usuario,
            'sucursal_id' => $request->sucursal,
            'motivo' => $request->motivo,
            'estado' => false,
            'fecha_devolucion' => now()
        ]);

        Mail::to('admin@tucorreo.com')->send(new validacion($devolucion));

        return redirect()->route('devoluciones.index')->with('success', 'Devolución registrada correctamente.');
    }

    public function autorizar($id)
    {
        $devolucion = Devoluciones::findOrFail($id);
        $devolucion->estado = true;
        $devolucion->save();
    
        return redirect()->route('devoluciones.index')->with('success', 'Devolución autorizada correctamente.');
    }

}
