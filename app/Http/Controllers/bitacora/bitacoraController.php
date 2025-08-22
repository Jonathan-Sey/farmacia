<?php

namespace App\Http\Controllers\bitacora;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use Illuminate\Http\Request;

class bitacoraController extends Controller
{
    // mostramos las ultimas acciones del usuario en bitacora
   public function index()  {
    $bitacora = Bitacora::with('usuario')->orderBy('created_at', 'desc')->paginate(50);

    return view('bitacora.index', compact('bitacora'));
   }

   public function indexApi()  {
    $bitacora = Bitacora::with('usuario')->orderBy('created_at', 'desc')->paginate(50);

    return response()->json($bitacora);
   }

   public function show($id) {
    $bitacora = Bitacora::with('usuario')->findOrFail($id);

    
    return response()->json($bitacora);
}
}
