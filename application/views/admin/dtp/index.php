<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>All DTP Sales</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/dtp/add'); ?>" class="btn btn-primary">Add New</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('message')) : ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('message'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="card_header">
                        <form method="get" action="<?php echo base_url('admin/dtp'); ?>">
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="from_date">From Date</label>
                                    <input type="date" class="form-control" id="from_date" name="from_date" value="<?php echo $this->input->get('from_date', true) ?: date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="to_date">To Date</label>
                                    <input type="date" class="form-control" id="to_date" name="to_date" value="<?php echo $this->input->get('to_date', true) ?: date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="category">Category</label>
                                    <select class="form-control" id="category" name="category">
                                        <option value="">All</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" <?php echo ($this->input->get('category') == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo $category['cat_title']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label for="paid_status">Paid Status</label>
                                    <select class="form-control" id="paid_status" name="paid_status">
                                        <option value="">All</option>
                                        <option value="1" <?php echo ($this->input->get('paid_status') == '1') ? 'selected' : ''; ?>>Full Paid</option>
                                        <option value="2" <?php echo ($this->input->get('paid_status') == '2') ? 'selected' : ''; ?>>Partial</option>
                                        <option value="0" <?php echo ($this->input->get('paid_status') == '0') ? 'selected' : ''; ?>>Due</option>
                                    </select>
                                </div>
                                <!-- New Filter for Payment Mode -->
                                <div class="col-md-1">
                                    <label for="payment_mode">Payment Mode</label>
                                    <select class="form-control" id="payment_mode" name="payment_mode">
                                        <option value="">All</option>
                                        <option value="1" <?php echo ($this->input->get('payment_mode') == '1') ? 'selected' : ''; ?>>Cash</option>
                                        <option value="2" <?php echo ($this->input->get('payment_mode') == '2') ? 'selected' : ''; ?>>Online</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="created_by">Created By</label>
                                    <select class="form-control" id="created_by" name="created_by">
                                        <option value="">All</option>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?php echo $user['id']; ?>" <?php echo ($this->input->get('created_by') == $user['id']) ? 'selected' : ''; ?>>
                                                <?php echo $user['full_name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 mt-4 text-right">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="<?php echo base_url('admin/dtp'); ?>" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table id="commonTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service Description</th>
                                <th>Category</th>
                                <th>Service Charge</th>
                                <th>Paid Amount</th>
                                <th>Paid Status</th>
                                <th>Payment Mode</th>
                                <th>Service Date</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($dtp_services)) : ?>
                                <?php foreach ($dtp_services as $service) : ?>
                                    <tr>
                                        <td><?php echo $service['id']; ?></td>
                                        <td><?php echo $service['service_descriptions']; ?></td>
                                        <td>
                                            <?php
                                            $category = array_filter($categories, function ($cat) use ($service) {
                                                return $cat['id'] === $service['dtp_service_category_id'];
                                            });
                                            echo !empty($category) ? current($category)['cat_title'] : 'Unknown';
                                            ?>
                                        </td>
                                        <td>₹<?php echo number_format($service['service_charge'], 2); ?></td>
                                        <td>₹<?php echo number_format($service['paid_amount'], 2); ?></td>
                                        <td>
                                            <?php
                                            $status = ['Due', 'Full Paid', 'Partial'];
                                            echo $status[$service['paid_status']];
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($service['paid_status'] == 0) {
                                                echo '-';
                                            } else {
                                                $paymentModes = ['1' => 'Cash', '2' => 'Online'];
                                                echo isset($paymentModes[$service['payment_mode']]) ? $paymentModes[$service['payment_mode']] : 'Unknown';
                                            }

                                            ?>
                                        </td>
                                        <td><?php echo date('d-m-Y', strtotime($service['service_date'])); ?></td>
                                        <td>
                                            <?php
                                            $user = array_filter($users, function ($user_name) use ($service) {
                                                return $user_name['id'] === $service['created_by'];
                                            });
                                            echo !empty($user) ? current($user)['full_name'] : 'Unknown';
                                            ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('admin/dtp/edit/' . $service['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <?php if ($this->session->userdata('role') == 1) : ?>
                                                <a href="<?php echo base_url('admin/dtp/delete/' . $service['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service?');">Delete</a>
                                                <button type="button" class="btn btn-info btn-sm" onclick="viewLog(<?php echo $service['id']; ?>)">View Log</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="9">No services found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>Total</strong></td>
                                <td><strong>₹<?php echo number_format($total_service_charge, 2); ?></strong></td>
                                <td><strong>₹<?php echo number_format($total_paid_amount, 2); ?></strong></td>
                                <td colspan="5"></td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </section>
</div>

<!-- Log Modal -->
<div class="modal fade" id="logModal" tabindex="-1" aria-labelledby="logModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logModalLabel">Service Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="logData">
                <!-- Log data will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
    function viewLog(serviceId) {
        $.ajax({
            url: '<?php echo base_url("admin/dtp/get_log_data/"); ?>' + serviceId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var logData = response.log_data;
                    var logContent = '';

                    logData.forEach(function(log) {
                        // Display log meta information (who performed the action, when, and what type of action)
                        logContent += '<p><strong>' + log.made_by_name + '</strong> (' + log.created_at + ') - ' + getActionLabel(log.action) + '</p>';

                        logContent += '<pre>' + log.log_data + '</pre>';

                        logContent += '<hr>';
                    });

                    // Add the formatted log data to the modal
                    $('#logData').html(logContent);
                    $('#logModal').modal('show');
                } else {
                    alert('No log data found.');
                }
            },
            error: function() {
                alert('Error loading log data.');
            }
        });
    }

    // Helper function to get readable action labels
    function getActionLabel(action) {
        switch (action) {
            case '1':
                return 'Add';
            case '2':
                return 'Update';
            case '3':
                return 'Delete';
            case '4':
                return 'Refund';
            default:
                return 'Unknown Action';
        }
    }
</script>