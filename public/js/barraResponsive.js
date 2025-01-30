document.addEventListener('DOMContentLoaded', function () {
    const menuBtn = document.getElementById('menu-btn');
    const menu = document.getElementById('menu');

    
    if (menuBtn && menu) {
  
        menuBtn.addEventListener('click', function() {  
            menu.classList.toggle('hidden'); 
            

    const token = localStorage.getItem('jwt_token');
    if (token) {
        const decodedToken = jwt_decode(token);
        const pestanas = decodedToken.pestanas || [];
        
        setupNavigation(pestanas);
    }
});

window.addEventListener('resize', () => { 
    const menu = document.getElementById('menu'); 
    if (window.innerWidth >= 768) { menu.classList.add('hidden'); } 
});
}


    // Función para configurar la navegación dinámica según las pestañas
    function setupNavigation(pestanas) {
        
       const navItems = document.querySelectorAll('[data-pestanal]');
        navItems.forEach(item => item.style.display = 'none'); // Ocultar todas las pestañas

        pestanas.forEach(pestana => {
            var nev;
            const navItem = document.querySelector(`[data-pestanal="${pestana}"]`);
            if (navItem) {
                navItem.style.display = 'block'; // Mostrar las pestañas correspondientes
                
            }
          
        });
        // Mostrar siempre el botón de cerrar sesión
      const logoutBtn = document.getElementById('logout-btn-mobile');
            if (logoutBtn) {
            logoutBtn.style.display = 'block';
         }
    }
});



