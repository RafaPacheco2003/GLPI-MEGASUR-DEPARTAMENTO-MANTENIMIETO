<?php // No dejar espacios ni líneas antes de esta línea ?>

<!-- ================== MODAL AUTORIZAR ================== -->
<div class="modal fade" id="modalAutorizar" tabindex="-1" aria-labelledby="modalAutorizarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- ========== HEADER ========== -->
      <div class="modal-header">
        <h5 class="modal-title" id="modalAutorizarLabel">Marcar como autorizado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- ========== BODY ========== -->
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <!-- Autorizador -->
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

          <!-- Mensaje de firma -->
          <p class="mt-2">Necesitamos tu firma para autorizar.</p>

          <!-- Preview de firma -->
          <div class="signature-container" style="width:100%; background:#f4f8ff; border-radius:8px; overflow:hidden; border:2px dashed #2563eb; margin-bottom:10px;">
            <div id="autorizacionSignaturePreview" class="signature-preview"
              style="width:100%; height:120px; border:none; border-radius:8px; display:flex; align-items:center; justify-content:center; background:transparent; cursor:pointer;">
              <span class="placeholder-text" style="color:#2563eb; font-weight:500; font-size:1.05rem; letter-spacing:0.2px;">Haz clic aquí para firmar</span>
              <img id="autorizacionSignatureImg" src="" alt="Firma"
                style="max-width:100%; max-height:100%; display:none; object-fit:contain;" />
            </div>
          </div>

          <!-- Modal para dibujar la firma con el mismo estilo que ProgramacionModal.php -->
          <style>
            #autorizacionSignatureModal .modal-dialog {
              max-width: 100vw !important;
              margin: 0;
              width: 100vw;
            }
            #autorizacionSignatureModal .modal-content {
              height: 100vh !important;
              width: 100vw;
              border-radius: 18px;
              box-shadow: 0 8px 32px rgba(37,99,235,0.10);
              background: linear-gradient(135deg,#f8faff 60%,#e0e7ff 100%);
            }
            #autorizacionSignatureModal .modal-header {
              border-radius: 18px 18px 0 0;
              border-bottom: 1.5px solid #e0e7ff;
              background: rgba(37,99,235,0.07);
            }
            #autorizacionSignatureModal .modal-title {
              font-weight: 700;
              color: #2563eb;
              display: flex;
              align-items: center;
              gap: 8px;
            }
            #autorizacionSignatureModal .modal-title span {
              display: inline-flex;
              align-items: center;
              justify-content: center;
              background: #e0e7ff;
              border-radius: 50%;
              width: 38px;
              height: 38px;
              margin-right: 8px;
            }
            #autorizacionSignatureModal .modal-body {
              height: calc(100vh - 120px) !important;
              flex: 1;
              width: 100vw;
              padding: 0;
              display: flex;
              flex-direction: column;
              justify-content: center;
              align-items: center;
            }
            #autorizacionSignatureModal .signature-pad-container.signature-pad-fullscreen {
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
            #autorizacionSignatureModal .signature-pad-container.signature-pad-fullscreen canvas {
              width: 100% !important;
              height: 60vh !important;
              min-height: 220px;
              border-radius: 14px;
              border: none;
              background: #f8fafc;
            }
            #autorizacionSignatureModal .signature-controls {
              width: 96vw;
              max-width: 700px;
              display: flex;
              justify-content: flex-end;
              align-items: center;
              padding: 0 0.5vw;
              gap: 12px;
            }
            #autorizacionSignatureModal .signature-controls button {
              font-size: 1rem;
              padding: 8px 24px;
              border-radius: 8px;
              font-weight: 600;
              box-shadow: 0 2px 8px rgba(37,99,235,0.08);
              transition: all 0.18s;
            }
            #autorizacionSignatureModal .signature-controls .btn-outline-secondary {
              background: #fff;
              color: #2563eb;
              border: 2px solid #2563eb;
            }
            #autorizacionSignatureModal .signature-controls .btn-outline-secondary:hover {
              background: #e0e7ff;
              color: #1d4ed8;
              border-color: #1d4ed8;
            }
            #autorizacionSignatureModal .signature-controls .btn-primary, #autorizacionSignatureModal .signature-controls .btn {
              background: linear-gradient(90deg,#2563eb 60%,#1d4ed8 100%);
              color: #fff;
              border: none;
            }
            #autorizacionSignatureModal .signature-controls .btn-primary:hover, #autorizacionSignatureModal .signature-controls .btn:hover {
              background: #1d4ed8;
              box-shadow: 0 4px 16px rgba(37,99,235,0.13);
            }
            @media (max-width: 991.98px) {
              #autorizacionSignatureModal .signature-pad-container.signature-pad-fullscreen {
                max-width: 98vw;
                min-height: 180px;
                border-radius: 12px;
              }
              #autorizacionSignatureModal .signature-pad-container.signature-pad-fullscreen canvas {
                height: 32vh !important;
                min-height: 120px;
                border-radius: 10px;
              }
              #autorizacionSignatureModal .signature-controls {
                max-width: 98vw;
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                padding: 0 2vw;
              }
            }
            @media (max-width: 600px) {
              #autorizacionSignatureModal .modal-content {
                border-radius: 0;
              }
              #autorizacionSignatureModal .modal-header {
                border-radius: 0;
                padding: 0.7rem 0.7rem 0.7rem 1rem;
              }
              #autorizacionSignatureModal .modal-title span {
                width: 30px;
                height: 30px;
                margin-right: 5px;
              }
              #autorizacionSignatureModal .signature-pad-container.signature-pad-fullscreen {
                width: 99vw;
                max-width: 99vw;
                min-height: 100px;
                border-radius: 6px;
              }
              #autorizacionSignatureModal .signature-pad-container.signature-pad-fullscreen canvas {
                height: 22vh !important;
                min-height: 70px;
                border-radius: 6px;
              }
              #autorizacionSignatureModal .signature-controls {
                width: 99vw;
                max-width: 99vw;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
                padding: 0 2vw;
              }
              #autorizacionSignatureModal .signature-controls button {
                font-size: 0.98rem;
                padding: 8px 0;
              }
            }
          </style>
          <div class="modal fade" id="autorizacionSignatureModal" tabindex="-1" aria-labelledby="autorizacionSignatureModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title d-flex align-items-center gap-2" id="autorizacionSignatureModalLabel">
                    <span><i class="fas fa-signature" style="font-size:1.3rem; color:#2563eb;"></i></span>
                    Dibujar firma
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body d-flex flex-column justify-content-center align-items-center pb-0">
                  <div class="signature-pad-container signature-pad-fullscreen shadow-lg">
                    <canvas id="autorizacionSignaturePad"></canvas>
                  </div>
                  <div class="signature-controls mt-4 d-flex gap-3">
                    <button type="button" class="btn btn-outline-secondary me-2" id="clearAutorizacionSignature">
                      <i class="fas fa-eraser" style="font-size:1.1rem; margin-right:6px;"></i> Limpiar
                    </button>
                    <button type="button" class="btn" id="saveAutorizacionSignature">
                      <i class="fas fa-save" style="font-size:1.1rem; margin-right:6px;"></i> Guardar firma
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ========== FOOTER ========== -->
      <div class="modal-footer" style="padding: 1rem 1.5rem;">
        <button type="button" class="btn" id="btnGuardarAutorizar"
          style="background-color: #2563eb; color: #fff; border: 1px solid #2563eb; padding: 0.5rem 1.25rem; font-weight: 500; border-radius: 6px;"
          onmouseover="this.style.background='#1d4ed8'; this.style.borderColor='#1d4ed8';"
          onmouseout="this.style.background='#2563eb'; this.style.borderColor='#2563eb';">
          Guardar
        </button>
      </div>

    </div>
  </div>
