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
        Pestana::create(['nombre' => 'Almacenes', 'slug' => 'almacenes','ruta' => 'almacenes']);
        Pestana::create(['nombre' => 'Compras', 'slug' => 'compras','ruta' => 'compras']);
        Pestana::create(['nombre' => 'Ventas', 'slug' => 'ventas','ruta' => 'ventas']);
        Pestana::create(['nombre' => 'Personas', 'slug' => 'personas','ruta' => 'personas']);
        Pestana::create(['nombre' => 'Medicos', 'slug' => 'medicos','ruta' => 'medicos']);
        Pestana::create(['nombre' => 'Consultas', 'slug' => 'consultas','ruta' => 'consultas']);

    }
}
