<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Venta;
use Illuminate\Http\Request;

class ReporteVentasController extends Controller
{
    public function index()  {
        
      //  dd(auth()->user());
        // pruebas con fechas del año actual
        $año = date('Y');
        $mes = date('m');

       $NumeroVentas = Venta::count();
       $nombreSucursales = Sucursal::all();


       // datos de ventas, esto para la tabla
       $ventas = Venta::with([
           'persona:id,nombre',
           'productos:id,nombre',
           'sucursal:id,nombre,ubicacion',
           ])->latest()->get();

       // fitrar fechas por el campo fecha_venta
       $ventasFiltro = Venta::with([
           'persona:id,nombre',
           'productos:id,nombre',
           'sucursal:id,nombre,ubicacion',
       ])->whereYear('fecha_venta', $año) //filtro por año
       ->whereMonth('fecha_venta', $mes)// filto pod mes
       ->latest()
       ->get();

       // generar los dias del mes
       $numDias = cal_days_in_month(CAL_GREGORIAN, $mes,$año);// total de dias de ese mes
       $diasMes =  range(1, $numDias);//cantidad de dias de ese mes

       $ventasPorDia = [];
       $totalGeneral = 0;

       foreach ($diasMes as $dia){
           $ventasDelDia = $ventasFiltro->filter(function ($venta) use ($año,$mes,$dia){
                return \Carbon\Carbon::parse($venta->fecha_venta)->format('Y-m-d') === "$año-" . str_pad($mes,2, '0', STR_PAD_LEFT). "-" . str_pad($dia, 2, '0', STR_PAD_LEFT);
           });
           $totalDia = $ventasDelDia->sum('total');
           $ventasPorDia[] = $totalDia;
           $totalGeneral += $totalDia;
       }

       //return $productos;

       return view('reportes.ventas',compact('NumeroVentas','ventas','ventasFiltro','ventasPorDia','totalGeneral', 'diasMes', 'año','mes','nombreSucursales'));
    
    }

    public function filtrarPorFecha(){
        $sucursales = Sucursal::all();
        return view('reportes.venstasFecha',compact('sucursales'));
      }

      public function generateReport(Request $request)
    {
        $query = Venta::with(['detalles.producto', 'sucursal', 'usuario', 'persona']) // Se agregan las relaciones necesarias
        ->select(
            'venta.id as venta_id',
            'venta.fecha_venta',
            'venta.total',
            'venta.impuesto',
            'venta.id_sucursal',
            'venta.id_usuario',
            'venta.id_persona',
            'venta.estado as estado_venta'
        );
    
    // Filtrar por día específico
    if ($request->has('fecha')) {
        $query->whereDate('fecha_venta', $request->fecha);
    }
    
    // Filtrar por mes y año
    if ($request->has('mes')) {
        $query->whereMonth('fecha_venta', date('m', strtotime($request->mes)))
              ->whereYear('fecha_venta', date('Y', strtotime($request->mes)));
    }
    
    if ($request->has('año')) {
        $query->whereYear('fecha_venta', $request->año);
    }
    
    // Filtrar por rango de fechas
    if ($request->has('fechaInicio') && $request->has('fechaFin')) {
        $query->whereBetween('fecha_venta', [$request->fechaInicio, $request->fechaFin]);
    }
    
    // Obtener las ventas filtradas con los detalles y datos adicionales
    $ventas = $query->get()->map(function ($venta) {
        return [
            'venta_id' => $venta->venta_id,
            'fecha_venta' => $venta->fecha_venta,
            'total' => $venta->total,
            'impuesto' => $venta->impuesto,
            'id_sucursal' => $venta->id_sucursal,
            'nombre_sucursal' => $venta->sucursal->nombre ?? 'No especificado', // Se obtiene el nombre de la sucursal
            'id_usuario' => $venta->id_usuario,
            'nombre_usuario' => $venta->usuario->name ?? 'No especificado', // Se obtiene el nombre del usuario
            'id_persona' => $venta->id_persona,
            'nombre_persona' => $venta->persona->nombre ?? 'No especificado', // Se obtiene el nombre de la persona
            'estado_venta' => $venta->estado_venta,
            'detalles' => $venta->detalles->map(function ($detalle) {
                return [
                    'id_detalle' => $detalle->id,
                    'id_producto' => $detalle->id_producto,
                    'nombre_producto' => $detalle->producto->nombre ?? 'No especificado', // Se obtiene el nombre del producto
                    'cantidad' => $detalle->cantidad,
                    'precio' => $detalle->precio,
                    'subtotal' => $detalle->cantidad * $detalle->precio,
                    'estado_detalle' => $detalle->estado,
                ];
            }),
        ];
    });
    
    return response()->json($ventas);
    
    }
}
