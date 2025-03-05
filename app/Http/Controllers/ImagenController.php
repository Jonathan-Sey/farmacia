<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImagenController extends Controller
{
    public function upload(Request $request){
        // $request->validate([
        //     'imagen' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        // ]);

        $imagen = $request->file('file');
        $nombreImagen = Str::uuid() . "." . $imagen->extension();
        $imagen->move(public_path('uploads'), $nombreImagen);

        return response()->json(['imagen' => $nombreImagen]);

    }
}
