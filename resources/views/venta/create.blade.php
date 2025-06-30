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
    #campo-nuevo-precio.hidden {
    display: none;
    }


    #btn-guardar:disabled {
        background-color: #9ca3af;
        cursor: not-allowed;
    }
    #btn-guardar:disabled:hover {
        background-color: #9ca3af;
    }
    #requerido-receta {
        display: none;
    }
    .es-prescrito #requerido-receta {
        display: inline;
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
        document.getElementById('btn-cancelar').addEventListener('click', function(e) {
        // Verificar si hay cambios no guardados
        const hayCambios = document.querySelectorAll('#tabla-productos tbody tr').length > 0 ||
                        document.querySelector('[name="imagen"]').value ||
                        document.getElementById('imagen_receta').value;

        if (hayCambios) {
            e.preventDefault();
            Swal.fire({
                title: '¿Cancelar venta?',
                text: 'Tienes cambios no guardados. ¿Estás seguro de cancelar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'Continuar editando'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Limpiar datos temporales
                    const imagenNombre = document.querySelector('[name="imagen"]').value;
                    const imagenReceta = document.getElementById('imagen_receta').value;

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

                    if (imagenReceta) {
                        fetch("/eliminar-imagen-temp", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({ imagen: imagenReceta }),
                        });
                    }

                    // Redirigir
                    window.location.href = "{{ route('ventas.index') }}";
                }
            });
        }
    });

</script>
@endpush


