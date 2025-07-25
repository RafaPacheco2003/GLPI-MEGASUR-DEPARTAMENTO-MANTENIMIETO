<?php
// Script básico para exportar un Excel .xlsx con un texto usando PhpSpreadsheet
// Asegurarse de que no haya salida previa
if (headers_sent()) {
    die('Error: headers already sent.');
}
if (ob_get_length()) {
    ob_end_clean();
}
require __DIR__ . '/../../lib/PhpSpreadsheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Hoja1');
$sheet->setCellValue('A1', '¡Hola, este es un Excel de prueba!');




header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="excel_prueba.xlsx"');
header('Cache-Control: max-age=0');
header('Pragma: public');
header('Expires: 0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
