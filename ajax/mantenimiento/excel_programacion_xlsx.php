

<?php
// Exporta información de una programación a Excel (.xlsx) usando PhpSpreadsheet
require __DIR__ . '/../../lib/PhpSpreadsheet/autoload.php';
require_once __DIR__ . '/../../inc/mantenimiento/ProgramacionManager.php';
require_once __DIR__ . '/../../inc/mantenimiento/servicioManager.php';
 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Validar y obtener el parámetro id
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    exit('ID de programación no válido.');
}

$manager = new ProgramacionManager();
$managerServicio = new ServicioManager();

// Obtener la programación por ID
$programacion = $manager->getById($id);
if (!$programacion) {
    exit('No se encontró la programación.');
}

// Obtener el servicio asociado a la programación
$servicio = $managerServicio->getServiciosByProgramacion2($id);



// Crear hoja de cálculo y configurar encabezado
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$nombreProgramacion = $programacion['nombre_programacion'] ?? '';

// Forzar vista "Diseño de página" (Page Layout) al abrir el archivo
$sheet->getSheetView()->setView(\PhpOffice\PhpSpreadsheet\Worksheet\SheetView::SHEETVIEW_PAGE_LAYOUT);


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




// --- Servicios: tabla y firma (NUEVO FORMATO, ESPAÑOL) ---
$servicioHeaders = [
    'Día', 'Fecha', 'Mes', 'Año', 'Hora de Inicio', 'Hora Fin', 'Servidor/Site', 'Serie/ID', 'Afectaciones', 'Serie Folio Hoja Servicio'
];
$colStart = 2; // Columna B
$rowStart = 5;

// Determinar la fila donde va la firma
$row = $rowStart + 1; // Por defecto, la fila después del header
if (!empty($servicio)) {
    $row = $rowStart + 1;
    foreach ($servicio as $serv) {
        $row++;
    }
}
$firmaRow = $row + 4; // 6 filas abajo del último servicio o del header si no hay servicios

if ($firmaPathFinal) {
    $tempImage = sys_get_temp_dir() . '/temp_excel_img.png';
    copy($firmaPathFinal, $tempImage);

    $sheet->getColumnDimension('D')->setWidth(25);


    // Agregar el texto "Elaboró" ARRIBA de la firma y centrado en C:D (más a la derecha)
    $elaboroRow = $firmaRow - 1;
    $sheet->mergeCells('E' . $elaboroRow . ':E' . $elaboroRow);
    $sheet->setCellValue('E' . $elaboroRow, 'Elaboró');
    $sheet->getStyle('E' . $elaboroRow)->getFont()->setSize(8);
    $sheet->getStyle('E' . $elaboroRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $drawing = new Drawing();
    $drawing->setName('FirmaElaboro');
    $drawing->setDescription('Firma de quien elaboró');
    $drawing->setPath($tempImage);
    $drawing->setHeight(120);
    $drawing->setWidth(150);
    $drawing->setCoordinates('B' . $firmaRow);
    $drawing->setWorksheet($sheet);
}

// Ajustar la altura de la fila 3 para que sea delgada
$sheet->getRowDimension(2)->setRowHeight(10);
$sheet->getRowDimension(3)->setRowHeight(10);
$sheet->getRowDimension(4)->setRowHeight(7);
$sheet->getColumnDimension('A')->setWidth(1.5);

// Encabezados y datos
$headers = ['Nombre Empresa', 'Nombre Programación', 'F
echa Emisión', 'ID Elaboró', 'Firma Elaboró'];

$fields  = ['nombre_empresa', 'nombre_programacion', 'fecha_emision', 'id_elaboro', 'firma_elaboro'];

// No escribir nada en la fila 1 ni en las celdas A2-E2
// Solo escribir datos a partir de F2 si existen más campos
foreach ($fields as $i => $field) {
    if ($i > 4) {
        $col = chr(65 + $i) . '2';
        $sheet->setCellValue($col, $programacion[$field] ?? '');
    }
}

// --- NUEVO: Año en J3/K3 y nombre empresa en F2 ---
$fechaEmision = $programacion['fecha_emision'] ?? '';
$año = '';
if ($fechaEmision) {
    // Extrae el año de la fecha (formato YYYY-MM-DD)
    $año = date('Y', strtotime($fechaEmision));
}
$sheet->setCellValue('J3', 'Año');
$sheet->setCellValue('K3', $año);

$nombreEmpresa = $programacion['nombre_empresa'] ?? '';


// Obtener el nombre del usuario que elaboró directamente desde la base de datos
$idElaboro = $programacion['id_elaboro'] ?? '';
$nombreElaboro = '';
if ($idElaboro) {
    $mysqli = new mysqli('localhost', 'root', '', 'glpi'); // Cambia usuario, contraseña y base si es necesario
    if (!$mysqli->connect_errno) {
        $stmt = $mysqli->prepare('SELECT realname, firstname, name FROM glpi_users WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $idElaboro);
        $stmt->execute();
        $stmt->bind_result($realname, $firstname, $login);
        if ($stmt->fetch()) {
            if (!empty($realname)) {
                $nombreElaboro = $realname;
                if (!empty($firstname)) {
                    $nombreElaboro .= ' ' . $firstname;
                }
            } else {
                $nombreElaboro = $login;
            }
        }
        $stmt->close();
        $mysqli->close();
    }
}

$sheet->mergeCells('F2:H2');
$sheet->setCellValue('F2', 'EMPRESA: ' . $nombreEmpresa);
$sheet->getStyle('F2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);



// Mostrar el nombre de quien elaboró JUSTO debajo de la firma y centrado con el texto 'Elaboró'
if ($firmaPathFinal && $idElaboro) {
    $mysqli = new mysqli('localhost', 'root', '', 'glpi'); // Cambia usuario, contraseña y base si es necesario
    $nombreElaboro = '';
    if (!$mysqli->connect_errno) {
        $stmt = $mysqli->prepare('SELECT name FROM glpi_users WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $idElaboro);
        $stmt->execute();
        $stmt->bind_result($login);
        if ($stmt->fetch()) {
            $nombreElaboro = $login;
        }
        $stmt->close();
        $mysqli->close();
    }
    // El nombre debe ir 4 filas debajo de la firma, en la columna E (igual que 'Elaboró')
    $nombreRow = $firmaRow + 4;
    $sheet->mergeCells('E' . $nombreRow . ':E' . $nombreRow);
    $sheet->setCellValue('E' . $nombreRow, $nombreElaboro);
    $sheet->getStyle('E' . $nombreRow)->getFont()->setSize(8);
    $sheet->getStyle('E' . $nombreRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
}


// --- Quitar bordes de toda la hoja y reducir tamaño de fuente ---
$highestColumn = $sheet->getHighestColumn();
$highestRow = $sheet->getHighestRow();
$range = 'A1:' . $highestColumn . $highestRow;
$sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);

// Encabezado de la tabla de servicios y todas las celdas: 5pt
$headerRange = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart) . $rowStart . ':' .
    \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + count($servicioHeaders) - 1) . $rowStart;


// Estilo para el encabezado de la tabla de servicios
$sheet->getStyle($headerRange)->applyFromArray([
    'font' => [
        'size' => 5,
        'bold' => false,
        'color' => ['rgb' => '000000'],
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => '8DB4E2'],
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
]);

// --- Bordes para la tabla de servicios (encabezado y datos) ---
$tablaStart = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart) . $rowStart;
$tablaEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + count($servicioHeaders) - 1) . ($rowStart + $numFilasServicios);
$tablaRange = $tablaStart . ':' . $tablaEnd;
$sheet->getStyle($tablaRange)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
]);

