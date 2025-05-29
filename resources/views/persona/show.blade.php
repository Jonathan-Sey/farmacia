@extends('template')

@section('contenido')
<div class="container mx-auto py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Detalles de {{ $persona->nombre }}</h1>

    <div class="bg-white p-6 rounded-xl shadow-md mb-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Datos Personales</h2>

        <!-- Contenedor de todos los datos de la persona -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <!-- Datos permanentes (nombre, NIT, teléfono, etc.) -->
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">Nombre:</span>
                    <p class="text-gray-800">{{ $persona->nombre }}</p>
                </div>
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">NIT:</span>
                    <p class="text-gray-800">{{ $persona->nit }}</p>
                </div>
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">Teléfono:</span>
                    <p class="text-gray-800">{{ $persona->telefono }}</p>
                </div>
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">Fecha de Nacimiento:</span>
                    <p class="text-gray-800">{{ $persona->fecha_nacimiento ?? 'No especificado' }}</p>
                </div>
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">Estado:</span>
                    <p class="text-gray-800">{{ $persona->estado == 1 ? 'Activo' : 'Inactivo' }}</p>
                </div>
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">Sexo:</span>
                    <p class="text-gray-800">{{ $persona->fichasMedicas->first()->sexo ?? 'No especificado' }}</p>
                </div>
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">DPI:</span>
                    <p class="text-gray-800">
                        @if($persona->rol == 2)
                            {{-- Paciente: DPI de ficha médica --}}
                            {{ $persona->fichasMedicas->first()->DPI ?? 'No especificado' }}
                        @else
                            {{-- Cliente: DPI directo en persona --}}
                            {{ $persona->DPI ?? 'No especificado' }}
                        @endif
                    </p>
                </div>
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">Tipo de Sangre:</span>
                    <p class="text-gray-800">{{ $persona->fichasMedicas->first()->tipo_sangre ?? 'No especificado' }}</p>
                </div>
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">Habla Lengua:</span>
                    <p class="text-gray-800">{{ $persona->fichasMedicas->first()->habla_lengua ?? 'No especificado' }}</p>
                </div>
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">Dirección:</span>
                    <p class="text-gray-800">{{ $persona->fichasMedicas->first()->direccion ?? 'No especificado' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Fichas Médicas</h2>

        @if ($persona->fichasMedicas->isEmpty())
            <p>No hay fichas médicas registradas para esta persona.</p>
        @else
            <ul class="space-y-4">
                @foreach ($persona->fichasMedicas as $ficha)
                    <li class="border-b pb-4">
                        <p><strong class="text-gray-600">Diagnóstico:</strong> {{ $ficha->diagnostico }}</p>
                        <p><strong class="text-gray-600">Médico:</strong> {{ $ficha->detalleMedico->usuario->name ?? 'No asignado' }}</p>
                        <p><strong class="text-gray-600">Consulta Programada:</strong> {{ $ficha->consulta_programada }}</p>

                        @if ($ficha->receta_foto)
                            <div class="mt-4">
                                <img src="{{ asset('storage/' . $ficha->receta_foto) }}" alt="Receta Médica" class="w-32 h-32 object-cover cursor-pointer rounded-md" onclick="openModal('{{ asset('storage/' . $ficha->receta_foto) }}')">
                            </div>
                        @endif

                        <!-- Botones Editar y Eliminar -->
                        <div class="mt-3 space-x-2">
                            <a href="{{ route('fichas.edit', $ficha->id) }}" 
                            class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                                Editar
                            </a>

                            <a href="{{ route('fichas.delete', $ficha->id) }}"
                            onclick="return confirm('¿Seguro que deseas eliminar esta ficha médica?')"
                            class="inline-block px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">
                                Eliminar
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('fichas.create', $persona->id) }}">
            <button type="button" class="w-full sm:w-auto text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 px-6 py-2 rounded-md text-sm font-semibold mt-4">
                Agregar Ficha Médica
            </button>
        </a>

        <a href="{{ route('personas.index') }}">
            <button type="button" class="w-full sm:w-auto text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 px-6 py-2 rounded-md text-sm font-semibold mt-4">
                Volver al Índice de Personas
            </button>
        </a>

    </div>

    <!-- Modal para mostrar la imagen en tamaño grande -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-md max-w-4xl w-full">
            <span class="text-white text-2xl cursor-pointer absolute top-4 right-4" onclick="closeModal()">&times;</span>
            <div class="max-h-[80vh] overflow-auto">
                <img id="modalImage" src="" alt="Receta Médica" class="max-w-full h-auto mx-auto">
            </div>
            <div class="mt-4 text-center">
                <button onclick="closeModal()" class="bg-red-600 text-white py-2 px-4 rounded-md text-sm font-semibold hover:bg-red-700 focus:outline-none">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    function openModal(imageSrc) {
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('modalImage').src = imageSrc;
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>
@endsection
