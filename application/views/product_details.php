<link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/css/lightslider.min.css" />
<div class="listing">
    <div class="container">
        <div class="breadcrumb_area">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo base_url('products'); ?>">Products</a></li>
                <li class="breadcrumb-item current"><?php echo $product['name']; ?></li>
            </ol>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="demo">
                    <ul id="lightSlider">
                        <?php
                        $image_url = ($product['featured_image'] != "") ? base_url('uploads/products/' . $product['featured_image']) : base_url('assets/uploads/no_image.jpeg');

                        //$image = '<img src="' . $image_url . '" alt="' . $product['name'] . '" width="100">';
                        ?>
                        <li data-thumb="<?php echo $image_url; ?>">
                            <img
                                class="slide_img"
                                src="<?php echo $image_url; ?>" />
                        </li>
                        <?php
                        if (!empty($product['gallery_images'])) :
                            $gallery_images = json_decode($product['gallery_images']);
                            foreach ($gallery_images as $gallery_image) :
                                $image_thumb_url = ($gallery_image) ? base_url('uploads/products/' . $gallery_image) : base_url('assets/uploads/no_image.jpeg');
                                echo '<li data-thumb="' . $image_thumb_url . '"><img class="slide_img" src="' . $image_thumb_url . '" /></li>';
                            endforeach;
                        endif;
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="single_product_content_box">
                <h6 class="pro_id"><?php echo getSetting('invoice_prefix'); ?><?php echo $product['id']; ?></h6>
                <h4 class="single_product_heading">
                    <?php echo $product['name']; ?>
                </h4>

                <div class="single_product_col">
                    <h5 class="price_single_product">
                        <?php echo ($product['sale_price'] > 0)
                            ? '<del>₹' . number_format($product['regular_price'], 2) . '</del> <span class="sale-price">₹' . number_format($product['sale_price'], 2) . '</span>'
                            : '₹' . number_format($product['regular_price'], 2); ?>
                    </h5>
                    <img src="<?php echo base_url(); ?>assets/frontend/images/lineheart.png" class="img-fluid heart_clickable" alt="">
                </div>
                <p class="tax_data">Inclusive of all taxes</p>
                <div class="underline"></div>

                <div class="single_pro_data">
                    <h6>Product Details</h6>
                    <?php echo $product['highlight_text']; ?>
                </div>
                <div class="underline"></div>

                <div class="single_pro_quantity">
                    <h6>Quantity</h6>
                    <form>
                        <div
                            class="value-button"
                            id="decrease"
                            onclick="decreaseValue()"
                            value="Decrease Value">
                            <i class="fa-solid fa-minus"></i>
                        </div>
                        <input type="number" id="number" value="1" />
                        <div
                            class="value-button"
                            id="increase"
                            onclick="increaseValue()"
                            value="Increase Value">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </form>
                </div>
                <div class="single_pro_button">
                    <a href="#" class="add_to_cart_btn">Add to Cart</a>
                    <a href="#" class="buy_now_btn">Buy Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="single_pro_description">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="wrapper">
                    <input type="radio" name="slider" checked id="desp" />
                    <input type="radio" name="slider" id="fab" />
                    <input type="radio" name="slider" id="return" />
                    <input type="radio" name="slider" id="help" />
                    <nav>
                        <label for="desp" class="desp"><i class="fas fa-home"></i>Description</label>
                        <label for="return" class="return"><i class="fas fa-code"></i>Delivery and Returns</label>
                        <label for="help" class="help"><i class="far fa-envelope"></i>Need Help</label>
                        <div class="slider"></div>
                    </nav>
                    <section class="tab_content_area">
                        <div class="content content-1">
                            <div class="title"><?php echo $product['name']; ?></div>
                            <?php echo $product['description']; ?>
                        </div>
                        <div class="content content-3">
                            <div class="title">This is a Code content</div>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Iure, debitis nesciunt! Consectetur officiis, libero nobis
                                dolorem pariatur quisquam temporibus. Labore quaerat neque
                                facere itaque laudantium odit veniam consectetur numquam
                                delectus aspernatur, perferendis repellat illo sequi
                                excepturi quos ipsam aliquid est consequuntur.
                            </p>
                        </div>
                        <div class="content content-4">
                            <div class="title">This is a Help content</div>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Enim reprehenderit null itaq, odio repellat asperiores vel
                                voluptatem magnam praesentium, eveniet iure ab facere
                                officiis. Quod sequi vel, rem quam provident soluta nihil,
                                eos. Illo oditu omnis cumque praesentium voluptate maxime
                                voluptatibus facilis nulla ipsam quidem mollitia! Veniam,
                                fuga, possimus. Commodi, fugiat aut ut quorioms stu
                                necessitatibus, cumque laborum rem provident tenetur.
                            </p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="similar_products_sec">
    <div class="container">
        <div class="heading">
            <h4>Similar <span>Products</span></h4>
            <div class="underline"></div>
        </div>
        <div class="similar_products_all owl-carousel owl-theme">
            <?php foreach ($similar_products as $product): ?>
                <div class="similar_single_product">
                    <div class="single_product_listing">
                        <?php $this->load->view('templates/product', ['product' => $product]); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/frontend/js/lightslider.min.js"></script>