<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImagenController extends Controller
{
/**
* funciones para subir un archivo de imagen a una carpeta temporal y eliminarlo posteriormente.
*
* @param Request request El objeto "Request" representa una solicitud HTTP. este contiene todos
* los datos enviados con la solicitud, como datos del formulario, parametros de consulta y archivos.
* se gestionar la subida y la eliminación de archivos.
*
* función "upload" devuelve una respuesta JSON que contiene el nombre del archivo de imagen subido.
* La función "eliminarTemp" devuelve una respuesta JSON con un mensaje de éxito tras eliminar el
* archivo de imagen temporal especificado en la solicitud.
*/
    public function upload(Request $request){
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

/**
* La función `moverDefinitiva` mueve un archivo de imagen de una ubicacion temporal a una permanente
*
* @param imagenNombre La función "moverDefinitiva" toma el parámetro que
* representa el nombre del archivo de la imagen y que debe moverse de una ubicación temporal a una
* permanente. La función primero construye las rutas para la ubicación temporal (``)
* y la ubicacion permanente
*
* @return booleano función `moverDefinitiva` devuelve `true` si el archivo existe en la ruta temporal y
* se ha movido correctamente a la ruta final. De lo contrario, devuelve `false`.
*/
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
