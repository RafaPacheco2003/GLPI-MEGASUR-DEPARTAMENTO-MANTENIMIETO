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
        <div class="card-header px-3 py-3 d-flex align-items-center">
            <?php echo ButtonComponent::volver('Volver', 'fas fa-arrow-left', "http://localhost/glpi/front/mantenimiento/view/programacion.php#"); ?>
            <form class="d-flex align-items-center ms-2" method="GET" action="" style="min-width: 200px; max-width: 350px;">
                <input type="hidden" name="id" value="<?php echo $id_programacion; ?>">
                <input type="text" name="search" class="form-control me-2 ms-2"
                    placeholder="Buscar por servidor..." style="width: 100%; min-width: 0; max-width: 350px;"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <?php echo ButtonComponent::search(); ?>
            </form>
            <div class="ms-auto d-flex flex-row align-items-center justify-content-end" style="gap: 0.5rem;">
                <?php if (isset($programacion) && isset($programacion['estado'])): ?>
                    <?php $estado = (int) $programacion['estado']; ?>
                    <?php if ($estado != 1 && $estado != 2): ?>
                        <button type="button" class="btn" id="btnMarcarRevisado"
                            style="background-color: white; color: #2563eb; border: 1px solid #2563eb;"
                            onmouseover="this.style.borderColor='#1d4ed8'; this.style.color='#1d4ed8';"
                            onmouseout="this.style.borderColor='#2563eb'; this.style.color='#2563eb';">
                            <i class="me-2"></i> Marcar como revisado
                        </button>
                    <?php endif; ?>
                    <?php if ($estado === 1): ?>
                        <button type="button" class="btn" id="btnMarcarAutorizado"
                            style="background-color: white; color: #2563eb; border: 1px solid #2563eb;"
                            onmouseover="this.style.borderColor='#1d4ed8'; this.style.color='#1d4ed8';"
                            onmouseout="this.style.borderColor='#2563eb'; this.style.color='#2563eb';"
                            data-usuario="<?php echo htmlspecialchars($_SESSION['glpiname'] ?? ''); ?>"
                            data-id="<?php echo htmlspecialchars($_SESSION['glpiID'] ?? ''); ?>">
                            <i class="me-2"></i> Marcar como autorizado
                        </button>
                    <?php endif; ?>
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
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
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
        const btnMarcar = document.getElementById('btnMarcarRevisado');
        if (btnMarcar) {
            const modalElement = document.getElementById('modalRevisado');
            if (modalElement) {
                btnMarcar.addEventListener('click', function () {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                });
            }
        }

        // --- LÓGICA DE AUTORIZACIÓN CENTRALIZADA ---
        document.body.addEventListener('click', function (e) {
            if (e.target.closest('#btnGuardarAutorizar')) {
                e.preventDefault();
                // Obtener datos necesarios
                const autorizador = document.getElementById('autorizador');
                const idUsuario = document.getElementById('id_usuario_logueado');
                // Obtener el id de la programación desde la URL
                const urlParams = new URLSearchParams(window.location.search);
                const idProgramacion = urlParams.get('id');
                // Obtener la firma (base64) del global o del input oculto
                let firma = null;
                if (window.autorizacionSignatureDataUrl) {
                    firma = window.autorizacionSignatureDataUrl;
                } else if (window.autorizacionSignatureFileName) {
                    // Si se usa upload_signature.php y se guarda el nombre del archivo
                    firma = window.autorizacionSignatureFileName;
                }
                if (!idProgramacion) {
                    alert('No se encontró el ID de la programación.');
                    return;
                }
                if (!idUsuario || !idUsuario.value) {
                    alert('No se encontró el usuario logueado.');
                    return;
                }
                if (!firma) {
                    alert('Por favor, realiza y guarda una firma antes de autorizar.');
                    return;
                }
                // Preparar datos para el endpoint
                const payload = {
                    id: idProgramacion,
                    id_autorizo: idUsuario.value,
                    firma_autorizo: firma
                };
                fetch('../../../ajax/mantenimiento/mark_autorizado.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                })
                    .then(async response => {
                        const data = await response.json();
                        if (response.ok && data.success) {
                       
                            const modal = document.getElementById('modalAutorizar');
                            if (modal) {
                                const modalInstance = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                                modalInstance.hide();
                            }
                            window.location.reload();
                        } else {
                            alert('Error: ' + (data.message || 'No se pudo autorizar la programación.'));
                        }
                    })
                    .catch(error => {
                        alert('Error al guardar la autorización: ' + error.message);
                    });
            }
        });

        const btnMarcarAutorizado = document.getElementById('btnMarcarAutorizado');
        if (btnMarcarAutorizado) {
            btnMarcarAutorizado.addEventListener('click', function () {
                let modalAutorizar = document.getElementById('modalAutorizar');
                const usuario = btnMarcarAutorizado.getAttribute('data-usuario') || '';
                const idUsuario = btnMarcarAutorizado.getAttribute('data-id') || '';

                const asignarValoresFirma = () => {
                    const autorizadorInput = document.getElementById('autorizador');
                    const idUsuarioInput = document.getElementById('id_usuario_logueado');
                    if (autorizadorInput) autorizadorInput.value = usuario;
                    if (idUsuarioInput) idUsuarioInput.value = idUsuario;
                };

                if (!modalAutorizar) {
                    fetch('../componentes/programacion/AutorizarModal.php')
                        .then(response => response.text())
                        .then(html => {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = html;

                            const modalAutorizarElem = tempDiv.querySelector('#modalAutorizar');
                            const modalFirmaElem = tempDiv.querySelector('#autorizacionSignatureModal');

                            if (modalAutorizarElem) document.body.appendChild(modalAutorizarElem);
                            if (modalFirmaElem) document.body.appendChild(modalFirmaElem);

                            asignarValoresFirma();

                            if (typeof initAutorizacionSignaturePad === 'function') {
                                setTimeout(() => { initAutorizacionSignaturePad(); }, 100);
                            }

                            const modal = new bootstrap.Modal(document.getElementById('modalAutorizar'));
                            modal.show();
                        });
                } else {
                    asignarValoresFirma();
                    const modal = new bootstrap.Modal(modalAutorizar);
                    modal.show();
                }
            });
        }

        const nuevoServicioModalElem = document.getElementById('nuevoServicioModal');
        let nuevoServicioModal;
        if (nuevoServicioModalElem) {
            nuevoServicioModal = new bootstrap.Modal(nuevoServicioModalElem);
        }

        const btnGuardarEdicion = document.getElementById('btnGuardarEdicionProgramacion');
        if (btnGuardarEdicion) {
            btnGuardarEdicion.addEventListener('click', function () {
                if (typeof window.guardarEdicionProgramacion === 'function') {
                    window.guardarEdicionProgramacion();
                }
            });
        }

        const btnNuevoServicio = document.querySelector('button:has(i.fas.fa-plus)');
        if (btnNuevoServicio) {
            btnNuevoServicio.addEventListener('click', function () {
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                const fechaInput = document.getElementById('fecha_servicio');
                const horaInicioInput = document.getElementById('hora_inicio');
                const horaFinalInput = document.getElementById('hora_final');

                if (fechaInput) fechaInput.value = now.toISOString().split('T')[0];
                const horaActual = now.toTimeString().slice(0, 5);
                if (horaInicioInput) horaInicioInput.value = horaActual;
                const horaFinal = new Date(now.getTime() + 60 * 60000).toTimeString().slice(0, 5);
                if (horaFinalInput) horaFinalInput.value = horaFinal;

                if (nuevoServicioModal) nuevoServicioModal.show();
            });
        }

        // Validación al guardar nuevo servicio
        const btnGuardarServicio = document.getElementById('btnGuardarServicio');
        if (btnGuardarServicio) {
            btnGuardarServicio.addEventListener('click', function (e) {
                // Evita el envío automático si es un formulario
                e.preventDefault();

                // Obtén los campos requeridos
                const fechaInput = document.getElementById('fecha_servicio');
                const horaInicioInput = document.getElementById('hora_inicio');
                const horaFinalInput = document.getElementById('hora_final');
                const servidorInput = document.getElementById('servidor_site');
                const serieIdInput = document.getElementById('serie_id');
                const estatusInput = document.getElementById('estatus');
                const afectacionInput = document.getElementById('afectacion');
                const folioInput = document.getElementById('serie_folio_hoja_servicio');

                // Valida que no estén vacíos
                if (!fechaInput || !fechaInput.value ||
                    !horaInicioInput || !horaInicioInput.value ||
                    !horaFinalInput || !horaFinalInput.value ||
                    !servidorInput || !servidorInput.value ||
                    !serieIdInput || !serieIdInput.value ||
                    !estatusInput || !estatusInput.value ||
                    !afectacionInput || !afectacionInput.value ||
                    !folioInput || !folioInput.value) {
                    alert('Por favor, completa todos los campos obligatorios.');
                    return;
                }

                // Prepara los datos para el backend
                const data = {
                    fecha_inicio: horaInicioInput.value,
                    fecha_final: horaFinalInput.value,
                    servidor_site: servidorInput.value,
                    afectacion: afectacionInput.value,
                    // Puedes agregar los demás campos si tu backend los acepta
                    fecha_servicio: fechaInput.value,
                    serie_id: serieIdInput.value,
                    estatus: estatusInput.value,
                    serie_folio_hoja_servicio: folioInput.value,
                    id_programacion: (new URLSearchParams(window.location.search)).get('id')
                };

                fetch('../../../ajax/mantenimiento/create_servicio.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Servicio creado exitosamente');
                        // Puedes cerrar el modal y recargar la lista si lo deseas
                        if (nuevoServicioModal) nuevoServicioModal.hide();
                        window.location.reload();
                    } else {
                        alert('Error: ' + result.message);
                    }
                })
                .catch(error => {
                    alert('Error de conexión: ' + error.message);
                });
            });
        }

        const btnEditarProg = document.getElementById('btnEditarProg');
        if (btnEditarProg) {
            btnEditarProg.addEventListener('click', function () {
                if (typeof cargarDatosProgramacion === 'function') {
                    cargarDatosProgramacion();
                }
            });
        }

        const btnExportar = document.getElementById('btnExportar');
        if (btnExportar) {
            btnExportar.addEventListener('click', function () {
                if (typeof window.exportarProgramacion === 'function') {
                    window.exportarProgramacion();
                }
            });
        }
    });
