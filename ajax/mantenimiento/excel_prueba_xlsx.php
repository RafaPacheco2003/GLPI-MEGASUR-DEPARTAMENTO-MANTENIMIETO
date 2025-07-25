<?php
// Script para exportar un Excel .xlsx con imagen usando PhpSpreadsheet
require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Prueba');

// Datos de ejemplo
$sheet->setCellValue('A1', 'Columna 1');
$sheet->setCellValue('B1', 'Columna 2');
$sheet->setCellValue('C1', 'Columna 3');
$sheet->setCellValue('A2', 'Dato 1');
$sheet->setCellValue('B2', 'Dato 2');
$sheet->setCellValue('C2', 'Dato 3');
$sheet->setCellValue('A3', 'Otro 1');
$sheet->setCellValue('B3', 'Otro 2');
$sheet->setCellValue('C3', 'Otro 3');

// Descargar imagen de internet y guardarla temporalmente
$imageUrl = 'https://upload.wikimedia.org/wikipedia/commons/f/f9/Wikimedia_Brand_Guidelines_Update_2022_Wikimedia_Logo_Brandmark.png';
$tmpImage = tempnam(sys_get_temp_dir(), 'img');
file_put_contents($tmpImage, file_get_contents($imageUrl));

// Insertar imagen en la hoja
$drawing = new Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo de prueba');
$drawing->setPath($tmpImage);
$drawing->setHeight(68);
$drawing->setCoordinates('B5');
$drawing->setWorksheet($sheet);

// Enviar el archivo al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="excel_prueba.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Eliminar imagen temporal
@unlink($tmpImage);
?>
