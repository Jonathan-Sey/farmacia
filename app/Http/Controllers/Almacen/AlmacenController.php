<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Bitacora;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\Requisicion;
use Illuminate\Http\Request;
use App\Models\Sucursal;
use App\Models\SucursalUser;
use App\Models\Traslado;
use App\Models\User;
use App\Models\Vencidos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$traslados = Traslado::with(['producto', 'sucursalOrigen', 'sucursalDestino'])->get();
        //comprobar que no exista producto vencido en el almacen
        $productosVencidos = Lote::where('fecha_vencimiento', '<', Carbon::now())->get();

        // Obtener productos vencidos del almacén
        $almacenVencido = Almacen::where('fecha_vencimiento', '<', Carbon::now())->get();

        if ($productosVencidos->isNotEmpty() || $almacenVencido->isNotEmpty()) {

            foreach ($productosVencidos as $producto) {
                // Obtener todas las requisiciones relacionadas con este lote
                $requisiciones = Requisicion::where('id_lote', $producto->id)->get();

                // Eliminar todas las requisiciones asociadas
                foreach ($requisiciones as $requisicion) {
                    $requisicion->delete();
                }

                // Insertar en la tabla correcta (revisa que el nombre esté bien escrito)
                DB::table('producto__vecidos')->insert([
                    "id_producto" => $producto->id_producto,
                    'fecha_vencimiento' => $producto->fecha_vencimiento,
                    "id_compra" => $producto->id_compra,
                    'cantidad' => $producto->cantidad,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Eliminar el producto de la tabla original
                $producto->delete();
            }

            // borrar productos vencidos de la tabla almacen

            $almacenVencido =  Almacen::where('fecha_vencimiento', '<', Carbon::now())->get();

            foreach ($almacenVencido as $almacen) {

                // Insertar en la tabla correcta (revisa que el nombre esté bien escrito)
                DB::table('almacen_vencidos')->insert([
                    'id_sucursal' => $almacen->id_sucursal,
                    "id_producto" => $almacen->id_producto,
                    'cantidad' => $almacen->cantidad,
                    'fecha_vencimiento' => $almacen->fecha_vencimiento,
                    'id_user' => $almacen->id_user,
                    'estado' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Eliminar el producto de la tabla original
                $almacen->delete();
            }

            // notificar al usuario
            DB::table('notificaciones')->insert([
                'tipo' => 'producto vencidos',
                'mensaje' => 'Se han encontrado productos vencidos y se han movido a la tabla correspondiente.',
                'leido' => false,
                'accion' => 'ver productos vencidos',
                'url' => '/productos-vencidos',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        $almacenes = Almacen::with('producto:id,codigo,nombre,tipo,imagen')
            ->where('estado', '!=', 0)

            ->get();
        //return($almacenes);
        return view('almacen.index', compact('almacenes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productos = Producto::activos()->where('tipo', 2)->get();
        $sucursales = Sucursal::activos()->get();
        return view('almacen.create', compact('productos', 'sucursales'));
    }

    //     public function getProductosPorSucursal($idSucursal)
    // {
    //     $almacenes = Almacen::activos()
    //         ->where('id_sucursal', $idSucursal)
    //         ->with('producto') // Relación con el modelo Producto
    //         ->get();

    //     $productos = $almacenes->map(function ($almacen) {
    //         return [
    //             'id' => $almacen->producto->id,
    //             'nombre' => $almacen->producto->nombre,
    //             'precio_venta' => $almacen->producto->precio_venta,
    //             'tipo' => $almacen->producto->tipo,
    //             'stock' => $almacen->cantidad,
    //         ];
    //     });

    //     return response()->json([
    //         'success' => true,
    //         'productos' => $productos,
    //     ]);
    // }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id_sucursal' => ['required'],
            'id_producto' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $existe = Almacen::where('id_sucursal', $request->id_sucursal)
                        ->where('id_producto', $value)
                        ->exists();
                    if ($existe) {
                        $fail('El servicio ya existe en esta sucursal.');
                    }
                }
            ],
            // 'cantidad' => ['required','numeric'],

        ]);

        Almacen::create([
            'id_producto' => $request->id_producto,
            'id_sucursal' => $request->id_sucursal,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'cantidad' => 1,
            'id_user' => 1,
        ]);

        $usuario=User::find($request->idUsuario);
        $producto = Producto::find($request->id_producto); // Obtener el producto por su ID
        $sucursal = Sucursal::find($request->id_sucursal); // Obtener la sucursal por su ID
           Bitacora::create([
                   'id_usuario' => $request->idUsuario,
                   'name_usuario' =>$usuario->name,
                   'accion' => 'Creación',
                   'tabla_afectada' => 'Almacenes',
                    'detalles' => "Se asignó el producto: {$producto->nombre} a la sucursal: {$sucursal->nombre}", //detalles especificos
                   'fecha_hora' => now(),
           ]);

        return redirect()->route('almacenes.index')->with('success', '¡Registro exitoso!');
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
    public function edit(Almacen $almacen)
    {
        // $productos = Producto::activos()->get();
        $productos = Producto::activos()->where('tipo', 2)->get();
        $sucursales = Sucursal::activos()->get();
        return view('almacen.edit', compact('almacen', 'productos', 'sucursales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Almacen $almacen)
    {

        $this->validate($request, [
            'id_sucursal' => ['required'],
            'id_producto' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $existe = Almacen::where('id_sucursal', $request->id_sucursal)
                        ->where('id_producto', $value)
                        ->exists();
                    if ($existe) {
                        $fail('El servicio ya existe en esta sucursal.');
                    }
                }
            ],
            // 'cantidad' => ['required','numeric'],

        ]);

        $datosActualizados = $request->only(['id_sucursal', 'id_producto', 'cantidad']);
        $datosSinCambios = $almacen->only(['id_sucursal', 'id_producto', 'cantidad']);

        if ($datosActualizados == $datosSinCambios) {
            return redirect()->route('almacenes.index');
        }
        $almacen->update($datosActualizados);

        $usuario = User::find($request->idUsuario);
        Bitacora::create([
            'id_usuario' => $request->idUsuario,
            'name_usuario' => $usuario->name,
            'accion' => 'Actualización',
            'tabla_afectada' => 'Almacenes',
            'detalles' => "Se actualizo el almacen: {$request->id_sucursal}", //detalles especificos
            'fecha_hora' => now(),
        ]);
        return redirect()->route('almacenes.index')->with('success', '¡Almacen actualizado!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Almacen $almacen)
    {

        $estado = $request->input('status', 0);
        if ($estado == 0) {
            $almacen->update(['estado' => 0]);
            return redirect()->route('almacenes.index')->with('success', 'Almacen eliminado con éxito!');
        } else {
            $almacen->estado = $estado;
            $almacen->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function cambiarEstado($id)
    {
        $almacen = Almacen::find($id);

        if ($almacen) {
            $almacen->estado = $almacen->estado == 1 ? 2 : 1; // Cambiar el estado (activo <-> inactivo)
            $almacen->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    //Para mostrar lo que hay en la vista de modificar el alerta
    public function alertStock($id)
    {
        $almacen = Almacen::findOrFail($id);
        $productos = Producto::activos()->where('tipo', 2)->get();
        $sucursales = Sucursal::activos()->get();

        return view('almacen.alertStock', compact('almacen', 'productos', 'sucursales'));
    }

    //Actualiza la cantidad en el campo alerta_stock que se usa para mostrar la alerta de poco stock
    public function updateAlertStock(Request $request, $id)
    {
        $request->validate([
            'alerta_stock' => ['required', 'numeric', 'min:1'],
        ]);
        $almacen = Almacen::findOrFail($id);

        // Actualizar el campo alerta_stock
        $almacen->alerta_stock = $request->input('alerta_stock');
        $almacen->save();

        return redirect()->route('almacenes.index')->with('success', '¡Alerta de stock actualizada!');
    }

    //aqui se manda a la vista de productosa vencidos
    public function productosVencidos()
    {

        $sucursales = Sucursal::activos()->get();
        $almacenes = Almacen::with('producto:id,codigo,nombre');

        return view('producto.vencidos', compact('sucursales', 'almacenes'));
    }
}
