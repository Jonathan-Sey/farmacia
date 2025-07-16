@extends('template')
@section('titulo', 'Reporte de Paciente' . ' '. $persona->nombre)
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')

{{-- <div class="max-w-5xl mx-auto p-4 mb-6 bg-white rounded-lg shadow-md">
</div> --}}

<x-data-table>
    <x-slot name="thead">
        <thead class=" text-white font-bold">
            <tr class="bg-slate-600  ">
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Diagnostico</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Medico</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Sucursal</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Fecha de Consulta</th>
            </tr>
        </thead>
    </x-slot>

    <x-slot name="tbody">
        <tbody>
            @foreach ($fichas as $ficha)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $ficha->diagnostico ?? 'Sin diagnosticos'}}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $ficha->detalleMedico->usuario->name ?? 'Medico no asignado'}}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $ficha->sucursal->nombre ?? 'No tiene sucursal'}}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $ficha->consulta_programada ?? 'Sin fecha programada'}}</td>
            </tr>
            @endforeach
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
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js">//botones en general</script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js">//imprimir</script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.colVis.min.js">//fltrar columnas</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js">//pdf</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js">//copiar</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js">//excel</script>

<script src=""></script>

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
@endpush