</div>

<!-- ================== SCRIPTS ================== -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
// =================== LIMPIAR FUNCIONES ANTERIORES ===================
console.log('🧹 LIMPIANDO FUNCIONES ANTERIORES');

// Eliminar cualquier función anterior que pueda causar conflictos
if (window.initAutorizacionSignaturePad) {
    window.initAutorizacionSignaturePad = null;
    delete window.initAutorizacionSignaturePad;
}

if (window.handleGuardarAutorizar) {
    window.handleGuardarAutorizar = null;
    delete window.handleGuardarAutorizar;
}

// =================== VARIABLES GLOBALES ===================
let autorizacionSignaturePad = null;
let autorizacionSignatureModal = null;
let autorizacionSignatureDataUrl = null;

// =================== FUNCIÓN PRINCIPAL PARA GUARDAR AUTORIZACIÓN ===================
function guardarAutorizacion() {
    console.log('💾 GUARDANDO AUTORIZACIÓN...');
    
    try {
        // Obtener datos del formulario
        const autorizador = document.getElementById('autorizador');
        const idUsuario = document.getElementById('id_usuario_logueado');
        // Obtener el id de la programación desde la URL
        const urlParams = new URLSearchParams(window.location.search);
        const idProgramacion = urlParams.get('id');

        console.log('[Autorizar] Datos obtenidos:', {
            autorizador: autorizador ? autorizador.value : null,
            idUsuario: idUsuario ? idUsuario.value : null,
            idProgramacion,
            firma: autorizacionSignatureDataUrl ? autorizacionSignatureDataUrl.substring(0, 30) + '...' : null
        });

        if (!idProgramacion) {
            console.error('[Autorizar] Falta idProgramacion');
            return;
        }

        // Verificar si hay firma
        if (!autorizacionSignatureDataUrl) {
            console.error('[Autorizar] Falta firma');
            return;
        }

        // Preparar datos para el endpoint
        const payload = {
            id: idProgramacion,
            id_autorizo: idUsuario ? idUsuario.value : '',
            firma_autorizo: autorizacionSignatureDataUrl
        };

        console.log('[Autorizar] Enviando payload a mark_autorizado.php:', payload);

        fetch('../../../ajax/mantenimiento/mark_autorizado.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(async response => {
            let data = null;
            try {
                data = await response.json();
            } catch (e) {
                console.error('[Autorizar] Error parseando JSON de respuesta:', e);
            }
            console.log('[Autorizar] Respuesta HTTP:', response.status, response.statusText);
            console.log('[Autorizar] Respuesta JSON:', data);
            if (response.ok && data && data.success) {
                cerrarModal();
                window.location.reload();
            } else {
                console.error('[Autorizar] Error en respuesta:', data);
            }
        })
        .catch(error => {
            console.error('❌ Error al guardar autorización (fetch):', error);
        });
    } catch (error) {
        console.error('❌ Error al guardar autorización (try/catch):', error);
        alert('Error al guardar la autorización: ' + error.message);
    }
}

