@extends('template')
@section('titulo', 'Crear Médico')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('medicos.store') }}" method="POST">
            @csrf

            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-2 mb-5">
                    {{-- <label for="id_usuario" class="uppercase block text-sm font-medium text-gray-900">Usuario</label>
                    <div class="border p-3 rounded-md bg-gray-100">
                        <select class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600"
                            name="id_usuario" id="id_usuario">
                            <option value="">Buscar Usuario</option>
                            @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <x-select2
                        id="id_usuario"
                        name="id_usuario"
                        label="Usuario"
                        :options="$usuarios->pluck('name', 'id')"
                        :selected="old('id_usuario')"
                        placeholder="Buscar Usuario"
                        required
                    />
                </div>

                <div class="mt-2 mb-5">
                    <label for="especialidad" class="uppercase block text-sm font-medium text-gray-900">Especialidad</label>
                    <div class="border p-3 rounded-md bg-gray-100">
                        <label for="especialidad" class="block text-sm font-medium text-gray-700">Especialidad</label>
                        <select name="especialidad" id="especialidad" class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600" required>
                            <option value="" disabled selected>Selecciona una especialidad</option>
                            <option value="Cardiología" {{ old('especialidad') == 'Cardiología' ? 'selected' : '' }}>Cardiología</option>
                            <option value="Pediatría" {{ old('especialidad') == 'Pediatría' ? 'selected' : '' }}>Pediatría</option>
                            <option value="Dermatología" {{ old('especialidad') == 'Dermatología' ? 'selected' : '' }}>Dermatología</option>
                            <option value="Neurología" {{ old('especialidad') == 'Neurología' ? 'selected' : '' }}>Neurología</option>
                            <option value="Ginecología" {{ old('especialidad') == 'Ginecología' ? 'selected' : '' }}>Ginecología</option>
                            <option value="Oftalmología" {{ old('especialidad') == 'Oftalmología' ? 'selected' : '' }}>Oftalmología</option>
                            <!-- Agrega más opciones según tus necesidades -->
                        </select>
                    </div>
                </div>

                <div class="mt-2 mb-5">
                    <label for="numero_colegiado" class="uppercase block text-sm font-medium text-gray-900">Número de Colegiado</label>
                    <div class="border p-3 rounded-md bg-gray-100">
                        <input type="text" name="numero_colegiado" id="numero_colegiado" placeholder="Colegiado"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600"
                            value="{{ old('numero_colegiado') }}">
                    </div>
                </div>
                @error('numero_colegiado')
                <div role="alert" class="alert alert-error mt-4 p-2">
                    <span class="text-white font-bold">{{ $message }}</span>
                </div>
                @enderror

                <div class="mt-2 mb-5">
                    <label for="horarios" class="uppercase block text-sm font-medium text-gray-900">Sucursales y Horarios</label>
                    <div id="horarios-container"></div>
                    <button type="button" onclick="agregarHorario()"
                        class="mt-2 block w-full rounded-md bg-indigo-600 px-3 py-1.5 text-white hover:bg-indigo-700">
                        + Agregar Sucursal y Horario
                    </button>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('medicos.index') }}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/js/select2-global.js"></script>
