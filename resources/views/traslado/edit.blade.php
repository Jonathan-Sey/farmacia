@extends('template')
@section('titulo', 'Editar transferencia')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{route('traslado.update',['traslado'=> $traslado->id])}}" method="POST">
            @csrf
            @method('PATCH')
            <div id="usuario">

            </div>
            <div class="border-b border-gray-200 pb-6">
                <div class="mb-5">
                    <div class="flex gap-6 justify-center">
                        <div class="w-1/2">
                            <label for="id_sucursal_1" class="uppercase block text-sm font-medium text-gray-900">Sucursal salida </label>
                            <select
                                class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                name="id_sucursal_1"
                                id="id_sucursal_1">
                                <option value="">Seleccionar una Sucursal</option>
                                @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" {{$sucursal->id == $traslado->id_sucursal_origen ? 'selected' : ''}}>{{$sucursal->nombre}}</option>
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
                                <option value="">Seleccionar una Sucursal</option>
                                @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" {{$sucursal->id == $traslado->id_sucursal_destino ? 'selected' : ''}}>{{$sucursal->nombre}}</option>
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
                      <!--  @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}" {{$producto->id == $traslado->id_producto ? 'selected' : ''}}>{{$producto->nombre}}</option>
                        @endforeach-->
                    </select>
                    @error('id_producto')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <!-- Cantidad -->
                <div class="mt-2 mb-5">
                    <label for="cantidad" class="uppercase block text-sm font-medium text-gray-900">Cantidad</label>
                    <input
                        type="text"
                        name="cantidad"
                        id="cantidad"
                        autocomplete="given-name"
                        placeholder="Cantidad"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('cantidad',$traslado->cantidad) }}">

                    @error('cantidad')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{route('traslado.index')}}" class="text-sm font-semibold p-4 text-gray-600 hover:text-gray-800">Cancelar</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>


@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    document.addEventListener('DOMContentLoaded', function(

    ) {
        var select1 = document.getElementById('id_sucursal_1');
        var select2 = document.getElementById('id_sucursal_2');
        const opcionesOriginales = Array.from(select2.options);


        let valor1 = select1.value;


        select2.innerHTML = '';
        
        opcionesOriginales.forEach(option => {
            if (option.value !== valor1) {
                select2.appendChild(option.cloneNode(true));
            }
        });

    })

    var select1 = document.getElementById('id_sucursal_1');
    var select2 = document.getElementById('id_sucursal_2');


    const opcionesOriginales = Array.from(select2.options);

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
<script>
    // limitar la fecha a datos actuales
    document.addEventListener('DOMContentLoaded', function() {
        var DatoActual = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_caducidad').setAttribute('min', DatoActual);

    });
    // fin fecha

    //uso del select2
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: "Seleccione una categoría",
            allowClear: true
        });
    });
    // pocicionar el cursor en el input para buscar producto
    $('.select2').on('select2:open', function() {
        document.querySelector('.select2-search__field').focus();
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tipoCheckbox = document.getElementById('tipo');
        const fechaCaducidadContainer = document.getElementById('fecha_caducidad_container');
        const fechaCaducidadInput = document.getElementById('fecha_caducidad');

        const toggleFechaCaducidad = () => {
            if (tipoCheckbox.checked) {
                fechaCaducidadContainer.style.display = 'none';
                fechaCaducidadInput.removeAttribute('required');
                fechaCaducidadInput.value = '';
            } else {
                fechaCaducidadContainer.style.display = 'block';
                fechaCaducidadInput.setAttribute('required', 'required');
            }
        };

        tipoCheckbox.addEventListener('change', toggleFechaCaducidad);
        // Ejecutar la función al cargar la página para establecer el estado inicial
        toggleFechaCaducidad();
    });
</script>


@endpush