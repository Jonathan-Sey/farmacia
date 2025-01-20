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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
    
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }
        $user = auth('api')->user(); 
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
 
        if (!$user->rol) {
            return response()->json(['error' => 'El usuario no tiene rol asignado'], 400);
        }
        $user->load('rol.pestanas');
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user,
            'rol' =>  $user->rol,
            //'pestanas' => $user->rol->pestanas,
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
            'password' => 'required|string|min:6|max:12',
            'rol' => 'required|string',
            'id_rol'=> 'required',
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