<?php
require_once __DIR__ . '/../../../vendor/autoload.php'; // PHPMailer autoload

/**
 * Envía un correo usando PHPMailer (SMTP Gmail)
 * @param string $to Correo destino
 * @param string $subject Asunto
 * @param string $body HTML del mensaje
 * @param string $from Correo origen (opcional)
 * @param string $fromName Nombre origen (opcional)
 * @return bool true si se envió, false si falló
 */
function sendEmail($to, $subject, $body, $from = 'rodrigorafaelchipacheco@gmail.com', $fromName = 'GLPI Mantenimiento') {
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rodrigorafaelchipacheco@gmail.com';
        $mail->Password = 'rzlgjdhkkrflvuus';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($from, $fromName);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        error_log('[send_email.php] Correo enviado a ' . $to);
        return true;
    } catch (Exception $e) {
        error_log('[send_email.php] Error al enviar correo: ' . $e->getMessage());
        return false;
    }
}
