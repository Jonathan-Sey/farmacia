@extends('template')
@section('titulo', 'Reporte de ventas por sucursal')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')



<div class="max-w-5xl mx-auto p-6 m-5 bg-white rounded-lg shadow-md">

    <form id="formReporte" class="space-y-4">
        <!-- Fila Día, Mes, Año -->
        <!--<div class="flex gap-4">
            <div class="flex-1 m-2">
                <label for="fecha" class="block text-sm font-medium text-gray-600 ">Día:</label>
                <input type="date" id="fecha" name="fecha"
                    class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <div class="flex-1 m-2">
                <label for="mes" class="block text-sm font-medium text-gray-600">Mes:</label>
                <input type="month" id="mes" name="mes"
                    class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <div class="flex-1 m-2">
                <label for="año" class="block text-sm font-medium text-gray-600">Año:</label>
                <input type="number" id="año" name="año" min="2000" max="2100"
                    class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
            </div>
        </div>


        <div class="flex gap-4">
            <div class="flex-1 m-2">
                <label for="fechaInicio" class="block text-sm font-medium text-gray-600">Desde:</label>
                <input type="date" id="fechaInicio" name="fechaInicio"
                    class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <div class="flex-1 m-2">
                <label for="fechaFin" class="block text-sm font-medium text-gray-600">Hasta:</label>
                <input type="date" id="fechaFin" name="fechaFin"
                    class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
            </div>
        </div>-->

        <div>
            <div class="max-w-5xl mx-auto p-6 m-5 bg-white rounded-lg shadow-md">
                <label for="sucursal" class="block text-sm font-medium text-gray-600">Sucursal:</label>
                <select id="sucursal" name="sucursal" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="" disabled selected>Seleccione una sucursal</option>
                    @foreach($sucursales as $sucursal)
                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <button type="button" id="btnGenerarInforme"
                class="px-4 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-700 transition">
                Generar Informe
            </button>
        </div>
    </form>
</div>





<!-- Aquí se insertará la tabla dinámica -->
<x-data-table>
    <x-slot name="thead">
        <thead class="text-white font-bold">
            <tr class="bg-slate-600">
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Código</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">producto</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">cantidad</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">precio</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">impuesto</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">total</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">usuario</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">persona</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">fecha</th>

            </tr>
        </thead>
    </x-slot>

    <x-slot name="tbody">
        <tbody id="tabla">
            <!-- Aquí se insertarán las filas de la tabla -->
        </tbody>
    </x-slot>
</x-data-table>


@push('js')





<script>
    document.getElementById('btnGenerarInforme').addEventListener('click', async () => {
        // Obtener valores de los inputs
        const sucursal = document.getElementById('sucursal').value;
        console.log(sucursal);
        /* const mes = document.getElementById('mes').value;
         const año = document.getElementById('año').value;
         const fechaInicio = document.getElementById('fechaInicio').value;
         const fechaFin = document.getElementById('fechaFin').value;*/

        // Construir URL
        let url = '/ventas-informe/sucursal?';
        if (sucursal) url += `sucursal=${sucursal}&`;

        try {
            // Fetch data
            const response = await fetch(url);
            if (!response.ok) throw new Error('Error en la respuesta');
            const data = await response.json();

            // Generar HTML de las filas
            const rows = data.map(venta => `
            <tr>
                <td class="px-6 py-3">${venta.venta_id}</td>
                <td class="px-6 py-3">${venta.nombre_producto || 'N/A'}</td>
                <td class="px-6 py-3">${venta.cantidad}</td>
                <td class="px-6 py-3">${venta.precio}</td>
                <td class="px-6 py-3">${venta.impuesto}</td>
                <td class="px-6 py-3">${venta.subtotal}</td>
                <td class="px-6 py-3">${venta.nombre_usuario}</td>
                <td class="px-6 py-3">${venta.nombre_persona}</td>
                <td class="px-6 py-3">${venta.fecha_venta}</td>
            </tr>
        `).join('');

            // Insertar filas en la tabla
            const tbody = document.getElementById('tabla');
            tbody.innerHTML = rows;

            // Destruir DataTable si existe
            if ($.fn.DataTable.isDataTable('#example')) {
                $('#example').DataTable().destroy();
                tbody.innerHTML = ''; // Limpiar temporalmente
                tbody.innerHTML = rows; // Volver a insertar
            }

            // Inicializar DataTable
            $('#example').DataTable({
                responsive: true,
                order: [
                    [0, 'desc']
                ],
                language: {
                url: '/js/i18n/Spanish.json',
                 paginate: {
                     first: `<i class="fa-solid fa-backward"></i>`,
                     previous: `<i class="fa-solid fa-caret-left">`,
                     next: `<i class="fa-solid fa-caret-right"></i>`,
                     last: `<i class="fa-solid fa-forward"></i>`
                 }
            },
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: 1
                    }
                ]
            });

        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
        }
    });
</script>



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
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            order: [0, 'desc'],
            language: {
                url: '/js/i18n/Spanish.json',
                 paginate: {
                     first: `<i class="fa-solid fa-backward"></i>`,
                     previous: `<i class="fa-solid fa-caret-left">`,
                     next: `<i class="fa-solid fa-caret-right"></i>`,
                     last: `<i class="fa-solid fa-forward"></i>`
                 }
            },ut: {
                topStart: {

                    buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
                }
            },
            columnDefs: [{
                    responsivePriority: 3,
                    targets: 0
                },
                {
                    responsivePriority: 1,
                    targets: 1
                },

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

@endpush
@endsection