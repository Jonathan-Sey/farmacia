@extends('template')
@section('titulo', 'Editar Persona')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    #ficha_medica {
        transition: all 0.3s ease;
    }
    .form-select {
        @apply block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm;
    }
</style>
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('personas.update', $persona->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="border-b border-gray-900/10 pb-12">

                <!-- Datos Básicos -->
                <div class="mt-2 mb-5 flex flex-col gap-5 md:grid md:grid-cols-2">
                    <div>
                        <label for="nombre" class="uppercase block text-sm font-medium text-gray-900">Nombre</label>
                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('nombre', $persona->nombre) }}"
                            required>
                        @error('nombre')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="flex flex-col gap-1">
                        <label for="rol">Tipo de Persona</label>
                        <select name="rol" id="rol" class="form-select">
                            <option value="1" {{ $persona->rol == 1 ? 'selected' : '' }}>Cliente</option>
                            <option value="2" {{ $persona->rol == 2 ? 'selected' : '' }}>Paciente</option>
                        </select>
                    </div>
                </div>

                <!-- Datos Generales -->
                <div class="mt-2 mb-5">
                    <label for="nit" class="uppercase block text-sm font-medium text-gray-900">NIT</label>
                    <input
                        type="text"
                        name="nit"
                        id="nit"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('nit', $persona->nit) }}"
                        {{ $persona->rol == 2 ? 'required' : '' }}>
                    @error('nit')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                    <label for="telefono" class="uppercase block text-sm font-medium text-gray-900">Teléfono</label>
                    <input
                        type="text"
                        name="telefono"
                        id="telefono"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('telefono', $persona->telefono) }}">
                </div>

                <div class="mt-2 mb-5">
                    <label for="fecha_nacimiento" class="uppercase block text-sm font-medium text-gray-900">Fecha Nacimiento</label>
                    <input
                        type="date"
                        name="fecha_nacimiento"
                        id="fecha_nacimiento"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento) }}">
                </div>

                <!-- Ficha Médica (solo para pacientes) -->
                <div id="ficha_medica" style="{{ old('rol', $persona->rol) == 2 ? '' : 'display:none;' }}">
                    <h3 class="text-lg font-semibold text-gray-900">Ficha Médica</h3>

                    @php
                        $fichaMedica = $persona->fichasMedicas->first();
                    @endphp

                    <div class="mt-2 mb-5">
                        <label for="apellido_paterno" class="uppercase block text-sm font-medium text-gray-900">Apellido Paterno</label>
                        <input
                            type="text"
                            name="apellido_paterno"
                            id="apellido_paterno"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('apellido_paterno', $fichaMedica->apellido_paterno ?? '') }}"
                            {{ $persona->rol == 2 ? 'required' : '' }}>
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="apellido_materno" class="uppercase block text-sm font-medium text-gray-900">Apellido Materno</label>
                        <input
                            type="text"
                            name="apellido_materno"
                            id="apellido_materno"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('apellido_materno', $fichaMedica->apellido_materno ?? '') }}"
                            {{ $persona->rol == 2 ? 'required' : '' }}>
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="sexo" class="uppercase block text-sm font-medium text-gray-900">Sexo</label>
                        <select name="sexo" id="sexo" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            {{ $persona->rol == 2 ? 'required' : '' }}>
                            <option value="Hombre" {{ old('sexo', $fichaMedica->sexo ?? '') == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                            <option value="Mujer" {{ old('sexo', $fichaMedica->sexo ?? '') == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                        </select>
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="dpi" class="uppercase block text-sm font-medium text-gray-900">DPI</label>
                        <input
                            type="text"
                            name="dpi"
                            id="dpi"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('dpi', $fichaMedica->dpi ?? '') }}"
                            {{ $persona->rol == 2 ? 'required' : '' }}>
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="habla_lengua" class="uppercase block text-sm font-medium text-gray-900">¿Habla lengua?</label>
                        <select name="habla_lengua" id="habla_lengua" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                            <option value="Sí" {{ old('habla_lengua', $fichaMedica->habla_lengua ?? '') == 'Sí' ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ old('habla_lengua', $fichaMedica->habla_lengua ?? '') == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="tipo_sangre" class="uppercase block text-sm font-medium text-gray-900">Tipo de Sangre</label>
                        <input
                            type="text"
                            name="tipo_sangre"
                            id="tipo_sangre"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('tipo_sangre', $fichaMedica->tipo_sangre ?? '') }}">
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="direccion" class="uppercase block text-sm font-medium text-gray-900">Dirección</label>
                        <input
                            type="text"
                            name="direccion"
                            id="direccion"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('direccion', $fichaMedica->direccion ?? '') }}">
                    </div>

                    @if(isset($medicos))
                    <div class="mt-2 mb-5">
                        <label for="detalle_medico_id" class="uppercase block text-sm font-medium text-gray-900">Médico</label>
                        <select name="detalle_medico_id" id="detalle_medico_id" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            {{ $persona->rol == 2 ? 'required' : '' }}>
                            @foreach($medicos as $medico)
                                <option value="{{ $medico->id }}" {{ old('detalle_medico_id', $fichaMedica->detalle_medico_id ?? '') == $medico->id ? 'selected' : '' }}>
                                    {{ $medico->nombre }} - {{ $medico->especialidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('personas.index') }}" class="text-sm font-semibold text-gray-900">Cancelar</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<<script>
    $(document).ready(function() {
        // Inicializar estado según el rol actual
        function actualizarVista() {
            if ($('#rol').val() == 2) {
                $('#ficha_medica').show();
                $('#ficha_medica input, #ficha_medica select').prop('required', true);
                $('#nit').prop('required', true);
            } else {
                $('#ficha_medica').hide();
                $('#ficha_medica input, #ficha_medica select').prop('required', false);
                $('#nit').prop('required', false);
            }
        }
    
        // Solo reaccionar a cambios manuales
        $('#rol').change(function() {
            actualizarVista();
            
            // Opcional: Mostrar confirmación para cambios de rol
            if ($(this).data('old-value') != $(this).val()) {
                if (!confirm('¿Está seguro de cambiar el tipo de persona?\nLos datos requeridos cambiarán.')) {
                    $(this).val($(this).data('old-value'));
                    actualizarVista();
                    return;
                }
            }
        });
    
        // Guardar valor inicial
        $('#rol').data('old-value', $('#rol').val());
        actualizarVista(); // Aplicar vista inicial
    });
</script>
@endpush