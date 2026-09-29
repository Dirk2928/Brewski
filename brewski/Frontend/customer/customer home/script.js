// script.js

document.addEventListener('DOMContentLoaded', () => {
    
    // Initialize Lucide Icons
    lucide.createIcons();

    // --- CART STATE ---
    let cartItems = [];
    let currentProduct = null;

    // --- DOM ELEMENTS ---
    const modal = document.getElementById('customization-modal');
    const modalProductName = document.getElementById('modal-product-name');
    const modalTotalPrice = document.getElementById('modal-total-price');
    const closeModalBtn = document.getElementById('modal-close');
    const confirmAddBtn = document.getElementById('btn-confirm-add');
    const cartBadge = document.querySelector('.cart-badge');
    const addToCartButtons = document.querySelectorAll('.btn-add');
    const optionButtons = document.querySelectorAll('.option-btn');
    const specialInstructionsInput = document.getElementById('special-instructions');

    // --- OPEN MODAL ---
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.product-card');
            
            // Get product data from data attributes
            currentProduct = {
                name: card.dataset.name,
                basePrice: parseFloat(card.dataset.basePrice),
                temp: 'Hot', // Default temperature
                size: 'Regular',
                sugar: '100%',
                addons: [],
                instructions: ''
            };

            // Reset modal selections
            resetModalSelections();
            
            // Update modal title
            modalProductName.textContent = `Customize: ${currentProduct.name}`;
            
            // Calculate initial total
            updateModalTotal();

            // Show modal
            modal.classList.add('show');
        });
    });

    // --- CLOSE MODAL ---
    closeModalBtn.addEventListener('click', () => {
        modal.classList.remove('show');
    });

    // Close modal if clicking outside the modal box
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });

    // --- HANDLE OPTION SELECTIONS ---
    optionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const group = this.parentElement.dataset.group;
            const value = this.dataset.value;
            const price = parseFloat(this.dataset.price);

            // Handle single-select groups (Temp, Size, Sugar)
            if (group === 'temp' || group === 'size' || group === 'sugar') {
                this.parentElement.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                if (group === 'temp') currentProduct.temp = value;
                if (group === 'size') currentProduct.size = value;
                if (group === 'sugar') currentProduct.sugar = value;
            } 
            // Handle multi-select group (Add-ons)
            else if (group === 'addons') {
                this.classList.toggle('active');
                
                if (this.classList.contains('active')) {
                    currentProduct.addons.push({ name: value, price: price });
                } else {
                    currentProduct.addons = currentProduct.addons.filter(item => item.name !== value);
                }
            }

            updateModalTotal();
        });
    });

    // --- CALCULATE TOTAL PRICE ---
    function updateModalTotal() {
        if (!currentProduct) return;

        let total = currentProduct.basePrice;

        // Add size price
        const sizeBtn = document.querySelector('[data-group="size"] .option-btn.active');
        if (sizeBtn) total += parseFloat(sizeBtn.dataset.price);

        // Add addons prices
        currentProduct.addons.forEach(addon => {
            total += addon.price;
        });

        modalTotalPrice.textContent = `₱${total}`;
    }

    // --- RESET MODAL SELECTIONS ---
    function resetModalSelections() {
        // Reset Temperature to Hot
        const tempGroup = document.querySelector('[data-group="temp"]');
        tempGroup.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));
        tempGroup.querySelector('[data-value="Hot"]').classList.add('active');

        // Reset Size to Regular
        const sizeGroup = document.querySelector('[data-group="size"]');
        sizeGroup.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));
        sizeGroup.querySelector('[data-value="Regular"]').classList.add('active');

        // Reset Sugar to 100%
        const sugarGroup = document.querySelector('[data-group="sugar"]');
        sugarGroup.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));
        sugarGroup.querySelector('[data-value="100%"]').classList.add('active');

        // Clear Addons
        const addonsGroup = document.querySelector('[data-group="addons"]');
        addonsGroup.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));

        // Clear Special Instructions
        specialInstructionsInput.value = '';
    }

    // --- CONFIRM ADD TO CART ---
    confirmAddBtn.addEventListener('click', () => {
        if (!currentProduct) return;

        // Get final total
        let total = currentProduct.basePrice;
        const sizeBtn = document.querySelector('[data-group="size"] .option-btn.active');
        if (sizeBtn) total += parseFloat(sizeBtn.dataset.price);
        currentProduct.addons.forEach(addon => total += addon.price);

        // Capture special instructions
        currentProduct.instructions = specialInstructionsInput.value.trim();

        // Create cart item object
        const cartItem = {
            ...currentProduct,
            totalPrice: total,
            quantity: 1
        };

        // Add to cart array
        cartItems.push(cartItem);
        console.log('Cart Updated:', cartItems);

        // Update Cart Badge
        updateCartBadge();

        // Visual feedback on button
        const originalHTML = confirmAddBtn.innerHTML;
        confirmAddBtn.innerHTML = '<i data-lucide="check"></i> Added!';
        confirmAddBtn.style.backgroundColor = 'var(--mocha)';
        
        // Re-initialize the new icon
        lucide.createIcons();

        setTimeout(() => {
            confirmAddBtn.innerHTML = originalHTML;
            confirmAddBtn.style.backgroundColor = '';
            lucide.createIcons(); // Re-initialize original icon
            modal.classList.remove('show');
        }, 1000);
    });

    // --- UPDATE CART BADGE ---
    function updateCartBadge() {
        const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
        cartBadge.textContent = totalItems;
        
        cartBadge.style.transform = 'scale(1.3)';
        setTimeout(() => {
            cartBadge.style.transform = 'scale(1)';
        }, 200);
    }

});