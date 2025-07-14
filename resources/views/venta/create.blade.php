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
            {{-- <input type="hidden" id="userSucursalId" name="userSucursalId"> --}}
            <div class="lg:grid lg:grid-cols-2 lg:gap-5 sm:grid sm:grid-cols-1 sm:gap-5 items-start">
                <fieldset class="border-2 border-gray-200 p-2 rounded-2xl">
                    <legend class="text-blue-500 font-bold">Datos Generales</legend>
                    <div class="border-b border-gray-900/10 ">

                        <div class="mt-2 mb-5">
                            <label for="id_sucursal" class="uppercase block text-sm font-medium text-gray-900">Farmaci</label>
                            <select
                                class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                name="id_sucursal"
                                id="id_sucursal"
                                required>
                                <option value="">Seleccionar una Farmacia</option>
                                @foreach ($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}" data-nombre-completo="{{ $sucursal->nombre }}" data-ubicacion-completa="{{ $sucursal->ubicacion }}">
                                        {{-- {{ $sucursal->nombre }} - {{ $sucursal->ubicacion }} --}}
                                        {{ $sucursal->nombre }}
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
                                        <option value="{{ $persona->id }}"
                                                data-nombre-completo="{{ $persona->dpi }} @if($persona->nit && $persona->nit != '0') - {{ $persona->nit }} @endif"
                                                data-dpi="{{ $persona->dpi }}"
                                                data-nit="{{ $persona->nit }}"
                                                @if(isset($personaPre) && $personaPre->id == $persona->id) selected @endif>
                                            {{ $persona->dpi }} @if($persona->nit && $persona->nit != '0') - {{ $persona->nit }} @endif - {{ $persona->nombre }}
                                            @if($persona->nit == '0') (Consumidor Final) @endif
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
                                        <img id="imagen" src="" alt="Imagen del producto" class="w-24 h-24 object-cover rounded" >
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
                        <div class="mt-2 mb-5 descuento-container bg-slate-50" style="display: none;">
                            <div class="border p-4 rounded-lg mt-4">
                                <label class="cursor-pointer label flex justify-between items-center">
                                    <span class="label-text font-medium">¿Aplicar descuento para cliente antiguo?</span>
                                    <input type="checkbox" name="aplicar_descuento" id="aplicar_descuento" class="toggle toggle-primary">
                                </label>

                                <!-- Campos que aparecen cuando hay descuento -->
                                <div id="campo-nuevo-precio" class="hidden mt-3">
                                    <div>
                                        <label for="justificacion_descuento" class="label">
                                            <span class="label-text">Justificación del descuento <span class="text-red-500">*</span></span>
                                        </label>
                                        <textarea name="justificacion_descuento" id="justificacion_descuento"
                                                  class="textarea textarea-bordered w-full" rows="2" ></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 mt-3">
                                        <div>
                                            <label class="label">
                                                <span class="label-text">Precio normal</span>
                                            </label>
                                            <input type="number" id="precio_original"
                                                   class="input input-bordered w-full bg-gray-100" readonly>
                                        </div>
                                        <div>
                                            <label for="nuevo_precio" class="label">
                                                <span class="label-text">Precio con descuento</span>
                                            </label>
                                            <input type="number" name="nuevo_precio" id="nuevo_precio"
                                                   class="input input-bordered w-full" min="0.01" step="0.01" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- end cantidad y precio --}}
                        <button id="btn-agregar" type="button" class=" cursor-pointer mt-3 rounded-md bg-indigo-600 px-3 w-full py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Agregar</button>
                    </div>

                </fieldset>

                 <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                    <div class="modal-box max-w-5xl m-auto">
                        <span class="text-white text-2xl cursor-pointer absolute top-4 right-4" onclick="closeModal()">&times;</span>
                        <div class="">
                            <img id="modalImage" src="" alt="Receta Médica" class="w-full h-auto mt-4">
                        </div>
                        <div class="mt-4 text-center">
                            <button onclick="closeModal()" class="bg-red-600 text-white py-2 px-4 rounded-md text-sm font-semibold hover:bg-red-700 focus:outline-none">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>


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
                            <div id="error-nombre" class="text-red-500 hidden mt-1 text-sm"></div>
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
                            <div id="error-dpi" class="text-red-500 hidden mt-1 text-sm"></div>
                        </div>

                        <div class="form-control">
                            <label class="label" for="nit">
                                <span class="label-text">NIT</span>
                            </label>
                            <input type="text" name="nit" id="nit" class="input input-bordered" >
                            <div id="error-nit" class="text-red-500 hidden mt-1 text-sm"></div>
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
<script src="/js/obtenerUsuario.js"></script>
{{-- <script src="/js/obtenerSucursalUsuario.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>
<script>


window.generarResumenVenta = function() {
    let mensaje = `
    <div class="w-full max-w-[100vw]">
        <h5 class="text-md font-semibold mb-4 text-center">Resumen de la Venta</h5>
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
        const precio = $(this).find('td:eq(2)').html().trim();
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
};


    // Configuración inicial
        let precioOriginal;
        let precioProducto;
        let nombreProducto;
        let contador = 0;
        let subtotal = [];
        let suma = 0;
        let iva = 0;
        let total = 0;
        const impuesto = 12;

        // Función para verificar estado del formulario
        function verificarEstadoFormularioVenta() {
            const filasProductos = document.querySelectorAll('#tabla-productos tbody tr');
            const sucursal = document.getElementById('id_sucursal').value;
            const persona = document.getElementById('id_persona').value;
            const esPrescrito = document.getElementById('es_prescrito').checked;
            const imagenReceta = document.getElementById('imagen_receta').value;
            const btnGuardar = document.getElementById('btn-guardar');

            // Validar condiciones
            const tieneProductos = filasProductos.length > 0;
            const tieneSucursal = sucursal !== '';
            const tienePersona = persona !== '';
            const prescripcionValida = !esPrescrito || (esPrescrito && imagenReceta);

            // Habilitar/deshabilitar botón
            btnGuardar.disabled = !(tieneProductos && tieneSucursal && tienePersona && prescripcionValida);
        }

        // En el script principal, restaurar el manejo del formulario de persona
document.getElementById('formPersona').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if(data.success) {
            // Cerrar modal
            my_modal_1.close();

            // Limpiar formulario
             document.getElementById('formPersona').reset();

              // Limpiar errores visuales si los hubiera
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

            // Restaurar el texto del botón de submit
            const submitButton = document.querySelector('#formPersona button[type="submit"]');
            submitButton.disabled = false;
            submitButton.innerHTML = 'Guardar';

            // Agregar nueva opción al select de personas
            const select = $('#id_persona');
            const newOption = new Option(
                `${data.persona.dpi} ${data.persona.nit ? ' - '+data.persona.nit : ''} - ${data.persona.nombre}`,
                data.persona.id,
                true,
                true
            );

            // Agregar datos adicionales al option
            $(newOption).data('dpi', data.persona.dpi);
            $(newOption).data('nit', data.persona.nit);

            select.append(newOption).trigger('change');

            // Mostrar mensaje de éxito
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Persona registrada correctamente'
            });
        }
    })
    .catch(error => {
        if (error.errors) {
            // Mostrar errores de validación en el modal
            mostrarErroresValidacion(error.errors);
        } else {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al registrar la persona'
            });
        }
    });
});

function mostrarErroresValidacion(errors) {
    // Limpiar errores anteriores
    document.querySelectorAll('.error-message').forEach(el => el.remove());

    // Mostrar nuevos errores
    for (const [field, messages] of Object.entries(errors)) {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = messages.join(', ');
            input.parentNode.insertBefore(errorDiv, input.nextSibling);

            // Resaltar campo con error
            input.classList.add('border-red-500');
        }
    }
}

// Mostrar/ocultar botón de receta según checkbox
document.getElementById('es_prescrito').addEventListener('change', function() {
    const btnReceta = document.getElementById('btn-subir-receta');
    if (this.checked) {
        btnReceta.classList.remove('hidden');
        document.getElementById('requerido-receta').classList.remove('hidden');
    } else {
        btnReceta.classList.add('hidden');
        document.getElementById('requerido-receta').classList.add('hidden');
        document.getElementById('imagen_receta').value = '';
        document.getElementById('observaciones_receta_value').value = '';
        document.getElementById('receta-subida-indicator').classList.add('hidden');
    }
    verificarEstadoFormularioVenta();
});

// Función para guardar receta
window.guardarReceta = function() {
    const imagen = document.querySelector('#formReceta input[name="imagen"]').value;
    const observaciones = document.getElementById('observaciones_receta').value;
    const esPrescrito = document.getElementById('es_prescrito').checked;

    if (esPrescrito && !imagen) {
        document.getElementById('error-imagen').classList.remove('hidden');
        document.getElementById('error-imagen').textContent = 'Para ventas prescritas es obligatorio subir la receta médica';
        return;
    }

    document.getElementById('imagen_receta').value = imagen;
    document.getElementById('observaciones_receta_value').value = observaciones;

    if (imagen || observaciones) {
        document.getElementById('receta-subida-indicator').classList.remove('hidden');
    } else {
        document.getElementById('receta-subida-indicator').classList.add('hidden');
    }

    my_modal_2.close();
    mensaje('La información de la receta se ha guardado correctamente', 'success');
    verificarEstadoFormularioVenta();
};

// Abrir modal de receta
document.getElementById('btn-subir-receta').addEventListener('click', function(e) {
    e.preventDefault();
    const observacionesGuardadas = document.getElementById('observaciones_receta_value').value;
    if (observacionesGuardadas) {
        document.getElementById('observaciones_receta').value = observacionesGuardadas;
    }
    my_modal_2.showModal();
});

        // Eventos para verificar estado del formulario - AGREGADOS
        $(document).ready(function() {
            // Verificar al cambiar cualquier campo relevante
            $('#id_sucursal, #id_persona, #es_prescrito').on('change', verificarEstadoFormularioVenta);

            // También verificar cuando se agregan/eliminan productos
            document.getElementById('btn-agregar').addEventListener('click', verificarEstadoFormularioVenta);
            $(document).on('click', '[onclick^="eliminarProducto"]', verificarEstadoFormularioVenta);

            // Verificar al cargar la página
            verificarEstadoFormularioVenta();
        });

        // Inicialización de Select2
        $(document).ready(function(){
            // Configuración para sucursales
            $('#id_sucursal').select2({
                width: '100%',
                placeholder: "Buscar farmacia",
                allowClear: true,
                templateResult: formatOption,
                templateSelection: formatSelection
            });

            // Auto-seleccionar sucursal del usuario
            function autoSeleccionarSucursal() {
                const token = localStorage.getItem('jwt_token');
                if (!token) return;

                try {
                    const decoded = jwt_decode(token);
                    const sucursalId = decoded.sucursal_id;

                    if (sucursalId && $(`#id_sucursal option[value="${sucursalId}"]`).length > 0) {
                        $('#id_sucursal').val(sucursalId).trigger('change');
                    }
                } catch (error) {
                    console.error('Error al auto-seleccionar sucursal:', error);
                }
            }

            setTimeout(autoSeleccionarSucursal, 200);

            // Configuración para personas
            $('#id_persona').select2({
                width: '100%',
                placeholder: "Buscar persona",
                allowClear: true,
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') return data;
                    var term = params.term.toLowerCase();
                    var dpi = $(data.element).data('dpi')?.toString().toLowerCase() || '';
                    var nit = $(data.element).data('nit')?.toString().toLowerCase() || '';
                    var nombre = data.text.toLowerCase();
                    return (dpi.includes(term) || nit.includes(term) || nombre.includes(term)) ? data : null;
                },
                templateResult: formatPersonaOption,
                templateSelection: formatPersonaSelection
            });

            // Cargar productos cuando cambia la sucursal
            $('#id_sucursal').change(function() {
                var sucursalId = $(this).val();
                if (sucursalId) {
                    $.ajax({
                        url: '/productos/sucursal/' + sucursalId,
                        method: 'GET',
                        success: function(response) {
                            $('#id_producto').empty().append('<option value="">Buscar un producto</option>');
                            response.forEach(function(producto) {
                                $('#id_producto').append(`
                                    <option value="${producto.id}"
                                        data-precio="${producto.precio_venta}"
                                        data-precio-porcentaje="${producto.precio_porcentaje || producto.precio_venta}"
                                        data-nombre-completo="${producto.nombre}"
                                        data-tipo="${producto.tipo}"
                                        data-stock="${producto.stock}"
                                        data-imagen="${producto.imagen}">
                                        ${producto.nombre} - Precio: ${producto.precio_venta}
                                    </option>
                                `);
                            });
                            $('#id_producto').select2({
                                width: '100%',
                                placeholder: "Buscar un producto",
                                allowClear: true,
                                templateResult: formatOption,
                                templateSelection: formatSelection
                            });
                        },
                        error: function() {
                            alert('Error al cargar los productos');
                        }
                    });
                } else {
                    $('#id_producto').empty().append('<option value="">Buscar un producto</option>');
                }
            });

            // Mostrar imagen del producto
            $('#id_producto').change(function() {
                const selectedOption = $(this).find('option:selected');
                const imagenUrl = selectedOption.data('imagen');
                if (imagenUrl) {
                    $('#imagen-producto').removeClass('hidden');
                    $('#imagen').attr('src', imagenUrl);
                } else {
                    $('#imagen-producto').addClass('hidden');
                }
            });

            // Manejar cambios en el producto
            $('#id_producto').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const tipo = selectedOption.data('tipo');
                const stock = selectedOption.data('stock');

                if (tipo === 1) { // Producto
                    $('#stock').val(stock).prop('readonly', true);
                    $('#cantidad').prop('disabled', false).attr('placeholder', 'Ingrese la cantidad');
                } else { // Servicio
                    $('#stock').val('').prop('placeholder', 'N/A');
                    $('#impuesto').val('').prop('placeholder', 'N/A');
                    $('#cantidad').prop('disabled', true).val('').attr('placeholder', 'No aplica');
                }

                // Mostrar/ocultar módulo de descuento
                if (tipo == 2) {
                    $('.descuento-container').show();
                } else {
                    $('.descuento-container').hide();
                    $('#aplicar_descuento').prop('checked', false).trigger('change');
                }

                mostrarValores();
            });

            // Evento para el toggle de IVA
            $('#impuesto-checkbox').change(recalcularTotales);

            // Botón agregar producto
            $('#btn-agregar').click(agregarProducto);

            // Porcentaje de aumento
            $('#porcentaje').on('input', mostrarValores);
        });

        // Función para mostrar valores del producto
        function mostrarValores() {
            let selectProducto = document.getElementById('id_producto');
            let selectedOption = selectProducto.options[selectProducto.selectedIndex];

            if (!selectedOption || !selectedOption.value) return;

            // Siempre mostrar el precio normal primero
            precioOriginal = parseFloat(selectedOption.getAttribute('data-precio'));
            nombreProducto = selectedOption.getAttribute('data-nombre-completo');

            // Aplicar porcentaje si existe
            let porcentaje = parseFloat($('#porcentaje').val()) || 0;
            precioProducto = round(precioOriginal + (precioOriginal * (porcentaje / 100)));

            $('#precio').val(precioProducto);
            $('#precio_original').val(precioOriginal);

            let tipoProducto = selectedOption.getAttribute('data-tipo');
            let personaId = $('#id_persona').val();
            let precioVenta = parseFloat(selectedOption.getAttribute('data-precio'));
            let precioPorcentaje = parseFloat(selectedOption.getAttribute('data-precio-porcentaje')) || precioVenta;

            // Precio base (sin descuento)
            precioOriginal = precioVenta;

            // Para servicios, verificar si es cliente antiguo
            if (tipoProducto == 2 && personaId) {
                $.ajax({
                    url: '/api/personas/' + personaId + '/es-antiguo',
                    method: 'GET',
                    success: function(response) {
                        if (response.es_antiguo) {
                            precioOriginal = precioPorcentaje;
                            $('#precio').addClass('text-green-600');
                            $('#nuevo_precio').val(precioPorcentaje).prop('readonly', true);
                        } else {
                            $('#precio').removeClass('text-green-600');
                            $('#nuevo_precio').val('').prop('readonly', false);
                        }

                        // Aplicar porcentaje si existe
                        let porcentaje = parseFloat($('#porcentaje').val()) || 0;
                        precioProducto = round(precioOriginal + (precioOriginal * (porcentaje / 100)));
                        nombreProducto = selectedOption.getAttribute('data-nombre-completo');

                        $('#precio').val(precioProducto);
                        $('#precio_original').val(precioOriginal);
                    },
                    error: function() {
                        console.error('Error al verificar cliente antiguo');
                        // Continuar con precio normal si hay error
                        let porcentaje = parseFloat($('#porcentaje').val()) || 0;
                        precioProducto = round(precioOriginal + (precioOriginal * (porcentaje / 100)));
                        nombreProducto = selectedOption.getAttribute('data-nombre-completo');

                        $('#precio').val(precioProducto);
                        $('#precio_original').val(precioOriginal);
                    }
                });
            } else {
                // Para productos o cuando no hay cliente seleccionado
                let porcentaje = parseFloat($('#porcentaje').val()) || 0;
                precioProducto = round(precioOriginal + (precioOriginal * (porcentaje / 100)));
                nombreProducto = selectedOption.getAttribute('data-nombre-completo');

                $('#precio').val(precioProducto);
                $('#precio_original').val(precioOriginal);
            }
        }

        // Toggle para aplicar descuento
        // Toggle para aplicar descuento - MODIFICADA
        $(document).on('change', '#aplicar_descuento', function() {
            const campoDescuento = $('#campo-nuevo-precio');
            const selectedOption = $('#id_producto').find('option:selected');

            if (this.checked && selectedOption.length) {
                campoDescuento.css('display', 'block').removeClass('hidden');

                const tipoProducto = selectedOption.data('tipo');
                const precioVenta = parseFloat(selectedOption.data('precio'));
                let precioPorcentaje = parseFloat(selectedOption.data('precio-porcentaje')) || precioVenta;

                $('#precio_original').val(precioVenta.toFixed(2));

                // Solo para servicios (tipo 2), auto-completar con precio porcentaje
                if (tipoProducto == 2) {
                    $('#nuevo_precio').val(precioPorcentaje.toFixed(2));
                    precioProducto = precioPorcentaje;
                    $('#precio').val(precioPorcentaje.toFixed(2));
                }
            } else {
                campoDescuento.css('display', 'none').addClass('hidden');
                $('#justificacion_descuento').val('');
                $('#nuevo_precio').val('');

                // Restaurar precio original al desactivar descuento
                if (selectedOption.length) {
                    let porcentaje = parseFloat($('#porcentaje').val()) || 0;
                    precioOriginal = parseFloat(selectedOption.data('precio'));
                    precioProducto = round(precioOriginal + (precioOriginal * (porcentaje / 100)));
                    $('#precio').val(precioProducto);
                }
            }
        });

        // Manejar cambios en el nuevo precio
        $('#nuevo_precio').on('input', function() {
            const nuevoPrecio = parseFloat(this.value) || 0;
            if (nuevoPrecio > 0) {
                $('#precio').val(nuevoPrecio);
                precioProducto = nuevoPrecio;
            }
        });

        // Función para agregar producto al detalle
       // Función para agregar producto - CORREGIDA
