@extends('template')
@section('titulo', 'Compras')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-7xl mb-10 ">
        <form action="{{ route('compras.store') }}" method="POST" >
            {{-- <form action="{{ route('compras.store', ['id'=>1]) }}" method="POST" > --}}
            @csrf
            <div class="lg:grid lg:grid-cols-2 lg:gap-5 sm:grid sm:grid-cols-1 sm:gap-5">
                <fieldset class="border-2 border-gray-200 p-2 rounded-2xl">
                    <legend class="text-blue-500 font-bold">Compras</legend>
                    <div class="border-b border-gray-900/10  lg:pb-0 lg:mb-0">
                        {{-- producto --}}

                            <div class="mt-2 mb-5">
                                <label for="id_producto" class="uppercase block text-sm font-medium text-gray-900">Producto</label>
                                <select
                                    class="select2-producto block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                    name="id_producto"
                                    id="id_producto">
                                    <option value="">Buscar un producto</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}" data-nombre="{{$producto->nombre}}" {{old('id_producto') == $producto->id ? 'selected' : ''}}>{{$producto->codigo.' '.$producto->nombre}}</option>
                                    @endforeach
                                </select>
                                @error('id_producto')
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
                                <label for="precio" class="uppercase block text-sm font-medium text-gray-900">Precio</label>
                                <input
                                    type="number"
                                    name="precio"
                                    id="precio"
                                    min="1"
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
                            <label for="id_proveedor" class="uppercase block text-sm font-medium text-gray-900">Proveedor</label>
                            <select
                                class="select2-proveedor block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                name="id_proveedor"
                                id="id_proveedor"
                                required>
                                <option value="">Seleccionar una categoría</option>
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}" {{old('id_proveedor') == $proveedor->id ? 'selected' : ''}}>{{$proveedor->empresa}}</option>
                                @endforeach
                            </select>
                            @error('id_proveedor')
                                <div role="alert" class="alert alert-error mt-4 p-2">
                                    <span class="text-white font-bold">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="mt-2 mb-5">
                            <label for="comprobante" class="uppercase block text-sm font-medium text-gray-900">Comprobante</label>
                            <input
                                type="text"
                                name="comprobante"
                                id="comprobante"
                                autocomplete="given-name"
                                placeholder="Numero del comprobante"
                                required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                value="{{ old('comprobante') }}">

                            @error('comprobante')
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

                            {{-- <div class="mt-2 mb-5">
                                <label for="fecha_compra" class="uppercase block text-sm font-medium text-gray-900">Fecha</label>
                                <input
                                    readonly
                                    type="date"
                                    name="fecha_compra"
                                    id="fecha_compra"
                                    autocomplete="given-name"
                                    placeholder="Impuesto"
                                    class=" block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                    value="<?php echo date("Y-m-d") ?>">

                            </div> --}}
                            <div class="mt-2 mb-5">
                                <label for="fecha_vencimiento" class="uppercase block text-sm font-medium text-gray-900">Fecha</label>
                                <input
                                    type="date"
                                    name="fecha_vencimiento"
                                    id="fecha_vencimiento"
                                    autocomplete="given-name"
                                    class=" block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                    value="{{old('fecha_vencimiento')}}">

                                    @error('fecha_vencimiento')
                                    <div role="alert" class="alert alert-error mt-4 p-2">
                                        <span class="text-white font-bold">{{ $message }}</span>
                                    </div>
                                    @enderror

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
                          <td>Fecha vencimiento</td>
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

                <a href="{{route('compras.index')}} " id="btn-cancelar">
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

    <script>
        //uso del select2 para proveedores
        $(document).ready(function(){
            $('.select2-proveedor').select2({
                width: '100%',
                placeholder: "Buscar proveedor",
                allowClear: true
            });
        // pocicionar el cursor en el input para buscar producto
        $('.select2-proveedor').on('select2-proveedor:open', function() {
        document.querySelector('.select2-search__field').focus();
        });

        //uso del select2 para proveedores
            $('.select2-producto').select2({
                width: '100%',
                placeholder: "Buscar producto",
                allowClear: true
            });
        // pocicionar el cursor en el input para buscar producto
        $('.select2-producto').on('select2-producto:open', function() {
        document.querySelector('.select2-search__field').focus();
        });
    });

    </script>
    <script>
        $(document).ready(function(){
            $('#btn-agregar').click(function(){
                agregarProducto();
            });

            $('#impuesto').val(impuesto + '%');
        })

        let contador = 0;
        let subtotal = [];
        let suma = 0;
        let iva = 0;
        let total = 0;


        const impuesto = 12;

        function agregarProducto(){
            let id_producto = $('#id_producto').val();
            //let producto = ($('#id_producto option:selected').text()).split(' ')[1];
            let producto = $('#id_producto option:selected').data('nombre');
            let cantidad = $('#cantidad').val();
            let precio = $('#precio').val();
            let fecha_vencimiento = $('#fecha_vencimiento').val();
            let aplicarImpuesto = $('#impuesto-checkbox').is(':checked'); // aplicacion de impuesto

            if(id_producto != '' && producto != '' && cantidad != '' && precio != '' && fecha_vencimiento !='')
            {
               if( parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(precio) > 0)
               {



                       // sumador
                        contador++;
                        // calcular subtotal
                        subtotal[contador] = round(cantidad * precio);
                        suma+=subtotal[contador]
                        //validacion del impuesto
                        if(aplicarImpuesto){
                            iva = round(suma/100 *  impuesto);
                        }
                        total = round(suma + iva);

                        $('#tabla-productos tbody').append(`
                            <tr id="fila${contador}">
                                <th>${contador}</th>
                                <td><input type="hidden" name="arrayIdProducto[]" value="${id_producto}">${producto}</td>
                                <td><input type="hidden" name="arraycantidad[]" value="${cantidad}">${cantidad}</td>
                                <td><input type="hidden" name="arrayprecio[]" value="${precio}">${precio}</td>
                                <td><input type="hidden" name="arrayvencimiento[]" value="${fecha_vencimiento}">${fecha_vencimiento}</td>
                                <td>${subtotal[contador]}</td>
                                <td><button type="button" onclick="eliminarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-trash"></i></button></td>
                            </tr> `);

                        limpiar();


                        $('#suma').html(suma);
                        $('#iva').html(iva);
                        $('#total').html(total);
                        $('#impuesto').val(iva);
                        $('#inputTotal').val(total);

               }else
               {
                    mensaje('favor ingresar una cantidad entera');
               }

            }else{
                mensaje('Los campos estan vacios');
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
            text: "Quieres canselar esta compra!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, cancelar!"
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                title: "Cancelado!",
                text: "La compra fue cancelada.",
                icon: "success"
                }).then(() => {
                    window.location.href = "{{ route('compras.index') }}";
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


@endpush

