// Script global para delegar el click del botón Guardar de AutorizarModal
if (!window._delegatedGuardarAutorizar) {
  console.log('[Autorizar] Script de delegación de Guardar cargado (global)');
  document.body.addEventListener('click', async function(e) {
    const btn = e.target.closest('#btnGuardarAutorizar');
    if (!btn) {
      if (e.target.closest('.modal-footer')) {
        console.error('[Autorizar] Click en modal-footer pero no se encontró el botón Guardar');
      }
      return;
    }
    console.log('[Autorizar] Click en btnGuardarAutorizar (delegado global)');
    let idProgramacion = window.idProgramacionSeleccionada || null;
    if (!idProgramacion) {
      const params = new URLSearchParams(window.location.search);
      idProgramacion = params.get('id');
    }
    if (!idProgramacion) {
      console.error('[Autorizar] No se encontró el ID de la programación a autorizar.');
      alert('No se encontró el ID de la programación a autorizar.');
      return console.warn('[Autorizar] return por falta de idProgramacion');
    }
    let idAutorizador = document.getElementById('id_usuario_logueado').value;
    let firmaAutorizador = window.autorizacionSignatureFileName || '';
    if (!firmaAutorizador) {
      console.warn('[Autorizar] Intento de guardar sin firma.');
      alert('Debes firmar antes de guardar.');
      return console.warn('[Autorizar] return por falta de firmaAutorizador');
    }
    const payload = {
      id: idProgramacion,
      id_autorizo: idAutorizador,
      firma_autorizo: firmaAutorizador
    };
    console.log('[Autorizar] Enviando datos al backend:', payload);
    try {
      const response = await fetch('/glpi/ajax/mantenimiento/mark_autorizado.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      let resultText = await response.text();
      let result;
      try {
        result = JSON.parse(resultText);
      } catch (e) {
        console.error('[Autorizar] Respuesta no es JSON válido:', resultText);
        alert('Respuesta inesperada del servidor.');
        return console.warn('[Autorizar] return por respuesta no JSON');
      }
      console.log('[Autorizar] Respuesta del backend:', result);
      if (result.success) {
        window.location.reload();
      } else {
        console.error('[Autorizar] Error en backend:', result.message);
        alert('Error: ' + (result.message || 'No se pudo marcar como autorizado.'));
        return console.warn('[Autorizar] return por error backend');
      }
    } catch (e) {
      console.error('[Autorizar] Error de conexión:', e);
      alert('Error de conexión: ' + e.message);
      return console.warn('[Autorizar] return por error de conexión');
    }
  });
  window._delegatedGuardarAutorizar = true;
}
