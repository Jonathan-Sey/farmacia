@extends('template')
@section('titulo', 'Reporte de cambio de precio por producto')
@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')
<div class="container">

     <form id="formReporte" class="space-y-4 sm:space-y-6 mb-5" >
        @csrf       
        <div class="mb-5">
               <a href="{{route('Reporte_ventas.index')}}" class="bg-blue-700 text-white font-bold p-3 rounded-md inline-block" >Volver</a>
        </div>

        <!-- Toggle para alternar modo de búsqueda -->
        <div class="flex flex-row gap-5">
            <div class="flex flex-col gap-1">
                <label for="tipo">Buscar Producto</label>
                <input name="tipo" id="tipo" type="checkbox" class="toggle toggle-success"
                {{ old('tipo') ? 'checked' : '' }}
                     />
            </div>
        </div>

        <div id="productos">
            <label for="productos" class="block text-sm font-medium text-gray-600">Buscar producto:</label>
                <select name="productos" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="" disabled selected>Seleccione producto</option>
                    @foreach($productos as $producto)
                    <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                    @endforeach
                </select>

        </div>

        <!-- Toggle para alternar modo de búsqueda -->
        <div class="flex items-center justify-center sm:justify-start mb-4">
            <div class="flex flex-row items-center gap-2 justify-center ">
                 <span class="ml-3 text-sm font-medium text-gray-600 select-none">
                        Buscar por rango de fechas
                 </span>
            </div>
        </div>
        <!-- Campos para el rang de fechas -->
        <div id="camposRango" class=" flex flex-col gap-2 md:flex-row ">
            <div class="flex-1 mb-4 sm:mb-0">
                <label for="fechaInicio" class="block text-sm font-medium text-gray-600 mb-1">Desde:</label>
                <input type="date" id="fechaInicio" name="fechaInicio" value="{{ old('fechaInicio') }}"
                    class="w-full p-2 sm:p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-300 transition-all duration-200 text-sm sm:text-base">
            </div>

            <div class="flex-1 mb-4 sm:mb-0">
                <label for="fechaFin" class="block text-sm font-medium text-gray-600 mb-1">Hasta:</label>
                <input type="date" id="fechaFin" name="fechaFin" value="{{ old('fechaFin') }}"
                    class="w-full p-2 sm:p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-300 transition-all duration-200 text-sm sm:text-base">
            </div>
        </div>

        <!-- Botón -->
        <div class=" flex flex-col gap-5 md:flex-row justify-end">
            <button type="submit" id="btnGenerarInforme"
                class="w-full sm:w-auto px-6 py-3 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 focus:bg-green-600 focus:ring-4 focus:ring-green-200 transition-all duration-200 font-medium text-sm sm:text-base">
                Generar Informe
            </button>
        </div>
    </form>
    
    <x-data-table id="tabla-reporte">
        <x-slot name="thead">
            <thead class="text-white font-bold">
                <tr class="bg-slate-600">
                    <th class="px-6 py-3 text-left">Producto</th>
                    <th class="px-6 py-3 text-left">Precio Anterior</th>
                    <th class="px-6 py-3 text-left">Precio Nuevo</th>
                    <th class="px-6 py-3 text-left">Fecha de Cambio</th>
                </tr>
            </thead>
        </x-slot>
        <x-slot name="tbody">
            <tbody id="tabla">
                @forelse($historico as $registro)
                    <tr>
                        <td class="px-6 py-4">{{ $registro->producto->nombre }}</td>
                        <td class="px-6 py-4">{{ number_format($registro->precio_anterior, 2) }}</td>
                        <td class="px-6 py-4">{{ number_format($registro->precio_nuevo, 2) }}</td>
                        <td class="px-6 py-4">{{ $registro->fecha_cambio }}</td>
                    </tr>
                    @empty
                    <td class="px-6 py-4">No se encuentran registros</td>

                @endforelse
            </tbody>
        </x-slot>
    </x-data-table>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            order: [[3, 'desc']],
            language: {
                url: '/js/i18n/Spanish.json',
            },
            layout: {
                topStart: {

                    buttons: [
                        {
                            extend: 'collection',
                        text: 'Export',
                        buttons: ['copy', 'pdf', 'excel', 'print']
                        },
                        'colvis'
                    ]
                }
            },
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: 3 }
            ],
            drawCallback: function() {
                setTimeout(function() {
                    $('a.paginate_button').addClass('btn btn-sm btn-primary mx-1');
                    $('a.paginate_button.current').removeClass('btn-gray-800').addClass('btn btn-sm btn-primary');
                }, 100);
            },
        });



    });
</script>

<script>
    $(document).ready(function (){
        // asignamos un evneto de tipo submit al formulario
        // al accionarse mandamos los datos de los inputs fecha, producto
        $('#formReporte').on('submit', function(e){
            e.preventDefault();
            // obtenemos el valor de los inputs 
            const formaData = {
                producto: $('#productos').val(), 
                fechaInicio: $('#fechaInicio').val(),
                fechaFin: $('#fechaFin').val(),
                _token: $('input[name="_token"]').val(),
            };

            $.ajax({
                url: '{{ route("reporte.fechaCambioPrecio")}}',
                method: 'POST',
                data: formaData,
                success: function (response){
                    dataTable.destroy();
                    $('#tabla').html(response.html);

                    dataTable = $('#tabla-reporte').DataTable({
                        responsive: true,
                        order: [[3, 'desc']],
                        language: {
                            url: '/js/i18n/Spanish.json',
                        },
                        layout: {
                            topStart: {

                                buttons: [
                                    {
                                        extend: 'collection',
                                    text: 'Export',
                                    buttons: ['copy', 'pdf', 'excel', 'print']
                                    },
                                    'colvis'
                                ]
                            }
                        },
                        columnDefs: [
                            { responsivePriority: 1, targets: 0 },
                            { responsivePriority: 2, targets: 3 }
                        ]

                    })
                },
                error:function(){
                    alerta('error al obtener los datos.')
                }

            });
            
        })


    })
</script>

{{-- proceso para ocultar y mostrar los datos  --}}
<script>
    document.addEventListener('DOMContentLoaded', function (){
        const toggle = document.getElementById('tipo'); // selecionamos el toggle
        const inputProducto = document.getElementById('productos');
        const campoRangos = document.getElementById('camposRango')

        function mostrarInputs(){
            if(toggle.checked){
                campoRangos.classList.remove('hidden');
                inputProducto.classList.remove('hidden');
            }else{
                inputProducto.classList.add('hidden');
                campoRangos.classList.remove('hidden');
            }
        }

        mostrarInputs();

        toggle.addEventListener('change', mostrarInputs);
        
    });
</script>
@endpush