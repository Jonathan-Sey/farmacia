<?php

namespace App\Http\Controllers\Proveedor;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $proveedores = Proveedor::select('id','nombre','telefono','empresa','correo','estado','updated_at')
       ->where('estado','!=','0')
       ->get();
       return view('proveedor.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('proveedor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre'=>['required','string','max:35'],
            'telefono'=>['required','string','max:20'],
            'empresa'=>['required','string','max:35'],
            'correo'=>['required','string','max:35'],
            'direccion'=>['max:100','nullable','string'],
            'estado'=>'integer',
        ]);

        Proveedor::create([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'empresa' => $request->empresa,
            'correo' => $request->correo,
            'direccion' => $request->direccion,
            'estado' => 1,

        ]);

        $usuario=User::find($request->idUsuario);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Proveedores',
                'detalles' => "Se creó el proveedor: {$request->nombre}", //detalles especificos
                'fecha_hora' => now(),
        ]);
        return redirect()->route('proveedores.index')->with('success', '¡Registro exitoso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedor $proveedor)
    {

        return view('proveedor.edit', compact('proveedor'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $this->validate($request,[
            'nombre'=>['required','string','max:35'],
            'telefono'=>['required','string','max:20'],
            'empresa'=>['required','string','max:35'],
            'correo'=>['required','string','max:35'],
            'direccion'=>['max:100','nullable','string'],
            'estado'=>'integer',
        ]);

        $datosActualizados = $request->only(['nombre','telefono','empresa','correo','direccion']);
        $datosSinCambio = $proveedor->only(['nombre','telefono','empresa','correo','direccion']);

        if($datosActualizados == $datosSinCambio){
            return redirect()->route('proveedores.index');
        }
        $proveedor->update($datosActualizados);

        $usuario=User::find($request->idUsuario);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Actualización',
                'tabla_afectada' => 'Proveedores',
                'detalles' => "Se actualizo el proveedor: {$request->nombre}", //detalles especificos
                'fecha_hora' => now(),
        ]);

        return redirect()->route('proveedores.index')->with('success','¡Proveedor actualizado!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Proveedor $proveedor)
    {
        $estado = $request->input('status', 0);
        if($estado == 0){
            $proveedor->update(['estado' => 0]);
            return redirect()->route('proveedores.index')->with('success','Proveedor eliminado con éxito!');
        }else{
            $proveedor->estado = $estado;
            $proveedor->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success'=> false]);
    }

    public function cambiarEstado($id)
    {
        $proveedor = Proveedor::find($id);

        if ($proveedor) {
            $proveedor->estado = $proveedor->estado == 1 ? 2 : 1; // Cambiar el estado (activo <-> inactivo)
            $proveedor->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
