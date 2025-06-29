@extends('template')

@section('titulo','Productos vencidos')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')
<!--<x-data-table>
    <x-slot name="thead">
        <thead class="text-white font-bold">
            <tr class="bg-slate-600">
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Código</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Producto</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Imagen</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Sucursal</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Cantidad</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Tipo</th>
            </tr>
        </thead>
    </x-slot>

    <x-slot name="tbody">
        <tbody>
            @foreach ($productosVencidos as $almacen)
            <tr>
             <td class="px-6 py-4 whitespace-nowrap text-left">{{ $almacen->producto->codigo }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-left"> {{ $almacen->producto->nombre }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-left">
                   
                    @if ($almacen->producto->imagen)
                        <div class="mt-2">
                            <img src="{{ asset('uploads/' . $almacen->producto->imagen) }}" alt="{{ $almacen->producto->nombre }}" class="w-16 h-16 object-cover rounded">
                        </div>
                    @else
                        <span class="text-gray-500 block">Sin imagen</span>
                    @endif
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-left">
                    {{ $almacen->sucursal->nombre }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-left">
                    @if ($almacen->cantidad <= $almacen->producto->alerta_stock)
                        <span class="text-red-500 font-bold">{{ $almacen->cantidad }}</span>
                    @else
                        <span class="text-green-500 font-bold">{{ $almacen->cantidad }}</span>
                    @endif
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-left">
                    @if ($almacen->producto->tipo == 1)
                        <span class="text-green-500 font-bold">Producto</span>
                    @else
                        <span class="text-red-500 font-bold">Servicio</span>
                    @endif
                </td>

             
            </tr>
            @endforeach
        </tbody>
    </x-slot>
</x-data-table>-->

 <x-data-table>
        <x-slot name="thead">
            <thead class=" text-white font-bold">
                <tr class="bg-slate-600  ">
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Código</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Producto</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Sucursal</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Cantidad</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Tipo</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Imagen</th>
                    
                </tr>
            </thead>
        </x-slot>

        <x-slot name="tbody">
            <tbody>
                @foreach ($productosVencidos as $almacen)
                <tr>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$almacen->id}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$almacen->producto->nombre}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$almacen->sucursal->nombre}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$almacen->cantidad}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$almacen->producto->tipo == 1 ? 'Producto' : 'Servicio'}}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ $almacen->producto->imagen ? asset('uploads/' . $almacen->producto->imagen) : '#' }}">
                            @if ($almacen->producto->imagen)
                                <img src="{{ asset('uploads/' . $almacen->producto->imagen) }}" alt="{{ $almacen->producto->nombre }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <span class="text-gray-500">Sin imagen</span>
                            @endif
                        </a>
                     </td>

                </tr>
                @endforeach
            </tbody>
        </x-slot>
    </x-data-table>
@endsection

@push('js')
{{-- Aquí agregarías el JS para cambiar estado si fuera necesario --}}

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
            autoWidth: false,
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
                { responsivePriority: 2, targets: 5 },
            ],
           
        });
    });
</script>
@endpush
