<?php
session_start();

header('Location: resend-login-otp.php', true, 307);
exit;

mysqli_report(MYSQLI_REPORT_OFF);








$conn = new mysqli(
    'localhost',
    'root',
    '',
    'brewski_db'
);

if ($conn->connect_error) {
    die('Unable to connect to the database.');
}

$conn->set_charset('utf8mb4');








if (
    !isset($_SESSION['verification_user_id']) ||
    !isset($_SESSION['verification_email'])
) {

    header('Location: signup.php');
    exit;
}


$user_id = (int) $_SESSION['verification_user_id'];
$email   = $_SESSION['verification_email'];

$first_name = $_SESSION['verification_name'] ?? 'there';








try {

    $otp = (string) random_int(
        100000,
        999999
    );

    $email_activation_token = bin2hex(random_bytes(32));
    $email_activation_hash = hash('sha256', $email_activation_token);

} catch (Exception $e) {

    die(
        'Unable to generate a verification code. Please try again.'
    );
}








$otp_hash = password_hash(
    $otp,
    PASSWORD_DEFAULT
);








$otp_expires = date(
    'Y-m-d H:i:s',
    time() + (10 * 60)
);

$email_activation_expires = date(
    'Y-m-d H:i:s',
    time() + (24 * 60 * 60)
);








$stmt = $conn->prepare(
    "UPDATE users
     SET
        activation_token = ?,
        activation_expires = ?,
          email_activation_token = ?,
          email_activation_expires = ?,
        is_active = 0,
        updated_at = CURRENT_TIMESTAMP
     WHERE user_id = ?"
);

if (!$stmt) {

    die(
        'Unable to prepare the verification request.'
    );
}


$stmt->bind_param(
    'ssssi',
    $otp_hash,
    $otp_expires,
    $email_activation_hash,
    $email_activation_expires,
    $user_id
);


if (!$stmt->execute()) {

    $stmt->close();

    die(
        'Unable to generate a new verification code.'
    );
}


$stmt->close();








$url = 'http://localhost:3000/send-otp';

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$directory = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$activation_url = $scheme . '://' . $host . $directory
    . '/activate.php?token=' . urlencode($email_activation_token);

$data = [
    'email'     => $email,
    'firstName' => $first_name,
    'otp'       => $otp,
    'activationUrl' => $activation_url
];

$json = json_encode($data);


$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_POST            => true,
    CURLOPT_POSTFIELDS      => $json,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json)
    ],
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_CONNECTTIMEOUT  => 5,
    CURLOPT_TIMEOUT         => 15
]);


$response = curl_exec($ch);


if ($response === false) {

    curl_close($ch);

    die(
        'Unable to contact the email service. Please make sure Node.js is running.'
    );
}


$httpCode = curl_getinfo(
    $ch,
    CURLINFO_HTTP_CODE
);

curl_close($ch);








$result = json_decode(
    $response,
    true
);


if (
    $httpCode !== 200 ||
    !isset($result['success']) ||
    $result['success'] !== true
) {

    die(
        'Unable to send the verification code. Please try again.'
    );
}








$_SESSION['otp_resend_success'] =
    'A new verification code has been sent to your email.';

header('Location: verify-otp.php');

exit;