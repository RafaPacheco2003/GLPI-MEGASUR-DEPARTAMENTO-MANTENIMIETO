
<?php
/**
 * Componente: Modal Hoja de Servicio (wizard)
 * Ubicación: /componentes/detalle_servicio/ModalHojaServicioComponent.php
 *
 * Este componente contiene el modal completo para la hoja de servicio, con toda la estructura, estilos y scripts necesarios.
 */

require_once '../../../inc/mantenimiento/ServicioManager.php';


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    Html::displayErrorAndDie(__('Invalid ID'));
}


$servicioManager = new ServicioManager();
$servicio = $servicioManager->getById($_GET['id']);
?>

<!-- Modal Formulario hoja de servicio (wizard) -->
<div class="modal fade" id="modalFormularioPuesto" tabindex="-1" aria-labelledby="modalFormularioPuestoLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modern-modal" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormularioPuestoLabel">
                    <i class="fas fa-file-alt me-2" style="font-size: 1.2rem;"></i>
                    Hoja de servicio sistemas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formHoja">
                    <!-- Paso 1: Datos de la Estación de servicio -->
                    <div id="step1" class="wizard-step">
                        <div class="section-container">
                            <h6 class="section-title">
                                <i class="fas fa-gas-pump me-2"></i>
                                Datos de la Estación de servicio
                            </h6>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="folio" class="form-label">Folio</label>
                                    <div class="input-group">
                                        <span class="input-group-text"
                                            style="padding: 0.375rem 0.75rem; color: #757575;"><i
                                                class="fas fa-hashtag fa-sm"></i></span>
                                        <input type="text" class="form-control" id="folio" name="folio" maxlength="20"
                                            required placeholder="Folio">
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="serie" class="form-label">Serie</label>
                                    <input type="text" class="form-control" id="serie" name="serie" maxlength="5"
                                        required placeholder="Serie">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="estacion" class="form-label">Estación</label>
                                <div class="input-with-icon">
                                    <select class="form-select text-dark" id="estacion" name="estacion" required>
                                        <option value="">Seleccione una estación</option>
                                    </select>
                                    <i class="fas fa-building"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="ubicacion" class="form-label">Ubicación/Dirección</label>
                                <div class="input-with-icon">
                                    <textarea class="form-control" id="ubicacion" name="ubicacion" rows="3" readonly
                                        style="resize:none; background:#f5f5f5;"></textarea>
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="rfc" class="form-label">RFC</label>
                                    <div class="input-with-icon">
                                        <input type="text" class="form-control" id="rfc" name="rfc" maxlength="13"
                                            required readonly style="background:#f5f5f5;">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="nestacion" class="form-label">N.Estación</label>
                                    <div class="input-with-icon">
                                        <input type="text" class="form-control" id="nestacion" name="nestacion"
                                            maxlength="5" required readonly style="background:#f5f5f5;">
                                        <i class="fas fa-list-ol"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="npcre" class="form-label">N.P CRE</label>
                                <div class="input-with-icon">
                                    <input type="text" class="form-control" id="npcre" name="npcre" maxlength="5"
                                        required readonly style="background:#f5f5f5;">
                                    <i class="fas fa-certificate"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <div class="input-with-icon">
                                    <input type="text" class="form-control" id="telefono" name="telefono" maxlength="10"
                                        required>
                                    <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="jefe" class="form-label">Jefe de estación</label>
                                <div class="input-with-icon">
                                    <input type="text" class="form-control" id="jefe" name="jefe" required>
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="fecha" name="fecha" required readonly
                                        style="background:#f5f5f5;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="hora_inicio" class="form-label">Hora de inicio</label>
                                    <div class="input-group">
                                        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio"
                                            required>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="hora_fin" class="form-label">Hora de fin</label>
                                    <div class="input-group">
                                        <input type="time" class="form-control" id="hora_fin" name="hora_fin" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 2: Descripción del servicio -->
                    <div id="step2" class="wizard-step" style="display:none;">
                        <div class="section-container" style="min-height: 200px;">
                            <h6 class="section-title">
                                <i class="fas fa-tools me-2"></i>
                                Descripción del servicio
                            </h6>
                            <label for="servicioSelect" class="form-label">Servicio</label>
                            <div class="input-with-icon mb-3">
                                <select class="form-select" id="servicioSelect" name="servicioSelect" required>
                                    <option value="">Seleccione un servicio</option>
                                    <option value="instalacion">Instalación</option>
                                    <option value="mantenimiento">Mantenimiento</option>
                                    <option value="retiro">Retiro</option>
                                    <option value="proyecto">Proyecto</option>
                                </select>
                                <i class="fas fa-cogs"></i>
                            </div>
                            <label for="descripcionServicio" class="form-label">Descripción</label>
                            <div class="input-with-icon mb-3">
                                <input type="text" class="form-control" id="descripcionServicio"
                                    name="descripcionServicio"
                                    placeholder="Se realizó mantenimiento preventivo y correctivo a la estación de servicio."
                                    required>
                                <i class="fas fa-align-left"></i>
                            </div>
                            <!-- Campo único: Artículo con búsqueda profesional (Select2 AJAX) -->
                            <label for="articuloSelect" class="form-label">Buscar artículo por serie o nombre</label>
                            <div class="input-with-icon mb-3">
                                <select class="form-select" id="articuloSelect" name="articuloSelect" required></select>
                                <i class="fas fa-search"></i>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const articuloSelect = document.getElementById('articuloSelect');
                                    $(articuloSelect).select2({
                                        dropdownParent: $('#modalFormularioPuesto'),
                                        width: '100%',
                                        placeholder: 'Escribe serie o nombre para buscar',
                                        allowClear: true,
                                        minimumInputLength: 2,
                                        ajax: {
                                            url: '../config/get_articulos.php',
                                            dataType: 'json',
                                            delay: 300,
                                            data: function (params) {
                                                return {
                                                    serie: params.term // lo que el usuario escribe
                                                };
                                            },
                                            processResults: function (data) {
                                                // data es un array de objetos [{tabla, id, name, serial}]
                                                return {
                                                    results: data.map(function (item) {
                                                        return {
                                                            id: item.id, // valor oculto
                                                            text: item.name + ' (' + item
                                                                .serial +
                                                                ')', // lo que se muestra
                                                            serial: item.serial,
                                                            name: item.name
                                                        };
                                                    })
                                                };
                                            },
                                            cache: true
                                        },
                                        templateResult: function (data) {
                                            if (!data.id) return data.text;
                                            // Muestra el nombre y la serie
                                            return $('<span>' + data.name +
                                                ' <span style="color:#888;font-size:0.8em;">(' +
                                                data.serial + ')</span></span>');
                                        },
                                        templateSelection: function (data) {
                                            if (!data.id) return data.text;
                                            // Solo muestra el nombre seleccionado
                                            return data.name;
                                        }
                                    });
                                });
                            </script>
                            <!-- Nuevo campo: Descripción 2 -->
                            <label for="descripcion2" class="form-label">Descripción 2</label>
                            <div class="input-with-icon mb-3">
                                <textarea class="form-control" id="descripcion2" name="descripcion2" rows="3"
                                    placeholder="Descripción adicional..." required style="resize:vertical;"></textarea>
                                <i class="fas fa-align-left"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 3: Firmas -->
                    <div id="step3" class="wizard-step" style="display:none;">
                        <div class="section-container" style="min-height: 200px;">
                            <h6 class="section-title">
                                <i class="fas fa-signature me-2"></i>
                                Firmas
                            </h6>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="firmaCliente" class="form-label">Entregado</label>
                                    <div class="input-with-icon">
                                        <input type="text" class="form-control" id="firmaCliente" name="firmaCliente"
                                            placeholder="Nombre del cliente" required readonly style="background:#f5f5f5;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-signature me-2"></i>
                                        Firma
                                    </label>
                                    <div id="firmaTecnicoContainer" class="signature-preview" style="cursor: pointer; width: 100%; min-height: 80px; display: flex; align-items: center; justify-content: center; border: 1px dashed #bbb; border-radius: 8px; background: #fafafa;">
                                        <img id="firmaTecnicoPreview" style="display: none; max-width: 100%; max-height: 120px;" alt="Firma del técnico">
                                        <span class="placeholder-text">Click aquí para firmar</span>
                                    </div>
                                </div>
                                <!-- Recibido -->
                                <div class="col-12 mb-3">
                                    <label for="firmaRecibido" class="form-label">Recibido</label>
                                    <div class="input-with-icon">
                                        <input type="text" class="form-control" id="firmaRecibido" name="firmaRecibido"
                                            placeholder="Nombre de quien recibe" required>
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-signature me-2"></i>
                                        Firma Recibido
                                    </label>
                                    <div id="firmaRecibidoContainer" class="signature-preview" style="cursor: pointer; width: 100%; min-height: 80px; display: flex; align-items: center; justify-content: center; border: 1px dashed #bbb; border-radius: 8px; background: #fafafa;">
                                        <img id="firmaRecibidoPreview" style="display: none; max-width: 100%; max-height: 120px;" alt="Firma de recibido">
                                        <span class="placeholder-text">Click aquí para firmar</span>
                                    </div>
                                </div>
                    <!-- Modal para la firma de recibido -->
                    <div class="modal fade" id="firmaRecibidoModal" tabindex="-1"
                        aria-labelledby="firmaRecibidoModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-fullscreen-sm-down modal-xl" style="max-width:100vw; margin:0;">
                            <div class="modal-content" style="height:100vh;">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="firmaRecibidoModalLabel">
                                        <i class="fas fa-signature me-2"></i>
                                        Firma de recibido
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body"
                                    style="height:calc(100vh - 120px); display:flex; flex-direction:column; justify-content:center; align-items:center;">
                                    <div class="signature-pad-container signature-pad-fullscreen"
                                        style="width:100%; max-width:1200px; height:100%; flex:1; display:flex; align-items:center; justify-content:center;">
                                        <canvas id="firmaRecibidoPad"
                                            style="width:100%; height:100%; min-height:300px; border-radius:12px;"></canvas>
                                    </div>
                                    <div class="signature-controls mt-3"
                                        style="width:100%; max-width:1200px; display:flex; justify-content:flex-end;">
                                        <button type="button" class="btn btn-outline-secondary" id="clearFirmaRecibido">
                                            <i class="fas fa-eraser"></i> Limpiar
                                        </button>
                                        <button type="button" class="btn btn-primary" id="saveFirmaRecibido">
                                            <i class="fas fa-save"></i> Guardar Firma
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                            </div>
                        </div>
                    </div>


                    <!-- Controles de navegación (dentro del form, después de los pasos) -->
                    <div class="wizard-controls mt-3">
                        <div id="controls-step1" style="display: flex; justify-content: flex-end;">
                            <button type="button" class="btn btn-next" id="btnNext1">Siguiente</button>
                        </div>
                        <div id="controls-step2" style="display: none; justify-content: space-between;">
                            <button type="button" class="btn btn-back" id="btnBack1">Anterior</button>
                            <button type="button" class="btn btn-next" id="btnNext2">Siguiente</button>
                        </div>
                        <div id="controls-step3" style="display: none; justify-content: space-between;">
                            <button type="button" class="btn btn-back" id="btnBack2">Anterior</button>
                            <button type="button" class="btn btn-next" id="btnGuardarPuesto">Guardar</button>
                        </div>
                    </div>

                    <!-- Modal para la firma del técnico -->
                    <!-- Modal para la firma del técnico -->
                    <div class="modal fade" id="firmaTecnicoModal" tabindex="-1"
                        aria-labelledby="firmaTecnicoModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-fullscreen-sm-down modal-xl" style="max-width:100vw; margin:0;">
                            <div class="modal-content" style="height:100vh;">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="firmaTecnicoModalLabel">
                                        <i class="fas fa-signature me-2"></i>
                                        Firma del técnico
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body"
                                    style="height:calc(100vh - 120px); display:flex; flex-direction:column; justify-content:center; align-items:center;">
                                    <div class="signature-pad-container signature-pad-fullscreen"
                                        style="width:100%; max-width:1200px; height:100%; flex:1; display:flex; align-items:center; justify-content:center;">
                                        <canvas id="firmaTecnicoPad"
                                            style="width:100%; height:100%; min-height:300px; border-radius:12px;"></canvas>
                                    </div>
                                    <div class="signature-controls mt-3"
                                        style="width:100%; max-width:1200px; display:flex; justify-content:flex-end;">
                                        <button type="button" class="btn btn-outline-secondary" id="clearFirmaTecnico">
                                            <i class="fas fa-eraser"></i> Limpiar
                                        </button>
                                        <button type="button" class="btn btn-primary" id="saveFirmaTecnico">
                                            <i class="fas fa-save"></i> Guardar Firma
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
                    <script>
                        let firmaTecnicoPad = null;
                        let firmaTecnicoModal = null;
                        let firmaTecnicoFileName = null;
                        let firmaRecibidoPad = null;
                        let firmaRecibidoModal = null;
                        let firmaRecibidoFileName = null;

                        function showFirmaTecnicoModal() {
                            if (!firmaTecnicoModal) {
                                firmaTecnicoModal = new bootstrap.Modal(document.getElementById('firmaTecnicoModal'));
                            }
                            firmaTecnicoModal.show();
                            setTimeout(() => {
                                const canvas = document.getElementById('firmaTecnicoPad');
                                resizeFirmaTecnicoCanvas(canvas);
                                if (!firmaTecnicoPad) {
                                    firmaTecnicoPad = new SignaturePad(canvas, {
                                        penColor: 'rgb(0, 0, 0)'
                                    });
                                } else {
                                    firmaTecnicoPad.clear();
                                }
                                document.getElementById('clearFirmaTecnico').onclick = function () {
                                    firmaTecnicoPad.clear();
                                };
                                document.getElementById('saveFirmaTecnico').onclick = async function () {
                                    if (firmaTecnicoPad.isEmpty()) {
                                        alert('Por favor, realiza una firma antes de guardar.');
                                        return;
                                    }
                                    const dataURL = firmaTecnicoPad.toDataURL('image/png');
                                    try {
                                        const fileName = await uploadFirmaTecnicoImage(dataURL);
                                        firmaTecnicoFileName = fileName;
                                        const firmaTecnicoPreview = document.getElementById('firmaTecnicoPreview');
                                        firmaTecnicoPreview.src = '/glpi/files/firmas/' + fileName;
                                        firmaTecnicoPreview.style.display = 'block';
                                        const placeholder = document.querySelector('#firmaTecnicoContainer .placeholder-text');
                                        if (placeholder) placeholder.style.display = 'none';
                                        firmaTecnicoModal.hide();
                                    } catch (err) {
                                        alert('Error al guardar la firma: ' + err);
                                    }
                                };
                                window.addEventListener('resize', function () {
                                    resizeFirmaTecnicoCanvas(canvas);
                                });
                            }, 100);
                        }

                        function showFirmaRecibidoModal() {
                            if (!firmaRecibidoModal) {
                                firmaRecibidoModal = new bootstrap.Modal(document.getElementById('firmaRecibidoModal'));
                            }
                            firmaRecibidoModal.show();
                            setTimeout(() => {
                                const canvas = document.getElementById('firmaRecibidoPad');
                                resizeFirmaTecnicoCanvas(canvas);
                                if (!firmaRecibidoPad) {
                                    firmaRecibidoPad = new SignaturePad(canvas, {
                                        penColor: 'rgb(0, 0, 0)'
                                    });
                                } else {
                                    firmaRecibidoPad.clear();
                                }
                                document.getElementById('clearFirmaRecibido').onclick = function () {
                                    firmaRecibidoPad.clear();
                                };
                                document.getElementById('saveFirmaRecibido').onclick = async function () {
                                    if (firmaRecibidoPad.isEmpty()) {
                                        alert('Por favor, realiza una firma antes de guardar.');
                                        return;
                                    }
                                    const dataURL = firmaRecibidoPad.toDataURL('image/png');
                                    try {
                                        const fileName = await uploadFirmaTecnicoImage(dataURL);
                                        firmaRecibidoFileName = fileName;
                                        const firmaRecibidoPreview = document.getElementById('firmaRecibidoPreview');
                                        firmaRecibidoPreview.src = '/glpi/files/firmas/' + fileName;
                                        firmaRecibidoPreview.style.display = 'block';
                                        const placeholder = document.querySelector('#firmaRecibidoContainer .placeholder-text');
                                        if (placeholder) placeholder.style.display = 'none';
                                        firmaRecibidoModal.hide();
                                    } catch (err) {
                                        alert('Error al guardar la firma: ' + err);
                                    }
                                };
                                window.addEventListener('resize', function () {
                                    resizeFirmaTecnicoCanvas(canvas);
                                });
                            }, 100);
                        }

                        function resizeFirmaTecnicoCanvas(canvas) {
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
                            canvas.getContext("2d").scale(ratio, ratio);
                        }

                        async function uploadFirmaTecnicoImage(dataURL) {
                            const response = await fetch('/glpi/ajax/mantenimiento/upload_signature.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    image: dataURL
                                })
                            });
                            const result = await response.json();
                            if (!result.success) throw result.message || 'Error al subir la firma';
                            return result.fileName;
                        }

                        document.addEventListener('DOMContentLoaded', function () {
                            const firmaTecnicoContainer = document.getElementById('firmaTecnicoContainer');
                            if (firmaTecnicoContainer) {
                                firmaTecnicoContainer.addEventListener('click', function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    showFirmaTecnicoModal();
                                });
                            }
                            const firmaRecibidoContainer = document.getElementById('firmaRecibidoContainer');
                            if (firmaRecibidoContainer) {
                                firmaRecibidoContainer.addEventListener('click', function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    showFirmaRecibidoModal();
                                });
                            }
                        });
                    </script>
            </div>
        </div>
    </div>

    </form>
