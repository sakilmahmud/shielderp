<!-- views/admin/change_password.php -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Change Password</h1>
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
                            <?php if ($this->session->flashdata('message')): ?>
                                <div class="alert alert-success">
                                    <?php echo $this->session->flashdata('message'); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger">
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-6">
                                <?php echo form_open('admin/password/update', 'class="needs-validation"'); ?>
                                <div class="form-group">
                                    <label for="current_password">Current Password:</label>
                                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                                    <?php echo form_error('current_password', '<div class="text-danger">', '</div>'); ?>
                                </div>

                                <div class="form-group">
                                    <label for="new_password">New Password:</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                                    <?php echo form_error('new_password', '<div class="text-danger">', '</div>'); ?>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password:</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                                    <?php echo form_error('confirm_password', '<div class="text-danger">', '</div>'); ?>
                                </div>

                                <button type="submit" class="btn btn-primary">Change Password</button>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
