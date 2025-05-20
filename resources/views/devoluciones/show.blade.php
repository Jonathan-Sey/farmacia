@extends('template')
@section('titulo','Detalle devolución')
@push('css')
@endpush

@section('contenido')

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
  <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
    <p class="card-title font">ID Devolución</p>
  </div>
  <div class="md:col-span-2">
    <p class="p-2 text-center font-semibold " >{{$devolucion->id}}</p>
  </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
  <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
    <p class="card-title font">Venta asociada</p>
  </div>
  <div class="md:col-span-2">
    <p class="p-2 text-center font-semibold " >{{$devolucion->venta->id}}</p>
  </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
  <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
    <p class="card-title font">Vendedor</p>
  </div>
  <div class="md:col-span-2">
    <p class="p-2 text-center font-semibold " >{{$devolucion->usuario->name}}</p>
  </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
  <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
    <p class="card-title font">Fecha devolución</p>
  </div>
  <div class="md:col-span-2">
    <p class="p-2 text-center font-semibold " >{{$devolucion->created_at}}</p>
  </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
  <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
    <p class="card-title font">Motivo</p>
  </div>
  <div class="md:col-span-2">
    <p class="p-2 text-center font-semibold">{{ $devolucion->motivo ?? 'N/A' }}</p>
  </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
  <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
    <p class="card-title font">Observaciones</p>
  </div>
  <div class="md:col-span-2">
    <p class="p-2 text-center font-semibold">{{ $devolucion->observaciones ?? 'N/A' }}</p>
  </div>
</div>


<div class="mt-5">
  <h2 class="text-center m-5 font-bold text-lg">Detalle Devolución</h2>
  <div class="overflow-x-auto">
    <table id="tabla-devoluciones" class="table table-md table-pin-rows table-pin-cols">
      <thead>
        <tr>
          <th></th>
          <td>Producto</td>
          <td>Cantidad</td>
          <td>Precio</td>
          <td>SubTotal</td>
          <td>Fecha Vencimiento</td>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($devolucion->detalles as $detalle)
        <tr>
          <th></th>
          <td class="bg-white">{{$detalle->producto->nombre}}</td>
          <td class="bg-white">{{$detalle->cantidad}}</td>
          <td class="bg-white">{{$detalle->precio}}</td>
          <td class="subTotal bg-white">{{$detalle->precio * $detalle->cantidad}}</td>
          <td class="bg-white">
            @if ($detalle->fecha_vencimiento)
              {{ \Carbon\Carbon::parse($detalle->fecha_vencimiento)->format('Y-m-d') }}
            @else
              N/A
            @endif
          </td>
          <th></th>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th></th>
          <td class="text-sm font-black">SUMA: <span id="suma" class="font-black">0</span></td>
          <td colspan="2" class="text-sm font-black"></td>
          <td class="text-sm font-black">TOTAL: <span id="total" class="font-black">0</span></td>
          <td></td>
          <th></th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

<script>
  $(document).ready(function(){
      calcularValores();
  });

  function calcularValores() {
    let suma = 0;
    let subTotal = document.getElementsByClassName('subTotal');

    for(let i = 0; i < subTotal.length; i++) {
      suma += parseFloat(subTotal[i].innerHTML);
    }

    $('#suma').html(Math.round(suma));
    $('#total').html(Math.round(suma));
  }
</script>
@endpush
