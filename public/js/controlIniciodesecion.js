
 document.getElementById('login-form').addEventListener('submit', function(e) {
     e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

            axios.post('api/auth/login', {
                 email: email,
                 password: password
            })
            .then(response => {
             // Guardar el token en localStorage
             console.log(response.data);
                const token = response.data.token;
                    localStorage.setItem('jwt_token',token);
                    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                        window.location.href = '/dashboard';
            })
            .catch(error => {
                console.error('Error al iniciar sesión:', error);
                alert('Error al iniciar sesión. Verifique sus credenciales.');
            });
});


