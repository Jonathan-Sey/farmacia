document.addEventListener('DOMContentLoaded', function () {function obtenerUsuario(){
    const token = localStorage.getItem('jwt_token');
    const crearUsuario = document.getElementById('usuario');

    if (!token) {
        alert("No se encontró un token activo");
        return;
    }
    
    const decodificarToken = jwt_decode(token);
    const limiteDeTiempo = Date.now() / 1000;
  
        //Axios incluira el token en todas las peticiones
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        // Configurar las pestañas del menú
        const userName = decodificarToken.id;
        console.log(userName); 

      crearUsuario.innerHTML = ` <input type="text" class="form-control" id="idUsuario" name="idUsuario" value="${userName}" hidden>`; 


}

obtenerUsuario();})