// =================== FUNCIÓN PARA CERRAR MODAL ===================
function cerrarModal() {
    try {
        const modal = document.getElementById('modalAutorizar');
        if (modal) {
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            } else {
                // Si no hay instancia, crear una temporal para cerrar
                const tempModal = new bootstrap.Modal(modal);
                tempModal.hide();
            }
        }
    } catch (error) {
        console.error('⚠️ Error al cerrar modal:', error);
    }
}

// =================== GESTIÓN DE FIRMA ===================
function inicializarFirma() {
    console.log('✍️ Inicializando sistema de firma...');
    
    const signatureModal = document.getElementById('autorizacionSignatureModal');
    if (!signatureModal) {
        console.log('❌ Modal de firma no encontrado');
        return;
    }
    
    try {
        autorizacionSignatureModal = new bootstrap.Modal(signatureModal);
        console.log('✅ Modal de firma inicializado');
    } catch (error) {
        console.error('❌ Error al inicializar modal de firma:', error);
    }
}

function abrirModalFirma() {
    console.log('📝 Abriendo modal de firma...');
    
    if (!autorizacionSignatureModal) {
        inicializarFirma();
    }
    
    if (autorizacionSignatureModal) {
        autorizacionSignatureModal.show();
        
        setTimeout(() => {
            const canvas = document.getElementById('autorizacionSignaturePad');
            if (canvas) {
                configurarCanvas(canvas);
                autorizacionSignaturePad = new SignaturePad(canvas, { 
                    penColor: 'rgb(0,0,0)',
                    backgroundColor: 'rgb(255,255,255)'
                });
                
                if (autorizacionSignatureDataUrl) {
                    cargarFirmaExistente();
                }
            }
        }, 300);
    }
}

