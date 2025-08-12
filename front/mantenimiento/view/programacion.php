<?php
/**
 * Vista principal del módulo de Mantenimiento
 */

include '../../../inc/includes.php';
include_once '../componentes/ButtonComponent.php';
include_once '../config/ColorConfig.php';
include_once '../componentes/programacion/ProgramacionCard.php';
include_once '../componentes/programacion/ProgramacionPagination.php';
include_once '../../../inc/mantenimiento/ProgramacionManager.php';
include_once '../../../inc/mantenimiento/servicioManager.php';

// Verificar permisos
Session::checkRight("config", UPDATE);

// Obtener el id y nombre del usuario logueado
$id_usuario = isset($_SESSION['glpiID']) ? $_SESSION['glpiID'] : 'No logueado';
$nombre_usuario = isset($_SESSION['glpiname']) ? $_SESSION['glpiname'] : 'Sin nombre';

// Crear instancia de los managers
$programacionManager = new ProgramacionManager();
$servicioManager = new ServicioManager();

// Obtener página actual
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;

// Procesar búsqueda si existe
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$result = [];

if (!empty($searchTerm)) {
    // Si hay término de búsqueda, usar la función de búsqueda
    $programaciones = $programacionManager->searchProgramacionesByNombre($searchTerm);
    $result = [
        'items' => $programaciones,
        'currentPage' => 1,
        'totalPages' => 1,
        'totalItems' => count($programaciones)
    ];
} else {
    // Si no hay búsqueda, obtener con paginación normal
    $result = $programacionManager->getAllPaginated($currentPage);
}

// Crear instancia del manager
$programacionManager = new ProgramacionManager();

// Procesar búsqueda y filtrado por año si existe
$programaciones = [];
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$anioFiltro = isset($_GET['anio']) ? trim($_GET['anio']) : '';

if (!empty($searchTerm)) {
    // Si hay término de búsqueda, usar la función de búsqueda
    $programaciones = $programacionManager->searchProgramacionesByNombre($searchTerm);
    // Si además hay filtro de año, filtrar por año de fecha_emision
    if (!empty($anioFiltro)) {
        $programaciones = array_filter($programaciones, function($prog) use ($anioFiltro) {
            return !empty($prog['fecha_emision']) && date('Y', strtotime($prog['fecha_emision'])) == $anioFiltro;
        });
        $programaciones = array_values($programaciones);
    }
    $result = [
        'items' => $programaciones,
        'currentPage' => 1,
        'totalPages' => 1,
        'totalItems' => count($programaciones)
    ];
} else {
    // Si no hay búsqueda, obtener con paginación normal
    $result = $programacionManager->getAllPaginated($currentPage);
    $programaciones = $result['items'];
    // Si hay filtro de año, filtrar por año de fecha_emision
    if (!empty($anioFiltro)) {
        $programaciones = array_filter($programaciones, function($prog) use ($anioFiltro) {
            return !empty($prog['fecha_emision']) && date('Y', strtotime($prog['fecha_emision'])) == $anioFiltro;
        });
        $programaciones = array_values($programaciones);
        $result['items'] = $programaciones;
        $result['totalItems'] = count($programaciones);
        $result['totalPages'] = 1;
        $result['currentPage'] = 1;
    }
}

$totalPages = $result['totalPages'];

// Inicializar HTML
Html::header("Programacion", $_SERVER['PHP_SELF']);

// Cargar estilos de paginación
echo ProgramacionPagination::getStyles();

?>


