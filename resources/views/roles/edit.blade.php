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

            <div id="usuario">

            </div>
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

                <div class="mt-2 mb-5">
                    <label for="nueva_pestana" class="uppercase block text-sm font-medium text-gray-900">Vista Principal</label>
                    <input list="pestanas-list" id="nueva_pestana" name="nueva_pestana" placeholder="Selecciona una Vista Principal" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                    <datalist id="pestanas-list">
                        @foreach($pestanas as $pestana)
                            <option value="{{ $pestana->nombre }}" data-id="{{ $pestana->id }}">
                        @endforeach
                    </datalist>
                </div>
                
                <button id="addNewTab">Agregar nueva pestaña</button>  

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
                    <textarea name="descripcion" require id="descripcion" rows="3" placeholder="Descripción del Rol" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">{{old('descripcion', $rol->descripcion)}}</textarea>
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
document.addEventListener("DOMContentLoaded", function () {
    const selectPestanas = document.getElementById("pestanas");
    const selectedTabsList = document.getElementById("selected-tabs-list");
    const nuevaPestanaInput = document.getElementById("nueva_pestana");
    const pestanasList = document.getElementById("pestanas-list");

    // Pestañas que ya están seleccionadas al editar el rol (esto viene del servidor)
    let selectedTabs = @json($rol->pestanas->pluck('id')->toArray()); // Pestañas seleccionadas previamente

    console.log("Array inicial:", selectedTabs); // Depuración: Ver el array inicial

    // Función para actualizar la lista visual de pestañas seleccionadas
    function updateSelectedTabs() {
        selectedTabsList.innerHTML = ""; // Limpiar la lista antes de actualizar

        // Recorrer el array selectedTabs y agregar cada pestaña a la lista visual
        selectedTabs.forEach(tabId => {
            const pestana = Array.from(selectPestanas.options).find(option => parseInt(option.value) === tabId);
            if (pestana) {
                let li = document.createElement("li");
                li.textContent = pestana.textContent;
                selectedTabsList.appendChild(li);
            }
        });

        console.log("Array después de actualizar la lista visual:", selectedTabs); // Depuración: Ver el array después de actualizar
    }

    // Llamar a la función inicial para cargar las pestañas seleccionadas previamente
    updateSelectedTabs();

    // Manejar la selección en el input de "nueva_pestana"
    nuevaPestanaInput.addEventListener("change", function () {
        const selectedPestanaName = nuevaPestanaInput.value;

        // Buscar el ID de la pestaña seleccionada en el datalist
        const selectedOption = Array.from(pestanasList.options).find(option => option.value === selectedPestanaName);

        if (selectedOption) {
            const nuevaPestanaId = parseInt(selectedOption.getAttribute("data-id"));

            console.log("Nueva pestaña seleccionada (ID):", nuevaPestanaId); // Depuración: Ver el ID de la nueva pestaña

            // Verificar si la pestaña ya está en el array para evitar duplicados
            if (!selectedTabs.includes(nuevaPestanaId)) {
                // Agregar la nueva pestaña al principio del array usando unshift()
                selectedTabs.unshift(nuevaPestanaId);

                console.log("Array después de agregar la nueva pestaña:", selectedTabs); // Depuración: Ver el array después de agregar

                // Actualizar la lista visual
                updateSelectedTabs();

                // Actualizar el select múltiple para reflejar los cambios
                Array.from(selectPestanas.options).forEach(option => {
                    if (selectedTabs.includes(parseInt(option.value))) {
                        option.selected = true;
                    } else {
                        option.selected = false;
                    }
                });
            }

            // Limpiar el input después de agregar la pestaña
            nuevaPestanaInput.value = "";
        }
    });

    // Cambiar el comportamiento predeterminado del evento 'mousedown' en el select
    selectPestanas.addEventListener("mousedown", function (event) {
        event.preventDefault(); // Evita la selección automática por el navegador

        let clickedOption = event.target;

        // Si el elemento clickeado es una opción dentro del select
        if (clickedOption.tagName === "OPTION") {
            clickedOption.selected = !clickedOption.selected; // Alterna la selección

            // Actualizar el array selectedTabs
            const clickedOptionId = parseInt(clickedOption.value);
            if (clickedOption.selected) {
                // Si la opción está seleccionada, agregarla al array (pero no al principio)
                if (!selectedTabs.includes(clickedOptionId)) {
                    selectedTabs.push(clickedOptionId);
                }
            } else {
                // Si la opción está deseleccionada, quitarla del array
                selectedTabs = selectedTabs.filter(id => id !== clickedOptionId);
            }

            console.log("Array después de interactuar con el select múltiple:", selectedTabs); // Depuración: Ver el array después de interactuar

            // Actualizar la lista visual
            updateSelectedTabs();
        }
    });

    // Asegurarnos de que el array selectedTabs se envíe correctamente al backend
    const form = document.querySelector("form");
    form.addEventListener("submit", function (event) {
        // Eliminar cualquier campo oculto previo de "pestanas[]"
        document.querySelectorAll("input[name='pestanas[]']").forEach(input => input.remove());

        // Crear un campo oculto por cada elemento en selectedTabs (en el orden correcto)
        selectedTabs.forEach(tabId => {
            const hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = "pestanas[]"; // Nombre del campo como array
            hiddenInput.value = tabId; // Valor del campo

            // Agregar el campo oculto al formulario
            form.appendChild(hiddenInput);
        });

        console.log("Array enviado al backend:", selectedTabs); // Depuración: Ver el array que se envía
    });
});
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const selectNuevaPestana = document.getElementById("nueva_pestana");
    
        selectNuevaPestana.addEventListener("change", function () {
            const nuevaPestanaId = selectNuevaPestana.value;
            console.log("Nueva Pestaña Seleccionada: ", nuevaPestanaId); // Verifica que el valor está bien
        });
    });

    </script>


@endpush

