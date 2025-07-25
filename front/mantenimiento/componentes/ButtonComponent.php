<?php
/**
 * Componente reutilizable para botones
 */

include_once __DIR__ . '/../config/ColorConfig.php';

class ButtonComponent 
{
    /**
     * Crear un botón con ícono
     * 
     * @param string $text Texto del botón
     * @param string $icon Clase del ícono (ej: 'fas fa-plus')
     * @param string $color Color del botón (ej: 'primary', 'success', 'danger', etc.)
     * @param string $backgroundColor Color de fondo personalizado (ej: 'rgb(39, 85, 192)')
     * @param string $textColor Color del texto (ej: 'white', 'black')
     * @param string $size Tamaño del botón ('sm', 'md', 'lg')
     * @param string $extraClasses Clases CSS adicionales
     * @param string $onClick Función JavaScript para el evento click
     * @param string $id ID del botón
     * @param string $type Tipo de botón ('button', 'submit', 'reset')
     * @return string HTML del botón
     */
    public static function create(
        $text, 
        $icon = null, 
        $color = 'primary', 
        $backgroundColor = null, 
        $textColor = null, 
        $size = 'md', 
        $extraClasses = '', 
        $onClick = null, 
        $id = null, 
        $type = 'button'
    ) {
        // Construir clases CSS
        $classes = ['btn'];
        
        // Si no hay backgroundColor personalizado, usar el color de Bootstrap
        if ($backgroundColor === null) {
            $classes[] = 'btn-' . $color;
        }
        
        // Agregar tamaño si no es mediano
        if ($size !== 'md') {
            $classes[] = 'btn-' . $size;
        }
        
        // Agregar clases extra
        if ($extraClasses) {
            $classes[] = $extraClasses;
        }
        
        $classString = implode(' ', $classes);
        
        // Construir estilos inline
        $styles = [];
        if ($backgroundColor) {
            $styles[] = 'background-color: ' . $backgroundColor;
            
            // Agregar transición suave para el hover
            $styles[] = 'transition: background-color 0.3s ease';
            
            // Si tiene la clase hover-btn, agregar hover con color más oscuro
            if (strpos($extraClasses, 'hover-btn') !== false) {
                $darkerColor = ColorConfig::getDarkerColor($backgroundColor, 10);
                $styles[] = '--hover-bg: ' . $darkerColor;
            }
        }
        if ($textColor) {
            $styles[] = 'color: ' . $textColor;
        }
        
        $styleString = !empty($styles) ? 'style="' . implode('; ', $styles) . '"' : '';
        
        // Construir atributos
        $attributes = [];
        $attributes[] = 'type="' . $type . '"';
        $attributes[] = 'class="' . $classString . '"';
        
        if ($styleString) {
            $attributes[] = $styleString;
        }
        
        if ($onClick) {
            $attributes[] = 'onclick="' . $onClick . '"';
        }
        
        if ($id) {
            $attributes[] = 'id="' . $id . '"';
        }
        
        $attributeString = implode(' ', $attributes);
        
        // Construir contenido del botón
        $content = '';
        if ($icon) {
            $content .= '<i class="' . $icon . ' me-2"></i>';
        }
        $content .= $text;
        
        // Retornar HTML del botón
        return '<button ' . $attributeString . '>' . $content . '</button>';
    }
    
    /**
     * Crear un botón primario (azul)
     */
    public static function primary($text, $icon = null, $extraClasses = '', $onClick = null, $id = null) {
        return self::create($text, $icon, null, ColorConfig::BTN_PRIMARY, 'white', 'md', $extraClasses . ' hover-btn', $onClick, $id);
    }
    
    /**
     * Crear un botón secundario (gris)
     */
    public static function secondary($text, $icon = null, $extraClasses = '', $onClick = null, $id = null) {
        return self::create($text, $icon, null, ColorConfig::BTN_MEGASUR, 'white', 'md', $extraClasses . ' hover-btn', $onClick, $id);
    }
    
    /**
     * Crear un botón de éxito (verde)
     */
    public static function success($text, $icon = null, $extraClasses = '', $onClick = null, $id = null) {
        return self::create($text, $icon, null, ColorConfig::BTN_SAVE, 'white', 'md', $extraClasses . ' hover-btn', $onClick, $id);
    }
    
    /**
     * Crear un botón de autorizar
     */
    public static function authorize($text, $icon = null, $extraClasses = '', $onClick = null, $id = null) {
        return self::create($text, $icon, null, ColorConfig::BTN_AUTHORIZE, 'white', 'md', $extraClasses . ' hover-btn', $onClick, $id);
    }

    /**
     * Crear un botón de completar
     */
    public static function complete($text, $icon = null, $extraClasses = '', $onClick = null, $id = null) {
        return self::create($text, $icon, null, ColorConfig::BTN_COMPLETE, 'white', 'md', $extraClasses . ' hover-btn', $onClick, $id);
    }
    
    /**
     * Crear un botón de editar
     */
    public static function edit($text, $icon = null, $extraClasses = '', $onClick = null, $id = null) {
        return self::create($text, $icon, null, ColorConfig::BTN_EDIT, 'white', 'md', $extraClasses . ' hover-btn', $onClick, $id);
    }
    
    /**
     * Crear un botón personalizado MEGASUR
     */
    public static function custom($text, $icon = null, $extraClasses = '', $onClick = null, $id = null) {
        return self::create($text, $icon, null, ColorConfig::BTN_MEGASUR, 'white', 'md', $extraClasses . ' hover-btn', $onClick, $id);
    }
    
    /**
     * Crear un botón volver (fondo blanco, borde y texto azul)
     */
    public static function volver($text = 'Volver', $icon = 'fas fa-arrow-left', $url = '', $extraClasses = '', $id = null) {
        $styles = [
            'background-color: white',
            'border: 2px solid ' . ColorConfig::BTN_PRIMARY,
            'color: ' . ColorConfig::BTN_PRIMARY,
            'text-decoration: none',
            'display: inline-flex',
            'align-items: center',
            'padding: 0.375rem 0.75rem',
            'font-size: 1rem',
            'border-radius: 0.25rem'
        ];
        
        $attributes = [
            'class="btn ' . $extraClasses . ' me-2"',
            'href="' . $url . '"'
        ];
        
        if ($id) {
            $attributes[] = 'id="' . $id . '"';
        }
        
        $content = '';
        if ($icon) {
            $content .= '<i class="' . $icon . ' me-2" style="color: ' . ColorConfig::BTN_PRIMARY . '"></i>';
        }
        $content .= $text;
        
        return '<a ' . implode(' ', $attributes) . ' style="' . implode('; ', $styles) . '">' . $content . '</a>';
    }

    /**
     * Crear un botón de búsqueda (solo ícono)
     */
    public static function search($onClick = null, $id = null, $extraClasses = '') {
        return '<button type="button" class="btn btn-light ' . $extraClasses . '"' . 
               ($onClick ? ' onclick="' . $onClick . '"' : '') . 
               ($id ? ' id="' . $id . '"' : '') . '>' .
               '<i class="fas fa-search" style="font-weight: 100;"></i>' .
               '</button>';
    }
}
?>
