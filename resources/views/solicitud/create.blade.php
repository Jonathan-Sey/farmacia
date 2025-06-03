@extends('template')
@section('titulo', 'Crear solicitud de articulos ')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-3xl mb-10">


            <div class="border-b border-gray-200 pb-6">
                <div class="mb-5">
                    <div class="flex gap-6 justify-center">
                        <div class="w-1/2">
                            <x-select2
                                name="id_sucursal_1"
                                label="Sucursal a solicitar"
                                :options="$sucursales->pluck('nombre', 'id')"
                                :selected="old('id_sucursal_1')"
                                placeholder="Seleccionar una Sucursal"
                                class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            />
                            {{-- <label for="id_sucursal_1" class="uppercase block text-sm font-medium text-gray-900">Sucursal a solicitar </label>
                            <select
                                class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                name="id_sucursal_1"
                                id="id_sucursal_1">
                                <option value="">Seleccionar una Sucursal</option>
                                @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" {{old('id_sucursal_1') == $sucursal->id ? 'selected' : ''}}>{{$sucursal->nombre}}</option>
                                @endforeach
                            </select>
                            @error('id_sucursal_1')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                            @enderror --}}
                        </div>

                        <div class="w-1/2 flex gap-6 p-6 items-center justify-center">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>

                        <div class="w-1/2">
                            <x-select2
                                name="id_sucursal_2"
                                label="Sucursal que solicita"
                                :options="$sucursales->pluck('nombre', 'id')"
                                :selected="old('id_sucursal_2')"
                                placeholder="Seleccionar una Sucursal"
                                id="id_sucursal_2"
                                class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            />
                            {{-- <label for="id_sucursal_2" class="uppercase block text-sm font-medium text-gray-900">Sucursal que solicita</label>
                            <select
                                class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                name="id_sucursal_2"
                                id="id_sucursal_2">
                                <option value="">Seleccionar una Sucursal</option>
                                @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" {{old('id_sucursal_2') == $sucursal->id ? 'selected' : ''}}>{{$sucursal->nombre}}</option>
                                @endforeach
                            </select>
                            @error('id_sucursal_2')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                            @enderror --}}
                        </div>
                    </div>
                </div>


                <!-- Producto -->
                <div class="mt-2 mb-5">
                    <x-select2
                        name="id_producto"
                        label="Producto que necesita"
                        :options="[]"
                        :selected="old('id_producto')"
                        placeholder="Seleccionar un producto"
                        id="id_producto"
                        class="select2-producto block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                    />
                    {{-- <label for="id_producto6" class="uppercase block text-sm font-medium text-gray-900">Producto que nesecesita</label>
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
                    @enderror --}}
                </div>

                <!-- Cantidad -->
                <div class="mt-2 mb-5">
                    <label for="cantidad" class="uppercase block text-sm font-medium text-gray-900">cantidad que nececita</label>
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

                <!-- Usuario -->

                <div class="mt-2">
                    <label for="descripcion" class="uppercase block text-sm/6 font-medium text-gray-900">Descripcion</label>
                    <textarea name="descripcion" id="descripcion"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{route('sucursales.index')}}" class="text-sm font-semibold p-4 text-gray-600 hover:text-gray-800">Cancelar</a>
                <button id="btn-agregar" type="button" class=" cursor-pointer mt-3 rounded-md bg-indigo-600 px-3 w-full py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Agregar</button>
            </div>

    </div>
</div>


{{-- tabla --}}

<form action="{{route('solicitud.store')}}" method="POST">
@csrf
<div class="mt-5">
    <h2 class="text-center m-5 font-bold text-lg">Detalle de la solicitud</h2>
    <div class="overflow-x-auto">
        <table id="tabla-productos" class="table  table-md table-pin-rows table-pin-cols">
            <thead>
                <tr>
                    <th></th>
                    <td>Sucursal de salida</td>
                    <td>Sucursal de entrada</td>
                    <td>Producto</td>
                    <td>Cantidad</td>
                    <td>descripcion</td>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {{--
                        <tr>
                            <th></th>
                        </tr> --}}

            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-x-6">

    <a href="{{route('solicitud.index')}} " id="btn-cancelar">
        <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
    </a>
    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