function agregarProducto() {
    let idSucursal = $('#id_sucursal').val();
    let id_producto = $('#id_producto').val();
    let producto = nombreProducto;
    let cantidad = parseInt($('#cantidad').val());
    let precio = parseFloat(precioProducto);
    let stock = parseInt($('#stock').val()) || 0;
    let tipo = $('#id_producto').find('option:selected').data('tipo');
    let aplicarDescuento = $('#aplicar_descuento').is(':checked');
    let justificacionDescuento = $('#justificacion_descuento').val();
    let nuevoPrecio = parseFloat($('#nuevo_precio').val()) || 0;
    let aplicarImpuesto = $('#impuesto-checkbox').is(':checked'); // Añadido esta línea

    // Obtener precios del producto
    let selectedOption = $('#id_producto').find('option:selected');
    let precioVenta = parseFloat(selectedOption.data('precio'));
    let precioPorcentaje = parseFloat(selectedOption.data('precio-porcentaje')) || precioVenta;
    let precioConDescuento = tipo == 2 && aplicarDescuento; // Añadido esta línea

    // Validar producto existente
    let productoExistente = $(`#tabla-productos tbody tr input[name="arrayIdProducto[]"][value="${id_producto}"]`).closest('tr');
    if (productoExistente.length > 0) {
        editarProducto(productoExistente.find('th').text(), idSucursal);
        return;
    }

    // Validaciones para el descuento
    if (aplicarDescuento) {
        if (tipo == 2) { // Servicio
            if (!justificacionDescuento) {
                mensaje('Debe ingresar una justificación para el descuento.');
                return;
            }
            if (nuevoPrecio <= 0 || nuevoPrecio >= precioVenta) {
                mensaje('El nuevo precio debe ser mayor que 0 y menor que el precio original');
                return;
            }
            precio = nuevoPrecio;
        } else {
            // No permitir descuentos en productos
            $('#aplicar_descuento').prop('checked', false);
            $('#campo-nuevo-precio').hide();
        }
    }

    if (id_producto != '' && producto != '' && precio > 0) {
        if (tipo === 1) { // Producto
            if (!cantidad || cantidad <= 0 || cantidad % 1 !== 0) {
                mensaje('Favor ingresar una cantidad válida.');
                return;
            }
            if (cantidad > stock) {
                mensaje(`La cantidad ingresada (${cantidad}) supera el stock disponible (${stock}).`);
                return;
            }
        } else { // Servicio
            cantidad = 1;
        }

        contador++;
        subtotal[contador] = round(cantidad * precio);
        suma += subtotal[contador];

        if (tipo === 1 && aplicarImpuesto) {
            iva += round((subtotal[contador] / 100) * impuesto);
        }

        total = round(suma + iva);

        // Mostrar precio en tabla
        let displayPrecio = '';
        if (precioConDescuento) {
            displayPrecio = `<span class="line-through text-gray-500 mr-2">${precioVenta.toFixed(2)}</span>${precio.toFixed(2)} <span class="text-green-600 text-xs">(Desc. cliente)</span>`;
        } else if (aplicarDescuento) {
            displayPrecio = `<span class="line-through text-gray-500 mr-2">${precioVenta.toFixed(2)}</span>${precio.toFixed(2)}`;
        } else {
            displayPrecio = precio.toFixed(2);
        }

        // Agregar fila a la tabla
        // En tu función agregarProducto(), asegúrate de incluir todos los campos:
        // En tu función agregarProducto(), asegúrate que cada fila tenga:
        $('#tabla-productos tbody').append(`
            <tr id="fila${contador}">
                <th>${contador}</th>
                <input type="hidden" name="arraytipo[]" value="${tipo}">
                <input type="hidden" name="arrayIdProducto[]" value="${id_producto}">
                <input type="hidden" name="arrayStock[]" value="${stock}">
                <input type="hidden" name="arrayPrecioOriginal[]" value="${precioOriginal}">
                <input type="hidden" name="arrayJustificacion[]" value="${justificacionDescuento || ''}">
                <td>${producto}</td>
                <td><input type="hidden" name="arraycantidad[]" value="${cantidad}">${cantidad}</td>
                <td>
                    <input type="hidden" name="arrayprecio[]" value="${precio}">
                    ${displayPrecio}
                </td>
                <td>${subtotal[contador].toFixed(2)}</td>
                <td>
                    <button type="button" onclick="editarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-edit"></i></button>
                    <button type="button" onclick="eliminarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `);
                // Limpiar campos
                limpiar();
                $('#aplicar_descuento').prop('checked', false).trigger('change');
                $('#campo-nuevo-precio').css('display', 'none').addClass('hidden');
                $('#justificacion_descuento').val('');
                $('#nuevo_precio').val('');
                $('#precio_original').val('');

                // Actualizar totales
                $('#suma').html(suma.toFixed(2));
                $('#iva').html(iva.toFixed(2));
                $('#total').html(total.toFixed(2));
                $('#impuesto').val(iva.toFixed(2));
                $('#inputTotal').val(total.toFixed(2));
            } else {
                mensaje('Los campos están vacíos o son inválidos.');
            }

            verificarEstadoFormularioVenta();
}

