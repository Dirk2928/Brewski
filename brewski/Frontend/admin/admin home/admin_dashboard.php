<?php
session_start();

$adminName = $_SESSION['admin_name'] ?? 'Admin';

$stats = [
    'total_customers' => 0,
    'today_sales' => 0,
    'pending_orders' => 0,
    'low_stock' => 0,
];

$mysqli = null;
$hasDbConnection = false;

try {
    $mysqli = new mysqli('localhost', 'root', '', 'brewski_db');
    $hasDbConnection = true;

    $customerResult = $mysqli->query("SELECT COUNT(*) AS total FROM users WHERE role = 'CUSTOMER'");
    if ($customerResult && $customerResult->num_rows > 0) {
        $stats['total_customers'] = (int) $customerResult->fetch_assoc()['total'];
    }

    $salesResult = $mysqli->query("SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders WHERE DATE(order_date) = CURDATE()");
    if ($salesResult && $salesResult->num_rows > 0) {
        $stats['today_sales'] = (float) $salesResult->fetch_assoc()['total'];
    }

    $pendingResult = $mysqli->query("SELECT COUNT(*) AS total FROM orders WHERE order_status = 'PENDING'");
    if ($pendingResult && $pendingResult->num_rows > 0) {
        $stats['pending_orders'] = (int) $pendingResult->fetch_assoc()['total'];
    }

    $stockResult = $mysqli->query("SELECT COUNT(*) AS total FROM products WHERE stock < 10");
    if ($stockResult && $stockResult->num_rows > 0) {
        $stats['low_stock'] = (int) $stockResult->fetch_assoc()['total'];
    }
} catch (Exception $e) {
    $hasDbConnection = false;
}

if ($mysqli) {
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brewski Admin Dashboard</title>
    <!-- The ?v= stamps are the file mtimes. Without them the browser happily
         serves a cached admin.css/admin.js after an edit, so a fixed stylesheet
         still renders as the old broken one until a manual hard refresh. -->
    <link rel="stylesheet" href="../admin.css?v=<?= filemtime(__DIR__ . '/../admin.css') ?>">
</head>
<body>

    <!-- TOP BAR -->
    <header class="topbar">
        <button type="button" id="menuBtn" class="icon-btn menu-btn" aria-label="Open admin menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <span class="topbar-title">Brewski Admin</span>

        <div class="profile-wrapper">
            <button type="button" id="profileBtn" class="icon-btn profile-btn" aria-label="Open profile menu" aria-expanded="false"></button>

            <div id="profileMenu" class="profile-menu hidden">
                <button type="button" class="profile-menu-item">Profile</button>
                <button type="button" class="profile-menu-item border-top">Log out</button>
            </div>
        </div>
    </header>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar">
        <nav class="sidebar-nav">
            <!-- Home Button (Static) -->
            <button type="button" class="nav-item active" data-view="home">Home</button>
            
            <!-- Product Management -->
            <button type="button" class="nav-item" data-view="../product%20management/product_management.php">
                Product Management
            </button>

            <!-- Customer Management Parent Toggle -->
            <button type="button" class="nav-item nav-parent" id="customerParent" aria-expanded="false" aria-controls="customerSubmenu">
                Customer Management
            </button>
            
            <!-- Submenu Items (Dynamic Loaders) -->
            <div class="submenu" id="customerSubmenu">
                <!-- Note: Paths are resolved by fetch() relative to THIS document (admin home/),
                     so they must match the folder names on disk. %20 is the space in
                     "customer information". -->
                <button type="button" class="nav-item nav-subitem" data-view="../customer%20information/customer_information.php">
                    Customer Information
                </button>

                <button type="button" class="nav-item nav-subitem" data-view="../customer%20information/transactions.php">
                    Transactions
                </button>

            </div>

            <!-- Staff Management Parent Toggle -->
            <button type="button" class="nav-item nav-parent" id="staffParent" aria-expanded="false" aria-controls="staffSubmenu">
                Staff Management
            </button>

            <!-- Staff Submenu Items (Dynamic Loaders) -->
            <div class="submenu" id="staffSubmenu">
                <button type="button" class="nav-item nav-subitem" data-view="../staff%20information/staff_information.php">
                    Staff Information
                </button>

                <button type="button" class="nav-item nav-subitem" data-view="../staff%20information/transactions_handled.php">
                    Transactions Handled
                </button>
            </div>

            <button type="button" class="nav-item" data-view="../logs/authentication_logs.php">Authentication Logs</button>

            <button type="button" class="nav-item" data-view="profile">Profile</button>
        </nav>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <main class="main-content" id="mainContent">
        
        <!-- 1. Static Home View (Shown by default) -->
        <section id="homeView">
            <h1>Welcome, @<?= htmlspecialchars($adminName) ?></h1>
            <p class="subtitle">This page is for the overview of Brewski store activity.</p>

            <div class="stats-grid">
                <div class="stat-card">
                    <p class="stat-label">Total Customers</p>
                    <p class="stat-value"><?= $hasDbConnection ? number_format($stats['total_customers']) : '--' ?></p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Today's Sales</p>
                    <p class="stat-value"><?= $hasDbConnection ? '₱' . number_format($stats['today_sales'], 2) : '--' ?></p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Pending Orders</p>
                    <p class="stat-value"><?= $hasDbConnection ? number_format($stats['pending_orders']) : '--' ?></p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Low Stock Items</p>
                    <p class="stat-value"><?= $hasDbConnection ? number_format($stats['low_stock']) : '--' ?></p>
                </div>
            </div>
        </section>

        <!-- 2. Dynamic View Container (Hidden initially, filled by JS) -->
        <section id="dynamicView" class="hidden">
            <!-- Content from customer_information.php etc. will be injected here -->
        </section>

        <!-- 3. Placeholder/Error View (Optional fallback) -->
        <section id="placeholderView" class="hidden">
            <h1 id="placeholderTitle">Page Not Found</h1>
            <p class="subtitle">This section has not been implemented yet.</p>
        </section>

    </main>

    <!-- Link External JS -->
    <!-- defer ensures HTML is loaded before script runs -->
    <script src="admin.js?v=<?= filemtime(__DIR__ . '/admin.js') ?>" defer></script>
</body>
</html>