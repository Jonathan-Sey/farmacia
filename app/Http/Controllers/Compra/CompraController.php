<?php

namespace App\Http\Controllers\Compra;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use Exception;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compras = Compra::with('proveedor','detalleCompras','usuario')
        ->latest()
        ->activos()
        ->get();
        return view('compra.index',compact('compras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $proveedores = Proveedor::whereNotIn('estado',[0,2])->get();
        $proveedores = Proveedor::activos()->get();
        $productos = Producto::activos()->get();
        return view('compra.create',compact('proveedores','productos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'arrayprecio' => 'required|array',
            'estado'=>'integer',
            'arraycantidad.*' => 'integer|min:1',
            'arrayprecio.*' => 'numeric|min:0',
            'estado'=>'integer',
        ]);
    

        try{
            DB::beginTransaction();
            // Generación de código
    $ultimoId = Compra::max('id') ?? 0;
    $codigo = 'CR-' . str_pad($ultimoId + 1, 5, '0', STR_PAD_LEFT);
    
            // Creando el registro de compra
            $compra = Compra::create([
                'numero_compra' => $codigo,
                'id_proveedor' => $request->id_proveedor,
                'id_usuario' => 1,
                'comprobante' => $request->comprobante,
                'impuesto' => $request->impuesto,
                'fecha_compra' => $request->fecha_compra,
                'total' => $request->input('total'),
                'estado' => 1,
            ]);

            // bitacora
            $usuario = User::find($request->idUsuario);
            Bitacora::create([
                'id_usuario' => $request->idUsuario,
                'name_usuario' => $usuario->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Compras',
                'detalles' => "Se creó la compra: {$compra->numero_compra}", 
                'fecha_hora' => now(),
            ]);

            // obtener los arrays de detalles
            $arrayProducto_id = $request->get('arrayIdProducto');
            $arrayCantidad = $request->get('arraycantidad');
            $arrayprecio= $request->get('arrayprecio');

            //insertar los detalels
            foreach($arrayProducto_id as $index => $idPoducto){
                DetalleCompra::create([
                    'id_compra' => $compra->id,
                    'id_producto' => $idPoducto,
                    'cantidad' => $arrayCantidad[$index],
                    'precio'=> $arrayprecio[$index]
                ]);
            }

            DB::commit();
            return redirect()->route('compras.index')->with('success', 'Compra creado exitosamente');
        }catch(Exception $e){
            // cancelar transaccion
            DB::reset();
            return redirect()->route('compra.create')->with('error', 'Error al crear la compra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Compra $compra)
    {
        // $productos = $compra->productos();
        return view('compra.show',compact('compra'));

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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Compra $compra)
    {
        $estado = $request->input('status', 0);
        if($estado == 0){
            $compra->update(['estado' => 0]);
            return redirect()->route('compras.index')->with('success','Compra eliminado con éxito!');
        }else{
            $compra->estado = $estado;
            $compra->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success'=> false]);
    }
}
