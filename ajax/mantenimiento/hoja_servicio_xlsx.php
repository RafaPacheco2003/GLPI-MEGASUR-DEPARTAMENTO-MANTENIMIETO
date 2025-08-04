<?php
// Exporta hoja de servicio a Excel (.xlsx) usando PhpSpreadsheet
require __DIR__ . '/../../lib/PhpSpreadsheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Crear hoja de cálculo y configurar encabezado
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Forzar vista "Diseño de página" (Page Layout) al abrir el archivo
$sheet->getSheetView()->setView(\PhpOffice\PhpSpreadsheet\Worksheet\SheetView::SHEETVIEW_PAGE_LAYOUT);

$logoPath = __DIR__ . '/../../files/logos/SGI.jpeg';
$nombreProgramacionHeader = 'Hoja de servicio sistemas';

if (file_exists($logoPath)) {
    $sheet->getHeaderFooter()->setOddHeader('&L&G&C&10&B' . $nombreProgramacionHeader . '&C');
    $headerImage = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
    $headerImage->setName('SGI Logo');
    $headerImage->setPath($logoPath);
    $headerImage->setHeight(40);
    $headerImage->setOffsetY(-10);
    $sheet->getHeaderFooter()->addImage($headerImage, \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter::IMAGE_HEADER_LEFT);
} else {
    $sheet->getHeaderFooter()->setOddHeader('&C&10&B' . $nombreProgramacionHeader . '&C');
}



// Limpiar el buffer de salida para evitar errores de encabezado
if (ob_get_length()) ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="hoja_servicio.xlsx"');
header('Cache-Control: max-age=0');
header('Pragma: public');
header('Expires: 0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
