@extends('template')

@section('titulo', 'Reporte de Traslados')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">


@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-7xl mb-10">
        <div class="mb-6">
            <h2 class="text-base font-bold text-blue-600 mb-4">Reporte de Traslados</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="sucursal-select" class="block text-sm font-medium text-gray-700 mb-1">Sucursal</label>
                    <select id="sucursal-select" class="form-select w-full rounded-md border border-gray-300 px-3 py-2">
                        <option value="">-- Todas las sucursales --</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="fecha-input" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input type="date" id="fecha-input" class="form-input w-full rounded-md border border-gray-300 px-3 py-2">
                </div>
            </div>

            <div class="mt-4 text-right">
                <button id="btn-buscar"
                type="button"
                class="px-4 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-700 transition">
                Generar Reporte
            </button>
            </div>
        </div>

        <!-- Tabla del reporte -->
        <div id="tabla-reporte" class="table-responsive">
            <table id="tabla-reporte-table" class="table table-striped table-bordered nowrap w-full">
                <thead>
                    <tr>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Semana</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Seleccione una sucursal y/o fecha para ver los datos.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- Librerías -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectSucursal = document.getElementById('sucursal-select');
    const fechaInput = document.getElementById('fecha-input');
    const btnBuscar = document.getElementById('btn-buscar');

    const tablaReporte = $('#tabla-reporte-table').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf', 'print'],
        language: {
            url: '/js/i18n/Spanish.json'
        },
        data: [],
        columns: [
            { data: 'sucursal_origen' },
            { data: 'sucursal_destino' },
            { data: 'nombre_producto' },
            { data: 'cantidad' },
            { data: 'semana', render: data => 'Semana ' + data },
        ]
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
        }

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Error al obtener los datos');
                return response.json();
            })
            .then(data => {
                tablaReporte.clear().rows.add(data).draw();
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                Swal.fire('Error', 'Ocurrió un error al cargar los datos.', 'error');
            });
    });
});
</script>
@endpush