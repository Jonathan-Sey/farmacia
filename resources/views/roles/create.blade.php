@extends('template')
@section('titulo','Crear Rol')

@push('css')

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <div id="usuario">

            </div>
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-2 mb-5">
                    <label for="nombre" class="uppercase block text-sm font-medium text-gray-900">Nombre del Rol</label>
                    <input type="text" name="nombre" id="nombre" autocomplete="given-name" placeholder="Nombre del Rol" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" value="{{ old('nombre') }}">
                    @error('nombre')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <!-- Pestañas seleccionadas -->
                <div class="mt-4">
                    <label for="pestanas" class="block text-sm font-medium text-gray-900">Seleccionar Pestañas</label>
                    <select name="pestanas[]" id="pestanas" multiple class="block w-full rounded-md bg-white px-3 py-3.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" onchange="updateSelectedTabs()">
                        @foreach ($pestanas as $pestana)
                            <option value="{{ $pestana->id }}">{{ $pestana->nombre }}</option>
                        @endforeach
                    </select>
                    @error('pestanas')
                    <div class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
                <!-- Nueva Pestaña -->
<div class="mt-4">
    <label for="nueva_pestana" class="block text-sm font-medium text-gray-900">Seleccionar Página de Inicio</label>
    <select name="nueva_pestana" id="nueva_pestana" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" onchange="updateSelectedTabs()">
        <option value="">-- Selecciona una Pestaña --</option>
        @foreach ($pestanas as $pestana)
            <option value="{{ $pestana->id }}">{{ $pestana->nombre }}</option>
        @endforeach
    </select>
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
                    <textarea name="descripcion" require id="descripcion" rows="3" placeholder="Descripción del Rol" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('roles.index') }}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="/js/selecciondePestañasCreateRol.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const selectPestanas = document.getElementById("pestanas");
    const selectedTabsList = document.getElementById("selected-tabs-list");

    selectPestanas.addEventListener("mousedown", function (event) {
        event.preventDefault(); // Evita la selección automática por el navegador

        let clickedOption = event.target;

        // Si el elemento clickeado es una opción dentro del select
        if (clickedOption.tagName === "OPTION") {
            clickedOption.selected = !clickedOption.selected; // Alterna selección
        }

        updateSelectedTabs(); // Llamamos a la función para actualizar la lista
    });

    function updateSelectedTabs() {
        selectedTabsList.innerHTML = ""; // Limpiar la lista antes de actualizar

        // Obtener todas las opciones seleccionadas
        const selectedOptions = Array.from(selectPestanas.selectedOptions);

        selectedOptions.forEach(option => {
            let li = document.createElement("li");
            li.textContent = option.textContent;
            selectedTabsList.appendChild(li);
        });
    }
});

</script>
@endpush
