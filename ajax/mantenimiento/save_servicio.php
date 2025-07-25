<?php
require_once '../../inc/includes.php';
require_once '../../inc/mantenimiento/ServicioManager.php';

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
    $requiredFields = [
        'fecha_inicio',
        'servidor_site',
        'serie_id',
        'estatus',
        'afectacion',
        'serie_folio_hoja_servicio',
        'id_programacion'
    ];

    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("El campo {$field} es requerido");
        }
    }

    // Crear instancia del ServicioManager
    $servicioManager = new ServicioManager();

    // Crear el servicio
    $id = $servicioManager->crearServicioServidorRed($data);

    // Devolver respuesta exitosa
    echo json_encode([
        'success' => true,
        'message' => 'Servicio creado exitosamente',
        'id' => $id
    ]);

} catch (Exception $e) {
    // Devolver error
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
