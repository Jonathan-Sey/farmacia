<?php

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index()
    {
        //return view('roles.index');
        // obtenemos los registros de la tabla rol
        $roles = Rol::all();
        //$roles = Rol::where('estado', 1)->get();
        // retornamos los valores al vista del rol
        return view('roles.index',['roles' => $roles]);

    }
    public function create()
    {
        return view('roles.create');

    }
    public function store(Request $request)
    {
        //dd($request);
        //dd($request->get('nombre'));
        $this->validate($request,[
            'nombre'=>['required','string','max:20','unique:rol,nombre'],
            'descripcion'=>'nullable|max:100',
            'estado'=>'integer',
        ]);
        Rol::create([
            'nombre'=> $request->nombre,
            'descripcion'=> $request->descripcion,
            'estado'=> 1,
        ]);
        return redirect()->route('roles.index')->with('success', '¡Registro exitoso!');
    }

    public function edit(Rol $rol)
    {

        return view('roles.edit', ['rol' => $rol]);
    }



    public function update(Request $request, Rol $rol)
    {
        //validacion de los datos
        $this->validate($request,[
            'nombre'=>['required','string','max:20','unique:rol,nombre,' . $rol->id],
            'descripcion'=>'nullable|max:100',
            'estado'=>'integer',
        ]);

        // varificar cambios
        $datosActualizados = $request->only(['nombre', 'descripcion']);
        $datosSinCambios = $rol->only(['nombre', 'descripcion']);

        if($datosActualizados == $datosSinCambios){
            return redirect()->route('roles.index');
        }

        // actualizar el rol
        $rol->update($request->only(['nombre','descripcion']));
        return redirect()->route('roles.index')->with('success', '¡Rol actualizado!');
    }




    public function destroy(Rol $rol)
    {
        //dd($rol);
        if($rol->estado==1)
        {
            $rol->update(['estado'=> 0]);
        }else{
            $rol->update(['estado'=> 1]);
        }


        return redirect()->route('roles.index')->with('success','¡Rol eliminado con éxito!');
    }

}
