@extends('template')
@section('titulo', 'Editar Porcentaje del precio')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('productos.actualizarprecio', $producto->id) }}" method="POST" id="precioForm">
            @csrf
            @method('PATCH')
            <div class="border-b border-gray-900/10 pb-12">
                <div id="producto">
                    <p>Producto: {{ $producto->nombre }}</p>
                    <p class="font-bold">Precio Actual: Q <span id="precio_actual" >{{ $producto->precio_porcentaje }}</span></p>
                </div>
                <!-- Nuevos campos para el precio -->
                <div class="mt-2 mb-5">
                    <label for="porcentaje" class="uppercase block text-sm font-medium text-gray-900">Margen de Ganancia</label>
                    <input
                        type="number"
                        name="porcentaje"
                        id="porcentaje"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-none"
                        placeholder="Ingrese el porcentaje"
                        {{-- value="{{ old('porcentaje') }}" --}}
                        min="1"
                        max="30"
                        step="0.01"
                        value="30"
                    />
                </div>


                <div class="mt-2 mb-5">
                    <label for="nuevo_precio" class="uppercase block text-sm font-medium text-gray-900">Precio con 30%</label>
                    <input
                        type="text"
                        name="nuevo_precio"
                        id="nuevo_precio"
                        readonly
                        class="block w-full rounded-md bg-gray-100 px-3 py-1.5 text-base text-gray-900 outline-none"
                         min="{{ $producto->precio_venta }}"
                        value="{{ $producto->precio_venta * 1.3}}"
                    />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('productos.index') }}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const precioCosto = parseFloat("{{ $producto->precio_venta }}");
    const porcentajeInput = document.getElementById('porcentaje');
    const nuevoPrecioInput = document.getElementById('nuevo_precio');
    const form = document.getElementById('precioForm');

    // Variable para guardar el ultimo valor valido
    let ultimaValidacion = 30;

    // Calcular precio
    function calcularPrecio() {
        const porcentaje = parseFloat(porcentajeInput.value) || ultimaValidacion;
        const nuevoPrecio = precioCosto * (1 + (porcentaje / 100));
        nuevoPrecioInput.value = nuevoPrecio.toFixed(2);
        return nuevoPrecio;
    }

    // Evento para cambios en el porcentaje
    porcentajeInput.addEventListener('input', function() {
        // Guardar posicion del cursor
        const cursorPosition = this.selectionStart;

        // Si el campo esta vacio temporalmente durante la edicion, no hacer nada
        if (this.value === '') {
            return;
        }

        let porcentaje = parseFloat(this.value);

        // Si no es un numero valido, restaurar el ultimo valor valido
        if (isNaN(porcentaje)) {
            this.value = ultimaValidacion;
            return;
        }

        // Validar límite maximo
        if (porcentaje > 30) {
            porcentaje = 30;
            this.value = 30;
            ultimaValidacion = 30;
            Swal.fire({
                icon: 'warning',
                title: 'Límite alcanzado',
                text: 'El porcentaje no puede ser mayor al 30%.',
                timer: 2000,
                showConfirmButton: false
            });
        }
        // Validar limite minimo
        else if (porcentaje < 1) {
            porcentaje = 1;
            this.value = 1;
            ultimaValidacion = 1;
            Swal.fire({
                icon: 'warning',
                title: 'Porcentaje mínimo',
                text: 'El porcentaje mínimo permitido es 1%.',
                timer: 2000,
                showConfirmButton: false
            });
        }
        else {
            // Si el valor es valido, actualizar ultimaValidacion
            ultimaValidacion = porcentaje;
        }

        // Calcular nuevo precio
        calcularPrecio();

        // Restaurar posicion del cursor
        this.setSelectionRange(cursorPosition, cursorPosition);
    });

    // Evento cuando pierde el foco (al terminar de editar)
    porcentajeInput.addEventListener('blur', function() {
        if (this.value === '') {
            this.value = ultimaValidacion;
            calcularPrecio();
        }
    });

    // Validacion antes de enviar el formulario
    form.addEventListener('submit', function(e) {
        const porcentaje = parseFloat(porcentajeInput.value);

        if (isNaN(porcentaje) || porcentaje < 1 || porcentaje > 30) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El porcentaje debe estar entre 1% y 30%',
                confirmButtonText: 'Entendido'
            });
            porcentajeInput.value = ultimaValidacion;
            calcularPrecio();
        }
    });

    // Inicializar al cargar
    calcularPrecio();
});
</script>
@endpush
