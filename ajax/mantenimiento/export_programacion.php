<?php
require_once '../../inc/includes.php';
require_once '../../inc/mantenimiento/ProgramacionManager.php';
require_once '../../inc/mantenimiento/servicioManager.php';

// Verificar que se proporcionó un ID
if (!isset($_GET['id'])) {
    die("ID de programación no proporcionado");
}

$id_programacion = (int)$_GET['id'];

// Crear instancias de los managers
$programacionManager = new ProgramacionManager();
$servicioManager = new ServicioManager();

// Obtener datos de la programación
$programacion = $programacionManager->getById($id_programacion);
if (!$programacion) {
    die("Programación no encontrada");
}

// Obtener todos los servicios de la programación
$servicios = $servicioManager->getServiciosByProgramacion2($id_programacion);

// Configurar headers para Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Programacion_' . $programacion['nombre_programacion'] . '.xls"');
header('Cache-Control: max-age=0');

// Función para obtener el nombre del día en español
function getDayNameSpanish($date) {
    $dias = array(
        'Sunday' => 'Domingo',
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sábado'
    );
    return $dias[date('l', strtotime($date))];
}

// Obtener imagen y convertir a base64 (con imagen de respaldo)
$imageUrl = 'https://via.placeholder.com/200x68?text=Ejemplo'; // Imagen de prueba
$imageData = @file_get_contents($imageUrl);
if($imageData === false) {
    // Imagen de respaldo mínima (1px transparente)
    $imageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
}
$base64Image = 'data:image/png;base64,'.base64_encode($imageData);

// Generar el documento Excel
echo '<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Title>Programación de Mantenimiento</Title>
  <Subject>Reporte de Servicios</Subject>
  <Author>Sistema GLPI</Author>
  <Company>' . htmlspecialchars($programacion['nombre_empresa']) . '</Company>
  <Created>' . date('Y-m-d\TH:i:s\Z') . '</Created>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>8955</WindowHeight>
  <WindowWidth>11355</WindowWidth>
  <WindowTopX>480</WindowTopX>
  <WindowTopY>60</WindowTopY>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Arial" ss:Size="6"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s1">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Arial" ss:Size="7" ss:Bold="1"/>
   <Interior ss:Color="#8DB4E2" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s2">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Arial" ss:Size="6"/>
  </Style>
  <Style ss:ID="s3">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Arial" ss:Size="6"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Programación">
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0.5" x:Data="&amp;C&amp;16&amp;B' . htmlspecialchars($programacion['nombre_programacion']) . '"/>
    <Footer x:Margin="0.3" x:Data="&amp;L&amp;8Generado automáticamente&amp;C&amp;8Página &amp;P de &amp;N&amp;R&amp;8Confidencial"/>
    <PageMargins x:Bottom="0.75" x:Left="0.7" x:Right="0.7" x:Top="1.5" x:Header="0.5" x:Footer="0.3"/>
   </PageSetup>
   <Print>
    <ValidPrinterInfo/>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>600</VerticalResolution>
   </Print>
  </WorksheetOptions>
  <Table ss:ExpandedColumnCount="11" ss:ExpandedRowCount="' . (count($servicios) + 11) . '" x:FullColumns="1" x:FullRows="1">
   <Column ss:Width="45"/>
   <Column ss:Width="22"/>
   <Column ss:Width="22"/>
   <Column ss:Width="30"/>
   <Column ss:Width="45"/>
   <Column ss:Width="45"/>
   <Column ss:Width="65"/>
   <Column ss:Width="45"/>
   <Column ss:Width="45"/>
   <Column ss:Width="55"/>
   <Column ss:Width="55"/>
   <Row ss:Height="9">
    <Cell><Data ss:Type="String">Programa:</Data></Cell>
    <Cell ss:MergeAcross="2"><Data ss:Type="String">' . htmlspecialchars($programacion['nombre_programacion']) . '</Data></Cell>
    <Cell><Data ss:Type="String">Empresa:</Data></Cell>
    <Cell ss:MergeAcross="1"><Data ss:Type="String">' . htmlspecialchars($programacion['nombre_empresa']) . '</Data></Cell>
    <Cell><Data ss:Type="String">Fecha:</Data></Cell>
    <Cell><Data ss:Type="String">' . date('d/m/Y', strtotime($programacion['fecha_emision'])) . '</Data></Cell>
   </Row>
   <Row ss:Height="3"></Row>
   <Row ss:Height="9">
    <Cell></Cell>
    <Cell></Cell>
    <Cell></Cell>
    <Cell></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="s1"><Data ss:Type="String">PROGRAMACIÓN</Data></Cell>
    <Cell></Cell>
    <Cell></Cell>
    <Cell></Cell>
    <Cell></Cell>
    <Cell></Cell>
   </Row>
   <Row ss:Height="9">
    <Cell ss:StyleID="s1"><Data ss:Type="String">Día</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Día</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Mes</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Año</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Hora de Inicio</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Hora Fin</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Servidor/Site</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Serie/ID</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Estatus</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Afectación</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Serie/Folio Hoja Servicio</Data></Cell>
   </Row>';

