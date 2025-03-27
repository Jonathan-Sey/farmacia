<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\FichaMedica;
use Illuminate\Http\Request;

class FichaMedicaController extends Controller
{
    // Mostrar el formulario para crear una ficha médica para una persona existente
    public function create($persona_id)
    {
        $persona = Persona::findOrFail($persona_id); // Encontrar la persona por ID
        return view('fichas.create', compact('persona'));
    }

    // Almacenar la nueva ficha médica
    public function store(Request $request, $persona_id)
    {
        $request->validate([
            'diagnostico' => 'required|string',
            'consulta_programada' => 'required|date',
            'receta_foto' => 'nullable|image|max:1024',
        ]);

        // Subir la foto de la receta médica si existe
        $receta_foto = null;
        if ($request->hasFile('receta_foto')) {
            $receta_foto = $request->file('receta_foto')->store('recetas', 'public');
        }

        // Crear la nueva ficha médica asociada a la persona
        FichaMedica::create([
            'persona_id' => $persona_id,
            'diagnostico' => $request->diagnostico,
            'consulta_programada' => $request->consulta_programada,
            'receta_foto' => $receta_foto,
        ]);

        return redirect()->route('personas.show', $persona_id)->with('success', 'Ficha médica agregada correctamente.');
    }
}
