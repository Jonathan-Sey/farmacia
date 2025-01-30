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

        })

        let precioProducto
        let nombreProducto
        function mostrarValores(){
            let selectProducto = document.getElementById('id_producto');
            precioProducto = selectProducto.options[selectProducto.selectedIndex].getAttribute('data-precio');
            nombreProducto = selectProducto.options[selectProducto.selectedIndex].getAttribute('data-nombre');
            $('#precio').val(precioProducto);
        }



        let contador = 0;
        let subtotal = [];
        let suma = 0;
        let iva = 0;
        let total = 0;


        const impuesto = 12;

        function agregarProducto(){
            let id_producto = $('#id_producto').val();
            let producto = nombreProducto;
            let cantidad = parseInt($('#cantidad').val());
            let precio = parseFloat(precioProducto);
            let stock = parseInt($('#stock').val()) || 0; // Si no tiene stock, usar 0
            let tipo = $('#id_producto').find('option:selected').data('tipo'); // 1: Producto físico, 2: Servicio
            let aplicarImpuesto = $('#impuesto-checkbox').is(':checked'); // proceso para verificar si incluye iva

            if (id_producto != '' && producto != '' && precio > 0) {
        if (tipo === 1) { // Producto físico
            if (!cantidad || cantidad <= 0 || cantidad % 1 !== 0) {
                mensaje('Favor ingresar una cantidad válida.');
                return;
            }
            if (cantidad > stock) {
                mensaje(`La cantidad ingresada (${cantidad}) supera el stock disponible (${stock}).`);
                return;
            }
        } else { // Servicio
            cantidad = 1; // Para servicios, la cantidad siempre es 1
        }

            // Sumador
            contador++;
            // Calcular subtotal
            subtotal[contador] = round(cantidad * precio);
            suma += subtotal[contador];

            // nota aca se valido si es tipo producto o servicio
            if(tipo === 1 && aplicarImpuesto) { // aca se agrego el aplicar impuesto
                iva += round((subtotal[contador]/100) * impuesto);
            }
            //iva = round((suma / 100) * impuesto); - esto tenia antes

            total = round(suma + iva);

            // Agregar producto a la tabla
            $('#tabla-productos tbody').append(`
                <tr id="fila${contador}">
                    <th>${contador}</th>
                    <td><input type="hidden" name="arrayIdProducto[]" value="${id_producto}">${producto}</td>
                    <td><input type="hidden" name="arraycantidad[]" value="${cantidad}">${cantidad}</td>
                    <td><input type="hidden" name="arrayprecio[]" value="${precio}">${precio}</td>
                    <td>${subtotal[contador]}</td>
                    <td><button type="button" onclick="eliminarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-trash"></i></button></td>
                </tr>
            `);

            limpiar();

                // Actualizar totales
                $('#suma').html(suma);
                $('#iva').html(iva);
                $('#total').html(total);
                $('#impuesto').val(iva);
                $('#inputTotal').val(total);
            } else {
                mensaje('Los campos están vacíos o son inválidos.');
            }

        }

        function eliminarProducto(index){
            // recalculamos el detalle de venta
            suma -= round(subtotal[index]);
            iva = round(suma / 100 * impuesto);
            total = round(suma + iva);

            // mostramos los nuevos datos
            $('#suma').html(suma);
            $('#iva').html(iva);
            $('#total').html(total);
            $('#impuesto').val(iva);
            $('#inputTotal').val(total);

            //eliminamos la fila
            $('#fila'+index).remove();
        }



        // Limpiar los campos
        function limpiar(){
                $('#id_producto').val(null).trigger('change');
                $('#producto').val('');
                $('#cantidad').val('');
                $('#precio').val('');
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
@endpush

