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


    // Mostrar formulario para editar ficha médica
public function edit($persona_id, $id)
    {
        $ficha = FichaMedica::findOrFail($id);
        $persona = Persona::findOrFail($persona_id); // o $ficha->persona si prefieres
        $medicos = DetalleMedico::with('medico')->activos()->get();

        return view('fichas.edit', compact('ficha', 'persona', 'medicos'));
    }

// Actualizar ficha médica
public function update(Request $request, $id)
    {
        $ficha = FichaMedica::findOrFail($id);

        $data = $request->validate([
            'detalle_medico_id'   => 'required|exists:detalle_medico,id',
            'diagnostico'         => 'required|string',
            'consulta_programada' => 'required|date',
            'receta_foto'         => 'nullable|image|max:5120',
        ]);

        // Manejar subida de nueva foto si la hay
        if ($request->hasFile('receta_foto')) {
            // Opcional: borrar foto antigua si quieres
            if ($ficha->receta_foto) {
                \Storage::disk('public')->delete($ficha->receta_foto);
            }
            $data['receta_foto'] = $request->file('receta_foto')->store('recetas', 'public');
        }

        $ficha->update($data);

        return redirect()
            ->route('personas.show', $ficha->persona_id)
            ->with('success', 'Ficha médica actualizada correctamente.');
    }

// Mostrar vista para confirmar eliminación
public function destroyConfirm($id)
    {
        $ficha = FichaMedica::findOrFail($id);
        return view('fichas.delete', compact('ficha'));
    }

// Eliminar ficha médica
public function destroy($id)
    {
        $ficha = FichaMedica::findOrFail($id);

        // Opcional: eliminar archivo receta
        if ($ficha->receta_foto) {
            \Storage::disk('public')->delete($ficha->receta_foto);
        }

        $ficha->delete();

        return redirect()
            ->route('personas.show', $ficha->persona_id)
            ->with('success', 'Ficha médica eliminada correctamente.');
    }


}
