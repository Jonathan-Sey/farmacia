<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class categoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categoria::create(
            [
                'nombre' => 'Medicina',
                 'descripcion' => 'Todo lo que tenga que ver con productod de Medician', 
                 'estado' => 1
            ]
            );
    }
}
