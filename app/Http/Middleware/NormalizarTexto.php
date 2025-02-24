<?php

namespace App\Http\Middleware;

use Closure;

class NormalizarTexto
{
    public function handle($request, Closure $next)
    {
        $excepciones = ['email', 'username', 'correo', 'usuario', '_token']; // Asegurar que _token no se toque
        
        $datos = $request->all();

        foreach ($datos as $key => $value) {
            if (is_string($value) && !in_array($key, $excepciones)) {
                // Normaliza la cadena con UTF-8 correctamente
                $datos[$key] = mb_convert_case($value, MB_CASE_TITLE, "UTF-8");
            }
        }

        // ✅ Mantener el token CSRF para evitar el error 419
        if ($request->has('_token')) {
            $datos['_token'] = $request->input('_token');
        }

        $request->replace($datos);

        return $next($request);
    }
}
