<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>Income Head</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/income/head'); ?>" class="btn btn-primary">All Income Heads</a>
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
                            $action = base_url('admin/income/head/add');
                            if ($isUpdate) {
                                $action = base_url('admin/income/head/edit/') . $income_head['id'];
                            }
                            ?>
                            <form action="<?php echo $action; ?>" method="post">
                                <div class="form-group">
                                    <label for="head_title">Head Title</label>
                                    <input type="text" class="form-control" id="head_title" name="head_title" value="<?php echo set_value('head_title', isset($income_head['head_title']) ? $income_head['head_title'] : ''); ?>">
                                    <?php echo form_error('head_title'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description', isset($income_head['description']) ? $income_head['description'] : ''); ?></textarea>
                                    <?php echo form_error('description'); ?>
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