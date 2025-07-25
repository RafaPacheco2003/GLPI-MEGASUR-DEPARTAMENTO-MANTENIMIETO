<?php
// Script para descargar un archivo PHP de prueba
define('ARCHIVO_PRUEBA', 'archivo_prueba.php'); // Cambia el nombre si lo necesitas

if (file_exists(ARCHIVO_PRUEBA)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename(ARCHIVO_PRUEBA) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize(ARCHIVO_PRUEBA));
    ob_clean();
    flush();
    readfile(ARCHIVO_PRUEBA);
    exit;
} else {
    echo "El archivo no existe.";
}
?>
