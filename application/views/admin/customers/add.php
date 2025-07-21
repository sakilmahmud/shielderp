<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Add Customer</h2>
            <a href="<?php echo base_url('admin/customers'); ?>" class="btn btn-primary">Customers</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="<?php echo base_url('admin/customers/add'); ?>" method="post">
                                <div class="form-group mb-3">
                                    <label for="customer_name">Customer Name</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo set_value('customer_name'); ?>" required>
                                    <?php echo form_error('customer_name'); ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo set_value('phone'); ?>" required>
                                    <?php echo form_error('phone'); ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="gst_number">GST</label>
                                    <input type="text" class="form-control" id="gst_number" name="gst_number" value="<?php echo set_value('gst_number'); ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" required><?php echo set_value('address'); ?></textarea>
                                    <?php echo form_error('address'); ?>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="state_id">State</label>
                                    <select name="state_id" id="state_id" class="form-control" required>
                                        <option value="">Select State</option>
                                        <?php foreach ($states as $state): ?>
                                            <option value="<?php echo $state['id']; ?>" <?php echo (set_value('state_id', $company_state) == $state['id']) ? 'selected' : ''; ?>><?php echo $state['state_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('state_id'); ?>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Customer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>