// Función para editar producto - MODIFICADA para restringir cambios de precio en servicios
// Función para editar producto - MODIFICADA para restringir cambios de precio en servicios
function editarProducto(index) {
    let fila = $(`#fila${index}`);
    let cantidadActual = fila.find('input[name="arraycantidad[]"]').val();
    let precioActual = parseFloat(fila.find('input[name="arrayprecio[]"]').val());
    let precioOriginal = parseFloat(fila.find('input[name="arrayPrecioOriginal[]"]').val());
    let idProducto = fila.find('input[name="arrayIdProducto[]"]').val();
    let tipo = fila.find('input[name="arraytipo[]"]').val() || '1';
    let justificacionActual = fila.find('input[name="arrayJustificacion[]"]').val() || '';

    // Para servicios, obtener si ya tenía descuento aplicado
    let tieneDescuento = tipo === '2' && precioActual < precioOriginal;

    $.ajax({
        url: '/productos/stock/' + idProducto + '/' + $('#id_sucursal').val(),
        method: 'GET',
        success: function(response) {
            let stockDisponible = tipo === '1' ? response.stock : 'N/A';
            let modalContent = `
                <div class="mb-4">
                    <label class="block mb-2">${tipo === '1' ? 'Cantidad (Stock: '+stockDisponible+')' : 'Servicio'}</label>
            `;

            if (tipo === '1') {
                // Producto - permitir editar cantidad
                modalContent += `
                    <input id="edit-cantidad" type="number" value="${cantidadActual}"
                           min="1" max="${stockDisponible}" step="1"
                           class="input input-bordered w-full">
                `;
            } else {
                // Servicio - mostrar información fija
                modalContent += `
                    <div class="p-2 bg-gray-100 rounded">
                        <p class="font-semibold">Cantidad: 1 (Servicio)</p>
                        <p class="font-semibold">Precio: ${precioActual.toFixed(2)}</p>
                        ${tieneDescuento ? '<p class="text-green-600 text-sm">Descuento aplicado</p>' : ''}
                    </div>
                    <input type="hidden" id="edit-cantidad" value="1">
                `;
            }

            modalContent += `</div>`;

            // Solo para servicios con descuento, mostrar justificación
            if (tipo === '2' && tieneDescuento) {
                modalContent += `
                    <div class="mb-4">
                        <label class="block mb-2">Justificación del descuento:</label>
                        <div class="p-2 bg-gray-100 rounded">
                            <p>${justificacionActual || 'Sin justificación registrada'}</p>
                        </div>
                    </div>
                `;
            }

            Swal.fire({
                title: 'Editar ' + (tipo === '1' ? 'Producto' : 'Servicio'),
                html: modalContent,
                showCancelButton: tipo === '1', // Solo mostrar cancelar para productos
                confirmButtonText: tipo === '1' ? 'Guardar' : 'Cerrar',
                cancelButtonText: 'Cancelar',
                showDenyButton: tipo === '1' ? false : false,
                denyButtonText: 'Eliminar',
                focusConfirm: false,
                preConfirm: () => {
                    if (tipo === '1') {
                        let nuevaCantidad = parseInt($('#edit-cantidad').val());

                        if (!nuevaCantidad || nuevaCantidad <= 0 || nuevaCantidad > stockDisponible) {
                            Swal.showValidationMessage('Cantidad inválida');
                            return false;
                        }

                        return { cantidad: nuevaCantidad };
                    }
                    return null;
                }
            }).then((result) => {
                if (result.isConfirmed && tipo === '1') {
                    // Actualizar solo cantidad para productos
                    fila.find('input[name="arraycantidad[]"]').val(result.value.cantidad);
                    fila.find('td:eq(1)').html(`
                        <input type="hidden" name="arraycantidad[]" value="${result.value.cantidad}">
                        ${result.value.cantidad}
                    `);

                    // Recalcular subtotal y totales
                    let nuevoSubtotal = round(result.value.cantidad * precioActual);
                    subtotal[index] = nuevoSubtotal;
                    fila.find('td:eq(3)').text(nuevoSubtotal.toFixed(2));

                    recalcularTotales();
                } else if (result.isDenied) {
                    // Opción para eliminar si es necesario
                    eliminarProducto(index);
                }
            });
        },
        error: function() {
            mensaje('Error al obtener la información del producto');
        }
    });
}