</script>

<!-- Script global para la firma de autorización, siempre disponible -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    // --- LÓGICA DEL MODAL DE AUTORIZACIÓN Y FIRMA ---
    let autorizacionSignaturePad, autorizacionSignatureModal;
    let autorizacionSignatureDataUrl = null;

    // Inicializar la firma y eventos del modal
    function initAutorizacionSignaturePad() {
        console.log('[Firma] Ejecutando initAutorizacionSignaturePad');
        const modalElem = document.getElementById('autorizacionSignatureModal');
        const img = document.getElementById('autorizacionSignatureImg');
        if (!modalElem) { console.error('[Firma] No se encontró el modalElem'); return; }
        if (!img) { console.error('[Firma] No se encontró el img'); return; }
        autorizacionSignatureModal = new bootstrap.Modal(modalElem);

        // Solo registrar una vez
        if (!window._autorizacionSignaturePadDelegated) {
            document.body.addEventListener('click', function (e) {
                // Abrir modal al hacer click en el preview
                if (e.target.closest('#autorizacionSignaturePreview')) {
                    console.log('[Firma] Click en preview, abriendo modal de firma...');
                    autorizacionSignatureModal.show();
                    setTimeout(() => {
                        const canvas = document.getElementById('autorizacionSignaturePad');
                        if (!canvas) { console.error('[Firma] No se encontró el canvas'); return; }
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
                // Guardar autorización (botón Guardar del modal)
                if (e.target.closest('#btnGuardarAutorizar')) {
                    console.log('[Autorizar] Click en Guardar autorización');
                    // Aquí puedes poner la lógica que desees, por ejemplo:
                    const autorizador = document.getElementById('autorizador');
                    const idUsuario = document.getElementById('id_usuario_logueado');
                    if (!autorizacionSignatureDataUrl) {
                        alert('Por favor, realiza y guarda una firma antes de autorizar.');
                        return;
                    }
                    alert('Autorización guardada para: ' + (autorizador ? autorizador.value : '') + '\nID usuario: ' + (idUsuario ? idUsuario.value : ''));
                    // Cerrar modal principal
                    const modal = document.getElementById('modalAutorizar');
                    if (modal) {
                        const modalInstance = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                        modalInstance.hide();
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
        console.log('[Firma] Canvas redimensionado', { width, height, ratio });
    }

    window.addEventListener('resize', function () {
        const canvas = document.getElementById('autorizacionSignaturePad');
        if (autorizacionSignatureModal && canvas && autorizacionSignatureModal._isShown) {
            resizeAutorizacionSignatureCanvas(canvas);
        }
    });
</script>