</div>
</form>
</div>
</div>

<script>
// Carga de productos según sucursal seleccionada
$(document).ready(function() {
    // Escuchar cambios en el select de sucursal (ahora es un Select2)
    $('#id_sucursal_1').on('change', function() {
        let sucursalId = $(this).val();
        let productosSelect = $('#id_producto');

        // Limpiar el select de productos
        productosSelect.empty().append('<option value="">Seleccione un producto</option>');
        productosSelect.val(null).trigger('change');

        if (sucursalId) {
            // Mostrar loading en el select de productos
            productosSelect.prop('disabled', true);

            fetch(`/productos-por-sucursal/${sucursalId}`)
                .then(response => response.json())
                .then(data => {
                    // Agregar nuevas opciones
                    data.forEach(producto => {
                        let option = new Option(producto.producto.nombre, producto.id_producto);
                        productosSelect.append(option);
                    });

                    // Habilitar y actualizar Select2
                    productosSelect.prop('disabled', false).trigger('change');
                })
                .catch(error => {
                    console.error('Error:', error);
                    productosSelect.prop('disabled', false);
                });
        }
    });

    // Mantener la funcionalidad de exclusión entre sucursales
    $('#id_sucursal_1').on('change', function() {
        let valor1 = $(this).val();
        let select2 = $('#id_sucursal_2');

        if (valor1) {
            // Obtener opciones originales (todas las sucursales)
            let opcionesOriginales = {!! $sucursales->pluck('nombre', 'id')->toJson() !!};

            // Filtrar para excluir la sucursal seleccionada
            let opcionesFiltradas = Object.keys(opcionesOriginales)
                .filter(id => id != valor1)
                .reduce((obj, key) => {
                    obj[key] = opcionesOriginales[key];
                    return obj;
                }, {});

            // Actualizar el segundo select
            select2.empty().select2({
                data: $.map(opcionesFiltradas, function(text, id) {
                    return { id: id, text: text };
                }),
                placeholder: "Seleccionar una Sucursal"
            });
        }
    });

    // Resto de tu código (agregarProducto, etc.)
    $('#btn-agregar').click(function() {
        agregarProducto();
    });
});

let contador = 0;

function agregarProducto() {
    let id_producto = $('#id_producto').val();
    let producto = $('#id_producto option:selected').text();
    let cantidad = $('#cantidad').val();
    let descripcion = $('#descripcion').val();
    let sucursal_1 = $('#id_sucursal_1').val();
    let sucursal_1T = $('#id_sucursal_1 option:selected').text();
    let sucursal_2 = $('#id_sucursal_2').val();
    let sucursal_2T = $('#id_sucursal_2 option:selected').text();

    if (id_producto && producto && cantidad && descripcion && sucursal_1 && sucursal_2) {
        if (parseInt(cantidad) > 0 && (cantidad % 1 == 0)) {
            contador++;
            $('#tabla-productos tbody').append(`
                <tr id="fila${contador}">
                    <th>${contador}</th>
                    <td><input type="hidden" name="arraySucursal1[]" value="${sucursal_1}">${sucursal_1T}</td>
                    <td><input type="hidden" name="arraySucursal2[]" value="${sucursal_2}">${sucursal_2T}</td>
                    <td><input type="hidden" name="arrayIdProducto[]" value="${id_producto}">${producto}</td>
                    <td><input type="hidden" name="arraycantidad[]" value="${cantidad}">${cantidad}</td>
                    <td><input type="hidden" name="arrayDescripcion[]" value="${descripcion}">${descripcion}</td>
                    <td><button type="button" onclick="eliminarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-trash"></i></button></td>
                </tr>`);
            limpiar();
        } else {
            mensaje('favor ingresar una cantidad entera');
        }
    } else {
        mensaje('Los campos estan vacios');
    }
}

function limpiar() {
    $('#id_producto').val(null).trigger('change');
    $('#cantidad').val('');
    $('#descripcion').val('');
}
</script>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/js/select2-global.js"></script>
@endpush
