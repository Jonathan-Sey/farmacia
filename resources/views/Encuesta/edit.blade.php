@extends('template')
@section('descripcion', 'Crear Encuesta')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('encuestas.update', $encuesta) }}" method="POST" id="encuesta-form">
            @csrf
            @method('PUT')

            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-2 mb-5">
                    <x-select2
                        id="detalle_medico_id"
                        name="detalle_medico_id"
                        label="Médico"
                        :options="$medicos->pluck('name', 'id')"
                        :selected="$encuesta->medico_id"
                        placeholder="Buscar Medico"
                        required
                    />
                </div>

                <div class="mt-2 mb-5">
                    <label for="titulo" class="uppercase block text-sm font-medium text-gray-900">Título</label>
                    <div>
                        <input type="text" name="titulo" id="titulo" placeholder="Título de la encuesta"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                            value="{{ old('titulo',$encuesta->titulo)}}" required>
                    </div>
                </div>

                <div class="mt-2 mb-5">
                    <label for="descripcion" class="uppercase block text-sm font-medium text-gray-900">Descripción</label>
                    <div>
                        <textarea name="descripcion" id="descripcion" placeholder="Descripción de la encuesta"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-200 focus:outline-indigo-600"
                            rows="3">{{ old('descripcion',$encuesta->descripcion) }}</textarea>
                    </div>
                </div>

                <div class="mt-2 mb-5">
                    <label class="uppercase block text-sm font-medium text-gray-900">Preguntas</label>
                    <div id="preguntas-contenedor">
                        <!-- Las preguntas se agregarán aquí dinámicamente -->
                    </div>
                    <button type="button" onclick="agregarPregunta()"
                        class="mt-2 block w-full rounded-md bg-indigo-600 px-3 py-1.5 text-white hover:bg-indigo-700">
                        + Agregar Pregunta
                    </button>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('encuestas.index') }}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Actualizar</button>
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
    let contadorPreguntas = 0;
    // obtenemos las preguntas de la encuesta a editar
    let preguntasExistentes = @json($encuesta->preguntas);
    //console.log(preguntasExistentes);
    // Funcion para obtener las preguntas existentes
    function cargarPreguntas(){
        const contenedor = document.getElementById('preguntas-contenedor')
        
        preguntasExistentes.forEach((pregunta, index)=> {
            contadorPreguntas = index + 1;

        // utilizamos el mismo contenedor
        const div = document.createElement('div');
        div.className = 'pregunta-item mb-4 p-4 border rounded-lg bg-gray-50';
        //definimos el formato y obtenemos los datos en pregunta
         div.innerHTML = `
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-medium">Pregunta #${index + 1}</h3>
                <button type="button" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <input type="hidden" name="preguntas[${index}][id]" value"${pregunta.id}">

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Texto de la pregunta</label>
                <input type="text" name="preguntas[${index}][texto]" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" 
                value="${pregunta.texto_pregunta}"
                required>
            </div>

             <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de pregunta</label>
                <select name="preguntas[${index}][tipo]" class="tipo-pregunta block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" onchange="cambiarTipoPregunta(this, ${index})" required>
                    <option value="escala" ${pregunta.tipo == 'escala' ? 'selected' : '' } >Escala de satisfacción (1-5)</option>
                    <option value="cerrado" ${pregunta.tipo == 'cerrado' ? 'selected' : '' } >Cerrado</option>
                    <option value="texto" ${pregunta.tipo == 'texto' ? 'selected' : '' } >Respuesta abierta</option>
                </select>
            </div>
        `;

            contenedor.appendChild(div);
         }); 
       
    }


    function agregarPregunta() {
        const contenedor = document.getElementById('preguntas-contenedor');
        const index = contadorPreguntas++;

        const div = document.createElement('div');
        div.className = 'pregunta-item mb-4 p-4 border rounded-lg bg-gray-50';
        div.innerHTML = `
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-medium">Pregunta #${index + 1}</h3>
                <button type="button" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Texto de la pregunta</label>
                <input type="text" name="preguntas[${index}][texto]" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de pregunta</label>
                <select name="preguntas[${index}][tipo]" class="tipo-pregunta block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" onchange="cambiarTipoPregunta(this, ${index})" required>
                    <option value="escala">Escala de satisfacción (1-5)</option>
                    <option value="cerrado">Cerrado</option>
                    <option value="texto">Respuesta abierta</option>
                </select>
            </div>
        `;

        contenedor.appendChild(div);
    }



    // function cambiarTipoPregunta(select, index) {
    //     const opcionescontenedor = document.getElementById(`opciones-contenedor-${index}`);
    //     if (select.value === 'opcion_multiple') {
    //         opcionescontenedor.classList.remove('hidden');
    //     } else {
    //         opcionescontenedor.classList.add('hidden');
    //     }
    // }

     //Agregar primera pregunta al cargar
     document.addEventListener('DOMContentLoaded', function() {
         cargarPreguntas();
     });


     //Validar formulario
     document.getElementById('encuesta-form').addEventListener('submit', function(e) {
         if (contadorPreguntas === 0) {
             e.preventDefault();
             Swal.fire('Error', 'Debe agregar al menos una pregunta', 'error');
         }
     });
</script>
@endpush