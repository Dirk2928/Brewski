<?php
session_start();

require __DIR__ . '/otp.php';

$CUSTOMER_HOME = '../customer/customer-home/customerhome.php';
$ADMIN_HOME    = '../admin/admin%20home/admin_dashboard.php';

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli('localhost', 'root', '', 'brewski_db');

if ($conn->connect_error) {
    $conn = null;
} else {
    $conn->set_charset('utf8mb4');
}

function normalizeRole($role)
{
    return strtoupper(trim((string) $role));
}

function redirectByRole($role, $customerHome, $adminHome)
{
    $role = normalizeRole($role);

    if ($role === 'ADMIN' || $role === 'STAFF') {
        header('Location: ' . $adminHome);
    } else {
        header('Location: ' . $customerHome);
    }
    exit;
}

$error       = '';
$email_value = '';

// A message passed from other pages (e.g. cancelled OTP)
if (isset($_GET['msg']) && $_GET['msg'] === 'otp_cancelled') {
    $error = 'Login cancelled. Please log in again.';
}

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}

if (isset($_SESSION['user_id'])) {
    redirectByRole($_SESSION['role'] ?? '', $CUSTOMER_HOME, $ADMIN_HOME);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $email_value = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

    if ($email === '' || $password === '') {

        $error = 'Please fill in all fields.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = 'Please enter a valid email address.';

    } elseif (!$conn) {

        $error = 'Unable to connect to the database. Please make sure MySQL is running.';

    } else {

        $stmt = $conn->prepare(
            "SELECT user_id, first_name, last_name, email, password, role, is_active
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        if (!$stmt) {

            $error = 'Something went wrong while accessing the database.';

        } else {

            $stmt->bind_param('s', $email);

            if (!$stmt->execute()) {

                $error = 'Something went wrong. Please try again.';

            } else {

                $stmt->store_result();

                if ($stmt->num_rows === 1) {

                    $stmt->bind_result(
                        $user_id,
                        $first_name,
                        $last_name,
                        $user_email,
                        $stored_password,
                        $role,
                        $is_active
                    );

                    $stmt->fetch();

                    $role = normalizeRole($role);

                    $passwordOk = password_verify($password, $stored_password);

                    // Legacy plain-text passwords: upgrade to a hash on first login
                    if (!$passwordOk && hash_equals((string) $stored_password, $password)) {

                        $passwordOk = true;

                        $newHash = password_hash($password, PASSWORD_DEFAULT);
                        $upd = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");

                        if ($upd) {
                            $upd->bind_param('si', $newHash, $user_id);
                            $upd->execute();
                            $upd->close();
                        }
                    }

                    if (!$passwordOk) {

                        $error = 'Incorrect email or password.';

                    } elseif ((int) $is_active !== 1) {

                        // Password is right, but the email was never confirmed
                        $error = 'Your account is not activated yet. Please check your email for the activation link.';

                    } else {

                        // Password OK + account active -> send OTP.
                        // The user is NOT logged in yet; we only remember who is pending.
                        $full_name = $first_name . ' ' . $last_name;

                        if (issue_otp($conn, (int) $user_id, $user_email, $full_name)) {

                            session_regenerate_id(true);

                            $_SESSION['otp_pending'] = [
                                'user_id'    => (int) $user_id,
                                'first_name' => $first_name,
                                'last_name'  => $last_name,
                                'email'      => $user_email,
                                'role'       => $role,
                                'started_at' => time(),
                                'last_sent'  => time(),
                            ];

                            session_write_close();

                            header('Location: verify-otp.php');
                            exit;

                        } else {

                            $error = 'We could not send your login code. Please try again.';
                        }
                    }

                } else {

                    $error = 'Incorrect email or password.';
                }
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | brewski</title>

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

            <h1 class="auth__title">Welcome back</h1>

            <?php if ($error !== ''): ?>
                <p id="login-error" class="field__error"
                   style="text-align: center; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php else: ?>
                <p id="login-error" class="field__error"
                   style="display: none; text-align: center; margin-bottom: 1rem;"></p>
            <?php endif; ?>

            <form class="form" id="login-form" method="POST" action="login.php">

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           value="<?php echo $email_value; ?>"
                           autocomplete="email" required>
                    <p class="field__error" id="email-error" style="display: none;"></p>
                </div>

               <div class="field">
    <label for="password">Password</label>
    <div class="password-wrap">
        <input type="password" id="password" name="password"
               autocomplete="current-password" required>
        <button type="button"
                class="password-toggle"
                id="toggle-password"
                aria-label="Show password"
                aria-pressed="false">
            👁
        </button>
    </div>
    <p class="field__error" id="password-error" style="display: none;"></p>
</div>

                <button type="submit" class="btn">Login</button>

            </form>

            <p class="auth__switch">
                Don't have an account?
                <a href="signup.php">Click here to sign up.</a>
            </p>

        </div>
    </section>

</main>

<script src="script.js"></script>

</body>
</html>