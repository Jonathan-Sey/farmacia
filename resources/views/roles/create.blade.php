@extends('template')
@section('titulo','Crear Rol')

@push('css')

@endpush

@section('contenido')
<div class="mx-6">
    <form  action="{{route('roles.store')}}" method="POST">
        @csrf
        <div class="border-b border-gray-900/10 pb-12">
            <div class=" mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-4">
                <label for="nombre" class=" uppercase block text-sm/6 font-medium text-gray-900">Nombre del Rol</label>
                <div class="mt-2">
                    <input
                    type="text"
                    name="nombre"
                    id="nombre"
                    autocomplete="given-name"
                    placeholder="Nombre del Rol"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                    value="{{old('nombre')}}"
                    >
                        @error('nombre')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message}}</span>
                        </div>

                        @enderror
                </div>
            </div>

            <div class="sm:col-span-4">
                <label for="descripcion" class="uppercase block text-sm/6 font-medium text-gray-900">Descripcion</label>
                <div class="mt-2">
                <textarea
                name="descripcion"
                id="descripcion"
                rows="3"
                placeholder="Descripcion del Rol"
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                >{{old('descripcion')}}</textarea>
                @error('descripcion')
                <div role="alert" class="alert alert-error mt-4 p-2">
                    <span class="text-white font-bold">{{ $message}}</span>
                </div>

                @enderror

            </div>


            </div>
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="{{route('roles.index')}}">
                <button type="button" class="text-sm/6 font-semibold text-gray-900">Cancelar</button>
            </a>
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
          </div>
    </form>
</div>
  @endsection
@push('js')

@endpush
