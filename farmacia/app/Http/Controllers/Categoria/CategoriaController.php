<?php

namespace App\Http\Controllers\Categoria;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::select('id','nombre','estado','created_at')
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
            'descripcion'=>'nullable|max:100',
            'estado'=>'integer',
        ]);
        Categoria::create([
            'nombre'=> $request->nombre,
            'descripcion'=> $request->descripcion,
            'estado'=> 1,
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
            'descripcion' => 'nullable|max:100',
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
}
