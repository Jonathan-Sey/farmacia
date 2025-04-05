@extends('template')
@section('titulo', 'Crear Persona')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('personas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div id="usuario"></div>
            <div class="border-b border-gray-900/10 pb-12">

                <!-- Datos Personales -->
                <div class="mt-2 mb-5 flex flex-col gap-5 md:grid md:grid-cols-2">
                    <div>
                        <label for="nombre" class="uppercase block text-sm font-medium text-gray-900">Nombre</label>
                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            autocomplete="given-name"
                            placeholder="Nombre"
                            class="block w-full md:w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('nombre') }}">
                        @error('nombre')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message }}</span>
                        </div>
                        @enderror
                    </div>
                    <div class="flex flex-row gap-5">
                        <div class="flex flex-col gap-1">
                            <label for="rol">Paciente</label>
                            <input name="rol" id="rol" type="checkbox" class="toggle toggle-success"
                            {{ old('rol') == 2 ? 'checked' : '' }}
                            value="2" />
                        </div>
                    </div>
                </div>

                <div class="mt-2 mb-5">
                    <label for="nit" class="uppercase block text-sm font-medium text-gray-900">Nit</label>
                    <input
                        type="text"
                        name="nit"
                        id="nit"
                        autocomplete="given-name"
                        placeholder="Nit"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('nit') }}">
                    @error('nit')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                    <label for="telefono" class="uppercase block text-sm font-medium text-gray-900">Telefono</label>
                    <input
                        type="text"
                        name="telefono"
                        id="telefono"
                        autocomplete="given-name"
                        placeholder="Telefono"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('telefono') }}">
                    @error('telefono')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                    <label for="fecha_nacimiento" class="uppercase block text-sm font-medium text-gray-900">Fecha Nacimiento</label>
                    <input
                        type="date"
                        name="fecha_nacimiento"
                        id="fecha_nacimiento"
                        autocomplete="given-name"
                        placeholder="date"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('fecha_nacimiento') }}">
                    @error('fecha_nacimiento')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div id="ficha_medica" class="mt-5">
                    <h3 class="text-lg font-semibold text-gray-900">Ficha Médica</h3>

                    <div class="mt-2 mb-5">
                        <label for="apellido_paterno" class="uppercase block text-sm font-medium text-gray-900">Apellido Paterno</label>
                        <input
                            type="text"
                            name="apellido_paterno"
                            id="apellido_paterno"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('apellido_paterno') }}">
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="apellido_materno" class="uppercase block text-sm font-medium text-gray-900">Apellido Materno</label>
                        <input
                            type="text"
                            name="apellido_materno"
                            id="apellido_materno"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('apellido_materno') }}">
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="sexo" class="uppercase block text-sm font-medium text-gray-900">Sexo</label>
                        <select name="sexo" id="sexo" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                            <option value="Hombre" {{ old('sexo') == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                            <option value="Mujer" {{ old('sexo') == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                        </select>
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="DPI" class="uppercase block text-sm font-medium text-gray-900">DPI</label>
                        <input
                            type="text"
                            name="DPI"
                            id="DPI"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('DPI') }}">
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="habla_lengua" class="uppercase block text-sm font-medium text-gray-900">Habla Lengua</label>
                        <select name="habla_lengua" id="habla_lengua" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                            <option value="Sí" {{ old('habla_lengua') == 'Sí' ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ old('habla_lengua') == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="tipo_sangre" class="uppercase block text-sm font-medium text-gray-900">Tipo de Sangre</label>
                        <select name="tipo_sangre" id="tipo_sangre" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                            <option value="O+" {{ old('tipo_sangre') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('tipo_sangre') == 'O-' ? 'selected' : '' }}>O-</option>
                            <option value="A+" {{ old('tipo_sangre') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('tipo_sangre') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('tipo_sangre') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('tipo_sangre') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('tipo_sangre') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('tipo_sangre') == 'AB-' ? 'selected' : '' }}>AB-</option>
                        </select>
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="direccion" class="uppercase block text-sm font-medium text-gray-900">Dirección</label>
                        <input
                            type="text"
                            name="direccion"
                            id="direccion"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('direccion') }}">
                    </div>
                    <div class="mt-2 mb-5">
                        <label for="detalle_medico_id" class="block text-sm font-medium text-gray-700">Médico:</label>
                                <select name="detalle_medico_id" id="detalle_medico_id" required class="block w-2/5 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                                    <option value="" disabled selected>Selecciona un médico</option>
                                    @foreach ($medicos as $medico)
                                        <option value="{{ $medico->id }}" title="Especialidad: {{ $medico->especialidad }}">
                                            {{ $medico->nombre }} - {{ $medico->especialidad }}
                                        </option>
                                    @endforeach
                                </select>
                            <p id="especialidad" class="mt-2 text-gray-600">Selecciona un médico para ver su especialidad.</p>   
                    </div>
                    <div class="mt-2 mb-5">
                        <label for="diagnostico" class="uppercase block text-sm font-medium text-gray-900">Diagnóstico</label>
                        <textarea
                            name="diagnostico"
                            id="diagnostico"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">{{ old('diagnostico') }}</textarea>
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

                    <div class="mt-2 mb-5">
                        <label for="receta_foto" class="uppercase block text-sm font-medium text-gray-900">Foto de la Receta Médica</label>
                        <input
                            type="file"
                            name="receta_foto"
                            id="receta_foto"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                    </div>
                </div>

            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('personas.index') }}">
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
<script src="/js/obtenerUsuario.js"></script>
<script>
    // Si el checkbox "Paciente" está marcado, mostrar los campos de la ficha médica
    $('#rol').change(function() {
        if ($(this).prop('checked')) {
            $('#ficha_medica').show(); // Mostrar los campos de ficha médica
        } else {
            $('#ficha_medica').hide(); // Ocultar los campos de ficha médica
        }
    });

    // Inicializar el estado de la ficha médica al cargar la página
    if ($('#rol').prop('checked')) {
        $('#ficha_medica').show();
    } else {
        $('#ficha_medica').hide();
    }
</script>
<script>
    // Obtener el select y el párrafo donde se mostrará la especialidad
    const select = document.getElementById('detalle_medico_id');
    const especialidadDisplay = document.getElementById('especialidad');

    // Agregar un evento para cuando cambie la selección
    select.addEventListener('change', function () {
        const selectedOption = select.options[select.selectedIndex];
        const especialidad = selectedOption.getAttribute('title'); // Obtener la especialidad desde el atributo title
        especialidadDisplay.textContent = ` ${especialidad}`;
    });
</script>
@endpush
