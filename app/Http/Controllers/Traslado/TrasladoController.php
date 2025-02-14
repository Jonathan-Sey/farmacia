<?php

namespace App\Http\Controllers\Traslado;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Inventario;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Traslado;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrasladoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $traslados = Traslado::with(['producto', 'sucursalOrigen', 'sucursalDestino'])->get();
         return view('Traslados.index',compact('traslados'));
        // $almacenes = Almacen::with('producto:id,nombre')
        // ->where('estado', '!=', 0)
        // ->get();
        // return view('Traslados.index',compact('almacenes'));

    }
    public function getLotes($idProducto, $idSucursal)
    {
        // $lotes = Lote::where('id_producto', $idProducto)->get();
        // return response()->json($lotes);
         // Obtener los lotes disponibles en el inventario de la sucursal de origen
        $lotes = Inventario::where('id_producto', $idProducto)
        ->where('id_sucursal', $idSucursal)
        ->with('lote') // Cargar la relación con la tabla `lote`
        ->get();

        Log::info('Lotes encontrados:', $lotes->toArray());

    return response()->json($lotes);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productos = Producto::activos()->get();
        $sucursales = Sucursal::activos()->get();
        $inventario = Inventario::all();
        return view('Traslados.create',compact('productos','sucursales','inventario'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        Log::info('Datos recibidos en el request:', $request->all());
        $this->validate($request, [
            'id_sucursal_origen' => 'required',
            'id_sucursal_destino' => 'required',
            'id_producto' => 'required',
            'id_lote' => 'required',
            'cantidad' => 'required|integer|min:1',
        ]);


        DB::beginTransaction();
        try{
            $producto = $request->id_producto;
            $lote = $request->id_lote;
            $sucursal_origen = $request->id_sucursal_origen;
            $sucursal_destino = $request->id_sucursal_destino;
            $cantidad = $request->cantidad;
            // revicion de inventario

             // Depuración: Verificar el inventario de origen
            Log::info('Buscando inventario de origen...');

            // $inventarioOrigen = Inventario::where('id_sucursal', $request->id_sucursal_origen)
            // ->where('id_producto', $request->id_producto)
            // ->where('id_lote', $request->id_lote)
            // ->first();
            $inventarioOrigen  = Inventario::where('id_producto', $producto)
            ->where('id_sucursal', $sucursal_origen)
            ->where('id_lote', $lote)
            ->first();

            // validaciones entre invnetario y la cantidad solicitada
            if(!$inventarioOrigen  || $inventarioOrigen ->cantidad < $request->cantidad){
                Log::error('No hay suficiente inventario en la sucursal de origen.');
                return redirect()->back()->with('error','No hay suficiente inventario en la sucural origen');
            }

                // 2. Restar la cantidad en la sucursal de origen
            $inventarioOrigen->cantidad -= $cantidad;
            $inventarioOrigen->save();

            // 3. Verificar si el producto ya existe en la sucursal destino
            $inventarioDestino = Inventario::where('id_producto', $producto)
            ->where('id_sucursal', $sucursal_destino)
            ->where('id_lote', $lote)
            ->first();

            if ($inventarioDestino) {
                $inventarioDestino->cantidad += $cantidad;
                $inventarioDestino->save();
            }else{
                // Log::error('No existe un registro de inventario para el producto y lote en la sucursal de destino.');
                // return redirect()->back()->with('error', 'No existe un registro de inventario para el producto y lote en la sucursal de destino.');
                Log::warning('Inventario en destino no encontrado. Creando un nuevo registro.');

                //     Inventario::create([
                //         'id_producto' => $producto,
                //         'id_sucursal' => $sucursal_destino,
                //         'id_lote' => $lote,
                //         'cantidad' => $cantidad
                // ]);
            }

            Log::info('Inventario de destino encontrado:', [$inventarioDestino]);


            // // Restar la cantidad del inventario de origen
            // $inventarioOrigen->cantidad -= $request->cantidad;
            // $inventarioOrigen->save();
            // Log::info('Buscando inventario de destino...');

            //  // Restar la cantidad del inventario de origen
            //  $inventarioDestino->cantidad += $request->cantidad;
            //  $inventarioDestino->save();


            // proceso para pasar los productos a la sucursal destino


            // if ($inventarioDestino) {
            //     $inventarioDestino->cantidad += $request->cantidad;
            //     $inventarioDestino->save();
            // } else {
            //     Inventario::create([
            //         'id_sucursal' => $request->id_sucursal_destino,
            //         'id_producto' => $request->id_producto,
            //         'id_lote' => $request->id_lote,
            //         'cantidad' => $request->cantidad,
            //     ]);
            // }

                // verificamos el inventario
           /*
                $inventarioDestino = Inventario::firstOrCreate(
                    [
                        'id_sucursal' => $request->id_sucursal_destino,
                        'id_producto' => $request->id_producto,
                        'id_lote' => $request->id_lote,
                    ],
                    [
                        'cantidad' => 0, // Inicializar con cantidad 0 si no existe
                    ]
                );

                // Actualizamos el inventario destino
                $inventarioDestino->cantidad += $request->cantidad;
                $inventarioDestino->save();

                // Restar la cantidad del inventario de origen
                $inventarioOrigen->cantidad -= $request->cantidad;
                $inventarioOrigen->save();

*/


              // Depuración: Registrar el traslado
                Log::info('Registrando el traslado...');

            // almacenar el traslado
            Traslado::create([
                'id_sucursal_origen' => $request->id_sucursal_origen,
                'id_sucursal_destino' => $request->id_sucursal_destino,
                'id_producto' => $request->id_producto,
                'id_lote' => $request->id_lote,
                'cantidad' => $request->cantidad,
                'fecha_traslado' => now(),
                'id_usuario' => 1,
            ]);
            // Depuración: Actualizar el almacén de origen
                Log::info('Actualizando el almacén de origen...');

            // proceso apra actualizar el almacen
            $almacenOrigen = Almacen::where('id_sucursal', $request->id_sucursal_origen)
            ->where('id_producto', $request->id_producto)
            ->first();

            if ($almacenOrigen) {
                $almacenOrigen->cantidad -= $request->cantidad;
                $almacenOrigen->save();
            }
             // Depuración: Actualizar el almacén de destino
            Log::info('Actualizando el almacén de destino...');

            // Actualizar la tabla almacen para la sucursal de destino
            $almacenDestino = Almacen::where('id_sucursal', $request->id_sucursal_destino)
                                     ->where('id_producto', $request->id_producto)
                                     ->first();

            if ($almacenDestino) {
                $almacenDestino->cantidad += $request->cantidad;
                $almacenDestino->save();
            } else {
                Almacen::create([
                        'id_sucursal' => $request->id_sucursal_destino,
                        'id_producto' => $request->id_producto,
                        'cantidad' => $request->cantidad,
                        'id_user' => 1,
                        'estado' => 1, // Estado activo
                        ]);
            }

            DB::commit();
             // Depuración: Traslado realizado con éxito
            Log::info('Traslado realizado exitosamente.');

            return redirect()->route('traslados.index')->with('success', 'Traslado realizado exitosamente.');
        }
        catch (\Exception $e) {
            DB::rollBack();
               // Depuración: Error al realizar el traslado
            Log::error('Error al realizar el traslado: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al realizar el traslado: ' . $e->getMessage());
        }
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
