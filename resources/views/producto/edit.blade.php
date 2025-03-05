@extends('template')
@section('titulo', 'Editar Producto')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
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

    //validamos nuevamente que no se exeda de una imagen
    dropzone.on("addedfile", function(file) {
        if (this.files.length > 1) {
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

    // Limpiar el campo oculto si se elimina la imagen
    dropzone.on("removedfile", function(file) {
        document.querySelector('[name="imagen"]').value = "";
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
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{route('productos.update',['producto'=> $producto->id])}}" method="POST">
            @csrf
            @method('PATCH')
            <div id="usuario">

            </div>
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-2 mb-5 flex flex-col gap-5 md:grid md:grid-cols-2 " >
                    <div class="mt-2 mb-5">
                        <label for="id_categoria" class="uppercase block text-sm font-medium text-gray-900">Categoría</label>
                        <select
                            class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            name="id_categoria"
                            id="id_categoria">
                            <option value="">Seleccionar una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ $categoria->id == $producto->id_categoria ? 'selected' : ''}}>
                                    {{$categoria->nombre}}
                                    </option>
                            @endforeach
                        </select>
                        @error('id_categoria')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="flex flex-row gap-5">
                        <div class="flex flex-col gap-1">
                            <label for="tipo">Servicio</label>
                            <input name="tipo" id="tipo" type="checkbox" class="toggle toggle-success"
                            {{ old('tipo', $producto->tipo) == 2 ? 'checked' : '' }}
                            />
                        </div>
                    </div>
                </div>

                <div class="mt-2 mb-5">
                    <label class="uppercase block text-sm font-medium text-gray-900">Imagen del producto</label>
                    <div id="dropzone" class="dropzone border-2 border-dashed rounded w-full h-60">
                        <input type="hidden" name="imagen" value="{{ old('imagen', $producto->imagen)}}" >

                    </div>
                    @error('imagen')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                    @enderror
                </div>
                {{-- <div class="mt-2 mb-5">
                    <label for="codigo" class="uppercase block text-sm font-medium text-gray-900">Codigo</label>
                    <input
                        type="text"
                        name="codigo"
                        id="codigo"
                        autocomplete="given-name"
                        readonly
                        value="{{$codigoTemporal}}"
                        placeholder="Codigo del producto"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('codigo') }}">

                    @error('codigo')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div> --}}

                <div class="mt-2 mb-5">
                    <label for="nombre" class="uppercase block text-sm font-medium text-gray-900">Nombre</label>
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        autocomplete="given-name"
                        placeholder="Nombre del producto"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('nombre', $producto->nombre) }}">

                    @error('nombre')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
                <div class="mt-2 mb-5">
                    <label for="precio_venta" class="uppercase block text-sm font-medium text-gray-900">Precio</label>
                    <input
                        type="number"
                        name="precio_venta"
                        id="precio_venta"
                        autocomplete="given-name"
                        placeholder="Precio"
                        min="1"
                        step="any"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('precio_venta', $producto->precio_venta) }}">

                    @error('precio_venta')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
                {{-- <div class="mt-2 mb-5" id="fecha_caducidad_container">
                    <label for="fecha_caducidad" class="uppercase block text-sm font-medium text-gray-900">Fecha caducidad</label>
                    <input
                        type="date"
                        name="fecha_caducidad"
                        min=""
                        required
                        id="fecha_caducidad"
                        autocomplete="given-name"
                        placeholder="date"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('fecha_caducidad', $producto->fecha_caducidad) }}">

                    @error('fecha_caducidad')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div> --}}

                <div class="mt-2">
                    <label for="descripcion" class="uppercase block text-sm font-medium text-gray-900">Descripción</label>
                    <textarea name="descripcion"
                    require
                    id="descripcion" rows="3"
                    placeholder="Descripción del producto"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">{{ old('descripcion',$producto->descripcion) }}</textarea>
                    @error('descripcion')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>



            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{route('productos.index')}}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </div>
</div>


@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/js/obtenerUsuario.js"></script>
    <script>
        // limitar la fecha a datos actuales
        // document.addEventListener('DOMContentLoaded', function(){
        //     var DatoActual = new Date().toISOString().split('T')[0];
        //     document.getElementById('fecha_caducidad').setAttribute('min', DatoActual);

        // });
        // fin fecha

        //uso del select2
        $(document).ready(function(){
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

{{-- <script>
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
        toggleFechaCaducidad(); });
</script> --}}


@endpush

