<?php

namespace App\Http\Controllers\Persona;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Persona;
use App\Models\User;
use App\Models\Departamento;
use App\Models\FichaMedica;
use Illuminate\Http\Request;
use App\Models\DetalleMedico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SoftlogicGT\ValidationRulesGT\Rules\Dpi;

class PersonaController extends Controller
{
    public function index()
    {
        $personas = Persona::select('id', 'nombre', 'nit', 'rol', 'telefono', 'estado')
            ->where('estado', '!=', '0')
            ->get();
        return view('persona.index', compact('personas'));
    }

    public function create()
    {
        $medicos = DetalleMedico::all();
        $departamentos = Departamento::all();
        return view('persona.create', compact('medicos','departamentos'));
    }

     protected function crearPersona(Request $request)
     {
         $this->validate($request, [
             'nombre' => 'required|string|max:45|unique:persona,nombre',
             'nit' => 'max:10|unique:persona,nit',
             'telefono' => 'max:20',
         ]);
         $rol = $request->input('rol') == 2 ? 2 : 1;

         return Persona::create([
             'nombre' => $request->nombre,
             'nit' => $request->nit,
             'DPI' => $request->dpi,
             'rol' => $rol,
             'telefono' => $request->telefono,
             'fecha_nacimiento' => $request->fecha_nacimiento,
             'limite_compras' => 5,       // Valor por defecto
             'periodo_control' => 30,     // Valor por defecto
             'restriccion_activa' => false // Valor por defecto
         ]);
     }
    public function fichasMedicas()
    {
        return $this->hasMany(FichaMedica::class);
    }

