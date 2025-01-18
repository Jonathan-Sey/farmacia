@extends('template')
@section('titulo','Panel')

@push('css')

@endpush

@section('contenido')
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <a href="{{ route('productos.index') }}">
            <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg grid grid-cols-2 justify-center align-middle items-center text-center sm:grid-cols-1 lg:grid-cols-1 lg:p-2 sm:p-3  ">
                    <div class="flex flex-col items-center">
                        <i class='bx bxs-package text-7xl lg:text-6xl md:text-6xl sm:text-6xl' ></i>
                        <p class="uppercase text-lg font-bold">Productos</p>
                    </div>
                    <div class="uppercase font-bold sm:text-5xl lg:text-6xl xl:text-7xl">
                        <p class="text-5xl sm:text-3xl font-bold lg:text-3xl">{{$productos}}</p>
                    </div>
            </div>
        </a>
        <a href="{{ route('sucursales.index') }}">
            <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg grid grid-cols-2 justify-center align-middle items-center text-center sm:grid-cols-1 lg:grid-cols-1 lg:p-2  sm:p-3 ">
                    <div class="flex flex-col items-center">
                        <i class='bx bxs-building text-7xl lg:text-6xl  sm:text-6xl' ></i>
                        <p class="uppercase text-lg font-bold">Sucursales</p>
                    </div>
                    <div class="uppercase font-bold sm:text-5xl lg:text-6xl xl:text-7xl">
                        <p class="text-5xl sm:text-3xl font-bold lg:text-3xl">{{$sucursales}}</p>
                    </div>
            </div>
        </a>
        <a href="{{ route('compras.index') }}">
            <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg grid grid-cols-2 justify-center align-middle items-center text-center sm:grid-cols-1 lg:grid-cols-1 lg:p-2  sm:p-3 ">
                    <div class="flex flex-col items-center">
                        <i class='fa-solid fa-cart-shopping text-7xl lg:text-6xl sm:text-6xl' ></i>
                        <p class="uppercase text-lg font-bold">Compras</p>
                    </div>
                    <div class="uppercase font-bold sm:text-5xl lg:text-6xl xl:text-7xl">
                        <p class="text-5xl sm:text-3xl font-bold lg:text-3xl">{{$compras}}</p>
                    </div>
            </div>
        </a>
        <a href="{{ route('ventas.index') }}">
            <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg grid grid-cols-2 justify-center align-middle items-center text-center sm:grid-cols-1 lg:grid-cols-1 lg:p-2 sm:p-3">
                    <div class="flex flex-col items-center">
                        <i class='fa-solid fa-bag-shopping text-7xl lg:text-6xl sm:text-6xl' ></i>
                        <p class="uppercase text-lg font-bold">Ventas</p>
                    </div>
                    <div class="uppercase font-bold sm:text-5xl lg:text-6xl xl:text-7xl">
                        <p class="text-5xl sm:text-3xl font-bold lg:text-3xl">{{$ventas}}</p>
                    </div>
            </div>
        </a>
        <a href="{{ route('productos.index') }}">
            <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg grid grid-cols-2 justify-center align-middle items-center text-center sm:grid-cols-1 lg:grid-cols-1 lg:p-2 sm:p-3">
                    <div class="flex flex-col items-center">
                        <i class='fa-solid fa-heart-circle-check text-7xl lg:text-6xl sm:text-6xl' ></i>

                        <p class="uppercase text-lg font-bold">Servicios variados</p>
                    </div>
                    <div class="uppercase font-bold sm:text-5xl lg:text-6xl xl:text-7xl">
                        <p class="text-5xl sm:text-3xl font-bold lg:text-3xl">{{$servicios}} </p>
                    </div>
            </div>

        </a>
        <a href="{{ route('medicos.index') }}">
            <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg grid grid-cols-2 justify-center align-middle items-center text-center sm:grid-cols-1 lg:grid-cols-1 lg:p-2 sm:p-3">
                    <div class="flex flex-col items-center">
                        <i class='fa-solid fa-user-nurse text-7xl lg:text-6xl sm:text-6xl' ></i>

                        <p class="uppercase text-lg font-bold">Medicos</p>
                    </div>
                    <div class="uppercase font-bold sm:text-5xl lg:text-6xl xl:text-7xl">
                        <p class="text-5xl sm:text-3xl font-bold lg:text-3xl">{{$medicos}} </p>
                    </div>
            </div>

        </a>
    </div>


@endsection
@push('js')

@endpush
