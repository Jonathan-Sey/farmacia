@extends('template')
@section('titulo', 'Editar Médico')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('medicos.update', ['medico' => $medico->id]) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-2 mb-5">
                    <label for="id_usuario" class="uppercase block text-sm font-medium text-gray-900">Usuario</label>
                    <div class="border p-3 rounded-md bg-gray-100">
                        <select class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600"
                            name="id_usuario" id="id_usuario">
                            <option value="">Buscar Usuario</option>
                            @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ $usuario->id == $medico->id_usuario ? 'selected' : '' }}>{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-2 mb-5">
                    <label for="especialidad" class="uppercase block text-sm font-medium text-gray-900">Especialidad</label>
                    <div class="border p-3 rounded-md bg-gray-100">
                        <input type="text" name="especialidad" id="especialidad" placeholder="Especialidad"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600"
                            value="{{ old('especialidad', $medico->especialidad) }}">
                    </div>
                </div>

                <div class="mt-2 mb-5">
                    <label for="numero_colegiado" class="uppercase block text-sm font-medium text-gray-900">Número de Colegiado</label>
                    <div class="border p-3 rounded-md bg-gray-100">
                        <input type="text" name="numero_colegiado" id="numero_colegiado" placeholder="Colegiado"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600"
                            value="{{ old('numero_colegiado', $medico->numero_colegiado) }}">
                    </div>
                </div>

                <div class="mt-2 mb-5">
                    <label for="estado" class="uppercase block text-sm font-medium text-gray-900">Estado</label>
                    <div class="border p-3 rounded-md bg-gray-100">
                        <select name="estado" id="estado" required class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600">
                            <option value="1" {{ $medico->estado == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ $medico->estado == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>
                

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
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: "Buscar usuario",
            allowClear: true
        });
    });

    let sucursales = @json($sucursales);

    function agregarHorario() {
        let container = document.getElementById('horarios-container');
        let index = container.children.length;
        let div = document.createElement('div');
        div.classList.add("mt-2", "mb-5", "p-3", "border", "border-gray-300", "rounded-md", "bg-white");

        let options = `<option value="">Seleccione una sucursal</option>`;
        sucursales.forEach(sucursal => {
            options += `<option value="${sucursal.id}">${sucursal.nombre}</option>`;
        });

        div.innerHTML = `
            <div class="border p-3 rounded-md bg-gray-100">
                <label class="uppercase block text-sm font-medium text-gray-900">Sucursal</label>
                <select name="horarios[${index}][sucursal_id]" required class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900">
                    ${options}
                </select>

                <label class="uppercase block text-sm font-medium text-gray-900 mt-2">Día</label>
                <select name="horarios[${index}][dia]" required class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900">
                    <option value="lunes">Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miércoles">Miércoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                    <option value="sábado">Sábado</option>
                    <option value="domingo">Domingo</option>
                </select>

                <label class="uppercase block text-sm font-medium text-gray-900 mt-2">Hora Inicio</label>
                <input type="time" name="horarios[${index}][hora_inicio]" required class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900">

                <label class="uppercase block text-sm font-medium text-gray-900 mt-2">Hora Fin</label>
                <input type="time" name="horarios[${index}][hora_fin]" required class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900">
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="mt-3 block w-full rounded-md bg-red-600 px-3 py-1.5 text-white font-semibold hover:bg-red-700">
                Eliminar Sucursal y Horario
            </button>
        `;
        container.appendChild(div);
    }
</script>
@endpush
