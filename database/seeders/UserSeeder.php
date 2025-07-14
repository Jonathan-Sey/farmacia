<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => null,
            'password' => Hash::make('admin123'), // Cambia 'password' por la contraseña que desees
            'id_rol' => 1,
            'remember_token' => null,
            'estado' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
