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

    public function store(Request $request)
    {
  
        $this->validate($request, [
            'nombre' => ['required', 'string', 'max:20', 'unique:rol,nombre'],
            'descripcion' => 'nullable|max:100',
            'estado' => 'integer',
            'pestanas' => 'required|array', // Validamos que pestanas sea un array
            'pestanas.*' => 'exists:pestanas,id', // Validamos que cada ID de pestaña exista en la base de datos
            'nueva_pestana' => 'nullable|exists:pestanas,id', // Validamos que la nueva pestaña, si es seleccionada, exista
        ]);

        
        $rol = Rol::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => 1,
        ]);

        // Obtener el array de pestañas seleccionadas
        $selectedTabs = $request->pestanas;

        // Si hay una nueva pestaña seleccionada, agregarla al principio
        if ($request->nueva_pestana) {
            array_unshift($selectedTabs, $request->nueva_pestana);
        }

        // Asignar las pestañas seleccionadas al rol
        $rol->pestanas()->sync($selectedTabs);  

    
        return redirect()->route('roles.index')->with('success', '¡Registro exitoso!');
    }

    public function update(Request $request, Rol $rol)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'pestanas' => 'array|nullable',
            'nueva_pestana' => 'nullable|exists:pestanas,id',
        ]);
        
        // Obtener las pestañas seleccionadas en el mismo orden en que fueron seleccionadas
        $selectedTabs = $request->input('pestanas', []);
        
        // Si hay una nueva pestaña seleccionada, reemplazar la primera pestaña
        if ($request->input('nueva_pestana')) {
            $newTab = $request->input('nueva_pestana');
            
            // Verificar si la nueva pestaña ya está en el array
            if (!in_array($newTab, $selectedTabs)) {
                // Agregar la nueva pestaña al inicio del array
                array_unshift($selectedTabs, $newTab);  // Agregar al inicio
            } else {
                // Si la nueva pestaña ya está en el array, la movemos al inicio
                $selectedTabs = array_diff($selectedTabs, [$newTab]); // Eliminar la pestaña existente
                array_unshift($selectedTabs, $newTab); // Agregarla al inicio
            }
        }
    
        // Asegurarse de que las pestañas estén únicas
        $selectedTabs = array_unique($selectedTabs);
        
        // Asignar las pestañas al rol en el mismo orden
        $rol->pestanas()->sync($selectedTabs);
        
        // Actualizar los otros campos del rol
        $rol->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);
        
        return redirect()->route('roles.index')->with('success', 'Rol actualizado con éxito');
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
