<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Computer Parts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #ffffff, #e0f7ff);
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #001f3f;
            padding: 10px 20px;
            color: #fff;
        }

        .nav-links {
            display: flex;
            gap: 15px;
            list-style: none;
        }

        .nav-links li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        .banner {
            text-align: center;
            padding: 50px 20px;
            color: #001f3f;
        }

        .banner h1 {
            font-size: 3rem;
        }

        .categories,
        .products {
            padding: 20px;
            text-align: center;
        }

        .category-list,
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .category-item,
        .product-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #001f3f;
            color: white;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                flex-direction: column;
            }

            .nav-toggle {
                display: block;
            }

            .categories,
            .products {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">TechStore</div>
        <nav>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#categories">Categories</a></li>
                <li><a href="#products">Products</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <button class="nav-toggle">☰</button>
        </nav>
    </header>

    <main>
        <section id="home" class="banner">
            <h1>Best Deals on Computer Parts</h1>
            <p>Upgrade your system today!</p>
            <button class="shop-now">Shop Now</button>
        </section>

        <section id="categories" class="categories">
            <h2>Categories</h2>
            <div class="category-list">
                <div class="category-item">
                    <img src="images/mouse.png" alt="Mouse">
                    <p>Mouse</p>
                </div>
                <div class="category-item">
                    <img src="images/keyboard.png" alt="Keyboard">
                    <p>Keyboard</p>
                </div>
                <div class="category-item">
                    <img src="images/monitor.png" alt="Monitor">
                    <p>Monitor</p>
                </div>
                <!-- Add more categories -->
            </div>
        </section>

        <section id="products" class="products">
            <h2>Our Products</h2>
            <div class="product-grid">
                <div class="product-card">
                    <img src="images/ssd.png" alt="SSD">
                    <h3>1TB SSD</h3>
                    <p>$99.99</p>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
                <div class="product-card">
                    <img src="images/ram.png" alt="RAM">
                    <h3>16GB RAM</h3>
                    <p>$79.99</p>
                    <button class="add-to-cart">Add to Cart</button>
                </div>
                <!-- Add more products -->
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 TechStore. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
<script>
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');

    navToggle.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });

    // Responsive toggle
</script>

</html>