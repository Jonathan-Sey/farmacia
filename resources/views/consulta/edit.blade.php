@extends('template')
@section('titulo', 'Editar Consulta')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{route('consultas.update',['consulta' => $consulta->id])}}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mt-2 mb-5">
                <label for="asunto" class="uppercase block text-sm font-medium text-gray-900">Asunto</label>
                <input
                    type="text"
                    name="asunto"
                    id="asunto"
                    autocomplete="given-name"
                    placeholder="Asunto medico"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                    value="{{ old('asunto',$consulta->asunto) }}">

                @error('asunto')
                <div role="alert" class="alert alert-error mt-4 p-2">
                    <span class="text-white font-bold">{{ $message }}</span>
                </div>
                @enderror
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-2 mb-5">
                        <label for="id_usuario" class="uppercase block text-sm font-medium text-gray-900">Paciente</label>
                        <select
                            class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            name="id_persona"
                            id="id_persona">
                            <option value="">Buscar persona</option>
                            @foreach ($personas as $persona)
                                <option value="{{ $persona->id }}"
                                    {{ $persona->id == $consulta->id_persona ? 'selected' : '' }}>
                                    {{ $persona->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_persona')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="id_medico" class="uppercase block text-sm font-medium text-gray-900">Medico a cargo</label>
                        <select
                            class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            name="id_medico"
                            id="id_medico">
                            <option value="">Buscar Medico</option>
                            @foreach ($medicos as $medico)
                                <option value="{{ $medico->id }}"
                                    {{$medico->id == $consulta->id_medico ? 'selected' : ''}}
                                    >
                                    {{ $medico->usuario->name }} - {{$medico->especialidad}}</option>
                            @endforeach
                        </select>
                        @error('id_medico')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                <div class="mt-2 mb-5">
                    <label for="fecha_consulta" class="uppercase block text-sm font-medium text-gray-900">Fecha Consulta</label>

                    <input
                        readonly
                        type="date"
                        name="fecha_consulta"
                        min=""
                        id="fecha_consulta"
                        autocomplete="given-name"
                        placeholder="date"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('fecha_consulta',$consulta->fecha_consulta) }}">

                    @error('fecha_consulta')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5" id="fecha_caducidad_container">
                    <label for="proxima_cita" class="uppercase block text-sm font-medium text-gray-900">Proxima cita</label>
                    <input
                        type="date"
                        name="proxima_cita"
                        min=""
                        id="proxima_cita"
                        autocomplete="given-name"
                        placeholder="date"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('proxima_cita',$consulta->proxima_cita) }}">

                    @error('proxima_cita')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="detalle" class="uppercase block text-sm font-medium text-gray-900">Detalle de la consulta</label>
                    <textarea name="detalle"
                    id="detalle" rows="3"
                    placeholder="Detalle"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">{{ old('detalle',$consulta->detalle) }}</textarea>
                    @error('detalle')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{route('consultas.index')}}">
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
                placeholder: "Buscar",
                allowClear: true
            });
        });
        // pocicionar el cursor en el input para buscar producto
        $('.select2').on('select2:open', function() {
        document.querySelector('.select2-search__field').focus();
        });
    </script>

@endpush

