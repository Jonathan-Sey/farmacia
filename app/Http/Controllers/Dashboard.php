<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleMedico;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Venta;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function index(){
        $productos = Producto::where('tipo',1)->count();
        $sucursales = Sucursal::count();
        $compras = Compra::count();
        $ventas = Venta::count();
        $servicios = Producto::where('tipo',2)->count();
        $medicos = DetalleMedico::count();
        return view('dashboard.index',compact('productos','sucursales','compras','ventas','servicios','medicos'));
    }

}
