<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;  // Asegúrate de importar el modelo Rol
use App\Models\Pestana; 
class PestanasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Rol::where('nombre', 'admin')->first();
        $admin->pestanas()->sync(Pestana::pluck('id')->toArray()); // Todas las pestañas

        // Relacionar pestañas con el rol Cajero
        $cajero = Rol::where('nombre', 'cajero')->first();
        $cajero->pestanas()->sync(
            Pestana::whereIn('slug', ['dashboard', 'sucursales', 'productos'])->pluck('id')->toArray()
        );

        // Relacionar pestañas con el rol Gerente
        $gerente = Rol::where('nombre', 'gerente')->first();
        $gerente->pestanas()->sync(
            Pestana::whereIn('slug', ['dashboard', 'categorias', 'proveedores'])->pluck('id')->toArray()
        );
    }
}
