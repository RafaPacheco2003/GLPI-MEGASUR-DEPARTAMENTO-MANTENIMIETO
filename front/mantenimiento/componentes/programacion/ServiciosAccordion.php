<?php
/**
 * Componente para el acordeón de servicios en la programación
 */
class ServiciosAccordion
{
    /**
     * Renderiza el acordeón de servicios y el botón para agregar
     * @return string HTML del acordeón y botón
     */
    public static function render()
    {
        ob_start();
        ?>
        <!-- Servicios Accordion Header -->
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="section-title mb-0">
                <i class="fas fa-server me-2"></i> Servicios
            </h6>
            <button type="button" class="btn btn-sm btn-success" id="btnAgregarServicio">
                <i class="fas fa-plus"></i> Agregar servicio
            </button>
        </div>

        <!-- Accordion Container -->
        <div class="accordion" id="serviciosAccordion" style="border-radius: 10px; background: transparent; box-shadow: none; padding: 0;"></div>

        <!-- JavaScript: Servicios Accordion Logic -->
        <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Element references
            const serviciosAccordion = document.getElementById('serviciosAccordion');
            const btnAgregarServicio = document.getElementById('btnAgregarServicio');
            const nombreProgramacion = document.getElementById('nombreProgramacion');
            const cardsContainer = document.getElementById('cardsProgramacion');
            let servicioCount = 0;
            let tipoProgramacionActual = nombreProgramacion?.value || '';

            // Card selection event: reset accordion on program type change
            if (cardsContainer && nombreProgramacion) {
                cardsContainer.querySelectorAll('.card-programacion').forEach(card => {
                    card.addEventListener('click', function () {
                        tipoProgramacionActual = this.dataset.nombre;
                        serviciosAccordion.innerHTML = '';
                        servicioCount = 0;
                        crearServicioAcordeon();
                    });
                });
            }

            // Add new service accordion item
            const crearServicioAcordeon = (nombre = '') => {
                servicioCount++;
                const id = `servicioAcordeon${servicioCount}`;
                const plantilla = tipoProgramacionActual === 'PROGRAMA DE MANTENIMIENTO PREVENTIVO DE EQUIPOS DE CÓMPUTO Y RED (UENS)'
                    ? plantillaUENS(id, nombre)
                    : plantillaDefault(id, nombre);

                const temp = document.createElement('div');
                temp.innerHTML = plantilla;
                const item = temp.firstElementChild;
                item.querySelector('.btnQuitarServicio')?.addEventListener('click', () => item.remove());
                serviciosAccordion.appendChild(item);
            };

            // Service fields for UENS type
            const generarCamposUENS = (nombre, id) => `
                <div class="col-md-3">
                    <label class="form-label">Estación</label>
                    <select class="form-select select-estacion" name="estacion[]" id="selectEstacion_${id}">
                        <option value="">Cargando estaciones...</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Fecha</label>
                    <input type="date" class="form-control" name="fecha_servicio[]">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hora Inicio</label>
                    <input type="time" class="form-control" name="hora_inicio[]">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hora Fin</label>
                    <input type="time" class="form-control" name="hora_fin[]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Serie/ID</label>
                    <input type="text" class="form-control" name="serie_id[]" placeholder="Serie/ID">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estatus</label>
                    <input type="text" class="form-control" name="estatus[]" placeholder="Estatus">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quien</label>
                    <input type="text" class="form-control" name="quien[]" placeholder="Quien realizó">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Serie Folio Hoja Servicio</label>
                    <input type="text" class="form-control" name="serie_folio_hoja[]" placeholder="Serie Folio Hoja Servicio">
                </div>
            `;

            // Service fields for default type
            const generarCamposDefault = (nombre, id) => `
                <div class="col-md-3">
                    <label class="form-label">Fecha de Servicio</label>
                    <input type="date" class="form-control" name="fecha_servicio[]">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hora Inicio</label>
                    <input type="time" class="form-control" name="hora_inicio[]">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hora Fin</label>
                    <input type="time" class="form-control" name="hora_fin[]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Servidor/Site</label>
                    <input type="text" class="form-control" name="servidor_site[]" placeholder="Servidor o Site">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Serie ID</label>
                    <input type="text" class="form-control" name="serie_id[]" placeholder="Serie ID">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Estatus</label>
                    <input type="text" class="form-control" name="estatus[]" placeholder="Estatus">
                </div>
                <div class="col-md-3">
                    <label for="afectacion" class="form-label">Afectación</label>
                    <select class="form-select" id="afectacion" name="afectacion">
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
                <div class="col-md-3">
                    <label class="form-label">Serie/Folio Hoja</label>
                    <input type="text" class="form-control" name="serie_folio_hoja[]" placeholder="Serie/Folio Hoja">
                </div>
            `;

            // Accordion item template for UENS
            const plantillaUENS = (id, nombre) => `
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="heading${id}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${id}" aria-expanded="false">
                            Servicio ${servicioCount}
                        </button>
                    </h2>
                    <div id="collapse${id}" class="accordion-collapse collapse" aria-labelledby="heading${id}" data-bs-parent="#serviciosAccordion">
                        <div class="accordion-body">
                            <div class="row g-3 mb-2">
                                ${generarCamposUENS(nombre, id)}
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <span class="btnQuitarServicio" title="Quitar servicio" style="cursor:pointer; color:#dc3545; font-size:1.2rem;">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Accordion item template for default
            const plantillaDefault = (id, nombre) => `
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="heading${id}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${id}" aria-expanded="false">
                            Servicio ${servicioCount}
                        </button>
                    </h2>
                    <div id="collapse${id}" class="accordion-collapse collapse" aria-labelledby="heading${id}" data-bs-parent="#serviciosAccordion">
                        <div class="accordion-body">
                            <div class="row g-4 mb-2">
                                ${generarCamposDefault(nombre, id)}
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <span class="btnQuitarServicio" title="Quitar servicio" style="cursor:pointer; color:#dc3545; font-size:1.2rem;">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Add service event
            btnAgregarServicio?.addEventListener('click', () => crearServicioAcordeon());
            crearServicioAcordeon(); // inicial

            // Load stations into select
            async function cargarSucursalesEnSelect(idSelect) {
                try {
                    const response = await fetch('../config/get_sucursales.php');
                    const data = await response.json();
                    console.log('Cantidad de sucursales obtenidas:', Array.isArray(data) ? data.length : 0);
                    const select = document.getElementById(idSelect);
                    if (!select) return;
                    select.innerHTML = '<option value="">Seleccione una estación</option>';
                    const estacionesMap = new Map();
                    data.forEach(item => {
                        estacionesMap.set(String(item.IdSucursal), item);
                        const option = document.createElement('option');
                        option.value = item.IdSucursal;
                        option.textContent = item.NombreSucursal;
                        select.appendChild(option);
                    });
                    select.addEventListener('change', function() {
                        const id = select.value;
                        if (id && estacionesMap.has(id)) {
                            console.log('Estación seleccionada:', estacionesMap.get(id));
                        }
                    });
                    let hiddenInput = select.parentElement.querySelector('input[type="hidden"][name="id_estacion[]"]');
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'id_estacion[]';
                        select.parentElement.appendChild(hiddenInput);
                    }
                    select.addEventListener('change', function() {
                        hiddenInput.value = select.value;
                    });
                    hiddenInput.value = select.value;
                    window.getIdEstacionSeleccionada = function() {
                        return hiddenInput.value;
                    };
                } catch (err) {
                    const select = document.getElementById(idSelect);
                    if (select) select.innerHTML = '<option value="">Error al cargar estaciones</option>';
                }
            }

