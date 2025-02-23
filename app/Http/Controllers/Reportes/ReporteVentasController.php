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
}
