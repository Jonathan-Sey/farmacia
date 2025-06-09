@extends('template')
@section('titulo', 'Asignar servicio')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{route('almacenes.store')}}" method="POST">
            @csrf
            <div class="border-b border-gray-900/10 pb-12">
                <div id="usuario">

                </div>


                <div class="mt-2 mb-5">
                    <x-select2
                        name="id_producto"
                        label="Servicio"
                        :options="$productos->pluck('nombre', 'id')"
                        :selected="old('id_producto')"
                        placeholder="Seleccionar un servicio"
                        id="id_producto"
                        class="select2-producto block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                    />
                </div>

                <div class="mt-2 mb-5">
                    <x-select2
                        name="id_sucursal"
                        label="Farmacia"
                        :options="$sucursales->pluck('nombre', 'id')"
                        :selected="old('id_sucursal')"
                        placeholder="Seleccionar una farmacia"
                        id="id_sucursal"
                        class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                    />
                </div>


            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{route('almacenes.index')}}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </div>
</div>


@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/js/select2-global.js"></script>


@endpush
