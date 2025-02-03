<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Obtener el token JWT del encabezado de la solicitud
            $token = JWTAuth::parseToken();
            $user = $token->authenticate();

            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            // Obtener las pestañas permitidas para el usuario
            $pestanasPermitidas = $user->pestanas;

            // Adjuntar las pestañas permitidas a la solicitud
            $request->merge(['pestanasPermitidas' => $pestanasPermitidas]);

            return $next($request);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token inválido o expirado'], 401);

    }
}
}
