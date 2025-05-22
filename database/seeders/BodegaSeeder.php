<?php

namespace Database\Seeders;

use App\Models\Bodega;
use Illuminate\Database\Seeder;

class BodegaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bodega::create([
            'nombre' => 'Bodega Principal',
            'ubicacion' => 'Sede Central',
            'es_principal' => true,
            'estado' => true
        ]);
    }
}
