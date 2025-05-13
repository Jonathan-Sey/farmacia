@extends('template')

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        @if ($errors->any())
    <div class="mb-4 text-red-600">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

        <form action="{{ route('fichas.store', $persona->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h3 class="text-xl font-semibold mb-4">Crear Ficha Médica para {{ $persona->nombre }}</h3>
            
            <div class="mt-2 mb-5">
                <label for="diagnostico" class="uppercase block text-sm font-medium text-gray-900">Diagnóstico</label>
                <textarea
                    name="diagnostico"
                    id="diagnostico"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">{{ old('diagnostico') }}</textarea>
            </div>
            <div class="mt-2 mb-5">
                <label for="detalle_medico_id" class="uppercase block text-sm font-medium text-gray-900">
                    Médico
                </label>
                <select name="detalle_medico_id" id="detalle_medico_id" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" required>
                    <option value="" disabled selected>-- Seleccione un médico --</option>
                    @foreach($medicos as $detalle)
                      <option value="{{ $detalle->id }}"
                          {{ old('detalle_medico_id') == $detalle->id ? 'selected' : '' }}>
                        {{ $detalle->usuario->name }} {{-- o el campo que uses para el nombre --}}
                        @if($detalle->especialidad) – {{ $detalle->especialidad }} @endif
                      </option>
                    @endforeach
                  </select>
            </div>

            <div class="mt-2 mb-5">
                <label for="consulta_programada" class="uppercase block text-sm font-medium text-gray-900">Consulta Programada</label>
                <input
                    type="date"
                    name="consulta_programada"
                    id="consulta_programada"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                    value="{{ old('consulta_programada') }}">
            </div>

            <div class="mt-4">
                <label for="receta_foto" class="block text-sm font-medium text-gray-700">Foto de la Receta</label>
                <input type="file" name="receta_foto" id="receta_foto" class="mt-1 block w-full" accept="image/*">
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('personas.show', $persona->id) }}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>

            
        </form>
    </div>
</div>
@endsection
