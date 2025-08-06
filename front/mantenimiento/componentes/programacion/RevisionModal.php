<div class="modal fade" id="modalRevisado" tabindex="-1" aria-labelledby="modalRevisadoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalRevisadoLabel">Marcar como revisado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="mb-2">
              <label for="inputReviso" class="form-label" style="font-weight:600; color:#2563eb; letter-spacing:0.5px;">Revisor</label>
              <input type="text" class="form-control" id="elaborador" readonly
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

          <p class="mt-2">Necesitamos tu firma para marcar como revisado.</p>

          <div class="signature-container" style="width:100%; background:#f4f8ff; border-radius:8px; overflow:hidden; border:2px dashed #2563eb; margin-bottom:10px;">
            <div id="revisionSignaturePreview" class="signature-preview"
              style="width:100%; height:120px; border:none; border-radius:8px; display:flex; align-items:center; justify-content:center; background:transparent; cursor:pointer;">
              <span class="placeholder-text" style="color:#2563eb; font-weight:500; font-size:1.05rem; letter-spacing:0.2px;">Haz clic aquí para firmar</span>
              <img id="revisionSignatureImg" src="" alt="Firma"
                style="max-width:100%; max-height:100%; display:none; object-fit:contain;" />
            </div>
          </div>

          <!-- Modal para dibujar la firma con el mismo estilo que ProgramacionModal.php -->
          <style>
            #revisionSignatureModal .modal-dialog {
              max-width: 100vw !important;
              margin: 0;
              width: 100vw;
            }
            #revisionSignatureModal .modal-content {
              height: 100vh !important;
              width: 100vw;
              border-radius: 18px;
              box-shadow: 0 8px 32px rgba(37,99,235,0.10);
              background: linear-gradient(135deg,#f8faff 60%,#e0e7ff 100%);
            }
            #revisionSignatureModal .modal-header {
              border-radius: 18px 18px 0 0;
              border-bottom: 1.5px solid #e0e7ff;
              background: rgba(37,99,235,0.07);
            }
            #revisionSignatureModal .modal-title {
              font-weight: 700;
              color: #2563eb;
              display: flex;
              align-items: center;
              gap: 8px;
            }
            #revisionSignatureModal .modal-title span {
              display: inline-flex;
              align-items: center;
              justify-content: center;
              background: #e0e7ff;
              border-radius: 50%;
              width: 38px;
              height: 38px;
              margin-right: 8px;
            }
            #revisionSignatureModal .modal-body {
              height: calc(100vh - 120px) !important;
              flex: 1;
              width: 100vw;
              padding: 0;
              display: flex;
              flex-direction: column;
              justify-content: center;
              align-items: center;
            }
            #revisionSignatureModal .signature-pad-container.signature-pad-fullscreen {
              width: 96vw;
              max-width: 700px;
              height: 100%;
              min-height: 340px;
              flex: 1;
              display: flex;
              align-items: center;
              justify-content: center;
              margin: 0;
              border-radius: 18px;
              background: #fff;
              border: 2.5px solid #2563eb;
              box-shadow: 0 4px 24px rgba(37,99,235,0.08);
            }
            #revisionSignatureModal .signature-pad-container.signature-pad-fullscreen canvas {
              width: 100% !important;
              height: 60vh !important;
              min-height: 220px;
              border-radius: 14px;
              border: none;
              background: #f8fafc;
            }
            #revisionSignatureModal .signature-controls {
              width: 96vw;
              max-width: 700px;
              display: flex;
              justify-content: flex-end;
              align-items: center;
              padding: 0 0.5vw;
              gap: 12px;
            }
            #revisionSignatureModal .signature-controls button {
              font-size: 1rem;
              padding: 8px 24px;
              border-radius: 8px;
              font-weight: 600;
              box-shadow: 0 2px 8px rgba(37,99,235,0.08);
              transition: all 0.18s;
            }
            #revisionSignatureModal .signature-controls .btn-outline-secondary {
              background: #fff;
              color: #2563eb;
              border: 2px solid #2563eb;
            }
            #revisionSignatureModal .signature-controls .btn-outline-secondary:hover {
              background: #e0e7ff;
              color: #1d4ed8;
              border-color: #1d4ed8;
            }
            #revisionSignatureModal .signature-controls .btn-primary, #revisionSignatureModal .signature-controls .btn {
              background: linear-gradient(90deg,#2563eb 60%,#1d4ed8 100%);
              color: #fff;
              border: none;
            }
            #revisionSignatureModal .signature-controls .btn-primary:hover, #revisionSignatureModal .signature-controls .btn:hover {
              background: #1d4ed8;
              box-shadow: 0 4px 16px rgba(37,99,235,0.13);
            }
            @media (max-width: 991.98px) {
              #revisionSignatureModal .signature-pad-container.signature-pad-fullscreen {
                max-width: 98vw;
                min-height: 180px;
                border-radius: 12px;
              }
              #revisionSignatureModal .signature-pad-container.signature-pad-fullscreen canvas {
                height: 32vh !important;
                min-height: 120px;
                border-radius: 10px;
              }
              #revisionSignatureModal .signature-controls {
                max-width: 98vw;
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                padding: 0 2vw;
              }
            }
            @media (max-width: 600px) {
              #revisionSignatureModal .modal-content {
                border-radius: 0;
              }
              #revisionSignatureModal .modal-header {
                border-radius: 0;
                padding: 0.7rem 0.7rem 0.7rem 1rem;
              }
              #revisionSignatureModal .modal-title span {
                width: 30px;
                height: 30px;
                margin-right: 5px;
              }
              #revisionSignatureModal .signature-pad-container.signature-pad-fullscreen {
                width: 99vw;
                max-width: 99vw;
                min-height: 100px;
                border-radius: 6px;
              }
              #revisionSignatureModal .signature-pad-container.signature-pad-fullscreen canvas {
                height: 22vh !important;
                min-height: 70px;
                border-radius: 6px;
              }
              #revisionSignatureModal .signature-controls {
                width: 99vw;
                max-width: 99vw;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
                padding: 0 2vw;
              }
              #revisionSignatureModal .signature-controls button {
                font-size: 0.98rem;
                padding: 8px 0;
              }
            }
          </style>
          <div class="modal fade" id="revisionSignatureModal" tabindex="-1" aria-labelledby="revisionSignatureModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title d-flex align-items-center gap-2" id="revisionSignatureModalLabel">
                    <span><i class="fas fa-signature" style="font-size:1.3rem; color:#2563eb;"></i></span>
                    Dibujar firma
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body d-flex flex-column justify-content-center align-items-center pb-0">
                  <div class="signature-pad-container signature-pad-fullscreen shadow-lg">
                    <canvas id="revisionSignaturePad"></canvas>
                  </div>
                  <div class="signature-controls mt-4 d-flex gap-3">
                    <button type="button" class="btn btn-outline-secondary me-2" id="clearRevisionSignature">
                      <i class="fas fa-eraser" style="font-size:1.1rem; margin-right:6px;"></i> Limpiar
                    </button>
                    <button type="button" class="btn" id="saveRevisionSignature">
                      <i class="fas fa-save" style="font-size:1.1rem; margin-right:6px;"></i> Guardar firma
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
          <script>
            let revisionSignaturePad, revisionSignatureModal;
            let revisionSignatureDataUrl = null;

            document.addEventListener('DOMContentLoaded', function () {
              // Modal de firma
              revisionSignatureModal = new bootstrap.Modal(document.getElementById('revisionSignatureModal'));
              const preview = document.getElementById('revisionSignaturePreview');
              const img = document.getElementById('revisionSignatureImg');
              const placeholder = preview.querySelector('.placeholder-text');

              // Al hacer clic en la previsualización, abrir el modal fullscreen
              preview.addEventListener('click', function () {
                revisionSignatureModal.show();
                setTimeout(() => {
                  const canvas = document.getElementById('revisionSignaturePad');
                  resizeRevisionSignatureCanvas(canvas);
                  revisionSignaturePad = new SignaturePad(canvas, { penColor: 'rgb(0,0,0)' });
                  if (revisionSignatureDataUrl) {
                    const image = new window.Image();
                    image.onload = function () {
                      const ctx = canvas.getContext('2d');
                      ctx.clearRect(0, 0, canvas.width, canvas.height);
                      ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
                    };
                    image.src = revisionSignatureDataUrl;
                  } else {
                    revisionSignaturePad.clear();
                  }
                }, 200);
              });

              // Limpiar firma
              document.getElementById('clearRevisionSignature').addEventListener('click', function () {
                if (revisionSignaturePad) revisionSignaturePad.clear();
              });

              // Guardar firma
              document.getElementById('saveRevisionSignature').addEventListener('click', function () {
                if (revisionSignaturePad && !revisionSignaturePad.isEmpty()) {
                  revisionSignatureDataUrl = revisionSignaturePad.toDataURL('image/png');
                  // Enviar la firma al backend para guardarla como imagen
                  fetch('/glpi/ajax/mantenimiento/upload_signature.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ image: revisionSignatureDataUrl })
                  })
                    .then(response => response.json())
                    .then(result => {
                      if (result.success && result.fileName) {
                        img.src = revisionSignatureDataUrl;
                        img.style.display = 'block';
                        placeholder.style.display = 'none';
                        revisionSignatureModal.hide();
                        window.revisionSignatureFileName = result.fileName;
                      } else {
                        alert('Error al guardar la firma: ' + (result.message || 'Error desconocido'));
                      }
                    })
                    .catch(e => {
                      alert('Error de conexión al guardar la firma: ' + e.message);
                    });
                } else {
                  alert('Por favor, realiza una firma antes de guardar.');
                }
              });

              // Redimensionar canvas al mostrar el modal fullscreen
              function resizeRevisionSignatureCanvas(canvas) {
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
              }

              window.addEventListener('resize', function () {
                const canvas = document.getElementById('revisionSignaturePad');
                if (revisionSignatureModal && canvas && revisionSignatureModal._isShown) {
                  resizeRevisionSignatureCanvas(canvas);
                }
              });
            });
          </script>
        </div>
      </div>

      <div class="modal-footer" style="padding: 1rem 1.5rem;">
        <button type="button" class="btn" id="btnGuardarRevisado"
          style="background-color: #2563eb; color: #fff; border: 1px solid #2563eb; padding: 0.5rem 1.25rem; font-weight: 500; border-radius: 6px;"
          onmouseover="this.style.background='#1d4ed8'; this.style.borderColor='#1d4ed8';"
          onmouseout="this.style.background='#2563eb'; this.style.borderColor='#2563eb';">
          Guardar
        </button>
        <script>
        // Ejemplo de uso: al dar click en Guardar, se manda el id de la programación y el id del usuario logueado
        document.getElementById('btnGuardarRevisado').addEventListener('click', async function() {
          let idProgramacion = window.idProgramacionSeleccionada || null;
          if (!idProgramacion) {
            const params = new URLSearchParams(window.location.search);
            idProgramacion = params.get('id');
          }
          if (!idProgramacion) {
            alert('No se encontró el ID de la programación a revisar.');
            return;
          }
          let idRevisor = document.getElementById('id_usuario_logueado').value;
          let firmaRevisor = window.revisionSignatureFileName || '';
          if (!firmaRevisor) {
            alert('Debes firmar antes de guardar.');
            return;
          }
          try {
            const response = await fetch('/glpi/ajax/mantenimiento/mark_revisado.php', {
              method: 'PUT',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({
                id: idProgramacion,
                id_reviso: idRevisor,
                firma_reviso: firmaRevisor
              })
            });
            const result = await response.json();
            if (result.success) {
              window.location.reload();
            } else {
              alert('Error: ' + (result.message || 'No se pudo marcar como revisado.'));
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
