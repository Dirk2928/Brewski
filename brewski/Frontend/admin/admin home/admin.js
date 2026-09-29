document.addEventListener('DOMContentLoaded', function () {

    // --- DOM ELEMENTS ---
    var menuBtn = document.getElementById('menuBtn');
    var sidebar = document.getElementById('sidebar');
    var profileBtn = document.getElementById('profileBtn');
    var profileMenu = document.getElementById('profileMenu');
    
    // Navigation Items (Buttons with data-view)
    var navItems = document.querySelectorAll('.nav-item[data-view]');
    
    // Content Containers
    var homeView = document.getElementById('homeView');
    var dynamicView = document.getElementById('dynamicView'); // The new loader target
    var placeholderView = document.getElementById('placeholderView');
    var placeholderTitle = document.getElementById('placeholderTitle');
    
    // Submenu Elements
    var customerParent = document.getElementById('customerParent');
    var customerSubmenu = document.getElementById('customerSubmenu');

    // --- HELPER FUNCTIONS ---

    /**
     * Opens the mobile/desktop sidebar
     */
    function openMenu() {
        sidebar.classList.add('open');
        document.body.classList.add('sidebar-open');
        menuBtn.setAttribute('aria-expanded', 'true');
    }

    /**
     * Closes the mobile/desktop sidebar
     */
    function closeMenu() {
        sidebar.classList.remove('open');
        document.body.classList.remove('sidebar-open');
        menuBtn.setAttribute('aria-expanded', 'false');
    }

    /**
     * Updates the active state of navigation items
     * @param {HTMLElement} clickedBtn - The button that was clicked
     */
    function updateActiveNav(clickedBtn) {
        // Remove active class from ALL nav items first
        document.querySelectorAll('.nav-item').forEach(function (b) {
            b.classList.remove('active');
        });

        // Add active class to the clicked item
        if (clickedBtn) {
            clickedBtn.classList.add('active');
        }

        // Logic for Parent Highlighting
        // If a sub-item is clicked, highlight its parent ("Customer Management")
        if (clickedBtn && clickedBtn.classList.contains('nav-subitem')) {
            customerParent.classList.add('active');
        } else {
            // Otherwise, ensure parent is NOT highlighted
            customerParent.classList.remove('active');
        }
    }

    /**
     * Loads content dynamically via Fetch API
     * @param {string} viewIdentifier - Either "home" or a relative file path
     */
    function loadView(viewIdentifier) {
        // Reset views: Hide everything except the one we are about to show
        
        // Case 1: Static Home Page
        if (viewIdentifier === 'home') {
            homeView.classList.remove('hidden');
            dynamicView.classList.add('hidden');
            placeholderView.classList.add('hidden');
            
            // Clear any previously loaded dynamic content to save memory/prevent conflicts
            dynamicView.innerHTML = ''; 
            return;
        }

        // Case 2: Dynamic Content (PHP Files)
        // Check if identifier looks like a file path (contains .php or ../)
        if (viewIdentifier.includes('.php') || viewIdentifier.startsWith('../')) {
            
            // Show Loading State in Dynamic View
            dynamicView.innerHTML = '<div style="padding: 20px; text-align: center;"><p>Loading...</p></div>';
            
            // Hide other views
            homeView.classList.add('hidden');
            placeholderView.classList.add('hidden');
            dynamicView.classList.remove('hidden');

            // Perform Fetch Request
            fetch(viewIdentifier)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text(); // Get raw HTML string
                })
                .then(html => {
                    // Inject the fetched HTML into the container
                    dynamicView.innerHTML = html;
                    
                    // Optional: Scroll to top of main content area after load
                    window.scrollTo(0, 0);
                })
                .catch(error => {
                    console.error('Error loading view:', error);
                    // Show Error Message in Dynamic View
                    dynamicView.innerHTML = `
                        <div style="padding: 20px; color: #dc2626;">
                            <h2>Error Loading Content</h2>
                            <p>${error.message}</p>
                            <button onclick="location.reload()" style="margin-top:10px; padding:8px 16px; cursor:pointer;">Reload Page</button>
                        </div>
                    `;
                });
        } 
        // Case 3: Placeholder / Unimplemented Features
        else {
            homeView.classList.add('hidden');
            dynamicView.classList.add('hidden');
            placeholderView.classList.remove('hidden');
            
            // Set title based on the identifier (fallback)
            placeholderTitle.textContent = viewIdentifier.charAt(0).toUpperCase() + viewIdentifier.slice(1);
        }
    }

    // --- EVENT LISTENERS ---

    // 1. Sidebar Toggle (Hamburger Menu)
    menuBtn.addEventListener('click', function (event) {
        event.stopPropagation();
        if (sidebar.classList.contains('open')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    // 2. Close Sidebar when clicking outside
    document.addEventListener('click', function (event) {
        var isOpen = sidebar.classList.contains('open');
        var clickedInsideSidebar = sidebar.contains(event.target);
        var clickedMenuBtn = menuBtn.contains(event.target);

        if (isOpen && !clickedInsideSidebar && !clickedMenuBtn) {
            closeMenu();
        }
    });

    // 3. Profile Dropdown Toggle
    profileBtn.addEventListener('click', function (event) {
        event.stopPropagation();
        var isHidden = profileMenu.classList.toggle('hidden');
        profileBtn.setAttribute('aria-expanded', String(!isHidden));
    });

    // 4. Close Profile Dropdown when clicking outside
    document.addEventListener('click', function (event) {
        if (!profileBtn.contains(event.target) && !profileMenu.contains(event.target)) {
            profileMenu.classList.add('hidden');
            profileBtn.setAttribute('aria-expanded', 'false');
        }
    });

    // 5. Customer Management Submenu Toggle
    if (customerParent) {
        customerParent.addEventListener('click', function () {
            var isOpen = customerSubmenu.classList.toggle('open');
            customerParent.setAttribute('aria-expanded', String(isOpen));
        });
    }

    // 6. Main Navigation Click Handler (The Core Logic)
    navItems.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default link behavior if any remain

            // Get the target view from the data attribute
            var viewTarget = btn.getAttribute('data-view');

            // Update UI Active States
            updateActiveNav(btn);

            // Load the Content
            loadView(viewTarget);

            // Auto-close sidebar on mobile devices after selection
            if (window.innerWidth <= 768) {
                closeMenu();
            }
        });
    });

});