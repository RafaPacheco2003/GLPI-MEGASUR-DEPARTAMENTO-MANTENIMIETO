<?php

include '../../../inc/includes.php';
include '../componentes/ButtonComponent.php';
include '../componentes/servicio/ServicioCard.php';

include '../../../inc/mantenimiento/ServicioManager.php';
include '../../../inc/mantenimiento/ProgramacionManager.php';
include '../componentes/servicio/NuevoServicioModal.php';
include '../componentes/programacion/EditarProgramacionModal.php';


// Verificar que el usuario esté autenticado
Session::checkLoginUser();

// Verificar permisos específicos para el módulo de mantenimiento
if (!Session::haveRight("config", READ) || !Session::haveRight("config", UPDATE)) {
    Html::displayRightError();
    exit();
}

// Verificar que se haya proporcionado un ID válido y que exista la programación
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirigir a la página 404
    header("Location: 404.php");
    exit;
}

// Obtener el ID de la programación de la URL
$id_programacion = (int) $_GET['id'];

// Verificar si existe la programación
$programacionManager = new ProgramacionManager();
$programacion = $programacionManager->getById($id_programacion);

if (!$programacion) {
    // Si no existe la programación, redirigir a 404
    header("Location: 404.php");
    exit;
}

// Inicializar HTML
Html::header("Servicio", $_SERVER['PHP_SELF']);
?>

<!-- Header-->
<div class="center">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">


            <div class="d-flex">
                <?php echo ButtonComponent::volver('Volver', 'fas fa-arrow-left', "http://localhost/glpi/front/mantenimiento/view/programacion.php#"); ?>

                <form class="d-flex" method="GET" action="">
                    <input type="hidden" name="id" value="<?php echo $id_programacion; ?>">
                    <input type="text" name="search" class="form-control me-2 ms-2" placeholder="Buscar por servidor..."
                        style="width: 250px;"
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <?php echo ButtonComponent::search(); ?>
                </form>
            </div>

            <div>
                <?php if (isset($programacion) && isset($programacion['estado']) && $programacion['estado'] != 1): ?>
                <button type="button" class="btn" id="btnMarcarRevisado"
                    style="background-color: white; color: #2563eb; border: 1px solid #2563eb;"
                    onmouseover="this.style.borderColor='#1d4ed8'; this.style.color='#1d4ed8';"
                    onmouseout="this.style.borderColor='#2563eb'; this.style.color='#2563eb';">
                    <i class="me-2"></i> Marcar como revisado
                </button>
                <?php endif; ?>

                <button type="button" class="btn btn-editar-prog" id="btnEditarProg">
                    <i class="me-2 fas fa-edit"></i> Editar programacion
                </button>
                <button type="button" class="btn btn-exportar" id="btnExportar">
                    <i class="me-2 fas fa-file-excel"></i> Exportar a Excel
                </button>
                <?php echo ButtonComponent::custom('Nueva servicio', 'fas fa-plus'); ?>
            </div>
        </div>
    </div>
</div>



