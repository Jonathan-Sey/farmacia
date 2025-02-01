@extends('template')
@section('titulo','Editar Rol')

@push('css')

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-4xl mb-10">
        <form action="{{route('roles.update', ['rol' => $rol->id])}}" method="POST">
            @csrf
            @method('PATCH')
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-2 mb-5">
                    <label for="nombre" class="uppercase block text-sm font-medium text-gray-900">Nombre del Rol</label>
                    <input type="text" name="nombre" id="nombre" autocomplete="given-name" placeholder="Nombre del Rol" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" value="{{old('nombre', $rol->nombre)}}">
                    @error('nombre')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <!-- Vista Principal -->
                <div class="mt-4">
                    <label for="nueva_pestana" class="block text-sm font-medium text-gray-900">Seleccionar Vista Principal</label>
                    <select name="nueva_pestana" id="nueva_pestana" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" onchange="updateSelectedTabs()">
                        <option value="">-- Selecciona una Vista Principal --</option>
                        @foreach($pestanas as $pestana)
                            <option value="{{ $pestana->id }}">{{ $pestana->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pestañas seleccionadas -->
                <div class="mt-4">
                    <label for="pestanas" class="block text-sm font-medium text-gray-900">Seleccionar Pestañas</label>
                    <select name="pestanas[]" id="pestanas" multiple class="block w-full rounded-md bg-white px-3 py-3.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" onchange="updateSelectedTabs()">
                        @foreach($pestanas as $pestana)
                            <option value="{{ $pestana->id }}" 
                                @if(in_array($pestana->id, $rol->pestanas->pluck('id')->toArray())) selected @endif>
                                {{ $pestana->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('pestanas')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <!-- Mostrar las pestañas seleccionadas -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-900">Pestañas Seleccionadas</label>
                    <ul id="selected-tabs-list" class="list-disc pl-5 text-sm text-gray-700">
                        <!-- Lista de pestañas seleccionadas se mostrará aquí -->
                    </ul>
                </div>

                <div class="mt-2">
                    <label for="descripcion" class="uppercase block text-sm font-medium text-gray-900">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" placeholder="Descripción del Rol" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">{{old('descripcion', $rol->descripcion)}}</textarea>
                    @error('descripcion')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{route('roles.index')}}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')

<script>
    window.onload = function() {
        updateSelectedTabs();
        updateSelectTabs();
    };
    
    // Función para actualizar las pestañas seleccionadas
    function updateSelectedTabs() {
        const selectedTabs = document.getElementById('pestanas').selectedOptions;
        const newTab = document.getElementById('nueva_pestana').value;
        const selectedTabsList = document.getElementById('selected-tabs-list');
    
        // Crear una lista de pestañas seleccionadas
        let tabsArray = Array.from(selectedTabs).map(option => option.text);
    
        // Si hay una nueva pestaña seleccionada y no está en la lista, reemplazar la primera pestaña
        if (newTab) {
            const newTabText = document.querySelector(`#nueva_pestana option[value='${newTab}']`).text;
    
            // Si la nueva pestaña no está en el array, reemplazar la primera
            if (!tabsArray.includes(newTabText)) {
                tabsArray[0] = newTabText; 
            }
        }
    
        // Limpiar la lista visual y volver a llenarla con el orden correcto
        selectedTabsList.innerHTML = '';
        tabsArray.forEach(tab => {
            const listItem = document.createElement('li');
            listItem.textContent = tab;
            selectedTabsList.appendChild(listItem);
        });
    
        // Actualizar las opciones seleccionadas en el select
        const selectElement = document.getElementById('pestanas');
        Array.from(selectElement.options).forEach(option => {
            option.selected = tabsArray.includes(option.text);
        });
    
     
    }
    
    // Función para actualizar el selector múltiple
    function updateSelectTabs() {
        const selectedTabs = document.getElementById('pestanas');
        const currentSelectedTabs = @json($rol->pestanas->pluck('id')->toArray()); 
        Array.from(selectedTabs.options).forEach(option => {
            if (currentSelectedTabs.includes(parseInt(option.value))) {
                option.selected = true;
            }
        });
    }
    
</script>
    
    
@endpush
