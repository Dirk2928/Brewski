<?php
mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli('localhost', 'root', '', 'brewski_db');
$conn->set_charset('utf8mb4');

$token   = $_GET['token'] ?? '';
$status  = 'invalid';

if (preg_match('/^[a-f0-9]{64}$/', $token)) {

    $hash = hash('sha256', $token);

    $stmt = $conn->prepare(
        "SELECT user_id FROM users
         WHERE activation_token = ? AND is_active = 0
           AND activation_expires > NOW()
         LIMIT 1"
    );
    $stmt->bind_param('s', $hash);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user) {
        $upd = $conn->prepare(
            "UPDATE users
             SET is_active = 1, activation_token = NULL, activation_expires = NULL
             WHERE user_id = ?"
        );
        $upd->bind_param('i', $user['user_id']);
        $upd->execute();
        $upd->close();
        $status = 'success';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account activation | brewski</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<main class="auth" style="justify-content:center;align-items:center;">
    <section class="auth__panel">
        <div class="auth__content">
            <img class="brand__logo" src="images/brewskilogo.png" alt="Brewski Logo">
            <p class="brand__name">brew<span>ski</span></p>

            <?php if ($status === 'success'): ?>
                <h1 class="auth__title">Account activated!</h1>
                <p>Your email is confirmed. You can now log in.</p>
                <a class="btn" href="login.php"
                   style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
                    Go to login
                </a>
            <?php else: ?>
                <h1 class="auth__title">Link invalid or expired</h1>
                <p>This activation link is no longer valid. Please sign up again to get a new one.</p>
                <a class="btn" href="signup.php"
                   style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
                    Back to sign up
                </a>
            <?php endif; ?>
        </div>
    </section>
</main>
</body>
</html>