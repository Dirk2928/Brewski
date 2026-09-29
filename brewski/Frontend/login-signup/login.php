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

                <p id="login-error" class="field__error" style="text-align: center; margin-bottom: 1rem; display: none;"></p>

                <form class="form" id="login-form" novalidate>

                    <div class="field">
                        <label for="first_name">First name</label>
                        <input type="text" id="first_name" name="first_name" autocomplete="given-name" required>
                        <p class="field__error" id="first_name-error" style="display: none;"></p>
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" autocomplete="current-password" required>
                        <p class="field__error" id="password-error" style="display: none;"></p>
                    </div>

                    <button type="submit" class="btn">Login</button>
                </form>

                <p class="auth__switch">
                    Don't have an account? <a href="signup.php">Click here to sign up.</a>
                </p>

            </div>
        </section>

    </main>

    <!-- Link to external JavaScript file -->
    <script src="script.js"></script>
</body>
</html>