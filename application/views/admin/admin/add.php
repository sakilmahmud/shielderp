<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>Admin User</h2>
            <a href="<?php echo base_url('admin/adminAccounts'); ?>" class="btn btn-info">Admin Users</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <?php
                            $action = base_url('admin/adminAccounts/add');
                            if ($isUpdate) {
                                $action = base_url('admin/adminAccounts/edit/') . $user['id'];
                            }
                            ?>
                            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="<?php echo set_value('username', isset($user['username']) ? $user['username'] : ''); ?>">
                                    <?php echo form_error('username'); // Display validation error for username 
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label for="full_name">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter full name" value="<?php echo set_value('full_name', isset($user['full_name']) ? $user['full_name'] : ''); ?>">
                                    <?php echo form_error('full_name'); // Display validation error for full_name 
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label for="mobile">Mobile</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter mobile number" value="<?php echo set_value('mobile', isset($user['mobile']) ? $user['mobile'] : ''); ?>">
                                    <?php echo form_error('mobile'); // Display validation error for mobile 
                                    ?>
                                </div>
                                <?php if ($isUpdate) { ?>
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                                <?php } else { ?>
                                    <button type="submit" class="btn btn-primary mt-3">Add</button>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>