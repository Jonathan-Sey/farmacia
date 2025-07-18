@extends('template')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        const imagenOriginal = "{{ $ficha->receta_foto ? basename($ficha->receta_foto) : '' }}";

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
            const nombreArchivo = recetaNombre.includes('/') ? recetaNombre.split('/').pop() : recetaNombre;
            const mockFile = { name: nombreArchivo, size: 12345 };
            dropzone.emit("addedfile", mockFile);
            dropzone.emit("thumbnail", mockFile, "{{ asset('uploads') }}/" + nombreArchivo);
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

            {{-- Sección para productos recetados --}}
            <div class="grid grid-cols-1 gap-2 md:gap-2 md:grid-cols-2 items-center justify-center">
                <div>
                    <x-select2
                        name="id_producto"
                        label="Servicio"
                        :options="$productos->pluck('nombre', 'id')"
                        placeholder="Seleccionar producto o servicio"
                        id="id_producto"
                        class="select2-producto block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm mt-0 mb-0"
                    />
                </div>
                <div>
                    <label for="cantidad" class="uppercase block text-sm font-medium text-gray-900">Cantidad</label>
                    <input type="number" min="1" value="1" name="cantidad" id="cantidad"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                </div>
            </div>

            <div class="mb-4">
                <label for="instrucciones" class="block text-sm font-medium text-gray-700 uppercase">Instrucciones (opcional)</label>
                <textarea id="instrucciones" rows="2" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"></textarea>
            </div>

            <div class="flex flex-col items-end mb-2 mt-2 md:mt-2">
                <button type="button" id="agregar-producto" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">
                    Agregar
                </button>
            </div>

            {{-- Tabla de productos --}}
            <div class="overflow-x-auto">
                <table class="table table-sm table-pin-rows table-pin-cols">
                    <thead>
                        <tr>
                            <th></th>
                            <td>Nombre</td>
                            <td>Cantidad</td>
                            <td>Instrucciones</td>
                            <td>Acciones</td>
                        </tr>
                    </thead>
                    <tbody id="contenido-productos">
                        @foreach($ficha->productosRecetados as $index => $producto)
                        <tr data-producto-id="{{ $producto->id }}">
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->pivot->cantidad }}</td>
                            <td>{{ $producto->pivot->instrucciones ?? 'N/A' }}</td>
                            <td>
                                <button type="button" class="eliminar-producto">
                                    <i class="p-3 cursor-pointer fa-solid fa-trash"></i>
                                </button>
                                <input type="hidden" name="producto[{{ $producto->id }}][id]" value="{{ $producto->id }}">
                                <input type="hidden" name="producto[{{ $producto->id }}][cantidad]" value="{{ $producto->pivot->cantidad }}">
                                <input type="hidden" name="producto[{{ $producto->id }}][instrucciones]" value="{{ $producto->pivot->instrucciones }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
                        {{ $detalle->usuario->name }} - {{ $detalle->especialidad->nombre ?? ''}}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-2 mb-5">
                <x-select2
                    name="sucursal_id"
                    label="Sucursal"
                    :options="$sucursales->pluck('nombre', 'id')"
                    :selected="old('sucursal_id', $ficha->sucursal_id)"
                    placeholder="Seleccionar una Sucursal"
                    class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                />
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

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/js/select2-global.js"></script>

<script>
$(document).ready(function(){
    // Inicializar contador basado en los productos existentes
    let contador = {{ $ficha->productosRecetados->count() }};

    // Agregar producto
    $('#agregar-producto').click(function(){
        const productoSelect = document.getElementById('id_producto');
        const id_producto = $('#id_producto').val();
        const nombre = productoSelect.options[productoSelect.selectedIndex].text;
        const cantidad = $('#cantidad').val();
        const instrucciones = $('#instrucciones').val();

        // Validaciones
        if(!id_producto || !cantidad){
            Swal.fire('Error', 'Debe seleccionar un producto y su cantidad','error');
            return;
        }

        if(parseInt(cantidad) <= 0 || !/^\d+$/.test(cantidad)){
            Swal.fire('Error', 'La cantidad debe ser un entero positivo','error');
            return;
        }

        if($(`#contenido-productos tr[data-producto-id="${id_producto}"]`).length > 0){
            Swal.fire('Error', 'El producto ya fue agregado al detalle','error');
            return;
        }

        // Agregar fila
        contador++;
        const row = `
        <tr data-producto-id="${id_producto}">
            <th>${contador}</th>
            <td>${nombre}</td>
            <td>${cantidad}</td>
            <td>${instrucciones || 'N/A'}</td>
            <td>
                <button type="button" class="eliminar-producto">
                    <i class="p-3 cursor-pointer fa-solid fa-trash"></i>
                </button>
                <input type="hidden" name="producto[${id_producto}][id]" value="${id_producto}">
                <input type="hidden" name="producto[${id_producto}][cantidad]" value="${cantidad}">
                <input type="hidden" name="producto[${id_producto}][instrucciones]" value="${instrucciones}">
            </td>
        </tr>
        `;
        $('#contenido-productos').append(row);
        limpiar();
    });

    // Eliminar producto
    $(document).on('click', '.eliminar-producto', function() {
        Swal.fire({
            title: "¿Eliminar?",
            text: "¿Está seguro de eliminar el producto?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar!"
        }).then((result) => {
            if (result.isConfirmed) {
                $(this).closest('tr').remove();
                // Reordenar los números
                $('#contenido-productos tr').each(function(index) {
                    $(this).find('th').text(index + 1);
                });
                contador = $('#contenido-productos tr').length;
                Swal.fire("Eliminado!", "Producto eliminado", "success");
            }
        });
    });

    function limpiar(){
        $('#id_producto').val(null).trigger('change');
        $('#cantidad').val(1);
        $('#instrucciones').val('');
    }
});
</script>
@endpush