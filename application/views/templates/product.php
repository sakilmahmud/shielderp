<!-- views/templates/product.php -->
<?php
$image_url = ($product['featured_image'] != "") ? base_url('uploads/products/' . $product['featured_image']) : base_url('assets/uploads/no_image.jpeg');
?>
<?php $endpoint = ($product['slug'] != "") ? $product['slug'] : $product['id']; ?>
<div class="single_product_listing">
    <div class="single_pro_work">
        <img src="<?php echo base_url(); ?>assets/frontend/images/shopping-cart.png" class="img-fluid" alt="">
        <img src="<?php echo base_url(); ?>assets/frontend/images/lineheart.png" class="img-fluid heart_clickable" alt="">
    </div>
    <div class="single_product_listing_img">
        <a href="<?php echo base_url('products/' . $endpoint); ?>">
            <img
                src="<?php echo $image_url; ?>" class="img-fluid"
                alt="<?php echo $product['name']; ?>" />
        </a>
    </div>
    <div class="single_product_listing_content">
        <h6><a href="<?php echo base_url('products/' . $endpoint); ?>"><?php echo $product['name']; ?></a></h6>
        <div class="price">
            <p class="strike_price"><?php echo '₹' . number_format($product['regular_price'], 2); ?></p>
            <p class="main_value"><?php echo '₹' . number_format($product['sale_price'], 2); ?></p>
        </div>
    </div>
</div>