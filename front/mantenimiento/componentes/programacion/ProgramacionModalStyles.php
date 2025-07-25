<?php
/**
 * Componente de estilos para el modal de programación
 */
class ProgramacionModalStyles {
    /**
     * Obtiene los estilos CSS necesarios para el modal
     */
    public static function getStyles() {
        $cssPath = __DIR__ . '/ModalStyles.css';
        $styles = file_get_contents($cssPath);
        return '<style>' . $styles . '</style>';
    }
}
