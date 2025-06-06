<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Inventario;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\Requisicion;
use Carbon\Carbon;
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
        // $inventario = Inventario::with('producto','bodegas','lote')
        // ->latest()
        // ->get();
        //return $inventario; 
        //se compruba que no exista producto vencido en el inventario
        // Obtener productos vencidos del lote
        $productosVencidos = Lote::where('fecha_vencimiento', '<', Carbon::now())->get();

        // Obtener productos vencidos del almacén
        $almacenVencido = Almacen::where('fecha_vencimiento', '<', Carbon::now())->get();

        if ($productosVencidos->isNotEmpty() || $almacenVencido->isNotEmpty()) {

            foreach ($productosVencidos as $producto) {
                // Obtener todas las requisiciones relacionadas con este lote
                $requisiciones = Requisicion::where('id_lote', $producto->id)->get();

                // Eliminar todas las requisiciones asociadas
                foreach ($requisiciones as $requisicion) {
                    $requisicion->delete();
                }

                // Insertar en la tabla correcta (revisa que el nombre esté bien escrito)
                DB::table('producto__vecidos')->insert([
                    "id_producto" => $producto->id_producto,
                    'fecha_vencimiento' => $producto->fecha_vencimiento,
                    "id_compra" => $producto->id_compra,
                    'cantidad' => $producto->cantidad,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Eliminar el producto de la tabla original
                $producto->delete();
            }

            // borrar productos vencidos de la tabla almacen

            $almacenVencido =  Almacen::where('fecha_vencimiento', '<', Carbon::now())->get();

            foreach ($almacenVencido as $almacen) {

                // Insertar en la tabla correcta (revisa que el nombre esté bien escrito)
                DB::table('almacen_vencidos')->insert([
                    'id_sucursal' => $almacen->id_sucursal,
                    "id_producto" => $almacen->id_producto,
                    'cantidad' => $almacen->cantidad,
                    'fecha_vencimiento' => $almacen->fecha_vencimiento,
                    'id_user' => $almacen->id_user,
                    'estado' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Eliminar el producto de la tabla original
                $almacen->delete();
            }

            // notificar al usuario
            DB::table('notificaciones')->insert([
                'tipo' => 'producto vencidos',
                'mensaje' => 'Se han encontrado productos vencidos y se han movido a la tabla correspondiente.',
                'leido' => false,
                'accion' => 'ver productos vencidos',
                'url' => '/productos-vencidos',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        //* segunda fase de prueba agrupado por loes*/
        $inventario = Inventario::select(
            'inventario.id_producto',
            'inventario.id_bodega', // Cambiado de id_sucursal
            'producto.nombre as producto',
            'bodegas.nombre as bodegas', // Cambiado de sucursal
            DB::raw('COUNT(lote.id) as cantidad_lotes'),
            DB::raw('SUM(inventario.cantidad) as cantidad_total')
        )
            ->join('producto', 'inventario.id_producto', '=', 'producto.id')
            ->join('bodegas', 'inventario.id_bodega', '=', 'bodegas.id') // Cambiado de sucursal
            ->join('lote', 'inventario.id_lote', '=', 'lote.id')
            ->where('inventario.cantidad', '>', 0)
            ->groupBy('inventario.id_producto', 'inventario.id_bodega', 'producto.nombre', 'bodegas.nombre')
            ->get();

        // Inventario agotado
        $inventarioAgotado = Inventario::select(
            'inventario.id_producto',
            'inventario.id_bodega', // Cambiado
            'producto.nombre as producto',
            'bodegas.nombre as bodegas', // Cambiado
            DB::raw('COUNT(lote.id) as cantidad_lotes'),
            DB::raw('SUM(inventario.cantidad) as cantidad_total')
        )
            ->join('producto', 'inventario.id_producto', '=', 'producto.id')
            ->join('bodegas', 'inventario.id_bodega', '=', 'bodegas.id') // Cambiado
            ->join('lote', 'inventario.id_lote', '=', 'lote.id')
            ->where('inventario.cantidad', '=', 0)
            ->groupBy('inventario.id_producto', 'inventario.id_bodega', 'producto.nombre', 'bodegas.nombre')
            ->get();

        // Productos próximos a vencer
        $productosProximosAVencer = Inventario::select(
            'inventario.id_producto',
            'inventario.id_bodega', // Cambiado
            'producto.nombre as producto',
            'bodegas.nombre as bodegas', // Cambiado
            'lote.fecha_vencimiento',
            DB::raw('SUM(inventario.cantidad) as cantidad_total')
        )
            ->join('producto', 'inventario.id_producto', '=', 'producto.id')
            ->join('bodegas', 'inventario.id_bodega', '=', 'bodegas.id') // Cambiado
            ->join('lote', 'inventario.id_lote', '=', 'lote.id')
            ->where('inventario.cantidad', '>', 0)
            ->where('lote.fecha_vencimiento', '<=', now()->addDays(30))
            ->groupBy('inventario.id_producto', 'inventario.id_bodega', 'producto.nombre', 'bodegas.nombre', 'lote.fecha_vencimiento')
            ->orderBy('lote.fecha_vencimiento', 'asc')
            ->get();

        return view('Inventario.index', compact('inventario', 'inventarioAgotado', 'productosProximosAVencer'));





        //return $inventario;
        //return view('Inventario.index',compact('inventario','inventarioAgotado','productosProximosAVencer'));
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
    public function show($idProducto, $idBodega)

    {

        // Obtener los lotes originales
        $lotesOriginales = Lote::where('id_producto', $idProducto)->get();

        $lotesDisponibles  = Inventario::with(['lote', 'producto', 'bodega'])
            ->where('id_producto', $idProducto)
            ->where('id_bodega', $idBodega)
            ->get();
        // $inventario = Inventario::with('lote', 'producto', 'bodegas')->findOrFail($inventario->id);
        // return view('Inventario.show',compact('inventario'));
        return view('Inventario.show', compact('lotesDisponibles', 'lotesOriginales'));
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
