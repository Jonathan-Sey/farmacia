@extends('template')
@section('titulo', 'Crear traslado de articulos ')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{route('traslado.store')}}" method="POST">
            @csrf
            <div class="border-b border-gray-200 pb-6">
                <div class="mb-5">
                    <div class="flex gap-6 justify-center">
                        <div class="w-1/2">
                            <label for="id_sucursal_1" class="uppercase block text-sm font-medium text-gray-900">Sucursal salida </label>
                            <select
                                class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                name="id_sucursal_1"
                                id="id_sucursal_1">
                                <option value="">Seleccionar una Socursal</option>
                                @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" {{old('id_sucursal_1') == $sucursal->id ? 'selected' : ''}}>{{$sucursal->nombre}}</option>
                                @endforeach
                            </select>
                            @error('id_sucursal_1')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>

                        <div class="w-1/2 flex gap-6 p-6 items-center justify-center">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>

                        <div class="w-1/2">
                            <label for="id_sucursal_2" class="uppercase block text-sm font-medium text-gray-900">Sucursal entrada</label>
                            <select
                                class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                name="id_sucursal_2"
                                id="id_sucursal_2">
                                <option value="">Seleccionar una Socursal</option>
                                @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" {{old('id_sucursal_2') == $sucursal->id ? 'selected' : ''}}>{{$sucursal->nombre}}</option>
                                @endforeach
                            </select>
                            @error('id_sucursal_2')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>


                <!-- Producto -->
                <div class="mt-2 mb-5">
                    <label for="id_producto" class="uppercase block text-sm font-medium text-gray-900">Producto</label>
                    <select
                        class="select2-producto block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        name="id_producto"
                        id="id_producto">
                        <option value="">Seleccionar un producto</option>
                        <!--       @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}" {{old('id_producto') == $producto->id ? 'selected' : ''}}>{{$producto->nombre}}</option>
                        @endforeach -->
                    </select>
                    @error('id_producto')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <!-- Cantidad -->
                <div class="mt-2 mb-5">
                    <label for="cantidad" class="uppercase block text-sm font-medium text-gray-900">cantidad</label>
                    <input
                        type="text"
                        name="cantidad"
                        id="cantidad"
                        autocomplete="given-name"
                        placeholder="cantidad"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('cantidad') }}">

                    @error('cantidad')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{route('sucursales.index')}}" class="text-sm font-semibold p-4 text-gray-600 hover:text-gray-800">Cancelar</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('id_sucursal_1').addEventListener('change', function() {
                let sucursalId = this.value;
                let productosSelect = document.getElementById('id_producto');

                // Limpiar el select de productos
                productosSelect.innerHTML = '<option value="">Seleccione un producto</option>';

                if (sucursalId) {
                    fetch(`/productos-por-sucursal/${sucursalId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(producto => {
                                let option = document.createElement('option');
                                option.value = producto.id_producto;
                                option.textContent = producto.producto.nombre;
                                productosSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }});
</script>

<script>
    var select1 = document.getElementById('id_sucursal_1');
    var select2 = document.getElementById('id_sucursal_2');


    const opcionesOriginales = Array.from(select2.options).map(option => option.cloneNode(true));

    select1.addEventListener('change', function() {
        let valor1 = select1.value;


        select2.innerHTML = '';

        opcionesOriginales.forEach(option => {
            if (option.value !== valor1) {
                select2.appendChild(option.cloneNode(true));
            }
        });
    });
</script>



@endsection
@push('js')

@endpush