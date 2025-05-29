<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Rol;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

     public function login(Request $request)
     {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Errores de validación',
                'messages' => $e->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        // Usar el guardia de JWT ('api')
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        if (!$user->id_rol) {
            return response()->json(['error' => 'El usuario no tiene rol asignado'], 400);
        }

        if($user->estado == 2){
            return response()->json(['error' => 'Este usuario esta inactivo'], 404);
        }

        $rol = $user->rol;
        $pestanas = $rol->pestanas()->pluck('ruta')->toArray();

        // Crear la cookie con el token JWT
        $cookie = cookie(
            'jwt_token', // Nombre de la cookie
            $token, // Valor de la cookie
            config('jwt.ttl') * 120, // Tiempo de expiración (en minutos)
            '/', // Ruta (accesible en todo el dominio)
            null, // Dominio (null para el dominio actual)
            false, // Solo HTTPS (false para desarrollo local)
            true, // HttpOnly (para evitar acceso desde JavaScript)
            'Strict' // SameSite (para evitar ataques CSRF)
        );

        return response()
            ->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
                'pestanas' => $pestanas,
            ])
            ->withCookie($cookie);
     }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {

        // Verificar si hay un usuario autenticado
        if (auth('api')->check()) {
            auth('api')->logout(); // Cerrar sesión
            session()->flush();
            return response()->json(['message' => 'Successfully logged out'], 200);
        }
        Auth::logout();

        // Invalidar y regenerar sesión
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'No user is currently logged in'], 401);

    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        //return $this->respondWithToken(auth('api')->refresh());
        $newToken = auth()->refresh();
        return response()->json([
            'token' => $newToken
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


}
