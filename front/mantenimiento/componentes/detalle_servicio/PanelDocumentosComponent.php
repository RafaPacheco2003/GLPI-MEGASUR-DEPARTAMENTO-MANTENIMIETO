<?php
// Componente PanelDocumentosComponent
function renderPanelDocumentos($hojasServicioData) {
    ?>
    <div id="overlayDocs"></div>
    <div id="panelDocs">
        <button id="cerrarDocs" type="button" style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:1.5rem;z-index:1060;" aria-label="Cerrar panel">
            <i class="fas fa-times"></i>
        </button>
        <h4 class="mb-3">Documentos</h4>
        <div class="list-group">
            <?php if (!empty($hojasServicioData)): ?>
                <?php foreach ($hojasServicioData as $hoja): ?>
                    <div class="list-group-item d-flex align-items-center justify-content-between" style="gap:0.5rem;">
                        <div class="d-flex align-items-center flex-grow-1" style="gap:0.75rem;">
                            <i class="fas fa-file-excel fa-lg text-success"></i>
                            <div>
                                <div class="fw-semibold">
                                    Hoja de Servicio - <?php echo htmlspecialchars($hoja['tipo_servicio']); ?>
                                </div>
                                <small class="text-muted">Archivo Excel (.xlsx)</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center position-relative" style="gap:0.5rem;">
                            <a href="/glpi/ajax/mantenimiento/hoja_servicio_xlsx.php?id=<?php echo urlencode($hoja['id']); ?>" target="_blank" class="badge bg-light text-success" title="Descargar"><i class="fas fa-download"></i></a>
                            <button type="button" class="btn btn-link p-0 btn-opciones-doc" style="color:#6b7280;" title="Opciones">
                                <i class="fas fa-ellipsis-v fa-lg"></i>
                            </button>
                            <div class="dropdown-menu-doc shadow" style="display:none; position:absolute; top:100%; right:0; min-width:120px; background:#fff; border-radius:0.5rem; border:1px solid #e5e7eb; z-index:999;">
                                <button class="dropdown-item-doc w-100 text-start px-3 py-2" type="button" id="editarHojaServicio_<?php echo $hoja['id']; ?>">Editar</button>
                                <button class="dropdown-item-doc w-100 text-start px-3 py-2" type="button">Opción 2</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item">No hay hoja de servicio generada.</div>
            <?php endif; ?>
        </div>
    </div>
    <style>
        .dropdown-menu-doc {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .dropdown-item-doc {
            background: none;
            border: none;
            width: 100%;
            font-size: 1rem;
            color: #374151;
            cursor: pointer;
            transition: background 0.2s;
        }
        .dropdown-item-doc:hover {
            background: #f3f4f6;
        }
    </style>
    <script>
        document.getElementById('btnDocumentos').addEventListener('click', () => {
            document.getElementById('panelDocs').classList.add('activo');
            document.getElementById('overlayDocs').classList.add('activo');
        });
        document.getElementById('cerrarDocs').addEventListener('click', cerrarPanel);
        document.getElementById('overlayDocs').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarPanel();
            }
        });
        function cerrarPanel() {
            document.getElementById('panelDocs').classList.remove('activo');
            document.getElementById('overlayDocs').classList.remove('activo');
        }

        // Menú de opciones de cada card
        document.querySelectorAll('.btn-opciones-doc').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                // Cerrar otros menús
                document.querySelectorAll('.dropdown-menu-doc').forEach(function(menu) {
                    menu.style.display = 'none';
                });
                // Abrir el menú de este botón
                var menu = btn.nextElementSibling;
                if (menu) {
                    menu.style.display = 'block';
                }
            });
        });
        // Cerrar menú al hacer click fuera
        document.addEventListener('click', function(e) {
            document.querySelectorAll('.dropdown-menu-doc').forEach(function(menu) {
                menu.style.display = 'none';
            });
        });
    </script>
    <?php
}
