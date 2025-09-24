@extends('template')
@section('titulo', 'Reporte de cambio de precio por producto')
@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('contenido')
<div class="container">

    <div class="mb-5">
               <a href="{{route('Reporte_ventas.index')}}" class="bg-blue-700 text-white font-bold p-3 rounded-md inline-block" >Volver</a>
    </div>

     <form action="{{ route('reporte.ProductoCambioPrecio') }}" method="POST" id="formReporte" class="space-y-4 sm:space-y-6 mb-5" >
        @csrf

        <!-- Filtros de búsqueda -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Filtro por producto -->
            <div>
                <x-select2
                    name="productos"
                    label="Filtrar por Producto (Opcional)"
                    :options="$productos->pluck('nombre', 'id')"
                    :selected="old('productos')"
                    placeholder="Todos los productos"
                />
            </div>
        </div>

        <!-- Título para rango de fechas -->
        <div class="flex items-center justify-start mb-2">
            <span class="text-sm font-medium text-gray-600">
                Filtrar por rango de fechas (Opcional)
            </span>
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
            <button type="button" id="btnGenerarInforme"
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
                @foreach($historico as $registro)
                    <tr>
                        <td class="px-6 py-4">{{ $registro->producto->nombre }}</td>
                        <td class="px-6 py-4">{{ number_format($registro->precio_anterior, 2) }}</td>
                        <td class="px-6 py-4">{{ number_format($registro->precio_nuevo, 2) }}</td>
                        <td class="px-6 py-4">{{ $registro->fecha_cambio }}</td>
                    </tr>

                @endforeach
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/js/select2-global.js"></script>

<script>
document.getElementById('btnGenerarInforme').addEventListener('click', async () => {
    const btn = document.getElementById('btnGenerarInforme');
    const originalText = btn.textContent;

    // Mostrar indicador de carga
    btn.disabled = true;
    btn.textContent = 'Cargando...';

    const productosSelect = document.getElementById('productos');
    const productos = productosSelect ? productosSelect.value : '';
    const fechaInicio = document.getElementById('fechaInicio').value;
    const fechaFin = document.getElementById('fechaFin').value;

    let url = '/reporte-cambioPrecio-informe?';
    if (productos) url += `productos=${productos}&`;
    if (fechaInicio) url += `fechaInicio=${fechaInicio}&`;
    if (fechaFin) url += `fechaFin=${fechaFin}&`;

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error('Error en la respuesta');
        const data = await response.json();

        // Verificar si DataTable ya está inicializado
        if ($.fn.DataTable.isDataTable('#example')) {
            // Si ya existe, limpiar los datos y agregar los nuevos
            const table = $('#example').DataTable();
            table.clear();

            // Agregar las nuevas filas
            data.forEach(registro => {
                table.row.add([
                    registro.producto.nombre,
                    parseFloat(registro.precio_anterior).toFixed(2),
                    parseFloat(registro.precio_nuevo).toFixed(2),
                    registro.fecha_cambio
                ]);
            });

            table.draw();
        } else {
            // Si no existe, crear el HTML y luego inicializar DataTable
            const rows = data.map(registro => `
                <tr>
                    <td class="px-6 py-4">${registro.producto.nombre}</td>
                    <td class="px-6 py-4">${parseFloat(registro.precio_anterior).toFixed(2)}</td>
                    <td class="px-6 py-4">${parseFloat(registro.precio_nuevo).toFixed(2)}</td>
                    <td class="px-6 py-4">${registro.fecha_cambio}</td>
                </tr>
            `).join('');

            const tbody = document.getElementById('tabla');
            tbody.innerHTML = rows;

            // Inicializar DataTable
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
        }

    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
    } finally {
        // Restaurar el botón
        btn.disabled = false;
        btn.textContent = originalText;
    }
});
</script>

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


@endpush
