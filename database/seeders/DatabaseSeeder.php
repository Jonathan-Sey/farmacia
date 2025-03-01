<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       
        $this->call([
            RolesSeeder::class,
            RolPestanaSeeder::class,
            UnionRolPestanaSeeder::class,
            UserSeeder::class,
            categoriaSeeder::class,
            productoSeeder::class,
            socursalesSeeder::class,
    
            // FarmaciaSeeder::class,
        ]);
    }
}
