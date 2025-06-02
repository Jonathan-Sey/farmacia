@extends('template')
@section('titulo','Editar Rol')

@push('css')
<style>
    .checkbox-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }
</style>
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-4xl mb-10">
        <form action="{{route('roles.update', $rol)}}" method="POST">
            @csrf
            @method('PATCH')
            <div id="usuario"></div>
            <div class="border-b border-gray-900/10 pb-12">
                <!-- Nombre y Descripción -->
                    <div class="mt-2 mb-5">
                        <label for="nombre" class="block text-sm font-medium text-gray-900">Nombre del Rol</label>
                        <input type="text" name="nombre" id="nombre" value="{{old('nombre', $rol->nombre)}}"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                        @error('nombre')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message }}</span>
                        </div>
                        @enderror
                    </div>
                <!-- Pestañas -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-900">Pestañas Disponibles</label>
                    <div class="checkbox-container">
                        @foreach($pestanas as $pestana)
                        <div class="flex items-center">
                            <input type="checkbox" name="pestanas[]" id="pestana_{{$pestana->id}}"
                                   value="{{$pestana->id}}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                   @if(in_array($pestana->id, old('pestanas', $rol->pestanas->pluck('id')->toArray()))) checked @endif>
                            <label for="pestana_{{$pestana->id}}" class="ml-2 block text-sm text-gray-900">
                                {{$pestana->nombre}}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('pestanas')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <!-- Página de Inicio -->
                <div class="mt-4">
                    <label for="pagina_inicio" class="block text-sm font-medium text-gray-900">Página de Inicio</label>
                    <select name="pagina_inicio" id="pagina_inicio" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                        <option value="">-- Seleccione --</option>
                        @foreach($pestanas as $pestana)
                            @if(in_array($pestana->id, old('pestanas', $rol->pestanas->pluck('id')->toArray())))
                            <option value="{{$pestana->id}}"
                                    @if($pestana->id == old('pagina_inicio', optional($rol->pestanas->firstWhere('pivot.es_inicio', 1))->id)) selected @endif>
                                {{$pestana->nombre}}
                            </option>
                            @endif
                        @endforeach
                    </select>
                    @error('pagina_inicio')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="descripcion" class="uppercase block text-sm font-medium text-gray-900">Descripción</label>
                    <textarea name="descripcion"
                    require
                    id="descripcion" rows="3"
                    placeholder="Descripción del producto"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">{{ old('descripcion',$rol->descripcion) }}</textarea>
                    @error('descripcion')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{route('roles.index')}}" class="rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Cancelar
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Actualizar Rol
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="/js/selecciondePestañasCreateRol.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkboxes = document.querySelectorAll('input[name="pestanas[]"]');
        const paginaInicioSelect = document.getElementById('pagina_inicio');

        // Función para actualizar las opciones del select
        function updatePaginaInicioOptions() {
            // obtener pestañas
            const selectedTabs = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => {
                    return {
                        id: checkbox.value,
                        name: checkbox.nextElementSibling.textContent.trim()
                    };
                });

            // Guardar selección actual
            const currentSelection = paginaInicioSelect.value;
            // Limpiar select
            paginaInicioSelect.innerHTML = '<option value="">-- Selecciona una Pestaña --</option>';

            // Agregar opcion al seleccionar
            selectedTabs.forEach(tab => {
                const option = document.createElement('option');
                option.value = tab.id;
                option.textContent = tab.name;

                // Mantener la selección ya seleccionado
                if (tab.id === currentSelection) {
                    option.selected = true;
                }

                paginaInicioSelect.appendChild(option);
            });

            // Si solo hay una pestaña seleccionada, seleccionarla automáticamente
            if (selectedTabs.length === 1 && !currentSelection) {
                paginaInicioSelect.value = selectedTabs[0].id;
            }
        }

        // Actualizar al cargar la pagina
        updatePaginaInicioOptions();

        // Escuchar cambios en los checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updatePaginaInicioOptions);
        });
    });
    </script>
@endpush
