@extends('template')
@section('titulo', 'Compras')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />


<style>


    #btn-guardar:disabled {
        background-color: #9ca3af;
        cursor: not-allowed;
    }
    #btn-guardar:disabled:hover {
        background-color: #9ca3af;
    }


        @media (max-width: 768px) {
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            white-space: normal;
            overflow: visible ;
            text-overflow: clip ;
            max-width: 100% ;
            word-wrap: break-word;
        }

        .select2-container {
            width: 100%;
        }

        .select2-dropdown {
            width: auto;
        }
    }

    @media (max-width: 768px) {
    .bg-white.p-5.rounded-xl.shadow-lg {
        padding: 1rem;
        margin-left: 0.5rem;
        margin-right: 0.5rem;
    }

    .lg\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
}
</style>

@endpush



@push('js')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
       Dropzone.autoDiscover = false;
    const dropzone = new Dropzone("#dropzone", {
        url: "{{ route('upload.image.temp') }}",
        dictDefaultMessage: "Arrastra y suelta una imagen o haz clic aquí para subirla",
        acceptedFiles: ".png,.jpg,.jpeg",
        addRemoveLinks: true,
        dictRemoveFile: "Borrar imagen",
        maxFiles: 1,
        uploadMultiple: false,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    dropzone.on("addedfile", function(file) {
        if (this.files.length > 1) {
            this.removeFile(this.files[0]);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Solo se permite subir una imagen.',
                confirmButtonText: 'Aceptar',
            });
        }
    });

    dropzone.on("success", function(file, response) {
        console.log("Archivo subido temporalmente:", response.imagen);
        document.querySelector('[name="imagen"]').value = response.imagen;
    });

    dropzone.on("removedfile", function(file) {
        const imagenNombre = document.querySelector('[name="imagen"]').value;
        if (imagenNombre) {
            fetch("{{ route('eliminar.imagen.temp') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ imagen: imagenNombre }),
            });
        }
        document.querySelector('[name="imagen"]').value = "";
    });

    // Eliminar imagen temporal al cerrar la página
    window.addEventListener("beforeunload", function() {
        const imagenNombre = document.querySelector('[name="imagen"]').value;
        if (imagenNombre && !document.querySelector('form').submitted) {
            fetch("{{ route('eliminar.imagen.temp') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ imagen: imagenNombre }),
            });
        }
    });

    // Marcar el formulario como enviado al hacer submit
    document.querySelector('form').addEventListener('submit', function() {
        this.submitted = true;
    });

    // Precargar imagen en edición
    document.addEventListener("DOMContentLoaded", function() {
        const imagenNombre = document.querySelector('[name="imagen"]').value;
        if (imagenNombre) {
            const mockFile = { name: imagenNombre, size: 12345 };
            dropzone.emit("addedfile", mockFile);
            dropzone.emit("thumbnail", mockFile, "{{ asset('uploads') }}/" + imagenNombre);
            dropzone.emit("complete", mockFile);
        }
    });

</script>

@endpush


