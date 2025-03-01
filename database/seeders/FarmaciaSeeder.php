<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Str;
class FarmaciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productos = [];
        $secuencia = 1;
        $categorias = [
            [
                'nombre' => 'Medicamentos',
                'descripcion' => 'Productos farmacéuticos para el tratamiento de enfermedades.',
                'estado' => 1,
            ],
            [
                'nombre' => 'Cuidado Personal',
                'descripcion' => 'Productos para el cuidado e higiene personal.',
                'estado' => 1,
            ],
            [
                'nombre' => 'Vitaminas y Suplementos',
                'descripcion' => 'Suplementos alimenticios y vitaminas para la salud.',
                'estado' => 1,
            ],
            [
                'nombre' => 'Primeros Auxilios',
                'descripcion' => 'Productos para el tratamiento de heridas y emergencias.',
                'estado' => 1,
            ],
        ];
        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }

        // Crear productos
        $productosData = [
            [

                'nombre' => 'Paracetamol 500mg',
                'descripcion' => 'Analgésico y antipirético para el alivio del dolor y la fiebre.',
                'precio_venta' => 5.50,
                'fecha_caducidad' => '2025-12-31',
                'id_categoria' => 1, // Medicamentos
                'tipo' => 1,
                'estado' => 1,
            ],
            [

                'nombre' => 'Ibuprofeno 400mg',
                'descripcion' => 'Antiinflamatorio no esteroideo para el alivio del dolor y la inflamación.',
                'precio_venta' => 7.00,
                'fecha_caducidad' => '2025-11-30',
                'id_categoria' => 1, // Medicamentos
                'tipo' => 1,
                'estado' => 1,
            ],
            [

                'nombre' => 'Jabón Antibacterial',
                'descripcion' => 'Jabón para la higiene personal con acción antibacterial.',
                'precio_venta' => 3.20,
                'fecha_caducidad' => '2026-05-15',
                'id_categoria' => 2, // Cuidado Personal
                'tipo' => 1,
                'estado' => 1,
            ],
            [

                'nombre' => 'Vitamina C 1000mg',
                'descripcion' => 'Suplemento de vitamina C para fortalecer el sistema inmunológico.',
                'precio_venta' => 12.00,
                'fecha_caducidad' => '2025-10-20',
                'id_categoria' => 3, // Vitaminas y Suplementos
                'tipo' => 1,
                'estado' => 1,
            ],
            [

                'nombre' => 'Curitas Adhesivas',
                'descripcion' => 'Curitas para el tratamiento de heridas leves.',
                'precio_venta' => 2.50,
                'fecha_caducidad' => '2027-03-01',
                'id_categoria' => 4, // Primeros Auxilios
                'tipo' => 1,
                'estado' => 1,
            ],
        ];

        foreach ($productosData as $producto) {
            $codigo = sprintf('C-%05d', $secuencia++);
            $producto['codigo'] = $codigo;
            Producto::create($producto);
        }

        $this->command->info('Categorías y productos de farmacia creados exitosamente.');
    }

}
