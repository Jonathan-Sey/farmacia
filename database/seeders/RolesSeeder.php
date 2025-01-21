<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Rol::create(['nombre' => 'admin', 'descripcion' => 'Administrador del sistema', 'estado' => 1]);
    }
}
