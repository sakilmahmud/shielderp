<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GC Prints - Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/style.css'); ?>">
</head>

<body>
    <!-- Navbar -->
    <div class="d-flex justify-content-between bg-dark">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">GC Prints</a>
            </div>
        </nav>

        <div>
            <a class="text-right me-5 mt-3 text-light" href="tel:9732138374">09732138374</a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero d-flex align-items-center">
        <!-- <div class="container text-center">
            <h1>Personalize Your Prints</h1>
            <p class="lead">Mugs, T-shirts, Photo Frames & more. Custom designs at your fingertips!</p>
        </div> -->
    </section>

    <!-- Product Section -->
    <section class="products container text-center">
        <h2 class="mb-5">Our Products</h2>
        <div class="row">
            <!-- Product Cards -->
            <div class="col-md-3 mb-4">
                <div class="card product-card">
                    <img src="<?php echo base_url('assets/frontend/images/300x300.jpeg'); ?>" class="card-img-top" alt="White Mug">
                    <div class="card-body">
                        <h5 class="card-title">White Mug</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card product-card">
                    <img src="<?php echo base_url('assets/frontend/images/300x300.jpeg'); ?>" class="card-img-top" alt="Colour Mug">
                    <div class="card-body">
                        <h5 class="card-title">Colour Mug</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card product-card">
                    <img src="<?php echo base_url('assets/frontend/images/300x300.jpeg'); ?>" class="card-img-top" alt="Magic Mug">
                    <div class="card-body">
                        <h5 class="card-title">Magic Mug</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card product-card">
                    <img src="<?php echo base_url('assets/frontend/images/300x300.jpeg'); ?>" class="card-img-top" alt="Photo Frame">
                    <div class="card-body">
                        <h5 class="card-title">Photo Frame</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card product-card">
                    <img src="<?php echo base_url('assets/frontend/images/300x300.jpeg'); ?>" class="card-img-top" alt="Mouse Pad">
                    <div class="card-body">
                        <h5 class="card-title">Mouse Pad</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card product-card">
                    <img src="<?php echo base_url('assets/frontend/images/300x300.jpeg'); ?>" class="card-img-top" alt="Bottle Print">
                    <div class="card-body">
                        <h5 class="card-title">Bottle Print</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card product-card">
                    <img src="<?php echo base_url('assets/frontend/images/300x300.jpeg'); ?>" class="card-img-top" alt="T-shirt Print">
                    <div class="card-body">
                        <h5 class="card-title">T-shirt Print</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card product-card">
                    <img src="<?php echo base_url('assets/frontend/images/300x300.jpeg'); ?>" class="card-img-top" alt="Cap Print">
                    <div class="card-body">
                        <h5 class="card-title">Cap Print</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lead Capture Form Section -->
    <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="lead-form p-4 rounded">
                    <h3 class="text-center mb-4">Get a Quote for Your Custom Print</h3>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter your name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="name@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" placeholder="Enter your phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="product" class="form-label">Select Product</label>
                            <select class="form-select" id="product" required>
                                <option value="" selected>Select a product</option>
                                <option value="White Mug">White Mug</option>
                                <option value="Colour Mug">Colour Mug</option>
                                <option value="Magic Mug">Magic Mug</option>
                                <option value="Photo Frame">Photo Frame</option>
                                <option value="Mouse Pad">Mouse Pad</option>
                                <option value="Bottle Print">Bottle Print</option>
                                <option value="T-shirt Print">T-shirt Print</option>
                                <option value="Cap Print">Cap Print</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="4" placeholder="Provide more details about your print requirements"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Get a Quote</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>