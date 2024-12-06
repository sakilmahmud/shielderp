<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <?php if ($isUpdate) { ?>
                        <h2>Edit Client</h2>
                    <?php } else { ?>
                        <h2>Add Client</h2>
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
                            <?php if ($isUpdate) { ?>
                                <form action="<?php echo base_url('admin/clients/edit/') . $client['id']; ?>" method="post">
                                <?php } else { ?>
                                    <form action="<?php echo base_url('admin/clients/add'); ?>" method="post">
                                    <?php } ?>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username', isset($client['username']) ? $client['username'] : ''); ?>">
                                        <?php echo form_error('username'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email', isset($client['email']) ? $client['email'] : ''); ?>">
                                        <?php echo form_error('email'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile">Mobile</label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo set_value('mobile', isset($client['mobile']) ? $client['mobile'] : ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                        <?php echo form_error('password'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="full_name">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo set_value('full_name', isset($client['full_name']) ? $client['full_name'] : ''); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" id="address" name="address"><?php echo set_value('address', isset($client['address']) ? $client['address'] : ''); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="1" <?php echo set_select('status', '1', isset($client['status']) && $client['status'] == '1'); ?>>Active</option>
                                            <option value="0" <?php echo set_select('status', '0', isset($client['status']) && $client['status'] == '0'); ?>>Inactive</option>
                                        </select>
                                    </div>
                                    <?php if ($isUpdate) { ?>
                                        <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
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