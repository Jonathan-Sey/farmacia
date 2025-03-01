<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleMedico;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Venta;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class Dashboard extends Controller
{

    public function index(Request $request){

      //  dd(auth()->user());
        // pruebas con fechas del año actual
         $año = date('Y');
         $mes = date('m');

        $productos = Producto::where('tipo',1)->count();// conteo de productos
        $sucursales = Sucursal::count();
        $compras = Compra::count();
        $NumeroVentas = Venta::count();
        $servicios = Producto::where('tipo',2)->count();//conteo de servicios
        $medicos = DetalleMedico::count();
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

        return view('dashboard.index',compact('productos','sucursales','compras','NumeroVentas','servicios','medicos','ventas','ventasFiltro','ventasPorDia','totalGeneral', 'diasMes', 'año','mes','nombreSucursales'));
    }

    public function filtrarVentas(Request $request){
        // filtro en base al mes
        $año = $request->input('año');
        $mes = $request->input('mes');
        // filtrado por mes
        $sucusalId = $request->input('sucursal');


         // fitrar fecjas por el campo fecha_venta
         $ventasFiltro = Venta::with([
            'persona:id,nombre',
            'productos:id,nombre',
            'sucursal:id,nombre,ubicacion',
        ])->whereYear('fecha_venta', $año) //filtro por año
        ->whereMonth('fecha_venta', $mes)// filto pod mes
        ->when($sucusalId, function($query) use ($sucusalId){
            return $query->where('id_sucursal', $sucusalId);
        })
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

        return response()->json([
            'diasMes' => $diasMes,
            'ventasPorDia' => $ventasPorDia,
            'totalGeneral' => $totalGeneral,
            'mes' => $mes,
            'año' => $año,
        ]);
    }

}
