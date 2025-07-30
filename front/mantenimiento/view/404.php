<?php
include '../../../inc/includes.php';
include '../config/ColorConfig.php';

// Inicializar HTML con título personalizado
Html::header("404 - Página no encontrada", $_SERVER['PHP_SELF']);
?>

<div class="error-container">
    <div class="error-content">
        
        <div class="logo-container">
            <div class="megasur-m">M</div>
        </div>
        <h1>404</h1>
        <h2>Página no encontrada</h2>
        <p>Lo sentimos, la página que estás buscando no existe o ha sido movida.</p>
        <a href="/glpi/front/mantenimiento/view/programacion.php" class="btn-return">
            <i class="fas fa-home"></i>
            Volver al inicio
        </a>
    </div>
</div>

<style>
    .error-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8fafc;
        padding: 20px;
    }

    .error-content {
        text-align: center;
        max-width: 500px;
        padding: 40px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .logo-container {
        margin-bottom: 2rem;
    }

    .megasur-m {
        font-size: 6rem;
        font-weight: 800;
        background-color: <?php echo ColorConfig::LOGO_BG; ?>;
        color: <?php echo ColorConfig::LOGO_TEXT; ?>;
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border-radius: 12px;
        position: relative;
        transform: perspective(500px) rotateY(15deg);
        box-shadow: -8px 8px 0 <?php echo ColorConfig::getColorWithOpacity(ColorConfig::HEADER_BG, 0.9); ?>;
        transition: all 0.3s ease;
    }

    .megasur-m:hover {
        transform: perspective(500px) rotateY(0deg);
        box-shadow: 0 8px 0 <?php echo ColorConfig::getColorWithOpacity(ColorConfig::HEADER_BG, 0.9); ?>;
    }

    h1 {
        font-size: 5rem;
        font-weight: 700;
        color: <?php echo ColorConfig::HEADER_BG; ?>;
        margin: 0;
        line-height: 1;
        letter-spacing: -2px;
    }

    h2 {
        font-size: 1.5rem;
        color: <?php echo ColorConfig::BTN_MEGASUR; ?>;
        margin: 1rem 0;
        font-weight: 600;
    }

    p {
        color: #6b7280;
        font-size: 1.1rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .btn-return {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: <?php echo ColorConfig::BTN_PRIMARY; ?>;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-return:hover {
        background-color: <?php echo ColorConfig::BTN_PRIMARY_HOVER; ?>;
        transform: translateY(-1px);
    }

    .btn-return i {
        font-size: 1.1rem;
    }

    /* Animación de entrada */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .error-content {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .error-content {
            padding: 30px;
        }

        .megasur-m {
            font-size: 4rem;
            width: 90px;
            height: 90px;
        }

        h1 {
            font-size: 4rem;
        }

        h2 {
            font-size: 1.25rem;
        }

        p {
            font-size: 1rem;
        }
    }
</style>
