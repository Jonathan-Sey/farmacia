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

        Pestana::create(['nombre' => 'Dashboard', 'slug' => 'dashboard','ruta' => '/dashboard']);
        Pestana::create(['nombre' => 'Rol', 'slug' => 'rol','ruta' => '/roles']);
        Pestana::create(['nombre' => 'Categorias', 'slug' => 'categorias','ruta' => '/categorias']);
        Pestana::create(['nombre' => 'Proveedores', 'slug' => 'proveedores','ruta' => '/proveedores']);
        Pestana::create(['nombre' => 'Sucursales', 'slug' => 'sucursales','ruta' => '/sucursales']);
        Pestana::create(['nombre' => 'Productos', 'slug' => 'productos','ruta' => '/productos']);
        Pestana::create(['nombre' => 'Almacenes', 'slug' => 'almacenes','ruta' => '/almacenes']);
        Pestana::create(['nombre' => 'Compras', 'slug' => 'compras','ruta' => '/compras']);
        Pestana::create(['nombre' => 'Ventas', 'slug' => 'ventas','ruta' => '/ventas']);
        Pestana::create(['nombre' => 'Personas', 'slug' => 'personas','ruta' => '/personas']);
        Pestana::create(['nombre' => 'Medicos', 'slug' => 'medicos','ruta' => '/medicos']);
        Pestana::create(['nombre' => 'Consultas', 'slug' => 'consultas','ruta' => '/consultas']);
        Pestana::create(['nombre' => 'Usuarios', 'slug' => 'usuarios','ruta' => '/usuarios']);
        Pestana::create(['nombre' => 'Inventario', 'slug' => 'inventario','ruta' => '/inventario']);
        Pestana::create(['nombre' => 'Requisiciones', 'slug' => 'requisiciones','ruta' => '/requisiciones']);
        Pestana::create(['nombre' => 'Traslado', 'slug' => 'traslado','ruta' => '/traslado']);
        Pestana::create(['nombre' => 'Solicitud', 'slug' => 'solicitud','ruta' => '/solicitud']);
        Pestana::create(['nombre' => 'bitacora', 'slug' => 'bitacora','ruta' => 'bitacora']);
        Pestana::create(['nombre' => 'Reporte_ventas', 'slug' => 'Reporte_ventas','ruta' => 'Reporte_ventas']);
        Pestana::create(['nombre' => 'Reporte_ventas_filtro', 'slug' => 'Reporte_ventas_filtro','ruta' => '/reporte/ventas/filtrar']);
        Pestana::create(['nombre' => 'Historico', 'slug' => 'Historico','ruta' => '/historico']);
        Pestana::create(['nombre' => 'notificaciones', 'slug' => 'notificaciones','ruta' => '/notificaciones']);
        Pestana::create(['nombre' => 'Devoluciones', 'slug' => 'devoluciones','ruta' => '/devoluciones']);
        Pestana::create(['nombre' => 'reporte-productos', 'slug' => 'reporte-productos','ruta' => '/reporte-productos']);
    }
}
