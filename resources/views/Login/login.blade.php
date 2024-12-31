@extends('layouts.login')

@section('contenido')
<div class="max-w-[1000px] bg-[#00000050]">
    <div class="justify-center items-center">
        <h1>Logueate</h1>
        <form action="">
            <div>
                <div class=" ">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <input type="email" placeholder="Usuario">

                <div>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <input type="text" placeholder="Contraseña">
            </div>
            <button class="btn btn-outline btn-secondary">Iniciar secion</button>
        </form>
        <div>
            <p>
                ¿Olvido la contraseña?<a href="Registrate">Recuperar</a>
            </p>
        </div>
    </div>
</div>

@endsection
