<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\DetalleMedico;
use App\Models\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class MedicoController extends Controller
{
    /**
     * Mostrar la lista de médicos.
     */
    public function index()
    {
        $medicos = DetalleMedico::with('usuario:id,name')
            ->select('id', 'id_usuario', 'especialidad', 'estado', 'numero_colegiado', 'horarios')
            ->get();

        return view('medico.index', compact('medicos'));
    }

    /**
     * Mostrar el formulario para crear un nuevo médico.
     */
    public function create()
    {
        $usuarios = User::all();
        return view('medico.create', compact('usuarios'));
    }

    /**
     * Guardar un nuevo médico en la base de datos.
     */
    public function store(Request $request)
    {
        // Verifica los datos que llegan al controlador
        //dd($request->all());
        
        // Validación de los datos del formulario33
        $request->validate([
            'id_usuario' => 'required|exists:users,id',
            'especialidad' => ['required', 'string', 'max:75'],
            'numero_colegiado' => ['required', 'string', 'max:10'],
            'estado' => 'integer',
            'horarios' => 'required|array',
            'horarios.*.dia' => 'required|string',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => 'required|date_format:H:i|after:horarios.*.hora_inicio',
        ]);

        // Guardar el médico con los horarios en formato JSON
        DetalleMedico::create([
            'id_usuario' => $request->id_usuario,
            'especialidad' => $request->especialidad,
            'numero_colegiado' => $request->numero_colegiado,
            'estado' => 1,
            'horarios' => json_encode($request->horarios), // Guardar horarios como JSON
        ]);
        // Bitacora
        $usuario=User::find($request->idUsuario);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Medicos',
                'detalles' => "Se creó el medico:{$request->numero_colegiado} especialidad-{$request->especialidad}", //detalles especificos
                'fecha_hora' => now(),
        ]);

        return redirect()->route('medicos.index')->with('success', '¡Registro exitoso!');
    }

    /**
     * Mostrar el formulario de edición.
     */
    public function edit(DetalleMedico $medico)
    {
        $usuarios = User::all();
        return view('medico.edit', compact('medico', 'usuarios'));
    }

    /**
     * Actualizar los datos de un médico.
     */
    public function update(Request $request, DetalleMedico $medico)
    {
        $request->validate([
            'id_usuario' => 'required|exists:users,id',
            'especialidad' => ['required', 'string', 'max:75'],
            'numero_colegiado' => ['required', 'string', 'max:10'],
            'estado' => 'integer',
            'horarios' => 'required|array',
            'horarios.*.dia' => 'required|string',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => 'required|date_format:H:i|after:horarios.*.hora_inicio',
        ]);
        // Bitacora
        $usuario=User::find($request->idUsuario);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Actualización',
                'tabla_afectada' => 'Medicos',
                'detalles' => "Se actualizo el medico:{$request->numero_colegiado} especialidad-{$request->especialidad}", //detalles especificos
                'fecha_hora' => now(),
        ]);

        $datosActualizados = $request->only(['id_usuario', 'especialidad', 'numero_colegiado']);
        $datosActualizados['horarios'] = json_encode($request->horarios); // Guardar horarios como JSON

        $medico->update($datosActualizados);

        return redirect()->route('medicos.index')->with('success', '¡Médico actualizado!');
    }

    /**
     * Eliminar (desactivar) un médico.
     */
    public function destroy(Request $request, DetalleMedico $medico)
    {
        $estado = $request->input('status', 0);
        
        if ($estado == 0) {
            $medico->update(['estado' => 0]);
            return redirect()->route('medicos.index')->with('success', 'Médico eliminado con éxito!');
        } else {
            $medico->estado = $estado;
            $medico->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}

