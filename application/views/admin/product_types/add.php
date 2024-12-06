<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>Product Type</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/product-types'); ?>" class="btn btn-primary">All Product Type</a>
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
                            $action = base_url('admin/product-type/add');
                            if ($isUpdate) {
                                $action = base_url('admin/product-type/edit/') . $product_type['id'];
                            }
                            ?>
                            <form action="<?php echo $action; ?>" method="post">
                                <div class="form-group">
                                    <label for="product_type_name">Product Type Name</label>
                                    <input type="text" class="form-control" id="product_type_name" name="product_type_name" value="<?php echo set_value('product_type_name', isset($product_type['product_type_name']) ? $product_type['product_type_name'] : ''); ?>">
                                    <?php echo form_error('product_type_name'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="product_type_descriptions">Description</label>
                                    <textarea class="form-control" id="product_type_descriptions" name="product_type_descriptions"><?php echo set_value('product_type_descriptions', isset($product_type['product_type_descriptions']) ? $product_type['product_type_descriptions'] : ''); ?></textarea>
                                    <?php echo form_error('product_type_descriptions'); ?>
                                </div>
                                <?php if ($isUpdate) { ?>
                                    <input type="hidden" name="product_type_id" value="<?php echo $product_type['id']; ?>">
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