@extends('template')
@section('titulo','Editar Usuario')

@push('css')

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-4xl mb-10">
        <form action="{{ route('usuarios.update', $user->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-2 mb-5">
                    <label for="name" class="uppercase block text-sm font-medium text-gray-900">Nombre del Usuario</label>
                    <input type="text" name="name" id="name" autocomplete="given-name" placeholder="Nombre del Usuario" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" value="{{ old('name', $user->name) }}">
                    @error('name')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                

                <div class="mt-2 mb-5">
                    <label for="email" class="uppercase block text-sm font-medium text-gray-900">Correo Electrónico</label>
                    <input type="email" name="email" id="email" autocomplete="email" placeholder="Correo Electrónico" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" value="{{ old('email', $user->email) }}">
                    @error('email')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <div class="mt-2 mb-5">
                    <label for="password" class="uppercase block text-sm font-medium text-gray-900">Nueva Contraseña</label>
                    <input type="text" name="password" id="password" placeholder="Dejar en blanco si no desea cambiar" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                    @error('password')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <div class="mt-2 mb-5">
                    <label for="id_rol" class="uppercase block text-sm font-medium text-gray-900">Pestañas</label>
                    <select name="id_rol" id="id_rol" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->id }}" {{ $rol->id == $user->id_rol ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_rol')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{route('usuarios.index')}}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
@endpush

