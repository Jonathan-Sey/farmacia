@extends('template')
@section('titulo', 'Venta')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<style>

    .error-message {
        color: red;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .input-especial {
    border: 1px solid rgb(78, 17, 148) !important; 
    }
    /* .select2-container--default .select2-selection--single .select2-selection__rendered {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%; */
/* } */

</style>
@endpush

@push('js')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    Dropzone.autoDiscover = false;
    const dropzone = new Dropzone("#dropzone", {
        url: "{{ route('upload.image') }}",
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
        if (this.files.length > 1) { // Si hay más de un archivo
            this.removeFile(this.files[0]); // Elimina el primer archivo
                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Solo se permite subir una imagen.',
                confirmButtonText: 'Aceptar',
            });
        }
    });

    dropzone.on("sending", function(file, xhr, formData) {
        if (this.files.length > 1) { // Si hay más de un archivo
            this.removeFile(file); // Elimina el archivo adicional
            Swal.fire({
                icon: 'error', // Tipo de ícono (error, success, warning, info, etc.)
                title: 'Error', // Título de la alerta
                text: 'Solo se permite subir una imagen.', // Mensaje de la alerta
                confirmButtonText: 'Aceptar', // Texto del botón
            });
            return false; // Detiene la subida del archivo
        }
    });

    dropzone.on("success", function(file, response) {
        console.log("Archivo subido correctamente:", response.imagen);
        document.querySelector('[name="imagen"]').value = response.imagen;
    });

    dropzone.on("error", function(file, message) {
        console.error("Error al subir el archivo:", message);
        alert("Error al subir la imagen: " + message);
    });
    // remover la imagen
     dropzone.on("removedfile", function(file) {
        document.querySelector('[name="imagen"]').value = ""; // Limpiar el campo oculto
    });




    // precargar la imagen subida nuevamente
        document.addEventListener("DOMContentLoaded", function() {
        const imagenNombre = document.querySelector('[name="imagen"]').value;

        if (imagenNombre) {
            const mockFile = { name: imagenNombre, size: 12345 }; // Simula un archivo
            dropzone.emit("addedfile", mockFile);
            dropzone.emit("thumbnail", mockFile, "{{ asset('uploads') }}/" + imagenNombre); // cargar imagen
            dropzone.emit("complete", mockFile);
        }
    });


        //eliminar la imagen del server
        window.addEventListener("beforeunload", function() {
        const imagenNombre = document.querySelector('[name="imagen"]').value;

        if (imagenNombre) {
            fetch("/eliminar-imagen-temp", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ imagen: imagenNombre }),
            });
        }
    });

</script>
@endpush


