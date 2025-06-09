@extends('template')

@section('content')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <h3 class="text-xl font-semibold mb-4">Confirmar Eliminación</h3>

        <p class="mb-6">¿Estás seguro que deseas eliminar esta ficha médica?</p>

        <div class="bg-gray-100 p-4 rounded-lg mb-6">
            <p><strong>Diagnóstico:</strong> {{ $ficha->diagnostico }}</p>
            <p><strong>Médico:</strong> {{ $ficha->detalleMedico->usuario->name ?? 'No asignado' }}</p>
            <p><strong>Consulta programada:</strong> {{ $ficha->consulta_programada }}</p>
        </div>

        <form action="{{ route('fichas.destroy', $ficha->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end gap-x-4">
                <a href="{{ route('personas.show', $ficha->persona_id) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Confirmar Eliminación
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
