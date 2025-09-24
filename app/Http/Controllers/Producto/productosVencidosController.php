<?php

namespace App\Http\Controllers\Producto;

use App\Http\Controllers\Controller;
use App\Models\almacenVencido;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class productosVencidosController extends Controller
{
    public function index()
    {
        // Obtiene los productos vencidos con sus relaciones cargadas
        $productosVencidos = almacenVencido::with(['producto', 'sucursal'])->get();
        $sucursales = Sucursal::all();

        return view('producto.vencidos', compact('productosVencidos', 'sucursales'));
    }

    public function productosVencidosFecha(Request $request){
        //dd($request);
        $query = almacenVencido::with(['producto','sucursal']);

        if($request->filled('fecha')){
            $query->where('fecha_vencimiento', $request->fecha);
        }

        $productosVencidos = $query->get();

        return view('producto.vencidos', compact('productosVencidos'));
    }

    public function generateReportVencidos(Request $request)
    {
        $query = DB::table('almacen_vencidos as av')
            ->join('producto as p', 'av.id_producto', '=', 'p.id')
            ->join('sucursal as s', 'av.id_sucursal', '=', 's.id')
            ->leftJoin('users as u', 'av.id_user', '=', 'u.id')
            ->select(
                'av.id',
                'p.codigo as codigo_producto',
                'p.nombre as nombre_producto',
                'p.imagen',
                's.nombre as nombre_sucursal',
                'av.cantidad',
                'av.fecha_vencimiento',
                'p.tipo',
                'u.name as nombre_usuario',
                'av.created_at as fecha_registro'
            );

        // Filtrar por sucursal
        if ($request->has('sucursal') && $request->sucursal != '') {
            $query->where('av.id_sucursal', $request->sucursal);
        }

        // Filtrar por rango de fechas de vencimiento
        if ($request->has('fechaInicio') && $request->has('fechaFin')) {
            $query->whereBetween('av.fecha_vencimiento', [$request->fechaInicio, $request->fechaFin]);
        }

        // Filtrar por fecha específica de vencimiento
        if ($request->has('fecha') && $request->fecha != '') {
            $query->whereDate('av.fecha_vencimiento', $request->fecha);
        }

        // Solo productos activos
        $query->where('av.estado', 1);

        // Ordenar por fecha de vencimiento
        $productosVencidos = $query->orderBy('av.fecha_vencimiento', 'ASC')->get();

        return response()->json($productosVencidos);
    }
}
