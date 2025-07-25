<?php
/**
 * Componente de paginación para programaciones
 */

class ProgramacionPagination {
    /**
     * Renderiza la paginación
     * 
     * @param int $currentPage Página actual
     * @param int $totalPages Total de páginas
     * @return string HTML de la paginación
     */
    public static function render($currentPage, $totalPages) {
        if ($totalPages <= 1) {
            return '';
        }

        $html = '<div class="pagination-container">';
        $html .= '<nav aria-label="Navegación de programaciones" class="d-flex justify-content-center">';
        $html .= '<ul class="pagination">';

        // Botón Primera página
        $html .= self::renderPageItem('Primera página', '1', '&laquo;&laquo;', $currentPage <= 1);

        // Botón Anterior
        $html .= self::renderPageItem('Anterior', ($currentPage - 1), '&laquo;', $currentPage <= 1);

        // Paginación dinámica: solo 4 páginas visibles
        $maxVisible = 4;
        $start = max(1, $currentPage - 1);
        $end = $start + $maxVisible - 1;
        if ($end > $totalPages) {
            $end = $totalPages;
            $start = max(1, $end - $maxVisible + 1);
        }

        for ($i = $start; $i <= $end; $i++) {
            $html .= '<li class="page-item ' . ($i == $currentPage ? 'active' : '') . '">';
            $html .= '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
            $html .= '</li>';
        }

        // Botón Siguiente
        $html .= self::renderPageItem('Siguiente', ($currentPage + 1), '&raquo;', $currentPage >= $totalPages);

        // Botón Última página
        $html .= self::renderPageItem('Última página', $totalPages, '&raquo;&raquo;', $currentPage >= $totalPages);

        $html .= '</ul>';
        $html .= '</nav>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Renderiza un item de paginación
     */
    private static function renderPageItem($label, $page, $symbol, $disabled) {
        return sprintf(
            '<li class="page-item %s">
                <a class="page-link" href="?page=%s" aria-label="%s">
                    <span aria-hidden="true">%s</span>
                </a>
            </li>',
            $disabled ? 'disabled' : '',
            $page,
            $label,
            $symbol
        );
    }

    /**
     * Obtiene los estilos CSS necesarios para la paginación
     */
    public static function getStyles() {
        $cssPath = __DIR__ . '/ProgramacionStyles.css';
        $styles = file_get_contents($cssPath);
        return '<style>' . $styles . '</style>';
    }
}
