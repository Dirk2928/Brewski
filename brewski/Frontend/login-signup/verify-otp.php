<?php
session_start();

require __DIR__ . '/otp.php';

$CUSTOMER_HOME = '../customer/customer-home/customerhome.php';
$ADMIN_HOME    = '../admin/admin%20home/admin_dashboard.php';

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli('localhost', 'root', '', 'brewski_db');
if ($conn->connect_error) {
    die('Unable to connect to the database.');
}
$conn->set_charset('utf8mb4');

// Already fully logged in
if (isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// No pending login -> go back to login
if (empty($_SESSION['otp_pending'])) {
    header('Location: login.php');
    exit;
}

$pending = $_SESSION['otp_pending'];
$user_id = (int) $pending['user_id'];

$error   = '';
$success = '';

const MAX_ATTEMPTS    = 5;
const RESEND_COOLDOWN = 60; // seconds

// Cancel
if (isset($_GET['cancel'])) {
    $del = $conn->prepare("DELETE FROM otp_codes WHERE user_id = ?");
    $del->bind_param('i', $user_id);
    $del->execute();
    $del->close();

    unset($_SESSION['otp_pending']);
    header('Location: login.php?msg=otp_cancelled');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? 'verify';

    /* ---------- Resend code ---------- */
    if ($action === 'resend') {

        $wait = RESEND_COOLDOWN - (time() - (int) $pending['last_sent']);

        if ($wait > 0) {

            $error = "Please wait {$wait} seconds before requesting a new code.";

        } else {

            $name = $pending['first_name'] . ' ' . $pending['last_name'];

            if (issue_otp($conn, $user_id, $pending['email'], $name)) {
                $_SESSION['otp_pending']['last_sent'] = time();
                $success = 'A new code has been sent to your email.';
            } else {
                $error = 'We could not send a new code. Please try again.';
            }
        }

    /* ---------- Verify code ---------- */
    } else {

        $code = preg_replace('/\D/', '', $_POST['otp'] ?? '');

        if (strlen($code) !== 6) {

            $error = 'Please enter the 6-digit code.';

        } else {

            $stmt = $conn->prepare(
                "SELECT otp_id, code_hash, attempts,
                        (expires_at > NOW()) AS still_valid
                 FROM otp_codes
                 WHERE user_id = ?
                 ORDER BY otp_id DESC
                 LIMIT 1"
            );
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$row) {

                $error = 'No active code. Please request a new one.';

            } elseif (!(int) $row['still_valid']) {

                $error = 'This code has expired. Please request a new one.';

            } elseif ((int) $row['attempts'] >= MAX_ATTEMPTS) {

                $error = 'Too many incorrect attempts. Please request a new code.';

            } elseif (password_verify($code, $row['code_hash'])) {

                // Success: remove codes and complete the login
                $del = $conn->prepare("DELETE FROM otp_codes WHERE user_id = ?");
                $del->bind_param('i', $user_id);
                $del->execute();
                $del->close();

                session_regenerate_id(true);

                $_SESSION['user_id']    = $pending['user_id'];
                $_SESSION['first_name'] = $pending['first_name'];
                $_SESSION['last_name']  = $pending['last_name'];
                $_SESSION['email']      = $pending['email'];
                $_SESSION['role']       = $pending['role'];

                unset($_SESSION['otp_pending']);

                session_write_close();

                if ($pending['role'] === 'ADMIN' || $pending['role'] === 'STAFF') {
                    header('Location: ' . $ADMIN_HOME);
                } else {
                    header('Location: ' . $CUSTOMER_HOME);
                }
                exit;

            } else {

                $upd = $conn->prepare(
                    "UPDATE otp_codes SET attempts = attempts + 1 WHERE otp_id = ?"
                );
                $upd->bind_param('i', $row['otp_id']);
                $upd->execute();
                $upd->close();

                $left  = MAX_ATTEMPTS - ((int) $row['attempts'] + 1);
                $error = $left > 0
                    ? "Incorrect code. {$left} attempt(s) left."
                    : 'Too many incorrect attempts. Please request a new code.';
            }
        }
    }
}

// Mask the email for display: j***@gmail.com
$parts  = explode('@', $pending['email']);
$masked = substr($parts[0], 0, 1) . str_repeat('*', max(strlen($parts[0]) - 1, 2)) . '@' . ($parts[1] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify code | brewski</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles.css">
</head>
<body>

<main class="auth">

    <aside class="side">
        <p class="side__word">brew<span>ski</span></p>
        <p class="side__line">Your favorite cup, ready when you are.</p>

        <nav class="side__nav">
            <a href="about-us.html" class="side__btn">About us</a>
            <a href="about-brewski.html" class="side__btn">About brewski</a>
        </nav>
    </aside>

    <section class="auth__panel">
        <div class="auth__content">

            <img class="brand__logo" src="images/brewskilogo.png" alt="Brewski Logo">
            <p class="brand__name">brew<span>ski</span></p>

            <h1 class="auth__title">Enter your code</h1>

            <p style="font-size:0.85rem;margin:0 0 1.2rem;">
                We sent a 6-digit code to
                <strong><?php echo htmlspecialchars($masked, ENT_QUOTES, 'UTF-8'); ?></strong>.
                It expires in 10 minutes.
            </p>

            <?php if ($error !== ''): ?>
                <p class="field__error" style="text-align:center;margin-bottom:1rem;">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>

            <?php if ($success !== ''): ?>
                <p class="field__success" style="text-align:center;margin-bottom:1rem;color:green;">
                    <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>

            <form class="form" method="POST" action="verify-otp.php">
                <input type="hidden" name="action" value="verify">

                <div class="field">
                    <label for="otp">6-digit code</label>
                    <input type="text" id="otp" name="otp"
                           inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
                           autocomplete="one-time-code" required autofocus
                           style="text-align:center;letter-spacing:0.5rem;font-size:1.2rem;">
                </div>

                <button type="submit" class="btn">Verify</button>
            </form>

            <form method="POST" action="verify-otp.php" style="margin-top:0.8rem;">
                <input type="hidden" name="action" value="resend">
                <button type="submit" class="btn"
                        style="background:transparent;color:var(--espresso);
                               border:1px solid var(--espresso);">
                    Resend code
                </button>
            </form>

            <p class="auth__switch">
                <a href="verify-otp.php?cancel=1">Cancel and go back to login</a>
            </p>

        </div>
    </section>

</main>

</body>
</html>