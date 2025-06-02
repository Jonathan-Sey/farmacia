@extends('template')
@section('titulo', 'Editar Farmacia')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
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
    const imagenOriginal = "{{ $sucursal->imagen }}";

    if (imagenNombre === imagenOriginal) {
        // En lugar de dejar el campo vacío, puedes asignar un valor por defecto
        document.querySelector('[name="imagen"]').value = 'default.jpg';

        // O si prefieres usar el campo eliminar_imagen
        if (!document.querySelector('[name="eliminar_imagen"]')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'eliminar_imagen';
            input.value = '1';
            document.querySelector('form').appendChild(input);
        }
    }
    else if (imagenNombre && imagenNombre !== imagenOriginal) {
        fetch("{{ route('eliminar.imagen.temp') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ imagen: imagenNombre }),
        });
    }

    // Solo limpia el campo si estás usando imagen por defecto
    // document.querySelector('[name="imagen"]').value = "";
});

    // Precargar la imagen existente
    document.addEventListener("DOMContentLoaded", function() {
        const imagenNombre = document.querySelector('[name="imagen"]').value;
        if (imagenNombre) {
            const mockFile = { name: imagenNombre, size: 12345 };
            dropzone.emit("addedfile", mockFile);
            dropzone.emit("thumbnail", mockFile, "{{ asset('uploads') }}/" + imagenNombre);
            dropzone.emit("complete", mockFile);
        }
    });

    // Eliminar imagen temporal al cerrar la página si no se guardó
    window.addEventListener("beforeunload", function() {
        const imagenNombre = document.querySelector('[name="imagen"]').value;
        const imagenOriginal = "{{ $sucursal->imagen }}";
        const formSubmitted = document.querySelector('form').submitted;

        if (imagenNombre && !formSubmitted && imagenNombre !== imagenOriginal) {
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
</script>
@endpush
@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{route('sucursales.update',['sucursal'=>$sucursal->id])}}" method="POST">
            @csrf
            @method('PATCH')
            <div id="usuario">

            </div>
            <div class="border-b border-gray-900/10 pb-12">

                <div class="mt-2 mb-5">
                    <label class="uppercase block text-sm font-medium text-gray-900">Imagen del producto</label>
                    <div id="dropzone" class="dropzone border-2 border-dashed rounded w-full h-60">
                        <input type="hidden" name="imagen" value="{{ old('imagen', $sucursal->imagen)}}" >

                    </div>
                    @error('imagen')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                    <label for="nombre" class="uppercase block text-sm font-medium text-gray-900">Nombre Sucursal</label>
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        autocomplete="given-name"
                        placeholder="Nombre sucursal"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('nombre',$sucursal->nombre) }}">

                    @error('nombre')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                {{-- Input para agregar el código de sucursal --}}
                <div class="mt-2 mb-5">
                    <label for="codigo_sucursal" class="uppercase block text-sm font-medium text-gray-900">Codigo Sucursal</label>
                    <input
                        type="text"
                        name="codigo_sucursal"
                        id="codigo_sucursal"
                        autocomplete="given-name"
                        placeholder="Codigo Sucursal"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('codigo_sucursal', $sucursal->codigo_sucursal) }}">

                        @error('nombre')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message }}</span>
                        </div>
                        @enderror
                {{-- Select para elegir al encargado --}}
                <div class="mt-2 mb-5">
                    <label for="encargado" class="uppercase block text-sm font-medium text-gray-900">Nombre Encargado</label>
                    <select
                        name="encargado"
                        id="encargado"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                        <option value="">Selecciona un encargado</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->name }}"
                                {{ old('encargado', $sucursal->encargado) == $usuario->name ? 'selected' : '' }}>
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('encargado')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>





                    {{-- Input para agregar la ubicacion --}}

                <div class="mt-2 mb-5">
                    <label for="ubicacion" class="uppercase block text-sm font-medium text-gray-900">Ubicación</label>
                    <input
                        type="text"
                        name="ubicacion"
                        id="ubicacion"
                        autocomplete="given-name"
                        placeholder="Ubicación"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('ubicacion',$sucursal->ubicacion) }}">

                    @error('ubicacion')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                    <label for="telefono" class="uppercase block text-sm font-medium text-gray-900">Numero de telefono</label>
                    <input
                        type="text"
                        name="telefono"
                        id="telefono"
                        autocomplete="given-name"
                        placeholder="Numero de telefono"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('telefono',$sucursal->telefono) }}">

                    @error('telefono')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                    <label for="email" class="uppercase block text-sm font-medium text-gray-900">Correo electronico</label>
                    <input
                        type="text"
                        name="email"
                        id="email"
                        autocomplete="given-name"
                        placeholder="Correo electronico"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('email',$sucursal->email) }}">

                    @error('email')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
            </div>


            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{route('sucursales.index')}}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </div>
</div>


@endsection
@push('js')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const selectUsuarios = document.getElementById("id_usuario");
    const selectedUsersList = document.getElementById("selected-users-list");

    selectUsuarios.addEventListener("mousedown", function (event) {
        event.preventDefault(); // Evita la selección automática por el navegador

        let clickedOption = event.target;

        // Si el elemento clickeado es una opción dentro del select
        if (clickedOption.tagName === "OPTION") {
            clickedOption.selected = !clickedOption.selected; // Alterna selección
        }

        updateSelectedUsers(); // Actualizar la lista mostrada
    });

    function updateSelectedUsers() {
        selectedUsersList.innerHTML = ""; // Limpiar antes de actualizar
        const selectedOptions = Array.from(selectUsuarios.selectedOptions);

        selectedOptions.forEach(option => {
            let li = document.createElement("li");
            li.textContent = option.textContent;
            selectedUsersList.appendChild(li);
        });
    }

    // Inicializar lista si ya hay seleccionados por old()
    updateSelectedUsers();
});
</script>

@endpush
