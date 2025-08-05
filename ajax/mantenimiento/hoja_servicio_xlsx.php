
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





// Agregar una fila con bordes negros de la columna A a la J en la fila 3 y hacerla visible
$filaBordes = 3;
$colInicio = 'A';
$colFin = 'J';
$rangoBordes = $colInicio . $filaBordes . ':' . $colFin . $filaBordes;
$sheet->getStyle($rangoBordes)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
]);
// Ajustar alto de la fila 3 para que sea visible
$sheet->getRowDimension($filaBordes)->setRowHeight(22);
// Ajustar ancho de columnas A-J para que sean visibles
foreach (range('A', 'J') as $col) {
    $sheet->getColumnDimension($col)->setWidth(14);
}
exit;
