<?php
session_start();
require_once __DIR__ . '/email-service-client.php';

mysqli_report(MYSQLI_REPORT_OFF);

if (empty($_SESSION['otp_pending'])) {
    header('Location: login.php');
    exit;
}

$pending = $_SESSION['otp_pending'];
$user_id = (int) $pending['user_id'];
$wait = 60 - (time() - (int) ($pending['last_sent'] ?? 0));

if ($wait > 0) {
    $_SESSION['otp_resend_error'] = "Please wait {$wait} seconds before requesting a new code.";
    header('Location: verify-otp.php');
    exit;
}

try {
    $otp = (string) random_int(100000, 999999);
} catch (Exception $e) {
    $_SESSION['otp_resend_error'] = 'Unable to generate a new login code. Please try again.';
    header('Location: verify-otp.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'brewski_db');
if ($conn->connect_error) {
    $_SESSION['otp_resend_error'] = 'Unable to connect to the database.';
    header('Location: verify-otp.php');
    exit;
}
$conn->set_charset('utf8mb4');

$delete = $conn->prepare('DELETE FROM otp_codes WHERE user_id = ?');
if (!$delete) {
    $_SESSION['otp_resend_error'] = 'Unable to prepare a new login code.';
    header('Location: verify-otp.php');
    exit;
}
$delete->bind_param('i', $user_id);
$delete->execute();
$delete->close();

$code_hash = password_hash($otp, PASSWORD_DEFAULT);
$insert = $conn->prepare(
    'INSERT INTO otp_codes (user_id, code_hash, expires_at, attempts) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE), 0)'
);

if (!$insert) {
    $_SESSION['otp_resend_error'] = 'Unable to save a new login code.';
    header('Location: verify-otp.php');
    exit;
}

$insert->bind_param('is', $user_id, $code_hash);
if (!$insert->execute()) {
    $_SESSION['otp_resend_error'] = 'Unable to save a new login code.';
    $insert->close();
    header('Location: verify-otp.php');
    exit;
}
$insert->close();

$sent = email_service_send(
    'send-login-otp',
    [
        'email' => $pending['email'],
        'firstName' => $pending['first_name'],
        'otp' => $otp
    ]
);

if (!$sent) {
    $delete = $conn->prepare('DELETE FROM otp_codes WHERE user_id = ?');
    if ($delete) {
        $delete->bind_param('i', $user_id);
        $delete->execute();
        $delete->close();
    }
    $_SESSION['otp_resend_error'] = 'We could not send a new login code. Please try again.';
    header('Location: verify-otp.php');
    exit;
}

$_SESSION['otp_pending']['last_sent'] = time();
$_SESSION['otp_resend_success'] = 'A new login code has been sent to your email.';
header('Location: verify-otp.php');
exit;
