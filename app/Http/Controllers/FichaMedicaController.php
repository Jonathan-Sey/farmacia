<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\FichaMedica;
use Illuminate\Http\Request;
use App\Models\DetalleMedico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            'receta_foto'         => 'nullable|string',
        ]);



        // Mover la receta de temp a la ubicación definitiva
        if (!empty($data['receta_foto'])) {
            $imagenController = new ImagenController();
            $moved = $imagenController->moverDefinitiva($data['receta_foto']);

            if ($moved) {
                $data['receta_foto'] = 'recetas/' . $data['receta_foto'];
            } else {
                unset($data['receta_foto']);
            }
        }

        // Agregar persona_id al array validado
        $data['persona_id'] = $persona_id;

        // Crear la ficha médica
        FichaMedica::create($data);

        return redirect()
            ->route('personas.show', $persona_id)
            ->with('success', 'Ficha médica agregada correctamente.');
    }


    // Mostrar formulario para editar ficha medica
    public function edit($persona_id, FichaMedica $ficha)
    {
        $persona = Persona::findOrFail($persona_id);
        $medicos = DetalleMedico::with('medico')->activos()->get();

        return view('fichas.edit', compact('ficha', 'persona', 'medicos'));
    }

// Actualizar ficha médica
public function update(Request $request, $persona_id, FichaMedica $ficha)
    {
        $data = $request->validate([
            'detalle_medico_id'   => 'required|exists:detalle_medico,id',
            'diagnostico'         => 'required|string',
            'consulta_programada' => 'required|date',
            'receta_foto'         => 'nullable|string',
        ]);

                // Manejo de la receta
                if ($request->has('eliminar_receta') && $request->eliminar_receta) {
                    // Eliminar receta existente si hay una
                    if ($ficha->receta_foto) {
                        Storage::disk('public')->delete($ficha->receta_foto);
                        $data['receta_foto'] = null;
                    }
                } elseif (!empty($data['receta_foto']) && $data['receta_foto'] !== $ficha->receta_foto) {
                    // Mover la nueva receta de temp a la ubicación definitiva
                    $imagenController = new ImagenController();
                    $moved = $imagenController->moverDefinitiva($data['receta_foto']);

                    if ($moved) {
                        // Eliminar receta anterior si existe
                        if ($ficha->receta_foto) {
                            Storage::disk('public')->delete($ficha->receta_foto);
                        }
                        $data['receta_foto'] = $data['receta_foto'];
                    } else {
                        unset($data['receta_foto']);
                    }
                } else {
                    // Mantener la receta existente
                    unset($data['receta_foto']);
                }

                $ficha->update($data);



        return redirect()
            ->route('personas.show', $persona_id)
            ->with('success', 'Ficha médica actualizada correctamente.');
    }

// // // Mostrar vista para confirmar eliminación
//  public function destroyConfirm($id)
//      {
//          return view('fichas.delete', compact('ficha'));
//      }

// Eliminar ficha médica
public function destroy(FichaMedica $ficha)
    {
        try {
            DB::beginTransaction();

            // Eliminar la imagen de receta si existe
            if ($ficha->receta_foto) {
                $filePath = str_replace('recetas/', '', $ficha->receta_foto);
                Storage::disk('public')->delete('recetas/' . $filePath);
            }

            $persona_id = $ficha->persona_id;
            $ficha->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ficha médica eliminada correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la ficha médica: ' . $e->getMessage()
            ], 500);
        }

    }


}
