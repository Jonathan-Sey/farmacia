<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\FichaMedica;
use Illuminate\Http\Request;
use App\Models\DetalleMedico;
class FichaMedicaController extends Controller
{
    // Mostrar el formulario para crear una ficha médica para una persona existente
    public function create($persona_id)
    {
        $persona = Persona::findOrFail($persona_id);
        $medicos = DetalleMedico::with('medico')
                    ->activos()
                    ->get(); // Encontrar la persona por ID
        return view('fichas.create', compact('persona' , 'medicos'));
    }

    // Almacenar la nueva ficha médica
    public function store(Request $request, $persona_id)
    {
        // Validar los datos
        $data = $request->validate([
            'detalle_medico_id'   => 'required|exists:detalle_medico,id',
            'diagnostico'         => 'required|string',
            'consulta_programada' => 'required|date',
            'receta_foto'         => 'nullable|image|max:5120',
        ]);

        // Subir la foto de la receta médica si existe
        if ($request->hasFile('receta_foto')) {
            $data['receta_foto'] = $request->file('receta_foto')->store('recetas', 'public');
        }

        // Agregar persona_id al array validado
        $data['persona_id'] = $persona_id;

        // Crear la ficha médica
        FichaMedica::create($data);

        return redirect()
            ->route('personas.show', $persona_id)
            ->with('success', 'Ficha médica agregada correctamente.');
    }

}
