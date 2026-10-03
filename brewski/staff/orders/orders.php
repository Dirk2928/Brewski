<?php

session_start();

/* Auth guard - commented out while this page is still a design shell.
   Uncomment this whole block before the page is exposed to real users.

if (!isset($_SESSION['user_id'])) {

    header('Location: ../../login-signup/login.php');
    exit;
}

$role = strtoupper(trim((string) ($_SESSION['role'] ?? '')));

if ($role !== 'STAFF' && $role !== 'ADMIN') {

    header('Location: ../../login-signup/login.php');
    exit;
}

*/

$is_signed_in = isset($_SESSION['user_id']);

$first_name = $_SESSION['first_name'] ?? '';
$last_name  = $_SESSION['last_name'] ?? '';

$full_name = trim($first_name . ' ' . $last_name);

if ($full_name === '') {
    $full_name = 'Staff';
}

$display_name = htmlspecialchars(
    $full_name,
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

    <title>Brewski Staff</title>

    <link
        rel="stylesheet"
        href="../staff.css?v=<?= filemtime(__DIR__ . '/../staff.css') ?>"
    >

</head>

<body>

    <header class="topbar">

        <button
            type="button"
            id="menuBtn"
            class="icon-btn menu-btn"
            aria-label="Open staff menu"
            aria-expanded="false"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

        <span class="topbar-title">
            Brewski Staff
        </span>

        <div class="profile-wrapper">

            <button
                type="button"
                id="profileBtn"
                class="icon-btn profile-btn"
                aria-label="Open profile menu"
                aria-expanded="false"
            ></button>

            <div id="profileMenu" class="profile-menu hidden">

                <button type="button" class="profile-menu-item">
                    Profile
                </button>

                <a
                    href="../../login-signup/logout.php"
                    class="profile-menu-item border-top"
                >
                    Log out
                </a>

            </div>

        </div>

    </header>

    <aside id="sidebar" class="sidebar">

        <nav class="sidebar-nav">

            <button
                type="button"
                class="nav-item active"
                data-view="orders"
            >
                Orders
            </button>

            <button
                type="button"
                class="nav-item"
                data-view="products"
            >
                Products
            </button>

            <button
                type="button"
                class="nav-item"
                data-view="../order_history/order_history.php"
            >
                Order History
            </button>

            <button
                type="button"
                class="nav-item"
                data-view="profile"
            >
                Profile
            </button>

        </nav>

    </aside>

    <main class="main-content" id="mainContent">

        <section id="ordersView">

            <div class="page-container">

                <div class="page-header">

                    <h1 class="page-title">
                        Orders
                    </h1>

                    <p class="subtitle">
                        <?php if ($is_signed_in): ?>Signed in as <?= $display_name ?>. <?php endif; ?>Track incoming orders
                        and update their status.
                    </p>

                </div>

                <div class="table-card">

                    <div class="empty-state">

                        <p class="empty-title">
                            No orders to show yet
                        </p>

                        <p class="empty-hint">
                            Orders placed by customers will appear here.
                        </p>

                    </div>

                </div>

            </div>

        </section>

        <section id="dynamicView" class="hidden"></section>

        <section id="placeholderView" class="hidden">

            <h1 id="placeholderTitle">
                Page Not Found
            </h1>

            <p class="subtitle">
                This section has not been implemented yet.
            </p>

        </section>

    </main>

    <script
        src="../staff.js?v=<?= filemtime(__DIR__ . '/../staff.js') ?>"
        defer
    ></script>

</body>

</html>
