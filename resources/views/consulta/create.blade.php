@extends('template')
@section('titulo', 'Crear Consulta')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('consultas.store') }}" method="POST">
            @csrf
            
            <div id="usuario">
                <h2 class="text-center text-lg font-bold">Datos de la Consulta</h2>

                {{-- Asunto de la Consulta --}}
                <div class="mt-2 mb-5">
                    <label for="asunto" class="uppercase block text-sm font-medium text-gray-900">Asunto</label>
                    <input type="text" name="asunto" id="asunto" placeholder="Asunto médico"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('asunto') }}" required>
                    @error('asunto')
                        <div class="alert alert-error mt-4 p-2"><span class="text-white font-bold">{{ $message }}</span></div>
                    @enderror
                </div>

                {{-- Selección del Paciente --}}
                <div class="mt-2 mb-5">
                    <label for="id_persona" class="uppercase block text-sm font-medium text-gray-900">Paciente</label>
                    <select class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600 sm:text-sm"
                        name="id_persona" id="id_persona" required>
                        <option value="">Buscar persona</option>
                        @foreach ($personas as $persona)
                            <option value="{{ $persona->id }}">{{ $persona->nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_persona')
                        <div class="alert alert-error mt-4 p-2"><span class="text-white font-bold">{{ $message }}</span></div>
                    @enderror
                </div>

                {{-- Selección del Médico --}}
                <div class="mt-2 mb-5">
                    <label for="id_medico" class="uppercase block text-sm font-medium text-gray-900">Médico a cargo</label>
                    <select class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600 sm:text-sm"
                        name="id_medico" id="id_medico" required>
                        <option value="">Buscar Médico</option>
                        @foreach ($medicos as $medico)
                            <option value="{{ $medico->id }}">{{ $medico->usuario->name }} - {{$medico->especialidad}}</option>
                        @endforeach
                    </select>
                    @error('id_medico')
                        <div class="alert alert-error mt-4 p-2"><span class="text-white font-bold">{{ $message }}</span></div>
                    @enderror
                </div>

                {{-- Fecha de la Consulta --}}
                <div class="mt-2 mb-5">
                    <label for="fecha_consulta" class="uppercase block text-sm font-medium text-gray-900">Fecha Consulta</label>
                    <input type="hidden" name="fecha_consulta" id="fecha_consulta" 
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600 sm:text-sm"
                        value="{{ date('Y-m-d') }}">
                </div>

                {{-- Próxima Cita --}}
                <div class="mt-2 mb-5">
                    <label for="proxima_cita" class="uppercase block text-sm font-medium text-gray-900">Próxima Cita</label>
                    <input type="date" name="proxima_cita" id="proxima_cita"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('proxima_cita') }}">
                </div>

                {{-- Detalle de la Consulta --}}
                <div class="mt-2">
                    <label for="detalle" class="uppercase block text-sm font-medium text-gray-900">Detalle de la consulta</label>
                    <textarea name="detalle" id="detalle" rows="3"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600 sm:text-sm"
                        required>{{ old('detalle') }}</textarea>
                </div>

                <hr class="my-5">

                {{-- 🔹 Campos de la Ficha Médica --}}
                <h2 class="text-center text-lg font-bold">Datos de la Ficha Médica</h2>

                <div class="mt-2 mb-5">
                    <label for="edad" class="uppercase block text-sm font-medium text-gray-900">Edad</label>
                    <input type="number" name="edad" id="edad" class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600 sm:text-sm"
                        required>
                </div>

                <div class="mt-2 mb-5">
                    <label for="peso" class="uppercase block text-sm font-medium text-gray-900">Peso (kg)</label>
                    <input type="number" step="0.1" name="peso" id="peso" class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600 sm:text-sm"
                        required>
                </div>

                <div class="mt-2 mb-5">
                    <label for="altura" class="uppercase block text-sm font-medium text-gray-900">Altura (m)</label>
                    <input type="number" step="0.01" name="altura" id="altura" class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600 sm:text-sm"
                        required>
                </div>

                <div class="mt-2 mb-5">
                    <label for="presion_arterial" class="uppercase block text-sm font-medium text-gray-900">Presión Arterial</label>
                    <input type="text" name="presion_arterial" id="presion_arterial" class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 outline-gray-300 focus:outline-indigo-600 sm:text-sm">
                </div>

                {{-- Botones --}}
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{ route('consultas.index') }}">
                        <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                    </a>
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
