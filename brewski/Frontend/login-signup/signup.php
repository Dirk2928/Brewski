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

                <form class="form" id="signup-form" novalidate>

                    <div class="field">
                        <label for="first_name">First name</label>
                        <input type="text" id="first_name" name="first_name" autocomplete="given-name" required>
                        <p class="field__error" id="first_name-error" style="display: none;"></p>
                    </div>

                    <div class="field">
                        <label for="last_name">Last name</label>
                        <input type="text" id="last_name" name="last_name" autocomplete="family-name" required>
                        <p class="field__error" id="last_name-error" style="display: none;"></p>
                    </div>

                    <div class="field">
                        <label for="password">Create password</label>
                        <input type="password" id="password" name="password" autocomplete="new-password" required minlength="8">
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