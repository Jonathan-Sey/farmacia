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
        document.querySelector('[name="receta_foto"]').value = "";
    });

    // Eliminar archivo temporal al cerrar la página
    window.addEventListener("beforeunload", function() {
        const imagenNombre = document.querySelector('[name="receta_foto"]').value;
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

        <form action="{{ route('fichas.store', $persona->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h3 class="text-xl font-semibold mb-4">Crear Ficha Médica para {{ $persona->nombre }}</h3>

            <div class="mt-2 mb-5">
                <label for="diagnostico" class="uppercase block text-sm font-medium text-gray-900">Diagnóstico</label>
                <textarea
                    name="diagnostico"
                    id="diagnostico"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">{{ old('diagnostico') }}</textarea>
            </div>

              <!-- Sección de Receta Médica -->
              <div class="mt-6 border-t pt-4">
                <h4 class="text-lg font-semibold mb-3">Receta Médica</h4>

                <!-- Selector de productos -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div class="md:col-span-2">
                        <label for="producto_id" class="block text-sm font-medium text-gray-700">Producto/Medicamento</label>
                        <select id="producto_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Seleccione un producto --</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">{{ $producto->nombre }} ({{ $producto->codigo }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input type="number" id="cantidad" min="1" value="1" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-end">
                        <button type="button" id="agregar-producto" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Agregar
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="instrucciones" class="block text-sm font-medium text-gray-700">Instrucciones (opcional)</label>
                    <textarea id="instrucciones" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <!-- Tabla de productos agregados -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Instrucciones</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="productos-container" class="divide-y divide-gray-200">
                            <!-- Aquí se agregarán dinámicamente los productos -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-2 mb-5">
                <label for="detalle_medico_id" class="uppercase block text-sm font-medium text-gray-900">
                    Médico
                </label>
                <select name="detalle_medico_id" id="detalle_medico_id" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" required>
                    <option value="" disabled>-- Seleccione un médico --</option>
                    @foreach($medicos as $detalle)
                    <option value="{{ $detalle->id }}"
                        {{ old('detalle_medico_id')}}>
                        {{ $detalle->usuario->name }} - {{ $detalle->especialidad->nombre ?? ''}}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4 mb-5">
                <x-select2
                    name="sucursal_id"
                    label="Sucursal"
                    :options="$sucursales->pluck('nombre', 'id')"
                    :selected="old('sucursal_id')"
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
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                    value="{{ old('consulta_programada') }}">
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Foto/PDF de la Receta</label>
                <div id="dropzone-receta" class="dropzone border-2 border-dashed rounded w-full h-40">
                    <input type="hidden" name="receta_foto" value="{{ old('receta_foto') }}">
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
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>


        </form>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
 $(document).ready(function() {
        // Manejar agregar productos a la receta
        $('#agregar-producto').click(function() {
            const productoSelect = document.getElementById('producto_id');
            const productoId = productoSelect.value;
            const productoNombre = productoSelect.options[productoSelect.selectedIndex].text;
            const cantidad = $('#cantidad').val();
            const instrucciones = $('#instrucciones').val();

            if (!productoId || !cantidad) {
                Swal.fire('Error', 'Debe seleccionar un producto y especificar la cantidad', 'error');
                return;
            }

            // Verificar si el producto ya fue agregado
            if ($(`#productos-container tr[data-producto-id="${productoId}"]`).length > 0) {
                Swal.fire('Error', 'Este producto ya fue agregado a la receta', 'error');
                return;
            }

            // Agregar fila a la tabla
            const row = `
                <tr data-producto-id="${productoId}">
                    <td class="px-4 py-2">${productoNombre}</td>
                    <td class="px-4 py-2">${cantidad}</td>
                    <td class="px-4 py-2">${instrucciones || 'N/A'}</td>
                    <td class="px-4 py-2">
                        <button type="button" class="eliminar-producto text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                        <input type="hidden" name="productos[${productoId}][id]" value="${productoId}">
                        <input type="hidden" name="productos[${productoId}][cantidad]" value="${cantidad}">
                        <input type="hidden" name="productos[${productoId}][instrucciones]" value="${instrucciones}">
                    </td>
                </tr>
            `;

            $('#productos-container').append(row);

            // Limpiar campos
            $('#producto_id').val('');
            $('#cantidad').val(1);
            $('#instrucciones').val('');
        });

        // Eliminar producto de la receta
        $(document).on('click', '.eliminar-producto', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
</script>
@endpush