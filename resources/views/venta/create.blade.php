@extends('template')
@section('titulo', 'Venta')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-7xl mb-10 ">
        <form action="{{ route('ventas.store') }}" method="POST" >
            @csrf

            <div id="usuario"></div>
            <div class="lg:grid lg:grid-cols-2 lg:gap-5 sm:grid sm:grid-cols-1 sm:gap-5">
                <fieldset class="border-2 border-gray-200 p-2 rounded-2xl">
                    <legend class="text-blue-500 font-bold">Venta</legend>
                    <div class="border-b border-gray-900/10  lg:pb-0 lg:mb-0">
                        {{-- producto --}}
                        <div class="mt-2 mb-5">
                            <label for="id_producto" class="uppercase block text-sm font-medium text-gray-900">Producto</label>
                            <select
                                class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                name="id_producto"
                                id="id_producto">
                                <option value="">Buscar un producto</option>

                            </select>
                            @error('id_producto')
                                <div role="alert" class="alert alert-error mt-4 p-2">
                                    <span class="text-white font-bold">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="mt-2 mb-5">
                            <label for="stock" class="uppercase block text-sm font-medium text-gray-900">Stock disponible</label>
                            <input
                                type="number"
                                class="block w-full rounded-md bg-gray-100 px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                id="stock"
                                name="stock"
                                value=""
                                readonly
                                min="1"
                                step="1"
                                >

                            @error('cantidad')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>

                        <div class="lg:grid lg:grid-cols-2 lg:gap-x-4">
                                {{-- cantidad --}}
                            <div class="mt-2 mb-5">
                                <label for="cantidad" class="uppercase block text-sm font-medium text-gray-900">Cantidad</label>
                                <input
                                    type="number"
                                    name="cantidad"
                                    id="cantidad"
                                    min="1"
                                    {{-- onblur="validarEntero(this)" --}}
                                    autocomplete="given-name"
                                    placeholder="Cantidad del producto"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                    value="{{ old('cantidad') }}">

                                @error('cantidad')
                                <div role="alert" class="alert alert-error mt-4 p-2">
                                    <span class="text-white font-bold">{{ $message }}</span>
                                </div>
                                @enderror
                            </div>

                            {{-- precio --}}
                            <div class="mt-2 mb-5">
                                <label for="precio" class="uppercase block text-sm font-medium text-gray-900">Precio de venta</label>
                                <input
                                    type="number"
                                    name="precio"
                                    id="precio"
                                    min="1"
                                    disabled
                                    autocomplete="given-name"
                                    placeholder="Precio del producto"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                    value="{{ old('precio') }}">

                                @error('precio')
                                <div role="alert" class="alert alert-error mt-4 p-2">
                                    <span class="text-white font-bold">{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                        </div>


                        {{-- end cantidad y precio --}}
                        <button id="btn-agregar" type="button" class=" cursor-pointer mt-3 rounded-md bg-indigo-600 px-3 w-full py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Agregar</button>
                    </div>

                </fieldset>

                <fieldset class="border-2 border-gray-200 p-2 rounded-2xl">
                    <legend class="text-blue-500 font-bold">Datos Generales</legend>
                    <div class="border-b border-gray-900/10 ">

                        <div class="mt-2 mb-5">
                            <label for="id_sucursal" class="uppercase block text-sm font-medium text-gray-900">Sucursal</label>
                            <select
                                class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                name="id_sucursal"
                                id="id_sucursal"
                                required>
                                <option value="">Seleccionar una sucursal</option>
                                @foreach ($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}" {{old('id_sucursal') == $sucursal->id ? 'selected' : ''}}>{{$sucursal->nombre}} - {{$sucursal->ubicacion}}</option>
                                @endforeach
                            </select>
                            @error('id_sucursal')
                                <div role="alert" class="alert alert-error mt-4 p-2">
                                    <span class="text-white font-bold">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="mt-2 mb-5">


                            <!-- Open the modal using ID.showModal() method -->
                            <button type="button" class="btn" onclick="my_modal_1.showModal()">+</button>

                            <label for="id_persona" class="uppercase block text-sm font-medium text-gray-900">Persona</label>
                            <select
                                class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                name="id_persona"
                                id="id_persona"
                                required>
                                <option value="">Seleccionar una sucursal</option>
                                @foreach ($personas as $persona)
                                    <option value="{{ $persona->id }}" {{ old('id_persona') == $persona->id ? 'selected' : '' }}>
                                        {{ $persona->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_persona')
                                <div role="alert" class="alert alert-error mt-4 p-2">
                                    <span class="text-white font-bold">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="lg:grid grid-cols-2 gap-8">

                            <div   class="md:flex md:flex-row gap-5 flex flex-col">
                                <div class="mt-2 mb-5">
                                    <label for="impuesto" class="uppercase block text-sm font-medium text-gray-900">Impuesto</label>
                                    <input
                                        readonly
                                        type="text"
                                        name="impuesto"
                                        id="impuesto"
                                        autocomplete="given-name"
                                        placeholder="Impuesto"
                                        class=" block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                        value="{{ old('impuesto') }}">

                                    @error('impuesto')
                                    <div role="alert" class="alert alert-error mt-4 p-2">
                                        <span class="text-white font-bold">{{ $message }}</span>
                                    </div>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-[0.7rem]"  for="tipo">Aplicar IVA</label>
                                    <input name="tipo" id="impuesto-checkbox" type="checkbox" class="toggle toggle-success"
                                    {{ old('tipo') ? 'checked' : '' }}
                                    />
                                </div>
                            </div>


                            <div class="mt-2 mb-5">
                                <label for="fecha_venta" class="uppercase block text-sm font-medium text-gray-900">Fecha</label>
                                <input
                                    readonly
                                    type="date"
                                    name="fecha_venta"
                                    id="fecha_venta"
                                    autocomplete="given-name"
                                    placeholder="Impuesto"
                                    class=" block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                    value="<?php echo date("Y-m-d") ?>">

                            </div>
                        </div>

                    </div>
                </fieldset>
            </div>

            {{-- tabla --}}
            <div class="mt-5">
                <h2 class="text-center m-5 font-bold text-lg">Detalle compra</h2>
                <div class="overflow-x-auto">
                    <table id="tabla-productos" class="table  table-md table-pin-rows table-pin-cols">
                      <thead>
                        <tr>
                          <th></th>
                          <td>Producto</td>
                          <td>Cantidad</td>
                          <td>Precio</td>
                          <td>SubTotal</td>
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
                        <tr>
                            <th></th>
                            <td class="text-sm font-black">SUMA:  <span id="suma" class="font-black ">0</span></td>
                            <td class="text-sm font-black">IVA %:  <span id="iva" class="font-black">0</span></td>
                            <td class="text-sm font-black"><input type="hidden" name="total" value="0" id="inputTotal"> TOTAL:  <span id="total" class="font-black">0</span></td>
                            <th></th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">

                <a href="{{route('ventas.index')}} " id="btn-cancelar">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>

           <!-- Modal para registrar una nueva persona -->
            <dialog id="my_modal_1" class="modal">
                <div class="modal-box">
                    <h3 class="font-bold text-lg">Registrar nueva persona</h3>
                    <form id="formPersona" method="POST" action="{{ route('personas.storeFromVentas') }}">
                        @csrf
                        <div class="form-control">
                            <label class="label" for="nombre">
                                <span class="label-text">Nombre</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" class="input input-bordered" required>
                        </div>
                        <div class="form-control">
                            <label class="label" for="nit">
                                <span class="label-text">NIT</span>
                            </label>
                            <input type="text" name="nit" id="nit" class="input input-bordered" required>
                            <label class="label" for="rol">
                                <span class="label-text">Rol</span>
                            </label>
                            <select name="rol" id="rol" class="input input-bordered" required>
                                <option value="1">Cliente</option>
                                <option value="2">Paciente</option>
                            </select>
                        </div>
                        <input type="hidden" name="rol" value="1"> <!-- Rol 1 para cliente -->
                        <div class="modal-action">
                            <button type="button" onclick="my_modal_1.close()" class="btn">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </dialog>

    </div>
</div>


@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- select2 de productos y sucursales --}}
    <script>
        //uso del select2 para proveedores
        $(document).ready(function(){
            $('.select2-sucursal').select2({
                width: '100%',
                placeholder: "Buscar",
                allowClear: true
            });
        // pocicionar el cursor en el input para buscar producto
        $('.select2-sucursal').on('select2-sucursal:open', function() {
        document.querySelector('.select2-search__field').focus();
        });
    });


    </script>


    <script>
        $('form').on('submit', function(event) {
            event.preventDefault(); // Evitar que el formulario se envíe automáticamente

            // Generar el resumen de la venta
            let resumen = generarResumenVenta();

            // Mostrar el resumen y pedir confirmación
            Swal.fire({
                title: 'Confirmar Venta',
                html: resumen,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Guardar Venta',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar el formulario
                    this.submit();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            // Escuchar el cambio en el select de sucursal
            $('#id_sucursal').change(function() {
                var sucursalId = $(this).val();  // Obtener el id de la sucursal seleccionada

                if(sucursalId) {
                    // Hacer una petición AJAX para obtener los productos de la sucursal seleccionada
                    $.ajax({
                        url: '/productos/sucursal/' + sucursalId,  // Ruta que proporcionaremos en el controlador
                        method: 'GET',
                        success: function(response) {
                            // Limpiar el select de productos
                            $('#id_producto').empty();
                            $('#id_producto').append('<option value="">Buscar un producto</option>');

                            // Llenar el select de productos con los productos obtenidos
                            response.forEach(function(producto) {
                                $('#id_producto').append(`
                                    <option value="${producto.id}"
                                        data-precio="${producto.precio_venta}"
                                        data-nombre="${producto.nombre}"
                                        data-tipo="${producto.tipo}"
                                        data-stock="${producto.stock}">
                                        ${producto.nombre} - Precio: ${producto.precio_venta}
                                    </option>
                                `);
                            });
                        },
                        error: function() {
                            alert('Error al cargar los productos');
                        }
                    });
                } else {
                    // Si no se selecciona una sucursal, limpiar el select de productos
                    $('#id_producto').empty();
                    $('#id_producto').append('<option value="">Buscar un producto</option>');
                }
            });
        });
    </script>



<script>
        $(document).ready(function(){

            $('.select2-producto').select2();

            // Actualizar stock al seleccionar producto
            $('#id_producto').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const tipo = selectedOption.data('tipo');
                const stock = selectedOption.data('stock');

                if (tipo === 1) { // si es Producto
                    $('#stock').val(stock).prop('readonly', true);
                    $('#cantidad').prop('disabled', false).attr('placeholder', 'Ingrese la cantidad');
                } else { // si es servicio
                    $('#stock').val('').prop('placeholder', 'N/A');
                    // nota se agrego 0 o puede dejarse como n/a, ya que es un servicio codigo nuevo
                    $('#impuesto').val('').prop('placeholder', 'N/A');
                    // fin
                    $('#cantidad').prop('disabled', true).val('').attr('placeholder', 'No aplica');

                }
            });

            //obtener datos de producto
            $('#id_producto').change(mostrarValores);


            $('#btn-agregar').click(function(){
                agregarProducto();
            });

            $('#impuesto').val(impuesto + '%');

            $('#porcentaje').on('input', function() {
                mostrarValores();
            });


        })

        let precioProducto
        let nombreProducto
        function mostrarValores() {
            let selectProducto = document.getElementById('id_producto');
            let precioBase = parseFloat(selectProducto.options[selectProducto.selectedIndex].getAttribute('data-precio'));

            let porcentaje = parseFloat($('#porcentaje').val()) || 0;
            let precioConAumento = round(precioBase + (precioBase * (porcentaje / 100)));

            precioProducto = precioConAumento;
            nombreProducto = selectProducto.options[selectProducto.selectedIndex].getAttribute('data-nombre');

            $('#precio').val(precioProducto);
        }



        let contador = 0;
        let subtotal = [];
        let suma = 0;
        let iva = 0;
        let total = 0;


        const impuesto = 12;

        function agregarProducto() {
            let idSucursal = $('#id_sucursal').val(); // nuevo dato a obtener
            let id_producto = $('#id_producto').val();
            let producto = nombreProducto;
            let cantidad = parseInt($('#cantidad').val());
            let precio = parseFloat(precioProducto);  // Ya tiene el porcentaje aplicado
            let stock = parseInt($('#stock').val()) || 0;
            let tipo = $('#id_producto').find('option:selected').data('tipo');
            let aplicarImpuesto = $('#impuesto-checkbox').is(':checked');

            // nueva validacion aca verificamos si el producto ya esta en el detalle compra
            let productoExistente = $(`#tabla-productos tbody tr input[name="arrayIdProducto[]"][value="${id_producto}"]`).closest('tr');
                if (productoExistente.length > 0) {
                    // Si el producto ya está en la tabla, editar la cantidad
                    let index = productoExistente.find('th').text();
                    editarProducto(index, idSucursal);
                    return;
                }

            if (id_producto != '' && producto != '' && precio > 0) {
                if (tipo === 1) { // validar si es producto
                    if (!cantidad || cantidad <= 0 || cantidad % 1 !== 0) {
                        mensaje('Favor ingresar una cantidad válida.');
                        return;
                    }
                    if (cantidad > stock) {
                        mensaje(`La cantidad ingresada (${cantidad}) supera el stock disponible (${stock}).`);
                        return;
                    }
                } else {
                    cantidad = 1;
                }

                contador++;
                subtotal[contador] = round(cantidad * precio);
                suma += subtotal[contador];

                if (tipo === 1 && aplicarImpuesto) {
                    iva += round((subtotal[contador] / 100) * impuesto);
                }

                total = round(suma + iva);

                $('#tabla-productos tbody').append(`
                    <tr id="fila${contador}">
                        <th>${contador}</th>
                        <td><input type="hidden" name="arrayIdProducto[]" value="${id_producto}">${producto}</td>
                        <td><input type="hidden" name="arraycantidad[]" value="${cantidad}">${cantidad}</td>
                        <td><input type="hidden" name="arrayprecio[]" value="${precio}">${precio}</td>
                        <td>${subtotal[contador]}</td>
                        <td>
                            <button type="button" onclick="editarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-edit"></i></button>
                            <button type="button" onclick="eliminarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-trash"></i></button>
                        </td>

                    </tr>
                `);

                limpiar();

                $('#suma').html(suma);
                $('#iva').html(iva);
                $('#total').html(total);
                $('#impuesto').val(iva);
                $('#inputTotal').val(total);




            } else {
                mensaje('Los campos están vacíos o son inválidos.');
            }
        }

function editarProducto(index) {
    let idSucursal = $('#id_sucursal').val(); // Obtener el ID de la sucursal seleccionada
    let cantidadActual = $(`#fila${index} input[name="arraycantidad[]"]`).val();
    let idProducto = $(`#fila${index} input[name="arrayIdProducto[]"]`).val();
    let tipo = $(`#fila${index} input[name="arrayIdProducto[]"]`).closest('tr').find('input[name="arraytipo[]"]').val();

    if (tipo === 2) { // Si es servicio, no permitir editar la cantidad
        mensaje('No se puede editar la cantidad de un servicio.');
        return;
    }

    // Obtener el stock disponible del producto desde el servidor
    $.ajax({
        url: '/productos/stock/' + idProducto + '/' + idSucursal, // Ruta para obtener el stock del producto
        method: 'GET',
        success: function(response) {
            let stockDisponible = response.stock;
            if (stockDisponible === undefined) {
                mensaje('No se pudo obtener el stock del producto.');
                return;
            }

            Swal.fire({
                title: 'Editar Cantidad',
                input: 'number',
                inputValue: cantidadActual,
                inputAttributes: {
                    min: 1,
                    max: stockDisponible,
                    step: 1
                },
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value || value <= 0 || value > stockDisponible) {
                        return `La cantidad debe ser mayor que 0 y no superar el stock disponible (${stockDisponible}).`;
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let nuevaCantidad = parseInt(result.value);
                    let precio = parseFloat($(`#fila${index} input[name="arrayprecio[]"]`).val());
                    let aplicarImpuesto = $('#impuesto-checkbox').is(':checked');

                    // Recalcular el subtotal, suma, IVA y total
                    subtotal[index] = round(nuevaCantidad * precio);
                    suma = subtotal.reduce((a, b) => a + b, 0);

                    if (aplicarImpuesto) {
                        iva = round(suma / 100 * impuesto);
                    } else {
                        iva = 0;
                    }

                    total = round(suma + iva);

                    // Actualizar los valores en la fila de la tabla
                    $(`#fila${index} input[name="arraycantidad[]"]`).val(nuevaCantidad); // Actualizar cantidad en el input oculto
                    $(`#fila${index} td:eq(1)`).html(`<input type="hidden" name="arraycantidad[]" value="${nuevaCantidad}">${nuevaCantidad}`); // Actualizar cantidad visible
                    $(`#fila${index} input[name="arrayprecio[]"]`).val(precio); // Actualizar precio en el input oculto
                    $(`#fila${index} td:eq(2)`).html(`<input type="hidden" name="arrayprecio[]" value="${precio}">${precio}`); // Actualizar precio visible
                    $(`#fila${index} td:eq(3)`).text(subtotal[index].toFixed(2)); // Actualizar subtotal visible

                    // Actualizar los valores en la interfaz
                    $('#suma').html(suma.toFixed(2));
                    $('#iva').html(iva.toFixed(2));
                    $('#total').html(total.toFixed(2));
                    $('#impuesto').val(iva.toFixed(2));
                    $('#inputTotal').val(total.toFixed(2));
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud AJAX:', xhr.responseText);
            mensaje('Error al obtener el stock disponible del producto.');
        }
    });
}



        function eliminarProducto(index){
            // recalculamos el detalle de venta
            // suma -= round(subtotal[index]);
            // iva = round(suma / 100 * impuesto);
            // total = round(suma + iva);

            // segunda foram, recalcular los precios
            suma -= subtotal[index];
            let producto = $(`#fila${index} input[name="arrayIdProducto[]"]`).closest('tr');
            let tipo = producto.find('input[name="arraytipo[]"]').val();

            // Si el producto tenía IVA aplicado, restar el IVA correspondiente
            if (tipo === 1 && $('#impuesto-checkbox').is(':checked')) {
                    iva -= round((subtotal[index] / 100) * impuesto);
                }
            // Recalcular el total
            total = round(suma + iva);

             // Si no hay productos, restablecer los valores a 0
            if ($('#tabla-productos tbody tr').length === 1) { // Solo queda la fila de encabezado
                suma = 0;
                iva = 0;
                total = 0;
            }

            // mostramos los nuevos datos
            // $('#suma').html(suma);
            // $('#iva').html(iva);
            // $('#total').html(total);
            // $('#impuesto').val(iva);
            // $('#inputTotal').val(total);
            $('#suma').html(suma.toFixed(2));
            $('#iva').html(iva.toFixed(2));
            $('#total').html(total.toFixed(2));
            $('#impuesto').val(iva.toFixed(2));
            $('#inputTotal').val(total.toFixed(2));

            //eliminamos la fila
            $('#fila'+index).remove();
                // Eliminar el subtotal del array
                delete subtotal[index];
        }



        // Limpiar los campos
        function limpiar(){
                $('#id_producto').val(null).trigger('change');
                $('#producto').val('');
                $('#cantidad').val('');
                $('#precio').val('');
        }

        function mensaje(texto) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: texto,
        });
    }

    function generarResumenVenta() {
        let resumen = '<h4>Resumen de la Venta</h4>';
        resumen += '<table class="table table-bordered">';
        resumen += '<thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>';
        resumen += '<tbody>';

        $('#tabla-productos tbody tr').each(function() {
            let producto = $(this).find('td:eq(0)').text();
            let cantidad = $(this).find('td:eq(1)').text();
            let precio = $(this).find('td:eq(2)').text();
            let subtotal = $(this).find('td:eq(3)').text();

            resumen += `<tr><td>${producto}</td><td>${cantidad}</td><td>${precio}</td><td>${subtotal}</td></tr>`;
        });

        resumen += '</tbody></table>';
        resumen += `<p><strong>Subtotal:</strong> ${$('#suma').text()}</p>`;
        resumen += `<p><strong>IVA:</strong> ${$('#iva').text()}</p>`;
        resumen += `<p><strong>Total:</strong> ${$('#total').text()}</p>`;

        return resumen;
}


        // modal para canselar la compra
        document.getElementById('btn-cancelar').addEventListener('click', function(event){
            event.preventDefault();
            Swal.fire({
            title: "Estas seguro de esto?",
            text: "Quieres cancelar esta Venta!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, cancelar!"
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                title: "Cancelado!",
                text: "La venta fue cancelada.",
                icon: "success"
                }).then(() => {
                    window.location.href = "{{ route('ventas.index') }}";
                });
            }
            });
        });


        function mensaje (message, icon = "error"){
            const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
            });
            Toast.fire({
            icon: icon,
            title: message
            });
        }


        // funcion para redondear los numeros
        // funete: https://es.stackoverflow.com/questions/48958/redondear-a-dos-decimales-cuando-sea-necesario
        function round(num, decimales = 2) {
            var signo = (num >= 0 ? 1 : -1);
            num = num * signo;
            if (decimales === 0) //con 0 decimales
                return signo * Math.round(num);
            // round(x * 10 ^ decimales)
            num = num.toString().split('e');
            num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
            // x * 10 ^ (-decimales)
            num = num.toString().split('e');
            return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
        }


    </script>

        @if(session('error'))
        <div class="alert-message">
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const errorMessage = document.querySelector('.alert-message span').textContent;
            if (errorMessage) {
                alert(errorMessage);
            }
        });
        </script>

<script>
  document.getElementById('formPersona').addEventListener('submit', function (e) {
    e.preventDefault(); // Evitar el envío tradicional del formulario

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Cerrar el modal
            my_modal_1.close();

            // Actualizar el select de personas
            const selectPersona = document.getElementById('id_persona');
            const newOption = new Option(data.persona.nombre, data.persona.id, true, true);
            selectPersona.appendChild(newOption);

            // Mostrar mensaje de éxito
            Swal.fire({
                icon: 'success',
                title: 'Persona registrada',
                text: 'La persona se ha registrado correctamente.',
            });
        }
    })
    .catch(error => {
        if (error.errors) {
            // Mostrar errores de validación
            let errorMessages = '';
            for (let field in error.errors) {
                errorMessages += error.errors[field].join('<br>') + '<br>';
            }
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                html: errorMessages,
            });
        } else {
            // Mostrar mensaje de error genérico
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al registrar la persona.',
            });
        }
    });
});
     </script>
@endpush

