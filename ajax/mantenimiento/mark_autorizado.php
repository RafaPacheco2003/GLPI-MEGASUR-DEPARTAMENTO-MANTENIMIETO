<?php
// Forzar cabecera JSON y evitar cualquier salida antes del JSON
while (ob_get_level()) ob_end_clean();
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);
require_once '../../inc/includes.php';
require_once '../../inc/mantenimiento/ProgramacionManager.php';


// Verificar que la solicitud sea PUT
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    error_log('[mark_autorizado.php] Método no permitido: ' . $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Método no permitido']));
}
// Incluir el archivo bootstrap que maneja todas las inclusiones
require_once dirname(dirname(dirname(__FILE__))) . '/inc/mantenimiento/bootstrap.php';

try {
    // Obtener el contenido JSON del body

    $json = file_get_contents('php://input');
    if (!$json) {
        error_log('[mark_autorizado.php] No se recibieron datos');
        throw new Exception('No se recibieron datos');
    }

    $data = json_decode($json, true);
    if (!$data) {
        error_log('[mark_autorizado.php] Error al decodificar JSON: ' . json_last_error_msg());
        throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
    }

    // Validar campos requeridos
    $requiredFields = ['id', 'id_autorizo', 'firma_autorizo'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || $data[$field] === '' || $data[$field] === null) {
            error_log("[mark_autorizado.php] El campo {$field} es requerido o vacío");
            throw new Exception("El campo {$field} es requerido");
        }
    }

    // Debug: Registrar los datos que se intentan insertar
    error_log("[mark_autorizado.php] Datos recibidos: " . json_encode($data));

    $programacionManager = new ProgramacionManager();

    // Actualizar la programación con firma
    $success = false;
    try {
        $success = $programacionManager->markAsAuthorized($data['id'], $data['id_autorizo'], $data['firma_autorizo']);
        error_log('[mark_autorizado.php] markAsAuthorized retorno: ' . var_export($success, true));
    } catch (Exception $ex) {
        error_log('[mark_autorizado.php] Excepción en markAsAuthorized: ' . $ex->getMessage());
        throw $ex;
    }

    if(!$success) {
        error_log('[mark_autorizado.php] No se pudo marcar la programación como autorizada. ID: ' . $data['id']);
        http_response_code(400);
        throw new Exception('No se pudo marcar la programación como autorizada');
    }

    // Enviar correo de notificación al marcar como autorizado
    require_once __DIR__ . '/send_email.php';
    $to = 'rafael.chi@gmegasur.com.mx';
    $subject = 'Programación AUTORIZADA en GLPI Mantenimiento';
    $body = '<b>La programación con ID ' . htmlspecialchars($data['id']) . ' ha sido <span style="color:green;">AUTORIZADA</span>.</b><br>' .
        'ID de programación: ' . htmlspecialchars($data['id']) . '<br>' .
        'ID de quien autorizó: ' . htmlspecialchars($data['id_autorizo']) . '<br><br>' .
        '<div style="margin-top:32px; padding-top:16px; border-top:1px solid #eee;">
            <img src="https://files.mega-digital.net/GLPI-MEGASUR-DEPARTAMENTO-MANTENIMIETO/firmas/enme-megasur-25.png" alt="Firma Grupo Megasur" style="max-width: 100%; height: auto; border-radius: 8px; margin-top: 8px;">
        </div>';
    $from = 'rodrigorafaelchipacheco@gmail.com';
    $fromName = 'GLPI Mantenimiento';
    sendEmail($to, $subject, $body, $from, $fromName);

    // Devolver respuesta exitosa
    http_response_code(200); // Asegurar que el código de estado sea 200 OK
    echo json_encode([
        'success' => true,
        'message' => 'La programación ha sido autorizada.'
    ]);

} catch (Exception $e) {
    // Limpiar cualquier salida previa
    if (ob_get_length()) ob_end_clean();
    // Solo establecer código 400 si no se ha establecido ya
    if (http_response_code() === 200) {
        http_response_code(400);
    }
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}