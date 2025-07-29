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
                 <?php if (isset($programacion) && isset($programacion['estado']) && $programacion['estado'] == 1): ?>
                <button type="button" class="btn" id="btnMarcarAutorizado"
                    style="background-color: white; color: #2563eb; border: 1px solid #2563eb;"
                    onmouseover="this.style.borderColor='#1d4ed8'; this.style.color='#1d4ed8';"
                    onmouseout="this.style.borderColor='#2563eb'; this.style.color='#2563eb';"
                    data-usuario="<?php echo htmlspecialchars(isset($_SESSION['glpiname']) ? $_SESSION['glpiname'] : ''); ?>"
                    data-id="<?php echo htmlspecialchars(isset($_SESSION['glpiID']) ? $_SESSION['glpiID'] : ''); ?>">
                    <i class="me-2"></i> Marcar como autorizado
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


        
        var btnMarcarAutorizado = document.getElementById('btnMarcarAutorizado');
        if (btnMarcarAutorizado) {
            btnMarcarAutorizado.addEventListener('click', function () {
                let modalAutorizar = document.getElementById('modalAutorizar');
                const usuario = btnMarcarAutorizado.getAttribute('data-usuario') || '';
                const idUsuario = btnMarcarAutorizado.getAttribute('data-id') || '';
                if (!modalAutorizar) {
                    // Si el modal no está en el DOM, cargarlo vía AJAX
                    fetch('../componentes/programacion/AutorizarModal.php')
                        .then(response => response.text())
                        .then(html => {
                            // Extraer el modal de firma y ponerlo al final del body
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = html;
                            // Buscar y separar el modal de firma
                            const modalAutorizarElem = tempDiv.querySelector('#modalAutorizar');
                            const modalFirmaElem = tempDiv.querySelector('#autorizacionSignatureModal');
                            if (modalAutorizarElem) document.body.appendChild(modalAutorizarElem);
                            if (modalFirmaElem) document.body.appendChild(modalFirmaElem);
                            // Asignar nombre e id al input y hidden
                            document.getElementById('autorizador').value = usuario;
                            document.getElementById('id_usuario_logueado').value = idUsuario;
                            // Inicializar la firma (por si no se inicializó)
                            if (typeof initAutorizacionSignaturePad === 'function') {
                                setTimeout(() => { initAutorizacionSignaturePad(); }, 100);
                            }
                            // Ahora sí mostrar el modal
                            const modal = new bootstrap.Modal(document.getElementById('modalAutorizar'));
                            modal.show();
                        });
                } else {
                    // Asignar nombre e id al input y hidden
                    document.getElementById('autorizador').value = usuario;
                    document.getElementById('id_usuario_logueado').value = idUsuario;
                    const modal = new bootstrap.Modal(modalAutorizar);
                    modal.show();
                }
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

<!-- Script global para la firma de autorización, siempre disponible -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
let autorizacionSignaturePad, autorizacionSignatureModal;
let autorizacionSignatureDataUrl = null;

function initAutorizacionSignaturePad() {
  console.log('[Firma] Ejecutando initAutorizacionSignaturePad');
  const modalElem = document.getElementById('autorizacionSignatureModal');
  const img = document.getElementById('autorizacionSignatureImg');
  if (!modalElem) { alert('[Firma] No se encontró el modalElem'); console.error('[Firma] No se encontró el modalElem'); return; }
  if (!img) { alert('[Firma] No se encontró el img'); console.error('[Firma] No se encontró el img'); return; }
  autorizacionSignatureModal = new bootstrap.Modal(modalElem);

  // Solo registrar una vez
  if (!window._autorizacionSignaturePadDelegated) {
    document.body.addEventListener('click', function(e) {
      // Abrir modal al hacer click en el preview
      if (e.target.closest('#autorizacionSignaturePreview')) {
        console.log('[Firma] Click en preview, abriendo modal de firma...');
        autorizacionSignatureModal.show();
        setTimeout(() => {
          const canvas = document.getElementById('autorizacionSignaturePad');
          if (!canvas) { alert('[Firma] No se encontró el canvas'); console.error('[Firma] No se encontró el canvas'); return; }
          resizeAutorizacionSignatureCanvas(canvas);
          autorizacionSignaturePad = new SignaturePad(canvas, { penColor: 'rgb(0,0,0)' });
          if (autorizacionSignatureDataUrl) {
            const image = new window.Image();
            image.onload = function () {
              const ctx = canvas.getContext('2d');
              ctx.clearRect(0, 0, canvas.width, canvas.height);
              ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
            };
            image.src = autorizacionSignatureDataUrl;
          } else {
            autorizacionSignaturePad.clear();
          }
          console.log('[Firma] Modal de firma abierto y canvas inicializado');
        }, 200);
      }
      // Limpiar firma
      if (e.target.closest('#clearAutorizacionSignature')) {
        console.log('[Firma] Click en limpiar firma');
        if (autorizacionSignaturePad) autorizacionSignaturePad.clear();
      }
      // Guardar firma
      if (e.target.closest('#saveAutorizacionSignature')) {
        console.log('[Firma] Click en guardar firma');
        const preview = document.getElementById('autorizacionSignaturePreview');
        const imgNew = document.getElementById('autorizacionSignatureImg');
        const placeholderNew = preview ? preview.querySelector('.placeholder-text') : null;
        if (autorizacionSignaturePad && !autorizacionSignaturePad.isEmpty()) {
          autorizacionSignatureDataUrl = autorizacionSignaturePad.toDataURL('image/png');
          fetch('/glpi/ajax/mantenimiento/upload_signature.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ image: autorizacionSignatureDataUrl })
          })
            .then(response => response.json())
            .then(result => {
              if (result.success && result.fileName) {
                imgNew.src = autorizacionSignatureDataUrl;
                imgNew.style.display = 'block';
                if (placeholderNew) placeholderNew.style.display = 'none';
                autorizacionSignatureModal.hide();
                window.autorizacionSignatureFileName = result.fileName;
                console.log('[Firma] Firma guardada correctamente');
              } else {
                alert('Error al guardar la firma: ' + (result.message || 'Error desconocido'));
                console.error('[Firma] Error al guardar la firma', result);
              }
            })
            .catch(e => {
              alert('Error de conexión al guardar la firma: ' + e.message);
              console.error('[Firma] Error de conexión al guardar la firma', e);
            });
        } else {
          alert('Por favor, realiza una firma antes de guardar.');
          console.warn('[Firma] Intento de guardar sin firma');
        }
      }
    });
    window._autorizacionSignaturePadDelegated = true;
  }
}

function resizeAutorizacionSignatureCanvas(canvas) {
  if (!canvas) { console.error('[Firma] No se puede redimensionar: canvas no encontrado'); return; }
  const container = canvas.parentElement;
  let width = container.offsetWidth;
  let height = container.offsetHeight;
  if (window.innerWidth >= 992) {
    height = Math.max(window.innerHeight * 0.7, 400);
  } else {
    height = Math.max(window.innerHeight * 0.5, 200);
  }
  canvas.style.width = width + 'px';
  canvas.style.height = height + 'px';
  const ratio = Math.max(window.devicePixelRatio || 1, 1);
  canvas.width = width * ratio;
  canvas.height = height * ratio;
  canvas.getContext('2d').scale(ratio, ratio);
  console.log('[Firma] Canvas redimensionado', {width, height, ratio});
}

window.addEventListener('resize', function () {
  const canvas = document.getElementById('autorizacionSignaturePad');
  if (autorizacionSignatureModal && canvas && autorizacionSignatureModal._isShown) {
    resizeAutorizacionSignatureCanvas(canvas);
  }
});
</script>