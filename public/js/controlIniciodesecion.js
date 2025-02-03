
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
        const id_rol = response.data.id_rol;
        const pestanas = response.data.pestanas || [];
        const userName = response.data.user.name; // Obtener el nombre del usuario

        // Guardar el token y el nombre del usuario en localStorage (opcional)
        localStorage.setItem('jwt_token', token);
        localStorage.setItem('id_rol', id_rol);
        localStorage.setItem('pestanas', JSON.stringify(pestanas));
        localStorage.setItem('user_name', userName); // Guardar el nombre del usuario

        const rutaDashboard = pestanas.length > 0 ? pestanas[0] : '/dashboard';

        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'Inicio de sesión exitoso. Redirigiendo...',
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
            window.location.href = rutaDashboard;
        });
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'Credenciales incorrectas o hubo un error al intentar iniciar sesión.',
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