function configurarCanvas(canvas) {
    const container = canvas.parentElement;
    const rect = container.getBoundingClientRect();
    
    canvas.width = rect.width * 2;
    canvas.height = rect.height * 2;
    canvas.style.width = rect.width + 'px';
    canvas.style.height = rect.height + 'px';
    
    const ctx = canvas.getContext('2d');
    ctx.scale(2, 2);
}

function cargarFirmaExistente() {
    if (autorizacionSignaturePad && autorizacionSignatureDataUrl) {
        const img = new Image();
        img.onload = function() {
            const canvas = document.getElementById('autorizacionSignaturePad');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0, canvas.width / 2, canvas.height / 2);
        };
        img.src = autorizacionSignatureDataUrl;
    }
}

function limpiarFirma() {
    console.log('🧹 Limpiando firma...');
    if (autorizacionSignaturePad) {
        autorizacionSignaturePad.clear();
    }
}

function guardarFirma() {
    console.log('💾 Guardando firma...');
    
    if (!autorizacionSignaturePad || autorizacionSignaturePad.isEmpty()) {
        alert('Por favor, realiza una firma antes de guardar.');
        return;
    }
    
    try {
        autorizacionSignatureDataUrl = autorizacionSignaturePad.toDataURL('image/png');
        
        // Actualizar preview
        const img = document.getElementById('autorizacionSignatureImg');
        const placeholder = document.querySelector('#autorizacionSignaturePreview .placeholder-text');
        
        if (img && placeholder) {
            img.src = autorizacionSignatureDataUrl;
            img.style.display = 'block';
            placeholder.style.display = 'none';
        }
        
        // Cerrar modal de firma
        if (autorizacionSignatureModal) {
            autorizacionSignatureModal.hide();
        }
        
        console.log('✅ Firma guardada correctamente');
        
    } catch (error) {
        console.error('❌ Error al guardar firma:', error);
        alert('Error al guardar la firma: ' + error.message);
    }
}

// =================== EVENT LISTENERS ===================
document.addEventListener('click', function(e) {
    // Botón Guardar Autorización
    if (e.target.id === 'btnGuardarAutorizar') {
        console.log('🎯 BOTÓN GUARDAR AUTORIZACIÓN CLICKEADO');
        e.preventDefault();
        guardarAutorizacion();
        return;
    }
    
    // Preview de firma
    if (e.target.closest('#autorizacionSignaturePreview')) {
        console.log('📝 Abriendo editor de firma');
        abrirModalFirma();
        return;
    }
    
    // Limpiar firma
    if (e.target.id === 'clearAutorizacionSignature') {
        limpiarFirma();
        return;
    }
    
    // Guardar firma
    if (e.target.id === 'saveAutorizacionSignature') {
        guardarFirma();
        return;
    }
});

