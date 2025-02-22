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


    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!auth('api')->check()) {
        //     return response()->json(['error' => 'Token inválido o no proporcionado'], 401);
        // }


        // $user = auth()->user();

        // if (!$user) {
        //     return response()->json(['error' => 'Usuario no autenticado'], 401);
        // }

        // $consultas = Consulta::with('persona:id,nombre', 'medico.usuario:id,name')->get();

        // return response()->json([
        //     'message' => 'Listado de consultas prueba',
        //     'user' => $user,
        //     'consultas' => $consultas
        // ]);


         $consultas = Consulta::with('persona:id,nombre','medico.usuario:id,name')->get();
         //return $consultas;
         return view('consulta.index',compact('consultas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $personas = Persona::all();
        $medicos = DetalleMedico::with('usuario')->get();
        //return $medicos;
        return view('consulta.create',compact('personas','medicos'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'asunto' => 'required',
            'id_persona' => 'required',
            'id_medico' => 'required',
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
        // Bitacora
        $usuario=User::find($request->idUsuario);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Consultas',
                'detalles' => "Se creó la consulta por: {$request->asunto}-Del paciente{$request->id_persona}", //detalles especificos
                'fecha_hora' => now(),
        ]);

        return redirect()->route('consultas.index')->with('success', 'Registro creado correctamente.');
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
    public function edit(Consulta $consulta)
    {
        $personas = Persona::all();
        $medicos = DetalleMedico::all();
        return view('consulta.edit',compact('consulta','personas','medicos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Consulta $consulta)
    {
        $this->validate($request,[
            'asunto' => 'required|max:35',
            'id_persona' => 'required',
            'id_medico' => 'required',
        ]);
         // Bitacora
         $usuario=User::find($request->idUsuario);
         Bitacora::create([
                 'id_usuario' => $request->idUsuario,
                 'name_usuario' =>$usuario->name,
                 'accion' => 'Actualización',
                 'tabla_afectada' => 'Consultas',
                 'detalles' => "Se creó la consulta por: {$request->asunto} -Del paciente{$request->id_persona}", //detalles especificos
                 'fecha_hora' => now(),
         ]);

        $datosActualizados = $request->only(['asunto','id_persona','id_medico','fecha_consulta','proxima_cita','detalle']);
        $datosSinActualizar = $consulta->only(['asunto','id_persona','id_medico','fecha_consulta','proxima_cita','detalle']);

        if($datosActualizados != $datosSinActualizar){
            $consulta->update($datosActualizados);
            return redirect()->route('consultas.index')->with('success','¡Consulta actualizado!');
        }
        return redirect()->route('consultas.index');
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
        if($estado == 0){
            $consulta->update(['estado' => 0]);
            return redirect()->route('consultas.index')->with('success','Consulta eliminado con éxito!');
        }else{
            $consulta->estado = $estado;
            $consulta->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success'=> false]);
    }
}
