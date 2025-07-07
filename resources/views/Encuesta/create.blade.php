
@extends('template')
@section('descripcion', 'Crear Médico')

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
                     <x-select2
                        id="detalle_medico_id"
                        name="detalle_medico_id"
                        label="Médico"
                        :options="$medicos->pluck('name', 'id')"
                        :selected="old('detalle_medico_id')"
                        placeholder="Buscar Medico"
                        required 
                    />
                </div> 
                <div class="mt-2 mb-5">
                    <label for="titulo" class="uppercase block text-sm font-medium text-gray-900">Titulo</label>
                    <div class="border p-1 rounded-md bg-gray-100">
                        <input type="text" name="titulo" id="titulo" placeholder="Titulo de la encuesta"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600"
                            value="{{ old('titulo') }}">
                    </div>
                </div>
                @error('titulo')
                <div role="alert" class="alert alert-error mt-4 p-2">
                    <span class="text-white font-bold">{{ $message }}</span>
                </div>
                @enderror

                <div class="checkbox-group required display:flex flex-row">
                    <label for="pregunta">Muy satisfecho</label>
                    <input name="pregunta" type="checkbox" checked="checked" class="checkbox checkbox-success" name="pregunta[]"/>
                    <button type="button" onclick=""><i class="p-3 cursor-pointer fa-solid fa-trash"></i></button>
                    <i class="fa-solid fa-plus"></i>
                </div>


                   <div class="mt-2 mb-5">
                    <label for="descripcion" class="uppercase block text-sm font-medium text-gray-900">Descripcion</label>
                    <div class="border p-1 rounded-md bg-gray-100">
                        <input type="text" name="descripcion" id="descripcion" placeholder="Descripcion de la encuesta"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-200 focus:outline-indigo-600"
                            value="{{ old('descripcion') }}">
                    </div>

                </div>
                @error('descripcion')
                <div role="alert" class="alert alert-error mt-4 p-2">
                    <span class="text-white font-bold">{{ $message }}</span>
                </div>
                @enderror

                
                <div class="mt-2 mb-5">
                    <label for="fecha" class="uppercase block text-sm font-medium text-gray-900">Fecha</label>
                    <div class="border p-1 rounded-md bg-gray-100">
                        <input type="text" name="fecha" id="fecha" placeholder="Fecha de la encuesta"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600"
                            value="{{ old('fecha') }}">
                    </div>
                </div>
                @error('fecha')
                <div role="alert" class="alert alert-error mt-4 p-2">
                    <span class="text-white font-bold">{{ $message }}</span>
                </div>
                @enderror

                <div class="mt-2 mb-5">
                    <label for="horarios" class="uppercase block text-sm font-medium text-gray-900">Preguntas</label>
                    <div id="horarios-container"></div>
                    <div id="MasPreguntas"></div>
                    <button type="button" onclick="agregarHorario()"
                        class="mt-2 block w-full rounded-md bg-indigo-600 px-3 py-1.5 text-white hover:bg-indigo-700">
                        + Agregar Preguntas
                    </button>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('encuestas.index') }}">
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
            <div class="checkbox-group required display:flex flex-row">
                <label for="pregunta">Muy satisfecho</label>
                <input name="pregunta" type="checkbox" checked="checked" class="checkbox checkbox-success" name="pregunta[]"/>
                <button type="button" onclick=""><i class="p-3 cursor-pointer fa-solid fa-trash"></i></button>
                <i class="fa-solid fa-plus "></i>
            </div>                    
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="mt-3 block w-full rounded-md bg-red-600 px-3 py-1.5 text-white font-semibold hover:bg-red-700">
            Eliminar Pregunta
        </button>
    `;
    
    container.appendChild(div);

    // function agregarPregutas() {
    // let container = document.getElementById('MasPreguntas');
    // let index = container.children.length;

    // let div = document.createElement('div');
    // div.classList.add("mt-2", "mb-5", "p-3", "border", "border-gray-300", "rounded-md", "bg-white");
    // }

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
