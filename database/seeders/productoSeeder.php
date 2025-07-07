<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class productoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('proveedor')->insert([
            [
                'nombre' => 'Proveedor 1',
                'telefono' => '123456789',
                'empresa' => 'Empresa 1',
                'correo' => 'proveedor1@empresa1.com',
                'direccion' => 'Dirección 1',
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        DB::table('persona')->insert([
            [
                'nombre' => 'CF',
                'nit' => 00000,
                'rol' => 1,
                'telefono' => '123456789',
                'fecha_nacimiento' => '1990-01-01',
                'dpi' => '',
                'estado' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }

}
