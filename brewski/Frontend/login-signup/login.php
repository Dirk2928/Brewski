<?php
session_start();
require_once 'login.php';

// If already logged in, redirect
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$email_value = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $email_value = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

    // Basic validation
    if ($email === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Look up user by email
        $sql = "SELECT user_id, first_name, last_name, email, password, role
                FROM users
                WHERE email = ?
                LIMIT 1";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                // Verify hashed password
                if (password_verify($password, $row['password'])) {
                    // Regenerate session ID to prevent fixation
                    session_regenerate_id(true);

                    $_SESSION['user_id']    = $row['user_id'];
                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['last_name']  = $row['last_name'];
                    $_SESSION['email']      = $row['email'];
                    $_SESSION['role']       = $row['role'];

                    // Redirect based on role (optional)
                    if ($row['role'] === 'ADMIN' || $row['role'] === 'STAFF') {
                        header('Location: dashboard.php');
                    } else {
                        header('Location: dashboard.php');
                    }
                    exit;
                } else {
                    $error = 'Incorrect email or password.';
                }
            } else {
                $error = 'Incorrect email or password.';
            }
            $stmt->close();
        } else {
            $error = 'Something went wrong. Please try again.';
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

                <p id="login-error" class="field__error"
                   style="text-align:center; margin-bottom:1rem; <?php echo $error ? '' : 'display:none;'; ?>">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </p>

                <form class="form" id="login-form" method="POST" action="login.php" novalidate>

                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                               value="<?php echo $email_value; ?>"
                               autocomplete="email" required>
                        <p class="field__error" id="email-error" style="display:none;"></p>
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password"
                               autocomplete="current-password" required>
                        <p class="field__error" id="password-error" style="display:none;"></p>
                    </div>

                    <button type="submit" class="btn">Login</button>
                </form>

                <p class="auth__switch">
                    Don't have an account? <a href="signup.php">Click here to sign up.</a>
                </p>

            </div>
        </section>

    </main>

    <script src="script.js"></script>
</body>
</html>