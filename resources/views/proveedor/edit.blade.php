@extends('template')
@section('titulo', 'Crear Proveedor')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3 ">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{route('proveedores.update',['proveedor'=>$proveedor->id])}}" method="POST">
            @csrf
            @method('PATCH')

            <div class="usuario">

            </div>
            <div class="border-b border-gray-900/10 pb-12">


                <div class="mt-2 mb-5">
                    <label for="nombre" class="uppercase block text-sm font-medium text-gray-900">Nombre</label>
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        autocomplete="given-name"
                        placeholder="Nombre"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('nombre',$proveedor->nombre) }}">

                    @error('nombre')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                    <label for="telefono" class="uppercase block text-sm font-medium text-gray-900">Telefono</label>
                    <input
                        type="text"
                        name="telefono"
                        id="telefono"
                        autocomplete="given-name"
                        placeholder="Telefono"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('telefono',$proveedor->telefono) }}">

                    @error('telefono')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
                <div class="mt-2 mb-5">
                    <label for="empresa" class="uppercase block text-sm font-medium text-gray-900">Empresa</label>
                    <input
                        type="text"
                        name="empresa"
                        id="empresa"
                        autocomplete="given-name"
                        placeholder="Empresa"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('empresa',$proveedor->empresa) }}">

                    @error('empresa')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
                <div class="mt-2 mb-5">
                    <label for="correo" class="uppercase block text-sm font-medium text-gray-900">E-mail</label>
                    <input
                        type="email"
                        name="correo"
                        id="correo"
                        autocomplete="given-name"
                        placeholder="E-mail"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                        value="{{ old('correo',$proveedor->correo) }}">

                    @error('correo')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2">
                    <label for="direccion" class="uppercase block text-sm font-medium text-gray-900">Descripción</label>
                    <textarea name="direccion"
                    id="direccion" rows="3"
                    placeholder="Direccion"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">{{ old('direccion',$proveedor->direccion) }}</textarea>
                    @error('direccion')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>



            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{route('proveedores.index')}}">
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
@endpush

