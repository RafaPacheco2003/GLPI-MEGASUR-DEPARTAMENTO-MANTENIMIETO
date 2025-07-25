<?php

// Definir la ruta base
define('GLPI_ROOT', dirname(dirname(dirname(__FILE__))));

// Incluir archivos base de GLPI si es necesario
if (file_exists(GLPI_ROOT . '/inc/includes.php')) {
    require_once GLPI_ROOT . '/inc/includes.php';
}

// Incluir las clases del módulo de mantenimiento
require_once __DIR__ . '/MantenimientoConnection.php';
require_once __DIR__ . '/ProgramacionManager.php';

// Configurar el reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', GLPI_ROOT . '/files/logs/mantenimiento.log');

// Asegurar que la zona horaria está configurada
date_default_timezone_set('America/Mexico_City');