@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-7xl mb-10 ">
        <form action="{{ route('compras.store') }}" method="POST" >
            {{-- <form action="{{ route('compras.store', ['id'=>1]) }}" method="POST" > --}}
            @csrf
            <div id="usuario">

            </div>
            <div class="lg:grid lg:grid-cols-2 lg:gap-5 sm:grid sm:grid-cols-1 sm:gap-5">
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
                            <label for="id_sucursal" class="uppercase block text-sm font-medium text-gray-900">Código de sucursal</label>
                            <select
                                class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                name="id_sucursal"
                                id="id_sucursal"
                                required>
                                <option value="">Selecciona una sucursal</option>
                                @foreach ($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}" {{ old('id_sucursal') == $sucursal->id ? 'selected' : '' }}>
                                        {{ $sucursal->nombre . ' - ' . $sucursal->codigo_sucursal}}
                                    </option>
                                @endforeach
                            </select>

                            @error('id_sucursal')
                                <div role="alert" class="alert alert-error mt-4 p-2">
                                    <span class="text-white font-bold">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- formulario para prescripciones -->
                        <div class="mt-2 mb-5">
                            <div class="md:flex md:flex-row md:items-center md:gap-3 flex flex-col gap-3 ">
                                {{-- <label class="cursor-pointer label md:flex md:flex-row  flex flex-col gap-2">
                                    <span class="label-text mr-2">¿Es prescrito?</span>
                                    <input type="checkbox" name="es_prescrito" id="es_prescrito" class="toggle toggle-primary">
                                </label> --}}
                                    <button type="button" class="btn btn-sm" onclick="my_modal_2.showModal()" id="btn-subir-comprobante">
                                        <i class="fa-solid fa-upload"></i> Subir Comprobante
                                        <span id="comprobante-subido" class="hidden ml-2 text-green-500">
                                            <i class="fa-solid fa-check-circle"></i>
                                        </span>
                                    </button>

                                <!-- id de la imagen-->
                                <input type="hidden" name="imagen_comprobante" id="imagen_comprobante" value="">
                                <input type="hidden" name="observaciones_comprobante" id="observaciones_comprobante_value" value="">


                            </div>

                            <!-- numero de reserva -->
                            <div id="campo-reserva" class="mt-2 hidden">
                                <label for="numero_reserva" class="uppercase block text-sm font-medium text-gray-900">Número de Reserva</label>
                                <input type="text" name="numero_reserva" id="numero_reserva"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                    placeholder="Ingrese número de reserva">
                            </div>
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
                                <label for="fecha_vencimiento" class="uppercase block text-sm font-medium text-gray-900">Fecha de Vencimiento</label>
                                <input
                                    type="date"
                                    name="fecha_vencimiento"
                                    id="fecha_vencimiento"
                                    min="{{ date('Y-m-d') }}"
                                    {{-- onblur="validarEntero(this)" --}}
                                    {{-- onblur="validarEntero(this)" --}}
                                    {{-- onblur="validarEntero(this)" --}}
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
                                        <option value="{{ $producto->id }}"  data-imagen="{{$producto->imagen_url}}" data-nombre-completo="{{$producto->nombre}}" {{old('id_producto') == $producto->id ? 'selected' : ''}}>{{$producto->codigo.' '.$producto->nombre}}</option>
                                    @endforeach
                                </select>
                                @error('id_producto')
                                    <div role="alert" class="alert alert-error mt-4 p-2">
                                        <span class="text-white font-bold">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <!-- Contenedor para mostrar la imagen -->
                            <div id="imagen-producto" class="mt-4 hidden">
                                <img id="imagen" src="" alt="Imagen del producto" class="w-24 h-24 object-cover rounded">
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
                                <label for="precio" class="uppercase block text-sm font-medium text-gray-900">Precio Compra</label>
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
                <button type="submit" id="btn-guardar" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>


              <!-- Modal para prescrito -->
            <!-- Modal para subir receta médica -->
            <dialog id="my_modal_2" class="modal">
                <div class="modal-box w-11/12 max-w-5xl">
                    <h3 class="font-bold text-lg">Subir Comprobante de Compra</h3>
                    <form id="formComprobante">
                        @csrf
                        <div class="form-control">
                            <label class="label" for="observaciones_comprobante">
                                <span class="label-text">Observaciones (Opcional)</span>
                            </label>
                            <textarea name="observaciones_comprobante" id="observaciones_comprobante"
                                class="textarea textarea-bordered" rows="3" placeholder="Ingrese observaciones si es necesario"></textarea>
                        </div>

                        <div class="form-control mt-4">
                            <label class="uppercase block text-sm font-medium text-gray-900">Imagen del comprobante (Opcional)</label>
                            <div id="dropzone" class="dropzone border-2 border-dashed rounded w-full h-60 p-4">
                                <input type="hidden" name="imagen" value="">
                            </div>
                        </div>
                        <div class="modal-action">
                            <button type="button" onclick="my_modal_2.close()" class="btn">Cancelar</button>
                            <button type="button" onclick="guardarComprobante()" class="btn btn-primary">Guardar</button>
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
<script src="/js/obtenerUsuario.js"></script>
<script>

    function verificarEstadoFormulario() {
        const filasProductos = document.querySelectorAll('#tabla-productos tbody tr');
        const proveedor = document.getElementById('id_proveedor').value;
        const sucursal = document.getElementById('id_sucursal').value;
        const btnGuardar = document.getElementById('btn-guardar');

        // Habilitar/deshabilitar botón según condiciones
        if (filasProductos.length > 0 && proveedor && sucursal) {
            btnGuardar.disabled = false;
        } else {
            btnGuardar.disabled = true;
        }
    }

    // Llamar a la función cuando cambie algo importante
    document.getElementById('id_proveedor').addEventListener('change', verificarEstadoFormulario);
    document.getElementById('id_sucursal').addEventListener('change', verificarEstadoFormulario);



    // Configuración para Select2 con truncado de texto
    $(document).ready(function(){
        $('#impuesto-checkbox').change(function() {
        recalcularTotales();
        });


        // Configuración para el select de proveedores
        $('#id_proveedor').select2({
            width: '100%',
            placeholder: "Buscar proveedor",
            allowClear: true,
            templateResult: formatOption,  // Mostrar nombre completo en el dropdown
            templateSelection: formatSelection  // Truncar nombre en la selección
        });

        // Configuración para el select de productos
        $('#id_producto').select2({
            width: '100%',
            placeholder: "Buscar producto",
            allowClear: true,
            templateResult: formatOption,  // Mostrar nombre completo en el dropdown
            templateSelection: formatSelection  // Truncar nombre en la selección
        });

        // Función para formatear cómo se muestran los resultados en el dropdown
        function formatOption(option) {
            if (!option.id) {
                return option.text;
            }
            // Mostrar el nombre completo en el dropdown
            var nombreCompleto = $(option.element).data('nombre-completo') || option.text;
            return $('<div>' + nombreCompleto + '</div>');
        }

        // Función para formatear cómo se muestra la selección en el select
        function formatSelection(option) {
            if (!option.id) {
                return option.text;
            }
            // Obtener el nombre completo
            var nombreCompleto = $(option.element).data('nombre-completo') || option.text;

            // Truncar el nombre si es necesario
            var nombreTruncado = nombreCompleto.length > 30
                ? nombreCompleto.substring(0, 30) + '...'
                : nombreCompleto;

            return nombreTruncado;
        }

        // Posicionar el cursor en el input para buscar
        $('.select2-proveedor, .select2-producto').on('select2:open', function() {
            document.querySelector('.select2-search__field').focus();
        });
    });
