<!-- views/templates/category.php -->

<div class="popular_collection_single">
    <a href="<?php echo base_url('categories/' . $category['slug']); ?>">
        <div class="popular_collection_single_img">
            <img src="<?php echo base_url('uploads/categories/' . $category['featured_image']); ?>" class="img-fluid" alt="<?php echo $category['name']; ?>" />
        </div>
    </a>
    <h6><a href="<?php echo base_url('categories/' . $category['slug']); ?>"><?php echo $category['name']; ?></a></h6>
</div>