<?php

namespace App\Http\Controllers\Producto;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $productos = Producto::with('categoria:id,nombre')
        ->select('id','codigo','nombre','nombre','precio_venta','estado','id_categoria')
        ->get();
        return view('producto.index',['productos'=>$productos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::all(['id', 'nombre']);
        return view('producto.create', compact('categorias'));
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
            // 'codigo'=>['nullable'],
            'id_categoria'=>'required',
            'nombre'=>['required','string','max:50'],
            'descripcion'=>['max:100'],
            'precio_venta'=>'integer|required',
            'fecha_caducidad'=>'required',
            'estado'=>'integer',
        ]);
        // generacion de codigo
        $codigo = 'P' . $request->id_categoria . '-' . (Producto::max('id') + 1);

        $producto = Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_venta' => $request->precio_venta,
            'fecha_caducidad' => $request->fecha_caducidad,
            'id_categoria' => $request->id_categoria,
            'estado' => 1,
            'codigo' => $codigo, // asignamos el codigo generado
        ]);


        $producto->update(['codigo'=>$codigo]);
        return redirect()->route('productos.index')->with('success','¡Registro exitoso!');

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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
