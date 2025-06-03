@extends('template')
@section('titulo', 'Editar transferencia')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .stock-info {
        background-color: #f3f4f6;
        padding: 0.5rem;
        border-radius: 0.375rem;
        margin-top: 0.25rem;
        font-size: 0.875rem;
    }
    .stock-label {
        font-weight: 600;
        color: #4b5563;
    }
    .stock-value {
        color: #1f2937;
        font-weight: 700;
    }
</style>
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{route('traslado.update',['traslado'=> $traslado->id])}}" method="POST">
            @csrf
            @method('PATCH')
            <div id="usuario"></div>

            <div class="border-b border-gray-200 pb-6">
                <div class="mb-5">
                    <div class="flex gap-6 justify-center">
                        <!-- Sucursal Origen -->
                        <div class="w-1/2">
                            <label for="id_sucursal_1" class="uppercase block text-sm font-medium text-gray-900">Sucursal salida</label>
                            <select class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                    name="id_sucursal_1" id="id_sucursal_1" required>
                                <option value="">Seleccionar sucursal</option>
                                @foreach ($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}" {{$sucursal->id == $traslado->id_sucursal_origen ? 'selected' : ''}}>{{ $sucursal->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_sucursal_1')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="w-1/2 flex gap-6 p-6 items-center justify-center">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>

                        <!-- Sucursal Destino -->
                        <div class="w-1/2">
                            <label for="id_sucursal_2" class="uppercase block text-sm font-medium text-gray-900">Sucursal entrada</label>
                            <select class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                    name="id_sucursal_2" id="id_sucursal_2" required>
                                <option value="">Seleccionar sucursal</option>
                                @foreach ($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}" {{$sucursal->id == $traslado->id_sucursal_destino ? 'selected' : ''}}>{{ $sucursal->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_sucursal_2')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Producto -->
                <div class="mt-2 mb-5">
                    <label for="id_producto" class="uppercase block text-sm font-medium text-gray-900">Producto</label>
                    <select class="select2-producto block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            name="id_producto" id="id_producto" required>
                        <option value="">Seleccione un producto</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}"
                                    {{$producto->id == $traslado->id_producto ? 'selected' : ''}}
                                    data-stock="{{ $producto->stock_actual ?? 0 }}">
                                {{ $producto->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_producto')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <!-- Mostrar stock disponible -->
                    <div id="stock-disponible" class="stock-info {{ $traslado->id_producto ? '' : 'hidden' }}">
                        <span class="stock-label">Stock disponible en sucursal:</span>
                        <span id="stock-value" class="stock-value">
                            @if($traslado->id_producto)
                                {{ $productos->firstWhere('id', $traslado->id_producto)->stock_actual ?? 0 }}
                            @else
                                0
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Cantidad -->
                <!-- Dentro de la sección de Cantidad, reemplaza este div -->
                <div class="mt-2 mb-5">
                    <label for="cantidad" class="uppercase block text-sm font-medium text-gray-900">Cantidad a transferir</label>

                    <!-- Mostrar cantidad original como referencia -->
                    <div class="mb-2 text-sm text-gray-600">
                        <span class="font-medium">Cantidad original:</span>
                        <span id="cantidad-original-value">{{ $traslado->cantidad }}</span>
                    </div>

                    <input type="number" name="cantidad" id="cantidad" min="1"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        placeholder="Ingrese la nueva cantidad"
                        value="{{ old('cantidad', $traslado->cantidad) }}"
                        required>

                    <div id="cantidad-error" class="text-red-500 text-sm mt-1 hidden">
                        La cantidad supera el stock disponible
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{route('traslado.index')}}" class="text-sm font-semibold p-4 text-gray-600 hover:text-gray-800">Cancelar</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    Actualizar traslado
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/js/obtenerUsuario.js"></script>

<script>
$(document).ready(function() {
    // Inicializar selects
    $('.select2-sucursal, .select2-producto').select2({
        width: '100%',
        placeholder: "Seleccionar",
        allowClear: true
    });

    // Variables para controlar stock
    let stockDisponible = {{ $traslado->id_producto ? $productos->firstWhere('id', $traslado->id_producto)->stock_actual ?? 0 : 0 }};
    const cantidadOriginal = parseInt('{{ $traslado->cantidad }}');

    // Cuando cambia la sucursal de origen
    $('#id_sucursal_1').on('change', function() {
        const sucursalId = $(this).val();
        const productoSelect = $('#id_producto');

        // Resetear campos
        productoSelect.empty().append('<option value="">Cargando productos...</option>');
        $('#stock-disponible').addClass('hidden');
        $('#cantidad').val(cantidadOriginal).prop('disabled', true);
        $('#cantidad-error').addClass('hidden');

        if (sucursalId) {
            // Cargar productos disponibles en la sucursal
            fetch(`/productos-por-sucursal/${sucursalId}`)
                .then(response => response.json())
                .then(data => {
                    productoSelect.empty().append('<option value="">Seleccione un producto</option>');

                    data.forEach(producto => {
                        if (producto.producto.tipo === 1) { // Solo productos físicos
                            const option = new Option(
                                `${producto.producto.nombre}`,
                                producto.id_producto,
                                false,
                                false
                            );
                            option.dataset.stock = producto.cantidad;
                            productoSelect.append(option);
                        }
                    });

                    // Seleccionar el producto actual si está disponible en la nueva sucursal
                    const currentProductId = '{{ $traslado->id_producto }}';
                    if (currentProductId && data.some(p => p.id_producto == currentProductId)) {
                        productoSelect.val(currentProductId).trigger('change');
                    }

                    productoSelect.prop('disabled', false).trigger('change');
                })
                .catch(error => {
                    productoSelect.empty().append('<option value="">Error al cargar productos</option>');
                    console.error('Error:', error);
                });
        } else {
            productoSelect.empty().append('<option value="">Seleccione primero una sucursal</option>')
                .prop('disabled', true);
        }
    });

    // Cuando se selecciona un producto
    $('#id_producto').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        stockDisponible = selectedOption.data('stock') || 0;

        if ($(this).val() && stockDisponible > 0) {
            $('#stock-value').text(stockDisponible);
            $('#stock-disponible').removeClass('hidden');
            $('#cantidad').prop('disabled', false)
                .attr('max', stockDisponible + cantidadOriginal)
                .attr('placeholder', `Máximo aumento: ${stockDisponible} (Total: ${stockDisponible + cantidadOriginal})`);
        } else {
            $('#stock-disponible').addClass('hidden');
            $('#cantidad').val(cantidadOriginal).prop('disabled', true);
        }
    });

    // Validar cantidad en tiempo real
    $('#cantidad').on('input', function() {
        const cantidad = parseInt($(this).val()) || 0;

        // Si estamos reduciendo la cantidad, no hay problema
        if (cantidad <= cantidadOriginal) {
            $('#cantidad-error').addClass('hidden');
            return;
        }

        // Si estamos aumentando, validar contra el stock disponible
        const incremento = cantidad - cantidadOriginal;
        if (incremento > stockDisponible) {
            $('#cantidad-error').removeClass('hidden');
        } else {
            $('#cantidad-error').addClass('hidden');
        }
    });

    // Exclusión entre sucursales
    $('#id_sucursal_1').on('change', function() {
        const selectedId = $(this).val();
        const selectDestino = $('#id_sucursal_2');

        if (selectedId) {
            selectDestino.find('option').prop('disabled', false);
            selectDestino.find(`option[value="${selectedId}"]`).prop('disabled', true);

            if (selectDestino.val() === selectedId) {
                selectDestino.val('').trigger('change');
            }
        }
    });

    // Inicializar el estado de los selects al cargar
    if ($('#id_sucursal_1').val()) {
        $('#id_sucursal_1').trigger('change');
    }
});
</script>
@endpush