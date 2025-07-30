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

    // Enviar correo al crear la programación usando función modular
    require_once __DIR__ . '/send_email.php';
    $to = 'rafael.chi@gmegasur.com.mx';
    $subject = 'Nueva programación creada en GLPI Mantenimiento';
    $body = '<b>Se ha creado una nueva programación.</b><br>' .
        'Empresa: ' . htmlspecialchars($data['nombre_empresa']) . '<br>' .
        'Nombre de programación: ' . htmlspecialchars($data['nombre_programacion']) . '<br>' .
        'Fecha de emisión: ' . htmlspecialchars($data['fecha_emision']) . '<br>' .
        'ID de programación: ' . $id . '<br><br>' .
        // Pie de firma con imagen
        '<div style="margin-top:32px; padding-top:16px; border-top:1px solid #eee;">
            <img src="http://localhost//glpi/files/logos/Firma-25-a%C3%B1os.jpg" alt="Firma Grupo Megasur" style="max-width: 100%; height: auto; border-radius: 8px; margin-top: 8px;">
        </div>';
    $from = 'rodrigorafaelchipacheco@gmail.com';
    $fromName = 'GLPI Mantenimiento';
    $emailSent = sendEmail($to, $subject, $body, $from, $fromName);
    if ($emailSent) {
        error_log('Correo de notificación enviado correctamente.');
        echo json_encode([
            'success' => true,
            'message' => 'Programación creada exitosamente',
            'id' => $id
        ]);
    } else {
        error_log('Error al enviar correo de notificación.');
        echo json_encode([
            'success' => false,
            'message' => 'Error al enviar correo de notificación',
            'id' => $id
        ]);
    }

} catch (Exception $e) {
    error_log("Error en save_programacion.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
