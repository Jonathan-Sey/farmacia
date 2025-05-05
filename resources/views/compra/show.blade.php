@extends('template')
@section('titulo','Detalle compra')
@push('css')

@endpush

@section('contenido')

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Codigo</p>
    </div>
    <div class="md:col-span-2">
      <p class="p-2 text-center font-semibold " >{{$compra->numero_compra}}</p>
    </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Comprobante</p>
    </div>
    <div class="md:col-span-2">
      <p class="p-2 text-center font-semibold " >{{$compra->sucursal->codigo_sucursal}}</p>
    </div>
</div>


<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Proveedor</p>
    </div>
    <div class="md:col-span-2">
      <p class="p-2 text-center font-semibold " >{{$compra->proveedor->empresa}}</p>
    </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Fecha</p>
    </div>
    <div class="md:col-span-2">
      <p class="p-2 text-center font-semibold " >{{$compra->fecha_compra}}</p>
    </div>
</div>
<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Impuesto</p>
    </div>
    <div class="md:col-span-2">
      <p id="impuesto"  class="p-2 text-center font-semibold " >{{$compra->impuesto}}</p>
    </div>
</div>

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Total</p>
    </div>
    <div class="md:col-span-2">
      <p class="p-2 text-center font-semibold " >{{$compra->total}}</p>
    </div>
</div>

{{-- <div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Impuesto</p>
    </div>
    <div class="md:col-span-2">
      <p  id="impuesto" class=" p-2 text-center font-semibold ">{{$compra->impuesto}}</p>
    </div>
</div>
<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
      <p class="card-title font">Comprobante </p>
    </div>
    <div class="md:col-span-2">
      <p  id="impuesto" class=" p-2 text-center font-semibold ">
            @if($compra->imagen_comprobante)
        <a href="{{ asset('uploads/' . $compra->imagen_comprobante) }}" target="_blank"
        class="btn btn-sm btn-info" title="Ver comprobante">
            <i class="fas fa-file-invoice"></i>
        </a>
    @endif
      </p>
    </div>
</div> --}}

<div class="card bg-base-100 w-full shadow-lg md:grid md:grid-cols-3 mb-5">
    <div class="card-body items-center p-2 bg-slate-200 rounded-t-xl md:rounded-xl md:col-span-1">
        <p class="card-title font">Comprobante de Compra</p>
    </div>
    <div class="md:col-span-2">
        @if($compra->imagen_comprobante)
            <div class="p-2 text-center">
                <p class="font-semibold mb-2">Compra con comprobante</p>

                @if($compra->observaciones_comprobante)
                    <p class="text-sm">Observaciones: <span class="font-bold">{{ $compra->observaciones_comprobante }}</span></p>
                @endif

                <div class="mt-3">
                    <p class="text-sm mb-2">Comprobante:</p>
                    <button onclick="document.getElementById('modal-comprobante').showModal()" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye mr-1"></i> Ver comprobante
                    </button>

                    <button onclick="descargarComprobante('{{ $compra->imagen_comprobante_url }}')" class="btn btn-sm text-white bg-red-800 ml-2">
                        <i class="fas fa-download mr-1"></i> Descargar
                    </button>
                </div>

                <!-- Modal para ver el comprobante -->
                <dialog id="modal-comprobante" class="modal">
                    <div class="modal-box max-w-5xl">
                        <h3 class="font-bold text-lg">Comprobante de Compra</h3>

                        @if(pathinfo($compra->imagen_comprobante, PATHINFO_EXTENSION) === 'pdf')
                            <embed src="{{ $compra->imagen_comprobante_url }}" type="application/pdf" width="100%" height="600px" class="mt-4">
                        @else
                            <img src="{{ $compra->imagen_comprobante_url }}" alt="Comprobante de compra" class="w-full h-auto mt-4">
                        @endif

                        <div class="modal-action">
                            <button onclick="document.getElementById('modal-comprobante').close()" class="btn">Cerrar</button>
                        </div>
                    </div>
                    <form method="dialog" class="modal-backdrop">
                        <button>close</button>
                    </form>
                </dialog>
            </div>
        @else
            <p class="p-2 text-center font-semibold">Compra sin comprobante adjunto</p>
        @endif
    </div>
</div>



{{--
<div class="flex flex-col gap-3">
    <div class="flex items-center">
        <span class="flex-shrink-0 p-2 bg-gray-300 rounded-md w-1/3 flex items-center gap-2 ">
        <i class="fa-solid fa-file "></i>
        <p>Codigo</p>
        </span>
        <input class="p-2 ml-2 rounded-md w-full bg-slate-300" readonly type="text" value="Codigo" disabled>
    </div>
    <div class="flex items-center">
        <span class="flex-shrink-0 p-2 bg-gray-300 rounded-md w-1/3 flex items-center gap-2 ">
        <i class="fa-solid fa-file "></i>
        <p>Codigo</p>
        </span>
        <input class="p-2 ml-2 rounded-md w-full bg-slate-300" readonly type="text" value="Codigo" disabled>
    </div>

</div>
 --}}

 <div class="mt-5">
    <h2 class="text-center m-5 font-bold text-lg">Detalle compra</h2>
    <div class="overflow-x-auto">
        <table id="tabla-productos" class="table  table-md table-pin-rows table-pin-cols">
          <thead>
            <tr>
              <th></th>
              <td>Producto</td>
              <td>Cantidad</td>
              <td>Precio</td>
              <td>SubTotal</td>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($compra->productos as $producto)
            <tr>
                <th></th>
                <td class="bg-white" >{{$producto->nombre}} </td>
                <td class="bg-white" >{{$producto->pivot->cantidad}} </td>
                <td class="bg-white" >{{$producto->pivot->precio}} </td>
                <td class="subTotal  bg-white">{{ $producto->pivot->cantidad * $producto->pivot->precio }}</td>
                <th></th>
            </tr>
            @endforeach


          </tbody>
          <tfoot>
            <tr>
                <th></th>
                <td class="text-sm font-black">SUMA:  <span id="suma" class="font-black "> 0</span></td>
                <td class="text-sm font-black">IVA: <span id="iva" class="font-black">{{$compra->impuesto}}</span></td>
                <td class="text-sm font-black"><input type="hidden" name="total" value="{{$compra->total}}" id="inputTotal"> TOTAL:  <span id="total" class="font-black">{{$compra->total}}</span></td>
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
    let subTotal = document.getElementsByClassName('subTotal');
    //let impuesto = document.getElementById('impuesto').innerHTML;
    let cont = 0;
    //console.log(impuesto);
    $(document).ready(function(){
        calcularValores();
    });

    function calcularValores(){
        for(let i = 0; i < subTotal.length; i++){
            cont += parseFloat(subTotal[i].innerHTML) || 0;
        }

        // $('#suma').html(cont);
        // $('#iva').html(impuesto);
        // $('#total').html(cont+ parseFloat(impuesto) );

        $('#suma').html(cont);
        // $('#iva').html(impuesto);
        // $('#inputTotal').val(tota);
    }


</script>
<script>
function descargarComprobante(url) {
    const link = document.createElement('a');
    link.href = url;
    // Extraer el nombre del archivo de la URL
    const fileName = url.substring(url.lastIndexOf('/') + 1);
    link.setAttribute('download', fileName);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endpush
