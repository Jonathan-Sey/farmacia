document.addEventListener("DOMContentLoaded", function() {
    const checkboxes = document.querySelectorAll('input[name="pestanas[]"]');
    const paginaInicioSelect = document.getElementById('pagina_inicio');

    // Actualizar opciones de página de inicio basado en checkboxes marcados
    function updatePaginaInicioOptions() {
        const selectedPestanas = Array.from(checkboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        // Habilitar/deshabilitar opciones
        Array.from(paginaInicioSelect.options).forEach(option => {
            if (option.value === "") return;
            option.disabled = !selectedPestanas.includes(option.value);
        });

        // Resetear si la selección actual no está en las pestañas marcadas
        if (paginaInicioSelect.value && !selectedPestanas.includes(paginaInicioSelect.value)) {
            paginaInicioSelect.value = "";
        }
    }

    // Event listeners
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updatePaginaInicioOptions);
    });

    // Inicializar
    updatePaginaInicioOptions();
});