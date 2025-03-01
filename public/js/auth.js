// auth.js

// Verificar si el token JWT está presente en localStorage
const token = localStorage.getItem('jwt_token');

if (token) {
    // Configurar Axios para incluir el token en el encabezado Authorization
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
} else {
    // Si no hay token, redirigir al login
    window.location.href = '/';
}
