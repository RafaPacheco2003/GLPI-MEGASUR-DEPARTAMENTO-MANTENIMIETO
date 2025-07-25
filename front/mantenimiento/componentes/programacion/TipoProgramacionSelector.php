<?php
/**
 * Componente para seleccionar el tipo de programación
 */
class TipoProgramacionSelector
{
    /**
     * Renderiza el selector de tipo de programación
     * @param ProgramacionManager $programacionManager Instancia del manager
     * @return string HTML del selector
     */
    public static function render($programacionManager)
    {
        ob_start();
        ?>
        <style>
            .card-programacion.selected {
                border: 2px solid #2563eb;
                box-shadow: 0 4px 16px rgba(37, 99, 235, 0.08);
                background: #f0f6ff;
            }
            .card-programacion {
                width: 100%;
                min-width: 0;
                border-radius: 10px;
                transition: box-shadow 0.2s;
            }
            #cardsProgramacion .col-12 {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        </style>
        <label class="form-label">Selecciona el tipo de programación</label>
        <div id="cardsProgramacionContainer" class="w-100">
            <div class="d-flex flex-column gap-3 w-100" id="cardsProgramacion">
                <?php
                $nombresProgramacion = $programacionManager->getNombreProgramacionEnum();
                foreach ($nombresProgramacion as $nombre):
                    $safeNombre = htmlspecialchars($nombre);
                    ?>
                    <div class="col-12 p-0">
                        <div class="card card-programacion w-100" data-nombre="<?= $safeNombre ?>"
                            style="cursor:pointer;">
                            <div class="card-body d-flex align-items-center" style="min-height:56px;">
                                <i class="fas fa-tag me-2"></i> <span><?= $safeNombre ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nombreProgramacion = document.getElementById('nombreProgramacion');
            const sectionRestante = document.getElementById('sectionRestante');
            const sectionEleccion = document.getElementById('sectionEleccion');
            const cardsContainer = document.getElementById('cardsProgramacion');
            if (cardsContainer && nombreProgramacion && sectionRestante && sectionEleccion) {
                cardsContainer.querySelectorAll('.card-programacion').forEach(card => {
                    card.addEventListener('click', function () {
                        // Remover selección previa
                        cardsContainer.querySelectorAll('.card-programacion').forEach(c => c.classList.remove('selected'));
                        this.classList.add('selected');
                        // Sincronizar valor y bloquear campo
                        nombreProgramacion.value = this.getAttribute('data-nombre');
                        nombreProgramacion.readOnly = true;
                        nombreProgramacion.disabled = true;
                        // Layout dinámico: tarjeta seleccionada arriba y ancho completo, las demás abajo
                        const allCards = Array.from(cardsContainer.children);
                        allCards.forEach(cardCol => {
                            cardCol.className = 'col-12';
                            cardsContainer.appendChild(cardCol);
                        });
                        sectionRestante.style.display = 'block';
                        sectionEleccion.style.display = 'none';
                        // Desplazar a la siguiente sección
                        setTimeout(function () {
                            sectionRestante.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 200);
                    });
                });
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
