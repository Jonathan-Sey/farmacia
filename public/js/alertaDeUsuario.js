document.getElementById('login-Form').addEventListener('submit', function (e) {
    e.preventDefault(); // Evitar que el formulario se envíe de manera tradicional
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Crear el objeto con los datos del formulario
    const data = {
        email: email,
        password: password
    };

    // Hacer la solicitud POST al backend usando fetch
    fetch('/auth/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())  // Convierte la respuesta a JSON
    .then(data => {
        // Si la respuesta contiene un token, redirigimos
        if (data.token) {
            // Guardamos el token en el almacenamiento local
            localStorage.setItem('jwt_token', data.token);
            // Redirigimos a la página del dashboard
            window.location.href = '/dashboard';
        } else {
            // Si no hay token, mostrar el error
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: data.error || 'Hubo un problema con la solicitud. Intenta nuevamente.'
            });
        }
    })
    .catch(error => {
        // En caso de error en la solicitud (por ejemplo, problemas de red)
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'Hubo un problema al intentar procesar tu solicitud.'
        });
    });
});