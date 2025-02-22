@extends('template')
@section('titulo', 'Crear Traslado')
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('almacenes.store') }}" method="POST">
            @csrf
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-2 mb-5">
                    <label for="id_sucursal_origen" class="uppercase block text-sm font-medium text-gray-900">Sucursal Origen</label>
                    <select class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            name="id_sucursal_origen" id="id_sucursal_origen" required>
                        <option value="">Seleccionar sucursal</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-2 mb-5">
                    <label for="id_sucursal_destino" class="uppercase block text-sm font-medium text-gray-900">Sucursal Destino</label>
                    <select class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            name="id_sucursal_destino" id="id_sucursal_destino" required>
                        <option value="">Seleccionar sucursal</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-2 mb-5">
                    <label for="id_producto" class="uppercase block text-sm font-medium text-gray-900">Producto</label>
                    <select class="select2-producto block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            name="id_producto" id="id_producto" required>
                        <option value="">Seleccionar producto</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-2 mb-5">
                    <label for="id_lote" class="uppercase block text-sm font-medium text-gray-900">Lote</label>
                    <select class="select2-lote block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            name="id_lote" id="id_lote" required>
                        <option value="">Seleccionar lote</option>
                        <!-- Los lotes se cargarán dinámicamente con JavaScript -->
                    </select>
                </div>

                <div class="mt-2 mb-5">
                    <label for="cantidad" class="uppercase block text-sm font-medium text-gray-900">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" min="1" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" required>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('almacenes.index') }}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-sucursal, .select2-producto, .select2-lote').select2();

        // Cargar lotes dinámicamente al seleccionar un producto
        $('#id_producto').change(function() {
            var idProducto = $(this).val();
            if (idProducto) {
                $.ajax({
                    url: '/get-lotes/' + idProducto,
                    type: 'GET',
                    success: function(response) {
                        $('#id_lote').empty();
                        $('#id_lote').append('<option value="">Seleccionar lote</option>');

                        response.forEach(function(lote) {
                            $('#id_lote').append(`
                                <option value="${lote.id}">
                                    ${lote.numero_lote} (Cantidad: ${lote.cantidad})
                                </option>
                            `);
                        });
                        console.log("Lotes cargados:", response);
                    },
                    error: function() {
                        alert('Error al cargar los lotes');
                    }
                });
            } else {
                $('#id_lote').empty();
                $('#id_lote').append('<option value="">Seleccionar lote</option>');
            }
        });
    });
</script>


<script>
    //uso del select2 para proveedores
    $(document).ready(function(){
        $('.select2-producto').select2({
            width: '100%',
            placeholder: "Buscar producto",
            allowClear: true
        });
    // pocicionar el cursor en el input para buscar producto
    $('.select2-producto').on('select2-producto:open', function() {
    document.querySelector('.select2-search__field').focus();
    });

    // //uso del select2 para proveedores
    //     $('.select2-sucursal').select2({
    //         width: '100%',
    //         placeholder: "Buscar sucursal",
    //         allowClear: true
    //     });
    // // pocicionar el cursor en el input para buscar producto
    // $('.select2-sucursal').on('select2-sucursal:open', function() {
    // document.querySelector('.select2-search__field').focus();
    // });
});

</script>
@endpush
@endsection


{{-- @push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}




    {{-- <script>
        //uso del select2 para proveedores
        $(document).ready(function(){
            $('.select2-producto').select2({
                width: '100%',
                placeholder: "Buscar producto",
                allowClear: true
            });
        // pocicionar el cursor en el input para buscar producto
        $('.select2-producto').on('select2-producto:open', function() {
        document.querySelector('.select2-search__field').focus();
        });

        // //uso del select2 para proveedores
        //     $('.select2-sucursal').select2({
        //         width: '100%',
        //         placeholder: "Buscar sucursal",
        //         allowClear: true
        //     });
        // // pocicionar el cursor en el input para buscar producto
        // $('.select2-sucursal').on('select2-sucursal:open', function() {
        // document.querySelector('.select2-search__field').focus();
        // });
    });

    </script> --}}
{{--

    <script>
        // limitar la fecha a datos actuales
        document.addEventListener('DOMContentLoaded', function(){
            var DatoActual = new Date().toISOString().split('T')[0];
            document.getElementById('fecha_caducidad').setAttribute('min', DatoActual);

        });

    </script> --}}

{{-- @endpush --}}