            // Observer for dynamic select loading
            const observer = new MutationObserver(mutations => {
                mutations.forEach(mutation => {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1) {
                            const selects = node.querySelectorAll('.select-estacion');
                            selects.forEach(sel => {
                                cargarSucursalesEnSelect(sel.id);
                            });
                        }
                    });
                });
            });
            observer.observe(serviciosAccordion, { childList: true });
        });

        // Guardar servicio al backend
        const btnGuardarServicio = document.getElementById('btnGuardarServicio');
        if (btnGuardarServicio) {
            btnGuardarServicio.addEventListener('click', async function() {
                const form = document.getElementById('nuevoServicioForm');
                const id_programacion = document.getElementById('id_programacion')?.value || '';
                const fecha_servicio = form.querySelector('input[name="fecha_servicio[]"]')?.value || form.querySelector('input[name="fecha_servicio"]')?.value || '';
                const hora_inicio = form.querySelector('input[name="hora_inicio[]"]')?.value || form.querySelector('input[name="hora_inicio"]')?.value || '';
                const hora_fin = form.querySelector('input[name="hora_fin[]"]')?.value || form.querySelector('input[name="hora_fin"]')?.value || '';
                const servidor_site = form.querySelector('input[name="servidor_site[]"]')?.value || form.querySelector('input[name="servidor_site"]')?.value || '';
                const serie_id = form.querySelector('input[name="serie_id[]"]')?.value || form.querySelector('input[name="serie_id"]')?.value || '';
                const estatus = form.querySelector('input[name="estatus[]"]')?.value || form.querySelector('input[name="estatus"]')?.value || '';
                const afectacion = form.querySelector('select[name="afectacion"]')?.value || '';
                const serie_folio_hoja_servicio = form.querySelector('input[name="serie_folio_hoja[]"]')?.value || form.querySelector('input[name="serie_folio_hoja_servicio"]')?.value || '';
                const quien = form.querySelector('input[name="quien[]"]')?.value || form.querySelector('input[name="quien"]')?.value || '';
                const id_estacion = window.getIdEstacionSeleccionada();
                const fecha_inicio = fecha_servicio && hora_inicio ? (fecha_servicio + ' ' + hora_inicio + ':00') : '';
                const fecha_final = fecha_servicio && hora_fin ? (fecha_servicio + ' ' + hora_fin + ':00') : '';
                const formData = {
                    fecha_inicio,
                    fecha_final,
                    servidor_site,
                    serie_id,
                    estatus,
                    afectacion,
                    serie_folio_hoja_servicio,
                    id_estacion,
                    quien,
                    id_programacion
                };
                console.log('Valor id_estacion a enviar:', formData.id_estacion);
                console.log('Objeto formData a enviar:', formData);
                try {
                    const response = await fetch('../ajax/mantenimiento/create_servicio.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert('Servicio guardado correctamente');
                    } else {
                        alert('Error: ' + result.message);
                    }
                } catch (err) {
                    alert('Error al guardar servicio');
                }
            });
        }
        </script>

        <!-- CSS: Servicios Accordion Styles -->
        <style>
            #serviciosAccordion {
                border-radius: 4px;
                background: #fff;
                box-shadow: none !important;
                padding: 2px 0;
                max-width: 100%;
            }
            #serviciosAccordion .accordion-item {
                border-radius: 4px;
                border: 1px solid #e3e8ee;
                margin-bottom: 4px;
                background: #fff;
                font-size: 0.90rem;
            }
            #serviciosAccordion .accordion-header {
                background: #fff;
                padding: 0.25rem 0.5rem;
                border-bottom: 1px solid #e3e8ee;
            }
            #serviciosAccordion .accordion-button {
                font-weight: 500;
                color: #222;
                background: transparent;
                border: none;
                box-shadow: none !important;
                font-size: 0.95rem;
                padding: 0.15rem 0.3rem;
            }
            #serviciosAccordion .accordion-body {
                background: #fff;
                border-radius: 0 0 4px 4px;
                padding: 0.4rem;
            }
            #serviciosAccordion label.form-label,
            #serviciosAccordion input.form-control {
                font-size: 0.90rem;
                color: #222;
            }
            #serviciosAccordion input.form-control {
                border-radius: 4px;
                border: 1px solid #d1d1d1;
                height: 28px;
                padding: 2px 6px;
                background: #fff;
            }
            .btnQuitarServicio,
            #btnAgregarServicio {
                border-radius: 4px;
                font-weight: 500;
                font-size: 0.90rem;
                padding: 1px 8px;
                border: 1px solid #e3e8ee;
                background: #fff;
                color: #444;
                box-shadow: none !important;
            }
            .btnQuitarServicio:hover,
            #btnAgregarServicio:hover {
                background: #ececec;
                border-color: #bbb;
                color: #222;
            }
            .btnQuitarServicio i,
            #btnAgregarServicio i {
                font-size: 0.90rem;
            }
        </style>
        <?php
        return ob_get_clean();
    }
}
