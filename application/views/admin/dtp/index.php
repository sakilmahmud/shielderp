<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>All DTP Sales</h2>
            <a href="<?php echo base_url('admin/dtp/add'); ?>" class="btn btn-primary">Add New</a>
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
                    <?php if ($this->session->flashdata('payment')) : ?>
                        <div class="alert alert-info">
                            <?php echo $this->session->flashdata('payment'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="card_header">
                        <form method="get" action="<?php echo base_url('admin/dtp'); ?>" id="filterForm">
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="from_date">From Date</label>
                                    <input type="date" class="form-control filter-input" id="from_date" name="from_date" value="<?php echo $this->input->get('from_date', true) ?: date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="to_date">To Date</label>
                                    <input type="date" class="form-control filter-input" id="to_date" name="to_date" value="<?php echo $this->input->get('to_date', true) ?: date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="category">Category</label>
                                    <select class="form-control filter-input" id="category" name="category">
                                        <option value="">All</option>
                                        <?php foreach ($dtp_categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" <?php echo ($this->input->get('category') == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo $category['cat_title']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label for="paid_status">Paid Status</label>
                                    <select class="form-control filter-input" id="paid_status" name="paid_status">
                                        <option value="">All</option>
                                        <option value="1" <?php echo ($this->input->get('paid_status') == '1') ? 'selected' : ''; ?>>Full Paid</option>
                                        <option value="2" <?php echo ($this->input->get('paid_status') == '2') ? 'selected' : ''; ?>>Partial</option>
                                        <option value="0" <?php echo ($this->input->get('paid_status') == '0') ? 'selected' : ''; ?>>Due</option>
                                    </select>
                                </div>
                                <!-- New Filter for Payment Mode -->
                                <div class="col-md-2">
                                    <!-- Payment Method -->
                                    <div class="form-group">
                                        <label for="payment_method_id">Payment Method</label>
                                        <select class="form-control filter-input" name="payment_method_id">
                                            <option value="">All</option>
                                            <?php foreach ($payment_methods as $method) : ?>
                                                <option value="<?php echo $method['id']; ?>" <?php echo set_select('payment_method_id', $method['id'], isset($income['payment_method_id']) && $income['payment_method_id'] == $method['id']); ?>>
                                                    <?php echo $method['title']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php echo form_error('payment_method_id'); ?>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label for="created_by">Created By</label>
                                    <select class="form-control filter-input" id="created_by" name="created_by">
                                        <option value="">All</option>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?php echo $user['id']; ?>" <?php echo ($this->input->get('created_by') == $user['id']) ? 'selected' : ''; ?>>
                                                <?php echo substr($user['full_name'], 0, 7); ?>
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
                    <table id="dtpTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service</th>
                                <th>Type</th>
                                <th>Charge</th>
                                <th>Paid</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Date</th>
                                <th>Made By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>Total</strong></td>
                                <td><strong id="total_service_charge">₹0.00</strong></td>
                                <td><strong id="total_paid_amount">₹0.00</strong></td>
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
    $(document).ready(function() {
        const table = $('#dtpTable').DataTable({
            processing: true,
            serverSide: true,
            order: false, // Disable default ordering
            pageLength: 100, // Set default items per page
            lengthMenu: [
                [100, -1],
                [100, "All"]
            ], // Options for 100 items or All
            ajax: {
                url: "<?php echo base_url('admin/dtp/fetch'); ?>",
                type: "POST",
                data: function(d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.created_by = $('#created_by').val();
                    d.category = $('#category').val();
                    d.paid_status = $('#paid_status').val();
                    d.payment_mode = $('select[name="payment_method_id"]').val();
                },
                dataSrc: function(json) {
                    // Update totals in tfoot
                    $('#total_service_charge').text(json.footer.total_service_charge);
                    $('#total_paid_amount').text(json.footer.total_paid_amount);
                    return json.data;
                }
            },
            columns: [{
                    data: 0,
                    width: "4%"
                }, // ID
                {
                    data: 1,
                    width: "20%"
                }, // Service Description
                {
                    data: 2,
                    width: "10%"
                }, // Category
                {
                    data: 3,
                    width: "6%"
                }, // Service Charge
                {
                    data: 4,
                    width: "6%"
                }, // Paid Amount
                {
                    data: 5,
                    width: "6%"
                }, // Paid Status
                {
                    data: 6,
                    width: "10%"
                }, // Payment Mode
                {
                    data: 7,
                    width: "7%"
                }, // Service Date
                {
                    data: 8,
                    width: "10%"
                }, // Created By
                {
                    data: 9,
                    width: "20%"
                } // Actions
            ],
            autoWidth: false, // Disable automatic width calculation
        });

        // Re-fetch data when any filter input changes
        $('.filter-input').on('change', function() {
            table.ajax.reload();
        });

        // Re-fetch data on form submission
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });
    });


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