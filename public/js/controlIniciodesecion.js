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

            // Guardar el token en localStorage
            localStorage.setItem('jwt_token', token);

            // Redirigir al dashboard
            window.location.href = '/dashboard';
        })
        .catch(error => {
            alert('Error al iniciar sesión. Verifique sus credenciales.');
        });
});
