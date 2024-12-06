<!-- views/admin/company_details.php -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>Company Details</h1>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <?php echo form_open('admin/settings/update'); ?>

                <!-- Company Address -->
                <div class="form-group">
                    <label for="company_address">Address:</label>
                    <textarea name="company_address" class="form-control"><?php echo $settings['company_address']; ?></textarea>
                </div>

                <!-- Company Contact -->
                <div class="form-group">
                    <label for="company_contact">Contact:</label>
                    <input type="text" name="company_contact" value="<?php echo $settings['company_contact']; ?>" class="form-control">
                </div>

                <!-- Company Email -->
                <div class="form-group">
                    <label for="company_email">Email:</label>
                    <input type="email" name="company_email" value="<?php echo $settings['company_email']; ?>" class="form-control">
                </div>

                <!-- Company Website -->
                <div class="form-group">
                    <label for="company_website">Website:</label>
                    <input type="text" name="company_website" value="<?php echo $settings['company_website']; ?>" class="form-control">
                </div>

                <!-- Company GSTIN -->
                <div class="form-group">
                    <label for="company_gstin">GSTIN:</label>
                    <input type="text" name="company_gstin" value="<?php echo $settings['company_gstin']; ?>" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Save Company Details</button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </section>
</div>