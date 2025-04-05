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
        'apellido_paterno' => 'nullable|string|max:255',
        'apellido_materno' => 'nullable|string|max:255',
        'sexo' => 'nullable|in:Hombre,Mujer',
        'fecha_nacimiento' => 'nullable|date',
        'DPI' => 'nullable|string|max:255',
        'habla_lengua' => 'nullable|in:Sí,No',
        'tipo_sangre' => 'nullable|in:O+,O-,A+,A-,B+,B-,AB+,AB-',
        'direccion' => 'nullable|string|max:255',
        'telefono' => 'nullable|string|max:255',
        'foto' => 'nullable|string|max:255',
        'diagnostico' => 'nullable|string',
        'consulta_programada' => 'nullable|date',
        'receta_foto' => 'nullable|image',
    ]);

    // Crear la persona
    $persona = $this->crearPersona($request);

    if ($persona->rol == 2) { // Si la persona es un paciente
        // Subir la receta foto si existe
        $receta_foto = null;
        if ($request->hasFile('receta_foto')) {
            $receta_foto = $request->file('receta_foto')->store('recetas', 'public');
        }

        // Crear la ficha médica
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
            'receta_foto' => $receta_foto,
        ]);
    }

    // Registrar en la bitácora
    $usuario = User::find($request->idUsuario);
    Bitacora::create([
        'id_usuario' => $request->idUsuario,
        'name_usuario' => $usuario->name,
        'accion' => 'Creación',
        'tabla_afectada' => 'Personas',
        'detalles' => "Se creó la persona: {$request->nombre}",
        'fecha_hora' => now(),
    ]);

    return redirect()->route('personas.index')->with('success', 'Registro creado correctamente.');
}


    // Método para almacenar una persona desde ventas (JSON)
    public function storeFromVentas(Request $request)
    {
        $persona = $this->crearPersona($request);
<<<<<<< HEAD
        
        // Obtener la lista actualizada de personas
        $personas = Persona::where('estado', '!=', '0')->get();
=======
            // Obtener la lista actualizada de personas
         //$personas = Persona::where('estado', '!=', '0')->get();
>>>>>>> 0e34a008a4a08b2105991fa271c50539fd1f6f8c

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
        $this->validate($request, [
            'nombre' => 'required|string|max:45',
            'nit' => 'max:10',
            'telefono' => 'max:20',
        ]);

        $datosActualizados = $request->only(['nombre', 'nit', 'telefono', 'rol', 'fecha_nacimiento']);
        $datosSinCambio = $persona->only(['nombre', 'nit', 'telefono', 'rol', 'fecha_nacimiento']);

        // Validar el cambio de rol
        $nuevoRol = $request->has('rol') ? 2 : 1;
        if ($persona->rol != $nuevoRol) {
            // Permitir cambio de cliente a paciente (1 a 2)
            if ($persona->rol == 1 && $nuevoRol == 2) {
                $datosActualizados['rol'] = 2;
            }
            // No permitir cambio de paciente a cliente (2 a 1)
            elseif ($persona->rol == 2 && $nuevoRol == 1) {
                return redirect()->route('personas.edit', $persona->id)
                    ->withErrors(['rol' => 'No se permite cambiar el rol de paciente a cliente.']);
            }
        }


        $datosActualizados['rol'] = $nuevoRol;


        if ($datosActualizados != $datosSinCambio) {
            $persona->update($datosActualizados);

            if ($persona->rol == 2) {
                $fichaMedica = FichaMedica::where('persona_id', $persona->id)->first();
                if ($fichaMedica) {
                    $fichaMedica->update([
                        'diagnostico' => $request->diagnostico,
                        'consulta_programada' => $request->consulta_programada,
                    ]);
                }
            }

            return redirect()->route('personas.index')->with('success', '¡Persona Actualizada!');
        }

        // Si no hay cambios, solo registrar la bitácora
        $usuario = User::find($request->idUsuario);
        Bitacora::create([
            'id_usuario' => $request->idUsuario,
            'name_usuario' => $usuario->name,
            'accion' => 'Actualización',
            'tabla_afectada' => 'Personas',
            'detalles' => "Se actualizó la persona: {$request->nombre}",
            'fecha_hora' => now(),
        ]);

        return redirect()->route('personas.index');
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
