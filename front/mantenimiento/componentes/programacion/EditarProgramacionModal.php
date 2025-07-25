<?php

class EditarProgramacionModal {
    public static function render() {
        ob_start();
        ?>
        <?php
        // Obtener los valores del enum para el select
        include_once '../../../inc/mantenimiento/ProgramacionManager.php';
        $programacionManager = new ProgramacionManager();
        $nombresProgramacion = $programacionManager->getNombreProgramacionEnum();
        ?>
        <div class="modal fade" id="editarProgramacionModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="editarProgramacionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarProgramacionModalLabel">
                            <i class="fas fa-edit me-2"></i>
                            Editar Programación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editarProgramacionForm">
                            <!-- Sección de Información General -->
                            <div class="p-3 mb-4 border rounded bg-light">
                                <h6 class="mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Información General
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="edit_nombre_empresa" class="form-label">Nombre de la Empresa</label>
                                        <input type="text" class="form-control" id="edit_nombre_empresa" name="nombre_empresa" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit_nombre_programacion" class="form-label">Nombre de la Programación</label>
                                        <select class="form-select" id="edit_nombre_programacion" name="nombre_programacion" required>
                                            <option value="">Seleccione tipo de programación</option>
                                            <?php foreach ($nombresProgramacion as $nombre): ?>
                                                <option value="<?php echo htmlspecialchars($nombre); ?>"><?php echo htmlspecialchars($nombre); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit_fecha_emision" class="form-label">Fecha de Emisión</label>
                                        <input type="date" class="form-control" id="edit_fecha_emision" name="fecha_emision" required>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-guardar-servicio" id="btnGuardarEdicionProgramacion">Guardar Cambios</button>
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
        </style>';
    }

    public static function getScript() {
        return '
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Asegurarse de que solo haya una instancia del modal
                let editarProgramacionModal;
                const modalElement = document.getElementById("editarProgramacionModal");
                
                if (!bootstrap.Modal.getInstance(modalElement)) {
                    editarProgramacionModal = new bootstrap.Modal(modalElement);
                } else {
                    editarProgramacionModal = bootstrap.Modal.getInstance(modalElement);
                }

                // Agregar event listeners para los botones de guardar
                document.getElementById("btnGuardarEdicionProgramacion").addEventListener("click", function() {
                    window.guardarEdicionProgramacion();
                });

                // Agregar el manejador para el botón de editar programación
                document.getElementById("btnEditarProg").addEventListener("click", function() {
                    cargarDatosProgramacion();
                });

                // Asignar las funciones a window para que estén disponibles globalmente
                window.cargarDatosProgramacion = async function() {
                    try {
                        const urlParams = new URLSearchParams(window.location.search);
                        const id_programacion = urlParams.get("id");

                        // Obtener los datos actuales de la programación
                        const response = await fetch(`../../../ajax/mantenimiento/get_programacion.php?id=${id_programacion}`);
                        if (!response.ok) {
                            throw new Error("Error al obtener los datos de la programación");
                        }

                        const data = await response.json();
                        if (!data.success) {
                            throw new Error(data.message || "Error al obtener los datos");
                        }

                        // Llenar el formulario con los datos
                        document.getElementById("edit_nombre_empresa").value = data.programacion.nombre_empresa;
                        // Seleccionar el valor correcto en el select
                        const selectNombreProg = document.getElementById("edit_nombre_programacion");
                        selectNombreProg.value = data.programacion.nombre_programacion;
                        document.getElementById("edit_fecha_emision").value = data.programacion.fecha_emision.split(" ")[0];

                        // Mostrar el modal
                        editarProgramacionModal.show();
                    } catch (error) {
                        alert("Error: " + error.message);
                        console.error("Error completo:", error);
                    }
                };

                window.guardarEdicionProgramacion = async function() {
                    try {
                        const urlParams = new URLSearchParams(window.location.search);
                        const id_programacion = urlParams.get("id");

                        const formData = {
                            id: id_programacion,
                            nombre_empresa: document.getElementById("edit_nombre_empresa").value,
                            nombre_programacion: document.getElementById("edit_nombre_programacion").value,
                            fecha_emision: document.getElementById("edit_fecha_emision").value
                        };

                        // Validar campos requeridos
                        for (const [key, value] of Object.entries(formData)) {
                            if (!value) {
                                throw new Error(`El campo ${key} es requerido`);
                            }
                        }

                        // Enviar los datos al servidor
                        const response = await fetch("../../../ajax/mantenimiento/update_programacion.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify(formData)
                        });

                        const result = await response.json();
                        
                        if (result.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById("editarProgramacionModal"));
                            modal.hide();
                            window.location.reload();
                        }

                    } catch (error) {
                        console.error("Error:", error);
                    }
                };
            });
        </script>';
        
    }
}
