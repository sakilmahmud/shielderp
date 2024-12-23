<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>DTP Service</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/dtp'); ?>" class="btn btn-primary">All Services</a>
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
                            $action = base_url('admin/dtp/add');
                            if ($isUpdate) {
                                $action = base_url('admin/dtp/edit/') . $service['id'];
                            }
                            ?>
                            <form action="<?php echo $action; ?>" method="post">
                                <div class="form-group">
                                    <label for="service_descriptions">Service Description</label>
                                    <input type="text" class="form-control" id="service_descriptions" name="service_descriptions" value="<?php echo set_value('service_descriptions', isset($service['service_descriptions']) ? $service['service_descriptions'] : ''); ?>">
                                    <?php echo form_error('service_descriptions'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="dtp_service_categories">Category</label>
                                    <select class="form-control" id="dtp_service_categories" name="dtp_service_categories">
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?php echo $category['id']; ?>"
                                                <?php
                                                echo set_select(
                                                    'dtp_service_categories',
                                                    $category['id'],
                                                    isset($service['dtp_service_category_id'])
                                                        ? $service['dtp_service_category_id'] == $category['id']
                                                        : $category['id'] == 1 // Default to category with ID 1
                                                );
                                                ?>>
                                                <?php echo $category['cat_title']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('dtp_service_categories'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="service_charge">Service Charge</label>
                                    <input type="number" class="form-control" id="service_charge" name="service_charge" value="<?php echo set_value('service_charge', isset($service['service_charge']) ? $service['service_charge'] : ''); ?>">
                                    <?php echo form_error('service_charge'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="paid_status">Paid Status</label><br>
                                    <label>
                                        <input type="radio" name="paid_status" value="1"
                                            <?php echo set_radio('paid_status', '1', isset($service['paid_status'])
                                                ? $service['paid_status'] == 1
                                                : true); // Default to "Full Paid"
                                            ?>> Full Paid
                                    </label>
                                    <label>
                                        <input type="radio" name="paid_status" value="2"
                                            <?php echo set_radio('paid_status', '2', isset($service['paid_status'])
                                                ? $service['paid_status'] == 2
                                                : false);
                                            ?>> Partial
                                    </label>
                                    <label>
                                        <input type="radio" name="paid_status" value="0"
                                            <?php echo set_radio('paid_status', '0', isset($service['paid_status'])
                                                ? $service['paid_status'] == 0
                                                : false);
                                            ?>> Due
                                    </label>
                                    <?php echo form_error('paid_status'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="service_date">Service Date</label>
                                    <input type="date" class="form-control" id="service_date" name="service_date" value="<?php echo set_value('service_date', isset($service['service_date']) ? $service['service_date'] : date('Y-m-d')); ?>">
                                    <?php echo form_error('service_date'); ?>
                                </div>
                                <button type="submit" class="btn btn-primary"><?php echo ($isUpdate) ? "Update" : "Add"; ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    // Set focus to the Service Description field on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('service_descriptions').focus();
    });
</script>