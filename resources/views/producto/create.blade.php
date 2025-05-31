@extends('template')
@section('titulo', 'Crear Producto')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

@endpush
@push("js")
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
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{route('productos.store')}}" method="POST">
            @csrf
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
                                <option value="{{ $categoria->id }}">
                                    {{ $categoria->nombre }}</option>
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
                            {{ old('tipo') ? 'checked' : '' }}
                            />
                        </div>
                    </div>

                </div>
                <div class="mt-2 mb-5">
                    <label class="uppercase block text-sm font-medium text-gray-900">Imagen del producto</label>
                    <div id="dropzone" class="dropzone border-2 border-dashed rounded w-full h-60">
                        <input type="hidden" name="imagen" value="{{ old('imagen')}}" >

                    </div>
                    @error('imagen')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                        @enderror
                </div>


                <div class="mt-2 mb-5">
                    <label for="nombre" class="uppercase block text-sm font-medium text-gray-900">Nombre</label>
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        autocomplete="given-name"
                        placeholder="Nombre del producto"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('nombre') }}">

                    @error('nombre')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                        <div >
                            <label for="precio_venta" class="uppercase block text-sm font-medium text-gray-900">Precio venta</label>
                            <input
                                type="text"
                                name="precio_venta"
                                id="precio_venta"
                                placeholder="Precio venta"
                                min="1"
                                step="any"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                value="{{ old('precio_venta') }}">

                            @error('precio_venta')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>
                </div>



                <div class="mt-2 mb-5">
                    <label for="precio_porcentaje" class="uppercase block text-sm font-medium text-gray-900">Margen de Ganancia (%)</label>
                    <input
                        type="number"
                        name="precio_porcentaje"
                        id="precio_porcentaje"
                        placeholder="Porcentaje de ganancia"
                        min="0"
                        step="0.01"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('precio_porcentaje') }}">

                    @error('precio_porcentaje')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

            {{--<div class="mt-2">
                    <label for="descripcion" class="uppercase block text-sm font-medium text-gray-900">Descripción</label>
                    <textarea name="descripcion"
                    require
                    id="descripcion" rows="3"
                    placeholder="Descripción del producto"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
                <div class="mt-2 mb-5">
                    <label for="fecha_caducidad" class="uppercase block text-sm font-medium text-gray-900">Fecha de Vencimiento</label>
                    <input
                        type="date"
                        name="fecha_caducidad"
                        id="fecha_caducidad"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('fecha_caducidad') }}">
                </div> --}}
                <div class="mt-2">
                    <label for="descripcion" class="uppercase block text-sm font-medium text-gray-900">Descripción</label>
                    <textarea name="descripcion"
                    id="descripcion" rows="3"
                    placeholder="Descripción del producto"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"></textarea>
                    @error('descripcion')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
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

<script src="/js/obtenerUsuario.js"></script>

@endsection
