<?php
// Asegurar que solo se devuelva JSON
header('Content-Type: application/json');

try {
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
    if (!isset($data['image'])) {
        throw new Exception('No se recibió la imagen');
    }

    $imageData = $data['image'];
    // Validar formato base64
    if (strpos($imageData, 'data:image/png;base64,') === 0) {
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
    } else if (strpos($imageData, 'data:image/jpeg;base64,') === 0) {
        $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
    }

    $imageData = base64_decode($imageData);
    if ($imageData === false) {
        throw new Exception('Error al decodificar la imagen');
    }

    // Crear carpeta si no existe
    $dir = dirname(dirname(dirname(__FILE__))) . '/files/firmas/';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // Generar nombre único para la imagen
    $fileName = 'firma_' . uniqid() . '.png';
    $filePath = $dir . $fileName;

    // Guardar la imagen en el servidor
    if (file_put_contents($filePath, $imageData) === false) {
        throw new Exception('No se pudo guardar la imagen');
    }

    echo json_encode([
        'success' => true,
        'fileName' => $fileName
    ]);

} catch (Exception $e) {
    error_log('Error en upload_signature.php: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