    public function store(Request $request)
    {
        $request->validate([
        'nombre' => 'required|string|max:255',
        'nit' => 'nullable|string|max:10|unique:persona,nit',
        'telefono' => 'nullable|string|max:20',
        'fecha_nacimiento' => 'nullable|date',
        'rol' => 'required|in:1,2',
        'apellido_paterno' => 'required_if:rol,2|string|max:100',
        'apellido_materno' => 'required_if:rol,2|string|max:100',
        'sexo' => 'required_if:rol,2|in:Hombre,Mujer',
        'dpi' => ['required', new Dpi()],
        'habla_lengua' => 'required_if:rol,2|in:Sí,No',
        'tipo_sangre' => 'nullable|string|max:5',
        'direccion' => 'nullable|string|max:255',
        'departamento_id' => 'required_if:rol,2|exists:departamentos,id',
        'municipio_id' => 'required_if:rol,2|exists:municipios,id'
    ]);
           $persona = $this->crearPersona($request);
        //   // Establecer valores por defecto
        //     $persona->update([
        //         'limite_compras' => 5,       // Valor por defecto
        //         'periodo_control' => 30,      // Valor por defecto
        //         'restriccion_activa' => false // Valor por defecto
        //     ]);

            // $persona = Persona::create([
            //     'nombre' => $request->nombre,
            //     'nit' => $request->nit,
            //     'telefono' => $request->telefono,
            //     'fecha_nacimiento' => $request->fecha_nacimiento,
            //     'rol' => $request->rol,
            //     'limite_compras' => 5,       // Valor por defecto
            //     'periodo_control' => 30,      // Valor por defecto
            //     'restriccion_activa' => false // Valor por defecto
            // ]);

        $usuario=User::find($request->idUsuario);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Personas',
                'detalles' => "Se creó la persona: {$request->nombre}", //detalles especificos
                'fecha_hora' => now(),
            ]);
        // $persona = Persona::create([
        //     'nombre' => $request->nombre,
        //     'nit' => $request->nit,
        //     'DPI' => $request->dpi,
        //     'telefono' => $request->telefono,
        //     'fecha_nacimiento' => $request->fecha_nacimiento,
        //     'rol' => $request->rol,
        // ]);

        if ($persona->rol == 2) {
            FichaMedica::create([
                'persona_id' => $persona->id,
                'nombre' => $request->nombre,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'sexo' => $request->sexo,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'DPI' => $request->dpi,
                'habla_lengua' => $request->habla_lengua,
                'tipo_sangre' => $request->tipo_sangre,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'foto' => $request->foto,
                'departamento_id' => $request->departamento_id,
                'municipio_id' => $request->municipio_id,
                'diagnostico' => $request->diagnostico,
                'consulta_programada' => $request->consulta_programada,
                'receta_foto' => $request->receta_foto,
                'detalle_medico_id' => $request->detalle_medico_id,
            ]);
        }

        return redirect()->route('personas.index')->with('success', 'Persona registrada correctamente');
    }
    // Nuevos métodos para restricciones
    public function obtenerRestricciones(Persona $persona)
    {
        return response()->json([
            'id' => $persona->id,
            'limite_compras' => $persona->limite_compras,
            'periodo_control' => $persona->periodo_control,
            'restriccion_activa' => $persona->restriccion_activa,
            'compras_recientes' => $persona->comprasRecientes(),
            'tiene_restriccion' => $persona->tieneRestriccion()
        ]);
    }

    public function actualizarRestricciones(Request $request)
    {
        $request->validate([
            'id_persona' => 'required|exists:persona,id',
            'limite_compras' => 'nullable|integer|min:0',
            'periodo_control' => 'nullable|integer|min:1',
            'restriccion_activa' => 'boolean'
        ]);

        $persona = Persona::findOrFail($request->id_persona);
        $persona->limite_compras = $request->limite_compras;
        $persona->periodo_control = $request->periodo_control;
        $persona->restriccion_activa = $request->restriccion_activa;

        $persona->save();

        return response()->json(['success' => true, 'message' => 'Restricciones actualizadas']);
    }




    public function storeFromVentas(Request $request)
    {
        $persona = $this->crearPersona($request);

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

        // Si es paciente pero no tiene ficha médica, crearla con datos mínimos
        if ($persona->rol == 2 && $persona->fichasMedicas->isEmpty()) {
            FichaMedica::create([
                'persona_id' => $persona->id,
                'nombre' => $persona->nombre,
                'apellido_paterno' => '',
                'apellido_materno' => '',
                'sexo' => 'Hombre',
                'fecha_nacimiento' => $persona->fecha_nacimiento,
                'DPI' => $persona->DPI,
                'habla_lengua' => 'No',
                'tipo_sangre' => '',
                'direccion' => '',
                'departamento_id' => ' ',
                'municipio_id' => '',
                'telefono' => $persona->telefono,
            ]);

            // Recargar la relación para que ya contenga la ficha creada
            $persona->load('fichasMedicas');
        }

        return view('persona.show', compact('persona'));
    }


    public function edit(Persona $persona)
    {
        $fichaMedica = $persona->fichasMedicas()->first(); // Obtener ficha médica si existe
        $departamentos = Departamento::all();
        return view('persona.edit', compact('persona', 'fichaMedica','departamentos'));
    }
    public function update(Request $request, Persona $persona)
    {
        Log::info('Iniciando update de persona', $request->all());

        $rules = [
            'nombre' => 'required|string|max:255|unique:persona,nombre,' . $persona->id,
            'rol' => 'required|in:1,2',
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'nit' => 'nullable|string|max:10|unique:persona,nit,' . $persona->id,
        ];

        if ($request->rol == 2) {
            $rules += [
                'apellido_paterno' => 'required|string|max:100',
                'apellido_materno' => 'required|string|max:100',
                'sexo' => 'required|in:Hombre,Mujer',
                'dpi' => ['required', new Dpi()],
                'habla_lengua' => 'required|in:Sí,No',
                'tipo_sangre' => 'nullable|string|max:5',
                'direccion' => 'nullable|string|max:255',
                'departamento_id' => 'required|exists:departamentos,id',
                'municipio_id' => 'required|exists:municipios,id',
            ];
        }

        $validatedData = $request->validate($rules);

        DB::beginTransaction();
        try {
            $persona->nombre = $validatedData['nombre'];
            $persona->nit = $validatedData['nit'] ?? null;
            $persona->telefono = $validatedData['telefono'] ?? null;
            $persona->fecha_nacimiento = $validatedData['fecha_nacimiento'] ?? null;
            $persona->rol = $validatedData['rol'];
            $persona->save();

            Log::info('Datos básicos actualizados', $persona->toArray());

            // Si el rol es paciente, actualizar o crear ficha médica
            if ($validatedData['rol'] == 2) {
                // Si no tiene ficha médica, crearla con datos mínimos para evitar error
                if (!$persona->fichasMedicas()->exists()) {
                    FichaMedica::create([
                        'persona_id' => $persona->id,
                        'nombre' => $persona->nombre,
                        'apellido_paterno' => $validatedData['apellido_paterno'],
                        'apellido_materno' => $validatedData['apellido_materno'],
                        'sexo' => $validatedData['sexo'],
                        'fecha_nacimiento' => $persona->fecha_nacimiento,
                        'DPI' => $validatedData['dpi'],
                        'habla_lengua' => $validatedData['habla_lengua'],
                        'tipo_sangre' => $validatedData['tipo_sangre'] ?? null,
                        'direccion' => $validatedData['direccion'] ?? null,
                        'departamento_id' => $validatedData['departamento_id'],
                        'municipio_id' => $validatedData['municipio_id'],
                        'telefono' => $persona->telefono,
                    ]);
                } else {
                    // Actualizar ficha médica existente
                    $persona->fichasMedicas()->updateOrCreate(
                        ['persona_id' => $persona->id],
                        [
                            'apellido_paterno' => $validatedData['apellido_paterno'],
                            'apellido_materno' => $validatedData['apellido_materno'],
                            'sexo' => $validatedData['sexo'],
                            'DPI' => $validatedData['dpi'],
                            'habla_lengua' => $validatedData['habla_lengua'],
                            'tipo_sangre' => $validatedData['tipo_sangre'] ?? null,
                            'direccion' => $validatedData['direccion'] ?? null,
                            'departamento_id' => $validatedData['departamento_id'],
                            'municipio_id' => $validatedData['municipio_id'],
                        ]
                    );
                }

                Log::info('Ficha médica actualizada o creada para paciente');
            } else {
                // Opcional: si cambió a cliente, puedes borrar la ficha médica o dejarla intacta
                // $persona->fichasMedicas()->delete();
            }

            DB::commit();
            Log::info('Fin del proceso de actualización OK');

            return redirect()->route('personas.index')->with('success', 'Datos actualizados correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar persona', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }



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
    }

    public function cambiarEstado($id)
    {
        $persona = Persona::find($id);

        if ($persona) {
            $persona->estado = $persona->estado == 1 ? 2 : 1;
            $persona->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
