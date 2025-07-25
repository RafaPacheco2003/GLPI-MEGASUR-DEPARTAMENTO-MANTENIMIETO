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
            <div class="mb-3">
              <label for="inputReviso" class="form-label" style="font-weight:500; color:#000;">ID Revisor</label>
              <input type="number" id="inputReviso" class="form-control" placeholder="ID numérico de quien revisa..." min="1">
            </div>
            <p>Necesitamos tu firma para marcar como revisado.</p>
            <div class="signature-container" style="width:100%; background:#fff; border-radius:8px; overflow:hidden;">
              <div id="revisionSignaturePreview" class="signature-preview"
                style="width:100%; height:120px; border:1px solid #ccc; border-radius:8px; display:flex; align-items:center; justify-content:center; background:#f8f9fa; cursor:pointer; margin-bottom:10px;">
                <span class="placeholder-text" style="color:#888;">Haz clic aquí para firmar</span>
                <img id="revisionSignatureImg" src="" alt="Firma"
                  style="max-width:100%; max-height:100%; display:none; object-fit:contain;" />
              </div>
            </div>

            <!-- Modal para dibujar la firma -->
            <div class="modal fade" id="revisionSignatureModal" tabindex="-1"
              aria-labelledby="revisionSignatureModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-fullscreen-sm-down modal-xl" style="max-width:100vw; margin:0;">
                <div class="modal-content" style="height:100vh;">
                  <div class="modal-header">
                    <h5 class="modal-title" id="revisionSignatureModalLabel">Dibujar firma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                  </div>
                  <div class="modal-body"
                    style="height:calc(100vh - 120px); display:flex; flex-direction:column; justify-content:center; align-items:center;">
                    <div class="signature-pad-container signature-pad-fullscreen"
                      style="width:100%; max-width:1200px; height:100%; flex:1; display:flex; align-items:center; justify-content:center;">
                      <canvas id="revisionSignaturePad"
                        style="width:100%; height:100%; min-height:300px; border-radius:12px;"></canvas>
                    </div>
                    <div class="signature-controls mt-3"
                      style="width:100%; max-width:1200px; display:flex; justify-content:flex-end;">
                      <button type="button" class="btn btn-outline-secondary me-2" id="clearRevisionSignature"
                        style="color: #2563eb; border-color: #2563eb; background: #fff;"
                        onmouseover="this.style.background='#f0f5ff'; this.style.color='#1d4ed8'; this.style.borderColor='#1d4ed8';"
                        onmouseout="this.style.background='#fff'; this.style.color='#2563eb'; this.style.borderColor='#2563eb';">
                        <i class="fas fa-eraser"></i> Limpiar
                      </button>
                      <button type="button" class="btn" id="saveRevisionSignature"
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
                        // Puedes guardar el nombre de la imagen en una variable global si lo necesitas para el siguiente paso
                        window.revisionSignatureFileName = result.fileName;
                        alert('Firma guardada correctamente.');
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
                  // Responsive fullscreen canvas
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" id="btnGuardarRevisado"
          style="background-color: #2563eb; color: #fff; border: 1px solid #2563eb;"
          onmouseover="this.style.background='#1d4ed8'; this.style.borderColor='#1d4ed8';"
          onmouseout="this.style.background='#2563eb'; this.style.borderColor='#2563eb';">Guardar</button>
      </div>
    </div>
  </div>
</div>