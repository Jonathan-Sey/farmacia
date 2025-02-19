<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class productoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Producto::create(
            [
         'nombre' => "Aspirina",
            'descripcion' => "para dolor de cabeza  ",
            'precio_venta' => 1.50,
            'fecha_caducidad' => "2030/10/10",
            'id_categoria' =>1,
            'estado' => 1,
            'tipo' => 1,
            'codigo' => "C-00001"// asignamos el codigo generado
 
            ] 
            );
    }
}
