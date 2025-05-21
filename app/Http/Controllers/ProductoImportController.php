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
    Log::info('Iniciando procesamiento de importación');

    $request->validate([
        'archivo' => 'required|mimes:xlsx,xls'
    ]);

    Log::info('Archivo validado correctamente');

    $import = new ProductosImport;

    try {
        Log::info('Antes de importar Excel');
        Excel::import($import, $request->file('archivo'));
        Log::info('Después de importar Excel');

        $data = $import->getData();
        Log::info('Datos obtenidos del import:', $data);

        if (empty($data)) {
            Log::warning('El archivo no contiene datos o no se procesó correctamente');
            return back()->withErrors(['error' => 'El archivo no contiene datos válidos']);
        }

        $productosConEstado = [];
        foreach ($import->getData() as $producto) {
            $productoExistente = Producto::where('codigo', $producto['codigo'])->first();

            $producto['existe'] = (bool)$productoExistente;
            $producto['cambios'] = [];

            if ($productoExistente) {
                // Comparación de NOMBRE
                if ($productoExistente->nombre != $producto['nombre']) {
                    $producto['cambios']['nombre'] = [
                        'actual' => $productoExistente->nombre,
                        'nuevo' => $producto['nombre']
                    ];
                }

                // Comparación de PRECIO
                if ($productoExistente->precio_venta != $producto['precio_venta']) {
                    $producto['cambios']['precio_venta'] = [
                        'actual' => $productoExistente->precio_venta,
                        'nuevo' => $producto['precio_venta']
                    ];
                }

                // Comparación de CATEGORÍA
                if ($productoExistente->id_categoria != $producto['id_categoria']) {
                    $producto['cambios']['id_categoria'] = [
                        'actual_id' => $productoExistente->id_categoria,
                        'actual_nombre' => optional($productoExistente->categoria)->nombre ?? 'Sin categoría',
                        'nuevo_id' => $producto['id_categoria'],
                        'nuevo_nombre' => optional(Categoria::find($producto['id_categoria']))->nombre ?? 'Sin categoría'
                    ];
                }
            }

            $productosConEstado[] = $producto;
        }


        Log::info('Productos procesados:', $productosConEstado);

        session(['productos_importados' => $productosConEstado]);

        return view('producto.previsualizacion', [
            'productos' => $productosConEstado,
            'categorias' => Categoria::all()
        ]);

    } catch (\Exception $e) {
        Log::error('Error en importación: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Error al procesar el archivo: ' . $e->getMessage()]);
    }
}

public function guardarImportacion(Request $request)
{
    $validator = Validator::make($request->all(), [
        'productos.*.id_categoria' => 'required|exists:categoria,id'
    ], [
        'productos.*.id_categoria.required' => 'Todos los productos deben tener una categoría asignada',
        'productos.*.id_categoria.exists' => 'Una o más categorías seleccionadas no existen en el sistema'
    ]);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput();
    }

    $productos = $request->input('productos', []);
    $errores = [];
    $successCount = 0;
    $updatedCount = 0;

    foreach ($productos as $index => $producto) {
        try {
            // Validación más estricta para categoría
            $validator = Validator::make($producto, [
                'codigo' => 'required|max:12',
                'nombre' => 'required|max:255',
                'precio_venta' => 'required|numeric|min:0',
                'id_categoria' => [
                    'required',
                    'integer',
                    function ($attribute, $value, $fail) {
                        if (empty($value)) {
                            $fail('Debe seleccionar una categoría');
                        }
                        if (!Categoria::find($value)) {
                            $fail('La categoría seleccionada no existe');
                        }
                    }
                ]
            ]);

            if ($validator->fails()) {
                $errores["Fila {$index}"] = $validator->errors()->all();
                continue;
            }

            DB::transaction(function () use ($producto, &$successCount, &$updatedCount) {
                $productoExistente = Producto::where('codigo', $producto['codigo'])->first();

                if ($productoExistente) {
                    $productoExistente->update([
                        'nombre' => $producto['nombre'],
                        'precio_venta' => $producto['precio_venta'],
                        'precio_porcentaje' => $producto['precio_venta'],
                        'id_categoria' => $producto['id_categoria']
                    ]);
                    $updatedCount++;
                } else {
                    Producto::create([
                        'codigo' => $producto['codigo'],
                        'nombre' => $producto['nombre'],
                        'ultimo_precio_compra' => 0,
                        'precio_venta' => $producto['precio_venta'],
                        'precio_porcentaje' => $producto['precio_venta'],
                        'id_categoria' => $producto['id_categoria'],
                        'imagen' => null,
                        'descripcion' => null,
                        'tipo' => 1,
                        'estado' => 1
                    ]);
                    $successCount++;
                }
            });

        } catch (\Exception $e) {
            $errores["Fila {$index}"] = $e->getMessage();
            Log::error("Error al importar producto: " . $e->getMessage());
        }
    }

    $request->session()->forget('productos_importados');

    $message = [
        'Se importaron '.$successCount.' productos nuevos',
        'Se actualizaron '.$updatedCount.' productos existentes'
    ];

    if (count($errores) > 0) {
        return back()
            ->withInput()
            ->withErrors(['errores' => $errores])
            ->with('success', $message);
    }

    return redirect()
        ->route('productos.index')
        ->with('success', $message);
}


public function eliminarDeImportacion(Request $request, $index)
{
    $productos = session('productos_importados', []);

    if (isset($productos[$index])) {
        unset($productos[$index]);
        session(['productos_importados' => $productos]);

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 404);
}
}
