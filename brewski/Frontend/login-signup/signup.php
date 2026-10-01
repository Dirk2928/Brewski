<?php
session_start();

require __DIR__ . '/mailer.php';

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli('localhost', 'root', '', 'brewski_db');

if ($conn->connect_error) {
    $conn = null;
} else {
    $conn->set_charset('utf8mb4');
}

if (isset($_SESSION['user_id'])) {
    header('Location: ../customer/customer%20home/customerhome.php');
    exit;
}

$error   = '';
$success = '';

$first_name_value = '';
$last_name_value  = '';
$email_value      = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';

    $first_name_value = htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8');
    $last_name_value  = htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8');
    $email_value      = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

    if ($first_name === '' || $last_name === '' || $email === '' || $password === '') {

        $error = 'Please fill in all fields.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = 'Please enter a valid email address.';

    } elseif (strlen($password) < 8) {

        $error = 'Password must be at least 8 characters long.';

    } elseif (
        !preg_match("/^[A-Za-zÀ-ÿ\s'\-]+$/u", $first_name) ||
        !preg_match("/^[A-Za-zÀ-ÿ\s'\-]+$/u", $last_name)
    ) {

        $error = 'Name may only contain letters, spaces, hyphens, and apostrophes.';

    } elseif (!$conn) {

        $error = 'Unable to connect to the database. Please make sure MySQL is running.';

    } else {

        // Does this email already exist?
        $check = $conn->prepare(
            "SELECT user_id, is_active FROM users WHERE email = ? LIMIT 1"
        );
        $check->bind_param('s', $email);
        $check->execute();
        $existing = $check->get_result()->fetch_assoc();
        $check->close();

        if ($existing && (int)$existing['is_active'] === 1) {

            $error = 'An account with this email already exists.';

        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Activation token: raw one goes in the email,
            // only the hash is stored in the database.
            $token      = bin2hex(random_bytes(32));
            $token_hash = hash('sha256', $token);
            $expires    = date('Y-m-d H:i:s', time() + 24 * 60 * 60);

            $user_id    = null;
            $is_new_row = false;

            if ($existing) {
                // Registered before but never activated: refresh the record
                $stmt = $conn->prepare(
                    "UPDATE users
                     SET first_name = ?, last_name = ?, password = ?,
                         activation_token = ?, activation_expires = ?
                     WHERE user_id = ?"
                );
                $uid = (int)$existing['user_id'];
                $stmt->bind_param('sssssi',
                    $first_name, $last_name, $hashed_password,
                    $token_hash, $expires, $uid
                );
                $ok      = $stmt->execute();
                $user_id = $uid;
            } else {
                $stmt = $conn->prepare(
                    "INSERT INTO users
                     (first_name, last_name, email, password, role,
                      is_active, activation_token, activation_expires)
                     VALUES (?, ?, ?, ?, 'CUSTOMER', 0, ?, ?)"
                );
                $stmt->bind_param('ssssss',
                    $first_name, $last_name, $email,
                    $hashed_password, $token_hash, $expires
                );
                $ok         = $stmt->execute();
                $user_id    = $conn->insert_id;
                $is_new_row = true;
            }

            if (!$ok) {

                $error = 'Unable to create account. Please try again.';
                error_log('Signup DB error: ' . $stmt->error);

            } else {

                // Build the activation link
                $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $dir    = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                $link   = $scheme . '://' . $_SERVER['HTTP_HOST'] . $dir
                        . '/activate.php?token=' . urlencode($token);

                $safe_name = htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8');

                $body = "
                    <div style='font-family:Arial,sans-serif;max-width:480px;margin:auto;color:#1e110a'>
                        <h2>Welcome to brewski, {$safe_name}!</h2>
                        <p>Please confirm your email address to activate your account.</p>
                        <p>
                            <a href='{$link}'
                               style='display:inline-block;padding:12px 24px;background:#1e110a;
                                      color:#f1e2ca;text-decoration:none;border-radius:8px;'>
                                Activate my account
                            </a>
                        </p>
                        <p style='font-size:12px;color:#71492a'>
                            This link expires in 24 hours. If you didn't create an account,
                            you can ignore this email.
                        </p>
                    </div>";

                if (send_mail($email, $first_name . ' ' . $last_name, 'Activate your brewski account', $body)) {

                    $success = 'Account created! Please check your email to activate your account.';

                    $first_name_value = '';
                    $last_name_value  = '';
                    $email_value      = '';

                } else {

                    // Email failed: remove the new row so they can retry
                    if ($is_new_row) {
                        $del = $conn->prepare("DELETE FROM users WHERE user_id = ?");
                        $del->bind_param('i', $user_id);
                        $del->execute();
                        $del->close();
                    }

                    $error = 'We could not send the activation email. Please try again later.';
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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Create your account | brewski</title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="styles.css"
    >

</head>

<body>

<main class="auth">

    

    <aside class="side">

        <p class="side__word">
            brew<span>ski</span>
        </p>

        <p class="side__line">
            Your favorite cup, ready when you are.
        </p>

        <nav class="side__nav">

            <a
                href="about-us.html"
                class="side__btn"
            >
                About us
            </a>

            <a
                href="about-brewski.html"
                class="side__btn"
            >
                About brewski
            </a>

        </nav>

    </aside>


    

    <section class="auth__panel">

        <div class="auth__content">

            <img
                class="brand__logo"
                src="images/brewskilogo.png"
                alt="Brewski Logo"
            >

            <p class="brand__name">
                brew<span>ski</span>
            </p>

            <h1 class="auth__title">
                Create your account
            </h1>


            

            <?php if ($error !== ''): ?>

                <p
                    id="signup-error"
                    class="field__error"
                    style="text-align:center; margin-bottom:1rem;"
                >
                    <?php
                    echo htmlspecialchars(
                        $error,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>

            <?php endif; ?>


            

            <?php if ($success !== ''): ?>

                <p
                    id="signup-success"
                    class="field__success"
                    style="text-align:center; margin-bottom:1rem; color:green;"
                >
                    <?php
                    echo htmlspecialchars(
                        $success,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    ?>
                </p>

            <?php endif; ?>


            

            <form
                class="form"
                id="signup-form"
                method="POST"
                action="signup.php"
            >

                

                <div class="field">

                    <label for="first_name">
                        First name
                    </label>

                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value="<?php echo $first_name_value; ?>"
                        autocomplete="given-name"
                        required
                    >

                    <p
                        class="field__error"
                        id="first_name-error"
                        style="display:none;"
                    ></p>

                </div>


                

                <div class="field">

                    <label for="last_name">
                        Last name
                    </label>

                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="<?php echo $last_name_value; ?>"
                        autocomplete="family-name"
                        required
                    >

                    <p
                        class="field__error"
                        id="last_name-error"
                        style="display:none;"
                    ></p>

                </div>


                

                <div class="field">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo $email_value; ?>"
                        autocomplete="email"
                        required
                    >

                    <p
                        class="field__error"
                        id="email-error"
                        style="display:none;"
                    ></p>

                </div>


                

               <div class="field">
    <label for="password">Create password</label>

    <div class="password-wrap">
        <input type="password" id="password" name="password"
               autocomplete="new-password" required minlength="8">

        <button type="button"
                class="password-toggle"
                id="toggle-password"
                aria-label="Show password"
                aria-pressed="false">
            👁
        </button>
    </div>

    <p class="field__error" id="password-error" style="display:none;"></p>
</div>


                

                <button
                    type="submit"
                    class="btn"
                >
                    Create account
                </button>

            </form>


            

            <p class="auth__switch">

                Already have an account?

                <a href="login.php">
                    Click here to login.
                </a>

            </p>

        </div>

    </section>

</main>


<?php if ($success !== ''): ?>

<script>

    setTimeout(function () {

        window.location.href = "login.php";

    }, 2000);

</script>

<?php endif; ?>


<?php if ($success !== ''): ?>

<script>
    setTimeout(function () {
        window.location.href = "login.php";
    }, 2000);
</script>

<?php endif; ?>

<script src="script.js"></script> 


</body>
</html>

</body>

</html>