<?php

namespace App\Imports;

use App\Models\Categoria;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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
//                'fecha_caducidad' => null
                'existe' => false,
                'cambios' => []
            ];
        }
    }
    private function getCategoryId($categoryName)
    {
        if (empty(trim($categoryName))) {
            Log::warning('Nombre de categoría vacío');
            return null; // Null indica que no se asignó categoría
        }

        // Normalizar el nombre de la categoría
        $normalized = strtolower(trim($categoryName));

        // Buscar coincidencia exacta (case insensitive)
        $category = Categoria::whereRaw('LOWER(nombre) = ?', [$normalized])->first();

        if (!$category) {
            Log::warning("Categoría '$categoryName' no encontrada");
            return null;
        }

        return $category->id;
    }

    public function getData()
    {
        return $this->data;
    }
}