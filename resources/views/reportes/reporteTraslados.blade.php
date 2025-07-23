@extends('template')

@section('titulo', 'Reporte de Traslados')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">

<style>
    /* Estilos adicionales */
    .dataTables_empty {
        text-align: center !important;
    }
</style>
@endpush

@section('contenido')
<div class="max-w-7xl mx-auto p-4 mb-6 bg-white rounded-lg shadow-md">
    <div class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label for="sucursal-select" class="block text-sm font-medium text-gray-700">Sucursal:</label>
            <select id="sucursal-select" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                <option value="">-- Todas las sucursales --</option>
                @foreach ($sucursales as $sucursal)
                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label for="fecha-input" class="block text-sm font-medium text-gray-700">Fecha:</label>
            <input type="date" id="fecha-input" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>
        <div class="flex justify-center items-center">
            <button id="btn-buscar" class="btn btn-primary px-3 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring focus:ring-blue-300">
                Generar Reporte
            </button>
        </div>
    </div>
</div>

<x-data-table>
    <x-slot name="thead">
        <thead class="text-white font-bold">
            <tr class="bg-slate-600">
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Origen</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Destino</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Producto</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Cantidad</th>
                <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">Semana</th>
            </tr>
        </thead>
    </x-slot>

    <x-slot name="tbody">
        <tbody id="tabla">
            <!-- DataTables llenará esto automáticamente -->
        </tbody>
    </x-slot>
</x-data-table>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>

{{-- botones --}}
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js">
    //botones en general
</script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js">
    //imprimir
</script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.colVis.min.js">
    //fltrar columnas
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js">
    //pdf
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js">
    //copiar
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js">
    //excel
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectSucursal = document.getElementById('sucursal-select');
    const fechaInput = document.getElementById('fecha-input');
    const btnBuscar = document.getElementById('btn-buscar');

    // Inicializar DataTable
    const tablaReporte = $('#example').DataTable({
        responsive: true,
        language: {
            url: '/js/i18n/Spanish.json',
            emptyTable: "Seleccione una sucursal y/o fecha para ver los datos.",
            paginate: {
                first: `<i class="fa-solid fa-backward"></i>`,
                previous: `<i class="fa-solid fa-caret-left">`,
                next: `<i class="fa-solid fa-caret-right"></i>`,
                last: `<i class="fa-solid fa-forward"></i>`
            }
        },
        dom: '<"top"Bfrtip<"clear">',
        buttons: [
            {
                extend: 'collection',
                text: 'Exportar',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            },
            'colvis'
        ],
        columns: [
            { data: 'sucursal_origen', name: 'origen' },
            { data: 'sucursal_destino', name: 'destino' },
            { data: 'nombre_producto', name: 'producto' },
            { data: 'cantidad', name: 'cantidad' },
            {
                data: 'semana',
                name: 'semana',
                render: function(data) {
                    return 'Semana ' + data;
                }
            }
        ],
        columnDefs: [
            { responsivePriority: 1, targets: 0 }, // Origen
            { responsivePriority: 2, targets: 1 }, // Destino
            { responsivePriority: 3, targets: 2 }, // Producto
            { className: "dt-center", targets: [3, 4] } // Centrar cantidad y semana
        ],
        initComplete: function() {
            // Estilizar botones
            $('.dt-buttons button').addClass('btn btn-sm btn-primary mx-1');
        },
        drawCallback: function() {
            // Estilizar paginación
            $('.paginate_button').addClass('btn btn-sm btn-primary mx-1');
            $('.paginate_button.current').addClass('btn-active');
        }
    });

    function getISOWeekNumber(date) {
        const target = new Date(date.valueOf());
        const dayNr = (date.getDay() + 6) % 7;
        target.setDate(target.getDate() - dayNr + 3);
        const firstThursday = new Date(target.getFullYear(), 0, 4);
        const diff = target - firstThursday;
        return 1 + Math.round(diff / (7 * 24 * 60 * 60 * 1000));
    }

    btnBuscar.addEventListener('click', () => {
        const sucursalId = selectSucursal.value;
        const fecha = fechaInput.value;
        const url = new URL('{{ route("reporte.traslado.datos") }}');

        if (sucursalId) url.searchParams.append('sucursal_id', sucursalId);
        if (fecha) {
            const selectedDate = new Date(fecha);
            url.searchParams.append('semana', getISOWeekNumber(selectedDate));
            url.searchParams.append('fecha', fecha);
        }

        // Mostrar carga
        tablaReporte.processing(true);

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Error al obtener los datos');
                return response.json();
            })
            .then(data => {
                // Limpiar y cargar datos
                tablaReporte.clear();
                if (data && data.length > 0) {
                    tablaReporte.rows.add(data).draw();
                } else {
                    // Mostrar mensaje de "no hay datos" usando emptyTable
                    tablaReporte.clear().draw();
                }
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                Swal.fire('Error', 'Ocurrió un error al cargar los datos.', 'error');
                tablaReporte.clear().draw(); // Asegurar que la tabla se redibuje
            })
            .finally(() => {
                tablaReporte.processing(false);
            });
    });
});
</script>
@endpush