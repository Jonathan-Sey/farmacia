<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\DetalleMedico;
use App\Models\User;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $medicos = DetalleMedico::with('usuario:id,name')
         ->select('id','id_usuario','especialidad','estado','numero_colegiado')
         ->get();

        return view('medico.index',compact('medicos'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuarios = User::all();
        return view('medico.create',compact('usuarios'));
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
            'id_usuario' => 'required',
            'especialidad'=>['required','string','max:75'],
            'numero_colegiado'=>['required','string','max:10'],
            'estado'=>'integer',
        ]);

        DetalleMedico::create([
            'id_usuario' => $request->id_usuario,
            'especialidad'=> $request->especialidad,
            'numero_colegiado'=> $request->numero_colegiado,
            'estado'=> 1,
        ]);

        return redirect()->route('medicos.index')->with('success','¡Registro exitoso!');
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
    public function edit(DetalleMedico $medico)
    {
        $usuarios = User::all();
        return view('medico.edit',compact('medico','usuarios'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleMedico $medico)
    {
        $this->validate($request,[
            'id_usuario' => 'required',
            'especialidad'=>['required','string','max:75'],
            'numero_colegiado'=>['required','string','max:10'],
            'estado'=>'integer',
        ]);

        $datosActualizados = $request->only(['id_usuario','especialidad','numero_colegiado']);
        $datosSinAcrualizar = $medico->only(['id_usuario','especialidad','numero_colegiado']);

        if($datosActualizados != $datosSinAcrualizar){
            $medico->update($datosActualizados);
            return redirect()->route('medicos.index')->with('success','¡Medico actualizado!');
        }
            return redirect()->route('medicos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, DetalleMedico $medico)
    {
        {
            $estado = $request->input('status', 0);
            if($estado == 0){
                $medico->update(['estado' => 0]);
                return redirect()->route('medicos.index')->with('success','Medico eliminado con éxito!');
            }else{
                $medico->estado = $estado;
                $medico->save();
                return response()->json(['success' => true]);
            }
            return response()->json(['success'=> false]);
        }
    }
}
