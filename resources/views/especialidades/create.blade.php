@extends('template')
@section('titulo','Crear especialidades')

@push('css')

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3 ">
        <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form  action="{{route('especialidades.store')}}" method="POST">
            @csrf
            <div id="usuario"></div>
            <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-2 mb-5">
                            <label for="nombre" class=" uppercase block text-sm/6 font-medium text-gray-900">Nombre de la especialidad</label>
                            <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            autocomplete="given-name"
                            placeholder="Nombre de la especialidad"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                            value="{{old('nombre')}}"
                            >
                                @error('nombre')
                                <div role="alert" class="alert alert-error mt-4 p-2">
                                    <span class="text-white font-bold">{{ $message}}</span>
                                </div>

                                @enderror
                    </div>



                    <div class="mt-2">
                        <label for="descripcion" class="uppercase block text-sm/6 font-medium text-gray-900">Descripción</label>
                        <textarea
                        name="descripcion"
                        require
                        id="descripcion"
                        rows="3"
                        placeholder="Descripción de la categoría"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                        >{{old('descripcion')}}</textarea>
                        @error('descripcion')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message}}</span>
                        </div>


                    @enderror
                    </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{route('especialidades.index')}}">
                    <button type="button" class="text-sm/6 font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script src="/js/obtenerUsuario.js"></script>
@endsection
@push('js')

@endpush
