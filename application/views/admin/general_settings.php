<!-- views/admin/settings.php -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>General Settings</h1>
                </div>
            </div>
        </div>
    </section>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php echo form_open_multipart('admin/settings/update', 'class="needs-validation"'); ?>


                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="frontend_logo">Frontend Logo:</label>
                                        <input type="file" name="frontend_logo" id="frontend_logo" class="form-control">
                                        <img id="frontend_logo_preview" src="<?php echo isset($settings['frontend_logo']) ? base_url($settings['frontend_logo']) : ''; ?>" alt="Frontend Logo" width="150">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="admin_logo">Admin Logo:</label>
                                        <input type="file" name="admin_logo" id="admin_logo" class="form-control">
                                        <img id="admin_logo_preview" src="<?php echo isset($settings['admin_logo']) ? base_url($settings['admin_logo']) : ''; ?>" alt="Admin Logo" width="150">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="owner_sign">Owner Signature:</label>
                                        <input type="file" name="owner_sign" id="owner_sign" class="form-control">
                                        <img id="owner_sign_preview" src="<?php echo isset($settings['owner_sign']) ? base_url($settings['owner_sign']) : ''; ?>" alt="owner sign" width="150">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="site_title">Site Title:</label>
                                        <input type="text" name="site_title" id="site_title" class="form-control" value="<?php echo isset($settings['site_title']) ? $settings['site_title'] : ''; ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="admin_title">Admin Title:</label>
                                        <input type="text" name="admin_title" id="admin_title" class="form-control" value="<?php echo isset($settings['admin_title']) ? $settings['admin_title'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Invoice Prefix:</label>
                                        <input type="text" name="invoice_prefix" class="form-control" value="<?php echo isset($settings['invoice_prefix']) ? $settings['invoice_prefix'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>GST bill as default:</label>
                                        <select class="form-control" name="is_gst_bill">
                                            <option value="0" <?php echo $settings['is_gst_bill'] == 0 ? 'SELECTED' : ''; ?>>No</option>
                                            <option value="1" <?php echo $settings['is_gst_bill'] == 1 ? 'SELECTED' : ''; ?>>Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>GST Rate:</label>
                                        <input type="text" name="default_gst_rate" class="form-control" value="<?php echo isset($settings['default_gst_rate']) ? $settings['default_gst_rate'] : 0; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="header_script">Header Script:</label>
                                <textarea name="header_script" id="header_script" class="form-control" rows="5"><?php echo isset($settings['header_script']) ? $settings['header_script'] : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="footer_text">Footer Text:</label>
                                <textarea name="footer_text" id="footer_text" class="form-control" rows="5"><?php echo isset($settings['footer_text']) ? $settings['footer_text'] : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="footer_script">Footer Script:</label>
                                <textarea name="footer_script" id="footer_script" class="form-control" rows="5"><?php echo isset($settings['footer_script']) ? $settings['footer_script'] : ''; ?></textarea>
                            </div>

                            <!-- <div class="form-group">
                                <label for="premium_account_commission">Premium Account Commission:</label>
                                <input type="text" name="premium_account_commission" id="premium_account_commission" class="form-control" value="<?php echo isset($settings['premium_account_commission']) ? $settings['premium_account_commission'] : ''; ?>" >
                            </div> -->

                            <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    // JavaScript to handle file input change and image preview
    function readURL(input, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#' + imgId).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#frontend_logo").change(function() {
        readURL(this, 'frontend_logo_preview');
    });

    $("#admin_logo").change(function() {
        readURL(this, 'admin_logo_preview');
    });

    $("#owner_sign").change(function() {
        readURL(this, 'owner_sign_preview');
    });
</script>