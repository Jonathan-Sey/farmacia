document.addEventListener('DOMContentLoaded', function () {function obtenerUsuario(){
    const token = localStorage.getItem('jwt_token');
    const crearUsuario = document.getElementById('usuario');

    if (!token) {
        alert("No se encontró un token activo");
        return;
    }
<<<<<<< HEAD
    
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
=======

    const decodificarToken = jwt_decode(token);


        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;


        const UserId = decodificarToken.id;

      console.log(UserId)
    crearUsuario.innerHTML = ` <input type="text" class="form-control" id="idUsuario" name="idUsuario" value="${UserId}" hidden>`;
    document.getElementById("idUsuario").style.display = "none";

}

obtenerUsuario();})
>>>>>>> main
