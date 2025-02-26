document.getElementById("logout-btn").addEventListener("click", logout);
document.getElementById("logout-btn-mobile").addEventListener("click", logout);

function logout() {
    const token = localStorage.getItem("jwt_token"); 

    if (!token) {
        alert("No se encontró un token activo");
        return;
    }

    axios.post('/api/auth/logout', {}, {
        headers: {
            Authorization: `Bearer ${token}`
        }
    })
    .then(response => {
        console.log(response.data.message);
        // Elimina el token del almacenamiento local y la demas informacion
        localStorage.clear();
        // Redirige al usuario a la página de inicio de sesión o inicio
        window.location.href = "/";
    })
    .catch(error => {
        console.error("Error al cerrar sesión", error.response.data);
        alert("Hubo un problema al cerrar sesión. Intenta nuevamente.");
    });
}
