<?php

namespace App\Http\Controllers\Consulta;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Consulta;
use App\Models\DetalleMedico;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;

class consultaController extends Controller
{



    public function index()
    {
        $consultas = Consulta::with('persona:id,nombre', 'medico.usuario:id,name')->get();
        //return $consultas;
        return view('consulta.index', compact('consultas'));
    }

    public function indexApi()
    {
        $consultas = Consulta::with('persona:id,nombre', 'medico.usuario:id,name')->get();
        return response()->json($consultas);
    }


    public function create()
    {
        $personas = Persona::all();
        $medicos = DetalleMedico::with('usuario')->get();
        //return $medicos;
        return view('consulta.create', compact('personas', 'medicos'));
    }


    public function store(Request $request)
    {
        dd($request->all());
        $this->validate($request, [
            'asunto' => 'required',
            'id_persona' => 'required',
            'id_medico' => 'required',
            'detalle' => 'required',
        ]);


        Consulta::create([
            'asunto' => $request->asunto,
            'id_persona' => $request->id_persona,
            'id_medico' => $request->id_medico,
            'fecha_consulta' => $request->fecha_consulta,
            'proxima_cita' => $request->proxima_cita,
            'detalle' => $request->detalle,
            'estado' => 1,

        ]);
        $persona = Persona::find($request->id_persona);
        $usuario = User::find($request->idUsuario);
        Bitacora::create([
            'id_usuario' => $request->idUsuario,
            'name_usuario' => $usuario->name,
            'accion' => 'Creación',
            'tabla_afectada' => 'Consultas',
            'detalles' => "Se creó la consulta por: {$request->asunto} - del paciente: {$persona->nombre}",
            'fecha_hora' => now(),
        ]);
        return redirect()->route('consultas.index')->with('success', 'Registro creado correctamente.');
    }

    public function storeApi(Request $request)
    {
        $this->validate($request, [
            'asunto' => 'required',
            'id_persona' => 'required',
            'id_medico' => 'required',
            'detalle' => 'required',
        ]);


        Consulta::create([
            'asunto' => $request->asunto,
            'id_persona' => $request->id_persona,
            'id_medico' => $request->id_medico,
            'fecha_consulta' => $request->fecha_consulta,
            'proxima_cita' => $request->proxima_cita,
            'detalle' => $request->detalle,
            'estado' => 1,

        ]);
        $persona = Persona::find($request->id_persona);
        $usuario = User::find($request->idUsuario);
        Bitacora::create([
            'id_usuario' => $request->idUsuario,
            'name_usuario' => $usuario->name,
            'accion' => 'Creación',
            'tabla_afectada' => 'Consultas',
            'detalles' => "Se creó la consulta por: {$request->asunto} - del paciente: {$persona->nombre}",
            'fecha_hora' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Consulta creada correctamente.']);
    }


    public function show($id)
    {
        $consulta = Consulta::with('persona:id,nombre', 'medico.usuario:id,name')->find($id);
        if (!$consulta) {
            return response()->json(['success' => false, 'message' => 'Consulta no encontrada.']);
        }
        return response()->json(['success' => true, 'data' => $consulta]);
    }


    public function edit(Consulta $consulta)
    {
        $personas = Persona::all();
        $medicos = DetalleMedico::all();
        return view('consulta.edit', compact('consulta', 'personas', 'medicos'));
    }


    public function update(Request $request, Consulta $consulta)
    {

     
        $this->validate($request, [
            'asunto' => 'required|max:35',
            'id_persona' => 'required',
            'id_medico' => 'required',
            'detalle' => 'required',
        ]);


        $datosActualizados = $request->only(['asunto', 'id_persona', 'id_medico', 'fecha_consulta', 'proxima_cita', 'detalle']);
        $datosSinActualizar = $consulta->only(['asunto', 'id_persona', 'id_medico', 'fecha_consulta', 'proxima_cita', 'detalle']);

        if ($datosActualizados != $datosSinActualizar) {
            $consulta->update($datosActualizados);

            // Obtener el usuario que hace la acción (mejor usar Auth si aplica)
            $persona = Persona::find($request->id_persona);
            $usuario = User::find($request->idUsuario);
            Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' => $usuario->name,
                'accion' => 'Actualización',
                'tabla_afectada' => 'Consultas',
                'detalles' => "Se actualizo la consulta por: {$request->asunto} - del paciente: {$persona->nombre}",
                'fecha_hora' => now(),
            ]);

            return redirect()->route('consultas.index')->with('success', '¡Consulta actualizada!');
        }

        return redirect()->route('consultas.index')->with('info', 'No se realizaron cambios.');
    }

    public function updateApi(Request $request, Consulta $consulta)
    {
        $this->validate($request, [
            'asunto' => 'required|max:35',
            'id_persona' => 'required',
            'id_medico' => 'required',
            'detalle' => 'required',
        ]);


        $datosActualizados = $request->only(['asunto', 'id_persona', 'id_medico', 'fecha_consulta', 'proxima_cita', 'detalle']);
        $datosSinActualizar = $consulta->only(['asunto', 'id_persona', 'id_medico', 'fecha_consulta', 'proxima_cita', 'detalle']);

        if ($datosActualizados != $datosSinActualizar) {
            $consulta->update($datosActualizados);

            // Obtener el usuario que hace la acción (mejor usar Auth si aplica)
            $persona = Persona::find($request->id_persona);
            $usuario = User::find($request->idUsuario);
            Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' => $usuario->name,
                'accion' => 'Actualización',
                'tabla_afectada' => 'Consultas',
                'detalles' => "Se actualizo la consulta por: {$request->asunto} - del paciente: {$persona->nombre}",
                'fecha_hora' => now(),
            ]);

            
            return response()->json(['success' => true, 'message' => 'Consulta actualizada correctamente.']);
        }

        return response()->json(['success' => false, 'message' => 'No se realizaron cambios.']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Consulta $consulta)
    {
        $estado = $request->input('status', 0);
        if ($estado == 0) {
            $consulta->update(['estado' => 0]);
            return redirect()->route('consultas.index')->with('success', 'Consulta eliminado con éxito!');
        } else {
            $consulta->estado = $estado;
            $consulta->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function cambiarEstado($id)
    {
        $consulta = Consulta::find($id);

        if ($consulta) {
            $consulta->estado = $consulta->estado == 1 ? 2 : 1; // Cambiar el estado (activo <-> inactivo)
            $consulta->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
