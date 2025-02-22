@extends('template')
@section('titulo', 'Crear Medico')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{route('medicos.store')}}" method="POST">
            @csrf
            <div id="usuario">
            </div> 
            <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-2 mb-5">
                        <label for="id_usuario" class="uppercase block text-sm font-medium text-gray-900">Usuario</label>
                        <select
                            class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            name="id_usuario"
                            id="id_usuario">
                            <option value="">Buscar Usuario</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">
                                    {{ $usuario->name }}</option>
                            @endforeach
                        </select>
                        @error('id_usuario')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>



                <div class="mt-2 mb-5">
                    <label for="especialidad" class="uppercase block text-sm font-medium text-gray-900">Especialidad</label>
                    <input
                        type="text"
                        name="especialidad"
                        id="especialidad"
                        autocomplete="given-name"
                        placeholder="Especialidad"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('especialidad') }}">

                    @error('especialidad')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>


                <div class="mt-2 mb-5">
                    <label for="numero_colegiado" class="uppercase block text-sm font-medium text-gray-900">Numero de colegiado</label>
                    <input
                        type="text"
                        name="numero_colegiado"
                        id="numero_colegiado"
                        autocomplete="given-name"
                        placeholder="Colegiado"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('numero_colegiado') }}">

                    @error('numero_colegiado')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                    <label for="horarios" class="uppercase block text-sm font-medium text-gray-900">Días y Horarios</label>
                    
                    <div id="horarios-container">
                        <!-- Aquí se agregarán los horarios dinámicamente -->
                    </div>
                
                    <button type="button" onclick="agregarHorario()"
                        class="mt-2 block w-full rounded-md bg-indigo-600 px-3 py-1.5 text-base text-white outline outline-1 -outline-offset-1 outline-gray-300 hover:bg-indigo-700 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                        + Agregar Día y Horario
                    </button>
                
                    @error('horarios')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{route('medicos.index')}}">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/js/obtenerUsuario.js"></script>

    <script>
        // limitar la fecha a datos actuales
        document.addEventListener('DOMContentLoaded', function(){
            var DatoActual = new Date().toISOString().split('T')[0];
            document.getElementById('fecha_caducidad').setAttribute('min', DatoActual);

        });
        // fin fecha
        
        //uso del select2
        $(document).ready(function(){
            $('.select2').select2({
                width: '100%',
                placeholder: "Buscar usuario",
                allowClear: true
            });
        });
        // pocicionar el cursor en el input para buscar producto
        $('.select2').on('select2:open', function() {
        document.querySelector('.select2-search__field').focus();
        });
    </script>

<script>
    function agregarHorario() {
        let container = document.getElementById('horarios-container');
        let index = container.children.length;

        let div = document.createElement('div');
        div.classList.add("mt-2", "mb-5", "p-3", "border", "border-gray-300", "rounded-md", "bg-white");

        div.innerHTML = `
            <label for="horarios[${index}][dia]" class="uppercase block text-sm font-medium text-gray-900">Día</label>
            <select name="horarios[${index}][dia]" required
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                <option value="lunes">Lunes</option>
                <option value="martes">Martes</option>
                <option value="miércoles">Miércoles</option>
                <option value="jueves">Jueves</option>
                <option value="viernes">Viernes</option>
                <option value="sábado">Sábado</option>
                <option value="domingo">Domingo</option>
            </select>

            <label for="horarios[${index}][hora_inicio]" class="uppercase block text-sm font-medium text-gray-900 mt-2">Hora Inicio</label>
            <input type="time" name="horarios[${index}][hora_inicio]" required
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">

            <label for="horarios[${index}][hora_fin]" class="uppercase block text-sm font-medium text-gray-900 mt-2">Hora Fin</label>
            <input type="time" name="horarios[${index}][hora_fin]" required
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">

            <button type="button" onclick="eliminarHorario(this)"
                class="mt-2 block w-full rounded-md bg-red-600 px-3 py-1.5 text-base text-white outline outline-1 -outline-offset-1 outline-gray-300 hover:bg-red-700 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-red-600 sm:text-sm">
                ❌ Eliminar
            </button>
        `;

        container.appendChild(div);
    }

    function eliminarHorario(element) {
        element.parentElement.remove();
    }
</script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tipoCheckbox = document.getElementById('tipo');
            const fechaCaducidadContainer = document.getElementById('fecha_caducidad_container');
            const toggleFechaCaducidad = () => {
                if (tipoCheckbox.checked) {
                    fechaCaducidadContainer.style.display = 'none';
                } else {
                    fechaCaducidadContainer.style.display = 'block';
                }
            };

            tipoCheckbox.addEventListener('change', toggleFechaCaducidad);
            // Ejecutar la función al cargar la página para establecer el estado inicial
            toggleFechaCaducidad(); });
    </script>

@endpush

