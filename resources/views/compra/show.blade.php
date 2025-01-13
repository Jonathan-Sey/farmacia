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
      <p class="card-title font">Comprovante</p>
    </div>
    <div class="md:col-span-2">
      <p class="p-2 text-center font-semibold " >{{$compra->comprobante}}</p>
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
      <p  id="impuesto" class=" p-2 text-center font-semibold ">{{$compra->impuesto}}</p>
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
            @foreach ($compra->productos as $compra)
            <tr>
                <th></th>
                <td class="bg-white" >{{$compra->nombre}} </td>
                <td class="bg-white" >{{$compra->pivot->cantidad}} </td>
                <td class="bg-white" >{{$compra->pivot->precio}} </td>
                <td class="subTotal  bg-white">{{ $compra->pivot->cantidad * $compra->pivot->precio }}</td>
                <th></th>
            </tr>
            @endforeach


          </tbody>
          <tfoot>
            <tr>
                <th></th>
                <td class="text-sm font-black">SUMA:  <span id="suma" class="font-black "> 0</span></td>

                <td class="text-sm font-black">IVA: <span id="iva" class="font-black"> 0</span></td>
                <td class="text-sm font-black"><input type="hidden" name="total" value="0" id="inputTotal"> TOTAL:  <span id="total" class="font-black">0</span></td>
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
    let impuesto = document.getElementById('impuesto').innerHTML;
    let cont = 0;
    console.log(impuesto);
    $(document).ready(function(){
        calcularValores();
    });

    function calcularValores(){
        for(let i = 0; i < subTotal.length; i++){
            cont += parseFloat(subTotal[i].innerHTML);
        }

        $('#suma').html(cont);
        $('#iva').html(impuesto);
        $('#total').html(cont+ parseFloat(impuesto) );
    }


</script>
@endpush
