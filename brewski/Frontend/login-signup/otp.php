<?php
require_once __DIR__ . '/mailer.php';

/**
 * Creates a new OTP for the user, stores its hash, and emails it.
 * Returns true if the email was sent.
 */
function issue_otp(mysqli $conn, int $user_id, string $email, string $name): bool
{
    // Remove any old codes for this user
    $del = $conn->prepare("DELETE FROM otp_codes WHERE user_id = ?");
    $del->bind_param('i', $user_id);
    $del->execute();
    $del->close();

    $code      = (string) random_int(100000, 999999);
    $code_hash = password_hash($code, PASSWORD_DEFAULT);

    // Expiry is calculated by MySQL so PHP/MySQL timezones can't disagree
    $ins = $conn->prepare(
        "INSERT INTO otp_codes (user_id, code_hash, expires_at)
         VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))"
    );
    $ins->bind_param('is', $user_id, $code_hash);
    $ok = $ins->execute();
    $ins->close();

    if (!$ok) {
        return false;
    }

    $safe_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

    $body = "
        <div style='font-family:Arial,sans-serif;max-width:480px;margin:auto;color:#1e110a'>
            <h2>Your brewski login code</h2>
            <p>Hi {$safe_name}, use this code to finish logging in:</p>
            <p style='font-size:32px;font-weight:bold;letter-spacing:8px;
                      background:#f1e2ca;padding:14px 20px;border-radius:8px;
                      display:inline-block;margin:8px 0;'>{$code}</p>
            <p style='font-size:12px;color:#71492a'>
                This code expires in 10 minutes. If you didn't try to log in,
                please change your password.
            </p>
        </div>";

    return send_mail($email, $name, 'Your brewski login code', $body);
}