</div>
</div>
</div>
</div>

<style>
    .wizard-step .section-container {
        padding: 1.5rem 1.2rem !important;
        border-radius: 12px !important;
        border: 1px solid #d1d1d1 !important;
        background: #fff !important;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        margin-bottom: 0;
    }

    .btn-next {
        background-color: #2563eb;
        color: #fff;
        border: 1px solid #2563eb;
        transition: background 0.2s, color 0.2s;
        padding: 8px 20px;
        border-radius: 8px;
    }

    .btn-next:hover {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
        color: #fff;
    }

    .btn-back {
        background-color: #fff;
        color: #2563eb;
        border: 1px solid #2563eb;
        transition: background 0.2s, color 0.2s;
        padding: 8px 20px;
        border-radius: 8px;
    }

    .btn-back:hover {
        background-color: #f0f5ff;
        color: #1d4ed8;
        border-color: #1d4ed8;
    }

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
        padding: 1.5rem;
        background-color: #fff;
    }

    .modern-modal .section-title {
        color: #000;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
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

    .modern-modal .form-label {
        font-weight: 500;
        font-size: 0.85rem;
        color: #000;
        margin-bottom: 0.4rem;
    }

    .modern-modal .input-with-icon {
        position: relative;
    }

    .modern-modal .input-with-icon input,
    .modern-modal .input-with-icon select {
        padding-right: 35px;
        border: 1px solid #d1d1d1;
        border-radius: 8px;
        height: 38px;
        width: 100%;
    }

    .modern-modal .input-with-icon i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #757575;
        font-size: 1rem;
    }

    .wizard-controls {
        display: flex;
        padding: 0 0.5rem;
    }

    .wizard-controls>div {
        width: 100%;
    }
