document.addEventListener('DOMContentLoaded', () => {

    lucide.createIcons();


const profileButton = document.getElementById('profile-button');
const profileMenu = document.querySelector('.profile-menu');

if (profileButton && profileMenu) {

    profileButton.addEventListener('click', (e) => {
        e.stopPropagation();
        profileMenu.classList.toggle('open');
    });

    document.addEventListener('click', (e) => {
        if (!profileMenu.contains(e.target)) {
            profileMenu.classList.remove('open');
        }
    });
}


    const CART_KEY = 'brewskiCart';
    let cartItems = [];
    try {
        cartItems = JSON.parse(localStorage.getItem(CART_KEY)) || [];
    } catch (e) {
        cartItems = [];
    }
    let currentProduct = null;


    const modal = document.getElementById('customization-modal');
    const modalProductName = document.getElementById('modal-product-name');
    const modalTotalPrice = document.getElementById('modal-total-price');
    const closeModalBtn = document.getElementById('modal-close');
    const confirmAddBtn = document.getElementById('btn-confirm-add');
    const cartBadge = document.querySelector('.cart-badge');
    const addToCartButtons = document.querySelectorAll('.btn-add');
    const optionButtons = document.querySelectorAll('.option-btn');
    const specialInstructionsInput = document.getElementById('special-instructions');


    const productCards = document.querySelectorAll('.product-card');
    const noResults = document.getElementById('no-results');
    const filterState = { category: 'all', temp: 'all' };

    function applyFilters() {
        let visibleCount = 0;

        productCards.forEach(card => {
            const matchCategory = filterState.category === 'all' || card.dataset.category === filterState.category;
            const matchTemp = filterState.temp === 'all' || card.dataset.temp === filterState.temp;
            const show = matchCategory && matchTemp;

            card.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    function setupFilterGroup(containerId, stateKey) {
        const container = document.getElementById(containerId);

        container.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                container.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                filterState[stateKey] = btn.dataset.filter;
                applyFilters();
            });
        });
    }

    setupFilterGroup('category-filters', 'category');
    setupFilterGroup('temp-filters', 'temp');


    addToCartButtons.forEach(button => {
        button.addEventListener('click', function () {
            const card = this.closest('.product-card');
            const defaultTemp = card.dataset.temp === 'iced' ? 'Iced' : 'Hot';

            currentProduct = {
                name: card.dataset.name,
                basePrice: parseFloat(card.dataset.basePrice),
                temp: defaultTemp,
                size: 'Regular',
                sugar: '100%',
                addons: [],
                instructions: ''
            };

            resetModalSelections(defaultTemp);

            modalProductName.textContent = `Customize: ${currentProduct.name}`;

            updateModalTotal();

            modal.classList.add('show');
        });
    });


    closeModalBtn.addEventListener('click', () => {
        modal.classList.remove('show');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });


    optionButtons.forEach(button => {
        button.addEventListener('click', function () {
            if (!currentProduct) return;

            const group = this.parentElement.dataset.group;
            const value = this.dataset.value;
            const price = parseFloat(this.dataset.price);

            if (group === 'temp' || group === 'size' || group === 'sugar') {
                this.parentElement.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                if (group === 'temp') currentProduct.temp = value;
                if (group === 'size') currentProduct.size = value;
                if (group === 'sugar') currentProduct.sugar = value;
            }
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

    function calculateTotal() {
        let total = currentProduct.basePrice;

        const sizeBtn = document.querySelector('[data-group="size"] .option-btn.active');
        if (sizeBtn) total += parseFloat(sizeBtn.dataset.price);

        currentProduct.addons.forEach(addon => {
            total += addon.price;
        });

        return total;
    }

    function updateModalTotal() {
        if (!currentProduct) return;
        modalTotalPrice.textContent = `₱${calculateTotal()}`;
    }

    function resetModalSelections(defaultTemp = 'Hot') {
        const tempGroup = document.querySelector('[data-group="temp"]');
        tempGroup.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));
        tempGroup.querySelector(`[data-value="${defaultTemp}"]`).classList.add('active');

        const sizeGroup = document.querySelector('[data-group="size"]');
        sizeGroup.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));
        sizeGroup.querySelector('[data-value="Regular"]').classList.add('active');

        const sugarGroup = document.querySelector('[data-group="sugar"]');
        sugarGroup.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));
        sugarGroup.querySelector('[data-value="100%"]').classList.add('active');

        const addonsGroup = document.querySelector('[data-group="addons"]');
        addonsGroup.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));

        specialInstructionsInput.value = '';
    }


    confirmAddBtn.addEventListener('click', () => {
        if (!currentProduct) return;

        currentProduct.instructions = specialInstructionsInput.value.trim();

        const cartItem = {
            ...currentProduct,
            totalPrice: calculateTotal(),
            quantity: 1
        };

        cartItems.push(cartItem);

        try {
            localStorage.setItem(CART_KEY, JSON.stringify(cartItems));
        } catch (e) {
            console.warn('Could not save cart:', e);
        }

        updateCartBadge();

        const originalHTML = confirmAddBtn.innerHTML;
        confirmAddBtn.innerHTML = '<i data-lucide="check"></i> Added!';
        confirmAddBtn.style.backgroundColor = 'var(--mocha)';

        lucide.createIcons();

        setTimeout(() => {
            confirmAddBtn.innerHTML = originalHTML;
            confirmAddBtn.style.backgroundColor = '';
            lucide.createIcons();
            modal.classList.remove('show');
        }, 1000);
    });


    function updateCartBadge() {
        const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
        cartBadge.textContent = totalItems;

        cartBadge.style.transform = 'scale(1.3)';
        setTimeout(() => {
            cartBadge.style.transform = 'scale(1)';
        }, 200);
    }


    cartBadge.textContent = cartItems.reduce((sum, item) => sum + item.quantity, 0);

});