<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>Payment Method</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/PaymentMethods'); ?>" class="btn btn-primary">All Payment Methods</a>
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
                            // Determine form action URL based on create/update context
                            $action = base_url(($isUpdate) ? 'admin/PaymentMethods/edit/' . $payment_method['id'] : 'admin/PaymentMethods/add');
                            ?>
                            <form action="<?php echo $action; ?>" method="post">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?php echo set_value('title', isset($payment_method['title']) ? $payment_method['title'] : ''); ?>">
                                    <?php echo form_error('title'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="code">Code</label>
                                    <input type="text" class="form-control" id="code" name="code" value="<?php echo set_value('code', isset($payment_method['code']) ? $payment_method['code'] : ''); ?>">
                                    <?php echo form_error('code'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="1" <?php echo (isset($payment_method['status']) && $payment_method['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                                        <option value="0" <?php echo (isset($payment_method['status']) && $payment_method['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                                <?php if ($isUpdate) { ?>
                                    <input type="hidden" name="method_id" value="<?php echo $payment_method['id']; ?>">
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