</style>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Wizard navigation
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        const controls1 = document.getElementById('controls-step1');
        const controls2 = document.getElementById('controls-step2');
        const controls3 = document.getElementById('controls-step3');

        let sucursalesData = [];

        // Validación y restricción de hora de inicio y fin
        const horaInicioInput = document.getElementById('hora_inicio');
        const horaFinInput = document.getElementById('hora_fin');

        function ajustarMinHoraFin() {
            if (horaInicioInput && horaFinInput) {
                const inicio = horaInicioInput.value;
                if (inicio) {
                    // El valor mínimo de hora_fin debe ser 1 minuto después de hora_inicio
                    let [h, m] = inicio.split(":").map(Number);
                    m++;
                    if (m >= 60) { h++; m = 0; }
                    let minFin = (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m;
                    horaFinInput.min = minFin;
                    horaFinInput.disabled = false;
                    // Si la hora_fin actual es menor o igual, la limpia
                    if (horaFinInput.value && horaFinInput.value < minFin) {
                        horaFinInput.value = '';
                    }
                    // Establecer automáticamente una hora después como valor por defecto
                    let hFin = parseInt(inicio.split(":")[0], 10) + 1;
                    let mFin = parseInt(inicio.split(":")[1], 10);
                    if (hFin >= 24) hFin = 0;
                    let sugerido = (hFin < 10 ? '0' : '') + hFin + ':' + (mFin < 10 ? '0' : '') + mFin;
                    // Solo poner el valor sugerido si está vacío o es menor al mínimo
                    if (!horaFinInput.value || horaFinInput.value < minFin) {
                        horaFinInput.value = sugerido;
                    }
                } else {
                    horaFinInput.min = '';
                    horaFinInput.value = '';
                    horaFinInput.disabled = true;
                }
            }
        }

        function validarHoras() {
            const inicio = horaInicioInput.value;
            const fin = horaFinInput.value;
            if (inicio && fin) {
                // Calcular el mínimo permitido (1 minuto después de inicio)
                let [h, m] = inicio.split(":").map(Number);
                m++;
                if (m >= 60) { h++; m = 0; }
                let minFin = (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m;
                if (fin < minFin) {
                    horaFinInput.setCustomValidity('La hora de fin debe ser mayor que la hora de inicio.');
                    horaFinInput.value = '';
                    mostrarErrorHoraFin('La hora de fin debe ser mayor que la hora de inicio.');
                } else {
                    horaFinInput.setCustomValidity('');
                    ocultarErrorHoraFin();
                }
            } else {
                horaFinInput.setCustomValidity('');
                ocultarErrorHoraFin();
            }
        }

        // Mensaje visual inmediato
        function mostrarErrorHoraFin(msg) {
            let error = document.getElementById('horaFinError');
            if (!error) {
                error = document.createElement('div');
                error.id = 'horaFinError';
                error.className = 'invalid-feedback d-block';
                horaFinInput.parentElement.appendChild(error);
            }
            error.textContent = msg;
        }
        function ocultarErrorHoraFin() {
            let error = document.getElementById('horaFinError');
            if (error) error.remove();
        }

        if (horaInicioInput && horaFinInput) {
            // Al inicio, deshabilitar hora_fin
            if (!horaInicioInput.value) {
                horaFinInput.disabled = true;
            }
            horaInicioInput.addEventListener('change', function() {
                ajustarMinHoraFin();
                validarHoras();
            });
            horaFinInput.addEventListener('input', validarHoras);
            horaFinInput.addEventListener('change', validarHoras);
            // Al cargar, también ajustar
            ajustarMinHoraFin();
        }

        function showStep(stepToShow, controlsToShow) {
            // Ocultar todos los pasos
            [step1, step2, step3].forEach(step => {
                step.style.display = 'none';
            });
            [controls1, controls2, controls3].forEach(ctrl => {
                ctrl.style.display = 'none';
            });

            // Mostrar el paso actual y sus controles
            stepToShow.style.display = 'block';
            controlsToShow.style.display = 'flex';

            // Ajustar scroll al inicio
            document.querySelector('.modal-body').scrollTop = 0;
        }

        document.getElementById('btnNext1').addEventListener('click', function () {
            // Cerrar Select2 de articuloSelect si está abierto
            if ($('#articuloSelect').data('select2')) {
                $('#articuloSelect').select2('close');
            }
            showStep(step2, controls2);
        });
        document.getElementById('btnBack1').addEventListener('click', function () {
            if ($('#articuloSelect').data('select2')) {
                $('#articuloSelect').select2('close');
            }
            showStep(step1, controls1);
        });
        document.getElementById('btnNext2').addEventListener('click', function () {
            if ($('#articuloSelect').data('select2')) {
                $('#articuloSelect').select2('close');
            }
            showStep(step3, controls3);
        });
        document.getElementById('btnBack2').addEventListener('click', function () {
            if ($('#articuloSelect').data('select2')) {
                $('#articuloSelect').select2('close');
            }
            showStep(step2, controls2);
        });
        document.getElementById('btnGuardarPuesto').addEventListener('click', function () {
            const form = document.getElementById('formHoja');
            validarHoras(); // Validar antes de guardar
            if (form.checkValidity()) {
                alert('Formulario guardado correctamente');
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalFormularioPuesto'));
                modal.hide();
            } else {
                form.reportValidity();
            }
        });

        // Reiniciar wizard al abrir el modal
        document.getElementById('modalFormularioPuesto').addEventListener('show.bs.modal', function () {
            // Si se acaba de guardar la firma, mantener el paso de firmas
            if (window._mantenerPasoFirmas) {
                showStep(step3, controls3);
                window._mantenerPasoFirmas = false;
            } else {
                showStep(step1, controls1);
            }
            // Mantener el wizard en el paso de firmas al cerrar el modal de firma
            window._mantenerPasoFirmas = true;
            // Llenar el select de estación desde la API PHP SIEMPRE que se abre el modal (para evitar problemas visuales)
            const estacionSelect = document.getElementById('estacion');
            // Si ya tiene Select2, destruirlo antes de manipular el select
            if (window.jQuery && $(estacionSelect).data('select2')) {
                $(estacionSelect).select2('destroy');
            }
            estacionSelect.innerHTML = '<option value="">Seleccione una estación</option>';
            fetch('../config/get_sucursales.php')
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data)) {
                        sucursalesData = data;
                        // Imprimir todas las estaciones en la consola
                        console.log('Estaciones obtenidas:');
                        data.forEach(function (item) {
                            console.log(
                                'IdEmpresa:', item.IdEmpresa || item.Empresa,
                                '\nNombreEmpresa:', item.NombreEmpresa || item.Nombre,
                                '\nRFCEmpresa:', item.RFCEmpresa || item.RFC,
                                '\nIdSucursal:', item.IdSucursal || item.Sucursal,
                                '\nNombreSucursal:', item.NombreSucursal || item.Nombre,
                                '\ncodigoPostal:', item.codigoPostal || item.CodigoPostal,
                                '\nRFC:', item.RFC,
                                '\nListaPreciosEsp:', item.ListaPreciosEsp,
                                '\nEncargado:', item.Encargado,
                                '\nDireccion:', item.Direccion,
                                '\nPoblacion:', item.Poblacion,
                                '\nEstado:', item.Estado,
                                '\nPais:', item.Pais,
                                '\nTelefonos:', item.Telefonos,
                                '\n-----------------------------'
                            );
                        });
                        data.forEach(function (item) {
                            const option = document.createElement('option');
                            option.value = item.IdSucursal;
                            option.textContent = item.NombreSucursal;
                            // Guardar el índice para fácil acceso
                            option.setAttribute('data-index', sucursalesData.indexOf(item));
                            estacionSelect.appendChild(option);
                        });
                        // Guardar el id de estación para usarlo después de inicializar Select2
                        var idEstacionServicio = <?php echo json_encode($servicio['id_estacion'] ?? null); ?>;
                        setTimeout(function() {
                            initSelect2Estacion();
                            // Seleccionar automáticamente la estación SOLO después de inicializar Select2
                            if (idEstacionServicio && window.jQuery && $(estacionSelect).data('select2')) {
                                // Selecciona la estación y dispara el evento change (Select2 actualiza UI y dispara handlers)
                                $(estacionSelect).val(idEstacionServicio).trigger('change');
                            }
                        }, 100);
                    }
                })
                .catch(error => {
                    console.error('Error al cargar estaciones:', error);
                });
            // Establecer la fecha de hoy y hacerla no editable
            const fechaInput = document.getElementById('fecha');
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            fechaInput.value = `${yyyy}-${mm}-${dd}`;
            fechaInput.readOnly = true;

            // Auto-rellenar el campo Nombre entregado (firmaCliente) con el nombre del usuario logueado
            const firmaClienteInput = document.getElementById('firmaCliente');
            if (firmaClienteInput) {
                firmaClienteInput.value = <?php echo json_encode($_SESSION['glpiname'] ?? ''); ?>;
            }

            // Imprimir el id_estacion del servicio en la consola al abrir el modal
            console.log('id_estacion:', <?php echo json_encode($servicio['id_estacion'] ?? null); ?>);
        });
        // Inicializar Select2 después de llenar el select
        function initSelect2Estacion() {
            $('#estacion').select2({
                dropdownParent: $('#modalFormularioPuesto'),
                width: '100%',
                minimumResultsForSearch: 10,
                placeholder: 'Seleccione una estación',
                allowClear: true,
                dropdownAutoWidth: true
            });
            // (Eliminado el handler de mousedown para evitar interferencia con Select2)
            // Handler para llenar los campos relacionados a la estación
            function fillEstacionFields(selectedId) {
                const selected = sucursalesData.find(item => String(item.IdSucursal) === String(selectedId));
                if (selected) {
                    // Concatenar los datos requeridos para ubicación
                    let direccion = '';
                    if (selected.ColoniaSucursal && selected.ColoniaSucursal !== 'NULL') direccion += selected.ColoniaSucursal + ', ';
                    if (selected.Direccion && selected.Direccion !== 'NULL') direccion += selected.Direccion + ', ';
                    if (selected.Poblacion && selected.Poblacion !== 'NULL') direccion += selected.Poblacion + ', ';
                    if (selected.Estado && selected.Estado !== 'NULL') direccion += selected.Estado + ', ';
                    if (selected.Pais && selected.Pais !== 'NULL') direccion += selected.Pais;
                    direccion = direccion.replace(/, $/, ''); // Quitar coma final
                    // Concatenar CodigoPostal al final si existe y no es 'NULL'
                    if ((selected.codigoPostal || selected.CodigoPostal) && (selected.codigoPostal !== 'NULL' && selected.codigoPostal !== undefined || selected.CodigoPostal !== 'NULL' && selected.CodigoPostal !== undefined)) {
                        let cp = selected.codigoPostal !== undefined ? selected.codigoPostal : selected.CodigoPostal;
                        direccion += (direccion ? ', ' : '') + cp;
                    }
                    document.getElementById('ubicacion').value = direccion;
                    // RFC
                    document.getElementById('rfc').value = (selected.RFC && selected.RFC !== 'NULL') ? selected.RFC : '';
                    // N.Estación
                    document.getElementById('nestacion').value = (selected.IdSucursal && selected.IdSucursal !== 'NULL') ? selected.IdSucursal : '';
                    // N.P CRE
                    document.getElementById('npcre').value = (selected.ListaPreciosEsp && selected.ListaPreciosEsp !== 'NULL') ? selected.ListaPreciosEsp : '';
                    // Teléfono
                    document.getElementById('telefono').value = (selected.Telefonos && selected.Telefonos !== 'NULL') ? selected.Telefonos : '';
                    // Jefe de estación
                    document.getElementById('jefe').value = (selected.Encargado && selected.Encargado !== 'NULL') ? selected.Encargado : '';
                }
            }
            // Evento al seleccionar una estación (Select2)
            $('#estacion').on('select2:select', function (e) {
                fillEstacionFields($(this).val());
            });
            // También llenar campos al cambiar el valor (para .trigger('change'))
            $('#estacion').on('change', function (e) {
                fillEstacionFields($(this).val());
            });
            // Limpiar input si se deselecciona
            $('#estacion').on('select2:clear', function () {
                document.getElementById('ubicacion').value = '';
            });
        }
    });
</script>

<!-- FontAwesome CDN for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />