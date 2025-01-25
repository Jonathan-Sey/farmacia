document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault(); // Evitar que el formulario se envíe de manera tradicional
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    axios.post('api/auth/login', {
        email: email,
        password: password,
    })
    .then(response => {
        const token = response.data.token;

        // Guardar el token en localStorage
        localStorage.setItem('jwt_token', token);

        // Redirigir al dashboard
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'Inicio de sesión exitoso. Redirigiendo...',
            position: 'center',  // Posición en la parte superior de la pantalla
            customClass: {
                popup: 'fixed top-10 left-1/2 transform -translate-x-1/2 z-50', // Posición fija y centrada
            },
            didOpen: () => {
                document.body.classList.add('overflow-hidden');  // Desactivar el scroll mientras el modal está abierto
            },
            willClose: () => {
                document.body.classList.remove('overflow-hidden');  // Restaurar el scroll cuando se cierre el modal
            }
        }).then(() => {
            window.location.href = '/dashboard';
        });
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'Credenciales incorrectas o hubo un error al intentar iniciar sesión.',
            customClass: {
                popup: 'fixed top-10 left-1/2 transform -translate-x-1/2 z-50',
            },
            didOpen: () => {
                document.body.classList.add('overflow-hidden');  // Desactivar el scroll mientras el modal está abierto
            },
            willClose: () => {
                document.body.classList.remove('overflow-hidden');  // Restaurar el scroll cuando se cierre el modal
            }
        });
    });
});
