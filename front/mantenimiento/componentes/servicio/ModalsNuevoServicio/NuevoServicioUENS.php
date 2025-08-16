<?php
// Componente modal para UENS
class NuevoServicioUENS {
    public static function getStyles() {
        // Puedes agregar estilos personalizados aquí si lo necesitas
        return '';
    }
    public static function render() {
        return '<div class="modal fade" id="modalNuevoServicioUENS" tabindex="-1" aria-labelledby="modalNuevoServicioUENSLabel" aria-hidden="true">'
            . '<div class="modal-dialog">'
            . '<div class="modal-content">'
            . '<div class="modal-header">'
            . '<h5 class="modal-title" id="modalNuevoServicioUENSLabel">hola</h5>'
            . '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'
            . '</div>'
            . '<div class="modal-body">'
            . '<p>Este es un modal personalizado para UENS.</p>'
            . '</div>'
            . '</div>'
            . '</div>'
            . '</div>';
    }
    public static function getScript() {
        // Si necesitas JS personalizado para este modal, agrégalo aquí
        return '';
    }
}
