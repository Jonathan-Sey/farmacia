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

        $sucursales = Sucursal::select('id','imagen','nombre','codigo_sucursal','ubicacion','telefono','email','estado','updated_at')
        ->where('estado', '!=', 0)
        ->paginate(4);
        return view('sucursal.index',['sucursales'=>$sucursales]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $usuarios=User::Where('estado',1)->get();
       return view('sucursal.create', compact('usuarios'));
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
            'codigo_sucursal'=> ['required','string','max:50','unique:sucursal,codigo_sucursal'],
            'id_usuario' => 'nullable|array',
            'id_usuario.*' => 'integer|exists:users,id', //Valida que cada valor dentro del array sea un ID de usuario válido en la tabla users
            'imagen'=> 'required',
            'ubicacion'=>'required|max:50',
            'telefono'=>'required|max:10',
            'email'=>'required|max:50',
            'estado'=>'integer',
        ]);
        $sucursal =Sucursal::create([
            'imagen'=>$imagenNombre,
            'nombre'=>$request->nombre,
            'codigo_sucursal'=>$request->codigo_sucursal,
            'ubicacion'=>$request->ubicacion,
            'telefono'=>$request->telefono,
            'email'=>$request->email,
            'estado'=>1,
        ]);

        if ($request->has('id_usuario')) {
            $sucursal->usuarios()->attach($request->id_usuario);
        }

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
        //mostrar usuarios activos
        $usuarios = User::where('estado', 1)->get();  
        // obtener los IDs de los usuarios asignados a esta sucursal
        $usuariosSeleccionados = $sucursal->usuarios->pluck('id')->toArray();
        return view('sucursal.edit', compact('sucursal', 'usuarios', 'usuariosSeleccionados'));
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
           'codigo_sucursal'=> ['required','string','max:50','unique:sucursal,codigo_sucursal,' . $sucursal->id],
            'id_usuario' => 'nullable|array',
            'id_usuario.*' => 'integer|exists:users,id',
            'ubicacion'=>'required|max:50',
            'telefono'=>'required|max:10',
            'email'=>'required|max:50',
            'estado'=>'integer',
        ]);
        $datosActualizados = $request->only(['nombre', 'ubicacion','codigo_sucursal','id_usuario','telefono','email']);

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

            $datosSinCambios = $sucursal->only(['imagen','nombre', 'ubicacion','codigo_sucursal','id_usuario','telefono','email']);

         // Verificación de cambios
         if ($datosActualizados != $datosSinCambios) {
             // Actualizar datos
             $sucursal->update($datosActualizados);

             if($request->has('id_usuario')) {
                $sucursal->usuarios()->sync($request->id_usuario); // Sincronizar usuarios
             }
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
