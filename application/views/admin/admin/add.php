<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <?php if ($isUpdate) { ?>
                    <h2>Edit Admin User</h2>
                <?php } else { ?>
                    <h2>Add Admin User</h2>
                <?php } ?>
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
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <?php if ($isUpdate) { ?>
                                <form action="<?php echo base_url('admin/adminAccounts/edit/') . $user['id']; ?>" method="post" enctype="multipart/form-data">
                            <?php } else { ?>
                                <form action="<?php echo base_url('admin/adminAccounts/add'); ?>" method="post" enctype="multipart/form-data">
                            <?php } ?>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="<?php echo set_value('username', isset($user['username']) ? $user['username'] : ''); ?>" <?php echo ($isUpdate) ? 'readonly' : ''; ?>>
                                <?php echo form_error('username'); // Display validation error for username ?>
                            </div>
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter full name" value="<?php echo set_value('full_name', isset($user['full_name']) ? $user['full_name'] : ''); ?>">
                                <?php echo form_error('full_name'); // Display validation error for full_name ?>
                            </div>
                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter mobile number" value="<?php echo set_value('mobile', isset($user['mobile']) ? $user['mobile'] : ''); ?>" <?php echo ($isUpdate) ? 'readonly' : ''; ?>>
                                <?php echo form_error('mobile'); // Display validation error for mobile ?>
                            </div>
                            <?php if ($isUpdate) { ?>
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
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
