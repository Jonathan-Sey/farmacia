<?php

namespace App\Http\Controllers\Especialidades;

use App\Http\Controllers\Controller;
use App\Models\Especialidades;
use Illuminate\Http\Request;

class especialidadesController extends Controller
{
    public function index()
    {
        // Aquí puedes implementar la lógica para listar las especialidades
        $especialidades = Especialidades::where('estado', '!=', 0)
            ->select('id', 'nombre', 'descripcion', 'estado', 'created_at')
            ->get();
        return view('especialidades.index', compact('especialidades'));
    }

    public function create()
    {
        // Aquí puedes implementar la lógica para mostrar el formulario de creación de especialidades
        return view('especialidades.create');
    }
    public function store(Request $request)
    {
        // Aquí puedes implementar la lógica para almacenar una nueva especialidad
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Especialidades::create($request->all());

        return redirect()->route('especialidades.index')->with('success', 'Especialidad creada exitosamente.');
    }

    public function edit($id)
    {
        // Aquí puedes implementar la lógica para mostrar el formulario de edición de una especialidad
        $especialidad = Especialidades::findOrFail($id);
        return view('especialidades.edit', compact('especialidad'));
    }

    public function update(Request $request, $id)
    {
        // Aquí puedes implementar la lógica para actualizar una especialidad existente
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $especialidad = Especialidades::findOrFail($id);
        $especialidad->update($request->all());

        return redirect()->route('especialidades.index')->with('success', 'Especialidad actualizada exitosamente.');
    }

    public function cambiarEstado($id)
    {
       $especialidad = Especialidades::find($id);

        if ($especialidad) {
            $especialidad->estado = $especialidad->estado == 1 ? 2 : 1; // Cambiar el estado (activo <-> inactivo)
            $especialidad->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
        
    }
}
