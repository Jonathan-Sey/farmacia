<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductosImport;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductoImportController extends Controller
{
    public function mostrarImportacion()
    {
        return view('producto.importar');
    }

    public function procesarImportacion(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls'
        ]);

        $import = new ProductosImport;
        Excel::import($import, $request->file('archivo'));

         // Guardamos los datos temporalmente en sesiones
        session(['productos_importados' => $import->getData()]);

        return view('producto.previsualizacion', [
            'productos' => $import->getData(),
            'categorias' => Categoria::all()
        ]);
    }

    public function guardarImportacion(Request $request)
{
    $productos = session('productos_importados', []);
    $errores = [];
    $successCount = 0;

    // Verifica la sesión
    Log::info('Datos a importar:', $productos);

    foreach ($productos as $index => $producto) {
        try {
            // Validación
            $validator = Validator::make($producto, [
                'codigo' => 'required|unique:producto,codigo|max:12',
                'nombre' => 'required|max:255',
                'precio_venta' => 'required|numeric|min:0',
                'id_categoria' => 'required|exists:categoria,id'
            ]);

            if ($validator->fails()) {
                $errores["Fila {$index}"] = $validator->errors()->all();
                continue;
            }

            // Debug: Verifica datos antes de crear
            Log::info('Creando producto:', $producto);

            // Crear el producto con transacción
            DB::transaction(function () use ($producto) {
                Producto::create([
                    'codigo' => $producto['codigo'],
                    'nombre' => $producto['nombre'],
                    'ultimo_precio_compra' => $producto['ultimo_precio_compra'],
                    'precio_venta' => $producto['precio_venta'],
                    'precio_porcentaje' => $producto['precio_venta'],
                    'id_categoria' => $producto['id_categoria'],
                    'imagen' => null,
                    'descripcion' => null,
                    'tipo' => 1,
                    'fecha_caducidad' => null,
                    'estado' => 1
                ]);
            });

            $successCount++;

        } catch (\Exception $e) {
            $errores["Fila {$index}"] = $e->getMessage();
            Log::error("Error al importar producto: " . $e->getMessage());
        }
    }

    $request->session()->forget('productos_importados');

    if (count($errores) > 0) {
        return back()
            ->withErrors(['errores' => $errores])
            ->with('success_count', $successCount);
    }

    return redirect()
        ->route('productos.index')
        ->with('success', "Se importaron {$successCount} productos correctamente");
}
}