@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-7xl mb-10 ">
        <!-- Open the modal using ID.showModal() method -->
        <!-- Contenedor para alerta dinámica -->
         <form id="formVenta" action="{{ route('ventas.store') }}" method="POST" >
            @csrf

            <div id="usuario"></div>
            <div class="lg:grid lg:grid-cols-2 lg:gap-5 sm:grid sm:grid-cols-1 sm:gap-5 items-start">
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

                        <div class="mt-2 mb-5 flex flex-row gap-3">
                                <div>
                                    <button type="button" class="btn" onclick="my_modal_1.showModal()"><i class="fa-solid fa-user-plus"></i></button>
                                </div>
                                <div class="w-full">
                                    <label for="id_persona" class="uppercase block text-sm font-medium text-gray-900">Persona</label>
                                    <select
                                        class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                        name="id_persona"
                                        id="id_persona"
                                        required>
                                        <option value="">Seleccionar persona</option>
                                        @foreach ($personas as $persona)
                                            <option value="{{ $persona->id }}" data-nombre-completo="{{ $persona->nit }}"
                                                @if(isset($personaPre) && $personaPre->id == $persona->id) selected @endif>
                                                {{ $persona->nit }} - {{ $persona->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                        @error('id_persona')
                                            <div role="alert" class="alert alert-error mt-4 p-2">
                                                <span class="text-white font-bold">{{ $message }}</span>
                                            </div>
                                        @enderror
                                </div>
                        </div>
                        {{-- Advertencia para las restricciones  --}}
                        <!-- Contenedor para alerta dinámica -->
                        <div id="restriccion-alert" class="hidden"></div>

                        <!-- formulario para prescripciones -->
                        <div class="mt-2 mb-5 ">
                            <div class="border p-4 rounded-lg bg-slate-50">

                                    <label class="flex justify-between items-center flex-col cursor-pointer md:flex md:flex-row  gap-2">
                                        <span class="label-text mr-2  font-medium">¿Es prescrito?</span>
                                        <input type="checkbox" name="es_prescrito" id="es_prescrito" class="toggle toggle-primary">

                                    </label>

                                  <!-- id de la imagen y campo observacion-->
                                    <input type="hidden" name="imagen_receta" id="imagen_receta" value="">
                                    <input type="hidden" name="observaciones_receta" id="observaciones_receta_value" value="">

                                <button type="button" class="bg-indigo-600 btn hover:bg-indigo-500 btn-sm w-full mt-3 hidden" onclick="my_modal_2.showModal()" id="btn-subir-receta">
                                    <i class="fa-solid fa-upload"></i> <p class="text-white">Subir Receta</p>
                                    <span id="receta-subida-indicator" class="hidden ml-2 text-green-500">
                                        <i class="fa-solid fa-check-circle"></i>
                                    </span>
                                </button>

                                                   <!-- numero de reserva -->
                                <div id="campo-reserva" class="mt-3 hidden ">
                                    <label for="numero_reserva" class="uppercase block text-sm font-medium text-gray-900">Número de Reserva</label>
                                    <input type="text" name="numero_reserva" id="numero_reserva"
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                        placeholder="Ingrese número de reserva">
                                </div>
                            </div>
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


                <fieldset class="border-2 border-gray-200 p-2 rounded-2xl self-start">
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

                        {{--  modulo agregar descuento --}}
                        <div class="mt-2 mb-5 descuento-container bg-slate-50">
                            <div class="border p-4 rounded-lg mt-4">
                                <label class="cursor-pointer label flex justify-between items-center">
                                    <span class="label-text font-medium">¿Aplicar descuento?</span>
                                    <input type="checkbox" name="aplicar_descuento" id="aplicar_descuento" class="toggle toggle-primary">
                                </label>

                                <!-- Campos que aparecen cuando hay descuento -->
                                <div id="campo-nuevo-precio" class="hidden mt-3 ">
                                    <div>
                                        <label for="justificacion_descuento" class="label">
                                            <span class="label-text">Justificación del descuento <span class="text-red-500">*</span></span>
                                        </label>
                                        <textarea name="justificacion_descuento" id="justificacion_descuento"
                                                  class="textarea textarea-bordered w-full" rows="2"></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="label">
                                                <span class="label-text">Precio original</span>
                                            </label>
                                            <input type="number" id="precio_original"
                                                   class="input input-bordered w-full bg-gray-100 size-9 " readonly>
                                        </div>
                                        <div>
                                            <label for="nuevo_precio" class="label">
                                                <span class="label-text">Nuevo precio</span>
                                            </label>
                                            <input type="number" name="nuevo_precio" id="nuevo_precio"
                                                   class="input input-bordered w-full size-9 " min="0.01" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- end cantidad y precio --}}
                        <button id="btn-agregar" type="button" class=" cursor-pointer mt-3 rounded-md bg-indigo-600 px-3 w-full py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Agregar</button>
                    </div>

                </fieldset>


            </div>

            {{-- tabla detalle --}}
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
                <button id="btn-guardar" type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600" disabled>Guardar</button>
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
                            <label class="label" for="dpi">
                                <span class="label-text">DPI <span class="text-red-500">*</span></span>
                            </label>
                            <input type="text" name="dpi" id="dpi" class="input input-bordered" required>
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
                                {{-- <option value="2" {{ old('rol') == 2 ? 'selected' : '' }}>Paciente</option> --}}
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

                    <div id="error-imagen" class="text-red-500 hidden mb-4 p-2 bg-red-50 rounded"></div>


                    <form id="formReceta">
                        @csrf
                        <div class="form-control">
                            <label class="label" for="observaciones_receta">
                                <span class="label-text">Observaciones (Opcional)</span>
                            </label>
                            <textarea name="observaciones_receta" id="observaciones_receta"
                                class="textarea textarea-bordered" rows="3" placeholder="Ingrese observaciones si es necesario"></textarea>
                        </div>

                        <div class="form-control mt-4">
                            <label class="uppercase block text-sm font-medium text-gray-900">Imagen de la receta <span id="requerido-receta" class="text-red-500 hidden">*</span></label>
                            <div id="dropzone" class="dropzone border-2 border-dashed rounded w-full h-60 p-4">
                                <input type="hidden" name="imagen" value="">
                            </div>
                            <div id="error-imagen" class="text-red-500 hidden mt-2">Debes subir una imagen de la receta</div>
                        </div>
                        <div class="modal-action">
                            <button type="button" onclick="my_modal_2.close()" class="btn">Cancelar</button>
                            <button type="button" onclick="guardarReceta()" class="btn btn-primary">Guardar</button>
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
        // validar el estado del formulario
        function verificarEstadoFormularioVenta() {
            const filasProductos = document.querySelectorAll('#tabla-productos tbody tr');
            const sucursal = document.getElementById('id_sucursal').value;
            const persona = document.getElementById('id_persona').value;
            const esPrescrito = document.getElementById('es_prescrito').checked;
            const imagenReceta = document.getElementById('imagen_receta').value;
            const btnGuardar = document.getElementById('btn-guardar');

            // Validar condiciones
            const condicionesBasicas = filasProductos.length > 0 && sucursal && persona;

            // Validar condición especial para prescripciones
            const condicionPrescripcion = !esPrescrito || (esPrescrito && imagenReceta);

            btnGuardar.disabled = !(condicionesBasicas && condicionPrescripcion);
        }

        // llamado de la función cuando presentemos algun cambio
        // document.getElementById('id_sucursal').addEventListener('change', verificarEstadoFormulario);
        // document.getElementById('id_persona').addEventListener('change', verificarEstadoFormulario);
        // document.getElementById('es_prescrito').addEventListener('change', verificarEstadoFormulario);




       // ocuptamos los campos de descuento
        document.addEventListener('DOMContentLoaded', function() {
            const campoDescuento = document.getElementById('campo-nuevo-precio');
            campoDescuento.classList.add('hidden');
        });

        //uso del select2 para proveedores
        $(document).ready(function(){
            $('.select2-sucursal').select2({
                width: '100%',
                placeholder: "Buscar",
                allowClear: true,
                dropdownAutoWidth: true,  // Nueva opción
                adaptDropdownCssClass: true  // Nueva opción
            });
        // pocicionar el cursor en el input para buscar producto
        $('.select2-sucursal').on('select2-sucursal:open', function() {
        document.querySelector('.select2-search__field').focus();
        });
    });
    </script>

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
                    var nombreTruncado = nombreCompleto.length > 10
                        ? nombreCompleto.substring(0, 10) + '...'
                        : nombreCompleto;

                    var ubicacionTruncada = ubicacionCompleta.length > 10
                        ? ubicacionCompleta.substring(0, 10) + '...'
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
                const nombreTruncado = $(option.element).data('nombre-completo').length > 15
                    ? $(option.element).data('nombre-completo').substring(0, 25) + '...'
                    : $(option.element).data('nombre-completo');
                return nombreTruncado;
                //return nombreTruncado + ' - Precio: ' + $(option.element).data('precio');
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


             // Evento para el toggle de IVA (debe estar aquí, no dentro de otro evento)
             $('#impuesto-checkbox').change(function() {
                recalcularTotales();
            });


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
        let precioOriginal;
        let precioProducto
        let nombreProducto
        function mostrarValores() {
            let selectProducto = document.getElementById('id_producto');
            precioOriginal = parseFloat(selectProducto.options[selectProducto.selectedIndex].getAttribute('data-precio'));

             // Mostrar precio original
            $('#precio_original').val(precioOriginal);

            let porcentaje = parseFloat($('#porcentaje').val()) || 0;
            let precioConAumento = round(precioOriginal + (precioOriginal * (porcentaje / 100)));

            precioProducto = precioConAumento;
            nombreProducto = selectProducto.options[selectProducto.selectedIndex].getAttribute('data-nombre-completo');

            $('#precio').val(precioProducto);
        }

        // proceso para el toggle
        $(document).on('change', '#aplicar_descuento', function() {
            const campoDescuento = $('#campo-nuevo-precio');

            if (this.checked) {
                campoDescuento.css('display', 'block').removeClass('hidden');

                // Obtener precio original del producto seleccionado
                const selectedOption = $('#id_producto').find('option:selected');
                if (selectedOption.length) {
                    const precioOriginal = parseFloat(selectedOption.data('precio'));
                    $('#precio_original').val(precioOriginal.toFixed(2));
                }
            } else {
                campoDescuento.css('display', 'none').addClass('hidden');

                // Limpiar campos al desactivar
                $('#justificacion_descuento').val('');
                $('#nuevo_precio').val('');
                $('#precio_original').val('');
            }
        });

        // Manejar cambios en el nuevo precio
        document.getElementById('nuevo_precio').addEventListener('input', function() {
            const nuevoPrecio = parseFloat(this.value) || 0;
            const precioInput = document.getElementById('precio');

            if (nuevoPrecio > 0) {
                precioInput.value = nuevoPrecio;
                precioProducto = nuevoPrecio;
            }
        });


        let contador = 0;
        let subtotal = [];
        let suma = 0;
        let iva = 0;
        let total = 0;
        const impuesto = 12;

