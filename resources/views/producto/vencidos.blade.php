@extends('template')

@section('titulo','Productos vencidos')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')
<!-- Formulario de filtros -->
<div class="max-w-5xl mx-auto p-6 m-5 bg-white rounded-lg shadow-md">
    <form id="formReporte" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="sucursal" class="block text-sm font-medium text-gray-600">Sucursal:</label>
                <select name="sucursal" id="sucursal" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    <option value="">Todas las sucursales</option>
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                    @endforeach
                </select>

                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 m-2">
                        <label for="fechaInicio" class="block text-sm font-medium text-gray-600">Fecha de vencimiento desde:</label>
                        <input type="date" id="fechaInicio" name="fechaInicio"
                            class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    </div>

                    <div class="flex-1 m-2">
                        <label for="fechaFin" class="block text-sm font-medium text-gray-600">Fecha de vencimiento hasta:</label>
                        <input type="date" id="fechaFin" name="fechaFin"
                            class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                    </div>
                </div>
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
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Producto</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Sucursal</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Cantidad</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Fecha Vencimiento</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Tipo</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Imagen</th>
            </tr>
        </thead>
    </x-slot>

    <x-slot name="tbody">
        <tbody id="tabla">
            <!-- Aquí se insertarán las filas de la tabla dinámicamente -->
            @foreach ($productosVencidos as $almacen)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{$almacen->producto->codigo ?? $almacen->id}}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{$almacen->producto->nombre}}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{$almacen->sucursal->nombre}}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{$almacen->cantidad}}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{$almacen->fecha_vencimiento}}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{$almacen->producto->tipo == 1 ? 'Producto' : 'Servicio'}}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if ($almacen->producto->imagen)
                        <img src="{{ asset('uploads/' . $almacen->producto->imagen) }}" alt="{{ $almacen->producto->nombre }}" class="w-16 h-16 object-cover rounded">
                    @else
                        <span class="text-gray-500">Sin imagen</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </x-slot>
</x-data-table>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
   document.getElementById('btnGenerarInforme').addEventListener('click', async () => {
    const sucursal = document.getElementById('sucursal').value;
    const fechaInicio = document.getElementById('fechaInicio').value;
    const fechaFin = document.getElementById('fechaFin').value;

    let url = '/productos-vencidos-informe?';
    if (sucursal) url += `sucursal=${sucursal}&`;
    if (fechaInicio) url += `fechaInicio=${fechaInicio}&`;
    if (fechaFin) url += `fechaFin=${fechaFin}&`;

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error('Error en la respuesta');
        const data = await response.json();

        const rows = data.map(producto => `
            <tr>
                <td class="px-6 py-3">${producto.codigo_producto || producto.id}</td>
                <td class="px-6 py-3">${producto.nombre_producto}</td>
                <td class="px-6 py-3">${producto.nombre_sucursal}</td>
                <td class="px-6 py-3">${producto.cantidad}</td>
                <td class="px-6 py-3">${producto.fecha_vencimiento}</td>
                <td class="px-6 py-3">${producto.tipo == 1 ? 'Producto' : 'Servicio'}</td>
                <td class="px-6 py-3">
                    ${producto.imagen ?
                        `<img src="/uploads/${producto.imagen}" alt="${producto.nombre_producto}" class="w-16 h-16 object-cover rounded">` :
                        '<span class="text-gray-500">Sin imagen</span>'
                    }
                </td>
            </tr>
        `).join('');

        const tbody = document.getElementById('tabla');
        tbody.innerHTML = rows;

        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
            tbody.innerHTML = rows;
        }

        $('#example').DataTable({
            responsive: true,
            order: [[4, 'asc']],
            language: {
                url: '/js/i18n/Spanish.json',
                paginate: {
                    first: `<i class="fa-solid fa-backward"></i>`,
                    previous: `<i class="fa-solid fa-caret-left"></i>`,
                    next: `<i class="fa-solid fa-caret-right"></i>`,
                    last: `<i class="fa-solid fa-forward"></i>`
                }
            },
            dom: 'Bfrtip',
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: 1 },
                { responsivePriority: 3, targets: 4 }
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
            autoWidth: false,
            order: [4, 'asc'],
            language: {
                url: '/js/i18n/Spanish.json',
                 paginate: {
                     first: `<i class="fa-solid fa-backward"></i>`,
                     previous: `<i class="fa-solid fa-caret-left"></i>`,
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
                            buttons: ['copy', 'pdf', 'excel', 'print'],
                         },
                         {
                         extend: 'colvis',
                     }
                     ]
                 }
             },
            columnDefs: [
                { responsivePriority: 3, targets: 0 },
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: 4 },
            ],
        });
    });
</script>
@endpush
