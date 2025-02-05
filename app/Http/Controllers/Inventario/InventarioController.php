<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //** Prumera fase de prueba */
        // $inventario = Inventario::with('producto','sucursal','lote')
        // ->latest()
        // ->get();
        //return $inventario;

        //* segunda fase de prueba agrupado por loes*/
        $inventario = Inventario::select(
            'inventario.id_producto',
            'inventario.id_sucursal',
            'producto.nombre as producto',
            'sucursal.ubicacion as sucursal',

            DB::raw('COUNT(lote.id) as cantidad_lotes'),
            DB::raw('SUM(lote.cantidad) as cantidad_total')
        )
        ->join('producto', 'inventario.id_producto', '=', 'producto.id')
        ->join('sucursal', 'inventario.id_sucursal', '=', 'sucursal.id')
        ->join('lote', 'inventario.id_lote', '=', 'lote.id')
        ->groupBy('inventario.id_producto', 'inventario.id_sucursal') // agrupado por lote
        //->groupBy('inventario.id_inventario', 'producto.nombre', 'sucursal.ubicacion')
        //->groupBy('inventario.id_producto', 'inventario.id_sucursal')
        // ->groupBy(
        //     'inventario.id_inventario',
        //     'producto.nombre',
        //     'sucursal.ubicacion',
        //     'inventario.id_producto',
        //     'inventario.id_sucursal',
        //     )
        ->get();

        //return $inventario;
        return view('Inventario.index',compact('inventario'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lotes = Inventario::with(['lote', 'producto', 'sucursal'])
        ->where('id_inventario', $id)
        ->get();
        // $inventario = Inventario::with('lote', 'producto', 'sucursal')->findOrFail($inventario->id);
        // return view('Inventario.show',compact('inventario'));
         return view('Inventario.show',compact('lotes'));
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
