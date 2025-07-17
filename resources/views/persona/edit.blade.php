@extends('template')
@section('titulo', 'Actualizar Persona')
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
        <form action="{{ route('personas.update', $persona->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div id="usuario"></div>
            <div class="border-b border-gray-900/10 pb-12">

                <!-- Selector de Rol -->
                <div class="mb-6">
                    <label for="rol" class="uppercase block text-sm font-medium text-gray-900 mb-1">Tipo de Persona</label>
                    <select name="rol" id="rol" required class="compact-select block rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                        <option value="1" {{ $persona->rol == 1 ? 'selected' : '' }}>Cliente</option>
                        <option value="2" {{ $persona->rol == 2 ? 'selected' : '' }}>Paciente</option>
                        <option value="3" {{ $persona->rol == 3 ? 'selected' : '' }}>Menor de edad</option>
                    </select>
                </div>

               
                {{-- datos del menor de edad  --}}
                @php $fichaMedica = $persona->fichasMedicas->first(); @endphp
                <div   div class="mt-2 mb-5 grid grid-cols-1 gap-5 ">
                    <div id="datos-menor" class="hidden" >
                        <fieldset class="border-2 border-gray-200 p-2 rounded-md">
                            <legend class="text-blue-500 font-bold">Datos del niño </legend>

                            <div class="mb-5">
                                <label for="nombreMenor" class="uppercase block text-sm font-medium text-gray-900">Nombre</label>
                                <input
                                    type="text"
                                    name="nombreMenor"
                                    id="nombreMenor"
                                    autocomplete="given-name"
                                    placeholder="Nombre del menor"
                                    class=" block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                    value="{{ old('nombreMenor', $fichaMedica->nombreMenor ?? '') }}">
                                @error('nombreMenor')
                                <div role="alert" class="alert alert-error mt-4 p-2">
                                    <span class="text-white font-bold">{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                            
                                <div>
                                    <label for="apellido_paterno_menor" class="uppercase block text-sm font-medium text-gray-900">Apellido Paterno</label>
                                    <input
                                        type="text"
                                        name="apellido_paterno_menor"
                                        id="apellido_paterno_menor"
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                        value="{{ old('apellido_paterno_menor', $fichaMedica->apellido_paterno_menor ?? '' ) }}">
                                </div>

                                <div>
                                    <label for="apellido_materno_menor" class="uppercase block text-sm font-medium text-gray-900">Apellido Materno</label>
                                    <input
                                        type="text"
                                        name="apellido_materno_menor"
                                        id="apellido_materno_menor"
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                        value="{{ old('apellido_materno_menor', $fichaMedica->apellido_materno_menor ?? '') }}">
                                </div>
                            </div>                            
                        </fieldset>                        
                    </div>

                 <!-- Datos Básicos -->    
                    <!-- Nombre -->
                
                    <div>
                        <h3 id="TituloEncargado" class="text-lg font-semibold text-gray-900 mb-5 border-b pb-2">Datos de la persona encargada</h3>
                        <label for="nombre" class="uppercase block text-sm font-medium text-gray-900">Nombre</label>
                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            required
                            autocomplete="given-name"
                            placeholder="Nombre completo"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            value="{{ old('nombre', $persona->nombre) }}">
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
                                value="{{ old('apellido_paterno', $fichaMedica->apellido_paterno ?? '') }}">
                        </div>

                        <div>
                            <label for="apellido_materno" class="uppercase block text-sm font-medium text-gray-900">Apellido Materno</label>
                            <input
                                type="text"
                                name="apellido_materno"
                                id="apellido_materno"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                value="{{ old('apellido_materno', $fichaMedica->apellido_materno ?? '') }}">
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
                                value="{{ old('nit',$persona->nit) }}">
                            @error('nit')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">DPI *</label>
                                <input type="text" name="dpi" value="{{ old('dpi', $fichaMedica->DPI ?? $persona->DPI ?? '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('dpi')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
                                value="{{ old('telefono',$persona->telefono) }}">
                            @error('telefono')
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
                    
                <!-- Fecha de Nacimiento -->
                    <div class="grid grid-cols-1 w-full mb-5">
                        <div>
                            <label for="fecha_nacimiento" class="uppercase block text-sm font-medium text-gray-900">Fecha Nacimiento</label>
                            <input
                                type="date"
                                name="fecha_nacimiento"
                                id="fecha_nacimiento"
                                required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                                value="{{ old('fecha_nacimiento', $fichaMedica->fecha_nacimiento ?? '') }}">
                            @error('fecha_nacimiento')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                        <div>
                            <label for="sexo" class="uppercase block text-sm font-medium text-gray-900">Sexo</label>
                            <select name="sexo" id="sexo" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                                <option value="Hombre" {{ old('sexo',$fichaMedica->sexo ?? '' ) == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                                <option value="Mujer" {{ old('sexo', $fichaMedica->sexto ?? '') == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                            </select>
                        </div>

                        <div>
                            <label for="tipo_sangre" class="uppercase block text-sm font-medium text-gray-900">Tipo de Sangre</label>
                            <select name="tipo_sangre" id="tipo_sangre" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                                <option value="" disabled selected>Selecciona un tipo</option>
                                <option value="O+" {{ old('tipo_sangre', $fichaMedica->tipo_sangre ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('tipo_sangre', $fichaMedica->tipo_sangre ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                                <option value="A+" {{ old('tipo_sangre', $fichaMedica->tipo_sangre ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('tipo_sangre', $fichaMedica->tipo_sangre ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('tipo_sangre', $fichaMedica->tipo_sangre ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('tipo_sangre', $fichaMedica->tipo_sangre ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('tipo_sangre', $fichaMedica->tipo_sangre ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('tipo_sangre', $fichaMedica->tipo_sangre ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            </select>
                        </div>

                        <div>
                            <label for="habla_lengua" class="uppercase block text-sm font-medium text-gray-900">Habla Lengua</label>
                            <select name="habla_lengua" id="habla_lengua" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                                <option value="1" {{ old('habla_lengua', $fichaMedica->habla_lengua ?? '') == 1 ? 'selected' : '' }}>Español</option>
                                <option value="2" {{ old('habla_lengua', $fichaMedica->habla_lengua ?? '') == 2 ? 'selected' : '' }}>Maya</option>
                                <option value="3" {{ old('habla_lengua', $fichaMedica->habla_lengua ?? '') == 3 ? 'selected' : '' }}>Extranjero</option>
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
                                value="{{ old('direccion', $fichaMedica->direccion ?? '') }}">
                        </div>
                    </div>

                    <div>
                            <label for="antigueno" class="uppercase block text-sm font-medium text-gray-900">Es antigueño?</label>
                            <select name="antigueno" id="antigueno" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                                <option value="1" {{ old('antigueno', $fichaMedica->antigueno ?? '') == 1 ? 'selected' : '' }}>Sí</option>
                                <option value="2" {{ old('antigueno', $fichaMedica->antigueno ?? '') == 2 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                    {{-- Select de los departamentos y municipios. --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                        <div>
                            <label for="departamento_id" class="uppercase block text-sm font-medium text-gray-900">Departamento</label>
                            <select name="departamento_id" id="departamento_id"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                                <option value="">Seleccione un departamento</option>
                                @foreach($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}" {{ old('departamento_id', $fichaMedica->departamento_id ?? '') == $departamento->id ? 'selected' : '' }}>
                                        {{ $departamento->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="municipio_id" class="uppercase block text-sm font-medium text-gray-900">Municipio</label>
                            <select name="municipio_id" id="municipio_id"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                                <option value="">Seleccione un municipio</option>
                                    {{-- se obtiene con ajax --}}
                            </select>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/js/obtenerUsuario.js"></script>
<script>
    $(document).ready(function() {
         const valor = document.getElementById('rol');
        const rol = valor.options[valor.selectedIndex].value;
        console.log(rol);
        
        //  tomamos el valor de toggle 
        const infoMenor = document.getElementById('datos-menor')
        const seccionEncargado = document.getElementById('TituloEncargado')
//        console.log($persona->rol);
        // Manejar cambio de rol
        $('#rol').change(function() {
            if ($(this).val() == 2) {
                //ocultamos la info del menor de edad 
                infoMenor.classList.add('hidden');
                seccionEncargado.classList.add('hidden')

                // Mostrar apellidos y ficha médica
                $('#apellidos-section, #ficha_medica').removeClass('hidden');
                // Hacer requeridos los campos adicionales
                $('#apellido_paterno, #apellido_materno').prop('required', true);
                $('#ficha_medica input, #ficha_medica select').prop('required', true);
                $('#nombreMenor, #apellido_paterno_menor, #apellido_materno_menor').prop('required', false);


            } else if($(this).val() == 3){
                infoMenor.classList.remove('hidden');
                seccionEncargado.classList.remove('hidden');
                // datos para el menor de edad
                $('#nombreMenor, #apellido_paterno_menor, #apellido_materno_menor').prop('required', true);
                $('#apellidos-section, #ficha_medica').removeClass('hidden');
            }
            
            else {
                //ocultamos la info del menor de edad 
                infoMenor.classList.add('hidden');
                seccionEncargado.classList.add('hidden');
                // Ocultar secciones adicionales
                $('#apellidos-section, #ficha_medica').addClass('hidden');
                // Quitar requeridos
                $('#apellido_paterno, #apellido_materno').prop('required', false);
                $('#ficha_medica input, #ficha_medica select').prop('required', false);
                // quitar requridos para los datos del menor de edad 
                $('#nombreMenor, #apellido_paterno_menor, #apellido_materno_menor').prop('required', false);

            }
        }).trigger('change');

        // Asegurar envío del formulario
        $('form').submit(function(e) {
            if ($('#rol').val() == 1) {
                // Deshabilitar todo excepto DPI
                $('#datos-menor, #apellidos-section, #ficha_medica').find('input, select').not('[name="dpi"]').prop('disabled', true);
            }
        });
        

    });
    
</script> 

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
             
            if ( rol == 2) {
                //ocultamos la info del menor de edad 
                infoMenor.classList.add('hidden');
                seccionEncargado.classList.add('hidden')

                // Mostrar apellidos y ficha médica
                $('#apellidos-section, #ficha_medica').removeClass('hidden');
                // Hacer requeridos los campos adicionales
                $('#apellido_paterno, #apellido_materno').prop('required', true);
                $('#ficha_medica input, #ficha_medica select').prop('required', true);
                $('#nombreMenor, #apellido_paterno_menor, #apellido_materno_menor').prop('required', false);
            } else if(rol == 3){
                infoMenor.classList.remove('hidden');
                seccionEncargado.classList.remove('hidden');
                // datos para el menor de edad
                $('#nombreMenor, #apellido_paterno_menor, #apellido_materno_menor').prop('required', true);
                $('#apellidos-section, #ficha_medica').removeClass('hidden');
            }
            
            else {
                //ocultamos la info del menor de edad 
                infoMenor.classList.add('hidden');
                seccionEncargado.classList.add('hidden');
                // Ocultar secciones adicionales
                $('#apellidos-section, #ficha_medica').addClass('hidden');
                // Quitar requeridos
                $('#apellido_paterno, #apellido_materno').prop('required', false);
                $('#ficha_medica input, #ficha_medica select').prop('required', false);
                // quitar requridos para los datos del menor de edad 
                $('#nombreMenor, #apellido_paterno_menor, #apellido_materno_menor').prop('required', false);

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

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementId('tipo');
        $('#rol').change(function() {
            if ($(this).val() == 2) {
                console.log("la opcion selecionado es la numero 2")
            }
        });
        
    });
</script> --}}
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