<?php
require_once '../../inc/includes.php';
require_once '../../inc/mantenimiento/ProgramacionManager.php';


// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Método no permitido']));
}
// Incluir el archivo bootstrap que maneja todas las inclusiones
require_once dirname(dirname(dirname(__FILE__))) . '/inc/mantenimiento/bootstrap.php';

try {
    // Obtener el contenido JSON del body
    $json = file_get_contents('php://input');
    if (!$json) {
        throw new Exception('No se recibieron datos');
    }

    $data = json_decode($json, true);
    if (!$data) {
        throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
    }

    // Validar campos requeridos
    $requiredFields = ['id', 'id_reviso'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("El campo {$field} es requerido");
        }
    }

    // Debug: Registrar los datos que se intentan insertar
    error_log("Datos recibidos en save_programacion.php: " . json_encode($data));

    $programacionManager = new ProgramacionManager();

     // Actualizar la programación
    $success = $programacionManager->markAsReviewed($data['id'], $data['id_reviso']);

    if(!$success) {
        http_response_code(400);
        throw new Exception('No se pudo marcar la programación como revisada');
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