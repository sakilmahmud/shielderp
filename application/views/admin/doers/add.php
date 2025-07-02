<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>Doer</h2>
            <a href="<?php echo base_url('admin/doers'); ?>" class="btn btn-info">All Doers</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if (!empty(validation_errors())) : ?>
                                <div class="alert alert-danger"><?php echo validation_errors(); ?></div>
                            <?php endif; ?>
                            <?php
                            $action = base_url('admin/doers/add');
                            if ($isUpdate) {
                                $action = base_url('admin/doers/edit/') . $doer['id'];
                            }
                            ?>
                            <form action="<?php echo $action; ?>" method="post">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username', isset($doer['username']) ? $doer['username'] : ''); ?>">
                                    <?php echo form_error('username'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email', isset($doer['email']) ? $doer['email'] : ''); ?>">
                                    <?php echo form_error('email'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="mobile">Mobile</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo set_value('mobile', isset($doer['mobile']) ? $doer['mobile'] : ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                    <?php echo form_error('password'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="full_name">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo set_value('full_name', isset($doer['full_name']) ? $doer['full_name'] : ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1" <?php echo set_select('status', '1', isset($doer['status']) && $doer['status'] == '1'); ?>>Active</option>
                                        <option value="0" <?php echo set_select('status', '0', isset($doer['status']) && $doer['status'] == '0'); ?>>Inactive</option>
                                    </select>
                                </div>
                                <div class="form-group mt-3">
                                    <?php if ($isUpdate) { ?>
                                        <input type="hidden" name="doer_id" value="<?php echo $doer['id']; ?>">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    <?php } else { ?>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>