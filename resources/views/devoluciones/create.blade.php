@extends('template')
@section('titulo', 'Devoluciones')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .error-message {
        color: red;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
@endpush

@section('contenido')

<div class="flex justify-center items-center mx-3">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl mb-10 p-5">
        <form action="{{ route('devoluciones.store') }}" method="POST">
            @csrf
            <div class="border-b border-gray-900/10 pb-12">
                <div id="usuario"></div>

                <div class="mt-2 mb-5">
                    <label for="id_venta" class="uppercase block text-sm font-medium text-gray-900">numero de la venta</label>
                    <select name="id_venta" id="id_venta"
                        class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                        <option value="">seleccionar la venta</option>
                        @foreach($ventas as $venta)
                        <option value="{{$venta->id}}">{{$venta->id}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-2 mb-5">
                    <label for="producto" class="uppercase block text-sm font-medium text-gray-900">producto</label>
                    <select name="producto" id="producto"
                        class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                        <option value="">seleccionar el producto</option>

                    </select>
                </div>

                <div class="mt-2 mb-5">
                    <label for="cantidad" class="uppercase block text-sm font-medium text-gray-900">cantidad</label>
                    <input type="number" name="cantidad" id="cantidad"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                </div>

                <div class="mt-2 mb-5">
                    <label for="monto" class="uppercase block text-sm font-medium text-gray-900">monto</label>
                    <input type="number" name="monto" id="monto"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                </div>

                <div class="mt-2 mb-5">
                    <label for="motivo" class="uppercase block text-sm font-medium text-gray-900">motivo</label>
                    <textarea type="text" name="motivo" id="motivo"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                    </textarea>
                </div>



                <div class="mt-2 mb-5">
                    <label for="observaciones" class="uppercase block text-sm font-medium text-gray-900">observaciones</label>

                    <textarea name="observaciones" id="observaciones"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                    </textarea>
                </div>

                <div class="mt-2 mb-5">
                    <label for="usuario" class="uppercase block text-sm font-medium text-gray-900">persona</label>
                    <select name="usuario" id="usuario"
                        class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">>
                        <option value="">seleccionar un persona</option>

                    </select>
                </div>

                <div class="mt-2 mb-5">
                    <label for="sucursal" class="uppercase block text-sm font-medium text-gray-900">sucursal</label>
                    <select name="sucursal" id="sucursal"
                        class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">>
                        <option value="">seleccionar una sucursal</option>

                    </select>
                </div>

                <div class="mt-2 mb-5">
                    <label for="producto_vencido" class="uppercase block text-sm font-medium text-gray-900 mb-1">¿Producto vencido?</label>
                    <input type="checkbox" id="producto_vencido" class="toggle toggle-warning">
                </div>

                <div id="fecha_vencimiento_container" class="mt-2 mb-5 hidden">
                    <label for="fecha_caducidad" class="uppercase block text-sm font-medium text-gray-900">Fecha de Vencimiento</label>
                    <input
                        type="date"
                        name="fecha_caducidad"
                        id="fecha_caducidad"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('fecha_caducidad') }}">
                </div>


            </div>
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 m-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>

        </form>
    </div>
</div>



@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const vencidoCheckbox = document.getElementById('producto_vencido');
    const fechaContainer = document.getElementById('fecha_vencimiento_container');

    vencidoCheckbox.addEventListener('change', () => {
        if (vencidoCheckbox.checked) {
            fechaContainer.classList.remove('hidden');
        } else {
            fechaContainer.classList.add('hidden');
        }
    });
</script>
{{-- select2 de productos y sucursales --}}
<script>
    //uso del select2 para proveedores
    $(document).ready(function() {
        $('.select2-sucursal').select2({
            width: '100%',
            placeholder: "Buscar",
            allowClear: true
        });
        // pocicionar el cursor en el input para buscar producto
        $('.select2-sucursal').on('select2-sucursal:open', function() {
            document.querySelector('.select2-search__field').focus();
        });
    });
</script>



@if(session('error'))
<div class="alert-message">
    <span>{{ session('error') }}</span>
</div>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const errorMessage = document.querySelector('.alert-message span').textContent;
        if (errorMessage) {
            alert(errorMessage);
        }
    });
</script>

<script>
    var $selectProductos = document.getElementById('producto');
var $inputCantidad = document.getElementById('cantidad');
var productosVenta = {}; // Objeto para guardar la info de los productos
var $selectSucursal = document.getElementById('sucursal');
var $selectUsuario = document.getElementById('usuario');

$(document).ready(function() {
    $('#id_venta').change(function() {
        var ventaId = $(this).val();

        if (ventaId) {
            $.ajax({
                url: '/ventas-devoluciones/' + ventaId,
                type: 'GET',
                success: function(data) {
                    console.log(data); // Verifica la respuesta
                    // Limpia el select de productos
                    $selectProductos.innerHTML = '<option value="">Seleccionar el producto</option>';
                    productosVenta = {}; // Limpia productosVenta

                    // Agrega los productos al select y guarda su info
                    data.detalles.forEach(function(detalle) {
                        var option = document.createElement('option');
                        option.value = detalle.id_producto;
                        option.textContent = detalle.producto.nombre;
                        $selectProductos.appendChild(option);

                        // Guarda la cantidad por id_producto
                        productosVenta[detalle.id_producto] = detalle.cantidad;
                    });

                    // Agrega los usuarios al select
                    $selectUsuario.innerHTML = '<option value="">Seleccionar un usuario</option>';
                    data.usuarios.forEach(function(usuario) {
                        var option = document.createElement('option');
                        option.value = usuario.id;
                        option.textContent = usuario.nombre;
                        $selectUsuario.appendChild(option);
                    });

                    // Agrega las sucursales al select
                    $selectSucursal.innerHTML = '<option value="">Seleccionar una sucursal</option>';
                    data.sucursales.forEach(function(sucursal) {
                        var option = document.createElement('option');
                        option.value = sucursal.id;
                        option.textContent = sucursal.nombre;
                        $selectSucursal.appendChild(option);
                    });
                },
                error: function() {
                    alert('Error al obtener los datos de la venta.');
                }
            });
        }
    });

    // Cuando se seleccione un producto
    $selectProductos.addEventListener('change', function() {
        var productoId = this.value;

        if (productoId && productosVenta[productoId] !== undefined) {
            $inputCantidad.value = productosVenta[productoId];
        } else {
            $inputCantidad.value = '';
        }
    });
});

</script>
@endpush