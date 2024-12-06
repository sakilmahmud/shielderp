<!-- views/admin/bank_details.php -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>Bank Details</h1>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <?php echo form_open('admin/settings/update'); ?>

                <!-- Bank Name -->
                <div class="form-group">
                    <label for="bank_name">Bank Name:</label>
                    <input type="text" name="bank_name" value="<?php echo $settings['bank_name']; ?>" class="form-control">
                </div>

                <!-- Account Number -->
                <div class="form-group">
                    <label for="account_no">Account Number:</label>
                    <input type="text" name="account_no" value="<?php echo $settings['account_no']; ?>" class="form-control">
                </div>

                <!-- IFSC Code -->
                <div class="form-group">
                    <label for="ifsc_code">IFSC Code:</label>
                    <input type="text" name="ifsc_code" value="<?php echo $settings['ifsc_code']; ?>" class="form-control">
                </div>

                <!-- Branch -->
                <div class="form-group">
                    <label for="branch">Branch:</label>
                    <input type="text" name="branch" value="<?php echo $settings['branch']; ?>" class="form-control">
                </div>

                <!-- Terms -->
                <div class="form-group">
                    <label for="terms">Terms:</label>
                    <textarea name="terms" class="form-control"><?php echo $settings['terms']; ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save Bank Details</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </section>
</div>