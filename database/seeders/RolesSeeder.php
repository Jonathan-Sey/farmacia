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
        Rol::create(['nombre' => 'cajero', 'descripcion' => 'Encargado de caja', 'estado' => 1]);
        Rol::create(['nombre' => 'gerente', 'descripcion' => 'Gerente de sucursal', 'estado' => 1]);
    }
}
