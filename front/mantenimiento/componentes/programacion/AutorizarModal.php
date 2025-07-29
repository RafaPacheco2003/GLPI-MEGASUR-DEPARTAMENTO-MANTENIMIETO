<?php // No dejar espacios ni líneas antes de esta línea ?>
<div class="modal fade" id="modalAutorizar" tabindex="-1" aria-labelledby="modalAutorizarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalAutorizarLabel">Marcar como autorizado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="mb-2">
              <label for="inputAutorizo" class="form-label" style="font-weight:600; color:#2563eb; letter-spacing:0.5px;">Autorizador</label>
              <input type="text" class="form-control" id="autorizador" readonly
                style="background:#f4f8ff; border:1.5px solid #2563eb; color:#222; font-weight:500; border-radius:7px; box-shadow:none; padding-left:14px; letter-spacing:0.2px;"
                placeholder="Nombre del responsable"
                value="<?php echo htmlspecialchars(isset($_SESSION['glpiname']) ? $_SESSION['glpiname'] : ''); ?>">
            </div>
            <?php
              // Guardar el ID del usuario logueado en un input oculto
              $id_usuario = isset($_SESSION['glpiID']) ? $_SESSION['glpiID'] : '';
            ?>
            <input type="hidden" id="id_usuario_logueado" value="<?php echo htmlspecialchars($id_usuario); ?>">
          </div>

          <p class="mt-2">Necesitamos tu firma para autorizar.</p>

          <div class="signature-container" style="width:100%; background:#f4f8ff; border-radius:8px; overflow:hidden; border:2px dashed #2563eb; margin-bottom:10px;">
            <div id="autorizacionSignaturePreview" class="signature-preview"
              style="width:100%; height:120px; border:none; border-radius:8px; display:flex; align-items:center; justify-content:center; background:transparent; cursor:pointer;">
              <span class="placeholder-text" style="color:#2563eb; font-weight:500; font-size:1.05rem; letter-spacing:0.2px;">Haz clic aquí para firmar</span>
              <img id="autorizacionSignatureImg" src="" alt="Firma"
                style="max-width:100%; max-height:100%; display:none; object-fit:contain;" />
            </div>
          </div>

          <!-- Modal para dibujar la firma -->
          <div class="modal fade" id="autorizacionSignatureModal" tabindex="-1" aria-labelledby="autorizacionSignatureModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-sm-down modal-xl" style="max-width:100vw; margin:0;">
              <div class="modal-content" style="height:100vh;">
                <div class="modal-header">
                  <h5 class="modal-title" id="autorizacionSignatureModalLabel">Dibujar firma</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body"
                  style="height:calc(100vh - 120px); display:flex; flex-direction:column; justify-content:center; align-items:center;">
                  <div class="signature-pad-container signature-pad-fullscreen"
                    style="width:100%; max-width:1200px; height:100%; flex:1; display:flex; align-items:center; justify-content:center;">
                    <canvas id="autorizacionSignaturePad"
                      style="width:100%; height:100%; min-height:300px; border-radius:12px;"></canvas>
                  </div>
                  <div class="signature-controls mt-3"
                    style="width:100%; max-width:1200px; display:flex; justify-content:flex-end;">
                    <button type="button" class="btn btn-outline-secondary me-2" id="clearAutorizacionSignature"
                      style="color: #2563eb; border-color: #2563eb; background: #fff;"
                      onmouseover="this.style.background='#f0f5ff'; this.style.color='#1d4ed8'; this.style.borderColor='#1d4ed8';"
                      onmouseout="this.style.background='#fff'; this.style.color='#2563eb'; this.style.borderColor='#2563eb';">
                      <i class="fas fa-eraser"></i> Limpiar
                    </button>
                    <button type="button" class="btn" id="saveAutorizacionSignature"
                      style="background-color: #2563eb; color: #fff; border: 1px solid #2563eb;"
                      onmouseover="this.style.background='#1d4ed8'; this.style.borderColor='#1d4ed8';"
                      onmouseout="this.style.background='#2563eb'; this.style.borderColor='#2563eb';">
                      <i class="fas fa-save"></i> Guardar firma
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

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

            // Inicializar automáticamente solo si el modal está visible en el DOM
            function tryInitFirmaModal() {
              const modal = document.getElementById('autorizacionSignatureModal');
              if (modal && modal.offsetParent !== null) {
                console.log('[Firma] Inicializando modal de firma (visible en DOM)');
                initAutorizacionSignaturePad();
              } else {
                setTimeout(tryInitFirmaModal, 300);
              }
            }
            tryInitFirmaModal();
          </script>
        </div>
      </div>

      <div class="modal-footer" style="padding: 1rem 1.5rem;">
        <button type="button" class="btn" id="btnGuardarAutorizar"
          style="background-color: #2563eb; color: #fff; border: 1px solid #2563eb; padding: 0.5rem 1.25rem; font-weight: 500; border-radius: 6px;"
          onmouseover="this.style.background='#1d4ed8'; this.style.borderColor='#1d4ed8';"
          onmouseout="this.style.background='#2563eb'; this.style.borderColor='#2563eb';">
          Guardar
        </button>
        <script>
        document.getElementById('btnGuardarAutorizar').addEventListener('click', async function() {
          let idProgramacion = window.idProgramacionSeleccionada || null;
          if (!idProgramacion) {
            const params = new URLSearchParams(window.location.search);
            idProgramacion = params.get('id');
          }
          if (!idProgramacion) {
            alert('No se encontró el ID de la programación a autorizar.');
            return;
          }
          let idAutorizador = document.getElementById('id_usuario_logueado').value;
          let firmaAutorizador = window.autorizacionSignatureFileName || '';
          if (!firmaAutorizador) {
            alert('Debes firmar antes de guardar.');
            return;
          }
          try {
            const response = await fetch('/glpi/ajax/mantenimiento/mark_autorizado.php', {
              method: 'PUT',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({
                id: idProgramacion,
                id_autorizo: idAutorizador,
                firma_autorizo: firmaAutorizador
              })
            });
            const result = await response.json();
            if (result.success) {
              window.location.reload();
            } else {
              alert('Error: ' + (result.message || 'No se pudo marcar como autorizado.'));
            }
          } catch (e) {
            alert('Error de conexión: ' + e.message);
          }
        });
        </script>
      </div>

    </div>
  </div>
</div>
<?php // No dejar espacios ni líneas después de esta línea ?>