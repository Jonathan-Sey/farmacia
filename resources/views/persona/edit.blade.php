@extends('template')
@section('titulo', 'Editar Persona')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('personas.update', $persona->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Datos Básicos -->
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Datos Básicos</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="nombre" class="text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $persona->nombre) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <div>
                    <label for="rol" class="text-sm font-medium text-gray-700">Tipo de Persona *</label>
                    <select name="rol" id="rol"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="1" {{ $persona->rol == 1 ? 'selected' : '' }}>Cliente</option>
                        <option value="2" {{ $persona->rol == 2 ? 'selected' : '' }}>Paciente</option>
                    </select>
                </div>
            </div>

            <!-- Datos Generales -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="nit" class="text-sm font-medium text-gray-700">NIT</label>
                    <input type="text" name="nit" id="nit" value="{{ old('nit', $persona->nit) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="telefono" class="text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $persona->telefono) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="fecha_nacimiento" class="text-sm font-medium text-gray-700">Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                          value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento ? $persona->fecha_nacimiento->format('Y-m-d') : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>

            <!-- Ficha Médica -->
            @php $fichaMedica = $persona->fichasMedicas->first(); @endphp
            <div id="ficha_medica" class="mt-8 {{ $persona->rol == 2 ? '' : 'hidden' }}">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ficha Médica</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Apellido Paterno *</label>
                        <input type="text" name="apellido_paterno"
                            value="{{ old('apellido_paterno', $fichaMedica->apellido_paterno ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Apellido Materno *</label>
                        <input type="text" name="apellido_materno"
                            value="{{ old('apellido_materno', $fichaMedica->apellido_materno ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Sexo *</label>
                        <select name="sexo"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="Hombre" {{ old('sexo', $fichaMedica->sexo ?? '') == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                            <option value="Mujer" {{ old('sexo', $fichaMedica->sexo ?? '') == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">DPI *</label>
                        <input type="text" name="dpi" value="{{ old('dpi', $fichaMedica->DPI ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">¿Habla lengua?</label>
                        <select name="habla_lengua"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="Sí" {{ old('habla_lengua', $fichaMedica->habla_lengua ?? '') == 'Sí' ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ old('habla_lengua', $fichaMedica->habla_lengua ?? '') == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Tipo de Sangre</label>
                        <input type="text" name="tipo_sangre" value="{{ old('tipo_sangre', $fichaMedica->tipo_sangre ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion', $fichaMedica->direccion ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Departamento</label>
                            <select name="departamento_id" id="departamento_id" required class="select2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Seleccione un departamento</option>
                                @foreach($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}" {{ old('departamento_id', $fichaMedica->departamento_id ?? '') == $departamento->id ? 'selected' : '' }}>
                                        {{ $departamento->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('departamento_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Municipio</label>
                            <select name="municipio_id" id="municipio_id" required class="select2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Seleccione un municipio</option>
                                {{-- Este se rellenará con AJAX --}}
                            </select>
                            @error('municipio_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('personas.index') }}" class="text-sm font-semibold text-gray-900">Cancelar</a>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        // Inicializar select2
        $('#departamento_id, #municipio_id').select2({
            dropdownAutoWidth: true,
            width: '100%',
            placeholder: 'Seleccione una opción',
            allowClear: true
        });

        // Cargar municipios cuando se selecciona un departamento
        $('#departamento_id').on('change', function () {
            const departamentoId = $(this).val();
            $('#municipio_id').html('<option value="">Cargando...</option>');

            if (departamentoId) {
                $.ajax({
                    url: '/api/municipios/' + departamentoId,
                    type: 'GET',
                    success: function (data) {
                        let options = '<option value="">Seleccione un municipio</option>';
                        data.forEach(function (municipio) {
                            options += `<option value="${municipio.id}">${municipio.nombre}</option>`;
                        });
                        $('#municipio_id').html(options).val('').trigger('change');
                    }
                });
            } else {
                $('#municipio_id').html('<option value="">Seleccione un municipio</option>');
            }
        });

        // Si ya hay un departamento cargado (por editar), se disparara el evento para cargar municipios
        const selectedDepartamento = $('#departamento_id').val();
        const selectedMunicipio = '{{ old('municipio_id', $fichaMedica->municipio_id ?? '') }}';

        if (selectedDepartamento) {
            $.ajax({
                url: '/api/municipios/' + selectedDepartamento,
                type: 'GET',
                success: function (data) {
                    let options = '<option value="">Seleccione un municipio</option>';
                    data.forEach(function (municipio) {
                        const selected = municipio.id == selectedMunicipio ? 'selected' : '';
                        options += `<option value="${municipio.id}" ${selected}>${municipio.nombre}</option>`;
                    });
                    $('#municipio_id').html(options).trigger('change');
                }
            });
        }
    });
</script>
<script>
    $(document).ready(function () {
        function toggleFichaMedica() {
            const isPaciente = $('#rol').val() == '2';
            if (isPaciente) {
                $('#ficha_medica').show();
                $('#ficha_medica input, #ficha_medica select').prop('disabled', false);
            } else {
                $('#ficha_medica').hide();
                $('#ficha_medica input, #ficha_medica select').prop('disabled', true);
            }
        }

        $('#rol').change(function () {
            const oldValue = $(this).data('old-value');
            const newValue = $(this).val();

            if (oldValue != newValue) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Cambiar el tipo de persona modificará los campos requeridos.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, cambiar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        $(this).val(oldValue);
                    }
                    toggleFichaMedica();
                });
            } else {
                toggleFichaMedica();
            }
        });

        $('#rol').data('old-value', $('#rol').val());
        toggleFichaMedica();
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if ($errors->has('dpi'))
<script>
  Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '{{ $errors->first('dpi') }}',
  });
</script>
@endif
@endpush
