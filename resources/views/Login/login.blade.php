@extends('layouts.login')

@section('contenido')
<div class="max-w-full sm:max-w-full bg-[#00000050] grid grid-cols-1 p-4 rounded-2xl">
    <div class=" max-w-[400px] sm:max-w-[500px] flex flex-wrap overflow-hidden">
    
        <h1 class="text-3xl sm:text-5xl font-bold text-white">Iniciar Secion</h1>
    <div class="mx-4 sm:mx-2 flex max-w-full sm:max-w-[640px] flex-wrap items-center justify-center">
        <form id="loginform" class="my-8 sm:my-20 mx-8 sm:mx-20 space-y-5 text-white" method="POST" action="{{ route('login') }}">
            @csrf
                <div class="space-y-5 grid grid-cols-1 relative">
                    <div class="absolute top-6 left-[2px] items-center sm:items-center sm:justify-center justify-center bg-[#FFFFFF30] rounded-full px-2 py-1 ring-2 ring-cyan-500">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                        <input type="email" name="email" placeholder="email" class="w-full sm:w-80 bg-[#FFFFFF30] px-11 py-2 rounded-full focus:bg-[#00000050] focus:outline-none focus:ring-2 focus:ring-cyan">
           
                    <div  class="absolute top-16 left-[2px] items-center sm:items-center sm:justify-center justify-center bg-[#FFFFFF30] rounded-full px-2 py-1 ring-2 ring-cyan-500">
                        <i class="fa-solid fa-lock"></i>
                    </div>    
                    <input type="password" name="password" placeholder="password" class="w-full sm:w-80 bg-[#FFFFFF30] px-10 py-2 rounded-full focus:bg-[#00000050] focus:outline-none focus:ring-2 focus:ring-cyan">
                </div>
            
                <button type="submit" class="btn btn-outline font-bold left-[-50px] sm:left-5 w-80 text-white hover:bg-[#00000050] hover:text-white hover:border-white">Iniciar Secion</button>
        </form>
    </div>
    <div>
        <p class=" font-bold text-white">
            ¿Olvido la contraseña? <a href="/Recuperacion_contraseña" class="text-[#3ABFF8]">Recuperar</a>
        </p>
    </div>
</div>
</div>

@endsection

