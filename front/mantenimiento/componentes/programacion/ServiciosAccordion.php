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


        <h6 class="section-title"><i class="fas fa-server me-2"></i> Servicios</h6>

        <button type="button" class="btn btn-sm btn-success mb-2" id="btnAgregarServicio">
            <i class="fas fa-plus"></i> Agregar servicio
        </button>

        <div class="accordion" id="serviciosAccordion"
            style="border-radius: 10px; background: #fff; box-shadow: 0 4px 16px rgba(0,0,0,0.04); padding: 8px 0;">
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const serviciosAccordion = document.getElementById('serviciosAccordion');
                const btnAgregarServicio = document.getElementById('btnAgregarServicio');
                const nombreProgramacion = document.getElementById('nombreProgramacion');
                const cardsContainer = document.getElementById('cardsProgramacion');
                let servicioCount = 0;
                let tipoProgramacionActual = nombreProgramacion?.value || '';

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

                btnAgregarServicio?.addEventListener('click', () => crearServicioAcordeon());
                crearServicioAcordeon(); // inicial

                // Función para cargar sucursales en todos los selects de estación
                async function cargarSucursalesEnSelect(idSelect) {
                    try {
                        // Consumir el endpoint PHP local
                        const response = await fetch('../config/get_sucursales.php');
                        const data = await response.json();
                        const select = document.getElementById(idSelect);
                        if (!select) return;
                        select.innerHTML = '<option value="">Seleccione una estación</option>';
                        // Guardar los datos en un Map para acceso rápido por id
                        const estacionesMap = new Map();
                        data.forEach(item => {
                            estacionesMap.set(String(item.IdSucursal), item);
                            const option = document.createElement('option');
                            option.value = item.IdSucursal; // El id que se guarda en la BD
                            option.textContent = item.NombreSucursal; // El nombre que ve el usuario
                            select.appendChild(option);
                        });
                        // Evento para imprimir en consola los datos de la estación seleccionada
                        select.addEventListener('change', function() {
                            const id = select.value;
                            if (id && estacionesMap.has(id)) {
                                console.log('Estación seleccionada:', estacionesMap.get(id));
                            }
                        });

                        // Guardar el id_estacion en el input oculto para el formulario
                        // Si no existe, lo crea
                        let hiddenInput = select.parentElement.querySelector('input[type="hidden"][name="id_estacion[]"]');
                        if (!hiddenInput) {
                            hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'id_estacion[]';
                            select.parentElement.appendChild(hiddenInput);
                        }
                        // Actualizar el valor cada vez que cambie el select
                        select.addEventListener('change', function() {
                            hiddenInput.value = select.value;
                        });
                        // Inicializar el valor al cargar
                        hiddenInput.value = select.value;

                        // --- Envío correcto del id_estacion al backend ---
                        // Si tienes un botón para guardar el servicio, aquí se asegura que se envíe el id correcto
                        // Ejemplo: document.getElementById('btnGuardarServicio').onclick = function() { ... }
                        // Si usas AJAX/fetch, agrega esto en el JS de envío:
                        window.getIdEstacionSeleccionada = function() {
                            return hiddenInput.value;
                        };
                    } catch (err) {
                        // Si hay error, mostrar opción de error
                        const select = document.getElementById(idSelect);
                        if (select) select.innerHTML = '<option value="">Error al cargar estaciones</option>';
                    }
                }

                // Observer para cargar sucursales en cada select de estación cuando se agregue
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

            // --- Envío de servicio al backend ---
            // Ejemplo de función para guardar el servicio
            const btnGuardarServicio = document.getElementById('btnGuardarServicio');
            if (btnGuardarServicio) {
                btnGuardarServicio.addEventListener('click', async function() {
                    // Recolecta los datos del formulario
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
                    // id_estacion (IdSucursal)
                    const id_estacion = window.getIdEstacionSeleccionada();

                    // Formatear fechas para MySQL
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
                    // Monitoreo: mostrar en consola el id_estacion y el objeto formData
                    console.log('Valor id_estacion a enviar:', formData.id_estacion);
                    console.log('Objeto formData a enviar:', formData);
                    // --- ejemplo de envío ---
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




        <style>
            #serviciosAccordion {
                border-radius: 8px;
                background: #fff;
                box-shadow: none !important;
                padding: 4px 0;
            }

            #serviciosAccordion .accordion-item {
                border-radius: 8px;
                border: 1px solid #e3e8ee;
                margin-bottom: 8px;
                background: #fff;
                font-size: 0.92rem;
            }

            #serviciosAccordion .accordion-header {
                background: #fff;
                padding: 0.5rem 0.8rem;
                border-bottom: 1px solid #e3e8ee;
            }

            #serviciosAccordion .accordion-button {
                font-weight: 500;
                color: #222;
                background: transparent;
                border: none;
                box-shadow: none !important;
                font-size: 0.98rem;
                padding: 0.3rem 0.5rem;
            }

            #serviciosAccordion .accordion-body {
                background: #fff;
                border-radius: 0 0 8px 8px;
                padding: 0.8rem;
            }

            #serviciosAccordion label.form-label,
            #serviciosAccordion input.form-control {
                font-size: 0.92rem;
                color: #222;
            }

            #serviciosAccordion input.form-control {
                border-radius: 6px;
                border: 1px solid #d1d1d1;
                height: 32px;
                padding: 4px 8px;
                background: #fff;
            }

            .btnQuitarServicio,
            #btnAgregarServicio {
                border-radius: 6px;
                font-weight: 500;
                font-size: 0.92rem;
                padding: 2px 10px;
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
                font-size: 0.95rem;
            }
        </style>
        <?php
        return ob_get_clean();
    }
}
