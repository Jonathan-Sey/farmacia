<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('js/app.js') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
</head>
<body class="bg-gradient-to-r from-[#072E33] from-10% via-[#0C7075] via-30% to-[#0F969C] to-90%  flex items-center justify-center h-screen">
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
</body>
</html>