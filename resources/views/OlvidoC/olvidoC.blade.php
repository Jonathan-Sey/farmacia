@extends('layouts.login')
@section('contenido')
<div class="max-w-[527px] sm:max-w-full bg-[#00000050] grid grid-cols-1 items-center justify-center p-4 rounded-2xl">
    <div class="max-w-full sm:max-w-[640px] flex flex-wrap overflow-hidden">
        <h1 class="text-3xl sm:text-5xl font-bold text-white">Recuperación de Contraseña</h1>
        <div class="ml-2">
            <p class="font-bold text-lg sm:text-2xl text-white">Ingrese su correo para la recuperación de la contraseña</p>
        </div>
        <div class="mx-4 sm:mx-20 flex max-w-full sm:max-w-[640px] flex-wrap items-center justify-center">
            <form class="my-8 sm:my-20 mx-8 sm:mx-20 space-y-5 text-white" action="">
                <div class="space-y-5 grid grid-cols-1 relative">
                    <div class="absolute top-6 left-1 items-center sm:items-center sm:justify-center justify-center bg-[#FFFFFF30] rounded-full px-2 py-1 ring-2 ring-cyan-500">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <input type="email" placeholder="Usuario" class="w-full  sm:w-80 bg-[#FFFFFF30] px-11 py-2 rounded-full focus:bg-[#00000050] focus:outline-none focus:ring-2 focus:ring-cyan">
                </div>

                <button class="btn btn-outline font-bold w-full sm:w-80 text-white hover:bg-[#00000050] hover:text-white hover:border-white">Recuperar Contraseña</button>
            </form>
        </div>
        <p class=" font-bold text-white">
            Regresar al <a href="/" class="text-[#3ABFF8]">Inicio de Secion</a>
        </p>
    </div>
</div>

@endsection