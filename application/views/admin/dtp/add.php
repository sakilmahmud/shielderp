<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>DTP Service</h2>
            <a href="<?php echo base_url('admin/dtp'); ?>" class="btn btn-primary">All Services</a>
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="service_descriptions">Service Description</label>
                                            <input type="text" class="form-control" id="service_descriptions" name="service_descriptions" value="<?php echo set_value('service_descriptions', isset($service['service_descriptions']) ? $service['service_descriptions'] : ''); ?>" required>
                                            <?php echo form_error('service_descriptions'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="dtp_service_categories">Category</label>
                                            <select class="form-control" id="dtp_service_categories" name="dtp_service_categories">
                                                <option value="">Select Category</option>
                                                <?php foreach ($dtp_categories as $category) : ?>
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
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="service_charge">Service Charge</label>
                                            <input type="number" class="form-control" id="service_charge" name="service_charge" value="<?php echo set_value('service_charge', isset($service['service_charge']) ? $service['service_charge'] : ''); ?>" required>
                                            <?php echo form_error('service_charge'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- New Payment Mode Field -->
                                        <div class="form-group mb-3 payment_method_section">
                                            <label for="payment_mode">Payment Mode</label>
                                            <select class="form-control payment_method_id" name="payment_mode">
                                                <?php if (!empty($payment_methods)) :
                                                    foreach ($payment_methods as $index => $paymentMode) :
                                                        $selected = (isset($service['payment_mode']) && $service['payment_mode'] == $paymentMode['id']) ? 'selected' : '';
                                                        echo '<option value="' . $paymentMode['id'] . '" ' . $selected . '>' . $paymentMode['title'] . '</option>';
                                                    endforeach;
                                                endif; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
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
                                        <!-- Hidden input for Partial Paid -->
                                        <div class="form-group mb-3" id="partial_paid_container" style="display: none;">
                                            <label for="partial_paid">Partial Paid Amount</label>
                                            <input type="number" class="form-control" id="partial_paid" name="partial_paid" value="<?php echo set_value('partial_paid', isset($service['paid_amount']) ? $service['paid_amount'] : ''); ?>">
                                            <?php echo form_error('partial_paid'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="service_date">Service Date</label>
                                            <input type="date" class="form-control" id="service_date" name="service_date" value="<?php echo set_value('service_date', isset($service['service_date']) ? $service['service_date'] : date('Y-m-d')); ?>">
                                            <?php echo form_error('service_date'); ?>
                                        </div>
                                    </div>
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
    $(document).ready(function() {
        // Set focus to the Service Description field on page load
        $('#service_descriptions').focus();

        // Show/Hide the Partial Paid input based on Paid Status
        $('input[name="paid_status"]').change(function() {
            if ($(this).val() == '2') {
                $('#partial_paid_container').show();
                $('#partial_paid').prop('required', true);
            } else {
                $('#partial_paid_container').hide();
                $('#partial_paid').prop('required', false);
            }
        });

        // Trigger change event on page load to handle pre-selected Paid Status
        $('input[name="paid_status"]:checked').trigger('change');
    });
</script>