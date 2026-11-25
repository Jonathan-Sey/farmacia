document.addEventListener('DOMContentLoaded', function () {
    const logoutButtons = [
        document.getElementById('logout-btn'),
        document.getElementById('logout-btn-mobile')
    ].filter(Boolean);

    logoutButtons.forEach(function (button) {
        button.addEventListener('click', logout);
    });
});

async function logout(event) {
    event.preventDefault();

    const logoutButtons = [
        document.getElementById('logout-btn'),
        document.getElementById('logout-btn-mobile')
    ].filter(Boolean);
    logoutButtons.forEach(function (button) {
        button.disabled = true;
    });

    const token = localStorage.getItem('jwt_token');

    try {
        if (token) {
            await axios.post('/api/auth/logout', {}, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
        }
    } catch (error) {
        console.error('No se pudo invalidar el token en el servidor.', error.response ? error.response.data : error);
    } finally {
        ['jwt_token', 'pestanas', 'user_name', 'pagina_inicio', 'ultimaRutaValida'].forEach(function (key) {
            localStorage.removeItem(key);
        });
        window.location.assign('/');
    }
}
