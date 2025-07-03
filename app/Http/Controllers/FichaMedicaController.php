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

         // Recuperar la persona
        $persona = \App\Models\Persona::findOrFail($persona_id);

        // Verificar si la persona ya tiene una ficha médica previa
        $fichaExistente = $persona->fichasMedicas()->first();

        // Si no existe ficha previa y tampoco tienes valores, se le asigna null
        $departamentoId = optional($fichaExistente)->departamento_id ?? NULL;
        $municipioId = optional($fichaExistente)->municipio_id ?? NULL;

        // Mover la receta de temp a definitivo
        if (!empty($data['receta_foto'])) {
            $imagenController = new ImagenController();
            $moved = $imagenController->moverDefinitiva($data['receta_foto']);

            if (!$moved) {
                return back()->with('error', 'No se pudo guardar la receta médica');
            }
        }

        $data['persona_id'] = $persona_id;
        $data['departamento_id'] = $departamentoId;
        $data['municipio_id'] = $municipioId;

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

        DB::beginTransaction();
        try {
            $imagenOriginal = $ficha->receta_foto ? basename($ficha->receta_foto) : null;
            $nuevaImagen = $request->receta_foto;

            // Manejo de eliminación de receta
            if ($request->has('eliminar_receta') && $request->eliminar_receta == '1') {
                // Eliminar la receta anterior si existe
                if ($imagenOriginal && file_exists(public_path('uploads/' . $imagenOriginal))) {
                    unlink(public_path('uploads/' . $imagenOriginal));
                }
                $data['receta_foto'] = null;
            }
            // Manejo de nueva receta
            elseif ($nuevaImagen && $nuevaImagen !== $imagenOriginal) {
                $imagenController = new ImagenController();
                $imagenMovida = $imagenController->moverDefinitiva($nuevaImagen);

                if (!$imagenMovida) {
                    throw new \Exception('No se pudo guardar la nueva receta');
                }

                // Eliminar la receta anterior si existe
                if ($imagenOriginal && file_exists(public_path('uploads/' . $imagenOriginal))) {
                    unlink(public_path('uploads/' . $imagenOriginal));
                }

                $data['receta_foto'] = $nuevaImagen;
            }
            else {
                $data['receta_foto'] = $ficha->receta_foto;
            }

            $ficha->update($data);
            DB::commit();

            return redirect()
                ->route('personas.show', $persona_id)
                ->with('success', 'Ficha médica actualizada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la ficha: ' . $e->getMessage());
        }
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
