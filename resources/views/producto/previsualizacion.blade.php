@extends('template')

@section('titulo', 'Previsualización de Importación')

@section('contenido')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold mb-5">Previsualización de Productos a Importar</h2>

    <form action="{{ route('productos.importar.guardar') }}" method="POST">
        @csrf

        <div class="mb-4">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                Confirmar Importación
            </button>
            <a href="{{ route('productos.importar') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded ml-2">
                Volver
            </a>
        </div>
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ is_array($error) ? implode(', ', $error) : $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b">Código</th>
                        <th class="py-2 px-4 border-b">Nombre</th>
                        <th class="py-2 px-4 border-b">Precio Venta</th>
                        <th class="py-2 px-4 border-b">Categoría</th>
                        <th class="py-2 px-4 border-b">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $index => $producto)
                    <tr>
                        <td class="py-2 px-4 border-b">
                            <input type="text" name="productos[{{$index}}][codigo]" value="{{ $producto['codigo'] }}" class="w-full px-2 py-1 border rounded">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="text" name="productos[{{$index}}][nombre]" value="{{ $producto['nombre'] }}" class="w-full px-2 py-1 border rounded">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="number" step="0.01" name="productos[{{$index}}][precio_venta]" value="{{ $producto['precio_venta'] }}" class="w-full px-2 py-1 border rounded">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <select name="productos[{{$index}}][id_categoria]" class="w-full px-2 py-1 border rounded">
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ $producto['id_categoria'] == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <button type="button" class="text-red-500 hover:text-red-700 remove-row">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Eliminar fila
    document.querySelectorAll('.remove-row').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('tr').remove();
        });
    });
});
</script>

@endsection