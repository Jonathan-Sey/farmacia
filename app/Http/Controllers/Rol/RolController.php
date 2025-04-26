<?php

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Rol;
use Illuminate\Http\Request;
use App\Models\Pestana;
use Illuminate\Support\Facades\DB;
use App\Models\User;


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
            'descripcion' => 'required|max:100',
            'estado' => 'integer',
            'pestanas' => 'required|array', // Se valida que pestanas sea un array
            'pestanas.*' => 'exists:pestanas,id', // Se valida que cada ID de pestaña exista en la base de datos
            'pagina_inicio' => 'nullable|exists:pestanas,id', // Se valida que la nueva pestaña, si es seleccionada, exista
        ]);


        $rol = Rol::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => 1,
        ]);

        // Obtener el array de pestañas seleccionadas
        //$selectedTabs = $request->pestanas;

        // Si hay una nueva pestaña seleccionada, agregarla al principio
        // if ($request->nueva_pestana) {
        //     array_unshift($selectedTabs, $request->nueva_pestana);
        // }

        // Asignar las pestañas seleccionadas al rol
            //$rol->pestanas()->sync($selectedTabs);

        $pestanasData = [];
        foreach ($request->pestanas as $index => $pestanaId) {
            $pestanasData[$pestanaId] = [
                'orden' => $index + 1,
                'es_inicio' => $pestanaId == $request->pagina_inicio
            ];
        }

        $rol->pestanas()->sync($pestanasData);

        $usuario=User::find($request->idUsuario);

        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Rol',
                'detalles' => "Se creó el Rol: {$request->nombre}", //detalles especificos
                'fecha_hora' => now(),
        ]);
        return redirect()->route('roles.index')->with('success', '¡Registro exitoso!');
    }

    public function update(Request $request, Rol $rol)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:rol,nombre,'.$rol->id,
            'descripcion' => 'required|string|max:255',
            'pestanas' => 'required|array',
            'pestanas.*' => 'exists:pestanas,id',
            'pagina_inicio' => 'required|exists:pestanas,id'
        ]);

        // Actualizar datos básicos del rol
        $rol->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        // Preparar datos para sincronización
        $pestanasData = [];
        foreach ($request->pestanas as $index => $pestanaId) {
            $pestanasData[$pestanaId] = [
                'orden' => $index + 1,
                'es_inicio' => $pestanaId == $request->pagina_inicio ? 1 : 0
            ];
        }

        // Sincronizar pestañas
        $rol->pestanas()->sync($pestanasData);

        // Bitácora
        $usuario = User::find($request->idUsuario);
        Bitacora::create([
            'id_usuario' => $request->idUsuario,
            'name_usuario' => $usuario->name,
            'accion' => 'Actualización',
            'tabla_afectada' => 'Rol',
            'detalles' => "Se actualizó el Rol: {$request->nombre}",
            'fecha_hora' => now(),
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

    public function cambiarEstado($id)
    {
        $rol = Rol::find($id);

        if ($rol) {
            $rol->estado = $rol->estado == 1 ? 2 : 1; // Cambiar el estado (activo <-> inactivo)
            $rol->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

}
