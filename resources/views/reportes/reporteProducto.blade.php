@extends('template')
@section('titulo', 'Reporte de Productos')
@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3 ">


    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-7xl mb-10">

        <div class="lg:grid lg:grid-cols-2 lg:gap-5 sm:grid sm:grid-cols-1 sm:gap-5">
            <div class="border-b border-gray-900/10  lg:pb-0 lg:mb-0">
                <legend class="text-blue-500 font-bold">Reporte de productos</legend>

                <div class="mt-2 mb-5">
                    <label for="sucursal-select" class="uppercase block text-sm font-medium text-gray-900">Sucursal</label>
                    <select id="sucursal-select" class="form-select block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                        <option value="">-- Todas las sucursales --</option>
                        @foreach ($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                @error('id_sucursal')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                    <label for="fecha-input" class="uppercase block text-sm font-medium text-gray-900">Fecha</label>
                    <input type="date" id="fecha-input" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                </div>


                <div class="text-end mb-4">
                    <button id="btn-buscar" type="button" class="btn btn-success">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </div>

        </div>


        <!-- Tabla del reporte -->
        <div id="tabla-reporte">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sucursal</th>
                            <th>Producto</th>
                            <th>Semana</th>
                            <th>Valor económico del producto</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Seleccione una sucursal y/o fecha para ver los datos.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectSucursal = document.getElementById('sucursal-select');
        const fechaInput = document.getElementById('fecha-input');
        const btnBuscar = document.getElementById('btn-buscar');

        function getISOWeekNumber(date) {
            const target = new Date(date.valueOf());
            const dayNr = (date.getDay() + 7) % 7;
            target.setDate(target.getDate() - dayNr + 3);
            const firstThursday = new Date(target.getFullYear(), 0, 4);
            const diff = target - firstThursday;
            console.log(1 + Math.round(diff / (7 * 24 * 60 * 60 * 1000)));
            return 1 + Math.round(diff / (7 * 24 * 60 * 60 * 1000));
        }

        btnBuscar.addEventListener('click', function() {
            const sucursalId = selectSucursal.value;
            const fecha = fechaInput.value;

            const url = new URL('{{ route("inventario.reporte") }}');

            if (sucursalId) url.searchParams.append('sucursal_id', sucursalId);

            if (fecha) {
                const selectedDate = new Date(fecha);
                const semana = getISOWeekNumber(selectedDate);
                url.searchParams.append('semana', semana);
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Error al obtener los datos');
                    return response.json();
                })
                .then(data => {
                    let html = `
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sucursal</th>
                                    <th>Producto</th>
                                    <th>Semana</th>
                                    <th>Valor económico del producto</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>`;

                    if (data.length === 0) {
                        html += `<tr><td colspan="4">No hay datos disponibles.</td></tr>`;
                    } else {
                        data.forEach(fila => {
                            html += `<tr>
                                <td>${fila.sucursal}</td>
                                <td>${fila.producto}</td>
                                <td>Semana ${fila.semana}</td>
                                <td>Q ${parseFloat(fila.valor_total_producto).toFixed(2)}</td>
                                <td>${fila.cantidad_disponible}</td>
                            </tr>`;
                        });
                    }

                    html += `</tbody></table>`;
                    document.getElementById('tabla-reporte').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error en la petición:', error);
                    Swal.fire('Error', 'Ocurrió un error al cargar los datos.', 'error');
                });
        });
    });
</script>
@endpush