<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

   require __DIR__ . '/phpmailer/src/Exception.php';
   require __DIR__ . '/phpmailer/src/PHPMailer.php';
   require __DIR__ . '/phpmailer/src/SMTP.php';

function send_mail(string $to, string $name, string $subject, string $html): bool
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mogurinia@gmail.com';
        $mail->Password   = 'gfkrbuqflfwjjqij';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('mogurinia@gmail.com', 'brewski');
        $mail->addAddress($to, $name);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body    = $html;
        $mail->AltBody = strip_tags($html);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mailer error: ' . $mail->ErrorInfo);
        return false;
    }
}