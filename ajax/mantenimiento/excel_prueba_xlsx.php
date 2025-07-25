<?php
// Script básico para exportar un Excel .xlsx con un texto usando PhpSpreadsheet
// Asegurarse de que no haya salida previa
require __DIR__ . '/../../lib/PhpSpreadsheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Hoja1');
$sheet->setCellValue('A1', '¡Hola, este es un Excel de prueba!');

// Encabezado de hoja de diseño (encabezado de página, no celda)
$sheet->getHeaderFooter()->setOddHeader('&C&BUno de prueba - Encabezado de diseño&C');

// --- Agregar imagen desde internet ---
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


$imageUrl = __DIR__ . '/../../files/firmas/firma_6883e075053cc.png'; // Imagen desde la ruta local del servidor
$tempImage = sys_get_temp_dir() . '/temp_excel_img.png';
// Copiar la imagen desde la ruta local a la ruta temporal
if (file_exists($imageUrl)) {
    copy($imageUrl, $tempImage);
} else {
    die('No se encontró la imagen en la ruta especificada.');
}


// Ajustar ancho de columna B y altura de fila 3
$sheet->getColumnDimension('B')->setWidth(25); // Puedes ajustar el valor
$sheet->getRowDimension(3)->setRowHeight(100); // Puedes ajustar el valor

$drawing = new Drawing();
$drawing->setName('ImagenPrueba');
$drawing->setDescription('Imagen adjunta por el usuario');
$drawing->setPath($tempImage);
$drawing->setHeight(100); // Igual a la altura de la fila
$drawing->setWidth(120);  // Aproximadamente igual al ancho de la columna (puedes ajustar)
$drawing->setCoordinates('B3'); // Celda donde se insertará la imagen
$drawing->setWorksheet($sheet);




header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="excel_prueba.xlsx"');
header('Cache-Control: max-age=0');
header('Pragma: public');
header('Expires: 0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Eliminar imagen temporal
if (file_exists($tempImage)) {
    unlink($tempImage);
}
exit;
?>
