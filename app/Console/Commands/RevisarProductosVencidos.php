<?php

namespace App\Console\Commands;

use App\Models\Almacen;
use App\Models\Lote;
use App\Models\Requisicion;
use Database\Seeders\productos;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevisarProductosVencidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productos:revisar-vencidos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revisa si hay productos vencidos y los mueve a otra tabla';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $productosVencidos = Lote::where('fecha_vencimiento', '<', Carbon::now())->get();

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

        foreach($almacenVencido as $almacen){
           
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
    
        $this->info('Productos vencidos procesados correctamente.');
    }
    
}