<!-- Header-->
<div class="center">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <form class="d-flex" method="GET" action="">
                <input type="text" name="search" class="form-control me-2" placeholder="Buscar..." 
                       style="width: 250px;" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <?php echo ButtonComponent::search(); ?>
            </form>

            <div class="d-flex gap-2">
                <?php
                    // Botón de filtro con menú de opciones
                    echo '
            <style>
                .btn-filtrar-icono {
                    color: #6c757d !important; /* gris bootstrap */
                    border: 2px solid #6c757d !important;
                    background: #fff !important;
                    font-weight: 500;
                    height: 38px;
                    width: 38px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 0;
                    transition: border-width 0.2s, font-weight 0.2s, color 0.2s;
                }
                .btn-filtrar-icono:hover, .btn-filtrar-icono:focus {
                    color: #343a40 !important; /* gris oscuro */
                    border-color: #343a40 !important;
                    border-width: 3px !important;
                    font-weight: 700;
                    background: #fff !important;
                }
                .filtro-dropdown {
                    display: none;
                    position: absolute;
                    top: 45px;
                    right: 0;
                    background: #fff;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
                    min-width: 200px;
                    z-index: 1000;
                }
            </style>';
                    ?>
                    <div style="position: relative; display: inline-block;">
                        <button type="button" class="btn btn-filtrar-icono" title="Filtrar" id="btnFiltrar">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                        <div class="filtro-dropdown" id="filtroDropdown">
                            <div style="padding:1rem;">
                                <strong>Opciones de filtrado</strong>
                                <hr style="margin:0.5rem 0;">
                                <div class="mb-2">
                                    <label for="filtroAnio" style="font-size:0.95em;">Año:</label>
                                    <select id="filtroAnio" class="form-control form-control-sm" name="anio" onchange="aplicarFiltro()">
                                        <option value="">Todos</option>
                                        <?php
                                        // Mostrar todos los años únicos de fecha_emision de todas las programaciones
                                        $anios = [];
                                        $todasProgramaciones = $programacionManager->getAll();
                                        if (!empty($todasProgramaciones)) {
                                            foreach ($todasProgramaciones as $prog) {
                                                if (!empty($prog['fecha_emision'])) {
                                                    $anio = date('Y', strtotime($prog['fecha_emision']));
                                                    if (!in_array($anio, $anios)) {
                                                        $anios[] = $anio;
                                                    }
                                                }
                                            }
                                        }
                                        rsort($anios);
                                        foreach ($anios as $anio) {
                                            $selected = ($anio == $anioFiltro) ? ' selected' : '';
                                            echo '<option value="' . $anio . '"' . $selected . '>' . $anio . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <!-- Botón Aplicar eliminado, ya no es necesario -->
                            </div>
                        </div>
                    </div>
            <style>
                .btn-importar-excel {
                    background: #fff !important;
                    color: #217346 !important;
                    border: 2px solid #217346 !important;
                    font-weight: 600;
                    transition: background 0.2s, color 0.2s;
                }
                .btn-importar-excel:hover, .btn-importar-excel:focus {
                    background: #fff !important;
                    color: #14532d !important;
                    border-color: #14532d !important;
                }
                .btn-importar-excel .excel-icon {
                    color: #217346;
                    margin-right: 6px;
                    font-size: 1.1em;
                    vertical-align: middle;
                    transition: color 0.2s;
                }
                .btn-importar-excel:hover .excel-icon, .btn-importar-excel:focus .excel-icon {
                    color: #14532d;
                }
                .modal-importar-excel .modal-content {
                    border-radius: 14px;
                    border: none;
                    box-shadow: 0 8px 32px rgba(60,60,60,0.12);
                    background: #fff;
                    padding: 0;
                }
                .modal-importar-excel .modal-header {
                    border-bottom: none;
                    padding: 2rem 2rem 0.5rem 2rem;
                    background: #f8fafc;
                    border-radius: 14px 14px 0 0;
                }
                .modal-importar-excel .modal-title {
                    font-size: 1.3rem;
                    font-weight: 700;
                    color: #217346;
                    letter-spacing: 0.5px;
                }
                .modal-importar-excel .modal-body {
                    padding: 1.5rem 2rem 2rem 2rem;
                    text-align: center;
                }
                .modal-importar-excel .form-control[type=file] {
                    border: 2px dashed #217346;
                    border-radius: 8px;
                    padding: 1.2rem;
                    background: #f8fafc;
                    color: #217346;
                    font-size: 1rem;
                    margin-bottom: 1.2rem;
                    transition: border-color 0.2s;
                }
                .modal-importar-excel .form-control[type=file]:focus {
                    border-color: #14532d;
                    outline: none;
                }
                .modal-importar-excel .btn-close {
                    background: none;
                    border: none;
                    font-size: 1.3rem;
                    color: #888;
                    opacity: 1;
                    transition: color 0.2s;
                }
                .modal-importar-excel .btn-close:hover {
                    color: #217346;
                }
                .modal-importar-excel .btn-upload {
                    background: #217346;
                    color: #fff;
                    border: none;
                    border-radius: 8px;
                    padding: 0.6rem 1.5rem;
                    font-weight: 600;
                    font-size: 1rem;
                    transition: background 0.2s;
                }
                .modal-importar-excel .btn-upload:hover {
                    background: #14532d;
                }
            </style>
            <button type="button" class="btn btn-importar-excel" data-bs-toggle="modal" data-bs-target="#modalImportarExcel">
                <i class="fa-solid fa-file-excel excel-icon"></i>Importar
            </button>
        <?php
        ?>
        <?php echo ButtonComponent::custom('Nueva programacion', 'fas fa-plus', '', 'openNewProgramacionModal()'); ?>
    </div>
<!-- Modal Importar Excel (solo Bootstrap) -->
<div class="modal fade" id="modalImportarExcel" tabindex="-1" aria-labelledby="modalImportarExcelLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-light border-0">
        <h5 class="modal-title text-success fw-bold d-flex align-items-center gap-2" id="modalImportarExcelLabel">
          <i class="fa-solid fa-file-excel"></i> Importar Excel
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body pb-4">
        <form id="formImportarExcel" enctype="multipart/form-data">
          <div class="mb-3">
            <input class="form-control border border-success border-2 py-3" type="file" accept=".xlsx,.xls" id="inputExcelFile" name="excel_file" required>
          </div>
          <button type="submit" class="btn btn-success w-100 fw-semibold">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i>Subir archivo
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
        </div>
    </div>
</div>


<div class="info-view my-4" >
    <div class="title-view mb-4">
        <h1 style="font-weight: 700; color: #000000;">Programaciones de Servicios</h1>
        <p>Gestiona las programaciones de servicios del área de mantenimiento</p>
    </div>

    <!-- Contenedor para los cards -->
    <div class="card-container mb-4">
        <?php
            if (!empty($programaciones)) {
                foreach ($programaciones as $prog) {
                    // Determinar el estado en texto
                    $estado = 'Pendiente';
                    if ($prog['estado'] == 1) {
                        $estado = 'Revisado';
                    } elseif ($prog['estado'] == 2) {
                        $estado = 'Autorizado';
                    }

                    // Formatear la fecha
                    $fecha = date('d/m/Y', strtotime($prog['fecha_emision']));
                    
                    // Obtener el número real de servicios para esta programación
                    $numServicios = $servicioManager->getCountServiciosByProgramacion($prog['id']);

                    echo ProgramacionCard::render(
                        $prog['nombre_programacion'],
                        '', // Sin descripción
                        $numServicios,
                        $estado,
                        $fecha,
                        $prog['id'] // Pasando el ID de la programación
                    );
                }
            } else {
                echo '<div class="alert alert-info">No hay programaciones registradas.</div>';
            }
        ?>
    </div>

    <!-- Paginación -->
    <?php echo ProgramacionPagination::render($currentPage, $totalPages); ?>
</div>

<!-- Renderizar el modal de programación -->
<?php 
include_once '../componentes/programacion/ProgramacionModal.php';
echo ProgramacionModal::render($programacionManager);
?>

<script>
// Mostrar/ocultar menú de filtrado
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('btnFiltrar');
    var dropdown = document.getElementById('filtroDropdown');
    if (btn && dropdown) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && e.target !== btn) {
                dropdown.style.display = 'none';
            }
        });
    }
});
function aplicarFiltro() {
    var anio = document.getElementById('filtroAnio').value;
    var params = new URLSearchParams(window.location.search);
    if (anio) {
        params.set('anio', anio);
    } else {
        params.delete('anio');
    }
    window.location.search = params.toString();
    document.getElementById('filtroDropdown').style.display = 'none';
}
console.log('ID usuario logueado: <?php echo $id_usuario; ?>, Nombre: <?php echo $nombre_usuario; ?>');
</script>
<?php Html::footer(); ?>