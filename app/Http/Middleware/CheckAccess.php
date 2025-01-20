<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAccess
{
    /**
     * Manejar la solicitud.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $pestana
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $pestana)
    {
        try {
            // Obtener el payload del token JWT
            $payload = JWTAuth::parseToken()->getPayload();
            
            // Obtener las pestañas del usuario desde el payload
            $pestanas = $payload->get('pestanas'); // Es una lista de pestañas
    
            // Verificar si la pestaña solicitada está en las pestañas del usuario
            if (!in_array($pestana, $pestanas)) {
                return response()->view('errors.403', [], 403); // Si no tiene acceso, muestra una página de error 403
            }
    
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // Si el token no es válido o ha expirado, devolver una respuesta de error 401
            return response()->json(['error' => 'Token no válido'], 401);
        }
    

        return $next($request);
    }
}
