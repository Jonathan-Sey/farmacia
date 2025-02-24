@extends('template')
@section('titulo', 'Crear Producto')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{route('productos.store')}}" method="POST">
            @csrf
            <div id="usuario">

            </div>
            <div class="border-b border-gray-900/10 pb-12">


                <div class="mt-2 mb-5 flex flex-col gap-5 md:grid md:grid-cols-2 " >
                    <div class="mt-2 mb-5">
                        <label for="id_categoria" class="uppercase block text-sm font-medium text-gray-900">Categoría</label>
                        <select
                            class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            name="id_categoria"
                            id="id_categoria">
                            <option value="">Seleccionar una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">
                                    {{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                        @error('id_categoria')
                            <div role="alert" class="alert alert-error mt-4 p-2">
                                <span class="text-white font-bold">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="flex flex-row gap-5">
                        <div class="flex flex-col gap-1">
                            <label for="tipo">Servicio</label>
                            <input name="tipo" id="tipo" type="checkbox" class="toggle toggle-success"
                            {{ old('tipo') ? 'checked' : '' }}
                            />
                        </div>
                    </div>
                </div>

                <div class="mt-2 mb-5">
                    <label for="nombre" class="uppercase block text-sm font-medium text-gray-900">Nombre</label>
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        autocomplete="given-name"
                        placeholder="Nombre del producto"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('nombre') }}">

                    @error('nombre')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                    <label for="precio_venta" class="uppercase block text-sm font-medium text-gray-900">Precio</label>
                    <input
                        type="text"
                        name="precio_venta"
                        id="precio_venta"
                        placeholder="Precio"
                        min="1"
                        step="any"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('precio_venta') }}">

                    @error('precio_venta')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                    <label for="porcentaje" class="uppercase block text-sm font-medium text-gray-900">Porcentaje de aumento (%)</label>
                    <input
                        type="number"
                        id="porcentaje"
                        placeholder="Ejemplo: 10 para 10%"
                        min="0"
                        step="any"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                </div>
{{--
                <div class="mt-2 mb-5">
                    <label for="fecha_caducidad" class="uppercase block text-sm font-medium text-gray-900">Fecha de Vencimiento</label>
                    <input
                        type="date"
                        name="fecha_caducidad"
                        id="fecha_caducidad"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('fecha_caducidad') }}">
                </div> --}}

                <div class="mt-2">
                    <label for="descripcion" class="uppercase block text-sm font-medium text-gray-900">Descripción</label>
                    <textarea name="descripcion"
                    id="descripcion" rows="3"
                    placeholder="Descripción del producto"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"></textarea>
                    @error('descripcion')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{route('productos.index')}}">
                        <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                    </a>
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
                </div>
        </form>
    </div>
</div>

<script src="/js/obtenerUsuario.js"></script>
<script>

let precioBase = 0; // almacena el precio original

// Función para desformatear el valor ingresado (para cálculos)
function desformatearMoneda(valor) {
  return parseFloat(valor.replace(/[^\d.-]/g, '')) || 0;
 }

// Función para formatear el número como moneda
 function formatearMoneda(valor) {
     return valor.toFixed(2); // Deja solo dos decimales
 }

// Evento para actualizar el precio mientras se escribe el valor en "precio_venta"
document.getElementById('precio_venta').addEventListener('input', function() {
    let precioIngresado = desformatearMoneda(document.getElementById('precio_venta').value);
    precioBase = precioIngresado;
    document.getElementById('precio_venta').value = precioIngresado; // Mantenemos el valor tal cual lo escribe el usuario
    calcularPrecioFinal(); // Llamamos la función de cálculo después de cambiar el precio
});

// Evento para actualizar el precio cuando se pierda el foco del campo
document.getElementById('precio_venta').addEventListener('blur', function() {
    let precioIngresado = desformatearMoneda(document.getElementById('precio_venta').value);
    document.getElementById('precio_venta').value = formatearMoneda(precioIngresado); // Formateamos el valor a dos decimales
    calcularPrecioFinal(); // Llamamos la función de cálculo después de que el campo pierde el foco
});

// Evento para actualizar el precio al modificar el porcentaje
document.getElementById('porcentaje').addEventListener('input', function() {
    calcularPrecioFinal(); // Llamamos a la función de cálculo al cambiar el porcentaje
});

function calcularPrecioFinal() {
    let precioBaseActual = precioBase;
    let porcentaje = parseFloat(document.getElementById('porcentaje').value) || 0;

    // Si el porcentaje es 0, mostramos solo el precio base
    if (porcentaje === 0) {
        document.getElementById('precio_venta').value = formatearMoneda(precioBaseActual);
    } else {
        // Calculamos el precio con el porcentaje y lo mostramos
        let precioFinal = precioBaseActual + (precioBaseActual * (porcentaje / 100));
        document.getElementById('precio_venta').value = formatearMoneda(precioFinal);
    }
}

</script>

@endsection
