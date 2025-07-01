<?php

namespace App\Http\Controllers\Usuario;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Rol;
use App\Models\Sucursal;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
        {

        $roles = Rol::where('estado', '!=', 2)->get();
        // Filtrar solo usuarios activos y cargar el rol relacionado con las columnas específicas
        $usuarios = User::whereIn('estado', [1, 2])->with('rol:id,nombre', 'sucursal:id,nombre')->get();
        // Pasar los usuarios y roles a la vista
        return view('Usuarios.index', compact('roles', 'usuarios'));
        }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $roles = Rol::where('estado', '!=', 2)->get();
        $sucursales = Sucursal::where('estado', 1)->get();
        return view('Usuarios.create',compact('roles','sucursales'));
     }

    public function edit($id)
        {
        // Obtén al usuario por ID
        $user = User::findOrFail($id);
        $roles = Rol::where('estado', '!=', 2)->get();
        $sucursales = Sucursal::where('estado', 1)->get();
        return view('Usuarios.edit', compact('user', 'roles','sucursales'));
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
                'sucursal_id' => 'nullable|exists:sucursal,id',
            ]);

            // Si la validación falla, redirigir con errores
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Actualizar los datos del usuario
            $user->name = $request->name;
            $user->email = $request->email;
            $user->id_rol = $request->id_rol;
            $user->sucursal_id = $request->sucursal_id;

            // Si se proporciona una nueva contraseña, actualizarla
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            // Guardar los cambios
            $user->save();

              // Bitacora
         $usuario = User::find($request->idUsuario);
         Bitacora::create([
             'id_usuario' => $request->idUsuario,
             'name_usuario' => $usuario->name,
             'accion' => 'Actualizacion',
             'tabla_afectada' => 'Usuario',
             'detalles' => "Se actualizo el usuario  {$request->name}", // Se usa el nombre de la sucursal
             'fecha_hora' => now(),
         ]);

            // Redirigir con mensaje de éxito
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
        }


    public function actualizarEstado(Request $request, $id)
        {
            $usuario = User::findOrFail($id); // Busca al usuario por ID

            // Cambiar el estado de 'activo' a false
            $usuario->estado = 2;
            $usuario->save();

            return response()->json(['success' => 'Usuario desactivado correctamente']);
        }
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|max:12',
            'id_rol' => 'required|exists:rol,id',
            'sucursal_id' => 'nullable|exists:sucursal,id',
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
            'sucursal_id' => $request->input('sucursal_id'),
        ]);

             // Bitacora
             $usuario = User::find($request->idUsuario);
             Bitacora::create([
                 'id_usuario' => $request->idUsuario,
                 'name_usuario' => $usuario->name,
                 'accion' => 'Creación',
                 'tabla_afectada' => 'Usuario',
                 'detalles' => "Se creo el usuario {$request->nombre}", // Se usa el nombre de la sucursal
                 'fecha_hora' => now(),
             ]);

        // auth()->attempt([
        //     'email' => $request->email,
        //     'password' => $request->password,
        // ]);

        return redirect()->route('usuarios.index')->with('success', '¡Usuario registrado exitosamente!');
    }

    public function cambiarEstado($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->estado = $user->estado == 1 ? 2 : 1; // Cambiar el estado (activo <-> inactivo)
            $user->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function usuarioActual(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

}
