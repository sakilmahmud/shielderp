<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Add Supplier</h2>
            <a href="<?php echo base_url('admin/suppliers'); ?>" class="btn btn-primary">Suppliers</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="<?php echo base_url('admin/suppliers/add'); ?>" method="post">
                                <div class="form-group mb-3">
                                    <label for="supplier_name">Supplier Name</label>
                                    <input type="text" class="form-control" id="supplier_name" name="supplier_name" value="<?php echo set_value('supplier_name'); ?>" required>
                                    <?php echo form_error('supplier_name'); ?>
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
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" required><?php echo set_value('address'); ?></textarea>
                                    <?php echo form_error('address'); ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="gst_number">GST Number</label>
                                    <input type="text" class="form-control" id="gst_number" name="gst_number" value="<?php echo set_value('gst_number'); ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Add Supplier</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>