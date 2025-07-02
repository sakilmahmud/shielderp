<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Add Client</h2>
            <a href="<?php echo base_url('admin/clients'); ?>" class="btn btn-info">All Clients</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo base_url('admin/clients/add'); ?>" method="post">
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo set_value('mobile', isset($client['mobile']) ? $client['mobile'] : ''); ?>">
                            <?php echo form_error('mobile'); ?>
                        </div>
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo set_value('full_name', isset($client['full_name']) ? $client['full_name'] : ''); ?>">
                            <?php echo form_error('full_name'); ?>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address"></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>