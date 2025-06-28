@extends('template')
@section('titulo', 'Reporte de Productos')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">

<style>
    .dt-buttons .dt-button {
        @apply bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-200;
        margin-right: 8px;
    }

    .dt-buttons {
        flex-wrap: wrap !important;
        gap: 0.5rem;
    }

    .dt-buttons .dt-button:last-child {
        margin-right: 0;
    }

    #reporte-productos-table thead {
        background-color: rgb(54, 54, 54);
    }

    #reporte-productos-table th {
        color: white;
    }

    .dataTables_filter {
        margin-left: auto;
    }

    .dataTables_filter label {
        font-weight: 500;
        color: #374151;
        /* Tailwind text-gray-700 */
    }

    .dataTables_filter input {
        border: 1px solid #d1d5db;
        padding: 0.5rem;
        border-radius: 0.375rem;
        outline: none;
    }

    .dataTables_filter {
        margin: 0 !important;
    }
</style>
@endpush

@section('contenido')
<div class="flex justify-center items-center min-h-screen px-4">
    <div class="w-full max-w-xl bg-white p-6 rounded-xl shadow-lg">
        <div class="grid grid-cols-1">
            <div>
                <h2 class="text-xl font-bold text-blue-600 mb-4 text-center">Reporte de productos</h2>

                <div class="mb-4">
                    <label for="sucursal-select" class="block text-sm font-semibold text-gray-700 mb-1">Sucursal</label>
                    <select id="sucursal-select" class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Todas las sucursales --</option>
                        @foreach ($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_sucursal')
                    <div class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="fecha-input" class="block text-sm font-semibold text-gray-700 mb-1">Fecha</label>
                    <input type="date" id="fecha-input" class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="text-center">
                    <button id="btn-buscar" type="button" class=" btn btn-success inline-flex items-center justify-center bg-green-600 text-white px-4 py-2 rounded-md shadow hover:bg-green-700 focus:outline-none transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21l-2-2h4l-2 2zm7-7a7 7 0 11-14 0 7 7 0 0114 0zm0 0l3 3" />
                        </svg>
                        Buscar
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla del reporte -->
        <div class="mt-6 overflow-auto">
            <table id="reporte-productos-table" class="min-w-full table-auto divide-y divide-gray-200 text-sm">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Sucursal</th>
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Semana</th>
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Valor económico del producto</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- DataTables poblará esto dinámicamente --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
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
    let dataTable = null;

    document.addEventListener('DOMContentLoaded', function() {
        const selectSucursal = document.getElementById('sucursal-select');
        const fechaInput = document.getElementById('fecha-input');
        const btnBuscar = document.getElementById('btn-buscar');

        function getISOWeekNumber(date) {
            const target = new Date(date.valueOf());
            const dayNr = (date.getDay() + 6) % 7;
            target.setDate(target.getDate() - dayNr + 3);
            const firstThursday = new Date(target.getFullYear(), 0, 4);
            const diff = target - firstThursday;
            return 1 + Math.round(diff / (7 * 24 * 60 * 60 * 1000));
        }

        function initializeDataTable(data = []) {
            if (dataTable) {
                dataTable.destroy();
                $('#reporte-productos-table tbody').empty();
                dataTable = null;
            }

            dataTable = $('#reporte-productos-table').DataTable({
                responsive: true,
                autoWidth: false,
                dom: "<'flex gap-2 mb-2 px-2 flex-col lg:flex-row lg:items-center lg:justify-between'<'dt-buttons flex flex-wrap gap-2'B><'flex justify-end'f>>rtip",
                data: data,
                columns: [{
                        data: 'sucursal'
                    },
                    {
                        data: 'producto'
                    },
                    {
                        data: 'semana',
                        render: function(data) {
                            return `Semana ${data}`;
                        }
                    },
                    {
                        data: 'valor_total_producto',
                        render: function(data) {
                            return `Q ${parseFloat(data).toFixed(2)}`;
                        }
                    }
                ],
                buttons: [{
                        extend: 'copyHtml5',
                        text: '<i class="bi bi-files"></i> Copiar',
                        titleAttr: 'Copiar al portapapeles',
                        className: 'btn btn-secondary',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                        titleAttr: 'Exportar a Excel',
                        className: 'btn btn-success',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                        titleAttr: 'Exportar a PDF',
                        className: 'btn btn-danger',
                        orientation: 'landscape',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer"></i> Imprimir',
                        titleAttr: 'Imprimir',
                        className: 'btn btn-info',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-eye"></i> Columnas',
                        titleAttr: 'Visibilidad de columnas',
                        className: 'btn btn-light'
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json'
                }
            });

            if (data.length === 0) {
                $('#reporte-productos-table tbody').append(`<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Seleccione una sucursal y/o fecha para ver los datos.</td></tr>`);
            }
        }

        btnBuscar.addEventListener('click', function() {
            const sucursalId = selectSucursal.value;
            const fecha = fechaInput.value;
            const url = new URL('{{ route("inventario.reporte") }}');

            if (sucursalId) url.searchParams.append('sucursal_id', sucursalId);
            if (fecha) {
                const selectedDate = new Date(fecha + 'T00:00:00');
                const semana = getISOWeekNumber(selectedDate);
                url.searchParams.append('semana', semana);
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    initializeDataTable(data);
                })
                .catch(error => {
                    console.error('Error al cargar los datos:', error);
                    Swal.fire('Error', 'Ocurrió un error al cargar los datos: ' + error.message, 'error');
                    initializeDataTable([]);
                    $('#reporte-productos-table tbody').append(`<tr><td colspan="4" class="px-6 py-4 text-center text-red-500">Error al cargar los datos: ${error.message}</td></tr>`);
                });
        });

        initializeDataTable([]);
    });
</script>
@endpush