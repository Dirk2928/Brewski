<section class="product-management-page">
    <style>
        .product-management-page {
            --espresso: #1e110a;
            --latte: #f1e2ca;
            --cream: #faf6ee;
            --mocha: #71492a;
            --dark-mocha: #3c3027;
            --white: #ffffff;
            --shadow-sm: 0 2px 8px rgba(30, 17, 10, 0.08);
            --shadow-md: 0 8px 24px rgba(30, 17, 10, 0.12);
            --font-brand: "Fredoka", "Century Gothic", sans-serif;
            --font-body: "Century Gothic", CenturyGothic, "AppleGothic", "Questrial", "Segoe UI", sans-serif;
        }

        .product-management-page * {
            box-sizing: border-box;
        }

        .product-management-page {
            display: block;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            color: var(--espresso);
            font-family: var(--font-body);
            background: transparent;
            padding: 2rem;
        }

        .product-management-page .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 3rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--latte);
            width: 100%;
            min-height: 68px;
        }

        .product-management-page .page-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: var(--espresso);
        }

        .product-management-page .subtitle {
            margin: 0.35rem 0 0;
            font-size: 0.95rem;
            color: var(--dark-mocha);
        }

        .product-management-page .toolbar-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-left: auto;
            align-self: flex-start;
            padding-top: 0.4rem;
        }

        .product-management-page .btn-toolbar {
            border: none;
            border-radius: 0.7rem;
            padding: 0.7rem 1rem;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, opacity 0.15s ease, background-color 0.15s ease;
        }

        .product-management-page .btn-toolbar:hover {
            transform: translateY(-1px);
        }

        .product-management-page .btn-add {
            background: var(--espresso);
            color: var(--latte);
        }

        .product-management-page .btn-delete {
            background: #c33d2f;
            color: var(--white);
        }

        .product-management-page .category {
            margin-bottom: 3rem;
        }

        .product-management-page .category__title {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0 0 1.25rem 0;
            color: var(--espresso);
        }

        .product-management-page .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.5rem;
            justify-content: center;
            align-items: stretch;
            margin: 0 auto;
        }

        .product-management-page .product-card {
            background-color: var(--latte);
            border-radius: 1rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: var(--shadow-sm);
        }

        .product-management-page .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .product-management-page .product-card__image {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #e8dac2, #d9c2a1);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .product-management-page .product-card__image::before {
            content: "☕";
            font-size: 3rem;
            opacity: 0.8;
        }

        .product-management-page .product-card__info {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-management-page .product-card__info h3 {
            margin: 0 0 0.25rem 0;
            font-size: 1rem;
            font-weight: 700;
            color: var(--espresso);
        }

        .product-management-page .price {
            margin: 0 0 1rem 0;
            font-size: 0.9rem;
            color: var(--dark-mocha);
            font-weight: 600;
        }

        .product-management-page .btn-edit {
            margin-top: auto;
            width: 100%;
            padding: 0.7rem 0.9rem;
            background-color: var(--espresso);
            color: var(--latte);
            border: none;
            border-radius: 0.6rem;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .product-management-page .btn-edit:hover {
            background-color: var(--mocha);
        }

        @media (max-width: 700px) {
            .product-management-page .page-header {
                flex-direction: row;
                align-items: center;
                flex-wrap: wrap;
            }

            .product-management-page .toolbar-actions {
                justify-content: flex-end;
                margin-left: auto;
                width: auto;
                padding-top: 0;
            }

            .product-management-page {
                width: 100%;
                padding: 1.25rem 1rem 2rem;
            }

            .product-management-page .product-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }
        }
    </style>

    <div class="page-header">
        <div>
            <h1>Product Management</h1>
            <p class="subtitle">Manage the Brewski menu catalog.</p>
        </div>

        <div class="toolbar-actions" aria-label="product actions">
            <button type="button" class="btn-toolbar btn-add">Add Product</button>
            <button type="button" class="btn-toolbar btn-delete">Delete Product</button>
        </div>
    </div>

    <section class="category">
        <h2 class="category__title">Best sellers</h2>
        <div class="product-grid">
            <article class="product-card">
                <div class="product-card__image" aria-label="Black Coffee"></div>
                <div class="product-card__info">
                    <h3>Black Coffee</h3>
                    <p class="price">₱170</p>
                    <button type="button" class="btn-edit">Edit</button>
                </div>
            </article>

            <article class="product-card">
                <div class="product-card__image" aria-label="Caramel Macchiato"></div>
                <div class="product-card__info">
                    <h3>Caramel Macchiato</h3>
                    <p class="price">₱180</p>
                    <button type="button" class="btn-edit">Edit</button>
                </div>
            </article>

            <article class="product-card">
                <div class="product-card__image" aria-label="Cafe Latte"></div>
                <div class="product-card__info">
                    <h3>Cafe Latte</h3>
                    <p class="price">₱170</p>
                    <button type="button" class="btn-edit">Edit</button>
                </div>
            </article>

            <article class="product-card">
                <div class="product-card__image" aria-label="Mocha"></div>
                <div class="product-card__info">
                    <h3>Vanilla Mocha</h3>
                    <p class="price">₱185</p>
                    <button type="button" class="btn-edit">Edit</button>
                </div>
            </article>
        </div>
    </section>

    <section class="category">
        <h2 class="category__title">Most Popular</h2>
        <div class="product-grid">
            <article class="product-card">
                <div class="product-card__image" aria-label="Iced Coffee"></div>
                <div class="product-card__info">
                    <h3>Iced Coffee</h3>
                    <p class="price">₱160</p>
                    <button type="button" class="btn-edit">Edit</button>
                </div>
            </article>

            <article class="product-card">
                <div class="product-card__image" aria-label="Cafe Mocha"></div>
                <div class="product-card__info">
                    <h3>Cafe Mocha</h3>
                    <p class="price">₱185</p>
                    <button type="button" class="btn-edit">Edit</button>
                </div>
            </article>

            <article class="product-card">
                <div class="product-card__image" aria-label="Double Espresso"></div>
                <div class="product-card__info">
                    <h3>Double Espresso</h3>
                    <p class="price">₱150</p>
                    <button type="button" class="btn-edit">Edit</button>
                </div>
            </article>

            <article class="product-card">
                <div class="product-card__image" aria-label="Matcha Latte"></div>
                <div class="product-card__info">
                    <h3>Matcha Latte</h3>
                    <p class="price">₱190</p>
                    <button type="button" class="btn-edit">Edit</button>
                </div>
            </article>
        </div>
    </section>
</section>
