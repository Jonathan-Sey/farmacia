@extends('template')

@section('contenido')
<div class="container mx-auto py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Detalles de {{ $persona->nombre }}</h1>

    <div class="bg-white p-6 rounded-xl shadow-md mb-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Datos Personales</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
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

                {{-- Mostrar Sexo solo si hay ficha médica --}}
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">Sexo:</span>
                    <p class="text-gray-800">
                        {{ optional($persona->fichasMedicas->first())->sexo ?? 'No especificado' }}
                    </p>
                </div>

                {{-- DPI según rol --}}
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">DPI:</span>
                    <p class="text-gray-800">
                        @if($persona->rol == 2)
                            {{ optional($persona->fichasMedicas->first())->DPI ?? 'No especificado' }}
                        @else
                            {{ $persona->DPI ?? 'No especificado' }}
                        @endif
                    </p>
                </div>

                {{-- Otros campos similares --}}
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">Tipo de Sangre:</span>
                    <p class="text-gray-800">{{ optional($persona->fichasMedicas->first())->tipo_sangre ?? 'No especificado' }}</p>
                </div>
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">Habla Lengua:</span>
                    <p class="text-gray-800">{{ optional($persona->fichasMedicas->first())->habla_lengua ?? 'No especificado' }}</p>
                </div>
                <div class="flex items-center mb-4">
                    <span class="font-medium text-gray-600 w-1/3">Dirección:</span>
                    <p class="text-gray-800">{{ optional($persona->fichasMedicas->first())->direccion ?? 'No especificado' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Fichas Médicas</h2>

        @if ($fichas->isEmpty())
            <p>No hay fichas médicas registradas para esta persona.</p>
        @else
            <!-- Paginación superior -->
            {{-- <div class="join mb-4 flex justify-center">
                @if ($fichas->onFirstPage())
                    <button class="join-item btn btn-disabled">«</button>
                @else
                    <a href="{{ $fichas->previousPageUrl() }}" class="join-item btn">«</a>
                @endif

                <span class="join-item btn">Página {{ $fichas->currentPage() }} de {{ $fichas->lastPage() }}</span>

                @if ($fichas->hasMorePages())
                    <a href="{{ $fichas->nextPageUrl() }}" class="join-item btn">»</a>
                @else
                    <button class="join-item btn btn-disabled">»</button>
                @endif
            </div> --}}

            <ul class="space-y-4">
                @foreach ($fichas as $ficha)
                    <li class="border-b pb-4 break-words">
                        <p><strong class="text-gray-600">Diagnóstico:</strong> {{ $ficha->diagnostico }}</p>
                        <p><strong class="text-gray-600">Médico:</strong> {{ $ficha->detalleMedico->usuario->name ?? 'No asignado' }}</p>
                        <p><strong class="text-gray-600">Sucursal:</strong> {{ $ficha->sucursal->nombre ?? 'No asignado' }}</p>
                        <p><strong class="text-gray-600">Consulta Programada:</strong> {{ $ficha->consulta_programada }}</p>

                        <div>
                            @if ($ficha->receta_foto)
                            <img src="{{ asset('uploads/' . $ficha->receta_foto) }}" alt="Receta Médica" class="w-32 h-32 object-cover cursor-pointer rounded-md" onclick="openModal('{{ asset('uploads/' . $ficha->receta_foto) }}')">
                            @else
                                <span class="text-gray-500">Sin imagen</span>
                            @endif
                        </div>

                        <div class="mt-3 space-x-2">
                            <a href="{{ route('fichas.edit', ['persona_id' => $persona->id, 'ficha' => $ficha->id]) }}"
                                class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                                 Editar
                             </a>
                        </div>
                    </li>
                @endforeach
            </ul>

            <!-- Paginación inferior -->
            <div class="join mt-4 flex justify-center">
                @if ($fichas->onFirstPage())
                    <button class="join-item btn btn-disabled">«</button>
                @else
                    <a href="{{ $fichas->previousPageUrl() }}" class="join-item btn">«</a>
                @endif

                <span class="join-item btn">Página {{ $fichas->currentPage() }} de {{ $fichas->lastPage() }}</span>

                @if ($fichas->hasMorePages())
                    <a href="{{ $fichas->nextPageUrl() }}" class="join-item btn">»</a>
                @else
                    <button class="join-item btn btn-disabled">»</button>
                @endif
            </div>
        @endif

        <!-- Botones de acción (mantener igual) -->
        <a href="{{ route('fichas.create', ['persona_id' => $persona->id]) }}">
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

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 1600,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
            }
        });
            document.addEventListener('DOMContentLoaded', function() {
                console.log("Evento DOMContentLoaded disparado");
                Toast.fire({ icon: "success",
                title: "{{ session('success')}}"
                });
        });
</script>
@endif

<script>
    function openModal(imageSrc) {
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('modalImage').src = imageSrc;
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>

<script>
    function confirmarEliminacion(fichaId, personaId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar solicitud DELETE
                fetch(`/fichas/${fichaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Error en la respuesta');
                })
                .then(data => {
                    Swal.fire(
                        '¡Eliminado!',
                        'La ficha médica ha sido eliminada.',
                        'success'
                    ).then(() => {
                        window.location.href = `/personas/${personaId}`;
                    });
                })
                .catch(error => {
                    Swal.fire(
                        'Error',
                        'Hubo un problema al eliminar la ficha médica.',
                        'error'
                    );
                    console.error('Error:', error);
                });
            }
        });
    }
    </script>

@endpush