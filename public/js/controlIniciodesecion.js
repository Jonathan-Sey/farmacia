
document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    axios.post('api/auth/login', {
        email: email,
        password: password,
    })
    .then(response => {
        const token = response.data.token;
        const pestanas = response.data.pestanas || [];
        const userName = response.data.user.name; // Obtener el nombre del usuario
        const paginaInicio = response.data.pagina_inicio || '/dashboard';

        // Guardar el token y el nombre del usuario en localStorage (opcional)
        localStorage.setItem('jwt_token', token);
        localStorage.setItem('pestanas', JSON.stringify(pestanas));
        localStorage.setItem('user_name', userName); // Guardar el nombre del usuario
        localStorage.setItem('pagina_inicio', paginaInicio); // Guardar la página de inicio

        const rutaDashboard = paginaInicio;

        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'Inicio de sesión exitoso. Redirigiendo...',
            showConfirmButton: false,
            timer:2000,
            position: 'center',
            customClass: {
                popup: 'z-50',
            },
            didOpen: () => {
                const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;
                document.body.style.overflow = 'hidden'; // Desactiva el scroll
                document.body.style.marginRight = `${scrollBarWidth}px`; // Compensa la barra de scroll
            },
            willClose: () => {
                document.body.style.overflow = ''; // Restaura el scroll
                document.body.style.marginRight = ''; // Elimina el margen adicional
            }
        }).then(() => {
            window.location.href = paginaInicio;
        });
    })
    .catch(error => {
        let mensaje ='';
        //Verifica si axios trajo una respuesta,verifica si la respuesta trae un cuerpo(normalmente esta en la propiedad data y
        //tendria que ser un json lo que esta en el cuerpo), y luego mira si el cuerpo trae una propiedad error.
        if (error.response && error.response.data && error.response.data.error) {
            //Guarda en la variable lo que contiene la propiedad error del json que esta en el cuerpo de la respuesta que recivio laravel
            mensaje = error.response.data.error;
        }

        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text:mensaje,
            showConfirmButton: false,
            timer:2000,
            customClass: {
                popup: 'z-50',
            },
            didOpen: () => {
                const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;
                document.body.style.overflow = 'hidden'; // Desactiva el scroll
                document.body.style.marginRight = `${scrollBarWidth}px`; // Compensa la barra de scroll
            },
            willClose: () => {
                document.body.style.overflow = ''; // Restaura el scroll
                document.body.style.marginRight = ''; // Elimina el margen adicional
            }
        });
    });
});
