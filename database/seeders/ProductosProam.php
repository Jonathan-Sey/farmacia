<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosProam extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productos = [

                ['codigo' => 'A001', 'nombre' => 'ACETAMINOFEN 100 MG/ML FRASCO GOTERO 20 ML', 'imagen'=> "", 'descripcion' => '', 'precio_venta' => 1, 'precio_porcentaje' => "1", 'tipo' => 1, 'fecha_caducidad' => '2025-02-24 21:22:08', 'id_categoria' => 1, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['codigo' => 'A002', 'nombre' => 'ACETAMINOFEN FRASCO JARABE 120MG/5ML', 'imagen'=> "", 'descripcion' => '', 'precio_venta' => 1, 'precio_porcentaje' => "1", 'tipo' => 1, 'fecha_caducidad' => '2025-02-24 21:22:08', 'id_categoria' => 1, 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],


        ];
        DB::table('producto')->insert($productos);
    }
}