<div class="card h-100" style="max-height: 300px;">
    <div class="card-body d-flex flex-column">
        <h2 class="fw-bold mb-0">Estado del Servicio</h2>
        <div class="timeline position-relative">
            <!-- Línea vertical -->
            <div class="timeline-line" style="background: #3b82f6;"></div>
            <!-- Item 1: Asignado -->
            <div class="timeline-item">
                <div class="timeline-badge"
                    style="background-color: <?php echo $servicio['estado'] >= 0 ? '#3b82f6' : '#e5e7eb'; ?>;">
                    <i class="fas <?php echo $servicio['estado'] >= 0 ? 'fa-check' : 'fa-clock'; ?>"></i>
                </div>
                <div class="timeline-text">
                    <span class="timeline-title">Asignado</span>
                </div>
            </div>
            <!-- Item 2: En Proceso -->
            <div class="timeline-item">
                <div class="timeline-badge"
                    style="background-color: <?php echo $servicio['estado'] >= 1 ? '#3b82f6' : '#e5e7eb'; ?>;">
                    <i class="fas <?php echo $servicio['estado'] >= 1 ? 'fa-check' : 'fa-clock'; ?>"></i>
                </div>
                <div class="timeline-text">
                    <span class="timeline-title">En Proceso</span>
                </div>
            </div>
            <!-- Item 3: Completado -->
            <div class="timeline-item">
                <div class="timeline-badge"
                    style="background-color: <?php echo $servicio['estado'] >= 2 ? '#3b82f6' : '#e5e7eb'; ?>;">
                    <i class="fas <?php echo $servicio['estado'] >= 2 ? 'fa-check' : 'fa-clock'; ?>"></i>
                </div>
                <div class="timeline-text">
                    <span class="timeline-title">Completado</span>
                </div>
            </div>
        </div>
        <style>
            .timeline {
                padding: 40px 0;
                margin-left: 30px;
                position: relative;
                min-height: 250px;
                display: flex;
                flex-direction: column;
                justify-content: space-around;
            }
            .timeline-line {
                position: absolute;
                left: 0;
                top: 40px;
                bottom: 40px;
                width: 2px;
                background: #3b82f6;
            }
            .timeline-item {
                position: relative;
                padding-left: 35px;
                margin: 15px 0;
                display: flex;
                align-items: center;
            }
            .timeline-badge {
                position: absolute;
                left: -9px;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
                transition: all 0.2s ease;
                border: 2px solid #fff;
                z-index: 1;
            }
            .timeline-badge i {
                color: white;
                font-size: 10px;
            }
            .timeline-text {
                display: flex;
                align-items: center;
            }
            .timeline-title {
                font-weight: 500;
                color: #4b5563;
                font-size: 0.9rem;
                letter-spacing: 0.01em;
            }
            .timeline-item:hover .timeline-badge {
                transform: scale(1.15);
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
            }
        </style>
    </div>
</div>
