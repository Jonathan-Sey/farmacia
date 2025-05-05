<?php

namespace App\Http\Controllers\notificaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class notificacionesController extends Controller
{
    public function index(){
        $notificaciones = DB::table('notificaciones')
            ->where('leido', 0)
            ->get();
        return view('notificaciones.index')->
            with('notificaciones', $notificaciones);
    }

    public function cantidadDeNotificaciones(){
        $cantidadDeSolicitudes = DB::table('notificaciones')
            ->where('leido', 0)
            ->count();
        return response()->json(['cantidad' => $cantidadDeSolicitudes]);
    }

    public function destroy($id){
        DB::table('notificaciones')
            ->where('id', $id)
            ->update(['leido' => 1]);
            return redirect()->route('notificaciones.index')->with('success','notificacion leida!');

    }


}

