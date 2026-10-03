<?php

$products = [
    [
        'id' => 1,
        'name' => 'Black Coffee',
        'price' => 170,
        'category' => 'coffee',
        'temp' => 'hot',
        'image' => '../../blackcoffee.png'
    ],
    [
        'id' => 2,
        'name' => 'Caramel Macchiato',
        'price' => 180,
        'category' => 'coffee',
        'temp' => 'hot',
        'image' => '../../caramel.png'
    ],
    [
        'id' => 3,
        'name' => 'Cafe Latte',
        'price' => 170,
        'category' => 'coffee',
        'temp' => 'hot',
        'image' => '../../cafelatte.png'
    ],
    [
        'id' => 4,
        'name' => 'Double Espresso',
        'price' => 150,
        'category' => 'coffee',
        'temp' => 'hot',
        'image' => '../../doubleepresso.png'
    ],
    [
        'id' => 5,
        'name' => 'Iced Coffee',
        'price' => 160,
        'category' => 'coffee',
        'temp' => 'iced',
        'image' => '../../icedcoffee.png'
    ],

    [
        'id' => 6,
        'name' => 'Matcha Latte',
        'price' => 190,
        'category' => 'non-coffee',
        'temp' => 'hot',
        'image' => '../../matchalatte.png'
    ],
    [
        'id' => 7,
        'name' => 'Chocolate Drink',
        'price' => 175,
        'category' => 'non-coffee',
        'temp' => 'hot',
        'image' => '../../mocha.png'
    ],
    [
        'id' => 8,
        'name' => 'Strawberry Milk',
        'price' => 185,
        'category' => 'non-coffee',
        'temp' => 'iced',
        'image' => '../../cafelatte.png'
    ],

    [
        'id' => 9,
        'name' => 'Caramel Frappe',
        'price' => 210,
        'category' => 'frappe',
        'temp' => 'iced',
        'image' => '../../caramel.png'
    ],
    [
        'id' => 10,
        'name' => 'Mocha Frappe',
        'price' => 205,
        'category' => 'frappe',
        'temp' => 'iced',
        'image' => '../../mocha.png'
    ],
    [
        'id' => 11,
        'name' => 'Matcha Frappe',
        'price' => 215,
        'category' => 'frappe',
        'temp' => 'iced',
        'image' => '../../blackcoffee.png'
    ],

    [
        'id' => 12,
        'name' => 'Classic Milk Tea',
        'price' => 160,
        'category' => 'tea',
        'temp' => 'iced',
        'image' => '../../icedcoffee.png'
    ],
    [
        'id' => 13,
        'name' => 'Peach Iced Tea',
        'price' => 150,
        'category' => 'tea',
        'temp' => 'iced',
        'image' => '../../caramel.png'
    ],
    [
        'id' => 14,
        'name' => 'Hot Green Tea',
        'price' => 140,
        'category' => 'tea',
        'temp' => 'hot',
        'image' => '../../matchalatte.png'
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
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="../menu.css">
    
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
            <a href="cart.html" class="topbar__link cart-link">
                <i data-lucide="shopping-cart"></i> Cart
                <span class="cart-badge">0</span>
            </a>
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
                         data-name="<?= htmlspecialchars($product['name']) ?>" 
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
</body>
</html>