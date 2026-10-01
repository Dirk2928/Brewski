<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header('Location: ../../login-signup/login.php');
    exit;
}

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'CUSTOMER'
) {

    header('Location: ../../login-signup/login.php');
    exit;
}

$first_name = $_SESSION['first_name'] ?? 'Customer';
$last_name  = $_SESSION['last_name'] ?? '';
$email      = $_SESSION['email'] ?? '';

$display_first_name = htmlspecialchars(
    $first_name,
    ENT_QUOTES,
    'UTF-8'
);

$display_last_name = htmlspecialchars(
    $last_name,
    ENT_QUOTES,
    'UTF-8'
);

$display_email = htmlspecialchars(
    $email,
    ENT_QUOTES,
    'UTF-8'
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Home | brewski</title>

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

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="profile.css">
    

    <script src="https://unpkg.com/lucide@latest"></script>

</head>

<body>

    <header class="topbar">

        <div class="topbar__brand">
            brewski
        </div>

        <nav class="topbar__nav">

            <a
                href="customerhome.php"
                class="topbar__link active"
            >
                <i data-lucide="home"></i>
                Home
            </a>

            <a
                href="customermenu.php"
                class="topbar__link"
            >
                <i data-lucide="coffee"></i>
                Menu
            </a>

            <a
                href="customermenu.html"
                class="topbar__link cart-link"
            >
                <i data-lucide="shopping-cart"></i>
                Cart

                <span class="cart-badge">
                    0
                </span>

            </a>

            <a
                href="orders.html"
                class="topbar__link"
            >
                <i data-lucide="receipt"></i>
                Orders
            </a>

            <div class="profile-menu">
    <button
        type="button"
        class="topbar__link profile-button"
        id="profile-button"
    >
        <i data-lucide="user"></i>
        Profile
        <i data-lucide="chevron-down" class="profile-chevron"></i>
    </button>

    <div class="profile-dropdown" id="profile-dropdown">
        <div class="profile-dropdown__user">
            <strong><?= $display_first_name . ' ' . $display_last_name ?></strong>
            <span><?= $display_email ?></span>
        </div>

        <div class="profile-dropdown__divider"></div>

        <a href="logout.php" class="logout-button">
            <i data-lucide="log-out"></i>
            Logout
        </a>
    </div>
</div>

    </header>

    <main class="main-content">

        <section class="greeting">

            <div class="greeting__text">

                <h1>
                    Order Up, Bro <?= $display_first_name ?>!
                </h1>

                <p>
                    New to Brewski? Let our platform recommend
                    your preferred drinks!
                    <br>
                    Click the question mark button.
                </p>

            </div>

            <button
                type="button"
                class="greeting__help"
                aria-label="Help"
            >
                <i data-lucide="help-circle"></i>
            </button>

        </section>

        <section class="category">

            <h2 class="category__title">
                Best sellers
            </h2>

            <div class="product-grid">

                <div
                    class="product-card"
                    data-name="Black Coffee"
                    data-base-price="170"
                >

                    <div class="product-card__image">

                        <img
                            src="blackcoffee.png"
                            alt="Black Coffee"
                        >

                    </div>

                    <div class="product-card__info">

                        <h3>
                            Black Coffee
                        </h3>

                        <p class="price">
                            ₱170
                        </p>

                        <button
                            type="button"
                            class="btn-add"
                        >

                            <i data-lucide="plus-circle"></i>

                            Add to Cart

                        </button>

                    </div>

                </div>

                <div
                    class="product-card"
                    data-name="Caramel Macchiato"
                    data-base-price="180"
                >

                    <div class="product-card__image">

                        <img
                            src="caramel.png"
                            alt="Caramel Macchiato"
                        >

                    </div>

                    <div class="product-card__info">

                        <h3>
                            Caramel Macchiato
                        </h3>

                        <p class="price">
                            ₱180
                        </p>

                        <button
                            type="button"
                            class="btn-add"
                        >

                            <i data-lucide="plus-circle"></i>

                            Add to Cart

                        </button>

                    </div>

                </div>

                <div
                    class="product-card"
                    data-name="Cafe Latte"
                    data-base-price="170"
                >

                    <div class="product-card__image">

                        <img
                            src="cafelatte.png"
                            alt="Cafe Latte"
                        >

                    </div>

                    <div class="product-card__info">

                        <h3>
                            Cafe Latte
                        </h3>

                        <p class="price">
                            ₱170
                        </p>

                        <button
                            type="button"
                            class="btn-add"
                        >

                            <i data-lucide="plus-circle"></i>

                            Add to Cart

                        </button>

                    </div>

                </div>

                <div
                    class="product-card"
                    data-name="Black Coffee"
                    data-base-price="170"
                >

                    <div class="product-card__image">

                        <img
                            src="blackcoffee.png"
                            alt="Black Coffee"
                        >

                    </div>

                    <div class="product-card__info">

                        <h3>
                            Black Coffee
                        </h3>

                        <p class="price">
                            ₱170
                        </p>

                        <button
                            type="button"
                            class="btn-add"
                        >

                            <i data-lucide="plus-circle"></i>

                            Add to Cart

                        </button>

                    </div>

                </div>

            </div>

        </section>

        <section class="category">

            <h2 class="category__title">
                Most Popular
            </h2>

            <div class="product-grid">

                <div
                    class="product-card"
                    data-name="Iced Coffee"
                    data-base-price="160"
                >

                    <div class="product-card__image">

                        <img
                            src="icedcoffee.png"
                            alt="Iced Coffee"
                        >

                    </div>

                    <div class="product-card__info">

                        <h3>
                            Iced Coffee
                        </h3>

                        <p class="price">
                            ₱160
                        </p>

                        <button
                            type="button"
                            class="btn-add"
                        >

                            <i data-lucide="plus-circle"></i>

                            Add to Cart

                        </button>

                    </div>

                </div>

                <div
                    class="product-card"
                    data-name="Cafe Mocha"
                    data-base-price="185"
                >

                    <div class="product-card__image">

                        <img
                            src="mocha.png"
                            alt="Cafe Mocha"
                        >

                    </div>

                    <div class="product-card__info">

                        <h3>
                            Cafe Mocha
                        </h3>

                        <p class="price">
                            ₱185
                        </p>

                        <button
                            type="button"
                            class="btn-add"
                        >

                            <i data-lucide="plus-circle"></i>

                            Add to Cart

                        </button>

                    </div>

                </div>

                <div
                    class="product-card"
                    data-name="Double Espresso"
                    data-base-price="150"
                >

                    <div class="product-card__image">

                        <img
                            src="doubleepresso.png"
                            alt="Double Espresso"
                        >

                    </div>

                    <div class="product-card__info">

                        <h3>
                            Double Espresso
                        </h3>

                        <p class="price">
                            ₱150
                        </p>

                        <button
                            type="button"
                            class="btn-add"
                        >

                            <i data-lucide="plus-circle"></i>

                            Add to Cart

                        </button>

                    </div>

                </div>

                <div
                    class="product-card"
                    data-name="Matcha Latte"
                    data-base-price="190"
                >

                    <div class="product-card__image">

                        <img
                            src="matchalatte.png"
                            alt="Matcha Latte"
                        >

                    </div>

                    <div class="product-card__info">

                        <h3>
                            Matcha Latte
                        </h3>

                        <p class="price">
                            ₱190
                        </p>

                        <button
                            type="button"
                            class="btn-add"
                        >

                            <i data-lucide="plus-circle"></i>

                            Add to Cart

                        </button>

                    </div>

                </div>

            </div>

        </section>

    </main>

    <div
        class="modal-overlay"
        id="customization-modal"
    >

        <div class="modal">

            <div class="modal__header">

                <div>

                    <h2 id="modal-product-name">
                        Customize your drink
                    </h2>

                    <p class="modal__subtitle">
                        Make it your own
                    </p>

                </div>

                <button
                    type="button"
                    class="modal__close"
                    id="modal-close"
                    aria-label="Close customization"
                >

                    <i data-lucide="x"></i>

                </button>

            </div>

            <div class="modal__body">

                <div class="option-group">

                    <h3>
                        Temperature
                    </h3>

                    <div
                        class="option-buttons"
                        data-group="temp"
                    >

                        <button
                            type="button"
                            class="option-btn active"
                            data-value="Hot"
                            data-price="0"
                        >

                            <i data-lucide="flame"></i>

                            Hot

                        </button>

                        <button
                            type="button"
                            class="option-btn"
                            data-value="Iced"
                            data-price="0"
                        >

                            <i data-lucide="snowflake"></i>

                            Iced

                        </button>

                    </div>

                </div>

                <div class="option-group">

                    <h3>
                        Size
                    </h3>

                    <div
                        class="option-buttons"
                        data-group="size"
                    >

                        <button
                            type="button"
                            class="option-btn active"
                            data-value="Regular"
                            data-price="0"
                        >
                            Regular
                        </button>

                        <button
                            type="button"
                            class="option-btn"
                            data-value="Large"
                            data-price="20"
                        >
                            Large (+₱20)
                        </button>

                    </div>

                </div>

                <div class="option-group">

                    <h3>
                        Sugar Level
                    </h3>

                    <div
                        class="option-buttons"
                        data-group="sugar"
                    >

                        <button
                            type="button"
                            class="option-btn active"
                            data-value="100%"
                            data-price="0"
                        >
                            100%
                        </button>

                        <button
                            type="button"
                            class="option-btn"
                            data-value="75%"
                            data-price="0"
                        >
                            75%
                        </button>

                        <button
                            type="button"
                            class="option-btn"
                            data-value="50%"
                            data-price="0"
                        >
                            50%
                        </button>

                        <button
                            type="button"
                            class="option-btn"
                            data-value="25%"
                            data-price="0"
                        >
                            25%
                        </button>

                        <button
                            type="button"
                            class="option-btn"
                            data-value="0%"
                            data-price="0"
                        >
                            0%
                        </button>

                    </div>

                </div>

                <div class="option-group">

                    <h3>
                        Add-ons
                    </h3>

                    <div
                        class="option-buttons"
                        data-group="addons"
                    >

                        <button
                            type="button"
                            class="option-btn"
                            data-value="Extra Shot"
                            data-price="30"
                        >
                            Extra Shot (+₱30)
                        </button>

                        <button
                            type="button"
                            class="option-btn"
                            data-value="Oat Milk"
                            data-price="25"
                        >
                            Oat Milk (+₱25)
                        </button>

                        <button
                            type="button"
                            class="option-btn"
                            data-value="Whipped Cream"
                            data-price="15"
                        >
                            Whipped Cream (+₱15)
                        </button>

                    </div>

                </div>

                <div class="option-group">

                    <h3>
                        Special Instructions
                    </h3>

                    <textarea
                        id="special-instructions"
                        class="special-instructions"
                        placeholder="e.g., Less ice, extra hot, no foam..."
                        rows="3"
                    ></textarea>

                </div>

            </div>

            <div class="modal__footer">

                <div class="modal__total">

                    <span class="modal__total-label">
                        Total
                    </span>

                    <span id="modal-total-price">
                        ₱170
                    </span>

                </div>

                <button
                    type="button"
                    class="btn-confirm"
                    id="btn-confirm-add"
                >

                    Add to Cart

                    <i data-lucide="arrow-right"></i>

                </button>

            </div>

        </div>

    </div>

    <script src="script.js"></script>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>
