
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

// Variables individuales para cada campo de la hoja de servicio
if (array_keys($datosHoja) !== range(0, count($datosHoja) - 1)) {
    $idHoja = isset($datosHoja['id']) ? $datosHoja['id'] : '';
    $idEstacionHoja = isset($datosHoja['id_estacion']) ? $datosHoja['id_estacion'] : '';
    $idServicioHoja = isset($datosHoja['id_servicio']) ? $datosHoja['id_servicio'] : '';
    $folioHoja = isset($datosHoja['folio']) ? $datosHoja['folio'] : '';
    $serieHoja = isset($datosHoja['serie']) ? $datosHoja['serie'] : '';
    $fechaInicioHoja = isset($datosHoja['fecha_inicio']) ? $datosHoja['fecha_inicio'] : '';
    $fechaFinHoja = isset($datosHoja['fecha_fin']) ? $datosHoja['fecha_fin'] : '';
    $descripcionHoja = isset($datosHoja['descripcion']) ? $datosHoja['descripcion'] : '';
    $idEntregadoHoja = isset($datosHoja['id_entregado']) ? $datosHoja['id_entregado'] : '';
    $firmaEntregadoHoja = isset($datosHoja['firma_entregado']) ? $datosHoja['firma_entregado'] : '';
    $idRecibidoHoja = isset($datosHoja['id_recibido']) ? $datosHoja['id_recibido'] : '';
    $firmaRecibidoHoja = isset($datosHoja['firma_recibido']) ? $datosHoja['firma_recibido'] : '';
    $tipoServicioHoja = isset($datosHoja['tipo_servicio']) ? $datosHoja['tipo_servicio'] : '';
    $idMaterialHoja = isset($datosHoja['id_material']) ? $datosHoja['id_material'] : '';
} else {
    $registroHoja = $datosHoja[0];
    $idHoja = isset($registroHoja['id']) ? $registroHoja['id'] : '';
    $idEstacionHoja = isset($registroHoja['id_estacion']) ? $registroHoja['id_estacion'] : '';
    $idServicioHoja = isset($registroHoja['id_servicio']) ? $registroHoja['id_servicio'] : '';
    $folioHoja = isset($registroHoja['folio']) ? $registroHoja['folio'] : '';
    $serieHoja = isset($registroHoja['serie']) ? $registroHoja['serie'] : '';
    $fechaInicioHoja = isset($registroHoja['fecha_inicio']) ? $registroHoja['fecha_inicio'] : '';
    $fechaFinHoja = isset($registroHoja['fecha_fin']) ? $registroHoja['fecha_fin'] : '';
    $descripcionHoja = isset($registroHoja['descripcion']) ? $registroHoja['descripcion'] : '';
    $idEntregadoHoja = isset($registroHoja['id_entregado']) ? $registroHoja['id_entregado'] : '';
    $firmaEntregadoHoja = isset($registroHoja['firma_entregado']) ? $registroHoja['firma_entregado'] : '';
    $idRecibidoHoja = isset($registroHoja['id_recibido']) ? $registroHoja['id_recibido'] : '';
    $firmaRecibidoHoja = isset($registroHoja['firma_recibido']) ? $registroHoja['firma_recibido'] : '';
    $tipoServicioHoja = isset($registroHoja['tipo_servicio']) ? $registroHoja['tipo_servicio'] : '';
    $idMaterialHoja = isset($registroHoja['id_material']) ? $registroHoja['id_material'] : '';
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
// Variables individuales para cada campo de la sucursal
$idEmpresa = isset($sucursal['IdEmpresa']) ? $sucursal['IdEmpresa'] : '';
$nombreEmpresa = isset($sucursal['NombreEmpresa']) ? $sucursal['NombreEmpresa'] : '';
$rfcEmpresa = isset($sucursal['RFCEmpresa']) ? $sucursal['RFCEmpresa'] : '';
$idSucursal = isset($sucursal['IdSucursal']) ? $sucursal['IdSucursal'] : '';
$nombreSucursal = isset($sucursal['NombreSucursal']) ? $sucursal['NombreSucursal'] : '';
$codigoPostal = isset($sucursal['codigoPostal']) ? $sucursal['codigoPostal'] : '';
$rfc = isset($sucursal['RFC']) ? $sucursal['RFC'] : '';
$listaPreciosEsp = isset($sucursal['ListaPreciosEsp']) ? $sucursal['ListaPreciosEsp'] : '';
$encargado = isset($sucursal['Encargado']) ? $sucursal['Encargado'] : '';
$direccion = isset($sucursal['Direccion']) ? $sucursal['Direccion'] : '';
$poblacion = isset($sucursal['Poblacion']) ? $sucursal['Poblacion'] : '';
$estado = isset($sucursal['Estado']) ? $sucursal['Estado'] : '';
$pais = isset($sucursal['Pais']) ? $sucursal['Pais'] : '';
$telefonos = isset($sucursal['Telefonos']) ? $sucursal['Telefonos'] : '';

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
$sheet->mergeCells('D5:I5');
$sheet->mergeCells('D6:I6');
$sheet->mergeCells('D7:I7');
$sheet->mergeCells('D8:I8');

$sheet->mergeCells('J5:L5');
$sheet->mergeCells('J6:L6');
$sheet->mergeCells('J7:L7');
$sheet->mergeCells('J8:L8');
$sheet->setCellValue('J7', '');
$sheet->setCellValue('J8', '');
$sheet->setCellValue('O1', 'FOLIO');

$sheet->setCellValue('C3', "Nombre de la estación: ");
$sheet->getStyle('C3')->getAlignment()->setWrapText(true);
$sheet->getStyle('C3')->getFont()->setSize(5)->setBold(true);
$sheet->setCellValue('D3', $nombreEmpresa);
$sheet->getStyle('D3')->getFont()->setSize(6);


$sheet->setCellValue('C4', "Ubicación / Dirección: ");
$sheet->getStyle('C4')->getFont()->setSize(5)->setBold(true);
$sheet->setCellValue('D4', $direccion . ' C.P. ' . $codigoPostal);
$sheet->getStyle('D4')->getFont()->setSize(6);



$sheet->setCellValue('C5', "RFC: ");
$sheet->getStyle('C5')->getFont()->setSize(5)->setBold(true);
$sheet->setCellValue('D5', $rfc);
$sheet->getStyle('D5')->getFont()->setSize(6);

$sheet->setCellValue('C6', "N. ESTACION: ");
$sheet->getStyle('C6')->getFont()->setSize(5)->setBold(true);
$sheet->setCellValue('D6', $idSucursal);
$sheet->getStyle('D6')->getFont()->setSize(6);
$sheet->setCellValue('C7', "N.P. CRE: ");
$sheet->getStyle('C7')->getFont()->setSize(5)->setBold(true);
$sheet->setCellValue('D7', $listaPreciosEsp);
$sheet->getStyle('D7')->getFont()->setSize(6);
$sheet->setCellValue('C8', "FECHA: ");
$sheet->getStyle('C8')->getFont()->setSize(5)->setBold(true);


$sheet->setCellValue('J5', "Telefono(s): ");
$sheet->getStyle('J5')->getFont()->setSize(5)->setBold(true);
$sheet->setCellValue('M5', $telefonos);
$sheet->getStyle('M5')->getFont()->setSize(6);


$sheet->setCellValue('J6', "Jefe de estacion: ");
$sheet->getStyle('J6')->getFont()->setSize(5)->setBold(true);
$sheet->setCellValue('M6', $encargado);
$sheet->getStyle('M6')->getFont()->setSize(6);



$sheet->setCellValue('J7', "Hora de servicio Ini: ");
$sheet->getStyle('J7')->getFont()->setSize(5)->setBold(true);


$sheet->setCellValue('J8', "Hora de servicio Ini: ");
$sheet->getStyle('J8')->getFont()->setSize(5)->setBold(true);




$sheet->getStyle('C4:D8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C4:D8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('M5:M8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('M5:M8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

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
$sheet->getStyle('O1:R1')->getFont()->setSize(10);
$sheet->getStyle('O2:R2')->getFont()->setSize(4);
$sheet->setCellValue('C1', 'Datos de la estación de Servicio');
$sheet->setCellValue('C2', 'Especificar los datos generales de la estación de servicio');
$sheet->getStyle('C1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C2:M2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C1:M1')->getFont()->setSize(15);
$sheet->getStyle('C1:M1')->getFont()->setBold(true);
$sheet->getStyle('C2:M2')->getFont()->setSize(10);

// ========================
// 5. DATOS DE SUCURSAL Y SERVICIO
// ========================
$sheet->setCellValue('B1', 'UEN');
$sheet->setCellValue('B2', '42');
$sheet->getStyle('B1')->getFont()->setSize(3);
$sheet->getStyle('B2')->getFont()->setSize(6);
$sheet->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

$fila = 10;
$sheet->setCellValue('B' . $fila, 'IdEmpresa');
$sheet->setCellValue('C' . $fila, $idEmpresa);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'NombreEmpresa');
$sheet->setCellValue('C' . $fila, $nombreEmpresa);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'RFCEmpresa');
$sheet->setCellValue('C' . $fila, $rfcEmpresa);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'IdSucursal');
$sheet->setCellValue('C' . $fila, $idSucursal);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'NombreSucursal');
$sheet->setCellValue('C' . $fila, $nombreSucursal);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'codigoPostal');
$sheet->setCellValue('C' . $fila, $codigoPostal);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'RFC');
$sheet->setCellValue('C' . $fila, $rfc);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'ListaPreciosEsp');
$sheet->setCellValue('C' . $fila, $listaPreciosEsp);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'Encargado');
$sheet->setCellValue('C' . $fila, $encargado);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'Direccion');
$sheet->setCellValue('C' . $fila, $direccion);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'Poblacion');
$sheet->setCellValue('C' . $fila, $poblacion);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'Estado');
$sheet->setCellValue('C' . $fila, $estado);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'Pais');
$sheet->setCellValue('C' . $fila, $pais);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
$sheet->setCellValue('B' . $fila, 'Telefonos');
$sheet->setCellValue('C' . $fila, $telefonos);
$sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
$fila++;
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


// Fondo gris #969696 para el rango B1:R2
$sheet->getStyle('B1:R2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('969696');
$sheet->getStyle('B3:C8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('969696');
$sheet->getStyle('J5:L8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('969696');
// ========================
// 6. AJUSTES FINALES DE FORMATO
// ========================
$highestRow = $sheet->getHighestRow();
for ($row = 1; $row <= $highestRow; $row++) {
    $sheet->getRowDimension($row)->setRowHeight(15);
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
