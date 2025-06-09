<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnionRolPestanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['rol_id' => 1, 'pestana_id' => 1, 'orden' => 1],
            ['rol_id' => 1, 'pestana_id' => 2, 'orden' => 2],
            ['rol_id' => 1, 'pestana_id' => 3, 'orden' => 3],
            ['rol_id' => 1, 'pestana_id' => 4, 'orden' => 4],
            ['rol_id' => 1, 'pestana_id' => 5, 'orden' => 5],
            ['rol_id' => 1, 'pestana_id' => 6, 'orden' => 6],
            ['rol_id' => 1, 'pestana_id' => 7, 'orden' => 7],
            ['rol_id' => 1, 'pestana_id' => 8, 'orden' => 8],
            ['rol_id' => 1, 'pestana_id' => 9, 'orden' => 9],
            ['rol_id' => 1, 'pestana_id' => 10, 'orden' => 10],
            ['rol_id' => 1, 'pestana_id' => 11, 'orden' => 11],
            ['rol_id' => 1, 'pestana_id' => 12, 'orden' => 12],
            ['rol_id' => 1, 'pestana_id' => 13, 'orden' => 13],
            ['rol_id' => 1, 'pestana_id' => 14, 'orden' => 14],
            ['rol_id' => 1, 'pestana_id' => 15, 'orden' => 15],
            ['rol_id' => 1, 'pestana_id' => 16, 'orden' => 16],
            ['rol_id' => 1, 'pestana_id' => 17, 'orden' => 17],
            ['rol_id' => 1, 'pestana_id' => 18, 'orden' => 18],
            ['rol_id' => 1, 'pestana_id' => 19, 'orden' => 19],
            ['rol_id' => 1, 'pestana_id' => 20, 'orden' => 20],
            ['rol_id' => 1, 'pestana_id' => 21, 'orden' => 21],
            ['rol_id' => 1, 'pestana_id' => 22, 'orden' => 22],
            ['rol_id' => 1, 'pestana_id' => 23, 'orden' => 23],
            ['rol_id' => 1, 'pestana_id' => 24, 'orden' => 24],
            ['rol_id' => 1, 'pestana_id' => 25, 'orden' => 25],
            ['rol_id' => 1, 'pestana_id' => 26, 'orden' => 26],




        ];

        DB::table('rol_pestana')->insert($data);
    }
}
