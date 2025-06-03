// Configuración global para todos los select2
$(document).ready(function() {
    // Inicializar todos los selects con clase select2-custom
    $('.select2-custom').select2({
        width: '100%',
        allowClear: true,
        placeholder: function() {
            return $(this).data('placeholder') || "Buscar...";
        },
        templateResult: formatOption,
        templateSelection: formatSelection
    });

    // Función para formatear cómo se muestran los resultados en el dropdown
    function formatOption(option) {
        if (!option.id) {
            return option.text;
        }
        // Mostrar el nombre completo en el dropdown
        var nombreCompleto = $(option.element).data('nombre-completo') || option.text;
        return $('<div>' + nombreCompleto + '</div>');
    }

    // Función para formatear cómo se muestra la selección en el select
    function formatSelection(option) {
        if (!option.id) {
            return option.text;
        }
        // Obtener el nombre completo
        var nombreCompleto = $(option.element).data('nombre-completo') || option.text;
        var maxLength = $(option.element).closest('select').data('max-length') || 30;

        // Truncar el nombre si es necesario
        var nombreTruncado = nombreCompleto.length > maxLength
            ? nombreCompleto.substring(0, maxLength) + '...'
            : nombreCompleto;

        return nombreTruncado;
    }

    // Posicionar el cursor en el input para buscar
    $(document).on('select2:open', '.select2-custom', function() {
        document.querySelector('.select2-search__field').focus();
    });
});