<script>
    $(document).ready(function() {


        // Validar el formulario antes de enviar
        $('form').on('submit', function(e) {
            if (!validarHorarios()) {
                e.preventDefault(); // Detener el envío del formulario
            }
        });
    });

    let sucursales = @json($sucursales);

    function validarHorarios() {
        let horarios = document.querySelectorAll('[name^="horarios["]');
        let esValido = true;
        let mensajeError = '';

        // Validar que haya al menos un horario
        if (horarios.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debe agregar al menos un horario para el médico.',
            });
            return false;
        }

        // Validar cada horario individualmente
        for (let i = 0; i < horarios.length / 4; i++) {
            const sucursal = document.querySelector(`[name="horarios[${i}][sucursal_id]`).value;
            const dia = document.querySelector(`[name="horarios[${i}][dia]`).value;
            const horaInicio = document.querySelector(`[name="horarios[${i}][hora_inicio]`).value;
            const horaFin = document.querySelector(`[name="horarios[${i}][hora_fin]`).value;

            // Validar que la hora de inicio sea menor que la hora de fin
            if (horaInicio >= horaFin) {
                mensajeError = `En el horario ${i+1}, la hora de inicio debe ser anterior a la hora de finalización.`;
                esValido = false;
                break;
            }

            // Validar que la diferencia sea de al menos 30 minutos
            const inicio = new Date(`2000-01-01T${horaInicio}`);
            const fin = new Date(`2000-01-01T${horaFin}`);
            const diferencia = (fin - inicio) / (1000 * 60); // diferencia en minutos

            if (diferencia < 30) {
                mensajeError = `En el horario ${i+1}, la duración mínima debe ser de 30 minutos.`;
                esValido = false;
                break;
            }
        }

        if (!esValido) {
            Swal.fire({
                icon: 'error',
                title: 'Error en los horarios',
                text: mensajeError,
            });
        }

        return esValido;
    }

    function agregarHorario() {
    let container = document.getElementById('horarios-container');
    let index = container.children.length;

    // Verificar si hay datos antiguos para este índice
    let oldData = @json(old('horarios') ?? []);
    let oldHorario = oldData[index] || {};

    let div = document.createElement('div');
    div.classList.add("mt-2", "mb-5", "p-3", "border", "border-gray-300", "rounded-md", "bg-white");

    let options = `<option value="">Seleccione una sucursal</option>`;
    sucursales.forEach(sucursal => {
        let selected = oldHorario.sucursal_id == sucursal.id ? 'selected' : '';
        options += `<option value="${sucursal.id}" ${selected}>${sucursal.nombre}</option>`;
    });

    div.innerHTML = `
        <div class="border p-3 rounded-md bg-gray-100">
            <label class="uppercase block text-sm font-medium text-gray-900">Sucursal</label>
            <select name="horarios[${index}][sucursal_id]" required class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900">
                ${options}
            </select>

            <label class="uppercase block text-sm font-medium text-gray-900 mt-2">Día</label>
            <select name="horarios[${index}][dia]" required class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900">
                <option value="lunes" ${oldHorario.dia == 'lunes' ? 'selected' : ''}>Lunes</option>
                <option value="martes" ${oldHorario.dia == 'martes' ? 'selected' : ''}>Martes</option>
                <option value="miércoles" ${oldHorario.dia == 'miércoles' ? 'selected' : ''}>Miércoles</option>
                <option value="jueves" ${oldHorario.dia == 'jueves' ? 'selected' : ''}>Jueves</option>
                <option value="viernes" ${oldHorario.dia == 'viernes' ? 'selected' : ''}>Viernes</option>
                <option value="sábado" ${oldHorario.dia == 'sábado' ? 'selected' : ''}>Sábado</option>
                <option value="domingo" ${oldHorario.dia == 'domingo' ? 'selected' : ''}>Domingo</option>
            </select>

            <label class="uppercase block text-sm font-medium text-gray-900 mt-2">Hora Inicio</label>
            <input type="time" name="horarios[${index}][hora_inicio]" value="${oldHorario.hora_inicio || ''}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900">

            <label class="uppercase block text-sm font-medium text-gray-900 mt-2">Hora Fin</label>
            <input type="time" name="horarios[${index}][hora_fin]" value="${oldHorario.hora_fin || ''}" required class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900">
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="mt-3 block w-full rounded-md bg-red-600 px-3 py-1.5 text-white font-semibold hover:bg-red-700">
            Eliminar Sucursal y Horario
        </button>
    `;
    container.appendChild(div);
}

// Agregar horarios automáticamente si hay datos antiguos
$(document).ready(function() {
    let oldHorarios = @json(old('horarios') ?? []);
    if (oldHorarios.length > 0) {
        oldHorarios.forEach((horario, index) => {
            agregarHorario();
        });
    }
});
</script>
@endpush