@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-7xl mb-10 ">
        <!-- Open the modal using ID.showModal() method -->

        <form id="formVenta" action="{{ route('ventas.store') }}" method="POST" >
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
                                     <!-- Contenedor para mostrar la imagen -->
                                     <div id="imagen-producto" class="mt-4 hidden">
                                        <img id="imagen" src="" alt="Imagen del producto" class="w-24 h-24 object-cover rounded">
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
                                name="arrayprecio[]"
                                id="precio"
                                min="1"
                                disabled
                                autocomplete="given-name"
                                placeholder="Precio del producto"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                value="{{ old('arrayprecio.0') }}">
                            
                            @error('precio')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>


                           
                           

                            {{-- porcentaje --}}
                            <div class="mt-2 mb-5">
                                <label for="porcentaje" class="uppercase block text-sm font-medium text-gray-900">Porcentaje</label>
                                <input
                                    type="number"
                                    name="porcentaje"
                                    id="porcentaje"
                                    min="1"
                                    disabled
                                    autocomplete="given-name"
                                    placeholder="Precio del producto con porcentaje"
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
                                    <option value="{{ $sucursal->id }}" data-nombre-completo="{{ $sucursal->nombre }}" data-ubicacion-completa="{{ $sucursal->ubicacion }}">
                                        {{ $sucursal->nombre }} - {{ $sucursal->ubicacion }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_sucursal')
                                <div role="alert" class="alert alert-error mt-4 p-2">
                                    <span class="text-white font-bold">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="mt-2 mb-5">
                                    <button type="button" class="btn" onclick="my_modal_1.showModal()"><i class="fa-solid fa-user-plus"></i></button>
                                    <label for="id_persona" class="uppercase block text-sm font-medium text-gray-900">Persona</label>
                                <select
                                    class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                    name="id_persona"
                                    id="id_persona"
                                    required>
                                    <!--<option value="">Seleccionar persona</option>-->
                                    @foreach ($personas as $persona)
                                        <option value="{{ $persona->id }}" data-nombre-completo="{{ $persona->nit }}">
                                            {{ $persona->nit }}
                                        </option>
                                    @endforeach
                                </select>
                                    @error('id_persona')
                                        <div role="alert" class="alert alert-error mt-4 p-2">
                                            <span class="text-white font-bold">{{ $message }}</span>
                                        </div>
                                    @enderror
                        </div>

                                <!-- Grupo de checkboxes -->
                        <div class="mt-2 mb-5">
                            <div class="flex flex-col md:flex-row md:items-center md:gap-6 gap-4">
                                <!-- Checkbox prescripción -->
                                <label class="cursor-pointer label flex flex-row items-center gap-2">
                                    <span class="label-text">¿Es prescrito?</span>
                                    <input type="checkbox" name="es_prescrito" id="es_prescrito" class="toggle toggle-primary">
                                </label>

                                <!-- Checkbox vecino -->
                                <label class="cursor-pointer label flex flex-row items-center gap-2">
                                    <span class="label-text">Cliente especial</span>
                                    <input type="checkbox" name="vecino" id="vecino" class="toggle toggle-primary">
                                </label>
                            </div>
                        </div>

                        <!-- Campo de receta -->
                        <div id="btn-subir-receta" class="hidden mb-3">
                            <button type="button" class="btn btn-sm" onclick="my_modal_2.showModal()">
                                <i class="fa-solid fa-upload"></i> Subir Receta
                            </button>
                            <input type="hidden" name="imagen_receta" id="imagen_receta" value="">
                        </div>

                        <!-- Tipo de vecino  -->
                        <div id="campo-reserva" class="mt-2 hidden mb-5">
                            <label for="numero_reserva" class="uppercase block text-sm font-medium text-gray-900">Número de Reserva</label>
                            <input type="text" name="numero_reserva" id="numero_reserva"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                placeholder="Ingrese número de reserva">
                        </div>

                        
                        {{-- precio especial --}}
                            <!-- Campo Precio Cliente Especial (oculto por defecto) -->
                            <div id="precio-especial-wrapper" class="mt-2 mb-5 hidden">
                                <label for="precio" class="uppercase block text-sm font-medium text-gray-900">Nuevo precio de venta</label>
                                <input
                                    type="number"
                                    name="arrayprecio[]"
                                    id="preciocliente"
                                    min="1"
                                    autocomplete="given-name"
                                    placeholder="Precio del producto"
                                     class="input-especial block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                    value="{{ old('arrayprecio.0') }}">
                                    
                                @error('preciocliente')
                                <div role="alert" class="alert alert-error mt-4 p-2">
                                    <span class="text-white font-bold">{{ $message }}</span>
                                </div>
                                @enderror
                            </div>

                        <!-- Campo justificación -->
                        <div id="campo_justificacion" class="mt-2 hidden mb-5">
                            <label for="justificacion" class="uppercase block text-sm font-medium text-gray-900">Justificación de pago</label>
                            <input type="text" name="justificacion" id="justificacion"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                placeholder="Ingrese la justificación">
                        </div>

                        <!-- Campo de impuesto -->  
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
                                <label for="fecha_venta" class="uppercase block text-sm font-medium text-gray-900">Fecha de venta</label>
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
                <h2 class="text-center m-5 font-bold text-lg">Detalle Venta</h2>
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
                            @error('nombre')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message}}</span>
                            </div>
                            @enderror
                        </div>
                        <div class="form-control">
                            <label class="label" for="nit">
                                <span class="label-text">NIT</span>
                            </label>
                            <input type="text" name="nit" id="nit" class="input input-bordered" required>
                            @error('nit')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message}}</span>
                            </div>

                            @enderror
                            <label class="label" for="rol">
                                <span class="label-text">Rol</span>
                            </label>
                            <select name="rol" id="rol" class="input input-bordered" required>
                                <option value="1" {{ old('rol') == 1 ? 'selected' : '' }}>Cliente</option>
                                <option value="2" {{ old('rol') == 2 ? 'selected' : '' }}>Paciente</option>
                            </select>

                        </div>
                        <div class="modal-action">
                            <button type="button" onclick="my_modal_1.close()" class="btn">Cancelar</button>
                            <button type="submit" onclick="guardarPersona()" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </dialog>


              <!-- Modal para prescrito -->
            <!-- Modal para subir receta médica -->
            <dialog id="my_modal_2" class="modal">
                <div class="modal-box w-11/12 max-w-5xl">
                    <h3 class="font-bold text-lg">Subir Receta Médica</h3>
                    <form id="formReceta">
                        @csrf
                        <div class="form-control">
                            <label class="label" for="observaciones_receta">
                                <span class="label-text">Observaciones (Opcional)</span>
                            </label>
                            <textarea name="observaciones_receta" id="observaciones_receta" class="textarea textarea-bordered" rows="3"></textarea>
                        </div>

                        <div class="form-control mt-4">
                            <label class="uppercase block text-sm font-medium text-gray-900">Imagen de la Receta</label>
                            <div id="dropzone" class="dropzone border-2 border-dashed rounded w-full h-60 p-4">
                                <input type="hidden" name="imagen" value="">
                            </div>
                            @error('imagen')
                                <div role="alert" class="alert alert-error mt-4 p-2">
                                    <span class="text-white font-bold">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="modal-action">
                            <button type="button" onclick="my_modal_2.close()" class="btn">Cancelar</button>
                            <button type="button" onclick="guardarReceta()" class="btn btn-primary">Guardar Receta</button>
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
                allowClear: true,
            });
        // pocicionar el cursor en el input para buscar producto
        $('.select2-sucursal').on('select2-sucursal:open', function() {
        document.querySelector('.select2-search__field').focus();
        });
    });
    </script>




