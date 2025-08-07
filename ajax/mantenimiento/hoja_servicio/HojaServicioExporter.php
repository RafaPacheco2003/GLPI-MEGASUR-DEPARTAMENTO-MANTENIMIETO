<?php
// Clase responsable de exportar la hoja de servicio a Excel
require_once __DIR__ . '/../../../lib/PhpSpreadsheet/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class HojaServicioExporter
{
    private $datosHoja;
    private $datosSucursal;

    public function __construct(array $datosHoja, array $datosSucursal)
    {
        $this->datosHoja = $datosHoja;
        $this->datosSucursal = $datosSucursal;
    }

    public function exportar()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getSheetView()->setView(\PhpOffice\PhpSpreadsheet\Worksheet\SheetView::SHEETVIEW_PAGE_LAYOUT);

        // Encabezado y logo
        $logoPath = __DIR__ . '/../../../files/logos/SGI.jpeg';
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

        // Estilos y encabezados
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
            'font' => ['size' => 10],
        ]);
        $sheet->getStyle('B1:R8')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
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
        $sheet->mergeCells('M5:R5');
        $sheet->mergeCells('M6:R6');
        $sheet->mergeCells('M7:R7');
        $sheet->mergeCells('M8:R8');

        $sheet->setCellValue('J7', '');
        $sheet->setCellValue('J8', '');
        $sheet->setCellValue('O1', 'FOLIO');

        // Datos de sucursal
        $s = $this->datosSucursal;
        $sheet->setCellValue('C3', 'Nombre de la estación: ');
        $sheet->getStyle('C3')->getAlignment()->setWrapText(true);
        $sheet->getStyle('C3')->getFont()->setSize(5)->setBold(true);
        $sheet->setCellValue('D3', $s['NombreEmpresa'] ?? '');
        $sheet->getStyle('D3')->getFont()->setSize(6);
        $sheet->setCellValue('C4', 'Ubicación / Dirección: ');
        $sheet->getStyle('C4')->getFont()->setSize(5)->setBold(true);
        $sheet->setCellValue('D4', ($s['Direccion'] ?? '') . ' C.P. ' . ($s['codigoPostal'] ?? ''));
        $sheet->getStyle('D4')->getFont()->setSize(6);
        $sheet->setCellValue('C5', 'RFC: ');
        $sheet->getStyle('C5')->getFont()->setSize(5)->setBold(true);
        $sheet->setCellValue('D5', $s['RFC'] ?? '');
        $sheet->getStyle('D5')->getFont()->setSize(6);
        $sheet->setCellValue('C6', 'N. ESTACION: ');
        $sheet->getStyle('C6')->getFont()->setSize(5)->setBold(true);
        $sheet->setCellValue('D6', $s['IdSucursal'] ?? '');
        $sheet->getStyle('D6')->getFont()->setSize(6);
        $sheet->setCellValue('C7', 'N.P. CRE: ');
        $sheet->getStyle('C7')->getFont()->setSize(5)->setBold(true);
        $sheet->setCellValue('D7', $s['ListaPreciosEsp'] ?? '');
        $sheet->getStyle('D7')->getFont()->setSize(6);
        $sheet->setCellValue('C8', 'FECHA: ');
        $sheet->getStyle('C8')->getFont()->setSize(5)->setBold(true);
        $sheet->setCellValue('J5', 'Telefono(s): ');
        $sheet->getStyle('J5')->getFont()->setSize(5)->setBold(true);
        $sheet->setCellValue('M5', $s['Telefonos'] ?? '');
        $sheet->getStyle('M5')->getFont()->setSize(6);
        $sheet->setCellValue('J6', 'Jefe de estacion: ');
        $sheet->getStyle('J6')->getFont()->setSize(5)->setBold(true);
        $sheet->setCellValue('M6', $s['Encargado'] ?? '');
        $sheet->getStyle('M6')->getFont()->setSize(6);
        $sheet->setCellValue('J7', 'Hora de servicio Ini: ');
        $sheet->getStyle('J7')->getFont()->setSize(5)->setBold(true);
        $sheet->setCellValue('J8', 'Hora de servicio Ini: ');
        $sheet->getStyle('J8')->getFont()->setSize(5)->setBold(true);

        $sheet->getStyle('C4:D8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C4:D8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('M5:M8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('M5:M8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('O2', $this->datosHoja['folio'] ?? '');
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









        // Datos de sucursal y servicio
        $sheet->setCellValue('B1', 'UEN');
        $sheet->setCellValue('B2', '42');
        $sheet->getStyle('B1')->getFont()->setSize(3);
        $sheet->getStyle('B2')->getFont()->setSize(6);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);





        
        // Descricpion del servicio


        $sheet->mergeCells('B10:R10');
        $sheet->setCellValue('B10', 'Datos de la estación de Servicio');
        $sheet->getStyle('B10:R10')->getFont()->setSize(7);
        $sheet->getStyle('B10:R10')->getFont()->setBold(true);
        $sheet->getStyle('B10:R10')->getFont()->getColor()->setRGB('000000');
        $sheet->getStyle('B10:R10')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('969696');

        $sheet->setCellValue('C11', 'Instalacion');
        $sheet->getStyle('C11')->getFont()->setSize(8);
        $sheet->getStyle('C11')->getFont()->setBold(true);
        $sheet->getStyle('C11')->getFont()->getColor()->setRGB('000000');
        $sheet->mergeCells('F11:H11');
        $sheet->setCellValue('F11', 'Mantenimiento');
        $sheet->getStyle('F11')->getFont()->setSize(8);
        $sheet->getStyle('F11')->getFont()->setBold(true);
        $sheet->getStyle('F11')->getFont()->getColor()->setRGB('000000');
        $sheet->mergeCells('K11:L11');
        $sheet->setCellValue('K11', 'Retiro');
        $sheet->getStyle('K11')->getFont()->setSize(8);
        $sheet->getStyle('K11')->getFont()->setBold(true);
        $sheet->getStyle('K11')->getFont()->getColor()->setRGB('000000');
        $sheet->mergeCells('O11:P11');
        $sheet->setCellValue('O11', 'Proyecto');
        $sheet->getStyle('O11')->getFont()->setSize(8);
        $sheet->getStyle('O11')->getFont()->setBold(true);
        $sheet->getStyle('O11')->getFont()->getColor()->setRGB('000000');

        $sheet->mergeCells('C12:Q12');
        
        $sheet->setCellValue('C12', 'Observaciones: ');
        $sheet->getStyle('C12')->getFont()->setSize(8);
        $sheet->getStyle('C12:Q12')->getFont()->setBold(true);
        $sheet->getStyle('C12:Q12')->getFont()->getColor()->setRGB('000000');
        // Borde solo abajo y negro en la celda fusionada C12:Q12
        $sheet->getStyle('C12:Q12')->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Bordes exteriores para el rango B10:R26 (sin bordes internos)
        $sheet->getStyle('B10:R26')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);




        $fila = 27;
        foreach ([
            'IdEmpresa' => $s['IdEmpresa'] ?? '',
            'NombreEmpresa' => $s['NombreEmpresa'] ?? '',
            'RFCEmpresa' => $s['RFCEmpresa'] ?? '',
            'IdSucursal' => $s['IdSucursal'] ?? '',
            'NombreSucursal' => $s['NombreSucursal'] ?? '',
            'codigoPostal' => $s['codigoPostal'] ?? '',
            'RFC' => $s['RFC'] ?? '',
            'ListaPreciosEsp' => $s['ListaPreciosEsp'] ?? '',
            'Encargado' => $s['Encargado'] ?? '',
            'Direccion' => $s['Direccion'] ?? '',
            'Poblacion' => $s['Poblacion'] ?? '',
            'Estado' => $s['Estado'] ?? '',
            'Pais' => $s['Pais'] ?? '',
            'Telefonos' => $s['Telefonos'] ?? '',
        ] as $campo => $valor) {
            $sheet->setCellValue('B' . $fila, $campo);
            $sheet->setCellValue('C' . $fila, $valor);
            $sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
            $fila++;
        }
        $fila++;
        $sheet->setCellValue('B' . $fila, '--- HOJA DE SERVICIO ---');
        $sheet->getStyle('B' . $fila)->getFont()->setSize(5);
        $fila++;
        foreach ($this->datosHoja as $campo => $valor) {
            $sheet->setCellValue('B' . $fila, $campo);
            $sheet->setCellValue('C' . $fila, $valor);
            $sheet->getStyle('B' . $fila . ':C' . $fila)->getFont()->setSize(5);
            $fila++;
        }
        $sheet->getStyle('B1:R2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('969696');
        $sheet->getStyle('B3:C8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('969696');
        $sheet->getStyle('J5:L8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('969696');
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
        return $spreadsheet;
    }
}
