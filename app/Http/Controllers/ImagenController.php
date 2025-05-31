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
             // Subir a una carpeta temporal
             $imagen->move(public_path('uploads/temp'), $nombreImagen);

        return response()->json(['imagen' => $nombreImagen]);

    }
    public function eliminarTemp(Request $request)
    {
        $imagen = $request->imagen;
        $rutaTemp = public_path('uploads/temp/' . $imagen);

        if (file_exists($rutaTemp)) {
            unlink($rutaTemp);
        }

        return response()->json(['success' => true]);
    }

    public function moverDefinitiva($imagenNombre)
    {
        $rutaTemp = public_path('uploads/temp/' . $imagenNombre);
        $rutaDefinitiva = public_path('uploads/' . $imagenNombre);

        if (file_exists($rutaTemp)) {
            rename($rutaTemp, $rutaDefinitiva);
            return true;
        }

        return false;
    }

}
