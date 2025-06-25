<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>Category</h2>
            <a href="<?php echo base_url('admin/categories'); ?>" class="btn btn-primary">Categories</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            $action = base_url('admin/categories/add');
                            if ($isUpdate) {
                                $action = base_url('admin/categories/edit/') . $category['id'];
                            }
                            ?>
                            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

                                <div class="form-group mb-3">
                                    <label for="name">Category Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name', isset($category['name']) ? $category['name'] : ''); ?>">
                                    <?php echo form_error('name'); ?>
                                    <div class="d-flex mt-2">
                                        <span><?php echo base_url('categories/'); ?></span>
                                        <span class="slug_text"><?php echo set_value('slug', isset($category['slug']) ? $category['slug'] : ''); ?></span>
                                        <input type="hidden" id="slug" name="slug" value="<?php echo set_value('slug', isset($category['slug']) ? $category['slug'] : ''); ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description"><?php echo set_value('description', isset($category['description']) ? $category['description'] : ''); ?></textarea>
                                    <?php echo form_error('description'); ?>
                                </div>

                                <!-- Featured Image Upload -->
                                <div class="form-group mb-3">
                                    <label for="featured_image">Featured Image</label>
                                    <input type="file" name="featured_image" id="featured_image" class="form-control" accept="image/*">

                                    <!-- Show existing featured image if available -->
                                    <?php if ($isUpdate && !empty($category['featured_image'])) { ?>
                                        <img src="<?php echo base_url('uploads/categories/' . $category['featured_image']); ?>" alt="Featured Image" style="max-width: 150px; margin-top: 10px;">
                                        <input type="hidden" name="existing_featured_image" value="<?php echo $category['featured_image']; ?>">
                                    <?php } ?>

                                    <!-- Preview for Featured Image -->
                                    <img id="featured_image_preview" src="#" alt="Preview Featured Image" style="max-width: 100px; margin-top: 10px; display: none;">
                                </div>

                                <?php if ($isUpdate) { ?>
                                    <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                <?php } else { ?>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
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
    });
</script>