<?php

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use App\Models\Pestana;

class RolController extends Controller
{
    public function index()
    {
        //return view('roles.index');
        // obtenemos los registros de la tabla rol
        //$roles = Rol::where('estado', 1)->get();
        // retornamos los valores al vista del rol

        //$roles = Rol::all();
        //return view('roles.index',['roles' => $roles]);

        $roles = Rol::where('estado', '!=', 0)->get();
        return view('roles.index', ['roles' => $roles]);

    }
    public function create()
    {
        $pestanas = Pestana::all();
        return view('roles.create',compact('pestanas'));

    }
    public function store(Request $request)
    {
        //dd($request);
        //dd($request->get('nombre'));
        $this->validate($request,[
            'nombre'=>['required','string','max:20','unique:rol,nombre'],
            'descripcion'=>'nullable|max:100',
            'estado'=>'integer',
            'pestanas' => 'required|array',
        ]);
         $rol = Rol::create([
            'nombre'=> $request->nombre,
            'descripcion'=> $request->descripcion,
            'estado'=> 1,
        ]);

        // Asignar las pestañas seleccionadas al rol
        $rol->pestanas()->sync($request->pestanas);  // Asignar las pestañas
        return redirect()->route('roles.index')->with('success', '¡Registro exitoso!');
    }

    public function edit(Rol $rol)
    {
        // Obtén todas las pestañas disponibles en la base de datos
        $pestanas = Pestana::all();

        // Retorna la vista de edición con el rol y las pestañas disponibles
        return view('roles.edit', [
            'rol' => $rol,
            'pestanas' => $pestanas
        ]);
    }




    public function update(Request $request, Rol $rol)
    {
        // Validación de los datos
        $this->validate($request, [
            'nombre' => ['required', 'string', 'max:20', 'unique:rol,nombre,' . $rol->id],
            'descripcion' => 'nullable|max:100',
            'estado' => 'integer',
            'pestanas' => 'array', // Validación para las pestañas seleccionadas
            'pestanas.*' => 'integer|exists:pestanas,id', // Cada pestaña debe tener un id valido
        ]);
    
        // Verificar cambios en nombre y descripción
        $datosActualizados = $request->only(['nombre', 'descripcion']);
        $datosSinCambios = $rol->only(['nombre', 'descripcion']);
    
        if ($datosActualizados == $datosSinCambios && !$request->has('pestanas')) {
            return redirect()->route('roles.index');
        }
    
        // Actualizar el rol
        $rol->update($datosActualizados);
    
        // Actualizar las pestañas asociadas
        if ($request->has('pestanas')) {
            $rol->pestanas()->sync($request->input('pestanas')); 
        }
    
        return redirect()->route('roles.index')->with('success', '¡Rol actualizado con éxito!');
    }

    public function destroy(Request $request, Rol $rol)
    {
        //dd($rol);
        $estado = $request->input('status', 0);
        if($estado == 0){
            $rol->update(['estado' => 0]);
            return redirect()->route('roles.index')->with('success','¡Rol eliminado con éxito!');
        }else{
            $rol->estado = $estado;
            $rol->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success'=> false]);
    }

    
}
