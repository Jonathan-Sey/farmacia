<?php

namespace App\Imports;

use App\Models\Categoria;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosImport implements ToCollection, WithHeadingRow
{
    protected $data = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $categoriaNombre = $row['categoria'] ?? $row['categoría'] ?? null;
            $categoriaId = $this->getCategoryId($categoriaNombre);

            $this->data[] = [
                'codigo' => $row['codigo'] ?? null,
                'nombre' => $row['nombre'] ?? null,
                'ultimo_precio_compra' => 0,
                'precio_venta' => $row['precio_anterior'] ?? 0,
                'precio_porcentaje' => $row['precio_anterior'] ?? 0,
                'id_categoria' => $categoriaId,
                'estado' => 1,
                'imagen' => null,
                'tipo' => 1,
                'descripcion' => null,
                'fecha_caducidad' => null
            ];
        }
    }

    private function getCategoryId($categoryName)
    {
        if (empty($categoryName)) {
            return 1; // ID por defecto
        }

        // Busca categoria
        $category = Categoria::where('nombre', 'like', $categoryName)->first();

        if (!$category) {
            // Intenta buscar sin distinguir mayúsculas/minúsculas
            $category = Categoria::whereRaw('LOWER(nombre) = ?', [strtolower($categoryName)])->first();
        }

        return $category ? $category->id : 1; // Retorna 1 si no encuentra
    }
    public function getData()
    {
        return $this->data;
    }
}