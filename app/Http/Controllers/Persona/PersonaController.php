<?php

namespace App\Http\Controllers\Persona;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $personas = Persona::select('id','nombre','nit','rol','telefono','estado')
        ->where('estado','!=','0')
        ->get();
        return view('persona.index',compact('personas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('persona.create');
    }


    protected function crearPersona(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:45',
            'nit' => 'max:10',
            'telefono' => 'max:20',
        ]);

        $rol = $request->has('rol') ? 2 : 1; // Rol 1 para cliente, 2 para paciente

        return Persona::create([
            'nombre' => $request->nombre,
            'nit' => $request->nit,
            'rol' => $rol,
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $persona = $this->crearPersona($request);

        $usuario=User::find($request->idUsuario);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Personas',
                'detalles' => "Se creó la persona: {$request->nombre}", //detalles especificos
                'fecha_hora' => now(),
        ]);


        return redirect()->route('personas.index')->with('success', 'Registro creado correctamente.');

        // $this -> validate($request,[
        //     'nombre' => 'required|string|max:45',
        //     'nit' => 'max:10',
        //     'telefono' => 'max:20',

        // ]);
        // $rol = $request->has('rol') ? 2 : 1;

        // Persona::create([
        //     'nombre' => $request->nombre,
        //     'nit' => $request->nit,
        //     'rol' => $rol,
        //     'telefono' => $request->telefono,
        //    'fecha_nacimiento' => $request->fecha_nacimiento,
        // ]);

        // return redirect()->route('personas.index')->with('success', 'Registro creado correctamente.');
        // return response()->json([
        //     'success' => true,
        //     'persona' => [
        //         'id' => $persona->id,
        //         'nombre' => $persona->nombre,
        //     ],
        // ]);
    }
    public function storeFromVentas(Request $request)
    {
        $persona = $this->crearPersona($request);

        return response()->json([
            'success' => true,
            'persona' => [
                'id' => $persona->id,
                'nombre' => $persona->nombre,
            ],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Persona $persona)
    {
        return view('persona.edit',compact('persona'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Persona $persona)
    {
        // $this->validate($request,[
        //     'nombre' => 'required|string|max:45',
        //     'nit' => 'string|max:10',
        //     'telefono' => 'string|max:20',
        //     'fecha_nacimiento'=>'date',
        // ]);

        $this -> validate($request,[
            'nombre' => 'required|string|max:45',
            'nit' => 'max:10',
            'telefono' => 'max:20',

        ]);

        $datosActualizados = $request->only(['nombre','nit','telefono','rol','fecha_nacimiento']);
        $datosSinCambio = $persona->only(['nombre','nit','telefono','rol','fecha_nacimiento']);

        // Validar el cambio de rol
        $nuevoRol = $request->has('rol') ? 2 : 1;
        if ($persona->rol != $nuevoRol) {
            // Permitir cambio de cliente a paciente (1 a 2)
            if ($persona->rol == 1 && $nuevoRol == 2) {
                $datosActualizados['rol'] = 2;
            }
            // No permitir cambio de paciente a cliente (2 a 1)
            elseif ($persona->rol == 2 && $nuevoRol == 1)
            {
                return redirect()->route('personas.edit', $persona->id)
                ->withErrors(['rol' => 'No se permite cambiar el rol de paciente a cliente.']);
            }
        }

        // Actualizar el rol
        $datosActualizados['rol'] = $nuevoRol;

        if($datosActualizados != $datosSinCambio){
            $persona->update($datosActualizados);
            return redirect()->route('personas.index')->with('success','¡Persona Actualizado!');
        }

        $usuario=User::find($request->idUsuario);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Actualización',
                'tabla_afectada' => 'Personas',
                'detalles' => "Se actualizo la persona: {$request->nombre}", //detalles especificos
                'fecha_hora' => now(),
        ]);
        return redirect()->route('personas.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Persona $persona)
    {
        $estado = $request->input('status', 0);
        if($estado == 0){
            $persona->update(['estado' => 0]);
            return redirect()->route('personas.index')->with('success','Persona eliminado con éxito!');
        }else{
            $persona->estado = $estado;
            $persona->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success'=> false]);
    }

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
