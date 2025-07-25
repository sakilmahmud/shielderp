<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>Product</h2>
            <div>
                <a href="<?php echo base_url('admin/products/bulk-upload'); ?>" class="btn btn-info">Bulk Upload</a>
                <a href="<?php echo base_url('admin/products'); ?>" class="btn btn-primary">All Products</a>
            </div>
        </div>
    </section>

    <?php if (validation_errors()) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo validation_errors('<div>', '</div>'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error_msg')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $this->session->flashdata('error_msg'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($upload_error)) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $upload_error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <section class="content">
        <div class="container-fluid">
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
                                    <div class="col-md-12 mb-3">
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
                                    <div class="col-md-4 col-sm-12 mb-3">
                                        <!-- Purchase Price Field -->
                                        <div class="form-group">
                                            <label for="purchase_price">Purchase Price <sup>*</sup></label>
                                            <input type="number" class="form-control" id="purchase_price" name="purchase_price" value="<?php echo set_value('purchase_price', isset($product['purchase_price']) ? $product['purchase_price'] : '0.00'); ?>" required>
                                            <?php echo form_error('purchase_price'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 mb-3">
                                        <!-- Sale Price Field -->
                                        <div class="form-group">
                                            <label for="sale_price">Sale Price <sup>*</sup></label>
                                            <input type="number" class="form-control" id="sale_price" name="sale_price" value="<?php echo set_value('sale_price', isset($product['sale_price']) ? $product['sale_price'] : '0.00'); ?>" required>
                                            <?php echo form_error('sale_price'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 mb-3">
                                        <!-- Regular Price Field -->
                                        <div class="form-group">
                                            <label for="regular_price">MRP</label>
                                            <input type="number" class="form-control" id="regular_price" name="regular_price" value="<?php echo set_value('regular_price', isset($product['regular_price']) ? $product['regular_price'] : '0.00'); ?>">
                                            <?php echo form_error('regular_price'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 mb-3">
                                        <div class="form-group">
                                            <div class="my-1 d-flex justify-content-between">
                                                <label for="category_id">Category <sup>*</sup></label>
                                                <a href="javascript:void(0)" class="text-sm add_category">
                                                    <span class="badge bg-success">Add Category</span>
                                                </a>
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
                                    <div class="col-md-4 col-sm-12 mb-3">
                                        <div class="form-group">
                                            <div class="my-1 d-flex justify-content-between">
                                                <label for="brand_id">Brand</label>
                                                <a href="javascript:void(0)" class="text-sm add_brand">
                                                    <span class="badge bg-success">Add Brand</span>
                                                </a>
                                            </div>
                                            <select class="form-control brand_id" name="brand_id">
                                                <option value="">Select Brand</option>
                                                <?php foreach ($brands as $brand) { ?>
                                                    <option value="<?php echo $brand['id']; ?>" <?php echo set_select('brand_id', $brand['id'], isset($product['brand_id']) && $product['brand_id'] == $brand['id']); ?>><?php echo ucwords($brand['brand_name']); ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php echo form_error('brand_id'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label for="hsn_code_id">HSN Code</label>
                                            <div class="input-group">
                                                <select class="form-control" id="hsn_code_id" name="hsn_code_id">
                                                    <option value="">Select HSN</option>
                                                    <?php foreach ($hsn_codes as $hsn) { ?>
                                                        <option value="<?= $hsn['id']; ?>"
                                                            data-gst="<?= $hsn['gst_rate']; ?>"
                                                            <?= set_select('hsn_code_id', $hsn['id'], isset($product['hsn_code_id']) && $product['hsn_code_id'] == $hsn['id']) ?>>
                                                            <?= $hsn['hsn_code']; ?> - <?= $hsn['description']; ?> (<?= $hsn['gst_rate']; ?>%)
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <button type="button" class="btn btn-outline-secondary" id="add_hsn_code_btn">Add HSN</button>
                                            </div>
                                            <?php echo form_error('hsn_code_id'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label>CGST / SGST (%)</label>
                                            <input type="text" id="cgst_sgst_display" class="form-control" value="" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12 mb-3">
                                        <div class="form-group">
                                            <div class="mt-1 d-flex justify-content-between">
                                                <label for="product_type_id">Type</label>
                                                <a href="javascript:void(0)" class="text-sm add_product_type">Add Type</a>
                                            </div>
                                            <select class="form-control product_type_id" id="product_type_id" name="product_type_id">
                                                <option value="">Select product type</option>
                                                <?php foreach ($product_types as $product_type) {
                                                    $selected = ($product_type['id'] == $product['product_type_id']) ? 'selected' : '';
                                                ?>
                                                    <option value="<?php echo $product_type['id']; ?>" <?php echo $selected; ?>>
                                                        <?php echo $product_type['product_type_name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <?php echo form_error('product_type_id'); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-12 mb-3">
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
                                    <div class="col-md-2 col-sm-12 mb-3">
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
                            <div class="col-md-6 mb-3">
                                <!-- Featured Image Upload -->
                                <div class="form-group mb-3">
                                    <label for="featured_image">Featured Image</label>
                                    <input type="file" name="featured_image" id="featured_image" class="form-control" accept="image/*">
                                    <small class="text-muted d-block mt-1">Allowed types: jpg, jpeg, png, gif. Max size: 300KB</small>

                                    <!-- Show existing featured image if available -->
                                    <?php if ($isUpdate && !empty($product['featured_image'])) { ?>
                                        <img src="<?php echo base_url('uploads/products/' . $product['featured_image']); ?>" alt="Featured Image" style="max-width: 150px; margin-top: 10px;">
                                        <input type="hidden" name="existing_featured_image" value="<?php echo $product['featured_image']; ?>">
                                    <?php } ?>

                                    <!-- Preview for Featured Image -->
                                    <img id="featured_image_preview" src="#" alt="Preview Featured Image" style="max-width: 150px; margin-top: 10px; display: none;">
                                </div>

                                <!-- Gallery Images Upload -->
                                <div class="form-group mb-3">
                                    <label for="gallery_images">Gallery Images (you can select multiple)</label>
                                    <input type="file" name="gallery_images[]" id="gallery_images" class="form-control" accept="image/*" multiple>
                                    <small class="text-muted d-block mt-1">Allowed types: jpg, jpeg, png, gif. Max size per file: 300KB</small>

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

                            <div class="col-md-12 mb-3">
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
                            <div class="col-md-2 mb-3">
                                <?php if ($isUpdate) { ?>
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                <?php } else { ?>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                <?php } ?>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

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
        <?php if ($isUpdate) { ?>
            setTimeout(() => {
                $('#hsn_code_id').change();
            }, 1000);
        <?php } ?>
        // Update CGST/SGST display based on selected HSN
        $('#hsn_code_id').on('change', function() {
            const gst = $(this).find(':selected').data('gst');
            const halfGst = (gst / 2).toFixed(2);
            $('#cgst_sgst_display').val(`CGST: ${halfGst}%, SGST: ${halfGst}%`);
        });

        // Max allowed file size in bytes (300KB)
        const MAX_FILE_SIZE = 300 * 1024;

        // Preview for featured image
        $('#featured_image').change(function(e) {
            const file = e.target.files[0];
            if (file) {
                if (!file.type.match('image.*')) {
                    alert('Please select a valid image file (jpg, jpeg, png, gif)');
                    $(this).val('');
                    return;
                }

                /*if (file.size > MAX_FILE_SIZE) {
                    alert('Featured image must be less than 300KB.');
                    $(this).val('');
                    return;
                }*/

                const preview = $('#featured_image_preview');
                preview.attr('src', URL.createObjectURL(file));
                preview.show();
            }
        });

        // Preview for gallery images
        $('#gallery_images').change(function(e) {
            const files = e.target.files;
            const previewContainer = $('#gallery_images_preview');
            previewContainer.empty(); // Clear previous images

            if (files.length > 0) {
                let valid = true;

                $.each(files, function(i, file) {
                    if (!file.type.match('image.*')) {
                        alert('Please select only image files (jpg, jpeg, png, gif).');
                        $('#gallery_images').val('');
                        valid = false;
                        return false; // Break loop
                    }

                    if (file.size > MAX_FILE_SIZE) {
                        alert(`Gallery image "${file.name}" exceeds 300KB. Please choose smaller images.`);
                        $('#gallery_images').val('');
                        valid = false;
                        return false; // Break loop
                    }
                });

                if (valid) {
                    $.each(files, function(i, file) {
                        const img = $('<img />', {
                            src: URL.createObjectURL(file),
                            css: {
                                'max-width': '100px',
                                'margin-right': '10px',
                                'margin-bottom': '10px'
                            }
                        });
                        previewContainer.append(img);
                    });
                }
            }
        });

    });
</script>