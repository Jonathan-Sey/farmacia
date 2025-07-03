<?php

namespace App\Http\Controllers\Producto;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImagenController;
use App\Models\Bitacora;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use App\Models\HistoricoPrecio;
use App\Models\Sucursal;
use App\Models\SucursalUser;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener el usuario logueado
        $user = auth()->user();
       // $sucusal = SucursalUser::where('user_id', $user->id)->first();
        $productos = Producto::with('categoria:id,nombre')
        ->select('id','codigo','nombre','tipo','ultimo_precio_compra','precio_venta','precio_porcentaje','imagen','estado','id_categoria','updated_at')
        ->where('estado', '!=', 0)

        ->get();
        //return $productos;
        return view('producto.index',['productos'=>$productos]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {

        // $categorias = Categoria::all(['id', 'nombre']);
        $categorias = Categoria::activos()->get();
        return view('producto.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

              // Guardar el nombre de la imagen temporalmente
            $imagenNombre = $request->imagen;

        $this->validate($request,[
            // 'codigo'=>['nullable'],
            'id_categoria'=>'required',
            'imagen'=>'required',
            'nombre'=>['required','string','max:50'],
            'descripcion'=>['max:100','required','string'],
            'precio_venta'=>'numeric|required|min:0',
            'precio_porcentaje' => 'nullable|numeric',
            'estado'=>'integer',

        ]);

         // Mover la imagen de temp a definitivo
        $imagenController = new ImagenController();
        $imagenMovida = $imagenController->moverDefinitiva($request->imagen);

        if (!$imagenMovida) {
            return back()->with('error', 'No se pudo guardar la imagen del producto');
        }



        $tipo = $request->has('tipo') ? 2 : 1;
        // generacion de codigo
        $ultimoId = Producto::max('id') ?? 0;
        $codigo = 'C-' . str_pad($ultimoId + 1, 5, '0', STR_PAD_LEFT);

        $datosProducto = [
            'nombre' => $request->nombre,
            'imagen' => $request->imagen,
            'descripcion' => $request->descripcion,
            'precio_venta' => $request->precio_venta,
            'id_categoria' => $request->id_categoria,
            'estado' => 1,
            'tipo' => $tipo,
            'codigo' => $codigo,
        ];

        // Asignar precio_porcentaje según el tipo
        $datosProducto['precio_porcentaje'] = ($tipo == 1)
            ? $request->precio_venta  // Para productos
            : $request->precio_porcentaje;  // Para servicios

        Producto::create($datosProducto);



            // Limpiar la imagen temporal de la sesión
            session()->forget('imagen_temp');

        $usuario=User::find($request->idUsuario);
        Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Producto',
                'detalles' => "Se creo el producto: {$request->nombre}", //detalles especificos
                'fecha_hora' => now(),
        ]);

        return redirect()->route('productos.index')->with('success','¡Registro exitoso!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)

    {
        $categorias = Categoria::activos()->get();
        return view('producto.edit',compact('producto','categorias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $this->validate($request, [
            'id_categoria' => 'required|exists:categoria,id',
            'nombre' => ['required', 'string', 'max:50'],
            'imagen' => 'nullable',
            'descripcion' => ['required', 'string', 'max:100'],
            'precio_venta' => 'numeric|required|min:0',
            'precio_porcentaje' => 'nullable|numeric|min:0',
            'estado' => 'integer',
        ]);

        // Determinar el tipo de producto
        $nuevoTipo = $request->has('tipo') ? 2 : 1;

        // Preparar datos para actualización
        $datosActualizados = [
            'id_categoria' => $request->id_categoria,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'tipo' => $nuevoTipo,
        ];

        // Manejo de imagen
        $imagenOriginal = $producto->imagen;

        if ($request->has('eliminar_imagen') && $request->eliminar_imagen == '1') {
            if ($imagenOriginal && file_exists(public_path('uploads/' . $imagenOriginal))) {
                unlink(public_path('uploads/' . $imagenOriginal));
            }
            $datosActualizados['imagen'] = null;
        } elseif ($request->imagen && $request->imagen !== $imagenOriginal) {
            $imagenController = new ImagenController();
            $imagenMovida = $imagenController->moverDefinitiva($request->imagen);

            if (!$imagenMovida) {
                return back()->with('error', 'No se pudo guardar la nueva imagen');
            }

            if ($imagenOriginal && file_exists(public_path('uploads/' . $imagenOriginal))) {
                unlink(public_path('uploads/' . $imagenOriginal));
            }

            $datosActualizados['imagen'] = $request->imagen;
        } else {
            $datosActualizados['imagen'] = $imagenOriginal;
        }

        // Lógica de precios según tipo de producto
        if ($nuevoTipo == 1) {
            // Producto normal: ambos precios iguales
            $nuevoPrecio = round($request->precio_venta * 10) / 10;
            $datosActualizados['precio_venta'] = $nuevoPrecio;
            $datosActualizados['precio_porcentaje'] = $nuevoPrecio;
        } else {
            // Servicio: precios pueden ser diferentes
            $datosActualizados['precio_venta'] = round($request->precio_venta * 10) / 10;
            $datosActualizados['precio_porcentaje'] = $request->precio_porcentaje
                ? round($request->precio_porcentaje * 10) / 10
                : null;
        }

        // Registrar cambio de precio si hubo modificación
        if ($producto->precio_porcentaje != $datosActualizados['precio_porcentaje']) {
            HistoricoPrecio::create([
                'id_producto' => $producto->id,
                'precio_anterior' => $producto->precio_porcentaje,
                'precio_nuevo' => $datosActualizados['precio_porcentaje'],
                'fecha_cambio' => now(),
            ]);
        }

        // Actualizar el producto si hay cambios
        if ($producto->fill($datosActualizados)->isDirty()) {
            $producto->save();

            $usuario = User::find($request->idUsuario);
            Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' => $usuario->name,
                'accion' => 'Actualización',
                'tabla_afectada' => 'Productos',
                'detalles' => "Se actualizó el producto: {$request->nombre}",
                'fecha_hora' => now(),
            ]);

            return redirect()->route('productos.index')
                   ->with('success', '¡Producto actualizado!');
        }

        return redirect()->route('productos.index');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Producto $producto)
    {
        $estado = $request->input('status', 0);
        if($estado == 0){
            $producto->update(['estado' => 0]);
            return redirect()->route('productos.index')->with('success','Producto eliminado con éxito!');
        }else{
            $producto->estado = $estado;
            $producto->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success'=> false]);
    }

    public function cambiarEstado($id)
    {
        $producto = Producto::find($id);

        if ($producto) {
            $producto->estado = $producto->estado == 1 ? 2 : 1; // Cambiar el estado (activo <-> inactivo)
            $producto->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function precioPorcentaje($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            abort(404, 'Producto no encontrado');
        }

        return view('producto.precio', compact('producto'));
    }

    public function actualizarPrecioPorcentaje(Request $request, $id)
    {
        //verifica que venga el nuevo dato cambiado
        $this->validate($request, [
            'nuevo_precio' => 'required|numeric|min:0'
        ]);

        $producto = Producto::find($id);

        if (!$producto) {
            return redirect()->route('productos.index')->with('error', 'Producto no encontrado');
        }
         // Guardar en historial antes de cambiar el precio en la tabla de historico_precio.
        HistoricoPrecio::create([
            'id_producto' => $producto->id,
            'precio_anterior' => $producto->precio_porcentaje,
            'precio_nuevo' => round($request->nuevo_precio * 10) / 10,
            'fecha_cambio' => now(),
        ]);
        //actualiza el campo de precio anterior en el index de producto con lo que tenia el campo de nuevo_precio.
        $producto->update(['precio_venta' => $producto->precio_porcentaje]);
        //actualiza el campo de nuevo_precio con el nuevo precio generado y lo redondea.
        $producto->update(['precio_porcentaje' => round($request->nuevo_precio * 10) / 10]);
        /*
        $usuario=User::find($request->idUsuario);
        Bitacora::create([
            'id_usuario' => $request->idUsuario,
            'name_usuario' =>$usuario->name,
            'accion' => 'Actualización',
            'tabla_afectada' => 'Productos',
            'detalles' => "Se actualizó el campo precio_porcentaje del producto: {$producto->nombre}",
            'fecha_hora' => now(),
        ]);*/

        return redirect()->route('productos.index')->with('success', '¡Precio porcentaje actualizado exitosamente!');
    }
    //Manda a la vista de historico para mostrar los datos y envia datos.
    public function verHistorico()
    {
        $historico = HistoricoPrecio::with('producto')->orderBy('fecha_cambio', 'desc')->get();

        return view('producto.historico', compact('historico'));
    }


}
