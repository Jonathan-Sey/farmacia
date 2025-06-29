<?php

namespace App\Http\Controllers\bitacora;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use Illuminate\Http\Request;

class bitacoraController extends Controller
{
    // mostramos las ultimas acciones del usuario en bitacora
   public function index()  {
    $bitacora = Bitacora::with('usuario')->orderBy('created_at', 'desc')->paginate(10);

    return view('bitacora.index', compact('bitacora'));
   }
}
