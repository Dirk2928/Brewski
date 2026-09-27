<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Brewski Admin Dashboard</title>
<style>
    :root {
        --sidebar-width: clamp(220px, 12.5vw, 320px);
        --transition-speed: 0.2s;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #ffffff;
        color: #000000;
        overflow-x: hidden;
    }

    .hidden {
        display: none !important;
    }

    /* Top bar */
    .topbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 16px;
        background-color: #ffffff;
        border-bottom: 1px solid #d1d5db;
        z-index: 30;
    }

    .icon-btn {
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        cursor: pointer;
    }

    .icon-btn:hover {
        background-color: #f3f4f6;
    }

    .icon-btn:focus-visible {
        outline: 2px solid #000000;
        outline-offset: 2px;
    }

    .menu-btn {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        flex-shrink: 0;
    }

    .menu-btn span {
        display: block;
        width: 20px;
        height: 2px;
        background-color: #000000;
    }

    .topbar-title {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
    }

    .profile-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .profile-btn {
        width: 40px;
        height: 40px;
        border-radius: 9999px;
        border: 1px solid #9ca3af;
    }

    .profile-menu {
        position: absolute;
        right: 0;
        margin-top: 8px;
        width: 176px;
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .profile-menu-item {
        display: block;
        width: 100%;
        padding: 8px 16px;
        text-align: left;
        font-size: 14px;
        color: #000000;
        background-color: #ffffff;
        border: none;
        cursor: pointer;
    }

    .profile-menu-item:hover {
        background-color: #f3f4f6;
    }

    .profile-menu-item.border-top {
        border-top: 1px solid #e5e7eb;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: var(--sidebar-width);
        padding-top: 64px;
        background-color: #ffffff;
        border-right: 1px solid #d1d5db;
        transform: translateX(-100%);
        transition: transform var(--transition-speed) ease-in-out;
        z-index: 30;
    }

    .sidebar.open {
        transform: translateX(0);
    }

    .sidebar-nav {
        display: flex;
        flex-direction: column;
    }

    .nav-item {
        padding: 12px 20px;
        text-align: left;
        font-size: 14px;
        color: #374151;
        background-color: #ffffff;
        border: none;
        border-bottom: 1px solid #e5e7eb;
        cursor: pointer;
    }

    .nav-item:hover {
        background-color: #f9fafb;
    }

    .nav-item:focus-visible {
        outline: 2px solid #000000;
        outline-offset: -2px;
    }

    .nav-item.active {
        background-color: #f3f4f6;
        font-weight: 500;
        color: #000000;
    }

    /* Main content: pushed over (not covered) when the sidebar is open */
    .main-content {
        padding: 96px 24px 40px;
        margin-left: 0;
        transition: margin-left var(--transition-speed) ease-in-out;
    }

    body.sidebar-open .main-content {
        margin-left: var(--sidebar-width);
    }

    .main-content h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        color: #000000;
    }

    .subtitle {
        margin-top: 4px;
        font-size: 14px;
        color: #4b5563;
    }

    .stats-grid {
        margin-top: 24px;
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
    }

    @media (min-width: 640px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .stat-card {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 16px;
    }

    .stat-label {
        margin: 0;
        font-size: 14px;
        color: #6b7280;
    }

    .stat-value {
        margin: 8px 0 0;
        font-size: 24px;
        font-weight: 600;
        color: #000000;
    }
</style>
</head>
<body>

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

    <aside id="sidebar" class="sidebar">
        <nav class="sidebar-nav">
            <button type="button" class="nav-item active" data-item="Home">Home</button>
            <button type="button" class="nav-item" data-item="Product Management">Product Management</button>
            <button type="button" class="nav-item" data-item="Customer Management">Customer Management</button>
            <button type="button" class="nav-item" data-item="Staff Management">Staff Management</button>
            <button type="button" class="nav-item" data-item="Analytics">Analytics</button>
            <button type="button" class="nav-item" data-item="Activity Logs">Activity Logs</button>
            <button type="button" class="nav-item" data-item="Profile">Profile</button>
        </nav>
    </aside>

    <main class="main-content" id="mainContent">
        <section id="homeView">
            <h1>Welcome, @Admin</h1>
            <p class="subtitle">This page is for the overview of Brewski store activity.</p>

            <div class="stats-grid">
                <div class="stat-card">
                    <p class="stat-label">Something1</p>
                    <p class="stat-value">--</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Something2</p>
                    <p class="stat-value">--</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Something3</p>
                    <p class="stat-value">--</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Something4</p>
                    <p class="stat-value">--</p>
                </div>
            </div>
        </section>

        <section id="placeholderView" class="hidden">
            <h1 id="placeholderTitle"></h1>
            <p class="subtitle">This page has not been built yet.</p>
        </section>
    </main>

<script>
    var menuBtn = document.getElementById('menuBtn');
    var sidebar = document.getElementById('sidebar');
    var profileBtn = document.getElementById('profileBtn');
    var profileMenu = document.getElementById('profileMenu');
    var navItems = document.querySelectorAll('.nav-item');
    var homeView = document.getElementById('homeView');
    var placeholderView = document.getElementById('placeholderView');
    var placeholderTitle = document.getElementById('placeholderTitle');

    function openMenu() {
        sidebar.classList.add('open');
        document.body.classList.add('sidebar-open');
        menuBtn.setAttribute('aria-expanded', 'true');
    }

    function closeMenu() {
        sidebar.classList.remove('open');
        document.body.classList.remove('sidebar-open');
        menuBtn.setAttribute('aria-expanded', 'false');
    }

    menuBtn.addEventListener('click', function (event) {
        event.stopPropagation();
        if (sidebar.classList.contains('open')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    // Close the sidebar when clicking anywhere outside of it.
    document.addEventListener('click', function (event) {
        var isOpen = sidebar.classList.contains('open');
        var clickedInsideSidebar = sidebar.contains(event.target);
        var clickedMenuBtn = menuBtn.contains(event.target);

        if (isOpen && !clickedInsideSidebar && !clickedMenuBtn) {
            closeMenu();
        }
    });

    profileBtn.addEventListener('click', function (event) {
        event.stopPropagation();
        var isHidden = profileMenu.classList.toggle('hidden');
        profileBtn.setAttribute('aria-expanded', String(!isHidden));
    });

    document.addEventListener('click', function (event) {
        if (!profileBtn.contains(event.target) && !profileMenu.contains(event.target)) {
            profileMenu.classList.add('hidden');
            profileBtn.setAttribute('aria-expanded', 'false');
        }
    });

    navItems.forEach(function (btn) {
        btn.addEventListener('click', function () {
            navItems.forEach(function (b) {
                b.classList.remove('active');
            });
            btn.classList.add('active');

            var label = btn.getAttribute('data-item');

            if (label === 'Home') {
                homeView.classList.remove('hidden');
                placeholderView.classList.add('hidden');
            } else {
                homeView.classList.add('hidden');
                placeholderView.classList.remove('hidden');
                placeholderTitle.textContent = label;
            }

            closeMenu();
        });
    });
</script>

</body>
</html>