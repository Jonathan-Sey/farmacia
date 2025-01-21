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
<<<<<<< HEAD
    public function index()
    {
        $roles = Rol::all();
        // Filtrar solo usuarios activos
        $usuarios = User::where('activo', true)->with('rol:id,nombre')->get();
        return view('usuarios.index', compact('roles', 'usuarios'));
    }



    public function create(){
        $roles = Rol::all();
        return view('usuarios.create',compact('roles'));
    }

    public function destroy()
    {

    }

    public function edit($id)
    {
        // Obtén al usuario por ID
        $user = User::findOrFail($id);
        $roles = Rol::all();
        return view('usuarios.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // Obtener el usuario desde la base de datos
        $user = User::findOrFail($id);

        // Determinar si el email ha cambiado
        $emailValidationRule = $request->email == $user->email
            ? 'required|string|email|max:100'
            : 'required|string|email|max:100|unique:users,email,' . $id;

        // Realizar la validación
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => $emailValidationRule, // Validación condicional del email
            'password' => 'nullable|string|min:6|max:12',
            'id_rol' => 'required|exists:rol,id',
        ]);

        // Si la validación falla, redirigir con errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Actualizar los datos del usuario
        $user->name = $request->name;
        $user->email = $request->email;
        $user->id_rol = $request->id_rol;

        // Si se proporciona una nueva contraseña, actualizarla
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Guardar los cambios
        $user->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // En el controlador de usuarios
    public function actualizarEstado(Request $request, $id)
    {
        $usuario = User::findOrFail($id); // Busca al usuario por ID

        // Cambiar el estado de 'activo' a false
        $usuario->activo = false;
        $usuario->save();

        return response()->json(['success' => 'Usuario desactivado correctamente']);
    }


=======
>>>>>>> main

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

        if (!$user->id_rol) {
            return response()->json(['error' => 'El usuario no tiene rol asignado'], 400);
        }
        $user->load('rol.pestanas');
        // Guardar el token JWT en una cookie
        $cookie = cookie('jwt_token', $token, 60, '/', null, false, true);

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user,
            //'pestanas' => $user->rol->pestanas,
        ])->cookie($cookie);
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
   
        // Verificar si hay un usuario autenticado
        if (auth('api')->check()) {
            auth('api')->logout(); // Cerrar sesión
            return response()->json(['message' => 'Successfully logged out'], 200);
        }

        return response()->json(['message' => 'No user is currently logged in'], 401);
    
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
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

<<<<<<< HEAD
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|max:12',
            'id_rol' => 'required|exists:rol,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->input('nombre'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'id_rol' => $request->input('id_rol'),
        ]);

        return redirect()->route('usuarios.index')->with('success', '¡Usuario registrado exitosamente!');
    }


}
=======
    
}
>>>>>>> main