// Generar filas de datos
foreach ($servicios as $servicio) {
    $fecha = strtotime($servicio['fecha_inicio']);
    echo '<Row ss:Height="9">
    <Cell ss:StyleID="s2"><Data ss:Type="String">' . getDayNameSpanish($servicio['fecha_inicio']) . '</Data></Cell>
    <Cell ss:StyleID="s2"><Data ss:Type="String">' . date('d', $fecha) . '</Data></Cell>
    <Cell ss:StyleID="s2"><Data ss:Type="String">' . date('m', $fecha) . '</Data></Cell>
    <Cell ss:StyleID="s2"><Data ss:Type="String">' . date('Y', $fecha) . '</Data></Cell>
    <Cell ss:StyleID="s2"><Data ss:Type="String">' . date('h:i A', $fecha) . '</Data></Cell>
    <Cell ss:StyleID="s2"><Data ss:Type="String">' . (isset($servicio['fecha_final']) && $servicio['fecha_final'] ? date('h:i A', strtotime($servicio['fecha_final'])) : 'N/A') . '</Data></Cell>
    <Cell ss:StyleID="s3"><Data ss:Type="String">' . htmlspecialchars($servicio['servidor_site']) . '</Data></Cell>
    <Cell ss:StyleID="s2"><Data ss:Type="String">' . htmlspecialchars($servicio['serie_id']) . '</Data></Cell>
    <Cell ss:StyleID="s2"><Data ss:Type="String">' . htmlspecialchars($servicio['estatus']) . '</Data></Cell>
    <Cell ss:StyleID="s3"><Data ss:Type="String">' . htmlspecialchars($servicio['afectacion']) . '</Data></Cell>
    <Cell ss:StyleID="s2"><Data ss:Type="String">' . htmlspecialchars($servicio['serie_folio_hoja_servicio']) . '</Data></Cell>
   </Row>';
}

// Fila con la imagen incrustada
echo '   <Row ss:Height="9"></Row>
   <Row ss:Height="9"></Row>
   <Row ss:Height="9"></Row>
   <Row ss:Height="72">
    <Cell ss:Index="5" ss:MergeAcross="2" ss:StyleID="s2"><Data ss:Type="String">Imagen de Ejemplo</Data></Cell>
    <Cell ss:StyleID="s2">s
      <ss:Data ss:Type="String" xmlns="http://www.w3.org/TR/REC-html40">
        <html>
          <body>
            <img src="'.$base64Image.'" 
                 width="200" height="68"
                 style="display:block; margin:0; padding:0; border:none;"/>
          </body>
        </html>
      </ss:Data>
    </Cell>
   </Row>
   <Row ss:Height="9">
    <Cell></Cell>
    <Cell></Cell>
    <Cell></Cell>
    <Cell ss:MergeAcross="3" ss:StyleID="s2"><Data ss:Type="String">Elaborado por</Data></Cell>
    <Cell ss:StyleID="s2"><Data ss:Type="String"><![CDATA[<img src="' . htmlspecialchars($programacion['firma_elaboro']) . '" height="30"/>]]></Data></Cell>
    <Cell></Cell>
    <Cell></Cell>
   </Row>
  </Table>
 </Worksheet>
</Workbook>';
?>