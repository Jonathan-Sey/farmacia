@extends('template')
@section('titulo', 'Histórico de Precios')
@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')
<div class="container">
    <x-data-table>
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
            <tbody>
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
                    buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
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