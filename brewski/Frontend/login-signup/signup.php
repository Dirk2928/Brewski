<?php
session_start();
require_once 'signup.php';

// If already logged in, redirect
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error   = '';
$success = '';

// Preserve input values on error
$first_name_value = '';
$last_name_value  = '';
$email_value      = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name']  ?? '');
    $email      = trim($_POST['email']      ?? '');
    $password   = $_POST['password']        ?? '';

    $first_name_value = htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8');
    $last_name_value  = htmlspecialchars($last_name,  ENT_QUOTES, 'UTF-8');
    $email_value      = htmlspecialchars($email,      ENT_QUOTES, 'UTF-8');

    // ---- Validation ----
    if ($first_name === '' || $last_name === '' || $email === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/^[A-Za-zÀ-ÿ\s\'\-]+$/u', $first_name) ||
              !preg_match('/^[A-Za-zÀ-ÿ\s\'\-]+$/u', $last_name)) {
        $error = 'Name may only contain letters, spaces, hyphens, and apostrophes.';
    } else {
        // ---- Check if email already exists ----
        $check = $conn->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
        $check->bind_param('s', $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'An account with this email already exists.';
            $check->close();
        } else {
            $check->close();

            // ---- Hash password & insert ----
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (first_name, last_name, email, password, role)
                    VALUES (?, ?, ?, ?, 'CUSTOMER')";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('ssss', $first_name, $last_name, $email, $hashed);

                if ($stmt->execute()) {
                    $success = 'Account created! Redirecting to login...';
                    // Clear values after success
                    $first_name_value = $last_name_value = $email_value = '';

                    // Optional: auto-redirect after 2 seconds
                    header('Refresh: 2; url=login.php');
                } else {
                    $error = 'Something went wrong. Please try again.';
                }
                $stmt->close();
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create your account | brewski</title>

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

                <h1 class="auth__title">Create your account</h1>

                <!-- Server-side error / success messages -->
                <p id="signup-error" class="field__error"
                   style="text-align:center; margin-bottom:1rem; <?php echo $error ? '' : 'display:none;'; ?>">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </p>

                <p id="signup-success" class="field__success"
                   style="text-align:center; margin-bottom:1rem; color: green; <?php echo $success ? '' : 'display:none;'; ?>">
                    <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
                </p>

                <form class="form" id="signup-form" method="POST" action="signup.php" novalidate>

                    <div class="field">
                        <label for="first_name">First name</label>
                        <input type="text" id="first_name" name="first_name"
                               value="<?php echo $first_name_value; ?>"
                               autocomplete="given-name" required>
                        <p class="field__error" id="first_name-error" style="display: none;"></p>
                    </div>

                    <div class="field">
                        <label for="last_name">Last name</label>
                        <input type="text" id="last_name" name="last_name"
                               value="<?php echo $last_name_value; ?>"
                               autocomplete="family-name" required>
                        <p class="field__error" id="last_name-error" style="display: none;"></p>
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                               value="<?php echo $email_value; ?>"
                               autocomplete="email" required>
                        <p class="field__error" id="email-error" style="display:none;"></p>
                    </div>

                    <div class="field">
                        <label for="password">Create password</label>
                        <input type="password" id="password" name="password"
                               autocomplete="new-password" required minlength="8">
                        <p class="field__error" id="password-error" style="display: none;"></p>
                    </div>

                    <button type="submit" class="btn">Create account</button>
                </form>

                <p class="auth__switch">
                    Already have an account? <a href="login.php">Click here to login.</a>
                </p>

            </div>
        </section>

    </main>

    <!-- Link to external JavaScript file -->
    <script src="script.js"></script>
</body>
</html>