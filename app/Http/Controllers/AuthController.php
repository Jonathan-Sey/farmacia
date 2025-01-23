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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);


        $credentials = $request->only('email', 'password');
          // Autenticar al usuario con Laravel para la sesión
        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

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
        $user->load('rol.pestanas');
       // auth()->attempt($credentials);
        // Guardar el token JWT en una cookie
        //$cookie = cookie('jwt_token', $token, 60, '/', null, false, true);
        session()->regenerate();

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => auth()->user(),
            //'user' => $user,
            //'pestanas' => $user->rol->pestanas,
        //])->cookie($cookie);
        ]);
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
