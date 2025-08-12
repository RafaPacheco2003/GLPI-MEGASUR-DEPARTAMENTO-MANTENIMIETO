
<?php
// Exporta hoja de servicio a Excel (.xlsx) usando PhpSpreadsheet
require __DIR__ . '/../../lib/PhpSpreadsheet/autoload.php';
require_once __DIR__ . '/hoja_servicio/HojaServicioExporter.php';
require_once __DIR__ . '/hoja_servicio/SucursalService.php';

// ========================
// 1. DEPENDENCIAS Y DATOS
// ========================
require __DIR__ . '/../../lib/PhpSpreadsheet/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
require_once __DIR__ . '/../../inc/mantenimiento/bootstrap.php';
require_once __DIR__ . '/../../inc/mantenimiento/HojaServicio.php';

// Obtener el id de hoja de servicio desde la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de hoja de servicio no válido.');
}
$idHoja = (int)$_GET['id'];
$hojaServicio = new HojaServicio();
$datosHoja = $hojaServicio->getById($idHoja);
if (!$datosHoja || !is_array($datosHoja)) {
    die('No se pudo obtener la información de la hoja de servicio.');
}
// Obtener datos de sucursal usando el servicio
$idEstacion = isset($datosHoja['id_estacion']) ? $datosHoja['id_estacion'] : null;
if (!$idEstacion) {
    die('No se encontró id_estacion en la hoja de servicio.');
}
$datosSucursal = SucursalService::getDatosSucursal($idEstacion);
if (!$datosSucursal) {
    die('No se pudo obtener la información de la sucursal.');
}


// ========================
// 2. EXPORTAR Y SALIDA
// ========================
// ========================
// 2. EXPORTAR Y SALIDA
// ========================
$exporter = new HojaServicioExporter($datosHoja, $datosSucursal);
$spreadsheet = $exporter->exportar();
if (ob_get_length()) ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="hoja_servicio.xlsx"');
header('Cache-Control: max-age=0');
header('Pragma: public');
header('Expires: 0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
$telefonos = isset($sucursal['Telefonos']) ? $sucursal['Telefonos'] : '';
