<?php
/**
 * Componente para mostrar una card de programación
 */

include_once __DIR__ . '/../../config/ColorConfig.php';

class ProgramacionCard {
    /**
     * Renderiza una card de programación
     * 
     * @param string $titulo Título de la programación
     * @param string $descripcion Descripción de la programación
     * @param int $numServicios Número de servicios
     * @param string $estado Estado de la programación (Pendiente, Autorizado, etc.)
     * @param string $fechaEmision Fecha de emisión
     * @param string $url URL para el enlace de la card
     * @return string HTML de la card
     */
    public static function render($titulo, $descripcion, $numServicios, $estado, $fechaEmision, $id_programacion) {
        // Determinar el color del estado
        $colorEstado = self::getColorEstado($estado);
        
        // Generar la URL con el ID de la programación
        $url = "servicio.php?id=" . $id_programacion;
        
        // Escapar valores para prevenir XSS
        $tituloSeguro = htmlspecialchars($titulo);
        $descripcionSegura = htmlspecialchars($descripcion);
        $numServiciosSeguro = htmlspecialchars($numServicios);
        $estadoSeguro = htmlspecialchars($estado);
        $fechaEmisionSegura = htmlspecialchars($fechaEmision);
        $urlSegura = htmlspecialchars($url);

        // Preparar variables para el HTML
        $borderColor = ColorConfig::BTN_PRIMARY;

        // Usar HEREDOC para mejor legibilidad del HTML
        $html = <<<HTML
        <a href="$urlSegura" class="text-decoration-none card-link" onclick="return handleCardClick(event, this)">
            <div class="programacion-card card mb-3 w-100 shadow-sm rounded-3" style="border-left: 5px solid $borderColor; transition: all 0.3s ease; cursor: pointer;">
                <style>
                    .programacion-card:hover {
                        box-shadow: 4px 4px 12px rgba(0,0,0,0.25) !important;
                    }
                    /* Estilo para cuando el modal está abierto */
                    body.modal-open .card-link {
                        pointer-events: none;
                        cursor: default;
                    }
                    body:not(.modal-open) .card-link {
                        pointer-events: auto;
                        cursor: pointer;
                    }
                </style>
                <div class="card-body">
                    <!-- Encabezado de la card -->
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-dark">$tituloSeguro</h5>
                        <span class="text-primary" style="font-size: 1rem;">
                            <i class="fas fa-chevron-right" style="color:grey;"></i>
                        </span>
                    </div>

                    <!-- Descripción -->
                    <p class="card-text mt-2">$descripcionSegura</p>

                    <!-- Información adicional -->
                    <div class="d-flex justify-content-start align-items-center">
                        <p class="me-4 mb-0">$numServiciosSeguro servicios</p>
                        <span class="me-4 mb-0 px-3 py-1 rounded-pill text-white fw-semibold"
                              style="font-size: 0.7rem; background-color: $colorEstado;">
                            $estadoSeguro
                        </span>
                        <p class="mb-0">Fecha de emisión: $fechaEmisionSegura</p>
                    </div>
                </div>
            </div>
        </a>
        <script>
            function handleCardClick(event, link) {
                // Si hay un modal abierto, prevenir la redirección
                if (document.body.classList.contains('modal-open')) {
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }
                return true;
            }
        </script>
HTML;

        return $html;
    }

    /**
     * Obtiene el color correspondiente al estado
     * 
     * @param string $estado Estado de la programación
     * @return string Color hexadecimal
     */
    private static function getColorEstado($estado) {
        switch (strtolower($estado)) {
            case 'pendiente':
                return ColorConfig::STATUS_PENDING;
            case 'autorizado':
                return ColorConfig::STATUS_AUTHORIZED;
            case 'en progreso':
                return ColorConfig::STATUS_IN_PROGRESS;
            case 'completado':
                return ColorConfig::STATUS_COMPLETED;
            default:
                return ColorConfig::BTN_PRIMARY;
        }
    }
}
?>
