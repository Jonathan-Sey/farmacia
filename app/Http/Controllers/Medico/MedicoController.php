<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\DetalleMedico;
use App\Models\Especialidades;
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
        $medicos = DetalleMedico::with(['usuario:id,name', 'especialidad:id,nombre', 'horarios' => function($query) {
            $query->with('sucursal')->latest();
        }])
        ->select('id', 'id_usuario', 'id_especialidad', 'estado', 'numero_colegiado')
        ->get();

        return view('medico.index', compact('medicos'));
    }

    /**
     * Mostrar el formulario para crear un nuevo médico.
     */
    public function create()
    {
        $usuarios = User::all();
        $sucursales = Sucursal::all(); // Asegúrate de que esta consulta está correcta
        $especialidades = Especialidades::all(); // Si necesitas las especialidades, puedes obtenerlas aquí

        // Puedes pasar las especialidades a la vista si es necesario
        return view('medico.create', compact('usuarios', 'sucursales', 'especialidades'));
    }


    /**
         * Guardar un nuevo médico y sus horarios en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $validatedData = $request->validate([
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
            'id_usuario' => $validatedData['id_usuario'],
            'id_especialidad' => $validatedData['especialidad'],
            'numero_colegiado' => $validatedData['numero_colegiado'],
            'estado' => 1,
           // 'horarios' => json_encode($request->horarios),
        ]);
         $usuario = User::find($request->id_usuario);

    Bitacora::create([
        'id_usuario' => $request->id_usuario,
        'name_usuario' => $usuario->name,
        'accion' => 'Creación',
        'tabla_afectada' => 'Medico',
        'detalles' => "Se creó el médico: {$usuario->name}",
        'fecha_hora' => now(),
    ]);

        // Guardar los horarios
        foreach ($validatedData['horarios'] as $horario) {
            Horario::create([
                'medico_id' => $medico->id,
                'sucursal_id' => $horario['sucursal_id'],
                'horarios' => [ // Pasamos directamente el array
                    $horario['dia'] => [
                        $horario['hora_inicio'] . '-' . $horario['hora_fin']
                    ]
                ],
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
        $sucursales = Sucursal::all();
        $especialidades = Especialidades::all(); // Si necesitas las especialidades, puedes obtenerlas aquí

        // Obtener horarios directamente con una consulta
        $horarios = Horario::where('medico_id', $medico->id)
                    ->with('sucursal')
                    ->get();

        $horariosTransformados = [];

        foreach ($horarios as $horario) {
            // Usar el casting automático del modelo Horario
            $horariosArray = $horario->horarios;

            // Si por alguna razón sigue siendo string
            if (is_string($horariosArray)) {
                $horariosArray = json_decode($horariosArray, true);
            }

            if (!is_array($horariosArray)) {
                continue;
            }

            foreach ($horariosArray as $dia => $rangos) {
                if (!is_array($rangos)) {
                    $rangos = [$rangos];
                }

                foreach ($rangos as $rango) {
                    if (is_string($rango) && strpos($rango, '-') !== false) {
                        list($hora_inicio, $hora_fin) = explode('-', $rango);

                        $horariosTransformados[] = [
                            'sucursal_id' => $horario->sucursal_id ?? null,
                            'dia' => $dia,
                            'hora_inicio' => trim($hora_inicio),
                            'hora_fin' => trim($hora_fin),
                            'horario_id' => $horario->id
                        ];
                    }
                }
            }
        }

        return view('medico.edit', compact('medico', 'usuarios', 'sucursales', 'horariosTransformados', 'especialidades'));
    }





    /**
     * Actualizar los datos de un médico y sus horarios.
     */
    public function update(Request $request, DetalleMedico $medico)
    {
        $validated = $request->validate([
            'id_usuario' => 'required|exists:users,id',
            'id_especialidad' => 'required|exists:especialidades,id',
            'numero_colegiado' => 'required|string|max:10',
            'horarios' => 'required|array',
            'horarios.*.sucursal_id' => 'required|exists:sucursal,id',
            'horarios.*.dia' => 'required|string',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => 'required|date_format:H:i|after:horarios.*.hora_inicio',
        ]);
         $usuario = User::find($request->id_usuario);
         Bitacora::create([
        'id_usuario' => $request->id_usuario,
        'name_usuario' => $usuario->name,
        'accion' => 'Actualización',
        'tabla_afectada' => 'Medico',
        'detalles' => "Se actualizo el médico: {$usuario->name}",
        'fecha_hora' => now(),
    ]);
           // Verificar que horarios es un array
    if (!is_array($validated['horarios'])) {
        return back()->with('error', 'Formato de horarios inválido');
    }
        // Actualizar datos del medico
        $medico->update([
            'id_usuario' => $request->id_usuario,
            'id_especialidad' => $request->id_especialidad,
            'numero_colegiado' => $request->numero_colegiado,
        ]);

        // Obtener ids de los horarios enviados
    $idsRecibidos = collect($request->horarios)
    ->pluck('horario_id')
    ->filter()
    ->toArray();



    // Eliminar horarios que ya no están en el formulario
    Horario::where('medico_id', $medico->id)
        ->whereNotIn('id', $idsRecibidos)
        ->delete();

    // Recorrer y actualizar o crear horarios
    foreach ($validated['horarios'] as $horario) {
        $horarioData = [
            $horario['dia'] => [$horario['hora_inicio'] . '-' . $horario['hora_fin']]
        ];

        if (isset($horario['horario_id'])) {
            Horario::where('id', $horario['horario_id'])
                ->update([
                    'sucursal_id' => $horario['sucursal_id'],
                    'horarios' => $horarioData // Ya no usamos json_encode aquí
                ]);
        } else {
            Horario::create([
                'medico_id' => $medico->id,
                'sucursal_id' => $horario['sucursal_id'],
                'horarios' => $horarioData // Ya no usamos json_encode aquí
            ]);
        }

        
    }
     // Forzar refresco de la relación
     $medico->load('horarios');

         // Alternativa: refrescar toda la instancia
    $medico->refresh();
    //dd($medico);
    

    return redirect()->route('medicos.index')->with('success', 'Médico actualizado correctamente');
    }


    /**
     * Desactivar o eliminar un médico.
     */
    public function destroy(Request $request, DetalleMedico $medico)
    {
        $estado = $request->input('status', 0);
        $medico->update(['estado' => $estado]);

        return redirect()->route('medicos.index')->with(
            'success', $estado == 0 ? 'Médico desactivado con éxito!' : 'Médico activado con éxito!'
        );
    }

    public function cambiarEstado($id)
    {
        $medico = DetalleMedico::find($id);

        if ($medico) {
            $medico->estado = $medico->estado == 1 ? 2 : 1; // Cambiar el estado (activo <-> inactivo)
            $medico->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
