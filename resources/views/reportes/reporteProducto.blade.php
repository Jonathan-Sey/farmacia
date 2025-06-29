@extends('template')
@section('titulo', 'Reporte de Productos')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@section('contenido')

<div class="max-w-5xl mx-auto p-4 mb-6 bg-white rounded-lg shadow-md">
    <div  class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label for="select-sucursal" class="block text-sm font-medium text-gray-700">Sucursal:</label>
            <select id="select-sucursal" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                <option value="">Todas</option>
                @foreach ($sucursales as $sucursal)
                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha:</label>
            <input type="date" id="fecha" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
        </div>
        <div class="flex-none">
            <button id="btn-buscar" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300">
                Buscar
            </button>
        </div>
</div>
</div>
<x-data-table>
    <x-slot name="thead">
        <thead class="text-white font-bold">
            <tr class="bg-slate-600">
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Sucursal</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Producto</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Cantidad</th>
                <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">Semana</th>
                <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">Valor económico del producto</th>
            </tr>
        </thead>
    </x-slot>

    <x-slot name="tbody">
        <tbody id="tabla">

        </tbody>
    </x-slot>

</x-data-table>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Responsive -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<!-- Buttons extensions -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<!-- Required libraries for buttons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    $('#example').DataTable({
        responsive: true,
        order: [5,'desc'],
        language: {
                url: '/js/i18n/Spanish.json',
                 paginate: {
                     first: `<i class="fa-solid fa-backward"></i>`,
                     previous: `<i class="fa-solid fa-caret-left">`,
                     next: `<i class="fa-solid fa-caret-right"></i>`,
                     last: `<i class="fa-solid fa-forward"></i>`
                 }
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
            { responsivePriority: 3, targets: 0 },
            { responsivePriority: 1, targets: 1 },
            { responsivePriority: 2, targets: 3 },
        ],
        drawCallback: function() {
            // Esperar un momento para asegurarse de que los botones se hayan cargado
            setTimeout(function() {
                // Seleccionar los botones de paginación y agregar clases de DaisyUI
                $('a.paginate_button').addClass('btn btn-sm btn-primary mx-1'); // Todos los botones
                $('a.paginate_button.current').removeClass('btn-gray-800').addClass('btn btn-sm btn-primary'); // Resaltar la página actual
            }, 100); // Espera 100 ms antes de aplicar las clases
        },
    });
});
</script>

<script>

     const btnBuscar = document.getElementById('btn-buscar');
        const selectSucursal = document.getElementById('select-sucursal');
        const fechaInput = document.getElementById('fecha');
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
                    console.log(data);
                    const tbody = $('#tabla');
                    tbody.empty();
                    if (data.length) {
                        data.forEach(item => {
                            tbody.append(
                                `<tr>
                                    <td>${item.sucursal}</td>
                                    <td>${item.producto}</td>
                                    <td>${item.cantidad_total}</td>
                                    <td>${item.semana}</td>
                                    <td>${item.valor_economico}</td>
                                </tr>`
                            );
                        });
                    } else {
                        tbody.append(
                            `<tr><td colspan="5" class="text-center">No hay datos para mostrar</td></tr>`
                        );
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los datos:', error);
                    Swal.fire('Error', 'Ocurrió un error al cargar los datos: ' + error.message, 'error');
                   
                });
        });

    
</script>


@endpush