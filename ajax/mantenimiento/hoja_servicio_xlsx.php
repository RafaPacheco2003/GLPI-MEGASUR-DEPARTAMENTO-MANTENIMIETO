<?php
// Exporta hoja de servicio a Excel (.xlsx) usando PhpSpreadsheet
require __DIR__ . '/../../lib/PhpSpreadsheet/autoload.php';

// ========================
// 1. DEPENDENCIAS Y DATOS
// ========================
require __DIR__ . '/../../lib/PhpSpreadsheet/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
require_once __DIR__ . '/../../inc/mantenimiento/bootstrap.php';
require_once __DIR__ . '/../../inc/mantenimiento/HojaServicio.php';

// Obtener datos de hoja de servicio
$hojaServicio = new HojaServicio();
$datosHoja = $hojaServicio->getById_servicio(51);
if (!is_array($datosHoja) || count($datosHoja) === 0) {
    die('No se pudo obtener la información de la hoja de servicio.');
}

// Obtener datos de sucursal
if (array_keys($datosHoja) !== range(0, count($datosHoja) - 1)) {
    $idSucursal = isset($datosHoja['id_estacion']) ? $datosHoja['id_estacion'] : null;
} else {
    $idSucursal = isset($datosHoja[0]['id_estacion']) ? $datosHoja[0]['id_estacion'] : null;
}
if (!$idSucursal) {
    die('No se encontró id_estacion en la hoja de servicio.');
}
$url = 'http://localhost/glpi/front/mantenimiento/config/get_sucursales_detalle.php?id=' . $idSucursal;
$json = file_get_contents($url);
$datosSucursal = json_decode($json, true);
if (!is_array($datosSucursal) || count($datosSucursal) === 0) {
    die('No se pudo obtener la información de la sucursal.');
}
$sucursal = $datosSucursal[0];

// ========================
// 2. CREAR HOJA Y CONFIGURACIÓN GENERAL
// ========================
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->getSheetView()->setView(\PhpOffice\PhpSpreadsheet\Worksheet\SheetView::SHEETVIEW_PAGE_LAYOUT);

// ========================
// 3. ENCABEZADO Y LOGO
// ========================
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

// ========================
// 4. ESTILOS Y ENCABEZADOS
// ========================
$highestColumn = $sheet->getHighestColumn();
$highestRow = $sheet->getHighestRow();
$sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
    'font' => [ 'size' => 10 ],
]);

// Borde grueso al bloque principal
$sheet->getStyle('B1:R8')->applyFromArray([
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
            'color' => ['rgb' => '000000'],
        ],
    ],
]);

// Encabezados principales
$sheet->mergeCells('C1:M1');
$sheet->mergeCells('C2:M2');
$sheet->mergeCells('O1:R1');
$sheet->mergeCells('O2:R2');
$sheet->mergeCells('D3:R3');
$sheet->mergeCells('D4:R4');
$sheet->setCellValue('O1', 'FOLIO');
$folioValor = '';
if (array_keys($datosHoja) !== range(0, count($datosHoja) - 1)) {
    $folioValor = isset($datosHoja['folio']) ? $datosHoja['folio'] : '';
} else {
    $folioValor = isset($datosHoja[0]['folio']) ? $datosHoja[0]['folio'] : '';
}
$sheet->setCellValue('O2', $folioValor);
$sheet->getStyle('O1:R1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('O2:R2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('O1:R1')->getFont()->setBold(true);
$sheet->getStyle('O1:R1')->getFont()->setSize(5);
$sheet->getStyle('O2:R2')->getFont()->setSize(4);
$sheet->setCellValue('C1', 'Datos de la estación de Servicio');
$sheet->setCellValue('C2', 'Especificar los datos generales de la estación de servicio');
$sheet->getStyle('C1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C2:M2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C1:M1')->getFont()->setSize(5);
$sheet->getStyle('C1:M1')->getFont()->setBold(true);
$sheet->getStyle('C2:M2')->getFont()->setSize(3);

// ========================
// 5. DATOS DE SUCURSAL Y SERVICIO
// ========================
$sheet->setCellValue('B1', 'UEN');
$sheet->setCellValue('B2', '42');
$sheet->getStyle('B1')->getFont()->setSize(3);
$sheet->getStyle('B2')->getFont()->setSize(6);
$sheet->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

$fila = 5;
foreach ($sucursal as $campo => $valor) {
    $sheet->setCellValue('B' . $fila, $campo);
    $sheet->setCellValue('C' . $fila, $valor);
    $sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
    $fila++;
}
$fila++; // Dejar una fila vacía
$sheet->setCellValue('B' . $fila, '--- HOJA DE SERVICIO ---');
$sheet->getStyle('B' . $fila)->getFont()->setSize(5);
$fila++;
if (array_keys($datosHoja) !== range(0, count($datosHoja) - 1)) {
    foreach ($datosHoja as $campo => $valor) {
        $sheet->setCellValue('B' . $fila, $campo);
        $sheet->setCellValue('C' . $fila, $valor);
        $sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
        $fila++;
    }
} else {
    foreach ($datosHoja as $registro) {
        foreach ($registro as $campo => $valor) {
            $sheet->setCellValue('B' . $fila, $campo);
            $sheet->setCellValue('C' . $fila, $valor);
            $sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
            $fila++;
        }
        $fila++;
    }
}

// ========================
// 6. AJUSTES FINALES DE FORMATO
// ========================
$highestRow = $sheet->getHighestRow();
for ($row = 1; $row <= $highestRow; $row++) {
    $sheet->getRowDimension($row)->setRowHeight(10);
}
$columnas = range('B', 'R');
foreach ($columnas as $col) {
    $sheet->getColumnDimension($col)->setWidth(5);
}
$sheet->getColumnDimension('A')->setWidth(2);
$sheet->getColumnDimension('A')->setAutoSize(false);
$sheet->getColumnDimension('B')->setWidth(3.6);
$sheet->getColumnDimension('B')->setAutoSize(false);
$sheet->getColumnDimension('C')->setWidth(12);
$sheet->getColumnDimension('C')->setAutoSize(false);
foreach (['O', 'P', 'Q', 'R'] as $col) {
    $sheet->getColumnDimension($col)->setWidth(3);
    $sheet->getColumnDimension($col)->setAutoSize(false);
}

// ========================
// 7. SALIDA DEL ARCHIVO
// ========================
if (ob_get_length()) ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="hoja_servicio.xlsx"');
header('Cache-Control: max-age=0');
header('Pragma: public');
header('Expires: 0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
// --- 6. Salida del archivo ---
