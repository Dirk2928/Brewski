<?php

$products = [
    [
        'id' => 1,
        'name' => 'Black Coffee',
        'price' => 170,
        'category' => 'coffee',
        'temp' => 'hot',
        'image' => '../../images/blackcoffee.png'
    ],
    [
        'id' => 2,
        'name' => 'Caramel Macchiato',
        'price' => 180,
        'category' => 'coffee',
        'temp' => 'hot',
        'image' => '../../images/caramel.png'
    ],
    [
        'id' => 3,
        'name' => 'Cafe Latte',
        'price' => 170,
        'category' => 'coffee',
        'temp' => 'hot',
        'image' => '../../images/cafelatte.png'
    ],
    [
        'id' => 4,
        'name' => 'Double Espresso',
        'price' => 150,
        'category' => 'coffee',
        'temp' => 'hot',
        'image' => '../../images/doubleepresso.png'
    ],
    [
        'id' => 5,
        'name' => 'Iced Coffee',
        'price' => 160,
        'category' => 'coffee',
        'temp' => 'iced',
        'image' => '../../images/icedcoffee.png'
    ],

    [
        'id' => 6,
        'name' => 'Matcha Latte',
        'price' => 190,
        'category' => 'non-coffee',
        'temp' => 'hot',
        'image' => '../../images/matchalatte.png'
    ],
    [
        'id' => 7,
        'name' => 'Chocolate Drink',
        'price' => 175,
        'category' => 'non-coffee',
        'temp' => 'hot',
        'image' => '../../images/mocha.png'
    ],
    [
        'id' => 8,
        'name' => 'Strawberry Milk',
        'price' => 185,
        'category' => 'non-coffee',
        'temp' => 'iced',
        'image' => '../../images/cafelatte.png'
    ],

    [
        'id' => 9,
        'name' => 'Caramel Frappe',
        'price' => 210,
        'category' => 'frappe',
        'temp' => 'iced',
        'image' => '../../images/caramel.png'
    ],
    [
        'id' => 10,
        'name' => 'Mocha Frappe',
        'price' => 205,
        'category' => 'frappe',
        'temp' => 'iced',
        'image' => '../../images/mocha.png'
    ],
    [
        'id' => 11,
        'name' => 'Matcha Frappe',
        'price' => 215,
        'category' => 'frappe',
        'temp' => 'iced',
        'image' => '../../images/blackcoffee.png'
    ],

    [
        'id' => 12,
        'name' => 'Classic Milk Tea',
        'price' => 160,
        'category' => 'tea',
        'temp' => 'iced',
        'image' => '../../images/icedcoffee.png'
    ],
    [
        'id' => 13,
        'name' => 'Peach Iced Tea',
        'price' => 150,
        'category' => 'tea',
        'temp' => 'iced',
        'image' => '../../images/caramel.png'
    ],
    [
        'id' => 14,
        'name' => 'Hot Green Tea',
        'price' => 140,
        'category' => 'tea',
        'temp' => 'hot',
        'image' => '../../images/matchalatte.png'
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menu | brewski</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="menu.css">
    
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

    <header class="topbar">
        <div class="topbar__brand">Brewski</div>
        <nav class="topbar__nav">
            <a href="../customer_home/customerhome.php" class="topbar__link">
                <i data-lucide="home"></i> Home
            </a>
            <a href="customermenu.php" class="topbar__link active">
                <i data-lucide="coffee"></i> Menu
            </a>

            <div class="cart-menu" id="cart-menu">
                <button type="button" class="topbar__link cart-link cart-button" id="cart-button" aria-haspopup="true" aria-expanded="false">
                    <i data-lucide="shopping-cart"></i> Cart
                    <span class="cart-badge" data-cart-count>0</span>
                </button>

                <div class="cart-dropdown" id="cart-dropdown">
                    <h3 class="cart-dropdown__title">Your cart</h3>
                    <div id="cart-body"></div>
                </div>
            </div>

            <a href="orders.html" class="topbar__link">
                <i data-lucide="receipt"></i> Orders
            </a>
            <a href="profile.html" class="topbar__link">
                <i data-lucide="user"></i> Profile
            </a>
        </nav>
    </header>

    <main class="main-content">

        <section class="page-header">
            <div>
                <h1>Our Menu</h1>
                <p>Find your perfect brew from our selection.</p>
            </div>
        </section>

        <section class="filter-section">
            <div class="filter-group">
                <h3>Category</h3>
                <div class="filter-buttons" id="category-filters">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="coffee">Coffee</button>
                    <button class="filter-btn" data-filter="non-coffee">Non-Coffee</button>
                    <button class="filter-btn" data-filter="frappe">Frappe</button>
                    <button class="filter-btn" data-filter="tea">Tea</button>
                </div>
            </div>

            <div class="filter-group">
                <h3>Temperature</h3>
                <div class="filter-buttons" id="temp-filters">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="hot">Hot</button>
                    <button class="filter-btn" data-filter="iced">Iced</button>
                </div>
            </div>
        </section>

        <section class="category">
            <div class="product-grid" id="menu-grid">

                <?php foreach ($products as $product): ?>

                    <div class="product-card"
                         data-id="<?= (int) $product['id'] ?>"
                         data-name="<?= htmlspecialchars($product['name']) ?>"
                         data-image="<?= htmlspecialchars($product['image']) ?>"
                         data-base-price="<?= $product['price'] ?>"
                         data-category="<?= $product['category'] ?>"
                         data-temp="<?= $product['temp'] ?>">

                        <div class="product-card__image">
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                        <div class="product-card__info">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="price">₱<?= $product['price'] ?></p>
                            <button class="btn-add">
                                <i data-lucide="plus-circle"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div id="no-results" class="no-results" style="display: none;">
                <i data-lucide="coffee"></i>
                <p>No drinks found matching your filters.</p>
            </div>
        </section>

    </main>

    <div class="modal-overlay" id="customization-modal">
        <div class="modal">
            <div class="modal__header">
                <div>
                    <h2 id="modal-product-name">Customize your drink</h2>
                    <p class="modal__subtitle">Make it your own</p>
                </div>
                <button class="modal__close" id="modal-close">
                    <i data-lucide="x"></i>
                </button>
            </div>

            <div class="modal__body">

                <div class="option-group">
                    <h3>Temperature</h3>
                    <div class="option-buttons" data-group="temp">
                        <button class="option-btn active" data-value="Hot" data-price="0">
                            <i data-lucide="flame"></i> Hot
                        </button>
                        <button class="option-btn" data-value="Iced" data-price="0">
                            <i data-lucide="snowflake"></i> Iced
                        </button>
                    </div>
                </div>

                <div class="option-group">
                    <h3>Size</h3>
                    <div class="option-buttons" data-group="size">
                        <button class="option-btn active" data-value="Regular" data-price="0">Regular</button>
                        <button class="option-btn" data-value="Large" data-price="20">Large (+₱20)</button>
                    </div>
                </div>

                <div class="option-group">
                    <h3>Sugar Level</h3>
                    <div class="option-buttons" data-group="sugar">
                        <button class="option-btn active" data-value="100%" data-price="0">100%</button>
                        <button class="option-btn" data-value="75%" data-price="0">75%</button>
                        <button class="option-btn" data-value="50%" data-price="0">50%</button>
                        <button class="option-btn" data-value="25%" data-price="0">25%</button>
                        <button class="option-btn" data-value="0%" data-price="0">0%</button>
                    </div>
                </div>

                <div class="option-group">
                    <h3>Add-ons</h3>
                    <div class="option-buttons" data-group="addons">
                        <button class="option-btn" data-value="Extra Shot" data-price="30">Extra Shot (+₱30)</button>
                        <button class="option-btn" data-value="Oat Milk" data-price="25">Oat Milk (+₱25)</button>
                        <button class="option-btn" data-value="Whipped Cream" data-price="15">Whipped Cream (+₱15)</button>
                    </div>
                </div>

                <div class="option-group">
                    <h3>Special Instructions</h3>
                    <textarea id="special-instructions" class="special-instructions" placeholder="e.g., Less ice, extra hot, no foam..." rows="3"></textarea>
                </div>
            </div>

            <div class="modal__footer">
                <div class="modal__total">
                    <span class="modal__total-label">Total</span>
                    <span id="modal-total-price">₱0</span>
                </div>
                <button class="btn-confirm" id="btn-confirm-add">
                    Add to Cart <i data-lucide="arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <script src="../script.js"></script>
    <script>
        (function () {
            const STORAGE_KEY = 'brewski_cart';
            const menu = document.getElementById('cart-menu');
            const button = document.getElementById('cart-button');
            const body = document.getElementById('cart-body');

            if (!menu || !button || !body) {
                return;
            }

            function load() {
                try {
                    const data = JSON.parse(localStorage.getItem(STORAGE_KEY));
                    return Array.isArray(data) ? data : [];
                } catch (error) {
                    return [];
                }
            }

            function save(items) {
                try {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
                } catch (error) {}
            }

            function money(amount) {
                return '₱' + Number(amount).toLocaleString('en-PH');
            }

            function escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            function setOpen(open) {
                menu.classList.toggle('open', open);
                button.setAttribute('aria-expanded', open ? 'true' : 'false');
            }

            function render() {
                const items = load();
                let count = 0;
                let subtotal = 0;

                items.forEach(function (item) {
                    count += item.quantity;
                    subtotal += item.price * item.quantity;
                });

                document.querySelectorAll('[data-cart-count]').forEach(function (element) {
                    element.textContent = count;
                });

                if (!items.length) {
                    body.innerHTML = '<p class="cart-empty">Your cart is empty.</p>';
                    return;
                }

                let html = '' +
                    '<div class="cart-row cart-row--head">' +
                    '<div>Drink</div><div>Customization</div><div>Quantity</div><div>Price</div><div></div>' +
                    '</div>' +
                    '<div class="cart-list">';

                items.forEach(function (item) {
                    const label = item.name + (item.size ? ' (' + item.size + ')' : '');

                    html += '' +
                        '<div class="cart-row" data-key="' + escapeHtml(item.key) + '">' +
                        '<div class="cart-drink">' +
                        '<img src="' + escapeHtml(item.image) + '" alt="' + escapeHtml(item.name) + '">' +
                        '<span>' + escapeHtml(label) + '</span>' +
                        '</div>' +
                        '<div class="cart-custom">' + escapeHtml(item.customization || 'None') + '</div>' +
                        '<div class="cart-qty">' +
                        '<button type="button" class="cart-qty__btn" data-action="increase" aria-label="Increase quantity"><i data-lucide="plus"></i></button>' +
                        '<span class="cart-qty__value">' + item.quantity + '</span>' +
                        '<button type="button" class="cart-qty__btn" data-action="decrease" aria-label="Decrease quantity"><i data-lucide="minus"></i></button>' +
                        '</div>' +
                        '<div class="cart-price">' + money(item.price * item.quantity) + '</div>' +
                        '<div class="cart-remove">' +
                        '<button type="button" class="cart-remove__btn" data-action="remove" aria-label="Remove item"><i data-lucide="trash-2"></i></button>' +
                        '</div>' +
                        '</div>';
                });

                html += '' +
                    '</div>' +
                    '<div class="cart-summary">' +
                    '<div class="cart-summary__line"><span>Subtotal</span><span>' + money(subtotal) + '</span></div>' +
                    '<div class="cart-summary__line cart-summary__line--total"><span>Total</span><span>' + money(subtotal) + '</span></div>' +
                    '<a href="checkout.html" class="cart-checkout">Proceed to check out</a>' +
                    '</div>';

                body.innerHTML = html;

                if (window.lucide) {
                    window.lucide.createIcons();
                }
            }

            function selectedValues(group) {
                return Array.prototype.map.call(
                    document.querySelectorAll('.option-buttons[data-group="' + group + '"] .option-btn.active'),
                    function (option) {
                        return option.dataset.value;
                    }
                );
            }

            function addFromModal() {
                const nameElement = document.getElementById('modal-product-name');
                const totalElement = document.getElementById('modal-total-price');

                if (!nameElement || !totalElement) {
                    return;
                }

                const name = nameElement.textContent.trim();
                const price = parseFloat(totalElement.textContent.replace(/[^0-9.]/g, '')) || 0;

                const card = Array.prototype.find.call(
                    document.querySelectorAll('.product-card'),
                    function (element) {
                        return element.dataset.name === name;
                    }
                );

                const image = card ? card.dataset.image || '' : '';
                const size = selectedValues('size')[0] || '';
                const parts = [];

                const temp = selectedValues('temp')[0];
                if (temp) {
                    parts.push(temp);
                }

                const sugar = selectedValues('sugar')[0];
                if (sugar) {
                    parts.push(sugar + ' sugar');
                }

                selectedValues('addons').forEach(function (addon) {
                    parts.push(addon);
                });

                const note = document.getElementById('special-instructions');
                if (note && note.value.trim() !== '') {
                    parts.push(note.value.trim());
                }

                const customization = parts.join(', ');
                const key = [name, size, customization].join('|');
                const items = load();
                const existing = items.find(function (item) {
                    return item.key === key;
                });

                if (existing) {
                    existing.quantity = Math.min(99, existing.quantity + 1);
                } else {
                    items.push({
                        key: key,
                        name: name,
                        image: image,
                        size: size,
                        customization: customization,
                        price: price,
                        quantity: 1
                    });
                }

                save(items);
                render();
                setOpen(true);
            }

            button.addEventListener('click', function (event) {
                event.stopPropagation();
                setOpen(!menu.classList.contains('open'));
            });

            document.addEventListener('click', function (event) {
                if (!menu.contains(event.target)) {
                    setOpen(false);
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    setOpen(false);
                }
            });

            document.addEventListener(
                'click',
                function (event) {
                    if (event.target.closest('#btn-confirm-add')) {
                        addFromModal();
                    }
                },
                true
            );

            body.addEventListener('click', function (event) {
                const target = event.target.closest('[data-action]');

                if (!target) {
                    return;
                }

                const row = target.closest('[data-key]');

                if (!row) {
                    return;
                }

                let items = load();
                const item = items.find(function (entry) {
                    return entry.key === row.dataset.key;
                });

                if (!item) {
                    return;
                }

                if (target.dataset.action === 'increase') {
                    item.quantity = Math.min(99, item.quantity + 1);
                } else if (target.dataset.action === 'decrease') {
                    item.quantity = Math.max(1, item.quantity - 1);
                } else if (target.dataset.action === 'remove') {
                    items = items.filter(function (entry) {
                        return entry.key !== item.key;
                    });
                }

                save(items);
                render();
            });

            window.addEventListener('storage', render);

            render();
        })();
    </script>
</body>
</html>