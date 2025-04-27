@extends('template')
@section('titulo','Detalle venta')
@push('css')

@endpush

@section('contenido')

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Venta</p>
    </div>
    <div class="md:col-span-2">
      <p class="p-2 text-center font-semibold " >{{$venta->id}}</p>
    </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Vendedor</p>
    </div>
    <div class="md:col-span-2">
      <p class="p-2 text-center font-semibold " >{{$venta->usuario->name}}</p>
    </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Cliente</p>
    </div>
    <div class="md:col-span-2">
      <p class="p-2 text-center font-semibold " >{{$venta->persona->nombre}}</p>
    </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Sucursal</p>
    </div>
    <div class="md:col-span-2">
      <p class="p-2 text-center font-semibold " >{{$venta->sucursal->nombre}} - {{$venta->sucursal->ubicacion}}</p>
    </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Fecha y hora</p>
    </div>
    <div class="md:col-span-2">
      <p class="p-2 text-center font-semibold " >{{$venta->created_at}}</p>
    </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Impuesto</p>
    </div>
    <div class="md:col-span-2">
      <p id="impuesto"  class="p-2 text-center font-semibold " >{{$venta->impuesto}}</p>
    </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Total</p>
    </div>
    <div class="md:col-span-2">
      <p class="p-2 text-center font-semibold " >{{$venta->total}}</p>
    </div>
</div>
{{-- campos para la receta --}}
<!-- Sección de Prescripción -->
<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
        <p class="card-title font">Prescripción Médica</p>
    </div>
    <div class="md:col-span-2">
        @if($venta->es_prescrito)
            <div class="p-2 text-center">
                <p class="font-semibold mb-2">Venta con receta médica</p>

                @if($venta->numero_reserva)
                    <p class="text-sm">Número de reserva: <span class="font-bold">{{ $venta->numero_reserva }}</span></p>
                @endif

                @if($venta->imagen_receta_url)
                    <div class="mt-3">
                        <p class="text-sm mb-2">Receta médica:</p>
                        <button onclick="document.getElementById('modal-receta').showModal()" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye mr-1"></i> Ver receta
                        </button>

                        <button onclick="descargarReceta('{{ $venta->imagen_receta_url }}')" class="btn btn-sm text-white bg-red-800  ml-2">
                            <i class="fas fa-download mr-1"></i> Descargar
                        </button>
                    </div>

                    <!-- Modal para ver la receta -->
                    <dialog id="modal-receta" class="modal">
                        <div class="modal-box max-w-5xl">
                            <h3 class="font-bold text-lg">Receta Médica</h3>
                            <img src="{{ $venta->imagen_receta_url }}" alt="Receta médica" class="w-full h-auto mt-4">
                            <div class="modal-action">
                                <button onclick="document.getElementById('modal-receta').close()" class="btn">Cerrar</button>
                            </div>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                @else
                    <p class="text-sm text-yellow-600">No se adjuntó imagen de receta</p>
                @endif
            </div>
        @else
            <p class="p-2 text-center font-semibold">Venta sin receta médica</p>
        @endif
    </div>
</div>



<div class="mt-5">
    <h2 class="text-center m-5 font-bold text-lg">Detalle Venta</h2>
    <div class="overflow-x-auto">
        <table id="tabla-productos" class="table table-md table-pin-rows table-pin-cols">
          <thead>
            <tr>
              <th></th>
              <td>Producto</td>
              <td>Cantidad</td>
              <td>Precio</td>
              <td>Precio</td>

              <td>SubTotal</td>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($venta->productos as $producto)
            <tr>
                <th></th>
                <td class="bg-white">{{$producto->nombre}} </td>
                <td class="bg-white">{{$producto->pivot->cantidad}} </td>
                <td class="bg-white">{{$producto->precio_porcentaje}} </td>
                <td class="subTotal bg-white">{{ $producto->pivot->cantidad * $producto->precio_porcentaje }}</td>
                <th></th>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
                <th></th>
                <td class="text-sm font-black">SUMA:  <span id="suma" class="font-black">0</span></td>
                <td class="text-sm font-black">IVA: <span id="iva" class="font-black">{{ $venta->impuesto}}</span></td>
                <td class="text-sm font-black"><input type="hidden" name="total" value="{{ $venta->total}}" id="inputTotal"> TOTAL:  <span id="total" class="font-black">{{ $venta->total}}</span></td>
                <td class="text-sm font-black"></td>
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
    //let impuesto = parseFloat(document.getElementById('impuesto').innerHTML) || 0;
    //let impuesto = parseFloat($('#impuesto').text().trim()) || 0;

    for(let i = 0; i < subTotal.length; i++) {
        suma += parseFloat(subTotal[i].innerHTML) || 0;
    }

    //let ivaCalculado = Math.round((suma * impuesto) / 100);
    //let totalCalculado = Math.round(suma + ivaCalculado);

    $('#suma').html(Math.round(suma));
    //$('#iva').html(ivaCalculado);
    //$('#total').html(totalCalculado);
    //$('#inputTotal').val(totalCalculado);
}

</script>

<script>
    function descargarReceta(url, nombreArchivo) {
    const link = document.createElement('a');
    link.href = url;
    link.download = nombreArchivo || 'receta-medica-' + new Date().toISOString().split('T')[0];
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endpush
