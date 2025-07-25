<?php
// Exporta información de una programación a Excel (.xlsx) usando PhpSpreadsheet
require __DIR__ . '/../../lib/PhpSpreadsheet/autoload.php';
require_once __DIR__ . '/../../inc/mantenimiento/ProgramacionManager.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Validar y obtener el parámetro id
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    exit('ID de programación no válido.');
}

$manager = new ProgramacionManager();
$programacion = $manager->getById($id);
if (!$programacion) {
    exit('No se encontró la programación.');
}



// Crear hoja de cálculo y configurar encabezado
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$nombreProgramacion = $programacion['nombre_programacion'] ?? '';


$logoPath = __DIR__ . '/../../files/logos/SGI.jpeg';
$nombreProgramacionHeader = (function($texto, $max = 50) {
    if (strpos($texto, "\n") !== false || strpos($texto, "\r") !== false) {
        return $texto;
    }
    if (mb_strlen($texto) > $max) {
        $mid = (int)(mb_strlen($texto) / 2);
        $espacioCercano = mb_strrpos(mb_substr($texto, 0, $mid), ' ');
        if ($espacioCercano !== false) {
            return trim(mb_substr($texto, 0, $espacioCercano)) . "\n" . trim(mb_substr($texto, $espacioCercano));
        }
        return wordwrap($texto, $mid, "\n", true);
    }
    return $texto;
})($nombreProgramacion);

if (file_exists($logoPath)) {
    $sheet->getHeaderFooter()->setOddHeader('&L&G&C&10&B' . $nombreProgramacionHeader . '&C');
    $headerImage = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
    $headerImage->setName('SGI Logo');
    $headerImage->setPath($logoPath);
    $headerImage->setHeight(40); // Más pequeño para que no sobresalga
    $headerImage->setOffsetY(-10); // Sube la imagen para alinearla mejor
    $sheet->getHeaderFooter()->addImage($headerImage, \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter::IMAGE_HEADER_LEFT);
} else {
    $sheet->getHeaderFooter()->setOddHeader('&C&10&B' . $nombreProgramacionHeader . '&C');
}

// --- Agregar imagen fija como en excel_prueba_xlsx.php ---
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

function obtenerFirmaPath($firmaArchivo, $defaultFirmaPath) {
    $firmaArchivo = trim($firmaArchivo);
    $firmaPath = __DIR__ . '/../../files/firmas/' . $firmaArchivo;
    if ($firmaArchivo && file_exists($firmaPath)) {
        return $firmaPath;
    }
    if (file_exists($defaultFirmaPath)) {
        return $defaultFirmaPath;
    }
    return null;
}

$firmaPathFinal = obtenerFirmaPath($programacion['firma_elaboro'] ?? '', __DIR__ . '/../../files/firmas/firma_6883e075053cc.png');
$tempImage = null;

if ($firmaPathFinal) {
    $tempImage = sys_get_temp_dir() . '/temp_excel_img.png';
    copy($firmaPathFinal, $tempImage);

    $sheet->getColumnDimension('B')->setWidth(25);
    $sheet->getRowDimension(3)->setRowHeight(100);

    $drawing = new Drawing();
    $drawing->setName('FirmaElaboro');
    $drawing->setDescription('Firma de quien elaboró');
    $drawing->setPath($tempImage);
    $drawing->setHeight(100);
    $drawing->setWidth(120);
    $drawing->setCoordinates('B6');
    $drawing->setWorksheet($sheet);
}

// Encabezados y datos
$headers = ['Nombre Empresa', 'Nombre Programación', 'Fecha Emisión', 'ID Elaboró', 'Firma Elaboró'];

$fields  = ['nombre_empresa', 'nombre_programacion', 'fecha_emision', 'id_elaboro', 'firma_elaboro'];

// Escribir encabezados en la fila 1
foreach ($headers as $i => $header) {
    $col = chr(65 + $i) . '1';
    $sheet->setCellValue($col, $header);
}

// Escribir datos en la fila 2
foreach ($fields as $i => $field) {
    $col = chr(65 + $i) . '2';
    $sheet->setCellValue($col, $programacion[$field] ?? '');
}

// Limpiar el buffer de salida para evitar errores de encabezado
if (ob_get_length()) ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="excel_programacion.xlsx"');
header('Cache-Control: max-age=0');
header('Pragma: public');
header('Expires: 0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