<!---Precio de venta de cliente aparezca-->
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const checkboxVecino = document.getElementById('vecino');
                const precioEspecialWrapper = document.getElementById('precio-especial-wrapper');
                const inputPrecioCliente = document.getElementById('preciocliente');
                const inputPrecio = document.getElementById('precio');

                checkboxVecino.addEventListener('change', function () {
                    if (this.checked) {
                        // mostrar el campo de precio especial
                        precioEspecialWrapper.classList.remove('hidden');
                        // Cambiar el borde a morado
                        inputPrecio.classList.add('input-especial');
                    } else {
                        // Ocultar el campo de precio especial
                        precioEspecialWrapper.classList.add('hidden');
                        // Remover el borde morado
                        inputPrecio.classList.remove('input-especial');
                    }
                });

                if (checkboxVecino.checked) {
                    // Si el checkbox ya está marcado, aplicar los estilos correspondientes
                    precioEspecialWrapper.classList.remove('hidden');
                    inputPrecio.disabled = false;
                    inputPrecio.classList.add('input-especial');
                }
            });
                
            </script>
    
        

    {{-- <script>
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
    </script> --}}
    <script>
            $(document).ready(function() {
                // Configuración para el select de sucursales
                $('#id_sucursal').select2({
                    width: '100%',
                    placeholder: "Buscar sucursal",
                    allowClear: true,
                    templateResult: formatOption,  // Mostrar nombre completo en el dropdown
                    templateSelection: formatSelection  // Truncar nombre y ubicación en la selección
                });

                // Configuración para el select de personas
                $('#id_persona').select2({
                    width: '100%',
                    placeholder: "Buscar persona",
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
                    var nombreCompleto = $(option.element).data('nombre-completo');
                    var ubicacionCompleta = $(option.element).data('ubicacion-completa') || ''; // Opcional para sucursales
                    return $('<div>' + nombreCompleto + (ubicacionCompleta ? ' - ' + ubicacionCompleta : '') + '</div>');
                }

                // Función para formatear cómo se muestra la selección en el select
                function formatSelection(option) {
                    if (!option.id) {
                        return option.text;
                    }
                    // Obtener el nombre y la ubicación completos
                    var nombreCompleto = $(option.element).data('nombre-completo');
                    var ubicacionCompleta = $(option.element).data('ubicacion-completa') || '';

                    // Truncar el nombre y la ubicación si es necesario
                    var nombreTruncado = nombreCompleto.length > 20
                        ? nombreCompleto.substring(0, 20) + '...'
                        : nombreCompleto;

                    var ubicacionTruncada = ubicacionCompleta.length > 20
                        ? ubicacionCompleta.substring(0, 20) + '...'
                        : ubicacionCompleta;

                    // Devolver el nombre y la ubicación truncados
                    return nombreTruncado + (ubicacionTruncada ? ' - ' + ubicacionTruncada : '');
                }
        });
    </script>



    <script>
$(document).ready(function() {
    // Escuchar el cambio en el select de sucursal
    $('#id_sucursal').change(function() {
        var sucursalId = $(this).val();  // Obtener el id de la sucursal seleccionada

        if (sucursalId) {
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
                                data-nombre-completo="${producto.nombre}"
                                data-tipo="${producto.tipo}"
                                data-stock="${producto.stock}"
                                data-imagen="${producto.imagen}">
                                ${producto.nombre} - Precio: ${producto.precio_venta}
                            </option>
                        `);
                    });

                    // Re-inicializar Select2 para aplicar la configuración específica
                    $('#id_producto').select2({
                        width: '100%',
                        placeholder: "Buscar un producto",
                        allowClear: true,
                        templateResult: formatOption,  // Función para formatear cómo se muestran los resultados
                        templateSelection: formatSelection  // Función para formatear cómo se muestra la selección
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

    // Función para formatear cómo se muestran los resultados en el dropdown
    function formatOption(option) {
        if (!option.id) {
            return option.text;
        }
        // Usar el nombre completo para la búsqueda
        var $option = $(
            '<div>' + $(option.element).data('nombre-completo') + ' - Precio: ' + $(option.element).data('precio') + '</div>'
        );
        return $option;
    }

    // Función para formatear cómo se muestra la selección en el select
    function formatSelection(option) {
        if (!option.id) {
            return option.text;
        }
        // Truncar el nombre del producto a 30 caracteres
        const nombreTruncado = $(option.element).data('nombre-completo').length > 30
            ? $(option.element).data('nombre-completo').substring(0, 30) + '...'
            : $(option.element).data('nombre-completo');
        return nombreTruncado + ' - Precio: ' + $(option.element).data('precio');
    }
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
            nombreProducto = selectProducto.options[selectProducto.selectedIndex].getAttribute('data-nombre-completo');

            $('#precio').val(precioProducto);
        }



        let contador = 0;
        let subtotal = [];
        let suma = 0;
        let iva = 0;
        let total = 0;


        const impuesto = 12;

        function agregarProducto() {

            let justificacion = $('input[name="justificacion"]').val().trim();//pedir la justificacion
            let idSucursal = $('#id_sucursal').val(); // nuevo dato a obtener
            let id_producto = $('#id_producto').val();
            let producto = nombreProducto;
            let cantidad = parseInt($('#cantidad').val());
            //Cambia el precio original a nuevo precio
            let esVecino = document.getElementById('vecino').checked;
            let precio = esVecino
                ? parseFloat(document.getElementById('preciocliente').value)
                : parseFloat(precioProducto);
            //
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

                if (esVecino && justificacion === '') {
                    mensaje('Debe ingresar una justificación al seleccionar precio especial.');
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
document.addEventListener('DOMContentLoaded', function () {

       // Manejar el envío del formulario de venta
       document.getElementById('formVenta').addEventListener('submit', function (e) {
        e.preventDefault(); // Evitar que el formulario se envíe automáticamente

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

    // manejo del modal de crear cliente
    document.getElementById('formPersona').addEventListener('submit', function (e) {
        e.preventDefault(); // Evitar el envío tradicional del formulario
        e.stopPropagation();

        const formData = new FormData(this);
        // Depuración: Verifica el valor de "rol"
        console.log('Valor de rol:', formData.get('rol'));


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
                  my_modal_1.close();

                    // Limpiar formulario
                    this.reset();

                    // Actualizar select2
                    const selectPersona = $('#id_persona');

                    // Crear nueva opción con todos los atributos necesarios
                    const newOption = new Option(
                        `${data.persona.nit} - ${data.persona.nombre}`, // Texto visible
                        data.persona.id, // Valor
                        false, // selected por defecto?
                        true // selected ahora?
                    );

                    // Añadir atributos de datos
                    $(newOption).attr('data-nombre-completo', data.persona.nit);
                    selectPersona.append(newOption);

                    // Seleccionar la nueva persona
                    selectPersona.val(data.persona.id).trigger('change');

                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Persona registrada correctamente',
                    });
                }
            })
        .catch(error => {
            if (error.errors) {
                // Limpiar errores anteriores
                document.querySelectorAll('.error-message').forEach(el => el.remove());

                for (let field in error.errors) {
                    const inputField = document.querySelector(`[name="${field}"]`);
                    if (inputField) {
                        const errorMessage = document.createElement('div');
                        errorMessage.className = 'error-message text-red-500 text-sm mt-1';
                        errorMessage.innerHTML = error.errors[field].join('<br>');
                        inputField.parentNode.insertBefore(errorMessage, inputField.nextSibling);
                    }
                }
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
});
     </script>

     <script>
        document.addEventListener('DOMContentLoaded', function () {
        const esPrescritoCheckbox = document.getElementById('es_prescrito');
        const campoReserva = document.getElementById('campo-reserva');
        const campoImagen = document.getElementById('btn-subir-receta');

        esPrescritoCheckbox.addEventListener('change', function () {
            campoReserva.classList.toggle('hidden', !this.checked);
            campoImagen.classList.toggle('hidden', !this.checked);
        });


        //Ckeckbox para justificar el vecino y su justificación
        const checkboxVecino = document.getElementById('vecino');
        const campoReservaVecino = document.getElementById('campo_justificacion'); // aquí el cambio

        checkboxVecino.addEventListener('change', function () {
            if (this.checked) {
                campoReservaVecino.classList.remove('hidden');
            } else {
                campoReservaVecino.classList.add('hidden');
            }
        });


            // Configurar Dropzone para subir imágenes
            Dropzone.autoDiscover = false;
            const dropzone = new Dropzone("#dropzone", {
                url: "{{ route('upload.image') }}",
                dictDefaultMessage: "Arrastra y suelta la receta médica o haz clic aquí para subirla",
                acceptedFiles: ".png,.jpg,.jpeg,.pdf",
                addRemoveLinks: true,
                dictRemoveFile: "Borrar archivo",
                maxFiles: 1,
                uploadMultiple: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            dropzone.on("success", function(file, response) {
                console.log("Archivo subido correctamente:", response.imagen);
                document.querySelector('#formReceta input[name="imagen"]').value = response.imagen;
            });
        });

        // Función para guardar la receta
        function guardarReceta() {
            const imagen = document.querySelector('#formReceta input[name="imagen"]').value;
            const observaciones = document.getElementById('observaciones_receta').value;

            if (!imagen) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debes subir una imagen de la receta médica',
                });
                return;
            }

            // Guardar la imagen en el campo oculto del formulario principal
            document.getElementById('imagen_receta').value = imagen;

            // Marcar como prescrito
            document.getElementById('es_prescrito').checked = true;
            document.getElementById('campo-reserva').classList.remove('hidden');
            document.getElementById('btn-subir-receta').classList.remove('hidden');

            // Cerrar el modal
            my_modal_2.close();

            Swal.fire({
                icon: 'success',
                title: 'Receta guardada',
                text: 'La receta médica se ha asociado correctamente a la venta',
            });
        }

     </script>
@endpush

