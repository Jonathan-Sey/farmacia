<?php

namespace App\Http\Controllers\Categoria;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoriaController extends Controller
{

    //   public function __construct() {
    //       if (request()->is('api/*')) {
    //         $this->middleware('auth:api', ['except' => ['index','show']]);
    //            $this->middleware('role:2', ['only' => ['create', 'store', 'update', 'destroy']]);
    //       } else {
    //           $this->middleware('auth', ['except' => [ 'index','show','create']]);
    //           $this->middleware('role:2', ['only' => ['create', 'store', 'update', 'destroy']]);
    //       }
    //       Log::info('Middleware applied in CategoriaController', ['path' => request()->path()]);
    //   }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::select('id','nombre','descripcion','estado','created_at')
        ->where('estado', '!=', 0)
        ->get();
        return view('categorias.index',['categorias'=>$categorias]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categorias.create');
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
            'nombre'=>['required','string','max:35','unique:categoria,nombre'],
            'descripcion'=>'required|max:100',
            'estado'=>'integer',
        ]);
        Categoria::create([
            'nombre'=> $request->nombre,
            'descripcion'=> $request->descripcion,
            'estado'=> 1,
        ]);

        $usuario=User::find($request->idUsuario);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Categorías',
                'detalles' => "Se creó la categoria: {$request->nombre}", //detalles especificos
                'fecha_hora' => now(),
        ]);
            return redirect()->route('categorias.index')->with('success', '¡Registro exitoso!');
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
    public function edit(Categoria $categoria)
    {

        return view('categorias.edit', ['categoria' => $categoria]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
        // Validaciones de los datos
        $this->validate($request, [
            'nombre' => ['required', 'string', 'max:35', 'unique:categoria,nombre,' . $categoria->id],
            'descripcion' => 'required|max:100',
            'estado' => 'integer',
        ]);

        // Verificación de cambios
        $datosActualizados = $request->only(['nombre', 'descripcion']);
        $datosSinCambios = $categoria->only(['nombre', 'descripcion']);

        if ($datosActualizados == $datosSinCambios) {
            return redirect()->route('categorias.index');
        }

        // Actualizar datos
        $categoria->update($datosActualizados);

        $usuario=User::find($request->idUsuario);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Actualización',
                'tabla_afectada' => 'Categorías',
                'detalles' => "Se actualizo la categoria: {$request->nombre}", //detalles especificos
                'fecha_hora' => now(),
        ]);

        return redirect()->route('categorias.index')->with('success', '¡Categoria actualizado!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Categoria $categoria)
    {
               //dd($rol);
               $estado = $request->input('status', 0);
               if($estado == 0){
                   $categoria->update(['estado' => 0]);
                   return redirect()->route('categorias.index')->with('success','Categoria eliminado con éxito!');
               }else{
                   $categoria->estado = $estado;
                   $categoria->save();
                   return response()->json(['success' => true]);
               }
               return response()->json(['success'=> false]);
    }

    public function cambiarEstado($id)
    {
        $categoria = Categoria::find($id);

        if ($categoria) {
            $categoria->estado = $categoria->estado == 1 ? 2 : 1; // Cambiar el estado (activo <-> inactivo)
            $categoria->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
