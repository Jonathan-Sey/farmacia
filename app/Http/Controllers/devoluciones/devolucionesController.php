<?php

namespace App\Http\Controllers\devoluciones;

use App\Http\Controllers\Controller;
use App\Models\Devoluciones;
use Illuminate\Http\Request;

class devolucionesController extends Controller
{
    public function index()
    {
        $devoluciones = Devoluciones::all();
        return view('devoluciones.index', compact('devoluciones'));
    }

    public function create()
    {
        return view('devoluciones.create');
    }
}
