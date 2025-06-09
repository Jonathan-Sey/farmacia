@extends('template')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endpush

@push('js')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    Dropzone.autoDiscover = false;
    const dropzone = new Dropzone("#dropzone-receta", {
        url: "{{ route('upload.image.temp') }}",
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

    dropzone.on("addedfile", function(file) {
        if (this.files.length > 1) {
            this.removeFile(this.files[0]);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Solo se permite subir un archivo.',
                confirmButtonText: 'Aceptar',
            });
        }
    });

    dropzone.on("success", function(file, response) {
        console.log("Archivo subido temporalmente:", response.imagen);
        document.querySelector('[name="receta_foto"]').value = response.imagen;
    });

    dropzone.on("removedfile", function(file) {
        const imagenNombre = document.querySelector('[name="receta_foto"]').value;
        const imagenOriginal = "{{ $ficha->receta_foto }}";

        if (imagenNombre === imagenOriginal) {
            if (!document.querySelector('[name="eliminar_receta"]')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'eliminar_receta';
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
        document.querySelector('[name="receta_foto"]').value = "";
    });

    // Precargar la receta existente
    document.addEventListener("DOMContentLoaded", function() {
        const recetaNombre = document.querySelector('[name="receta_foto"]').value;
        if (recetaNombre) {
            const mockFile = { name: recetaNombre, size: 12345 };
            dropzone.emit("addedfile", mockFile);
            dropzone.emit("thumbnail", mockFile, "{{ asset('storage/recetas') }}/" + recetaNombre);
            dropzone.emit("complete", mockFile);
        }
    });

    // Eliminar archivo temporal al cerrar la página si no se guardó
    window.addEventListener("beforeunload", function() {
        const imagenNombre = document.querySelector('[name="receta_foto"]').value;
        const imagenOriginal = "{{ $ficha->receta_foto }}";
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
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('fichas.update', ['persona_id' => $persona->id, 'ficha' => $ficha->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h3 class="text-xl font-semibold mb-4">Editar Ficha Médica para {{ $persona->nombre }}</h3>

            <div class="mt-2 mb-5">
                <label for="diagnostico" class="uppercase block text-sm font-medium text-gray-900">Diagnóstico</label>
                <textarea
                    name="diagnostico"
                    id="diagnostico"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">{{ old('diagnostico', $ficha->diagnostico) }}</textarea>
            </div>

            <div class="mt-2 mb-5">
                <label for="detalle_medico_id" class="uppercase block text-sm font-medium text-gray-900">
                    Médico
                </label>
                <select name="detalle_medico_id" id="detalle_medico_id" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" required>
                    <option value="" disabled>-- Seleccione un médico --</option>
                    @foreach($medicos as $detalle)
                      <option value="{{ $detalle->id }}"
                          {{ old('detalle_medico_id', $ficha->detalle_medico_id) == $detalle->id ? 'selected' : '' }}>
                        {{ $detalle->usuario->name }}
                        @if($detalle->especialidad) – {{ $detalle->especialidad }} @endif
                      </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-2 mb-5">
                <label for="consulta_programada" class="uppercase block text-sm font-medium text-gray-900">Consulta Programada</label>
                <input
                    type="date"
                    name="consulta_programada"
                    id="consulta_programada"
                    value="{{ old('consulta_programada', $ficha->consulta_programada) }}"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Foto/PDF de la Receta</label>
                <div id="dropzone-receta" class="dropzone border-2 border-dashed rounded w-full h-40">
                    <input type="hidden" name="receta_foto" value="{{ old('receta_foto', $ficha->receta_foto) }}">
                </div>
                @error('receta_foto')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('personas.show', $persona->id) }}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