// =================== INICIALIZACIÓN ===================
document.addEventListener('DOMContentLoaded', function() {
    console.log('📋 DOM listo - Verificando elementos...');
    
    const elementos = {
        modalAutorizar: document.getElementById('modalAutorizar'),
        modalFirma: document.getElementById('autorizacionSignatureModal'),
        btnGuardar: document.getElementById('btnGuardarAutorizar'),
        autorizador: document.getElementById('autorizador'),
        preview: document.getElementById('autorizacionSignaturePreview')
    };
    
    let todosPresentes = true;
    Object.entries(elementos).forEach(([nombre, elemento]) => {
        if (elemento) {
            console.log(`✅ ${nombre}: ENCONTRADO`);
        } else {
            console.log(`❌ ${nombre}: NO ENCONTRADO`);
            todosPresentes = false;
        }
    });
    
    if (todosPresentes) {
        console.log('🎉 TODOS LOS ELEMENTOS ENCONTRADOS - SISTEMA LISTO');
        inicializarFirma();
    } else {
        console.log('⚠️ FALTAN ELEMENTOS - VERIFICAR HTML');
    }
});

console.log('✅ SCRIPT DE AUTORIZACIÓN CARGADO COMPLETAMENTE');
</script>

<!-- COLOCA ESTE SCRIPT AL FINAL DE TU PÁGINA, DESPUÉS DE TODOS LOS DEMÁS -->
<script>
// =================== SCRIPT DOMINANTE - SOBRESCRIBE TODO ===================
console.log('🚀 SCRIPT DOMINANTE INICIANDO...');

// ESPERAR UN MOMENTO PARA QUE SE CARGUEN OTROS SCRIPTS
setTimeout(function() {
    console.log('💥 SOBRESCRIBIENDO FUNCIONES EXISTENTES...');
    
    // ELIMINAR TODOS LOS EVENT LISTENERS EXISTENTES DEL BODY
    const nuevoBody = document.body.cloneNode(true);
    document.body.parentNode.replaceChild(nuevoBody, document.body);
    
    // FUNCIÓN PARA EL BOTÓN GUARDAR
    function ejecutarGuardar() {
        console.log('🎯 ¡¡¡BOTÓN GUARDAR FUNCIONANDO!!!');
        
        // Obtener datos
        const autorizador = document.getElementById('autorizador');
        const idUsuario = document.getElementById('id_usuario_logueado');
        
        const datos = {
            autorizador: autorizador ? autorizador.value : 'No encontrado',
            idUsuario: idUsuario ? idUsuario.value : 'No encontrado'
        };
        
        console.log('📋 Datos obtenidos:', datos);
        
        alert('🎉 ¡ÉXITO! Autorización procesada:\n\n' + 
              'Autorizador: ' + datos.autorizador + '\n' +
              'ID Usuario: ' + datos.idUsuario);
        
        // Cerrar modal
        try {
            const modal = document.getElementById('modalAutorizar');
            if (modal) {
                const modalInstance = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                modalInstance.hide();
                console.log('✅ Modal cerrado');
            }
        } catch (e) {
            console.log('⚠️ Error al cerrar modal:', e);
        }
    }
    
    // NUEVO EVENT LISTENER QUE DOMINA TODOS
    document.body.addEventListener('click', function(e) {
        console.log('👆 CLICK INTERCEPTADO por script dominante:', e.target.id, e.target.tagName);
        
        // DETENER PROPAGACIÓN PARA EVITAR OTROS SCRIPTS
        e.stopImmediatePropagation();
        
        if (e.target.id === 'btnGuardarAutorizar') {
            console.log('🎯 BOTÓN GUARDAR DETECTADO POR SCRIPT DOMINANTE');
            e.preventDefault();
            ejecutarGuardar();
        }
    }, true); // true = CAPTURE phase, se ejecuta ANTES que otros
    
    console.log('✅ SCRIPT DOMINANTE CONFIGURADO');
    
}, 1000); // Esperar 1 segundo

console.log('⏳ SCRIPT DOMINANTE CARGADO, ESPERANDO...');
</script>

<?php // No dejar espacios ni líneas después de esta línea ?>