<?php

namespace App\Http\Controllers\Sucursal;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Sucursal;
use App\Models\User;
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

        $sucursales = Sucursal::select('id','imagen','nombre','ubicacion','estado','updated_at')
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
        $imagenNombre = $request->imagen;

        $this->validate($request,[
            'nombre'=>['required','string','max:35','unique:sucursal,nombre'],
            'imagen'=> 'required',
            'ubicacion'=>'required|max:50',
            'estado'=>'integer',
        ]);
        Sucursal::create([
            'imagen'=>$imagenNombre,
            'nombre'=>$request->nombre,
            'ubicacion'=>$request->ubicacion,
            'estado'=>1,
        ]);

         //Bitacora
         $usuario=User::find($request->idUsuario);
         Bitacora::create([
                 'id_usuario' => $request->idUsuario,
                 'name_usuario' =>$usuario->name,
                 'accion' => 'Creación',
                 'tabla_afectada' => 'Sucursal',
                 'detalles' => "Se creó la sucursal: {$request->nombre}", //detalles especificos
                 'fecha_hora' => now(),
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
            'imagen'=>'nullable',
            'nombre'=>['required','string','max:35','unique:sucursal,nombre,'. $sucursal->id],
            'ubicacion'=>'required|max:50',
            'estado'=>'integer',
        ]);
        $datosActualizados = $request->only(['nombre', 'ubicacion']);

             // Manejo de la imagen
             if ($request->imagen && $request->imagen !== $sucursal->imagen) {
                // Eliminar la imagen anterior si existe
                if ($sucursal->imagen && file_exists(public_path('uploads/' . $sucursal->imagen))) {
                    unlink(public_path('uploads/' . $sucursal->imagen));
                }
                $datosActualizados['imagen'] = $request->imagen; // Actualizar con la nueva imagen
            } else {
                $datosActualizados['imagen'] = $sucursal->imagen; // Mantener la imagen anterior
            }

            $datosSinCambios = $sucursal->only(['imagen','nombre', 'ubicacion']);

         // Verificación de cambios
         if ($datosActualizados != $datosSinCambios) {
             // Actualizar datos
             $sucursal->update($datosActualizados);

                //Bitacora
                $usuario=User::find($request->idUsuario);
                Bitacora::create([
                        'id_usuario' => $request->idUsuario,
                        'name_usuario' =>$usuario->name,
                        'accion' => 'Actualización',
                        'tabla_afectada' => 'Sucursal',
                        'detalles' => "Se actualizo la sucursal: {$request->nombre}", //detalles especificos
                        'fecha_hora' => now(),
                ]);
            return redirect()->route('sucursales.index')->with('success', '¡Sucursal actualizado!');
            }
            return redirect()->route('sucursales.index');
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
