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
            ['nombre' => "Antigua", 'ubicacion' => "Antigua guatemala", 'telefono'=>"48759654", 'email'=>"antigua@gmail.com", 'estado' => 1],
            ['nombre' => "Huehuetenango", 'ubicacion' => "Huehuetenango", 'telefono'=>"59238745", 'email'=>"huehuetenango@gmail.com", 'estado' => 1],
            
        ];

        DB::table("sucursal")->insert($data);
    }
}