function agregarProducto() {



    // nuevos campos para el descuento
    let aplicarDescuento = $('#aplicar_descuento').is(':checked');
    let justificacionDescuento = $('#justificacion_descuento').val();
    let nuevoPrecio = parseFloat($('#nuevo_precio').val()) || 0;

    let idSucursal = $('#id_sucursal').val();
    let id_producto = $('#id_producto').val();
    let producto = nombreProducto;
    let cantidad = parseInt($('#cantidad').val());
    let precio = parseFloat(precioProducto);  // Ya tiene el porcentaje aplicado
    let stock = parseInt($('#stock').val()) || 0;
    let tipo = $('#id_producto').find('option:selected').data('tipo');
    let aplicarImpuesto = $('#impuesto-checkbox').is(':checked');

    // validar si el producto ya está en el detalle compra
    let productoExistente = $(`#tabla-productos tbody tr input[name="arrayIdProducto[]"][value="${id_producto}"]`).closest('tr');
    if (productoExistente.length > 0) {
        let index = productoExistente.find('th').text();
        editarProducto(index, idSucursal);
        return;
    }

    // validaciones para el descuento (solo si está activado)
    if (aplicarDescuento) {
            if (!justificacionDescuento) {
                mensaje('Debe ingresar una justificación para el descuento.');
                return;
            }
            if (nuevoPrecio <= 0 || nuevoPrecio >= precioOriginal) {
                mensaje('El nuevo precio debe ser mayor que 0 y menor que el precio original');
                return;
            }
            precio = nuevoPrecio; // Usar el precio con descuento
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

        // Mostrar precio en tabla (con original tachado si hay descuento)
        let displayPrecio = aplicarDescuento
            ? `<span class="line-through text-gray-500 mr-2">${precioOriginal.toFixed(2)}</span>${precio.toFixed(2)}`
            : precio.toFixed(2);

        // Agregar campos ocultos para descuento (siempre enviamos ambos, pero con valores vacíos si no hay descuento)
        let camposDescuento = `
            <input type="hidden" name="arrayPrecioOriginal[]" value="${precioOriginal}">
            <input type="hidden" name="arrayJustificacion[]" value="${aplicarDescuento ? justificacionDescuento : ''}">
        `;

        // Agregar fila a la tabla
        $('#tabla-productos tbody').append(`
            <tr id="fila${contador}">
                <th>${contador}</th>

                <input type="hidden" name="arraytipo[]" value="${tipo}">
                <td><input type="hidden" name="arrayIdProducto[]" value="${id_producto}">${producto}</td>
                <td><input type="hidden" name="arraycantidad[]" value="${cantidad}">${cantidad}</td>
                <td>
                    <input type="hidden" name="arrayprecio[]" value="${precio}">
                    ${displayPrecio}
                    ${camposDescuento}
                </td>
                <td>${subtotal[contador].toFixed(2)}</td>
                <td>
                    <button type="button" onclick="editarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-edit"></i></button>
                    <button type="button" onclick="eliminarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `);
        // Limpiar y resetear campos de descuento después de agregar
        $('#aplicar_descuento').prop('checked', false).trigger('change');
        $('#justificacion_descuento').val('');
        $('#nuevo_precio').val('');
        $('#precio_original').val('');

        limpiar();

        $('#suma').html(suma.toFixed(2));
        $('#iva').html(iva.toFixed(2));
        $('#total').html(total.toFixed(2));
        $('#impuesto').val(iva.toFixed(2));
        $('#inputTotal').val(total.toFixed(2));

         // Limpiar y resetear campos de descuento después de agregar
        $('#aplicar_descuento').prop('checked', false).trigger('change');
        $('#campo-nuevo-precio').css('display', 'none').addClass('hidden');
        $('#justificacion_descuento').val('');
        $('#nuevo_precio').val('');
        $('#precio_original').val('');

    } else {
        mensaje('Los campos están vacíos o son inválidos.');
    }

    verificarEstadoFormularioVenta();
}



    // Función para editar producto (integrando cantidad, precio y justificación)
