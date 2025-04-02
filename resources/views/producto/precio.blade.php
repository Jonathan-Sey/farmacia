@extends('template')
@section('titulo', 'Editar Porcentaje del precio')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('productos.actualizarprecio', $producto->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="border-b border-gray-900/10 pb-12">
                <div id="producto">
                    <p>Producto: {{ $producto->nombre }}</p>
                    <p>Precio Actual: <span id="precio_actual">{{ $producto->precio_porcentaje }}</span></p>
                </div>
                <!-- Nuevos campos para el precio -->
                <div class="mt-2 mb-5">
                    <label for="porcentaje" class="uppercase block text-sm font-medium text-gray-900">Porcentaje de incremento</label>
                    <input
                        type="number"
                        name="porcentaje"
                        id="porcentaje"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-none"
                        placeholder="Ingrese el porcentaje"
                        value="{{ old('porcentaje') }}"
                        min="0"
                        step="0.01"
                    />
                </div>

                <div class="mt-2 mb-5">
                    <label for="nuevo_precio" class="uppercase block text-sm font-medium text-gray-900">Nuevo Precio</label>
                    <input
                        type="text"
                        name="nuevo_precio"
                        id="nuevo_precio"
                        readonly
                        class="block w-full rounded-md bg-gray-100 px-3 py-1.5 text-base text-gray-900 outline-none"
                        value="{{ old('nuevo_precio', $producto->precio_porcentaje) }}"
                    />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('productos.index') }}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    // Script para calcular el nuevo precio en base al porcentaje
    document.getElementById('porcentaje').addEventListener('input', function() {
        let precioActual = parseFloat(document.getElementById('precio_actual').textContent);
        let porcentaje = parseFloat(this.value);

        // Verificamos si el porcentaje es válido y calculamos el nuevo precio
        if (!isNaN(porcentaje)) {
            let nuevoPrecio = precioActual + (precioActual * porcentaje / 100);
            document.getElementById('nuevo_precio').value = nuevoPrecio.toFixed(2); // Mostrar con 2 decimales
        } else {
            document.getElementById('nuevo_precio').value = precioActual.toFixed(2); // En caso de que no haya porcentaje
        }
    });
</script>
@endpush
