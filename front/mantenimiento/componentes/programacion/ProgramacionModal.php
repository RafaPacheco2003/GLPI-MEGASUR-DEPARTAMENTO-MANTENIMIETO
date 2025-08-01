<?php
/**
 * Componente del modal de programación
 */
class ProgramacionModal
{
    /**
     * Renderiza el modal de programación
     * @param ProgramacionManager $programacionManager Instancia del manager
     * @return string HTML del modal
     */
    public static function render($programacionManager)
    {
        // Obtener el id del usuario logueado desde la sesión
        $id_usuario = isset($_SESSION['glpiID']) ? $_SESSION['glpiID'] : '';
        ob_start();
        ?>
        <style>
            /* Tarjeta seleccionada ocupa todo el ancho y se destaca */
            .card-programacion.selected {
                border: 2px solid #2563eb;
                box-shadow: 0 4px 16px rgba(37, 99, 235, 0.08);
                background: #f0f6ff;
            }

            .card-programacion {
                width: 100%;
                min-width: 0;
                border-radius: 10px;
                transition: box-shadow 0.2s;
            }

            #cardsProgramacion .col-12 {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            #selectedProgramacionRow {
                margin-bottom: 1rem;
            }

            #otherProgramacionRow {
                border-top: 1px solid #eee;
                padding-top: 1rem;
            }

            /* Estilos del modal */
            .modern-modal .modal-content {
                border: none;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }

            .modern-modal .modal-header {
                background-color: #f8f9fa;
                border-radius: 15px 15px 0 0;
                padding: 1rem 1.5rem;
                border-bottom: 1px solid #eee;
            }

            .modern-modal .modal-title {
                font-size: 1.2rem;
                font-weight: 600;
                display: flex;
                align-items: center;
            }

            .modern-modal .modal-title i {
                font-size: 1.2rem;
                color: #757575;
            }

            .modern-modal .modal-body {
                padding: 1.2rem 1.2rem 0 1.2rem !important;
                background-color: #fff;
            }
            /* Extra: fuerza el padding-bottom a 0 en cualquier modal-body */
            .modern-modal .modal-body, .modal-body {
                padding-bottom: 0 !important;
            }

            .modern-modal .section-container {
                background-color: #fff;
                border-radius: 10px;
                border: 1px solid #d1d1d1;
                padding: 1rem;
                margin-bottom: 0.8rem;
                transition: all 0.3s ease;
            }

            .modern-modal .section-container:hover {
                border-color: #a1a1a1;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
            }

            .modern-modal .section-title {
                color: #000;
                font-size: 1rem;
                font-weight: 600;
                margin-bottom: 0.8rem;
                padding-bottom: 0.6rem;
                border-bottom: 1px solid #d1d1d1;
                display: flex;
                align-items: center;
            }

            .modern-modal .section-title i {
                font-size: 1rem;
                margin-right: 0.6rem;
                color: #757575;
            }

            .modern-modal .form-label i {
                color: #757575;
                font-size: 0.85rem;
            }

            .modern-modal .form-label {
                font-weight: 500;
                font-size: 0.85rem;
                color: #000;
                margin-bottom: 0.4rem;
            }

            .modern-modal .input-with-icon {
                position: relative;
            }

            .modern-modal .input-with-icon input {
                padding-right: 35px;
                border: 1px solid #d1d1d1;
                border-radius: 8px;
                height: 38px;
            }

            .modern-modal .input-with-icon i {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #757575;
                font-size: 1rem;
            }

            .modern-modal .signature-container {
                width: 100%;
                background: #fff;
                border-radius: 8px;
                overflow: hidden;
            }

            .modern-modal .signature-pad-container {
                border: 2px solid #d1d1d1 !important;
                border-radius: 8px;
                background: #fff;
                position: relative;
                margin-bottom: 10px;
            }

            .modern-modal #signatureCanvas {
                touch-action: none;
                cursor: crosshair;
                width: 100%;
                height: 200px;
                background: #fff;
                display: block;
            }

            .modern-modal .signature-pad-container:hover {
                border-color: #a1a1a1 !important;
            }

            @media (max-width: 768px) {
                .modern-modal #signatureCanvas {
                    height: 120px;
                }
            }

            .modern-modal .signature-controls {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                margin-top: 8px;
            }

            .modern-modal .signature-controls button {
                padding: 4px 12px;
                font-size: 0.875rem;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .modern-modal .modal-footer {
                border-top: none;
                padding: 0rem 1.2rem 0.4rem 1.2rem;
                border-radius: 0 0 20px 20px;
                margin-top: 0;
            }

            /* Botón Guardar: azul */
            .btnGuardar {
                background-color: #2563eb;
                color: #fff;
                border: 1px solid #2563eb;
                font-weight: 500;
                border-radius: 6px;
                transition: background 0.2s, color 0.2s;
            }
            .btnGuardar:hover {
                background-color: #1d4ed8;
                border-color: #1d4ed8;
            }

            /* Botón Cancelar: fondo blanco, bordes y texto azul */
            .btnCancelar {
                background-color: #fff;
                color: #2563eb;
                border: 1px solid #2563eb;
                font-weight: 500;
                border-radius: 6px;
                transition: background 0.2s, color 0.2s;
            }
            .btnCancelar:hover {
                background-color: #f0f6ff;
                color: #1d4ed8;
                border-color: #1d4ed8;
            }
            

            /* Estilos para el pad de firma */
            .signature-pad-container {
                border: 2px dashed #ccc;
                border-radius: 8px;
                background: #fff;
                position: relative;
                margin-bottom: 10px;
            }

            .signature-pad-container canvas {
                width: 100%;
                height: 300px;
                border-radius: 6px;
            }

            .signature-preview {
                width: 100%;
                height: 150px;
                border: 1px solid #ccc;
                border-radius: 8px;
                display: flex;
                align-items: center;
                   
                

                #signatureModal .modal-content {
                    height: 100vh !important;
                }

                #signatureModal .modal-body {
                    height: calc(100vh - 120px) !important;
                }

                #signatureModal .signature-pad-fullscreen {
                    min-height: 200px !important;
                }
            }

            @media (min-width: 992px) {
                #signatureModal .modal-dialog {
                    max-width: 100vw !important;
                }

                #signatureModal .modal-content {
                    height: 100vh !important;
                }

                #signatureModal .modal-body {
                    height: calc(100vh - 120px) !important;
                }

                #signatureModal .signature-pad-fullscreen {
                    min-height: 400px !important;
                }
            }

        </style>

        <!-- Modal Nueva Programación -->
        <div class="modal fade" id="newProgramacionModal" tabindex="-1" aria-labelledby="newProgramacionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 1200px;">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="newProgramacionModalLabel">
                            <i class="fas fa-file-alt me-2"></i> Nueva Programación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <form id="newProgramacionForm">

                            <!-- Sección tipo de programación -->
                            <div class="mb-4" id="sectionEleccion">
                                <?php
                                require_once __DIR__ . '/TipoProgramacionSelector.php';
                                echo TipoProgramacionSelector::render($programacionManager);
                                ?>
                            </div>

                            <!-- Información adicional oculta -->
                            <div id="sectionRestante" style="display:none;">
                                <div class="mb-4">
                                    <h6 class="section-title"><i class="fas fa-info-circle me-2"></i> Información General</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="fechaEmision" class="form-label">Fecha de Emisión</label>
                                                <input type="date" class="form-control" id="fechaEmision" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nombreProgramacion" class="form-label">Nombre de la Programación</label>
                                                <input type="text" class="form-control" id="nombreProgramacion" name="nombreProgramacion" readonly disabled required>
                                                <!-- Campo oculto para el tipo de programación seleccionado -->
                                                <input type="hidden" id="tipoProgramacionSeleccionada" name="tipoProgramacionSeleccionada" value="<?php echo isset($_POST['tipoProgramacionSeleccionada']) ? htmlspecialchars($_POST['tipoProgramacionSeleccionada']) : ''; ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="empresa" class="form-label">Empresa</label>
                                                <input type="text" class="form-control" id="empresa" required>
                                            </div>
                                          
                                            <div class="mb-3">
                                                <label for="elaborador" class="form-label">Responsable / Elaboró</label>
                                                <input type="text" class="form-control" id="elaborador" readonly
                                                    placeholder="Nombre del responsable" value="<?php echo htmlspecialchars(isset($_SESSION['glpiname']) ? $_SESSION['glpiname'] : ''); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Firma del responsable</label>
                                                <div id="signatureContainer"
                                                    style="border: 2px dashed #2563eb; background: #f8f9fa; cursor: pointer; min-height: 80px; display: flex; align-items: center; justify-content: center; position: relative;">
                                                    <img id="signaturePreview"
                                                        style="display: none; max-width: 180px; max-height: 60px; object-fit: contain;"
                                                        alt="Firma">
                                                    <span class="placeholder-text d-block"
                                                        style="color: #2563eb; font-weight: 500;">Click aquí para ingresar
                                                        firma</span>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-8 d-flex align-items-start" style="padding-top:0;">
                                            <div class="w-100" style="margin-top:-13px;">
                                                <?php
                                                require_once __DIR__ . '/ServiciosAccordion.php';
                                                // La cantidad de acordeones la determina ServiciosAccordion.php (PHP)
                                                echo ServiciosAccordion::render();
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btnCancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btnGuardar" onclick="saveNewProgramacion()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Firma -->
        <div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 100vw;">
                <div class="modal-content" style="height: 90vh;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="signatureModalLabel">
                            <i class="fas fa-signature me-2"></i> Firma Digital
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body d-flex flex-column justify-content-center align-items-center pb-0" style="flex: 1;">
                        <div class="w-100" style="max-width: 1000px; flex: 1;">
                            <canvas id="signaturePad"
                                style="width:100%; height:400px; border-radius:12px; border: 1px solid #ccc;"></canvas>
                        </div>
                        <div class="mt-3 d-flex justify-content-end w-100" style="max-width:1000px;">
                            <button type="button" class="btn btn-outline-secondary me-2" id="clearSignature">
                                <i class="fas fa-eraser"></i> Limpiar
                            </button>
                            <button type="button" class="btn btn-primary" id="saveSignature">
                                <i class="fas fa-save"></i> Guardar Firma
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
        <script>
            let signaturePad;
            let signatureModal;
            let programacionModal;
            let signatureFileName = null;

            function openNewProgramacionModal() {
                if (!programacionModal) {
                    programacionModal = new bootstrap.Modal(document.getElementById('newProgramacionModal'));
                }
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('fechaEmision').value = today;
                programacionModal.show();
            }
            // Hacer la función global inmediatamente
            window.openNewProgramacionModal = openNewProgramacionModal;

            document.addEventListener('DOMContentLoaded', function () {
                // Sincronizar el campo oculto con el tipo seleccionado en JS
                const hiddenTipo = document.getElementById('tipoProgramacionSeleccionada');
                // Obtener cantidad de estaciones y crear acordeón dinámicamente
                let estacionesCantidad = null;
                fetch('/glpi/front/mantenimiento/config/get_sucursales.php')
                    .then(response => response.json())
                    .then(data => {
                        estacionesCantidad = Array.isArray(data) ? data.length : 0;
                        console.log('PROGRAMACION ESTACIONES:', estacionesCantidad);
                    });
                signatureModal = new bootstrap.Modal(document.getElementById('signatureModal'));
                programacionModal = new bootstrap.Modal(document.getElementById('newProgramacionModal'));
                // ...existing code...
                // Interceptar el click en cualquier botón cuyo texto sea 'Nueva programacion' (sin depender de onclick)
                document.addEventListener('click', function (e) {
                    let el = e.target;
                    while (el && el !== document) {
                        if (
                            el.tagName === 'BUTTON' &&
                            el.textContent.trim().toLowerCase().includes('nueva programacion')
                        ) {
                            e.preventDefault();
                            openNewProgramacionModal();
                            break;
                        }
                        el = el.parentElement;
                    }
                });
                const signatureContainer = document.getElementById('signatureContainer');
                if (signatureContainer) {
                    signatureContainer.addEventListener('click', function () {
                        showSignatureModal();
                    });
                }
                // Cards de selección de tipo de programación
                const nombreProgramacion = document.getElementById('nombreProgramacion');
                const sectionRestante = document.getElementById('sectionRestante');
                const sectionEleccion = document.getElementById('sectionEleccion');
                const cardsContainer = document.getElementById('cardsProgramacion');
                const cardsProgramacionContainer = document.getElementById('cardsProgramacionContainer');
                const serviciosSection1 = document.getElementById('serviciosSection1');
                const serviciosSection2 = document.getElementById('serviciosSection2');
                // Variable global para el tipo seleccionado
                window.tipoProgramacionSeleccionado = '';
                if (cardsContainer && nombreProgramacion && sectionRestante && sectionEleccion) {
                    cardsContainer.querySelectorAll('.card-programacion').forEach(card => {
                        card.addEventListener('click', function () {
                            // Remover selección previa
                            cardsContainer.querySelectorAll('.card-programacion').forEach(c => c.classList.remove('selected'));
                            this.classList.add('selected');
                            // Sincronizar valor y bloquear campo
                            nombreProgramacion.value = this.getAttribute('data-nombre');
                            // Guardar tipo seleccionado en variable global y en el campo oculto
                            window.tipoProgramacionSeleccionada = this.getAttribute('data-nombre').trim();
                            if (hiddenTipo) hiddenTipo.value = window.tipoProgramacionSeleccionada;
                            // Imprimir en consola que se ha guardado y mostrar cómo se guardó
                            console.log('TIPO DE PROGRAMACION SELECCIONADO GUARDADO:', window.tipoProgramacionSeleccionada);
                            console.log('Se guardó el tipo de programación en la variable window.tipoProgramacionSeleccionada:', window.tipoProgramacionSeleccionada);
                            nombreProgramacion.readOnly = true;
                            nombreProgramacion.disabled = true;

                            // Layout dinámico: tarjeta seleccionada arriba y ancho completo, las demás abajo
                            const allCards = Array.from(cardsContainer.children);
                            const selectedCard = this.parentElement;
                            // Crear contenedores
                            // Layout: todas las tarjetas una debajo de otra
                            allCards.forEach(cardCol => {
                                cardCol.className = 'col-12';
                                cardsContainer.appendChild(cardCol);
                            });

                            sectionRestante.style.display = 'block';
                            sectionEleccion.style.display = 'none';
                            // Mostrar/ocultar secciones de servicios según selección
                            if (window.tipoProgramacionSeleccionada.toLowerCase() === 'servidores y redes') {
                                if (serviciosSection1) serviciosSection1.style.display = 'block';
                                if (serviciosSection2) serviciosSection2.style.display = 'none';
                            } else if (window.tipoProgramacionSeleccionada.toLowerCase() === 'servidores') {
                                if (serviciosSection1) serviciosSection1.style.display = 'none';
                                if (serviciosSection2) serviciosSection2.style.display = 'block';
                            } else {
                                if (serviciosSection1) serviciosSection1.style.display = 'none';
                                if (serviciosSection2) serviciosSection2.style.display = 'none';
                            }

                            // Desplazar a la siguiente sección
                            setTimeout(function () {
                                sectionRestante.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }, 200);
                        });
                    });
                }

            });

            function showSignatureModal() {
                signatureModal.show();
                setTimeout(() => {
                    const canvas = document.getElementById('signaturePad');
                    resizeCanvas(canvas);
                    signaturePad = new SignaturePad(canvas, {
                        penColor: 'rgb(0, 0, 0)'
                    });
                    document.getElementById('clearSignature').onclick = function () {
                        signaturePad.clear();
                        // Mostrar el texto de placeholder si se limpia la firma
                        const placeholder = document.querySelector('#signatureContainer .placeholder-text');
                        if (placeholder) placeholder.style.display = 'block';
                        // Ocultar la imagen de la firma
                        const signaturePreview = document.getElementById('signaturePreview');
                        if (signaturePreview) signaturePreview.style.display = 'none';
                    };
                    document.getElementById('saveSignature').onclick = async function () {
                        if (signaturePad.isEmpty()) {
                            alert('Por favor, realiza una firma antes de guardar.');
                            return;
                        }
                        const dataURL = signaturePad.toDataURL('image/png');
                        try {
                            const fileName = await uploadSignatureImage(dataURL);
                            signatureFileName = fileName;
                            const signaturePreview = document.getElementById('signaturePreview');
                            signaturePreview.src = '/glpi/files/firmas/' + fileName;
                            signaturePreview.style.display = 'block';
                            // Ocultar el texto de placeholder si existe
                            const placeholder = document.querySelector('#signatureContainer .placeholder-text');
                            if (placeholder) placeholder.style.display = 'none';
                            signatureModal.hide();
                        } catch (err) {
                            alert('Error al guardar la firma: ' + err);
                        }
                    };

                    // Mostrar preview en tiempo real al dibujar
                    canvas.addEventListener('mouseup', showPreview);
                    canvas.addEventListener('touchend', showPreview);
                    function showPreview() {
                        if (!signaturePad.isEmpty()) {
                            const dataURL = signaturePad.toDataURL('image/png');
                            const signaturePreview = document.getElementById('signaturePreview');
                            signaturePreview.src = dataURL;
                            signaturePreview.style.display = 'block';
                            const placeholder = document.querySelector('#signatureContainer .placeholder-text');
                            if (placeholder) placeholder.style.display = 'none';
                        }
                    }
                    window.addEventListener('resize', function () { resizeCanvas(canvas); });
                }, 100);
            }

            function resizeCanvas(canvas) {
                // Make the canvas fill the container responsively
                const container = canvas.parentElement;
                let width = container.offsetWidth;
                let height = container.offsetHeight;
                // For tablets/desktop, use more height
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
                canvas.getContext("2d").scale(ratio, ratio);
            }

            async function uploadSignatureImage(dataURL) {
                const response = await fetch('/glpi/ajax/mantenimiento/upload_signature.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ image: dataURL })
                });
                const result = await response.json();
                if (!result.success) throw result.message || 'Error al subir la firma';
                return result.fileName;
            }

            async function saveNewProgramacion() {
                try {
                    const empresa = document.getElementById('empresa').value.trim();
                    const nombreProgramacion = document.getElementById('nombreProgramacion').value.trim();
                    const fechaEmision = document.getElementById('fechaEmision').value;
                    // Obtener el id del usuario logueado desde PHP
                    const idElaborador = '<?php echo isset($_SESSION['glpiID']) ? $_SESSION['glpiID'] : ''; ?>';
                    if (!empresa || !nombreProgramacion || !fechaEmision) {
                        alert('Por favor, completa todos los campos obligatorios.');
                        return;
                    }
                    if (!signatureFileName) {
                        alert('Por favor, agrega una firma antes de guardar.');
                        return;
                    }
                    const data = {
                        nombre_empresa: empresa,
                        nombre_programacion: nombreProgramacion,
                        fecha_emision: fechaEmision,
                        id_elaboro: idElaborador,
                        firma_elaboro: signatureFileName
                    };
                    const response = await fetch('/glpi/ajax/mantenimiento/save_programacion.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });
                    const result = await response.json();
                    if (result.success && result.id) {
                        // Crear servicios en lote usando el id de la programación creada
                        await crearServiciosRelacionados(result.id);
                        alert('Programación y servicios guardados exitosamente');
                        window.location.reload();
                    } else {
                        alert('Error: ' + result.message);
                    }

                    // Función para crear servicios en lote
                    async function crearServiciosRelacionados(idProgramacion) {
                        const serviciosAccordion = document.getElementById('serviciosAccordion');
                        if (!serviciosAccordion) return;
                        const items = serviciosAccordion.querySelectorAll('.accordion-item');
                        for (const item of items) {
                            // Recopilar los datos de cada servicio
                            const fecha_servicio = item.querySelector('input[name="fecha_servicio[]"]')?.value;
                            const hora_inicio = item.querySelector('input[name="hora_inicio[]"]')?.value;
                            const hora_fin = item.querySelector('input[name="hora_fin[]"]')?.value;
                            // Detectar si es plantillaUENS por la existencia del select[name="estacion[]"]
                            const isUENS = !!item.querySelector('select[name="estacion[]"]');
                            // Campos comunes
                            const serie_id = item.querySelector('input[name="serie_id[]"]')?.value || '';
                            const estatus = item.querySelector('input[name="estatus[]"]')?.value || '';
                            // Campos UENS
                            const id_estacion = item.querySelector('select[name="estacion[]"]')?.value || '';
                            const quien = item.querySelector('input[name="quien[]"]')?.value || '';
                            // Campos default
                            let servidor_site = '';
                            let afectacion = '';
                            let serie_folio_hoja_servicio = '';
                            if (isUENS) {
                                // UENS: no tiene servidor_site ni afectacion, pero backend los requiere, así que enviar 'N/A'
                                servidor_site = 'N/A';
                                afectacion = 'N/A';
                                serie_folio_hoja_servicio = item.querySelector('input[name="serie_folio_hoja[]"]')?.value || 'N/A';
                            } else {
                                servidor_site = item.querySelector('input[name="servidor_site[]"]')?.value || 'N/A';
                                afectacion = item.querySelector('select[name="afectacion"]')?.value || 'N/A';
                                serie_folio_hoja_servicio = item.querySelector('input[name="serie_folio_hoja[]"]')?.value || 'N/A';
                            }
                        // Validar solo los campos realmente requeridos por el backend
                        if (!fecha_servicio || !hora_inicio || !hora_fin || !servidor_site || !afectacion) {
                            alert('Faltan campos obligatorios en uno de los servicios. Verifica fecha, horas, servidor y afectación.');
                            continue;
                        }
                            // Formatear fechas
                            const fecha_inicio = new Date(fecha_servicio + 'T' + hora_inicio);
                            const fecha_final = new Date(fecha_servicio + 'T' + hora_fin);
                            const formatearFecha = (fecha) => {
                                return fecha.toISOString().slice(0, 19).replace('T', ' ');
                            };
                            // Enviar todos los campos requeridos y los exclusivos de UENS
                            const servicioData = {
                                fecha_inicio: formatearFecha(fecha_inicio),
                                fecha_final: formatearFecha(fecha_final),
                                servidor_site: servidor_site,
                                serie_id,
                                estatus,
                                afectacion: afectacion,
                                serie_folio_hoja_servicio: serie_folio_hoja_servicio,
                                id_programacion: idProgramacion,
                                id_estacion: id_estacion,
                                quien: quien
                            };
                            // Enviar servicio y verificar respuesta
                            const resp = await fetch('/glpi/ajax/mantenimiento/create_servicio.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify(servicioData)
                            });
                            const result = await resp.json();
                            if (!result.success) {
                                alert('Error al guardar el servicio: ' + (result.message || 'Error desconocido'));
                            }
                        }
                    }
                } catch (error) {
                    alert('Error al guardar la programación: ' + error);
                }
            }
        </script>

        <?php
        return ob_get_clean();
    }
}