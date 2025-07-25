<?php
// Evitar cualquier salida antes del JSON
error_reporting(0);
ini_set('display_errors', 0);

require_once '../../inc/mantenimiento/ProgramacionManager.php';

// Asegurarse de que no haya salida antes del JSON
if (ob_get_length()) ob_clean();
header('Content-Type: application/json');

try {
    $manager = new ProgramacionManager();
    $valores = $manager->getNombreProgramacionEnum();
    
    if (empty($valores)) {
        throw new Exception('No se pudieron obtener las opciones del tipo de programación');
    }
    
    echo json_encode([
        'success' => true,
        'data' => $valores
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
