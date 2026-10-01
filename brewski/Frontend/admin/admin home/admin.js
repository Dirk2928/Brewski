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
    var staffParent = document.getElementById('staffParent');
    var staffSubmenu = document.getElementById('staffSubmenu');

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
        // If a sub-item is clicked, highlight its matching parent.
        if (clickedBtn && clickedBtn.classList.contains('nav-subitem')) {
            var parent = clickedBtn.closest('.submenu') === customerSubmenu
                ? customerParent
                : staffParent;

            if (parent) parent.classList.add('active');
        } else {
            customerParent.classList.remove('active');
            staffParent.classList.remove('active');
        }
    }

    /**
     * Re-creates every <script> inside a container so the browser actually runs it.
     * Assigning HTML through innerHTML leaves <script> tags inert, so a loaded page's
     * own behaviour (filters, bulk actions) would silently never bind without this.
     * @param {HTMLElement} container - The element whose scripts should be executed
     */
    function runScripts(container) {
        container.querySelectorAll('script').forEach(function (oldScript) {
            var newScript = document.createElement('script');

            // Carry over attributes (src, type, etc.) so both inline and external scripts work
            Array.prototype.forEach.call(oldScript.attributes, function (attr) {
                newScript.setAttribute(attr.name, attr.value);
            });

            newScript.textContent = oldScript.textContent;

            // Replacing the node re-parses it, which is what triggers execution
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });
    }

    // The dialog currently on screen, if any. Tracked so navigating to another
    // view can tear it down - modals are appended to <body>, so clearing
    // #dynamicView would leave them floating over the next page.
    var activeModal = null;

    /**
     * Opens a modal dialog and returns a handle that closes it again.
     * Built on demand so a page loaded into #dynamicView can ask for a dialog
     * without carrying its own markup, and torn down completely on close so
     * listeners don't pile up as the admin moves between views.
     * @param {{title: string, body: (string|HTMLElement), footer?: Array}} options
     *        Each footer entry takes {label, className, onClick, closeOnClick}.
     *        An onClick returning false keeps the dialog open (failed validation).
     * @returns {{close: Function, element: HTMLElement}}
     */
    function openModal(options) {
        // Never stack dialogs - replacing keeps focus and listeners predictable
        if (activeModal) {
            activeModal.close();
        }

        var backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop';

        var modal = document.createElement('div');
        modal.className = 'modal';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');

        var previouslyFocused = document.activeElement;
        var handle = { close: close, element: modal };

        function close() {
            document.removeEventListener('keydown', handleKeydown);
            backdrop.remove();

            if (activeModal === handle) {
                activeModal = null;
            }

            if (previouslyFocused && typeof previouslyFocused.focus === 'function') {
                previouslyFocused.focus();
            }
        }

        function handleKeydown(event) {
            if (event.key === 'Escape') {
                close();
            }
        }

        // --- Header ---
        var header = document.createElement('div');
        header.className = 'modal-header';

        var title = document.createElement('h2');
        title.className = 'modal-title';
        title.textContent = options.title || '';

        var closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'modal-close';
        closeBtn.setAttribute('aria-label', 'Close dialog');
        closeBtn.innerHTML = '&times;';
        closeBtn.addEventListener('click', close);

        header.appendChild(title);
        header.appendChild(closeBtn);

        // --- Body ---
        var body = document.createElement('div');
        body.className = 'modal-body';

        if (typeof options.body === 'string') {
            body.innerHTML = options.body;
        } else if (options.body) {
            body.appendChild(options.body);
        }

        modal.appendChild(header);
        modal.appendChild(body);

        // --- Footer ---
        if (options.footer && options.footer.length) {
            var footer = document.createElement('div');
            footer.className = 'modal-footer';

            options.footer.forEach(function (buttonConfig) {
                var button = document.createElement('button');
                button.type = 'button';
                button.className = 'btn ' + (buttonConfig.className || 'btn-secondary');
                button.textContent = buttonConfig.label;

                button.addEventListener('click', function () {
                    var shouldClose = buttonConfig.onClick
                        ? buttonConfig.onClick(handle) !== false
                        : true;

                    if (shouldClose && buttonConfig.closeOnClick !== false) {
                        close();
                    }
                });

                footer.appendChild(button);
            });

            modal.appendChild(footer);
        }

        backdrop.appendChild(modal);

        // Clicking the dimmed area (but not the dialog itself) dismisses it
        backdrop.addEventListener('click', function (event) {
            if (event.target === backdrop) {
                close();
            }
        });

        document.body.appendChild(backdrop);
        document.addEventListener('keydown', handleKeydown);

        // Prefer the first form field, falling back to the close button
        var focusTarget = modal.querySelector('.modal-body input, .modal-body select, .modal-body textarea')
            || modal.querySelector('button');

        if (focusTarget) {
            focusTarget.focus();
        }

        activeModal = handle;

        return handle;
    }

    // Exposed so the pages fetched into #dynamicView can open dialogs
    window.openAdminModal = openModal;

    /**
     * Loads content dynamically via Fetch API
     * @param {string} viewIdentifier - Either "home" or a relative file path
     */
    function loadView(viewIdentifier) {
        // A dialog opened by the outgoing page lives on <body>, so it would
        // otherwise survive the swap and hover over the next view.
        if (activeModal) {
            activeModal.close();
        }

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

                    // innerHTML alone won't execute the page's scripts - do it explicitly
                    runScripts(dynamicView);

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

    // 6. Staff Management Submenu Toggle
    if (staffParent) {
        staffParent.addEventListener('click', function () {
            var isOpen = staffSubmenu.classList.toggle('open');
            staffParent.setAttribute('aria-expanded', String(isOpen));
        });
    }

    // 7. Main Navigation Click Handler (The Core Logic)
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