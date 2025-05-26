<?php

namespace App\Http\Controllers\Persona;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Persona;
use App\Models\User;
use App\Models\FichaMedica;  // Asegúrate de incluir el modelo de FichaMedica
use Illuminate\Http\Request;
use App\Models\DetalleMedico;

class PersonaController extends Controller
{
    // Método para mostrar la lista de personas
    public function index()
    {
        $personas = Persona::select('id', 'nombre', 'nit', 'rol', 'telefono', 'estado')
            ->where('estado', '!=', '0')
            ->get();
        return view('persona.index', compact('personas'));
    }

    // Método para mostrar el formulario de crear persona
    public function create()
    {
        // Obtener todos los médicos de la tabla 'detalle_medico'
        $medicos = DetalleMedico::all();
    
        // Pasar la variable $medicos a la vista
        return view('persona.create', compact('medicos'));
    }


    // Método para crear una persona
    protected function crearPersona(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:45|unique:persona,nombre',
            'nit' => 'max:10|unique:persona,nit',
            'telefono' => 'max:20',
        ]);
        $rol = $request->input('rol') == 2 ? 2 : 1;  // Rol 1 para cliente, 2 para paciente

        return Persona::create([
            'nombre' => $request->nombre,
            'nit' => $request->nit,
            'rol' => $rol,  
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ]);
    }

    public function fichasMedicas()
    {
        return $this->hasMany(FichaMedica::class);
    }

    // Método para almacenar una nueva persona y su ficha médica si es paciente
    public function store(Request $request)
{
    // Validar los datos de entrada
    $request->validate([
        'nombre' => 'required|string|max:255',
        'nit' => 'nullable|string|max:10|unique:persona,nit',
        'telefono' => 'nullable|string|max:20',
        'fecha_nacimiento' => 'nullable|date',
        'rol' => 'required|in:1,2',  // Asegurarse de que 'rol' esté en 1 o 2 (cliente o paciente)
    ]);

    $persona = Persona::create([
        'nombre' => $request->nombre,
        'nit' => $request->nit,
        'telefono' => $request->telefono,
        'fecha_nacimiento' => $request->fecha_nacimiento,
        'rol' => $request->rol,  // Asignar el rol correctamente
    ]);

    // Si es paciente, crear ficha médica
    if ($persona->rol == 2) { // Si el rol es 'paciente'
        FichaMedica::create([
            'persona_id' => $persona->id,
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'sexo' => $request->sexo,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'DPI' => $request->DPI,
            'habla_lengua' => $request->habla_lengua,
            'tipo_sangre' => $request->tipo_sangre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'foto' => $request->foto,
            'diagnostico' => $request->diagnostico,
            'consulta_programada' => $request->consulta_programada,
            'receta_foto' => $request->receta_foto,
            'detalle_medico_id' => $request->detalle_medico_id,
        ]);
    }

    // Redirigir a la lista de personas con un mensaje de éxito
    return redirect()->route('personas.index')->with('success', 'Persona registrada correctamente');
}


    // Método para almacenar una persona desde ventas (JSON)
    public function storeFromVentas(Request $request)
    {
        $persona = $this->crearPersona($request);


            // Obtener la lista actualizada de personas
         //$personas = Persona::where('estado', '!=', '0')->get();

        return response()->json([
            'success' => true,
            'persona' => [
                'id' => $persona->id,
                'nombre' => $persona->nombre,
                'nit' => $persona->nit,
                'rol' => $persona->rol,
            ],
            'personas' => Persona::where('estado', '!=', '0')->get(['id', 'nombre', 'nit', 'rol']),
        ]);
    }

    public function show($id)
        {
            $persona = Persona::with('fichasMedicas')->findOrFail($id); 
            return view('persona.show', compact('persona'));
        }

    // Método para editar una persona
    public function edit(Persona $persona)
    {
        return view('persona.edit', compact('persona'));
    }

    // Método para actualizar los datos de una persona
    public function update(Request $request, Persona $persona)
    {
        // 1. Definir reglas de validación base
        $rules = [
            'nombre' => 'required|string|max:255|unique:persona,nombre,'.$persona->id,
            'rol' => 'required|in:1,2',
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'nit' => 'nullable|string|max:10|unique:persona,nit,'.$persona->id
        ];
    
        // 2. Reglas específicas para pacientes
        if ($request->rol == 2) {
            $rules['nit'] = 'required|string|max:10|unique:persona,nit,'.$persona->id;
            $rules += [
                'apellido_paterno' => 'required|string|max:100',
                'apellido_materno' => 'required|string|max:100',
                'sexo' => 'required|in:Hombre,Mujer',
                'dpi' => 'required|string|max:20',
                'habla_lengua' => 'required|in:Sí,No',
                'tipo_sangre' => 'nullable|string|max:5',
                'direccion' => 'nullable|string|max:255',
                'detalle_medico_id' => 'required|exists:detalle_medico,id'
            ];
        }
    
        // 3. Validar los datos
        $validatedData = $request->validate($rules);
    
        // 4. Actualizar datos básicos de la persona
        $persona->update([
            'nombre' => $validatedData['nombre'],
            'nit' => $validatedData['nit'],
            'telefono' => $validatedData['telefono'],
            'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
            'rol' => $validatedData['rol']
        ]);
    
        // 5. Manejo de ficha médica para pacientes
        if ($validatedData['rol'] == 2) {
            $fichaMedicaData = [
                'apellido_paterno' => $validatedData['apellido_paterno'],
                'apellido_materno' => $validatedData['apellido_materno'],
                'sexo' => $validatedData['sexo'],
                'dpi' => $validatedData['dpi'],
                'habla_lengua' => $validatedData['habla_lengua'],
                'tipo_sangre' => $validatedData['tipo_sangre'] ?? null,
                'direccion' => $validatedData['direccion'] ?? null,
                'detalle_medico_id' => $validatedData['detalle_medico_id']
            ];
    
            // Actualizar o crear ficha médica
            if ($persona->fichasMedicas()->exists()) {
                $persona->fichasMedicas()->first()->update($fichaMedicaData);
            } else {
                $persona->fichasMedicas()->create($fichaMedicaData);
            }
        } elseif ($persona->fichasMedicas()->exists()) {
            // Si cambia de paciente a cliente, mantener la ficha pero marcarla como inactiva
            $persona->fichasMedicas()->first()->update(['estado' => 0]);
        }
    
        // 6. Redireccionar con mensaje de éxito
        return redirect()->route('personas.index', $persona->id)
               ->with('success', 'Datos actualizados correctamente');
    }

    // Método para eliminar una persona
    public function destroy(Request $request, Persona $persona)
    {
        $estado = $request->input('status', 0);
        if ($estado == 0) {
            $persona->update(['estado' => 0]);
            return redirect()->route('personas.index')->with('success', 'Persona eliminada con éxito!');
        } else {
            $persona->estado = $estado;
            $persona->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    // Método para cambiar el estado de una persona (activo / inactivo)
    public function cambiarEstado($id)
    {
        $persona = Persona::find($id);

        if ($persona) {
            $persona->estado = $persona->estado == 1 ? 2 : 1; // Cambiar el estado (activo <-> inactivo)
            $persona->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
