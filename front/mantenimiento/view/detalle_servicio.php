<style>
    #btnLlenarFormulario,
    #btnExportarHojaServicio {
        transition: color 0.2s, border-color 0.2s;
    }
    #btnLlenarFormulario:hover,
    #btnExportarHojaServicio:hover {
        color: #111827; /* texto más oscuro */
        border-color: #111827; /* borde más oscuro */
        background-color: inherit !important;
    }
</style>
<?php
/**
 * Vista principal del módulo de Mantenimiento
 */

include '../../../inc/includes.php';
include '../componentes/ButtonComponent.php';
include '../../../inc/mantenimiento/ServicioManager.php';
include '../../../inc/mantenimiento/ProgramacionManager.php';
include '../../../inc/mantenimiento/HojaServicio.php';
// Verificar permisos'
Session::checkRight("config", UPDATE);

// Verificar que se haya proporcionado un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    Html::displayErrorAndDie(__('Invalid ID'));
}

//Obtener hoja de servicio
$hojaServicio = new HojaServicio();
$hojaServicioData = $hojaServicio->getById_servicio($_GET['id']);



// Obtener el servicio
$servicioManager = new ServicioManager();
$servicio = $servicioManager->getById($_GET['id']);

if (!$servicio) {
    Html::displayErrorAndDie(__('Servicio no encontrado'));
}

// Obtener la programación asociada
$programacionManager = new ProgramacionManager();
$programacion = $programacionManager->getById($servicio['id_programacion']);

// Imprimir en consola el id_programacion y el estado de la programación
if ($programacion && isset($programacion['estado'])) {
    echo "<script>console.log('id_programacion: " . htmlspecialchars($servicio['id_programacion']) . "');console.log('estado programacion: " . htmlspecialchars($programacion['estado']) . "');</script>";
} else {
    echo "<script>console.log('id_programacion: " . htmlspecialchars($servicio['id_programacion']) . "');console.log('No se pudo obtener el estado de la programación');</script>";
}

// Mapear el estado numérico a texto
$estadoTexto = '';
$estadoColor = '';
switch ($servicio['estado']) {
    case 0:
        $estadoTexto = 'Asignado';
        $estadoColor = '#f59e0b'; // Amarillo
        break;
    case 1:
        $estadoTexto = 'En Proceso';
        $estadoColor = '#3b82f6'; // Azul
        break;
    case 2:
        $estadoTexto = 'Completado';
        $estadoColor = '#10b981'; // Verde
        break;
}

// Inicializar HTML
Html::header("detalle-servicio", $_SERVER['PHP_SELF']);
?>


<!-- Header-->
<div class="center">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">


            <div class="d-flex">
                <?php echo ButtonComponent::volver('Volver', 'fas fa-arrow-left', "http://localhost/glpi/front/mantenimiento/view/servicio.php?id=" . $servicio['id_programacion']); ?>

                <input type="text" class="form-control me-2 ms-2" placeholder="Buscar..." style="width: 250px;">
                <?php echo ButtonComponent::search(); ?>
            </div>


        </div>
    </div>
</div>



<!-- Contenido principal -->


<div class="section-info ">
    <div class="row">
        <!-- Columna principal (Información del servicio) -->
        <div class="col-md-9 p-0 m-0">
            <div class="card mb-3">
                <div class="card-body">
                    <h2>Información del servicio</h2>

                    <!-- Primera fila: Programación -->
                    <div class="row mb-3 mt-4">
                        <div class="col-md-6">
                            <p class="mb-0 text-muted mb-2">Programación</p>
                            <p class="fw-semibold">
                                <?php echo htmlspecialchars($programacion['nombre_programacion']); ?>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-0 text-muted mb-2">Servidor/Site</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($servicio['servidor_site']); ?></p>
                        </div>
                    </div>

                    <!-- Segunda fila: Servidor/Site y Fechas -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-0 text-muted mb-2">Fecha y hora de inicio</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($servicio['fecha_inicio']); ?></p>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-0 text-muted mb-2">Fecha y hora de finalización</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($servicio['fecha_final']); ?></p>
                        </div>
                    </div>

                    <!-- Cuarta fila: Afectaciones -->
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-0 text-muted mb-2">Afectaciones al momento</p>
                            <p class="fw-semibold"><?php echo htmlspecialchars($servicio['afectacion']); ?></p>
                        </div>

                        <div class="col-6">
                            <p class="mb-0 text-muted mb-2">Estado actual</p>
                            <span class="px-3 py-1 rounded-pill text-white fw-semibold"
                                style="font-size: 0.7rem; background-color: <?php echo $estadoColor; ?>;">
                                <?php echo htmlspecialchars($estadoTexto); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna lateral (Estado del Servicio) -->
        <div class="col-md-3">
            <?php include __DIR__ . '/../componentes/detalle_servicio/lineaTiempo.php'; ?>
        </div>
    </div>

    <div class="mt-3">
        <?php echo ButtonComponent::primary('Editar servicio', null, 'me-2'); ?>
        <?php if (isset($programacion['estado']) && $programacion['estado'] == 2): ?>
            <?php
                include_once __DIR__ . '/../config/ColorConfig.php';
            ?>
            <?php if (empty($hojaServicioData)): ?>
                <button class="btn btn-outline-dark hover-white" id="btnLlenarFormulario" type="button" data-bs-toggle="modal"
                    data-bs-target="#modalFormularioPuesto">
                    Llenar Hoja de servicio
                </button>
            <?php else: ?>
                <button class="btn btn-outline-dark hover-white" id="btnExportarHojaServicio" type="button">
                    <i class="fas fa-file-excel me-2"></i>Exportar Excel hoja
                </button>
            <?php endif; ?>
        <?php endif; ?>
        <?php include __DIR__ . '/../componentes/detalle_servicio/ModalHojaServicioComponent.php'; ?>
    </div>

    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    

    <!-- FontAwesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />