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

// Procesar búsqueda si existe
$programaciones = [];
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

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
    $programaciones = $result['items'];
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

            <div>
                <?php echo ButtonComponent::custom('Nueva programacion', 'fas fa-plus', '', 'openNewProgramacionModal()'); ?>
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
    console.log('ID usuario logueado: <?php echo $id_usuario; ?>, Nombre: <?php echo $nombre_usuario; ?>');
</script>
<?php Html::footer(); ?>