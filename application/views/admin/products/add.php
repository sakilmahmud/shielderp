<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="<?php echo base_url('assets/admin/dist/js/summernote.min.js') ?>"></script>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>Product</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/products/bulk-upload'); ?>" class="btn btn-info">Bulk Upload</a>
                    <a href="<?php echo base_url('admin/products'); ?>" class="btn btn-primary">All Products</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            $action = base_url('admin/products/add');
                            if ($isUpdate) {
                                $action = base_url('admin/products/edit/') . $product['id'];
                            }
                            ?>
                            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name">Product Name <sup>*</sup></label>
                                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name', isset($product['name']) ? $product['name'] : ''); ?>">
                                                    <?php echo form_error('name'); ?>
                                                    <div class="d-flex mt-2">
                                                        <span><?php echo base_url('product/'); ?></span>
                                                        <span class="slug_text"><?php echo set_value('slug', isset($product['slug']) ? $product['slug'] : ''); ?></span>
                                                        <input type="hidden" id="slug" name="slug" value="<?php echo set_value('slug', isset($product['slug']) ? $product['slug'] : ''); ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <!-- Purchase Price Field -->
                                                <div class="form-group">
                                                    <label for="purchase_price">Purchase Price <sup>*</sup></label>
                                                    <input type="number" class="form-control" id="purchase_price" name="purchase_price" value="<?php echo set_value('purchase_price', isset($product['purchase_price']) ? $product['purchase_price'] : '0.00'); ?>" required>
                                                    <?php echo form_error('purchase_price'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <!-- Sale Price Field -->
                                                <div class="form-group">
                                                    <label for="sale_price">Sale Price <sup>*</sup></label>
                                                    <input type="number" class="form-control" id="sale_price" name="sale_price" value="<?php echo set_value('sale_price', isset($product['sale_price']) ? $product['sale_price'] : '0.00'); ?>" required>
                                                    <?php echo form_error('sale_price'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <!-- Regular Price Field -->
                                                <div class="form-group">
                                                    <label for="regular_price">Regular Price <sup>*</sup></label>
                                                    <input type="number" class="form-control" id="regular_price" name="regular_price" value="<?php echo set_value('regular_price', isset($product['regular_price']) ? $product['regular_price'] : '0.00'); ?>" required>
                                                    <?php echo form_error('regular_price'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <div class="mt-1 d-flex justify-content-between">
                                                        <label for="category_id">Category <sup>*</sup></label>
                                                        <a href="javascript:void(0)" class="text-sm add_category">Add Category</a>
                                                    </div>
                                                    <select class="form-control category_id" id="category_id" name="category_id">
                                                        <option value="">Select Category</option>
                                                        <?php foreach ($categories as $category) { ?>
                                                            <option value="<?php echo $category['id']; ?>" <?php echo set_select('category_id', $category['id'], isset($product['category_id']) && $product['category_id'] == $category['id']); ?>><?php echo $category['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error('category_id'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <div class="mt-1 d-flex justify-content-between">
                                                        <label for="brand_id">Brand <sup>*</sup></label>
                                                        <a href="javascript:void(0)" class="text-sm add_brand">Add Brand</a>
                                                    </div>
                                                    <select class="form-control brand_id" id="brand_id" name="brand_id">
                                                        <option value="">Select Brand</option>
                                                        <?php foreach ($brands as $brand) { ?>
                                                            <option value="<?php echo $brand['id']; ?>" <?php echo set_select('brand_id', $brand['id'], isset($product['brand_id']) && $product['brand_id'] == $brand['id']); ?>><?php echo ucwords($brand['brand_name']); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error('brand_id'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="hsn_code">HSN Code <sup>*</sup></label>
                                                    <input type="text" class="form-control" id="hsn_code" name="hsn_code"
                                                        value="<?php echo set_value('hsn_code', isset($product['hsn_code']) ? $product['hsn_code'] : '0'); ?>" required>
                                                    <?php echo form_error('hsn_code'); ?>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="cgst">CGST (%) <sup>*</sup></label>
                                                    <input type="number" step="0.01" class="form-control" id="cgst" name="cgst"
                                                        value="<?php echo set_value('cgst', isset($product['cgst']) ? $product['cgst'] : '0.00'); ?>" required>
                                                    <?php echo form_error('cgst'); ?>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="sgst">SGST (%) <sup>*</sup></label>
                                                    <input type="number" step="0.01" class="form-control" id="sgst" name="sgst"
                                                        value="<?php echo set_value('sgst', isset($product['sgst']) ? $product['sgst'] : '0.00'); ?>" required>
                                                    <?php echo form_error('sgst'); ?>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <div class="mt-1 d-flex justify-content-between">
                                                        <label for="product_type_id">Type</label>
                                                        <a href="javascript:void(0)" class="text-sm add_product_type">Add Type</a>
                                                    </div>
                                                    <select class="form-control product_type_id" id="product_type_id" name="product_type_id">
                                                        <option value="">Select product type</option>
                                                        <?php foreach ($product_types as $product_type) {
                                                            $selected = ($product_type['id'] == 1) ? 'selected' : '';
                                                        ?>
                                                            <option value="<?php echo $product_type['id']; ?>" <?php echo $selected; ?>>
                                                                <?php echo $product_type['product_type_name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error('product_type_id'); ?>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="low_stock_alert">Low Stock Alert</label>
                                                    <input type="number" class="form-control" id="low_stock_alert" name="low_stock_alert"
                                                        value="<?php echo set_value('low_stock_alert', isset($product['low_stock_alert']) ? $product['low_stock_alert'] : '0'); ?>">
                                                    <?php echo form_error('low_stock_alert'); ?>
                                                </div>
                                            </div>
                                            <?php
                                            $selectedUnitId = isset($product['unit_id']) ? $product['unit_id'] : 1;
                                            ?>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <div class="mt-1 d-flex justify-content-between">
                                                        <label for="unit_id">Unit</label>
                                                        <a href="javascript:void(0)" class="text-sm add_unit">Add Unit</a>
                                                    </div>
                                                    <select class="form-control" id="unit_id" name="unit_id">
                                                        <option value="">Select unit</option>
                                                        <?php foreach ($units as $unit) { ?>
                                                            <option value="<?php echo $unit['id']; ?>" <?php echo ($unit['id'] == $selectedUnitId) ? 'selected' : ''; ?>>
                                                                <?php echo $unit['name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php echo form_error('unit_id'); ?>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Featured Image Upload -->
                                        <div class="form-group">
                                            <label for="featured_image">Featured Image</label>
                                            <input type="file" name="featured_image" id="featured_image" class="form-control" accept="image/*">

                                            <!-- Show existing featured image if available -->
                                            <?php if ($isUpdate && !empty($product['featured_image'])) { ?>
                                                <img src="<?php echo base_url('uploads/products/' . $product['featured_image']); ?>" alt="Featured Image" style="max-width: 150px; margin-top: 10px;">
                                                <input type="hidden" name="existing_featured_image" value="<?php echo $product['featured_image']; ?>">
                                            <?php } ?>

                                            <!-- Preview for Featured Image -->
                                            <img id="featured_image_preview" src="#" alt="Preview Featured Image" style="max-width: 150px; margin-top: 10px; display: none;">
                                        </div>

                                        <!-- Gallery Images Upload -->
                                        <div class="form-group">
                                            <label for="gallery_images">Gallery Images (you can select multiple)</label>
                                            <input type="file" name="gallery_images[]" id="gallery_images" class="form-control" accept="image/*" multiple>

                                            <!-- Show existing gallery images if available -->
                                            <?php if ($isUpdate && !empty($product['gallery_images'])) {
                                                $gallery_images = json_decode($product['gallery_images']);
                                                foreach ($gallery_images as $image) { ?>
                                                    <img src="<?php echo base_url('uploads/products/' . $image); ?>" alt="Gallery Image" style="max-width: 100px; margin-right: 10px; margin-bottom: 10px;">
                                            <?php }
                                            } ?>

                                            <!-- Preview for Gallery Images -->
                                            <div id="gallery_images_preview" style="margin-top: 10px;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="highlight_text">Highlight</label>
                                            <textarea class="form-control" id="highlight_text" name="highlight_text"><?php echo set_value('highlight_text', isset($product['highlight_text']) ? $product['highlight_text'] : ''); ?></textarea>
                                            <?php echo form_error('highlight_text'); ?>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control editor" id="description" name="description"><?php echo set_value('description', isset($product['description']) ? $product['description'] : ''); ?></textarea>
                                            <?php echo form_error('description'); ?>
                                        </div>
                                    </div> -->

                                    <?php if ($isUpdate) { ?>
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    <?php } else { ?>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    <?php } ?>
                                </div>
                            </form>
                            <!-- <div class="col-md-4">
                                    <div class="all_products_of_category"></div>
                                </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" role="dialog" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBrandModalLabel">Add Brand</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addBrandForm">
                    <div class="form-group">
                        <label for="brand_name">Brand Name</label>
                        <input type="text" class="form-control" id="brand_name" name="brand_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Brand</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Include this in your view file (inside <script> tags or a JS file) -->

<script>
    $(document).ready(function() {

        setTimeout(() => {
            $('#name').change();
        }, 1000);

        $('#name').on('keyup', function() {
            var productName = $(this).val();
            var slug = productName.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // Remove invalid characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-'); // Replace multiple hyphens with a single hyphen

            $('#slug').val(slug); // Set the generated slug value in the slug input
            $('.slug_text').text(slug); // Set the generated slug value in the slug input
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Preview for featured image
        $('#featured_image').change(function(e) {
            const file = e.target.files[0];
            if (file && file.type.match('image.*')) {
                const preview = $('#featured_image_preview');
                preview.attr('src', URL.createObjectURL(file));
                preview.show();
            } else {
                alert('Please select a valid image file (jpg, jpeg, png, gif)');
                $(this).val(''); // Clear the input
            }
        });

        // Preview for gallery images
        $('#gallery_images').change(function(e) {
            const files = e.target.files;
            const previewContainer = $('#gallery_images_preview');
            previewContainer.empty(); // Clear previous images

            if (files.length > 0) {
                $.each(files, function(i, file) {
                    if (file.type.match('image.*')) {
                        const img = $('<img />', {
                            src: URL.createObjectURL(file),
                            css: {
                                'max-width': '100px',
                                'margin-right': '10px',
                                'margin-bottom': '10px'
                            }
                        });
                        previewContainer.append(img);
                    } else {
                        alert('Please select only image files (jpg, jpeg, png, gif)');
                        $('#gallery_images').val(''); // Clear the input
                        return false; // Exit the loop if any non-image file is found
                    }
                });
            }
        });
    });


    $(document).ready(function() {
        function updateProductName() {
            /* var brandName = $('#brand_id option:selected').text();
            var productName = $.trim($('#name').val());
            $('#name').val(brandName + " "); */
        }

        $('#brand_id').change(function() {
            updateProductName();
        });

        $('#category_id').change(function() {

            var categoryId = $(this).val();
            if (categoryId) {
                $.ajax({
                    url: "<?php echo base_url('admin/products/getProductsByCategory'); ?>",
                    type: "POST",
                    data: {
                        category_id: categoryId
                    },
                    success: function(response) {
                        $('.all_products_of_category').html(response);
                    }
                });
            } else {
                $('.all_products_of_category').html('');
            }
        });

        // Show the modal when the "Add Brand" link is clicked
        $(".add_brand").on("click", function() {
            $("#addBrandModal").modal("show");
        });

        // Handle the form submission for adding a new brand
        $("#addBrandForm").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?php echo base_url('admin/brands/add-ajax'); ?>", // Replace with your correct URL
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    console.log(response);

                    if (response.success) {
                        // Append the new brand to the dropdown
                        var newOption = $("<option></option>")
                            .attr("value", response.brand.id)
                            .text(response.brand.name);
                        $(".brand_id").append(newOption);

                        // Set the new brand as the selected option
                        $(".brand_id").val(response.brand.id).trigger("chosen:updated");
                        updateProductName();
                        // Close the modal
                        $("#addBrandModal").modal("hide");

                        // Clear the form for the next time
                        $("#addBrandForm")[0].reset();
                    } else {
                        alert("There was an error adding the brand. Please try again.");
                    }
                },
                error: function() {
                    alert("An error occurred. Please try again.");
                },
            });
        });

        $("#brand_id").change(function() {
            updateProductName();
        });

        // Show the modal when the "Add Category" link is clicked
        $(".add_category").on("click", function() {
            $("#addCategoryModal").modal("show");
        });

        // Handle the form submission for adding a new category
        $("#addCategoryForm").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: "<?php echo base_url('admin/categories/add-ajax'); ?>", // Replace with your correct URL
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    console.log(response);

                    //response = typeof response === "string" ? JSON.parse(response) : response;

                    if (response.success) {
                        // Append the new category to the dropdown
                        var newOption = $("<option></option>")
                            .attr("value", response.category.id)
                            .text(response.category.name);
                        $(".category_id").append(newOption);

                        // Set the new category as the selected option
                        $(".category_id").val(response.category.id).trigger("chosen:updated");

                        // Close the modal
                        $("#addCategoryModal").modal("hide");

                        // Clear the form for the next time
                        $("#addCategoryForm")[0].reset();
                    } else {
                        alert("There was an error adding the category. Please try again.");
                    }
                },
                error: function() {
                    alert("An error occurred. Please try again.");
                },
            });
        });

    });
</script>

<script>
    $(document).ready(function() {
        $('.editor').summernote({
            height: 200 // Adjust height to approximately match 5 rows
        });
    });
</script>