</script>

<script>
    $(document).ready(function() {
    $('#id_producto').change(function() {
            const selectedOption = $(this).find('option:selected');
            const imagenUrl = selectedOption.data('imagen'); // Obtener la URL de la imagen

            if (imagenUrl) {
                $('#imagen-producto').removeClass('hidden'); // Mostrar el contenedor de la imagen
                $('#imagen').attr('src', imagenUrl); // Actualizar la imagen
            } else {
                $('#imagen-producto').addClass('hidden'); // Ocultar el contenedor de la imagen
            }
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
            let producto = $('#id_producto option:selected').data('nombre-completo');
            let cantidad = $('#cantidad').val();
            let precio = $('#precio').val();
            let fecha_vencimiento = $('#fecha_vencimiento').val();
            let aplicarImpuesto = $('#impuesto-checkbox').is(':checked'); // aplicacion de impuesto

            // Verificar si el producto ya existe en el detalle
            let productoExistente = $(`#tabla-productos tbody tr input[name="arrayIdProducto[]"][value="${id_producto}"]`).closest('tr');

            if (productoExistente.length > 0) {
                // Si el producto ya existe, preguntar si desea editarlo
                Swal.fire({
                    title: 'Producto ya agregado',
                    text: 'Este producto ya está en el detalle. ¿Desea editarlo?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, editar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let index = productoExistente.find('th').text();
                        editarProducto(index);
                    }
                });
                return;
            }

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
                                <td><button type="button" onclick="editarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-edit"></i></button></td>
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



            verificarEstadoFormulario();

        }

        function editarProducto(index) {
            let fila = $(`#fila${index}`);
            let id_producto = fila.find('input[name="arrayIdProducto[]"]').val();
            let cantidad = fila.find('input[name="arraycantidad[]"]').val();
            let precio = fila.find('input[name="arrayprecio[]"]').val();
            let fecha_vencimiento = fila.find('input[name="arrayvencimiento[]"]').val();


             // Obtener fecha actual en formato
            let fechaActual = new Date().toISOString().split('T')[0];

            Swal.fire({
                title: 'Editar Producto',
                html: `
                    <div class="mb-4">
                        <label class="block mb-2">Cantidad</label>
                        <input id="edit-cantidad" type="number" value="${cantidad}" min="1" class="input input-bordered w-full">
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2">Precio</label>
                        <input id="edit-precio" type="number" value="${precio}" min="0.01" step="0.01" class="input input-bordered w-full">
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2">Fecha Vencimiento</label>
                        <input id="edit-vencimiento" type="date" value="${fecha_vencimiento}" min="${fechaActual}" class="input input-bordered w-full">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    let nuevaCantidad = $('#edit-cantidad').val();
                    let nuevoPrecio = $('#edit-precio').val();
                    let nuevaFecha = $('#edit-vencimiento').val();

                      // Validar que la fecha no sea anterior a hoy
                    if (new Date(nuevaFecha) < new Date(fechaActual)) {
                        Swal.showValidationMessage('La fecha de vencimiento no puede ser anterior a hoy');
                        return false;
                    }

                    if (!nuevaCantidad || nuevaCantidad <= 0) {
                        Swal.showValidationMessage('La cantidad debe ser mayor a 0');
                        return false;
                    }
                    if (!nuevoPrecio || nuevoPrecio <= 0) {
                        Swal.showValidationMessage('El precio debe ser mayor a 0');
                        return false;
                    }
                    if (!nuevaFecha) {
                        Swal.showValidationMessage('Debe seleccionar una fecha de vencimiento');
                        return false;
                    }

                    return {
                        cantidad: nuevaCantidad,
                        precio: nuevoPrecio,
                        vencimiento: nuevaFecha
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Actualizar valores en la fila
                    fila.find('input[name="arraycantidad[]"]').val(result.value.cantidad);
                    fila.find('input[name="arrayprecio[]"]').val(result.value.precio);
                    fila.find('input[name="arrayvencimiento[]"]').val(result.value.vencimiento);

                    // Actualizar visualización
                    fila.find('td:eq(1)').html(`<input type="hidden" name="arraycantidad[]" value="${result.value.cantidad}">${result.value.cantidad}`);
                    fila.find('td:eq(2)').html(`<input type="hidden" name="arrayprecio[]" value="${result.value.precio}">${result.value.precio}`);
                    fila.find('td:eq(3)').html(`<input type="hidden" name="arrayvencimiento[]" value="${result.value.vencimiento}">${result.value.vencimiento}`);

                    // Recalcular todos los totales
                    recalcularTotales();
                }
            });
        }

        function recalcularTotales() {
            suma = 0;
            iva = 0;
            let aplicarImpuesto = $('#impuesto-checkbox').is(':checked');

            // Reiniciar contador y reindexar filas
            contador = 0;

            $('#tabla-productos tbody tr').each(function() {
                contador++;
                $(this).find('th').text(contador); // Actualizar índice

                let cantidad = parseFloat($(this).find('input[name="arraycantidad[]"]').val());
                let precio = parseFloat($(this).find('input[name="arrayprecio[]"]').val());
                let subtotalRow = round(cantidad * precio);

                suma += subtotalRow;

                // Aplicar IVA solo si está activado el checkbox y es producto (no servicio)
                if (aplicarImpuesto) {
                    iva += round((subtotalRow * impuesto) / 100);
                }

                // Actualizar subtotal en la tabla
                $(this).find('td:eq(4)').text(subtotalRow.toFixed(2));
            });

            total = round(suma + iva);

            // Actualizar la interfaz
            $('#suma').html(suma.toFixed(2));
            $('#iva').html(iva.toFixed(2));
            $('#total').html(total.toFixed(2));
            $('#impuesto').val(iva.toFixed(2));
            $('#inputTotal').val(total.toFixed(2));
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

            // Eliminar la fila primero
            $(`#fila${index}`).remove();
                recalcularTotales();
                // Verificar estado del formulario
                verificarEstadoFormulario();
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

    <script>
// Configuración unificada de Dropzone para comprobantes
document.addEventListener('DOMContentLoaded', function() {
    // Eliminar cualquier instancia previa de Dropzone
    if (typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;
    }

    // Configurar Dropzone
    const dropzone = new Dropzone("#dropzone", {
        url: "{{ route('upload.image.temp') }}",
        dictDefaultMessage: "Arrastra y suelta el comprobante o haz clic aquí para subirla",
        acceptedFiles: ".png,.jpg,.jpeg,.pdf",
        addRemoveLinks: true,
        dictRemoveFile: "Borrar comprobante",
        maxFiles: 1,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        init: function() {
            // Precargar imagen si existe
            const imagenExistente = document.getElementById('imagen_comprobante').value;
            if (imagenExistente) {
                const mockFile = {
                    name: imagenExistente,
                    size: 12345,
                    accepted: true
                };
                this.emit("addedfile", mockFile);
                this.emit("thumbnail", mockFile, "{{ asset('uploads/temp') }}/" + imagenExistente);
                this.emit("complete", mockFile);
            }
        }
    });

    // Eventos de Dropzone
    dropzone.on("addedfile", function(file) {
        if (this.files.length > 1) {
            this.removeFile(this.files[0]);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Solo se permite subir un comprobante.',
                confirmButtonText: 'Aceptar'
            });
        }
    });

    dropzone.on("success", function(file, response) {
        document.querySelector('#formComprobante input[name="imagen"]').value = response.imagen;
    });

    dropzone.on("error", function(file, message) {
        console.error("Error al subir el comprobante:", message);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al subir el comprobante: ' + message,
        });
    });

    dropzone.on("removedfile", function() {
        const imagenNombre = document.querySelector('#formComprobante input[name="imagen"]').value;
        if (imagenNombre) {
            fetch("{{ route('eliminar.imagen.temp') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ imagen: imagenNombre }),
            });
        }
        document.querySelector('#formComprobante input[name="imagen"]').value = "";
    });

    // Manejar el botón de guardar
    document.querySelector('#formComprobante button[type="button"][onclick="guardarComprobante()"]')
        .addEventListener('click', guardarComprobante);

    // Manejar el botón de cancelar
    document.querySelector('#my_modal_2 .btn[onclick="my_modal_2.close()"]')
        .addEventListener('click', function() {
            // Si hay archivos en dropzone y no se ha guardado, eliminarlos
            if (dropzone.files.length > 0 && !document.getElementById('imagen_comprobante').value) {
                const file = dropzone.files[0];
                dropzone.removeFile(file);

                // Eliminar del servidor si existe
                if (file.name) {
                    fetch("{{ route('eliminar.imagen.temp') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ imagen: file.name }),
                    });
                }
            }

            // Limpiar observaciones si no se ha guardado
            if (!document.getElementById('imagen_comprobante').value) {
                document.getElementById('observaciones_comprobante').value = '';
            }
        });

        verificarEstadoFormulario();
});

