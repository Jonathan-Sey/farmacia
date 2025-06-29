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
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <div class="w-full md:w-1/2">
                        <x-select2
                            name="id_sucursal_1"
                            label="Sucursal a solicitar"
                            :options="$sucursales->pluck('nombre', 'id')"
                            :selected="old('id_sucursal_1')"
                            placeholder="Seleccionar una Sucursal"
                            class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
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

                    <!-- Flecha - Solo visible en pantallas medianas/grandes -->
                    <div class="hidden md:flex w-1/2 gap-6 p-6 items-center justify-center">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>

                    <!-- Flecha para móviles - Aparece entre los selects -->
                    <div class="md:hidden flex justify-center py-2">
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>

                    <div class="w-full md:w-1/2">
                        <x-select2
                            name="id_sucursal_2"
                            label="Sucursal que solicita"
                            :options="$sucursales->pluck('nombre', 'id')"
                            :selected="old('id_sucursal_2')"
                            placeholder="Seleccionar una Sucursal"
                            id="id_sucursal_2"
                            class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
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
                    class="select2-producto block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
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
            <a href="{{route('almacenes.index')}}" class="text-sm font-semibold p-4 text-gray-600 hover:text-gray-800">Cancelar</a>
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
            <div id="usuario">

            </div>

            <table id="tabla-productos" class="table  table-md table-pin-rows table-pin-cols">
                <thead>
                    <tr>
                        <th></th>
                        <td>Sucursal de salida</td>
                        <td>Sucursal de entrada</td>
                        <td>Producto</td>
                        <td>Cantidad</td>
                        <td>descripcion</td>
                        <th>Acciones</th>
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
        // Escuchar cambios en el select de sucursal
        $('#id_sucursal_1').on('change', function() {
            let sucursalId = $(this).val();
            let productosSelect = $('#id_producto');

            productosSelect.empty().append('<option value="">Seleccione un producto</option>');
            productosSelect.val(null).trigger('change');

            if (sucursalId) {
                productosSelect.prop('disabled', true);

                fetch(`/productos-por-sucursal/${sucursalId}?tipo=1`) // Filtra por tipo=1 (productos)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(producto => {
                            // Verificar que sea producto (tipo=1)
                            if (producto.producto && producto.producto.tipo == 1) {
                                let option = new Option(producto.producto.nombre, producto.id_producto);
                                productosSelect.append(option);
                            }
                        });
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
                let opcionesOriginales = {
                    !!$sucursales - > pluck('nombre', 'id') - > toJson() !!
                };

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
                        return {
                            id: id,
                            text: text
                        };
                    }),
                    placeholder: "Seleccionar una Sucursal"
                });
            }
        });

        // Configurar el botón agregar/actualizar
        $('#btn-agregar').click(function() {
            if ($(this).text() === 'Actualizar') {
                let id = $(this).data('edit-id');
                actualizarProducto(id);
            } else {
                agregarProducto();
            }
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

        // Validación de campos requeridos
        let errores = [];
        if (!sucursal_1) errores.push('Debe seleccionar una sucursal de origen');
        if (!sucursal_2) errores.push('Debe seleccionar una sucursal destino');
        if (!id_producto) errores.push('Debe seleccionar un producto');
        if (!cantidad) errores.push('Debe ingresar una cantidad');
        if (!descripcion) errores.push('Debe ingresar una descripción');

        if (errores.length > 0) {
            mensaje(errores.join('<br>'));
            return;
        }

        // Validación de cantidad entera positiva
        if (!/^\d+$/.test(cantidad) || parseInt(cantidad) <= 0) {
            mensaje('La cantidad debe ser un número entero positivo');
            return;
        }

        // Verificar si el producto ya existe en la tabla
        let productoExistente = $(`#tabla-productos tbody tr input[name="arrayIdProducto[]"][value="${id_producto}"]`).closest('tr');
        if (productoExistente.length > 0) {
            Swal.fire({
                title: 'Producto ya agregado',
                text: 'Este producto ya está en la solicitud. ¿Desea editarlo?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, editar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let id = productoExistente.attr('id').replace('fila', '');
                    editarProducto(id);
                }
            });
            return;
        }

        contador++;
        $('#tabla-productos tbody').append(`
        <tr id="fila${contador}">
            <th>${contador}</th>
            <td><input type="hidden" name="arraySucursal1[]" value="${sucursal_1}">${sucursal_1T}</td>
            <td><input type="hidden" name="arraySucursal2[]" value="${sucursal_2}">${sucursal_2T}</td>
            <td><input type="hidden" name="arrayIdProducto[]" value="${id_producto}">${producto}</td>
            <td><input type="hidden" name="arraycantidad[]" value="${cantidad}">${cantidad}</td>
            <td><input type="hidden" name="arrayDescripcion[]" value="${descripcion}">${descripcion}</td>
            <td class="flex gap-2">
                <button type="button" onclick="editarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-edit"></i></button>
                <button type="button" onclick="eliminarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-trash"></i></button>
             </td>
        </td>`);

        limpiar();
    }

    function editarProducto(id) {
        let fila = $(`#fila${id}`);

        // Obtener valores actuales
        let sucursal1 = fila.find('input[name="arraySucursal1[]"]').val();
        let sucursal1T = $(`#id_sucursal_1 option[value="${sucursal1}"]`).text();
        let sucursal2 = fila.find('input[name="arraySucursal2[]"]').val();
        let sucursal2T = $(`#id_sucursal_2 option[value="${sucursal2}"]`).text();
        let producto = fila.find('input[name="arrayIdProducto[]"]').val();
        let productoT = $(`#id_producto option[value="${producto}"]`).text();
        let cantidad = fila.find('input[name="arraycantidad[]"]').val();
        let descripcion = fila.find('input[name="arrayDescripcion[]"]').val();

        // Obtener opciones de sucursales para los selects del modal
        let opcionesSucursal1 = $('#id_sucursal_1').html();
        let opcionesSucursal2 = $('#id_sucursal_2').html();
        let opcionesProductos = $('#id_producto').html();

        Swal.fire({
            title: 'Editar Producto',
            html: `
            <div class="text-left">
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Sucursal Origen</label>
                    <select id="swal-sucursal1" class="w-full p-2 border rounded">
                        ${opcionesSucursal1}
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Sucursal Destino</label>
                    <select id="swal-sucursal2" class="w-full p-2 border rounded">
                        ${opcionesSucursal2}
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Producto</label>
                    <select id="swal-producto" class="w-full p-2 border rounded">
                        ${opcionesProductos}
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Cantidad</label>
                    <input id="swal-cantidad" type="number" min="1" value="${cantidad}" class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Descripción</label>
                    <textarea id="swal-descripcion" class="w-full p-2 border rounded">${descripcion}</textarea>
                </div>
            </div>
        `,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            focusConfirm: false,
            preConfirm: () => {
                return {
                    sucursal1: $('#swal-sucursal1').val(),
                    sucursal1T: $('#swal-sucursal1 option:selected').text(),
                    sucursal2: $('#swal-sucursal2').val(),
                    sucursal2T: $('#swal-sucursal2 option:selected').text(),
                    producto: $('#swal-producto').val(),
                    productoT: $('#swal-producto option:selected').text(),
                    cantidad: $('#swal-cantidad').val(),
                    descripcion: $('#swal-descripcion').val()
                };
            },
            didOpen: () => {
                // Establecer valores iniciales en los selects
                $('#swal-sucursal1').val(sucursal1);
                $('#swal-sucursal2').val(sucursal2);
                $('#swal-producto').val(producto);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const data = result.value;

                // Validaciones
                if (!data.sucursal1 || !data.sucursal2 || !data.producto || !data.cantidad || !data.descripcion) {
                    mensaje('Todos los campos son requeridos');
                    return;
                }

                if (!/^\d+$/.test(data.cantidad) || parseInt(data.cantidad) <= 0) {
                    mensaje('La cantidad debe ser un número entero positivo');
                    return;
                }

                // Actualizar la fila
                $(`#fila${id}`).html(`
                <th>${id}</th>
                <td><input type="hidden" name="arraySucursal1[]" value="${data.sucursal1}">${data.sucursal1T}</td>
                <td><input type="hidden" name="arraySucursal2[]" value="${data.sucursal2}">${data.sucursal2T}</td>
                <td><input type="hidden" name="arrayIdProducto[]" value="${data.producto}">${data.productoT}</td>
                <td><input type="hidden" name="arraycantidad[]" value="${data.cantidad}">${data.cantidad}</td>
                <td><input type="hidden" name="arrayDescripcion[]" value="${data.descripcion}">${data.descripcion}</td>
                <td class="flex gap-2">
                    <button type="button" onclick="editarProducto('${id}')">
                        <i class="p-3 cursor-pointer fa-solid fa-edit"></i>
                    </button>
                    <button type="button" onclick="eliminarProducto('${id}')">
                        <i class="p-3 cursor-pointer fa-solid fa-trash"></i>
                    </button>
                </td>
            `);
            }
        });
    }

    function eliminarProducto(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#fila${id}`).remove();
                // Reindexar las filas restantes
                $('#tabla-productos tbody tr').each(function(index) {
                    $(this).find('th').text(index + 1);
                });
                contador = $('#tabla-productos tbody tr').length;

                Swal.fire(
                    '¡Eliminado!',
                    'El producto ha sido eliminado.',
                    'success'
                );
            }
        });
    }

    function limpiar() {
        $('#id_producto').val(null).trigger('change');
        $('#cantidad').val('');
        $('#descripcion').val('');
        $('#btn-agregar').text('Agregar').removeData('edit-id');
    }

    function mensaje(texto, icono = "error") {
        Swal.fire({
            icon: icono,
            title: 'Error',
            html: texto,
            confirmButtonText: 'Aceptar'
        });
    }
</script>

@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/js/select2-global.js"></script>
@endpush