// Función para recalcular totales
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
                subtotal[index] = subtotalRow;
                suma += subtotalRow;

                if (tipo === '1' && aplicarImpuesto) {
                    iva += round((subtotalRow * impuesto) / 100);
                }
            });

            total = round(suma + iva);

            // Actualizar UI
            $('#suma').html(suma.toFixed(2));
            $('#iva').html(iva.toFixed(2));
            $('#total').html(total.toFixed(2));
            $('#impuesto').val(iva.toFixed(2));
            $('#inputTotal').val(total.toFixed(2));
        }

        // Función para eliminar producto
        function eliminarProducto(index) {
            let fila = $(`#fila${index}`);
            let tipo = fila.find('input[name="arraytipo[]"]').val() || '1';
            let subtotalProducto = subtotal[index];

            // Restar del total
            suma -= subtotalProducto;
            if (tipo === '1' && $('#impuesto-checkbox').is(':checked')) {
                iva -= round((subtotalProducto / 100) * impuesto);
            }
            total = round(suma + iva);

            // Actualizar UI
            $('#suma').html(suma.toFixed(2));
            $('#iva').html(iva.toFixed(2));
            $('#total').html(total.toFixed(2));
            $('#impuesto').val(iva.toFixed(2));
            $('#inputTotal').val(total.toFixed(2));

            // Eliminar fila
            $(`#fila${index}`).remove();
            delete subtotal[index];

            // Resetear si no hay productos
            if ($('#tabla-productos tbody tr').length === 0) {
                suma = 0;
                iva = 0;
                total = 0;
                $('#suma, #iva, #total, #impuesto, #inputTotal').text('0.00');
            }

            verificarEstadoFormularioVenta();
        }

        // Función para limpiar campos
        function limpiar() {
            $('#id_producto').val(null).trigger('change');
            $('#producto').val('');
            $('#cantidad').val('');
            $('#precio').val('');
        }

        // Función para mostrar mensajes
        function mensaje(texto, icon = "error") {
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
            Toast.fire({ icon: icon, title: texto });
        }

        // Función para redondear números
        function round(num, decimales = 2) {
            var signo = (num >= 0 ? 1 : -1);
            num = num * signo;
            if (decimales === 0) return signo * Math.round(num);
            num = num.toString().split('e');
            num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
            num = num.toString().split('e');
            return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
        }

        // Funciones de formato para Select2
        function formatOption(option) {
            if (!option.id) return option.text;
            var nombreCompleto = $(option.element).data('nombre-completo');
            var ubicacionCompleta = $(option.element).data('ubicacion-completa') || '';
            return $('<div>' + nombreCompleto + (ubicacionCompleta ? ' - ' + ubicacionCompleta : '') + '</div>');
        }

        function formatSelection(option) {
            if (!option.id) return option.text;
            var nombreCompleto = $(option.element).data('nombre-completo');
            var ubicacionCompleta = $(option.element).data('ubicacion-completa') || '';
            var nombreTruncado = nombreCompleto.length > 10 ? nombreCompleto.substring(0, 10) + '...' : nombreCompleto;
            return nombreTruncado;
        }

        function formatPersonaOption(option) {
            if (!option.id) return option.text;
            var dpi = $(option.element).data('dpi');
            var nit = $(option.element).data('nit');
            var nombre = option.text.split(' - ').slice(2).join(' - ');
            var displayText = (dpi || 'Sin DPI') + (nit ? ' - ' + nit : '') + ' - ' + nombre;
            return $('<div>' + displayText + '</div>');
        }

        function formatPersonaSelection(option) {
            if (!option.id) return option.text;
            var dpi = $(option.element).data('dpi');
            var nit = $(option.element).data('nit');
            var nombre = option.text.split(' - ').slice(2).join(' - ');
            var displayText = (dpi || 'Sin DPI') + (nit ? ' - ' + nit : '');
            var nombreTruncado = nombre.length > 20 ? nombre.substring(0, 20) + '...' : nombre;
            return displayText + ' - ' + nombreTruncado;
        }

        // Inicializar Dropzone solo una vez
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Dropzone !== 'undefined' && typeof dropzoneInstance === 'undefined') {
                Dropzone.autoDiscover = false;
                var dropzoneInstance = new Dropzone("#dropzone", {
                    url: "{{ route('upload.image.temp') }}",
                    dictDefaultMessage: "Arrastra y suelta la receta médica o haz clic aquí para subirla",
                    acceptedFiles: ".png,.jpg,.jpeg,.pdf",
                    addRemoveLinks: true,
                    dictRemoveFile: "Borrar receta",
                    maxFiles: 1,
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    init: function() {
                        const imagenExistente = document.getElementById('imagen_receta').value;
                        if (imagenExistente) {
                            const mockFile = { name: imagenExistente, size: 12345, accepted: true };
                            this.emit("addedfile", mockFile);
                            this.emit("thumbnail", mockFile, "{{ asset('uploads/temp') }}/" + imagenExistente);
                            this.emit("complete", mockFile);
                        }

                        this.on("addedfile", function(file) {
                            document.getElementById('error-imagen').classList.add('hidden');
                            if (this.files.length > 1) {
                                this.removeFile(this.files[0]);
                                mensaje('Solo se permite subir una receta.');
                            }
                        });

                        this.on("success", function(file, response) {
                            document.querySelector('#formReceta input[name="imagen"]').value = response.imagen;
                        });

                        this.on("error", function(file, message) {
                            console.error("Error al subir la receta:", message);
                            mensaje('Error al subir la receta: ' + message);
                        });

                        this.on("removedfile", function() {
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
                    }
                });
            }

            // Un solo manejador de submit bien estructurado



            // Mostrar/ocultar receta médica
            document.getElementById('es_prescrito').addEventListener('change', function() {
                const btnReceta = document.getElementById('btn-subir-receta');
                if (this.checked) {
                    btnReceta.classList.remove('hidden');
                    document.getElementById('requerido-receta').classList.remove('hidden');
                } else {
                    btnReceta.classList.add('hidden');
                    document.getElementById('requerido-receta').classList.add('hidden');
                    document.getElementById('imagen_receta').value = '';
                    document.getElementById('observaciones_receta_value').value = '';
                    document.getElementById('receta-subida-indicator').classList.add('hidden');
                }
            });


            // Guardar receta
            window.guardarReceta = function() {
                const imagen = document.querySelector('#formReceta input[name="imagen"]').value;
                const observaciones = document.getElementById('observaciones_receta').value;
                const esPrescrito = document.getElementById('es_prescrito').checked;

                if (esPrescrito && !imagen) {
                    document.getElementById('error-imagen').classList.remove('hidden');
                    document.getElementById('error-imagen').textContent = 'Para ventas prescritas es obligatorio subir la receta médica';
                    return;
                }

                document.getElementById('imagen_receta').value = imagen;
                document.getElementById('observaciones_receta_value').value = observaciones;

                if (imagen || observaciones) {
                    document.getElementById('receta-subida-indicator').classList.remove('hidden');
                } else {
                    document.getElementById('receta-subida-indicator').classList.add('hidden');
                }

                my_modal_2.close();
                mensaje('La información de la receta se ha guardado correctamente', 'success');
                verificarEstadoFormularioVenta();
            };

            // Abrir modal de receta
            document.getElementById('btn-subir-receta').addEventListener('click', function(e) {
                e.preventDefault();
                const observacionesGuardadas = document.getElementById('observaciones_receta_value').value;
                if (observacionesGuardadas) {
                    document.getElementById('observaciones_receta').value = observacionesGuardadas;
                }
                my_modal_2.showModal();
            });


          // Asegúrate que esta función esté disponible globalmente
// window.generarResumenVenta = function() {
//     let mensaje = `
//     <div class="w-full max-w-[100vw]">
//         <h5 class="text-md font-semibold mb-4 text-center">Resumen de la Venta</h5>
//         <div class="overflow-x-auto">
//             <table class="table table-zebra table-sm md:table-md w-full">
//                 <thead>
//                     <tr class="bg-base-200">
//                         <th class="w-[50%] min-w-[150px]">Producto</th>
//                         <th class="text-center w-[15%]">Cantidad</th>
//                         <th class="text-right w-[20%]">Precio</th>
//                         <th class="text-right w-[15%]">Subtotal</th>
//                     </tr>
//                 </thead>
//                 <tbody>`;

//     $('#tabla-productos tbody tr').each(function() {
//         const producto = $(this).find('td:eq(0)').text().trim();
//         const cantidad = $(this).find('td:eq(1)').text().trim();
//         const precio = $(this).find('td:eq(2)').html().trim();
//         const subtotal = $(this).find('td:eq(3)').text().trim();

//         mensaje += `
//                     <tr>
//                         <td class="break-words max-w-[150px] md:max-w-none" title="${producto}">${producto}</td>
//                         <td class="text-center">${cantidad}</td>
//                         <td class="text-right">${precio}</td>
//                         <td class="text-right">${subtotal}</td>
//                     </tr>`;
//     });

//     mensaje += `
//                 </tbody>
//             </table>
//         </div>
//         <div class="mt-4 grid grid-cols-1 gap-1 text-sm md:text-base">
//             <div class="flex justify-between border-b pb-1">
//                 <span class="font-medium">SUMA:</span>
//                 <span>${$('#suma').text().trim()}</span>
//             </div>
//             <div class="flex justify-between border-b pb-1">
//                 <span class="font-medium">IVA %:</span>
//                 <span>${$('#iva').text().trim()}</span>
//             </div>
//             <div class="flex justify-between font-bold text-lg mt-2">
//                 <span>TOTAL:</span>
//                 <span>${$('#total').text().trim()}</span>
//             </div>
//         </div>
//     </div>`;

//     return mensaje;
// }


// Función para mostrar mensajes
function mensaje(message, icon = "error") {
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

// // Función para redondear números
// function round(num, decimales = 2) {
//     var signo = (num >= 0 ? 1 : -1);
//     num = num * signo;
//     if (decimales === 0) return signo * Math.round(num);
//     num = num.toString().split('e');
//     num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
//     num = num.toString().split('e');
//     return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
// }




});
    </script>


<script>
    document.getElementById('formVenta').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validaciones básicas
    const filasProductos = document.querySelectorAll('#tabla-productos tbody tr');
    if (filasProductos.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debes agregar al menos un producto al detalle de venta',
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
        }).then(() => {
            my_modal_2.showModal();
        });
        return;
    }

    // Mostrar confirmación con resumen
    Swal.fire({
        title: 'Confirmar Venta',
        html: generarResumenVenta(),
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Confirmar Venta',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'w-full max-w-4xl'
        },
        width: '80%'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loader mientras se procesa
            Swal.fire({
                title: 'Procesando venta',
                html: 'Por favor espere...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    // Enviar el formulario después de mostrar el loader
                    this.submit();
                }
            });
        }
    });
});
</script>
@endpush