function editarProducto(index) {
    let idSucursal = $('#id_sucursal').val();
    let fila = $(`#fila${index}`);
    let cantidadActual = fila.find('input[name="arraycantidad[]"]').val();
    let precioActual = parseFloat(fila.find('input[name="arrayprecio[]"]').val());
    let precioOriginal = parseFloat(fila.find('input[name="arrayPrecioOriginal[]"]').val());
    let idProducto = fila.find('input[name="arrayIdProducto[]"]').val();
    let justificacionActual = fila.find('input[name="arrayJustificacion[]"]').val() || '';
    let tieneDescuento = precioActual < precioOriginal;
    let tipo = fila.find('input[name="arraytipo[]"]').val() || '1';

    // Obtener stock disponible
    $.ajax({
        url: '/productos/stock/' + idProducto + '/' + idSucursal,
        method: 'GET',
        success: function(response) {
            let stockDisponible = response.stock;
            let aplicarImpuestoGlobal = $('#impuesto-checkbox').is(':checked');

            // Configurar el contenido del modal
            let modalContent = `
                <div class="mb-4">
                    <label class="block mb-2">Cantidad (Stock: ${stockDisponible})</label>
                    <input id="edit-cantidad" type="number" value="${cantidadActual}"
                           min="1" max="${stockDisponible}" step="1"
                           class="input input-bordered w-full">
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Precio original: ${precioOriginal.toFixed(2)}</label>
                    <label class="block mb-2">Precio actual:</label>
                    <input id="edit-precio" type="number" value="${precioActual.toFixed(2)}"
                           min="0.01" max="${precioOriginal}" step="0.01"
                           class="input input-bordered w-full">
                </div>
                <div class="mb-4" id="edit-justificacion-container" ${!tieneDescuento ? 'style="display:none;"' : ''}>
                    <label class="block mb-2">Justificación del descuento:</label>
                    <textarea id="edit-justificacion" class="textarea textarea-bordered w-full">${justificacionActual}</textarea>
                </div>
            `;

            Swal.fire({
                title: 'Editar Producto',
                html: modalContent,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                focusConfirm: false,
                didOpen: () => {
                    $('#edit-precio').on('input', function() {
                        let nuevoPrecio = parseFloat($(this).val()) || precioOriginal;
                        let mostrarJustificacion = nuevoPrecio < precioOriginal;
                        $('#edit-justificacion-container').toggle(mostrarJustificacion);
                    });
                },
                preConfirm: () => {
                    let nuevaCantidad = parseInt($('#edit-cantidad').val());
                    let nuevoPrecio = parseFloat($('#edit-precio').val());
                    let justificacion = $('#edit-justificacion').val();

                    // Validaciones
                    if (!nuevaCantidad || nuevaCantidad <= 0 || nuevaCantidad > stockDisponible) {
                        Swal.showValidationMessage('Cantidad inválida');
                        return false;
                    }
                    if (nuevoPrecio <= 0 || nuevoPrecio > precioOriginal) {
                        Swal.showValidationMessage('Precio debe ser > 0 y ≤ precio original');
                        return false;
                    }
                    if (nuevoPrecio < precioOriginal && !justificacion) {
                        Swal.showValidationMessage('Debe ingresar una justificación para el descuento');
                        return false;
                    }

                    return {
                        cantidad: nuevaCantidad,
                        precio: nuevoPrecio,
                        justificacion: justificacion
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let nuevaCantidad = result.value.cantidad;
                    let nuevoPrecio = result.value.precio;
                    let justificacion = result.value.justificacion;

                    // Actualizar valores en la fila
                    fila.find('input[name="arraycantidad[]"]').val(nuevaCantidad);
                    fila.find('input[name="arrayprecio[]"]').val(nuevoPrecio);
                    fila.find('input[name="arrayJustificacion[]"]').val(justificacion);

                    // Actualizar visualización
                    fila.find('td:eq(1)').html(`<input type="hidden" name="arraycantidad[]" value="${nuevaCantidad}">${nuevaCantidad}`);

                    let displayPrecio = nuevoPrecio < precioOriginal
                        ? `<span class="line-through text-gray-500 mr-2">${precioOriginal.toFixed(2)}</span>${nuevoPrecio.toFixed(2)}`
                        : nuevoPrecio.toFixed(2);

                    fila.find('td:eq(2)').html(`
                        <input type="hidden" name="arrayprecio[]" value="${nuevoPrecio}">
                        <input type="hidden" name="arrayPrecioOriginal[]" value="${precioOriginal}">
                        <input type="hidden" name="arrayJustificacion[]" value="${justificacion}">
                        ${displayPrecio}
                    `);

                    // Actualizar subtotal
                    let nuevoSubtotal = round(nuevaCantidad * nuevoPrecio);
                    subtotal[index] = nuevoSubtotal;
                    fila.find('td:eq(3)').text(nuevoSubtotal.toFixed(2));

                    // Recalcular totales (el IVA se manejará globalmente aquí)
                    recalcularTotales();
                }
            });
        },
        error: function() {
            mensaje('Error al obtener el stock disponible');
        }
    });
}
// Función para recalcular totales (actualizada)
function recalcularTotales() {
    suma = 0;
    iva = 0;
    let aplicarImpuesto = $('#impuesto-checkbox').is(':checked');

    $('#tabla-productos tbody tr').each(function() {
        let index = $(this).find('th').text();
        let cantidad = parseFloat($(this).find('input[name="arraycantidad[]"]').val());
        let precio = parseFloat($(this).find('input[name="arrayprecio[]"]').val());
        let tipo = $(this).find('input[name="arraytipo[]"]').val() || '1';

        let subtotalRow = round(cantidad * precio);
        subtotal[index] = subtotalRow; // Actualizar el array de subtotales
        suma += subtotalRow;

        if (tipo === '1' && aplicarImpuesto) {
            iva += round((subtotalRow * impuesto) / 100);
        }
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

            let fila = $(`#fila${index}`);
            let tipo = fila.find('input[name="arraytipo[]"]').val() || '1';
            let subtotalProducto = subtotal[index];

            // Restar del total
            suma -= subtotalProducto;

            // Restar IVA si aplica
            if (tipo === '1' && $('#impuesto-checkbox').is(':checked')) {
                iva -= round((subtotalProducto / 100) * impuesto);
            }

            total = round(suma + iva);

            // Actualizar la interfaz
            $('#suma').html(suma.toFixed(2));
            $('#iva').html(iva.toFixed(2));
            $('#total').html(total.toFixed(2));
            $('#impuesto').val(iva.toFixed(2));
            $('#inputTotal').val(total.toFixed(2));

            // Eliminar la fila
            $(`#fila${index}`).remove();
            delete subtotal[index];

            recalcularTotales();

            // Si no hay productos, resetear todo
            if ($('#tabla-productos tbody tr').length === 0) {
                suma = 0;
                iva = 0;
                total = 0;
                $('#suma, #iva, #total, #impuesto, #inputTotal').text('0.00');
            }

            verificarEstadoFormularioVenta();
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
    let mensaje = `
    <div class="w-full max-w-[100vw]">
        <h5 class="text-md font-semibold mb-4 text-center">Resumen de la Venta</h5>

        <!-- Tabla principal -->
        <div class="overflow-x-auto">
            <table class="table table-zebra table-sm md:table-md w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th class="w-[50%] min-w-[150px]">Producto</th>
                        <th class="text-center w-[15%]">Cantidad</th>
                        <th class="text-right w-[20%]">Precio</th>
                        <th class="text-right w-[15%]">Subtotal</th>
                    </tr>
                </thead>
                <tbody>`;

    $('#tabla-productos tbody tr').each(function() {
        const producto = $(this).find('td:eq(0)').text().trim();
        const cantidad = $(this).find('td:eq(1)').text().trim();
        const precio = $(this).find('td:eq(2)').html().trim(); // Usamos html() para mantener formato de descuento
        const subtotal = $(this).find('td:eq(3)').text().trim();

        mensaje += `
                    <tr>
                        <td class="break-words max-w-[150px] md:max-w-none" title="${producto}">${producto}</td>
                        <td class="text-center">${cantidad}</td>
                        <td class="text-right">${precio}</td>
                        <td class="text-right">${subtotal}</td>
                    </tr>`;
    });

    mensaje += `
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <div class="mt-4 grid grid-cols-1 gap-1 text-sm md:text-base">
            <div class="flex justify-between border-b pb-1">
                <span class="font-medium">SUMA:</span>
                <span>${$('#suma').text().trim()}</span>
            </div>
            <div class="flex justify-between border-b pb-1">
                <span class="font-medium">IVA %:</span>
                <span>${$('#iva').text().trim()}</span>
            </div>
            <div class="flex justify-between font-bold text-lg mt-2">
                <span>TOTAL:</span>
                <span>${$('#total').text().trim()}</span>
            </div>
        </div>
    </div>`;

    return mensaje;
}

    // Modificar el evento submit del formulario
    document.getElementById('formVenta').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validaciones básicas
    if ($('#tabla-productos tbody tr').length === 0) {
        mensaje('Debe agregar al menos un producto a la venta');
        return;
    }

    // Validar receta médica si es prescrito
    const esPrescrito = document.getElementById('es_prescrito').checked;
    const imagenReceta = document.getElementById('imagen_receta').value;

    if (esPrescrito && !imagenReceta) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Para ventas prescritas es obligatorio subir la receta médica',
            confirmButtonText: 'Entendido'
        }).then(() => {
            my_modal_2.showModal(); // Solo abrir modal de receta si falta
        });
        return;
    }

    // Mostrar confirmación de venta
    Swal.fire({
        title: 'Confirmar Venta',
        html: generarResumenVenta(),
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit(); // Enviar formulario solo si se confirma
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
        const filasProductos = document.querySelectorAll('#tabla-productos tbody tr');

        if (filasProductos.length === 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debes agregar al menos un producto al detalle de venta',
            confirmButtonText: 'Entendido'
        });
        return;
    }

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
        console.log('DPI enviado:', formData.get('dpi'));


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

    verificarEstadoFormulario();
});
     </script>

     <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Eliminar cualquier instancia previa de Dropzone
    if (typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;
    }

    // mostrar y ocultar la receta medica
    // Mostrar/ocultar botón de receta según checkbox
    document.getElementById('es_prescrito').addEventListener('change', function() {
        const btnReceta = document.getElementById('btn-subir-receta');
        if (this.checked) {
            btnReceta.classList.remove('hidden');
            document.getElementById('requerido-receta').classList.remove('hidden');
        } else {
            btnReceta.classList.add('hidden');
            document.getElementById('requerido-receta').classList.add('hidden');
            // Limpiar datos si se desmarca
            document.getElementById('imagen_receta').value = '';
            document.getElementById('observaciones_receta_value').value = '';
            document.getElementById('receta-subida-indicator').classList.add('hidden');
        }
    });




    // Configurar Dropzone
    if (typeof dropzoneInstance === 'undefined') {
    var dropzoneInstance = new Dropzone("#dropzone", {
        url: "{{ route('upload.image.temp') }}",
        dictDefaultMessage: "Arrastra y suelta la receta médica o haz clic aquí para subirla",
        acceptedFiles: ".png,.jpg,.jpeg,.pdf",
        addRemoveLinks: true,
        dictRemoveFile: "Borrar receta",
        maxFiles: 1,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        init: function() {

            // Precargar imagen si existe
            const imagenExistente = document.getElementById('imagen_receta').value;
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

            this.on("addedfile", function(file) {
            document.getElementById('error-imagen').classList.add('hidden');
            });

            // Manejar cierre del modal
            document.querySelector('#my_modal_2 .btn[onclick="my_modal_2.close()"]').addEventListener('click', function() {
                // Si hay archivos en dropzone y no se ha guardado, eliminarlos
                if (dropzone.files.length > 0 && !document.getElementById('imagen_receta').value) {
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
            });
        }
    });
}

    // Eventos de Dropzone
    dropzone.on("addedfile", function(file) {
        if (this.files.length > 1) {
            this.removeFile(this.files[0]);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Solo se permite subir una receta.',
                confirmButtonText: 'Aceptar'
            });
        }
    });

    dropzone.on("success", function(file, response) {
        document.querySelector('#formReceta input[name="imagen"]').value = response.imagen;
    });

    dropzone.on("error", function(file, message) {
        console.error("Error al subir la receta:", message);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al subir la receta: ' + message,
        });
    });

    dropzone.on("removedfile", function() {
        const imagenNombre = document.querySelector('#formReceta input[name="imagen"]').value;
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
        document.querySelector('#formReceta input[name="imagen"]').value = "";
    });
});

