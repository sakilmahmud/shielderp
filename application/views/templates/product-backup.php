<!-- views/templates/product.php -->
<style>
    .card_img img {
        max-height: 180px;
        width: auto;
        margin: auto;
        display: flex;
    }

    .latest_product_sec .card .product_name {
        text-align: center;
        min-height: auto !important;
    }

    .product_name a {
        color: #7F7373;
        font-size: 14px;
        text-decoration: none;
        text-align: center;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
    }

    h6 del {
        color: #888;
        font-size: 14px;
        font-weight: normal;
    }
</style>
<?php $endpoint = ($product['slug'] != "") ? $product['slug'] : $product['id']; ?>
<div class="card">
    <div class="card_img">
        <a href="<?php echo base_url('product/' . $endpoint); ?>">
            <img
                src="<?php echo base_url('uploads/products/' . $product['featured_image']); ?>" class="img-fluid"
                alt="<?php echo $product['name']; ?>" />
        </a>
    </div>
    <div class="card_content">
        <p class="product_name mt-3">
            <a href="<?php echo base_url('product/' . $endpoint); ?>">
                <?php echo $product['name']; ?>
            </a>
        </p>
        <hr />
        <div class="card_content_column">
            <div class="card_content_column_left">
                <h6>
                    <?php echo ($product['sale_price'] > 0)
                        ? '<del>₹' . number_format($product['regular_price'], 2) . '</del> <span class="sale-price">₹' . number_format($product['sale_price'], 2) . '</span>'
                        : '₹' . number_format($product['regular_price'], 2); ?>
                </h6>
            </div>
            <div class="card_content_column_middle">
                <img
                    src="<?php echo base_url('assets/frontend/images/heart.png') ?>"
                    class="img-fluid"
                    alt="heart" />
            </div>
            <div class="card_content_column_right">
                <img
                    src="<?php echo base_url('assets/frontend/images/cart.png') ?>"
                    class="img-fluid"
                    alt="cart" />
            </div>
        </div>
    </div>
</div>