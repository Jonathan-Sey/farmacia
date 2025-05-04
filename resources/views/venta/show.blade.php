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
    <div class="md:col-span-2 break-words">
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

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2  bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Observacioens</p>
    </div>
    <div class="md:col-span-2 break-words p-2">
      <p class="p-2 text-center font-semibold " >{{$venta->observaciones_receta}}</p>
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
              <td>Precio Original</td>
              <td>Precio Final</td>
              <td>Descuento</td>
              <td>SubTotal</td>
              <td>Justificación</td>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($venta->detalles as $detalle)
            @php
                $producto = $detalle->producto;
                $descuento = $detalle->precio_original - $detalle->precio;
                $tieneDescuento = $descuento > 0;
            @endphp
            <tr>
                <th></th>
                <td class="bg-white">{{$producto->nombre}}</td>
                <td class="bg-white">{{$detalle->cantidad}}</td>
                <td class="bg-white">Q{{number_format($detalle->precio_original, 2)}}</td>
                <td class="bg-white {{$tieneDescuento ? 'text-green-600 font-semibold' : ''}}">
                    Q{{number_format($detalle->precio, 2)}}
                </td>
                <td class="bg-white {{$tieneDescuento ? 'text-red-600 font-semibold' : ''}}">
                    @if($tieneDescuento)
                        -Q{{number_format($descuento, 2)}}
                    @else
                        Q0.00
                    @endif
                </td>
                <td class="subTotal bg-white">Q{{number_format($detalle->cantidad * $detalle->precio, 2)}}</td>
                <td class="bg-white text-sm">
                    @if($detalle->justificacion_descuento)
                        <div class="tooltip" data-tip="{{$detalle->justificacion_descuento}}">
                            <i class="fas fa-info-circle text-blue-500"></i>
                        </div>
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
                <td colspan="2" class="text-sm font-black">IVA: <span id="iva" class="font-black">{{ $venta->impuesto}}</span></td>
                <td class="text-sm font-black"><input type="hidden" name="total" value="{{ $venta->total}}" id="inputTotal"> TOTAL: <span id="total" class="font-black">{{ $venta->total}}</span></td>
                <td colspan="3"></td>
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
            // Eliminar el símbolo Q y convertir a número
            let value = parseFloat(subTotal[i].textContent.replace('Q', '').replace(',', '').trim()) || 0;
            suma += value;
        }

        $('#suma').html('Q' + suma.toFixed(2));
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