// Función para guardar la receta (debe estar en el ámbito global)
function guardarReceta() {
    const imagen = document.querySelector('#formReceta input[name="imagen"]').value;
    const observaciones = document.getElementById('observaciones_receta').value;
    const esPrescrito = document.getElementById('es_prescrito').checked;

    // Validación especial para ventas prescritas
    if (esPrescrito && !imagen) {
        document.getElementById('error-imagen').classList.remove('hidden');
        document.getElementById('error-imagen').textContent = 'Para ventas prescritas es obligatorio subir la receta médica';
        return;
    } else {
        document.getElementById('error-imagen').classList.add('hidden');
    }


    // Asignar valores al formulario principal
    document.getElementById('imagen_receta').value = imagen;
    document.getElementById('observaciones_receta_value').value = observaciones;

    // Mostrar indicador de receta subida si hay imagen u observaciones
    if (imagen || observaciones) {
        document.getElementById('receta-subida-indicator').classList.remove('hidden');
    } else {
        document.getElementById('receta-subida-indicator').classList.add('hidden');
    }

    // Cerrar el modal
    my_modal_2.close();

    Swal.fire({
        icon: 'success',
        title: 'Datos guardados',
        text: 'La información de la receta se ha guardado correctamente',
    });
}

// Al abrir el modal, cargar datos existentes
document.getElementById('btn-subir-receta').addEventListener('click', function(e) {
    e.preventDefault();
    const observacionesGuardadas = document.getElementById('observaciones_receta_value').value;
    if (observacionesGuardadas) {
        document.getElementById('observaciones_receta').value = observacionesGuardadas;
    }
    my_modal_2.showModal();
});

     </script>
     {{-- Fin del proceso de imagenes  --}}

     {{-- Proceso apra validar su esta bien el detalle venta  --}}

     <script>
        // Validación del formulario antes de enviar
        document.getElementById('formVenta').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validar detalle de venta
            const filasProductos = document.querySelectorAll('#tabla-productos tbody tr');
            if (filasProductos.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe agregar al menos un producto al detalle de venta',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            // Validar campos obligatorios
            const sucursal = document.getElementById('id_sucursal').value;
            const persona = document.getElementById('id_persona').value;

            if (!sucursal || !persona) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe seleccionar una sucursal y una persona',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            // Validar receta médica si es prescrito
            const esPrescrito = document.getElementById('es_prescrito').checked;
            const imagenReceta = document.getElementById('imagen_receta').value;

            if (esPrescrito && !imagenReceta) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Para ventas prescritas es obligatorio subir la receta médica',
                    confirmButtonText: 'Entendido'
                });
                my_modal_2.showModal();
                return;
            }

            // Verificar stock para productos físicos
            let stockValido = true;
            filasProductos.forEach(fila => {
                const idProducto = fila.querySelector('input[name="arrayIdProducto[]"]').value;
                const cantidad = parseInt(fila.querySelector('input[name="arraycantidad[]"]').value);
                const stock = parseInt(fila.querySelector('input[name="arraystock[]"]').value) || 0;
                const tipo = fila.querySelector('input[name="arraytipo[]"]').value;

                if (tipo === '1' && cantidad > stock) {
                    stockValido = false;
                }
            });

            if (!stockValido) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La cantidad de algunos productos supera el stock disponible',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            // Si todo está bien, mostrar confirmación
            Swal.fire({
                title: 'Confirmar Venta',
                html: generarResumenVenta(),
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });

            this.submitted = true;
        });
    </script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración inicial de Select2 para personas
        $('#id_persona').select2({
            width: '100%',
            placeholder: "Buscar persona",
            allowClear: true,
            templateResult: formatOption,
            templateSelection: formatSelection
        });

        // Función para manejar las restricciones
        function manejarRestricciones(personaId) {
            const alertContainer = document.getElementById('restriccion-alert');
            alertContainer.classList.add('hidden');
            alertContainer.innerHTML = '';

            if (!personaId) return;

            fetch(`/personas/${personaId}/restricciones`)
                .then(response => response.json())
                .then(data => {
                    if (data.tiene_restriccion) {
                        let mensaje = 'ADVERTENCIA: ';
                        mensaje += data.restriccion_activa
                            ? 'Restricción manual activada para este cliente'
                            : `Este cliente ha excedido su límite de compras (${data.compras_recientes}/${data.limite_compras})`;

                        alertContainer.innerHTML = `
                            <div class="alert alert-error shadow-lg mb-4">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span>${mensaje}</span>
                                </div>
                            </div>
                        `;
                        alertContainer.classList.remove('hidden');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Evento change para Select2
        $('#id_persona').on('change', function() {
            manejarRestricciones(this.value);
        });

        // Si hay persona pre-seleccionada, cargar sus restricciones después de un breve retraso
        @if(isset($persona) && $persona)
            setTimeout(() => {
                $('#id_persona').val('{{ $persona->id }}').trigger('change');
            }, 300);
        @endif
    });

    // Funciones de formato para Select2
    function formatOption(option) {
        if (!option.id) return option.text;
        const nit = $(option.element).data('nombre-completo');
        return $('<div>' + option.text + '</div>');
    }

    function formatSelection(option) {
        if (!option.id) return option.text;
        const nit = $(option.element).data('nombre-completo');
        return nit + ' - ' + option.text.substring(0, 20) + (option.text.length > 20 ? '...' : '');
    }
</script>
@endpush
