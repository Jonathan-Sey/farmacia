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
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
                    <tr class="@if($producto['existe']) bg-yellow-50 @endif">
                        <td class="py-2 px-4 border-b">
                            <input type="text" name="productos[{{$index}}][codigo]"
                                   value="{{ old("productos.$index.codigo", $producto['codigo']) }}"
                                   class="w-full px-2 py-1 border rounded @if($producto['existe']) border-yellow-400 @endif"
                                   @if($producto['existe']) readonly @endif>
                            @if($producto['existe'])
                            <span class="text-yellow-600 text-xs">Producto existente</span>
                            @endif
                            @error("productos.$index.codigo")
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </td>
                        <td class="py-2 px-4 border-b">
                            <input type="text" name="productos[{{$index}}][nombre]"
                                   value="{{ old("productos.$index.nombre", $producto['nombre']) }}"
                                   class="w-full px-2 py-1 border rounded @if(isset($producto['cambios']['nombre'])) border-blue-400 @endif">

                            @if(isset($producto['cambios']['nombre']))
                            <div class="text-blue-600 text-xs mt-1">
                                <div><strong>Actual:</strong> {{ $producto['cambios']['nombre']['actual'] }}</div>
                                <div><strong>Nuevo:</strong> {{ $producto['cambios']['nombre']['nuevo'] }}</div>
                            </div>
                            @endif
                        </td>

                        <td class="py-2 px-4 border-b">
                            <input type="number" step="0.01" name="productos[{{$index}}][precio_venta]"
                                   value="{{ old("productos.$index.precio_venta", $producto['precio_venta']) }}"
                                   class="w-full px-2 py-1 border rounded @if(isset($producto['cambios']['precio_venta'])) border-blue-400 @endif">

                            @if(isset($producto['cambios']['precio_venta']))
                            <div class="text-blue-600 text-xs mt-1">
                                <div><strong>Actual:</strong> {{ number_format($producto['cambios']['precio_venta']['actual'], 2) }}</div>
                                <div><strong>Nuevo:</strong> {{ number_format($producto['cambios']['precio_venta']['nuevo'], 2) }}</div>
                            </div>
                            @endif
                        </td>

                        <td class="py-2 px-4 border-b">
                            <select name="productos[{{$index}}][id_categoria]"
                                    class="w-full px-2 py-1 border rounded
                                           @if(is_null($producto['id_categoria'])) border-red-500
                                           @elseif($producto['existe'] && isset($producto['cambios']['id_categoria'])) border-blue-400 @endif"
                                    required>
                                <option value="">Seleccione categoría</option>
                                @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                        @if(old("productos.$index.id_categoria", $producto['id_categoria']) == $categoria->id) selected @endif>
                                    {{ $categoria->nombre }} (ID: {{ $categoria->id }})
                                </option>
                                @endforeach
                            </select>

                            <!-- Mensaje para categoría no seleccionada -->
                            @if(is_null($producto['id_categoria']))
                            <div class="text-red-500 text-xs mt-1">
                                <i class="fas fa-exclamation-triangle"></i> Categoría original no encontrada en el sistema
                            </div>
                            @endif

                            <!-- Comparación de cambios en categoría -->
                            @if($producto['existe'] && isset($producto['cambios']['id_categoria']))
                            <div class="text-blue-600 text-xs mt-1">
                                <div><strong>Actual:</strong>
                                    {{ $producto['cambios']['id_categoria']['actual_id'] }} -
                                    {{ $producto['cambios']['id_categoria']['actual_nombre'] }}
                                </div>
                                <div><strong>Nuevo:</strong>
                                    {{ $producto['id_categoria'] }} -
                                    {{ $categorias->firstWhere('id', $producto['id_categoria'])->nombre ?? 'Sin categoría' }}
                                </div>
                            </div>
                            @endif

                            @error("productos.$index.id_categoria")
                            <div class="text-red-500 text-xs mt-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                            @enderror
                        </td>
                        <td class="py-2 px-4 border-b">
                            @if($producto['existe'])
                            <span class="text-yellow-600">Existente</span>
                            @else
                            <span class="text-green-600">Nuevo</span>
                            @endif
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

     // Resaltar todas las celdas con cambios
     document.querySelectorAll('input, select').forEach(element => {
        if (element.classList.contains('border-blue-400')) {
            element.parentNode.classList.add('bg-blue-50');
        }
    });

    // Validación antes de enviar
    const form = document.querySelector('form');

    form.addEventListener('submit', function(e) {
        let hasErrors = false;
        const emptyCategorySelects = [];

        document.querySelectorAll('select[name*="[id_categoria]"]').forEach((select, index) => {
            if (!select.value) {
                hasErrors = true;
                select.classList.add('border-red-500');
                emptyCategorySelects.push(index + 1);

                // Crear mensaje de error si no existe
                if (!select.nextElementSibling || !select.nextElementSibling.classList.contains('select-error-message')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'text-red-500 text-xs mt-1 select-error-message';
                    errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Debe seleccionar una categoría';
                    select.parentNode.insertBefore(errorDiv, select.nextElementSibling);
                }
            }
        });

        if (hasErrors) {
            e.preventDefault();

            Swal.fire({
                title: 'Error en categorías',
                html: `Las siguientes filas tienen categoría no seleccionada: <strong>${emptyCategorySelects.join(', ')}</strong>`,
                icon: 'error',
                confirmButtonText: 'Entendido',
                focusConfirm: false,
                scrollbarPadding: false
            }).then(() => {
                // Desplazarse al primer error
                const firstError = document.querySelector('select.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    firstError.focus();
                }
            });
        }
    });

    // Remover error al seleccionar categoría
    document.querySelectorAll('select[name*="[id_categoria]"]').forEach(select => {
        select.addEventListener('change', function() {
            if (this.value) {
                this.classList.remove('border-red-500');
                const errorMsg = this.nextElementSibling;
                if (errorMsg && errorMsg.classList.contains('select-error-message')) {
                    errorMsg.remove();
                }
            }
        });
    });
});
    // Eliminar fila
    document.querySelectorAll('.remove-row').forEach(button => {
        button.addEventListener('click', function() {
            if(confirm('¿Está seguro de eliminar este producto de la importación?')) {
                const index = this.getAttribute('data-index');
                fetch(`/productos/importar/eliminar/${index}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        this.closest('tr').remove();
                    }
                });
            }
        });
    });
});
</script>
@endsection