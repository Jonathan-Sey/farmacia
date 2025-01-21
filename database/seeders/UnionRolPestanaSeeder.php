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
            ['id' => 1, 'rol_id' => 1, 'pestana_id' => 1],
            ['id' => 2, 'rol_id' => 1, 'pestana_id' => 2],
            ['id' => 3, 'rol_id' => 1, 'pestana_id' => 3],
            ['id' => 4, 'rol_id' => 1, 'pestana_id' => 4],
            ['id' => 5, 'rol_id' => 1, 'pestana_id' => 5],
            ['id' => 6, 'rol_id' => 1, 'pestana_id' => 6],
            ['id' => 7, 'rol_id' => 1, 'pestana_id' => 7],
            ['id' => 8, 'rol_id' => 1, 'pestana_id' => 8],
            ['id' => 9, 'rol_id' => 1, 'pestana_id' => 9],
            ['id' => 10, 'rol_id' => 1, 'pestana_id' => 10],
            ['id' => 11, 'rol_id' => 1, 'pestana_id' => 11],
            ['id' => 12, 'rol_id' => 1, 'pestana_id' => 12],
            ['id' => 13, 'rol_id' => 1, 'pestana_id' => 13],
        ];

        DB::table('rol_pestana')->insert($data);
    }
}
