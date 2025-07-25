<?php
// Asegurar que solo se devuelva JSON
header('Content-Type: application/json');

try {
    // Incluir el archivo bootstrap que maneja todas las inclusiones
    require_once dirname(dirname(dirname(__FILE__))) . '/inc/mantenimiento/bootstrap.php';

    // Verificar que sea una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Obtener el contenido JSON del body
    $json = file_get_contents('php://input');
    if (!$json) {
        throw new Exception('No se recibieron datos');
    }

    $data = json_decode($json, true);
    if (!$data) {
        throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
    }

    // Debug: Registrar los datos que se intentan insertar
    error_log("Datos recibidos en save_programacion.php: " . json_encode($data));

    $programacionManager = new ProgramacionManager();

    // Crear la programación
    $id = $programacionManager->create([
        'nombre_empresa' => trim($data['nombre_empresa']),
        'nombre_programacion' => $data['nombre_programacion'],
        'fecha_emision' => $data['fecha_emision'],
        'id_elaboro' => empty($data['id_elaboro']) ? null : intval($data['id_elaboro']),
        'firma_elaboro' => $data['firma_elaboro']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Programación creada exitosamente',
        'id' => $id
    ]);

} catch (Exception $e) {
    error_log("Error en save_programacion.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
