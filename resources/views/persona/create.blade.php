@extends('template')
@section('titulo', 'Crear Persona')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .compact-select {
        max-width: 200px;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('personas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div id="usuario"></div>
            <div class="border-b border-gray-900/10 pb-12">

                <!-- Selector de Rol -->
                <div class="mb-6">
                    <label for="rol" class="uppercase block text-sm font-medium text-gray-900 mb-1">Tipo de Persona</label>
                    <select name="rol" id="rol" required class="compact-select block rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                        <option value="1" {{ old('rol') == 1 ? 'selected' : '' }}>Cliente</option>
                        <option value="2" {{ old('rol') == 2 ? 'selected' : '' }}>Paciente</option>
                    </select>
                </div>

                <!-- Datos Básicos -->
                <div class="mt-2 mb-5 grid grid-cols-1 gap-5">
                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="uppercase block text-sm font-medium text-gray-900">Nombre</label>
                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            required
                            autocomplete="given-name"
                            placeholder="Nombre completo"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('nombre') }}">
                        @error('nombre')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- Apellidos (solo para pacientes) -->
                    <div id="apellidos-section" class="hidden grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="apellido_paterno" class="uppercase block text-sm font-medium text-gray-900">Apellido Paterno</label>
                            <input
                                type="text"
                                name="apellido_paterno"
                                id="apellido_paterno"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                value="{{ old('apellido_paterno') }}">
                        </div>

                        <div>
                            <label for="apellido_materno" class="uppercase block text-sm font-medium text-gray-900">Apellido Materno</label>
                            <input
                                type="text"
                                name="apellido_materno"
                                id="apellido_materno"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                value="{{ old('apellido_materno') }}">
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                        <div>
                            <label for="nit" class="uppercase block text-sm font-medium text-gray-900">NIT</label>
                            <input
                                type="text"
                                name="nit"
                                id="nit"
                                required
                                placeholder="NIT"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                value="{{ old('nit') }}">
                            @error('nit')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>

                        <div>
                            <label for="DPI" class="uppercase block text-sm font-medium text-gray-900">DPI</label>
                            <input
                                type="text"
                                name="DPI"
                                id="DPI"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                value="{{ old('DPI') }}">
                        </div>

                        <div>
                            <label for="telefono" class="uppercase block text-sm font-medium text-gray-900">Teléfono</label>
                            <input
                                type="text"
                                name="telefono"
                                id="telefono"
                                required
                                placeholder="Teléfono"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                value="{{ old('telefono') }}">
                            @error('telefono')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div class="grid grid-cols-1">
                        <div>
                            <label for="fecha_nacimiento" class="uppercase block text-sm font-medium text-gray-900">Fecha Nacimiento</label>
                            <input
                                type="date"
                                name="fecha_nacimiento"
                                id="fecha_nacimiento"
                                required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                value="{{ old('fecha_nacimiento') }}">
                            @error('fecha_nacimiento')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sección específica para Pacientes -->
                <div id="ficha_medica" class="hidden mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-5 border-b pb-2">Información Médica</h3>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                        <div>
                            <label for="sexo" class="uppercase block text-sm font-medium text-gray-900">Sexo</label>
                            <select name="sexo" id="sexo" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                                <option value="Hombre" {{ old('sexo') == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                                <option value="Mujer" {{ old('sexo') == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                            </select>
                        </div>

                        <div>
                            <label for="tipo_sangre" class="uppercase block text-sm font-medium text-gray-900">Tipo de Sangre</label>
                            <select name="tipo_sangre" id="tipo_sangre" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                                <option value="" disabled selected>Selecciona un tipo</option>
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

                        <div>
                            <label for="habla_lengua" class="uppercase block text-sm font-medium text-gray-900">Habla Lengua</label>
                            <select name="habla_lengua" id="habla_lengua" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                                <option value="Sí" {{ old('habla_lengua') == 'Sí' ? 'selected' : '' }}>Sí</option>
                                <option value="No" {{ old('habla_lengua') == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1">
                        <div>
                            <label for="direccion" class="uppercase block text-sm font-medium text-gray-900">Dirección</label>
                            <input
                                type="text"
                                name="direccion"
                                id="direccion"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                value="{{ old('direccion') }}">
                        </div>
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
    $(document).ready(function() {
        // Manejar cambio de rol
        $('#rol').change(function() {
            if ($(this).val() == 2) {
                // Mostrar apellidos y ficha médica
                $('#apellidos-section, #ficha_medica').removeClass('hidden');
                // Hacer requeridos los campos adicionales
                $('#apellido_paterno, #apellido_materno').prop('required', true);
                $('#ficha_medica input, #ficha_medica select').prop('required', true);
            } else {
                // Ocultar secciones adicionales
                $('#apellidos-section, #ficha_medica').addClass('hidden');
                // Quitar requeridos
                $('#apellido_paterno, #apellido_materno').prop('required', false);
                $('#ficha_medica input, #ficha_medica select').prop('required', false);
            }
        }).trigger('change');
    
        // Asegurar envío del formulario
        $('form').submit(function(e) {
            if ($('#rol').val() == 1) {
                $('#apellidos-section, #ficha_medica').find('input, select').prop('disabled', true);
            }
        });
    });
</script>
@endpush