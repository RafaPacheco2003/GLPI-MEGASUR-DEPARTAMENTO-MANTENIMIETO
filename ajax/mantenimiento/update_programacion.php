<?php
require_once '../../inc/includes.php';
require_once '../../inc/mantenimiento/ProgramacionManager.php';

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Método no permitido']));
}

try {
    // Obtener y decodificar los datos JSON
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    if (!$data) {
        throw new Exception('Datos inválidos');
    }

    // Validar campos requeridos
    $requiredFields = ['id', 'nombre_empresa', 'nombre_programacion', 'fecha_emision'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("El campo {$field} es requerido");
        }
    }

    // Crear instancia del ProgramacionManager
    $programacionManager = new ProgramacionManager();

    // Actualizar la programación
    $success = $programacionManager->update($data['id'], $data);

    if (!$success) {
        http_response_code(400);
        throw new Exception('No se pudo actualizar la programación');
    }

    // Devolver respuesta exitosa
    http_response_code(200); // Asegurar que el código de estado sea 200 OK
    echo json_encode([
        'success' => true,
        'message' => 'Programación actualizada exitosamente'
    ]);

} catch (Exception $e) {
    // Solo establecer código 400 si no se ha establecido ya
    if (http_response_code() === 200) {
        http_response_code(400);
    }
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
