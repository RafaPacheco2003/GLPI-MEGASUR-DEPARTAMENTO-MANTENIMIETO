<?php
/**
 * Configuración centralizada de colores para el sistema MEGASUR
 */
class ColorConfig {
    // Colores Principales Header/Navegación
    const HEADER_BG = '#2f3f64';         // Fondo principal
    const HEADER_HOVER = '#3a4a75';      // Hover navegación
    const LOGO_BG = '#fbbf24';           // Logo "M" fondo (yellow-400)
    const LOGO_TEXT = '#000000';         // Logo "M" texto (black)

    // Estados/Badges
    const STATUS_COMPLETED = '#10b981';   // Completado (green-500)
    const STATUS_AUTHORIZED = '#3b82f6';  // Autorizado (blue-500)
    const STATUS_IN_PROGRESS = '#3b82f6'; // En Progreso (blue-500)
    const STATUS_PENDING = '#eab308';     // Pendiente (yellow-500)

    // Botones Principales
    const BTN_PRIMARY = '#2563eb';        // Primario (blue-600)
    const BTN_PRIMARY_HOVER = '#1d4ed8';  // Primario Hover (blue-700)
    const BTN_MEGASUR = '#2f3f64';        // MEGASUR Específico
    const BTN_MEGASUR_HOVER = '#3a4a75';  // MEGASUR Hover

    // Botones de Acción
    const BTN_SAVE = '#16a34a';           // Guardar (green-600)
    const BTN_SAVE_HOVER = '#15803d';     // Guardar Hover (green-700)
    const BTN_EDIT = '#ea580c';           // Editar/Formulario (orange-600)
    const BTN_EDIT_HOVER = '#c2410c';     // Editar Hover (orange-700)
    const BTN_AUTHORIZE = '#16a34a';      // Autorizar (green-600)
    const BTN_AUTHORIZE_HOVER = '#15803d'; // Autorizar Hover (green-700)
    const BTN_COMPLETE = '#9333ea';       // Completar (purple-600)
    const BTN_COMPLETE_HOVER = '#7c3aed'; // Completar Hover (purple-700)

    // Timeline/Progreso
    const TIMELINE_COMPLETED = '#2563eb';  // Completado (blue-600)
    const TIMELINE_PENDING = '#d1d5db';    // Pendiente (gray-300)
    const TIMELINE_INDICATOR = '#10b981';  // Indicadores verdes (green-500)

    /**
     * Obtener color con opacidad
     * @param string $color Color hexadecimal
     * @param float $opacity Opacidad (0-1)
     * @return string Color con opacidad en formato rgba
     */
    public static function getColorWithOpacity($color, $opacity) {
        // Convertir hex a rgb
        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        return "rgba($r, $g, $b, $opacity)";
    }

    /**
     * Obtener variante más clara del color
     * @param string $color Color hexadecimal
     * @param int $percent Porcentaje de aclarado (0-100)
     * @return string Color aclarado
     */
    public static function getLighterColor($color, $percent) {
        return self::adjustBrightness($color, $percent);
    }

    /**
     * Obtener variante más oscura del color
     * @param string $color Color hexadecimal
     * @param int $percent Porcentaje de oscurecimiento (0-100)
     * @return string Color oscurecido
     */
    public static function getDarkerColor($color, $percent) {
        return self::adjustBrightness($color, -$percent);
    }

    /**
     * Ajustar el brillo de un color
     * @param string $color Color hexadecimal
     * @param int $percent Porcentaje de ajuste (-100 a 100)
     * @return string Color ajustado
     */
    private static function adjustBrightness($color, $percent) {
        $color = ltrim($color, '#');
        if (strlen($color) == 3) {
            $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
        }
        
        $rgb = array_map('hexdec', str_split($color, 2));
        
        foreach ($rgb as &$value) {
            $value = round(max(0, min(255, $value + ($value * ($percent/100)))));
        }
        
        return sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
    }
}
?>
