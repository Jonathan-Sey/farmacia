<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class socursalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['imagen' =>"",'nombre' => "Antigua", 'ubicacion' => "Antigua guatemala", 'estado' => 1],
            ['imagen' =>"",'nombre' => "Huehuetenango", 'ubicacion' => "Huenuetenango", 'estado' => 1],

        ];

        DB::table("sucursal")->insert($data);
    }
}
