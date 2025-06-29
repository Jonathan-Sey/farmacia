<?php

namespace App\Http\Controllers\Sucursal;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImagenController;
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

        $sucursales = Sucursal::select('id','imagen','nombre','codigo_sucursal','ubicacion','telefono','email','encargado','google_maps_link','estado','updated_at')
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
        $usuarios = User::select('id', 'name')->get();
        return view('sucursal.create',compact('usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$imagenNombre = $request->imagen;
        //dd($request);

        $this->validate($request,[
            'nombre'=>['required','string','max:35','unique:sucursal,nombre'],
            'codigo_sucursal'=> ['required','string','max:50','unique:sucursal,codigo_sucursal'],
            'imagen'=> 'required',
            'ubicacion'=>'required|max:200',
            'telefono'=>'required|max:10',
            'email'=>'required|max:50',
            'encargado' => 'required|max:100',
            'estado'=>'integer',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'google_maps_link' => 'required|url'
        ]);

          // Mover la imagen de temp a definitivo
        $imagenController = new ImagenController();
        $imagenMovida = $imagenController->moverDefinitiva($request->imagen);

        if (!$imagenMovida) {
            return back()->with('error', 'No se pudo guardar la imagen');
        }

        $sucursal =Sucursal::create([
            'imagen'=>$request->imagen,
            'nombre'=>$request->nombre,
            'codigo_sucursal'=>$request->codigo_sucursal,
            'ubicacion'=>$request->ubicacion,
            'telefono'=>$request->telefono,
            'email'=>$request->email,
            'encargado' =>$request->encargado,
            'estado'=>1,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'google_maps_link' => $request->google_maps_link
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
        $usuarios = User::select('id', 'name')->get();
        return view('sucursal.edit', ['sucursal'=>$sucursal], compact('usuarios'));
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
        $validated = $request->validate([
            'imagen' => 'nullable',
            'nombre' => ['required','string','max:35','unique:sucursal,nombre,'. $sucursal->id],
            'codigo_sucursal' => ['required','string','max:50','unique:sucursal,codigo_sucursal,' . $sucursal->id],
            'ubicacion' => 'required|max:200',
            'telefono' => 'required|max:10',
            'email' => 'required|max:50|email',
            'encargado' => 'required|max:100',
            'estado' => 'integer',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'google_maps_link' => 'required|url'
        ]);

        // Manejo de imagen
        $imagenOriginal = $sucursal->imagen;

        if ($request->has('eliminar_imagen') && $request->eliminar_imagen == '1') {
            if ($imagenOriginal && file_exists(public_path('uploads/' . $imagenOriginal))) {
                unlink(public_path('uploads/' . $imagenOriginal));
            }
            $validated['imagen'] = null;
        }
        elseif ($request->imagen && $request->imagen !== $imagenOriginal) {
            $imagenController = new ImagenController();
            $imagenMovida = $imagenController->moverDefinitiva($request->imagen);

            if (!$imagenMovida) {
                return back()->with('error', 'No se pudo guardar la nueva imagen');
            }

            if ($imagenOriginal && file_exists(public_path('uploads/' . $imagenOriginal))) {
                unlink(public_path('uploads/' . $imagenOriginal));
            }
        }
        else {
            $validated['imagen'] = $imagenOriginal;
        }

        // Actualizar todos los campos validados
        $sucursal->update($validated);

        //Bitacora
        $usuario = User::find($request->idUsuario);
        Bitacora::create([
            'id_usuario' => $request->idUsuario,
            'name_usuario' => $usuario->name,
            'accion' => 'Actualización',
            'tabla_afectada' => 'Sucursal',
            'detalles' => "Se actualizó la sucursal: {$request->nombre}",
            'fecha_hora' => now(),
        ]);

        return redirect()->route('sucursales.index')->with('success', '¡Sucursal actualizada!');
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
