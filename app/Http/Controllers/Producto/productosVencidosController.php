<?php

namespace App\Http\Controllers\Producto;

use App\Http\Controllers\Controller;
use App\Models\almacenVencido;
use Illuminate\Http\Request;

class productosVencidosController extends Controller
{
    public function index()
    {

       // Obtiene los productos vencidos con sus relaciones cargadas
    $productosVencidos = almacenVencido::with(['producto', 'sucursal'])->get();

    return view('producto.vencidos', compact('productosVencidos'));
    }
}
