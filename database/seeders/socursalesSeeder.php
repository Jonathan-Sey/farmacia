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
            ['imagen' =>"",'nombre' => "Antigua", 'codigo_sucursal'=>"1", 'ubicacion' =>"Antigua guatemala", 'latitud' => 14.56111, 'longitud' => -90.73444, 'google_maps_link' => ""  , 'telefono'=>"48759654", 'email'=>"antigua@gmail.com", 'encargado'=>"Jose", 'estado' => 1],
            ['imagen' =>"",'nombre' => "Huehuetenango", 'codigo_sucursal'=>"2", 'ubicacion' => "Huehuetenango", 'latitud' => 14.56111, 'longitud' => -90.73444, 'google_maps_link' => ""  ,'telefono'=>"59238745", 'email'=>"huehuetenango@gmail.com",'encargado'=>"Luis",  'estado' => 1],
        ];

        DB::table("sucursal")->insert($data);
    }
}
