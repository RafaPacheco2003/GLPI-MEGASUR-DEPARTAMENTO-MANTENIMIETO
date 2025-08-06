<?php
/**
 * Componente para mostrar una card de servicio
 */

include_once __DIR__ . '/../../config/ColorConfig.php';

class ServicioCard {
    /**
     * Renderiza una card de servicio
     * 
     * @param string $titulo Título del servicio (nombre del servidor)
     * @param string $afectacion Descripción de la afectación
     * @param string $estado Estado del servicio (Completado, En progreso, etc.)
     * @param int $progreso Número de pasos completados (1-3)
     * @param string $url URL para el enlace de la card (opcional)
     * @return string HTML de la card
     */
    public static function render($titulo, $afectacion, $estado, $progreso = 3, $url = '#', $id_estacion = null) {
        // Asegurarse de que $id_estacion esté definido
        // (ya está como parámetro, pero para evitar warnings en algunos entornos)
        if (!isset($id_estacion)) {
            $id_estacion = null;
        }
        // Escapar valores para prevenir XSS
        $tituloSeguro = htmlspecialchars($titulo);
        if (strtoupper(trim($tituloSeguro)) === 'N/A') {
            if ($id_estacion) {
                // Consultar el nombre de la sucursal
                $nombreSucursal = null;
                $endpoint = __DIR__ . '/../../config/get_sucursales_detalle.php?id=' . urlencode($id_estacion);
                $urlApi = 'http://localhost/glpi/front/mantenimiento/config/get_sucursales_detalle.php?id=' . urlencode($id_estacion);
                $response = @file_get_contents($urlApi);
                if ($response !== false) {
                    $data = json_decode($response, true);
                    if (is_array($data) && isset($data[0]['NombreSucursal'])) {
                        $nombreSucursal = $data[0]['NombreSucursal'];
                    }
                }
                $tituloSeguro = $nombreSucursal ? htmlspecialchars($nombreSucursal) : 'Estación: ' . htmlspecialchars($id_estacion);
            } else {
                $tituloSeguro = 'No tiene titulo';
            }
        }
        $afectacionSegura = htmlspecialchars($afectacion);
        $estadoSeguro = htmlspecialchars($estado);
        $urlSegura = htmlspecialchars($url);

        // Generar los puntos de progreso
        $puntosProgreso = '';
        for ($i = 1; $i <= 3; $i++) {
            // Si i es menor o igual al progreso actual, pintar verde, si no, gris
            $color = $i <= $progreso ? ColorConfig::STATUS_COMPLETED : '#e5e7eb';
            $puntosProgreso .= "
                <span style=\"display: inline-block; width: 12px; height: 12px; 
                             background-color: {$color}; border-radius: 50%; margin: 0 2px;\">
                </span>";
        }

        // Determinar el color del estado
        $colorEstado = self::getColorEstado($estado);
        $colorBorde = ColorConfig::BTN_PRIMARY;

        // Generar un id único para cada card usando el título y un hash simple
        $cardId = 'servicio_' . md5($tituloSeguro . $afectacionSegura . $estadoSeguro . $urlSegura);
        // Generar HTML de la card con atributo draggable y id único
        return <<<HTML
        <div class="card servico-card mb-3 w-100 shadow-sm rounded-3" id="{$cardId}" draggable="true"
             style="border-left: 5px solid {$colorBorde}; transition: box-shadow 0.3s ease;">
            <style>
                .servico-card:hover {
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
                }
            </style>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">{$tituloSeguro}</h5>
                    <a href="{$urlSegura}" class="text-primary" style="font-size: 1rem;">
                        <i class="fas fa-chevron-right text-secondary"></i>
                    </a>
                </div>
                <p class="card-text mt-2 text-muted">{$afectacionSegura}</p>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="px-3 py-1 rounded-pill text-white fw-semibold"
                          style="font-size: 0.7rem; background-color: {$colorEstado};">
                        {$estadoSeguro}
                    </span>

                    <div class="d-flex align-items-center gap-2">
                        <span style="font-weight: 600; font-size: 0.9rem; margin-right: 8px;">Progreso:</span>
                        <!-- Bolitas de progreso -->
                        {$puntosProgreso}
                    </div>
                </div>
            </div>
        </div>
HTML;
    }

    /**
     * Obtiene el color correspondiente al estado
     * 
     * @param string $estado Estado del servicio
     * @return string Color hexadecimal
     */
    private static function getColorEstado($estado) {
        switch (strtolower($estado)) {
            case 'asignado':
                return ColorConfig::STATUS_PENDING; // Amarillo para asignado
            case 'proceso':
                return ColorConfig::TIMELINE_PENDING; // Azul para en proceso
            case 'completado':
                return ColorConfig::STATUS_COMPLETED; // Verde para completado
            default:
                return ColorConfig::BTN_PRIMARY;
        }
    }
}
