<?php

namespace App\Http\Middleware;

//use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
//     public function handle($request, Closure $next, $role){
//     try {
//         // obtener el token
//         $token = $request->cookie('jwt_token');

//         if (!$token) {
//             return response()->json(['error' => 'Token no proporcionado', 'cookie' => $request->cookies->all()], 401);
//         }

//         $user = JWTAuth::setToken($token)->authenticate();

//         if (is_null($user)) {
//             return response()->json(['error' => 'Usuario no autenticado'], 401);
//         }
//         if ($user->rol != $role) {
//             return response()->json(['error' => 'No tiene acceso a esta ruta'], 403);
//         }
//     } catch (\Exception $e) {
//         return response()->json(['error' => 'No tiene acceso a esta ruta', 'exception' => $e->getMessage()], 403);
//     }
//     return $next($request);
// }

}
