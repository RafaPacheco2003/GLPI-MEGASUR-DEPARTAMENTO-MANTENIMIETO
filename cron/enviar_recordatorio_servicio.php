<?php
// Script para enviar recordatorio por correo un día antes de la fecha de servicio (primer servicio de cada programación)
// Ejecutar este script diariamente con cron o tarea programada

// Forzar recarga de la clase si ya está definida incorrectamente
if (!class_exists('ServicioManager')) {
    require_once __DIR__ . '/../inc/mantenimiento/servicioManager.php';
}
require_once __DIR__ . '/../ajax/mantenimiento/send_email.php';
require_once __DIR__ . '/../vendor/autoload.php';

$servicioManager = new ServicioManager();


// Obtener todas las programaciones y el correo del responsable (elaboro)
$mysqli = new MantenimientoConnection();
$conn = $mysqli->getConnection();
$programaciones = [];
// Ajusta el nombre de la tabla de usuarios y el campo de correo según tu modelo
$sql = 'SELECT id FROM programacion';
$res = $conn->query($sql);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $programaciones[] = $row;
    }
}

$hoy = new DateTime('now');
$hoy->setTime(0,0,0);
$manana = clone $hoy;
$manana->modify('+1 day');

foreach ($programaciones as $prog) {
    $servicio = $servicioManager->getPrimerServicioByProgramacion($prog['id']);
    if (!$servicio) continue;
    $fechaServicio = DateTime::createFromFormat('Y-m-d H:i:s', $servicio['fecha_inicio']);
    if (!$fechaServicio) continue;
    $fechaServicio->setTime(0,0,0);
    if ($fechaServicio == $hoy) { // CAMBIO: para pruebas, envía si es HOY
        // Enviar correo
    $to = 'rafael.chi@gmegasur.com.mx';
        $subject = 'Recordatorio: Servicio programado para mañana';
        $body = '<p>Le recordamos que tiene un servicio programado para el día <b>' . $fechaServicio->format('d/m/Y') . '</b>.</p>';
        $body .= '<p>Detalles:<br>Servidor/Site: ' . htmlspecialchars($servicio['servidor_site']) . '<br>Programación ID: ' . $prog['id'] . '</p>';
        sendEmail($to, $subject, $body);
    }
}

echo "Recordatorios enviados (si correspondía).\n";
