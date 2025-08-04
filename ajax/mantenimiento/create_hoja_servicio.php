<?php


// Habilitar todos los errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handler para errores fatales
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null) {
        $msg = date('c') . " - FATAL ERROR: [{$error['type']}] {$error['message']} en {$error['file']}:{$error['line']}\n";
        file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', $msg, FILE_APPEND);
        if (!headers_sent()) {
            http_response_code(500);
        }
        echo json_encode(['success'=>false,'message'=>'FATAL: ' . $error['message']]);
        exit;
    }
});

// Log simple para saber si el archivo se ejecuta
file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', date('c') . " - INICIO create_hoja_servicio.php\n", FILE_APPEND);


file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', date('c') . " - Incluyendo includes.php\n", FILE_APPEND);
include '../../inc/includes.php';
file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', date('c') . " - Incluyendo HojaServicio.php\n", FILE_APPEND);
include '../../inc/mantenimiento/HojaServicio.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido (solo POST)'
    ]);
    file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', date('c') . " - Metodo no permitido\n", FILE_APPEND);
    exit;
}



try {
    $jsonData = file_get_contents('php://input');
    file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', date('c') . " - RAW POST: " . $jsonData . "\n", FILE_APPEND);
    $data = json_decode($jsonData, true);

    if (!$data) {
        file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', date('c') . " - Datos inválidos\n", FILE_APPEND);
        throw new Exception('Datos inválidos');
    }

    // Validar campos requeridos
    $requiredFields = [
        'id_estacion',
        'id_servicio',
        'id_material',
        'folio',
        'serie',
        'fecha_inicio',
        'fecha_fin',
        'descripcion',
        'id_entregado',
        'id_recibido',
        'firma_entregado',
        'firma_recibido',
        'tipo_servicio',
    ];

    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', date('c') . " - FALTA CAMPO: {$field}\n", FILE_APPEND);
            throw new Exception("El campo {$field} es requerido");
        }
    }

    file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', date('c') . " - Instanciando HojaServicio\n", FILE_APPEND);
    $hojaServicio = new HojaServicio();
    file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', date('c') . " - Llamando crearHojaServicio\n", FILE_APPEND);
    $hojaServicioId = $hojaServicio->crearHojaServicio($data);
    file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', date('c') . " - EXITO id: {$hojaServicioId}\n", FILE_APPEND);
    echo json_encode([
        'success' => true,
        'message' => 'Servicio creado exitosamente',
        'id' => $hojaServicioId
    ]);
} catch (Exception $e) {
    http_response_code(500);
    file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', date('c') . " - ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}

// Si por alguna razón no se imprime nada, forzar salida
file_put_contents(__DIR__ . '/debug_create_hoja_servicio.log', date('c') . " - FIN SIN RESPUESTA\n", FILE_APPEND);
echo json_encode(['success'=>false,'message'=>'Sin respuesta del backend']);
exit;