<!-- Contenido principal -->
<div class="info-view my-4">
    <div class="title-view">
        <?php
        // Obtener el ID de la programación de la URL
        $id_programacion = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        // Crear instancia del ProgramacionManager y obtener los datos de la programación
        $programacionManager = new ProgramacionManager();
        $programacion = $programacionManager->getById($id_programacion);

        $nombreProgramacion = $programacion ? $programacion['nombre_programacion'] : 'Programación no encontrada';
        $fechaProgramacion = $programacion ? $programacion['fecha_emision'] : 'Fecha no disponible';
        $nombreEmpresa = $programacion ? $programacion['nombre_empresa'] : 'Empresa no disponible';
        ?>
        <div class="d-flex flex-row justify-content-between align-items-center">
            <h1 style="font-weight: 700; color: #000000;"><?php echo htmlspecialchars($nombreProgramacion); ?></h1>
            <p style="color: grey;"> <?php echo htmlspecialchars($fechaProgramacion); ?></p>

        </div>
        <p><?php echo htmlspecialchars($nombreEmpresa); ?></p>
    </div>

    <div class="card-container">
        <?php
        // Obtener el ID de la programación de la URL
        $id_programacion = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        // Obtener término de búsqueda
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Obtener la página actual
        $pagina = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        // Crear instancia del ServicioManager
        $servicioManager = new ServicioManager();

        // Obtener los servicios (con búsqueda si existe)
        $servicios = [];
        if (!empty($searchTerm)) {
            $servicios = $servicioManager->searchServiciosByServidor_site($searchTerm);
        } else {
            $servicios = $servicioManager->getServiciosByProgramacion($id_programacion, $pagina);
        }

        if (!empty($servicios)) {
            foreach ($servicios as $servicio) {
                // Mapear el estado numérico a texto
                $estadoTexto = '';
                $progreso = 1; // Por defecto, una bolita
                switch ($servicio['estado']) {
                    case 0:
                        $estadoTexto = 'Asignado';
                        $progreso = 1;
                        break;
                    case 1:
                        $estadoTexto = 'Proceso';
                        $progreso = 2;
                        break;
                    case 2:
                        $estadoTexto = 'Completado';
                        $progreso = 3;
                        break;
                }

                echo ServicioCard::render(
                    $servicio['servidor_site'],
                    $servicio['afectacion'],
                    $estadoTexto,
                    $progreso,
                    "detalle_servicio.php?id=" . $servicio['id']
                );
            }
        } else {
            echo '<div class="alert alert-info">No hay servicios registrados para esta programación.</div>';
        }
        ?>
    </div>

    <?php
    // Agregar paginación usando el componente ProgramacionPagination
    require_once '../componentes/programacion/ProgramacionPagination.php';
    $totalServicios = $servicioManager->getCountServiciosByProgramacion($id_programacion);
    $totalPaginas = ceil($totalServicios / 3); // 3 es el itemsPerPage definido en ServicioManager
    if ($totalPaginas > 1) {
        // Incluir los estilos de paginación para que se vea igual que en programacion.php
        echo ProgramacionPagination::getStyles();
        // Mantener el parámetro id en la URL
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $paginationHtml = ProgramacionPagination::render($currentPage, $totalPaginas);
        // Reemplazar los links para mantener el id de la programación
        $paginationHtml = preg_replace('/href="\?page=(\d+)"/', 'href="?id=' . $id_programacion . '&page=$1"', $paginationHtml);
        echo $paginationHtml;
    }
    ?>
</div>


<?php
// Renderizar estilos del modal
echo NuevoServicioModal::getStyles();

// Renderizar el modal
echo NuevoServicioModal::render();

// Renderizar el script del modal
echo NuevoServicioModal::getScript();

// Renderizar el modal de edición de programación
echo EditarProgramacionModal::getStyles();
echo EditarProgramacionModal::render();
echo EditarProgramacionModal::getScript();

// Incluir el modal de revisión sin afectar el layout
include '../componentes/programacion/RevisionModal.php';
?>

<script>
    // --- FUNCIONES GLOBALES ---
    window.exportarProgramacion = function () {
        const urlParams = new URLSearchParams(window.location.search);
        const id_programacion = urlParams.get('id');
        if (!id_programacion) {
            alert('No se encontró el ID de la programación');
            return;
        }
        window.location.href = '../../../ajax/mantenimiento/excel_programacion_xlsx.php?id=' + id_programacion;
    };

    // --- EVENTOS DOM ---
    document.addEventListener('DOMContentLoaded', function () {
        // Botón Marcar como revisado (si existe)
        var btnMarcar = document.getElementById('btnMarcarRevisado');
        if (btnMarcar) {
            btnMarcar.addEventListener('click', function () {
                const modal = new bootstrap.Modal(document.getElementById('modalRevisado'));
                modal.show();
            });
        }

        // Inicializar los modales
        const nuevoServicioModal = new bootstrap.Modal(document.getElementById('nuevoServicioModal'));
        // El modal de edición de programación se inicializa en su propio componente, no aquí.

        // Botón guardar edición programación (si existe)
        var btnGuardarEdicion = document.getElementById('btnGuardarEdicionProgramacion');
        if (btnGuardarEdicion) {
            btnGuardarEdicion.addEventListener('click', function () {
                window.guardarEdicionProgramacion();
            });
        }

        // Botón Nueva servicio (si existe)
        const btnNuevoServicio = document.querySelector('button:has(i.fas.fa-plus)');
        if (btnNuevoServicio) {
            btnNuevoServicio.addEventListener('click', function () {
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                document.getElementById('fecha_servicio').value = now.toISOString().split('T')[0];
                const horaActual = now.toTimeString().slice(0, 5);
                document.getElementById('hora_inicio').value = horaActual;
                const horaFinal = new Date(now.getTime() + 60 * 60000).toTimeString().slice(0, 5);
                document.getElementById('hora_final').value = horaFinal;
                nuevoServicioModal.show();
            });
        }

        // Botón editar programación (si existe)
        var btnEditarProg = document.getElementById('btnEditarProg');
        if (btnEditarProg) {
            btnEditarProg.addEventListener('click', function () {
                cargarDatosProgramacion();
            });
        }

        // Botón exportar (si existe)
        var btnExportar = document.getElementById('btnExportar');
        if (btnExportar) {
            btnExportar.addEventListener('click', window.exportarProgramacion);
        }
    });
</script>