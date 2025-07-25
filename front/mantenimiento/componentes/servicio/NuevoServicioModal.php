<?php

class NuevoServicioModal {
    public static function render() {
        ob_start();
        ?>
        <div class="modal fade" id="nuevoServicioModal" tabindex="-1" aria-labelledby="nuevoServicioModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoServicioModalLabel">Nuevo Servicio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="nuevoServicioForm">
                            <!-- Sección: Información del Servicio -->
                            <div class="p-3 mb-4 border rounded bg-light">
                                <h6 class="mb-3">
                                    <i class="fas fa-server me-2"></i>
                                    Información del Servicio
                                </h6>

                                <div class="row g-3 text-dark">
                                    <!-- Fecha común -->
                                    <div class="col-md-12">
                                        <label for="fecha_servicio" class="form-label">Fecha del servicio</label>
                                        <input type="date" class="form-control" id="fecha_servicio" name="fecha_servicio" required>
                                    </div>

                                    <!-- Hora de inicio y final -->
                                    <div class="col-md-6">
                                        <label for="hora_inicio" class="form-label">Hora de inicio</label>
                                        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="hora_final" class="form-label">Hora final</label>
                                        <input type="time" class="form-control" id="hora_final" name="hora_final" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="servidor_site" class="form-label">Servidor / Site</label>
                                        <input type="text" class="form-control" id="servidor_site" name="servidor_site" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="serie_id" class="form-label">Serie ID</label>
                                        <input type="text" class="form-control" id="serie_id" name="serie_id" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="estatus" class="form-label">Estatus</label>
                                        <input type="text" class="form-control" id="estatus" name="estatus" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="afectacion" class="form-label">Afectación</label>
                                        <select class="form-select" id="afectacion" name="afectacion" required>
                                            <option value="">Seleccione una afectación</option>
                                            <option value="SIN AFECTACION AL MOMENTO">Sin afectación al momento</option>
                                            <option value="SIN ACCESO AL PORTAL DE TICKET GLPI">Sin acceso al portal de ticket GLPI</option>
                                            <option value="SIN RED LOCAL E INTERNET EN EL CORPORATIVO">Sin red local e internet en el corporativo</option>
                                            <option value="SIN ACCESO AL PORTAL DE CALIDAD SGC">Sin acceso al portal de calidad SGC</option>
                                            <option value="SIN INTERNET EN EL DEPARTAMENTO JURIDICO">Sin internet en el departamento jurídico</option>
                                            <option value="SIN INTERNET EN EL DEPARTAMENTO DE OPERACIONES">Sin internet en el departamento de operaciones</option>
                                            <option value="SIN ACCESO AL SERVICIO DE CARPETAS COMPARTIDAS">Sin acceso al servicio de carpetas compartidas</option>
                                            <option value="AFECTACION GENERAL ESTACIONES Y CORPORATIVO">Afectación general estaciones y corporativo</option>
                                            <option value="SIN AFECTACION A LA OPERACION">Sin afectación a la operación</option>
                                            <option value="AFECTACION MINIMA Y SOLO AL DEPARTAMENTO">Afectación mínima y solo al departamento</option>
                                            <option value="AFECTACION MINIMA SIN ACCESO A LAS CARPETAS COMPARTIDAS">Afectación mínima sin acceso a las carpetas compartidas</option>
                                            <option value="NINGUNA">Ninguna</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="serie_folio_hoja_servicio" class="form-label">Serie/Folio hoja de servicio</label>
                                        <input type="text" class="form-control" id="serie_folio_hoja_servicio" name="serie_folio_hoja_servicio" required>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-guardar-servicio" id="btnGuardarServicio">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public static function getStyles() {
        return '
        <style>
            .btn-cancelar {
                background-color: #fff !important;
                border-color: grey;
                color: grey !important;
                transition: all 0.3s ease;
            }

            .btn-cancelar:hover {
                background-color: #fff !important;
                color: black !important;
                border-color: black !important;
            }

            .btn-guardar-servicio {
                background-color: #2563eb !important;
                border-color: #2563eb !important;
                color: white !important;
                transition: all 0.3s ease;
            }

            .btn-guardar-servicio:hover {
                background-color: #1d4ed8 !important;
                border-color: #1d4ed8 !important;
            }

            .modal .modal-body label {
                color: #000000 !important;
            }

            .modal .modal-body input,
            .modal .modal-body select {
                color: #000000 !important;
            }

            .modal .modal-header .modal-title {
                color: #000000;
            }

            .modal .modal-body h6 {
                color: #000000;
            }

            .modal .modal-body input::placeholder,
            .modal .modal-body select::placeholder {
                color: #6c757d !important;
            }
        </style>';
    }

    public static function getScript() {
        return '
        <script src="/glpi/js/nuevo_servicio_modal.js"></script>';
    }
}
