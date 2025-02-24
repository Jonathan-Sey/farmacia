<?php

namespace App\Http\Controllers\Sucursal;

use App\Http\Controllers\Controller;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $sucursales = Sucursal::select('id','nombre','ubicacion','estado','updated_at')
        ->where('estado', '!=', 0)
        ->get();
        return view('sucursal.index',['sucursales'=>$sucursales]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sucursal.create');
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
            'nombre'=>['required','string','max:35','unique:sucursal,nombre'],
            'ubicacion'=>'required|max:50',
            'estado'=>'integer',
        ]);

        Sucursal::create([
            'nombre'=>$request->nombre,
            'ubicacion'=>$request->ubicacion,
            'estado'=>1,
        ]);
        return redirect()->route('sucursales.index')->with('success', '¡Registro exitoso!');
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
    public function edit(Sucursal $sucursal)
    {
        return view('sucursal.edit', ['sucursal'=>$sucursal]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sucursal $sucursal)
    {
        $this->validate($request,[
            'nombre'=>['required','string','max:35','unique:sucursal,nombre,'. $sucursal->id],
            'ubicacion'=>'required|max:50',
            'estado'=>'integer',
        ]);

         // Verificación de cambios
         $datosActualizados = $request->only(['nombre', 'ubicacion']);
         $datosSinCambios = $sucursal->only(['nombre', 'ubicacion']);

         if ($datosActualizados == $datosSinCambios) {
             return redirect()->route('sucursales.index');
         }

         // Actualizar datos
         $sucursal->update($datosActualizados);

         return redirect()->route('sucursales.index')->with('success', '¡Sucursal actualizado!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Sucursal $sucursal){
          //dd($rol);
          $estado = $request->input('status', 0);
          if($estado == 0){
              $sucursal->update(['estado' => 0]);
              return redirect()->route('sucursales.index')->with('success','Sucursal eliminado con éxito!');
          }else{
              $sucursal->estado = $estado;
              $sucursal->save();
              return response()->json(['success' => true]);
          }
          return response()->json(['success'=> false]);
    }

    public function cambiarEstado($id)
    {
        $sucursal = Sucursal::find($id);

        if ($sucursal) {
            $sucursal->estado = $sucursal->estado == 1 ? 2 : 1; // Cambiar el estado (activo <-> inactivo)
            $sucursal->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

}