// --- F4:G4 unidas, fondo azul, texto PROGRAMACION centrado ---
$sheet->mergeCells('F4:G4');
$sheet->setCellValue('F4', 'PROGRAMACION');
$sheet->getStyle('F4')->applyFromArray([
    'font' => [
        'bold' => false,
        'color' => ['rgb' => '000000'],
        'size' => 8,
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => '8DB4E2'],
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
]);
$sheet->getStyle('F4:G4')->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
]);

$sheet->getStyle($range)->getFont()->setSize(5);










function getDiaMesEsp($timestamp) {
    $dias = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $dia = $dias[(int)date('w', $timestamp)];
    $mes = $meses[(int)date('n', $timestamp)-1];
    return [$dia, $mes];
}

function escribirEncabezadoServicios($sheet, $headers, $colStart, $rowStart) {
    for ($i = 0; $i < count($headers); $i++) {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + $i);
        $sheet->setCellValue($colLetter . $rowStart, $headers[$i]);
    }
    $headerRange = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart) . $rowStart . ':' .
        \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + count($headers) - 1) . $rowStart;
    $sheet->getStyle($headerRange)->getFont()->setBold(true);
    $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
}

function escribirServiciosNuevo($sheet, $servicios, $colStart, $rowStart, $programacion) {
    $row = $rowStart + 1;
    $fecha = $programacion['fecha_emision'] ?? '';
    $horaInicio = $programacion['hora_inicio'] ?? '';
    $horaFin = $programacion['hora_fin'] ?? '';
    $timestamp = $fecha ? strtotime($fecha) : false;
    list($dia, $mes) = $timestamp ? getDiaMesEsp($timestamp) : ['', ''];
    $numDia = $timestamp ? date('d', $timestamp) : '';
    $anio = $timestamp ? date('Y', $timestamp) : '';

    foreach ($servicios as $serv) {
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + 0) . $row, ucfirst($dia));
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + 1) . $row, $numDia);
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + 2) . $row, ucfirst($mes));
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + 3) . $row, $anio);
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + 4) . $row, $horaInicio);
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + 5) . $row, $horaFin);
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + 6) . $row, $serv['servidor_site'] ?? '');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + 7) . $row, $serv['serie_id'] ?? '');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + 8) . $row, $serv['afectacion'] ?? '');
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + 9) . $row, $serv['serie_folio_hoja_servicio'] ?? '');
        $row++;
    }
    return $row;
}


// Escribir encabezado
escribirEncabezadoServicios($sheet, $servicioHeaders, $colStart, $rowStart);

// Ajustar ancho de columnas y alto de filas de la tabla de servicios para que se vean parejos con el contenido
for ($i = 0; $i < count($servicioHeaders); $i++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + $i);
    // Día, Fecha, Mes y Año a la mitad de ancho (4), excepto Fecha (2 dígitos)
    if ($i === 1) { // Fecha
        $sheet->getColumnDimension($colLetter)->setWidth(2.5);
    } elseif ($i === 0 || ($i >= 2 && $i <= 3)) { // Día, Mes, Año
        $sheet->getColumnDimension($colLetter)->setWidth(4);
    } else {
        $sheet->getColumnDimension($colLetter)->setWidth(8);
    }
}
// Ajustar el alto de las filas de la tabla de servicios (encabezado y datos) a 8 (delgadas)
$numFilasServicios = (!empty($servicio)) ? count($servicio) : 1;
for ($fila = $rowStart; $fila <= $rowStart + $numFilasServicios; $fila++) {
    $sheet->getRowDimension($fila)->setRowHeight(8);
}

// Escribir servicios y calcular la fila final
$row = $rowStart + 1;
if (!empty($servicio)) {
    $row = escribirServiciosNuevo($sheet, $servicio, $colStart, $rowStart, $programacion);
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
