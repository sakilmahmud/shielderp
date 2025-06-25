<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>Brand</h2>
            <a href="<?php echo base_url('admin/brands'); ?>" class="btn btn-primary">All Brand</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            $action = base_url('admin/brands/add');
                            if ($isUpdate) {
                                $action = base_url('admin/brands/edit/') . $brand['id'];
                            }
                            ?>
                            <form action="<?php echo $action; ?>" method="post">
                                <div class="form-group mb-3">
                                    <label for="brand_name">Brand Name</label>
                                    <input type="text" class="form-control" id="brand_name" name="brand_name" value="<?php echo set_value('brand_name', isset($brand['brand_name']) ? $brand['brand_name'] : ''); ?>">
                                    <?php echo form_error('brand_name'); ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="brand_descriptions">Description</label>
                                    <textarea class="form-control" id="brand_descriptions" name="brand_descriptions"><?php echo set_value('brand_descriptions', isset($brand['brand_descriptions']) ? $brand['brand_descriptions'] : ''); ?></textarea>
                                    <?php echo form_error('brand_descriptions'); ?>
                                </div>
                                <?php if ($isUpdate) { ?>
                                    <input type="hidden" name="brand_id" value="<?php echo $brand['id']; ?>">
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