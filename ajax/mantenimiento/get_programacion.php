<?php
require_once '../../inc/includes.php';
require_once '../../inc/mantenimiento/ProgramacionManager.php';

// Verificar que se proporcionó un ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    http_response_code(400);
    die(json_encode([
        'success' => false,
        'message' => 'ID de programación no proporcionado'
    ]));
}

try {
    $programacionManager = new ProgramacionManager();
    $programacion = $programacionManager->getById($id);

    if (!$programacion) {
        throw new Exception('Programación no encontrada');
    }

    echo json_encode([
        'success' => true,
        'programacion' => $programacion
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
