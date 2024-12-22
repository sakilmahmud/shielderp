<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>Category</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/dtp/categories'); ?>" class="btn btn-primary">All Categories</a>
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
                            $action = base_url('admin/dtp/categories/add');
                            if ($isUpdate) {
                                $action = base_url('admin/dtp/categories/edit/') . $category['id'];
                            }
                            ?>
                            <form action="<?php echo $action; ?>" method="post">
                                <div class="form-group">
                                    <label for="category_name">Category Name</label>
                                    <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo set_value('category_name', isset($category['cat_title']) ? $category['cat_title'] : ''); ?>">
                                    <?php echo form_error('category_name'); ?>
                                </div>
                                <button type="submit" class="btn btn-primary"><?php echo ($isUpdate) ? "Update" : "Add"; ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>