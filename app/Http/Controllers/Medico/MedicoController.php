<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\DetalleMedico;
use App\Models\User;
use App\Models\Horario;
use Illuminate\Http\Request;
use App\Models\Sucursal;

class MedicoController extends Controller
{
    /**
     * Mostrar la lista de médicos con sus horarios y sucursales.
     */
    public function index()
    {
        $medicos = DetalleMedico::with(['usuario:id,name', 'horarios.sucursal'])
            ->select('id', 'id_usuario', 'especialidad', 'estado', 'numero_colegiado')
            ->get();

        return view('medico.index', compact('medicos'));
    }

    /**
     * Mostrar el formulario para crear un nuevo médico.
     */
    public function create()
    {
    $usuarios = User::all();
    $sucursales = Sucursal::all(); // Cargar sucursales para la vista
    return view('medico.create', compact('usuarios', 'sucursales'));
    }

    /**
     * Guardar un nuevo médico y sus horarios en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'id_usuario' => 'required|exists:users,id',
            'especialidad' => 'required|string|max:75',
            'numero_colegiado' => 'required|string|max:10',
            'estado' => 'integer',
            'horarios' => 'required|array',
            'horarios.*.sucursal_id' => 'required|exists:sucursal,id',
            'horarios.*.dia' => 'required|string',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => 'required|date_format:H:i|after:horarios.*.hora_inicio',
        ]);

        // Crear el médico
        $medico = DetalleMedico::create([
            'id_usuario' => $request->id_usuario,
            'especialidad' => $request->especialidad,
            'numero_colegiado' => $request->numero_colegiado,
            'estado' => 1, // Activo por defecto
        ]);

        // Guardar los horarios en la tabla `horarios`
        foreach ($request->horarios as $horario) {
            Horario::create([
                'medico_id' => $medico->id,
                'sucursal_id' => $horario['sucursal_id'],
                'horarios' => json_encode([
                    $horario['dia'] => [$horario['hora_inicio'] . '-' . $horario['hora_fin']]
                ]),
            ]);
        }

        return redirect()->route('medicos.index')->with('success', '¡Médico registrado exitosamente!');
    }

    /**
     * Mostrar el formulario de edición de un médico.
     */
    public function edit(DetalleMedico $medico)
    {
        $usuarios = User::all();
        return view('medico.edit', compact('medico', 'usuarios'));
    }

    /**
     * Actualizar los datos de un médico y sus horarios.
     */
    public function update(Request $request, DetalleMedico $medico)
    {
        $request->validate([
            'id_usuario' => 'required|exists:users,id',
            'especialidad' => 'required|string|max:75',
            'numero_colegiado' => 'required|string|max:10',
            'estado' => 'integer',
            'horarios' => 'required|array',
            'horarios.*.sucursal_id' => 'required|exists:sucursal,id',
            'horarios.*.dia' => 'required|string',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => 'required|date_format:H:i|after:horarios.*.hora_inicio',
        ]);

        // Actualizar datos del médico
        $medico->update([
            'id_usuario' => $request->id_usuario,
            'especialidad' => $request->especialidad,
            'numero_colegiado' => $request->numero_colegiado,
            'estado' => $request->estado,
        ]);

        // Actualizar o insertar nuevos horarios
        foreach ($request->horarios as $horario) {
            Horario::updateOrCreate(
                [
                    'medico_id' => $medico->id,
                    'sucursal_id' => $horario['sucursal_id'],
                ],
                [
                    'horarios' => json_encode([
                        $horario['dia'] => [$horario['hora_inicio'] . '-' . $horario['hora_fin']]
                    ]),
                ]
            );
        }

        return redirect()->route('medicos.index')->with('success', '¡Médico actualizado exitosamente!');
    }

    /**
     * Desactivar o eliminar un médico.
     */
    public function destroy(Request $request, DetalleMedico $medico)
    {
        $estado = $request->input('status', 0);

        if ($estado == 0) {
            $medico->update(['estado' => 0]);
            return redirect()->route('medicos.index')->with('success', 'Médico desactivado con éxito!');
        } else {
            $medico->estado = $estado;
            $medico->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