// Función para guardar el comprobante (debe estar en el ámbito global)
function guardarComprobante() {
    const imagen = document.querySelector('#formComprobante input[name="imagen"]').value;
    const observaciones = document.getElementById('observaciones_comprobante').value;

    // Asignar valores al formulario principal
    document.getElementById('imagen_comprobante').value = imagen;
    document.getElementById('observaciones_comprobante_value').value = observaciones;

    // Mostrar indicador de comprobante subido si hay imagen u observaciones
    if (imagen || observaciones) {
        document.getElementById('comprobante-subido').classList.remove('hidden');
    } else {
        document.getElementById('comprobante-subido').classList.add('hidden');
    }

    // Cerrar el modal
    my_modal_2.close();

    Swal.fire({
        icon: 'success',
        title: 'Datos guardados',
        text: 'La información del comprobante se ha guardado correctamente',
    });
}

// Al abrir el modal, cargar datos existentes
document.getElementById('btn-subir-comprobante').addEventListener('click', function() {
    const observacionesGuardadas = document.getElementById('observaciones_comprobante_value').value;
    if (observacionesGuardadas) {
        document.getElementById('observaciones_comprobante').value = observacionesGuardadas;
    }
});


    // validar ante de enviar la info del formulario

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Validar si hay productos en el detalle
        const filasProductos = document.querySelectorAll('#tabla-productos tbody tr');
        if (filasProductos.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debes agregar al menos un producto al detalle de compra',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // Validar fechas de vencimiento
        let fechaInvalida = false;
        document.querySelectorAll('input[name="arrayvencimiento[]"]').forEach(input => {
            if (new Date(input.value) < new Date()) {
                fechaInvalida = true;
            }
        });

        if (fechaInvalida) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Una o más fechas de vencimiento son anteriores a hoy',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // Mostrar confirmación
        Swal.fire({
            title: 'Confirmar Compra',
            html: generarResumenCompra(),
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirmar Compra',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // funcion para el detalle compra
    function generarResumenCompra() {
        let mensaje = `<h4 class="text-lg font-bold">Resumen de la Compra</h4>`;
        mensaje += `<table class="table-auto w-full my-4">`;
        mensaje += `<thead><tr class="bg-gray-100"><th class="px-4 py-2">Producto</th><th class="px-4 py-2">Cantidad</th><th class="px-4 py-2">Precio</th><th class="px-4 py-2">Vencimiento</th><th class="px-4 py-2">Subtotal</th></tr></thead>`;
        mensaje += `<tbody>`;

        $('#tabla-productos tbody tr').each(function() {
            const producto = $(this).find('td:eq(0)').text();
            const cantidad = $(this).find('td:eq(1)').text();
            const precio = $(this).find('td:eq(2)').text();
            const vencimiento = $(this).find('td:eq(3)').text();
            const subtotal = $(this).find('td:eq(4)').text();

            mensaje += `<tr>
                <td class="border px-4 py-2">${producto}</td>
                <td class="border px-4 py-2 text-center">${cantidad}</td>
                <td class="border px-4 py-2 text-right">${precio}</td>
                <td class="border px-4 py-2 text-center">${vencimiento}</td>
                <td class="border px-4 py-2 text-right">${subtotal}</td>
            </tr>`;
        });

        mensaje += `</tbody></table>`;
        mensaje += `<div class="mt-4 text-right"><strong>Subtotal:</strong> ${$('#suma').text()}</div>`;
        mensaje += `<div class="text-right"><strong>IVA:</strong> ${$('#iva').text()}</div>`;
        mensaje += `<div class="text-right font-bold text-lg"><strong>TOTAL:</strong> ${$('#total').text()}</div>`;

        return mensaje;
    }


     </script>


@endpush

