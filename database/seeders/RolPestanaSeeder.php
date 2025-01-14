<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pestana; 
class RolPestanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        Pestana::create(['nombre' => 'Dashboard', 'slug' => 'dashboard','ruta' => 'dashboard']);
        Pestana::create(['nombre' => 'Rol', 'slug' => 'rol','ruta' => 'rol']);
        Pestana::create(['nombre' => 'Categorías', 'slug' => 'categorias','ruta' => 'categorias']);
        Pestana::create(['nombre' => 'Proveedores', 'slug' => 'proveedores','ruta' => 'proveedores']);
        Pestana::create(['nombre' => 'Sucursales', 'slug' => 'sucursales','ruta' => 'sucursales']);
        Pestana::create(['nombre' => 'Productos', 'slug' => 'productos','ruta' => 'productos']);
    }
}
