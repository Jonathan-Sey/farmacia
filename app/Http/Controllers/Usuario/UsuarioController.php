<?php

namespace App\Http\Controllers\Usuario;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Rol;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
        {
      
        $roles = Rol::where('estado', '!=', 0)->get();
        // Filtrar solo usuarios activos y cargar el rol relacionado con las columnas específicas
        $usuarios = User::where('activo', true)->with('rol:id,nombre') // Solo cargar 'id' y 'nombre' de la tabla 'rol'
        ->get();
        
        // Pasar los usuarios y roles a la vista
        return view('usuarios.index', compact('roles', 'usuarios'));
        }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $roles = Rol::where('estado', '!=', 0)->get();
        return view('usuarios.create',compact('roles'));
     }

    public function edit($id)
        {
        // Obtén al usuario por ID
        $user = User::findOrFail($id);
        $roles = Rol::where('estado', '!=', 0)->get();
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

   
    public function actualizarEstado(Request $request, $id)
        {
            $usuario = User::findOrFail($id); // Busca al usuario por ID

            // Cambiar el estado de 'activo' a false
            $usuario->activo = false;
            $usuario->save();

            return response()->json(['success' => 'Usuario desactivado correctamente']);
        }
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
