<?php
// Exporta hoja de servicio a Excel (.xlsx) usando PhpSpreadsheet
require __DIR__ . '/../../lib/PhpSpreadsheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// --- 1. Crear hoja de cálculo ---
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


// --- 3. Configuración visual general ---
$sheet->getSheetView()->setView(\PhpOffice\PhpSpreadsheet\Worksheet\SheetView::SHEETVIEW_PAGE_LAYOUT);

// --- 4. Encabezado con logo ---
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

// --- 5. Estilos y formato compacto ---
$highestColumn = $sheet->getHighestColumn();
$highestRow = $sheet->getHighestRow();
$sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
    'font' => [ 'size' => 10 ],
]);

// Bordes exteriores gruesos en filas 3 y 4 de la columna A a la J
$colInicio = 'A';
$colFin = 'J';
foreach ([3, 4] as $fila) {
    $rango = $colInicio . $fila . ':' . $colFin . $fila;
    $borders = [
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                'color' => ['rgb' => '000000'],
            ],
            'inside' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
            ],
        ],
    ];
    $sheet->getStyle($rango)->applyFromArray($borders);
}

// Unir celdas C3:H3 y C4:H4
$sheet->mergeCells('C3:H3');
$sheet->mergeCells('C4:H4');

// Ajustar alto de todas las filas para que sea compacto
for ($row = 1; $row <= $highestRow; $row++) {
    $sheet->getRowDimension($row)->setRowHeight(16);
}
// Ajustar ancho de todas las columnas usadas
foreach (range('A', $highestColumn) as $col) {
    $sheet->getColumnDimension($col)->setWidth(10);
}





// --- 2. Escribir datos principales ---
$sheet->setCellValue('A3', 'UEN');
$sheet->setCellValue('A4', '42');
$sheet->getStyle('A3')->getFont()->setSize(5);
$sheet->getStyle('A4')->getFont()->setSize(12);
$sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);






// --- 6. Salida del archivo ---
if (ob_get_length()) ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="hoja_servicio.xlsx"');
header('Cache-Control: max-age=0');
header('Pragma: public');
header('Expires: 0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
