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
            
            <!-- Product Management (Placeholder for now) -->
            <button type="button" class="nav-item" data-view="products">Product Management</button>

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

                <button type="button" class="nav-item nav-subitem" data-view="../customer%20information/authentication_logs.php">
                    Authentication Logs
                </button>
            </div>

            <!-- Other Placeholders -->
            <button type="button" class="nav-item" data-view="staff">Staff Management</button>
            <button type="button" class="nav-item" data-view="analytics">Analytics</button>
            <button type="button" class="nav-item" data-view="logs">Activity Logs</button>
            <button type="button" class="nav-item" data-view="profile">Profile</button>
        </nav>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <main class="main-content" id="mainContent">
        
        <!-- 1. Static Home View (Shown by default) -->
        <section id="homeView">
            <h1>Welcome, @Admin</h1>
            <p class="subtitle">This page is for the overview of Brewski store activity.</p>

            <div class="stats-grid">
                <div class="stat-card">
                    <p class="stat-label">Total Customers</p>
                    <p class="stat-value">--</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Today's Sales</p>
                    <p class="stat-value">--</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Pending Orders</p>
                    <p class="stat-value">--</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Low Stock Items</p>
                    <p class="stat-value">--</p>
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