

@extends('template')

@section('titulo','Bitacora')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">


@endpush

@section('contenido')
    <x-data-table>
        <x-slot name="thead">
            <thead class="text-white font-bold">
                <tr class="bg-slate-600">
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">idUsuario</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Nombre Usuario</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Acción</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Detalles</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Fecha-Hora</th>




                </tr>
            </thead>
        </x-slot>

        <x-slot name="tbody">
            <tbody>
                @foreach ($bitacora as $bitacora)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bitacora->id }}</td>


                        <td class="px-6 py-4 whitespace-nowrap">{{ $bitacora->id_usuario }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bitacora->name_usuario }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bitacora->accion }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bitacora->detalles}}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $bitacora->fecha_hora}}</td>



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


<script>
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            order: [0,'desc'],
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
                        {
                        extend:'colvis',
                        }
                        
                    ]
                }
            },
            columnDefs: [
                { responsivePriority: 3, targets: 0 },

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