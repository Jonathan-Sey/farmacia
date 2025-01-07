<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        logger('Entrando al método login');
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    
        logger('Datos validados:', $request->only('email', 'password'));
    
        $credentials = $request->only('email', 'password');
    
        if (!$token = auth('api')->attempt($credentials)) {
            logger('Credenciales inválidas');
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        // Imprimir el token para verlo en el log
    \Log::info('Generated Token: ' . $token);
    
        logger('Usuario autenticado, token generado');
        $request->session()->put('jwt_token', $token);
    
        return redirect()->route('dashboard')->with('success', 'Inicio de sesión exitoso.');
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
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
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

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            ]
        );

        if($validator->fails()){
            return response()->json($validator->errors()->tojson(),400);
        }

       $user = User::create(array_merge(
        $validator->validate(),
        ['password' => bcrypt($request->password)]
       ));

       return response()->json([
        'message' => '¡Usuario registrado exitosamente!',
        'user' => $user
       ],201);
    }
}