<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Invoices</h2>
            <a href="<?php echo base_url('admin/invoices/create'); ?>" class="btn btn-primary">Create Invoice</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form id="filterForm" class="mb-2 bg-light p-1">
                        <div class="row">
                            <div class="col-6 col-md-2">
                                <input type="date" id="from_date" class="form-control filter-input" value="<?php echo date('Y-m-d', strtotime('-15 days')); ?>">
                            </div>
                            <div class="col-6 col-md-2">
                                <input type="date" id="to_date" class="form-control filter-input" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-6 col-md-2 mt-2 mt-md-0">
                                <select id="payment_status" class="form-control filter-input">
                                    <option value="">Payment Status</option>
                                    <option value="0">Due</option>
                                    <option value="1">Paid</option>
                                    <option value="2">Partial</option>
                                    <option value="3">Return</option>
                                </select>
                            </div>
                            <div class="d-none d-md-block col-md-2 mt-md-0">
                                <select id="type" class="form-control filter-input">
                                    <option value="">Type</option>
                                    <option value="0">NON-GST</option>
                                    <option value="1">GST</option>
                                </select>
                            </div>
                            <div class="d-none d-md-block col-md-2 mt-md-0">
                                <select id="created_by" class="form-control filter-input">
                                    <option value="">Created By</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?php echo $user['id']; ?>"><?php echo $user['full_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6 col-md-2 mt-2 mt-md-0">
                                <button type="button" id="resetFilter" class="btn btn-secondary btn-sm">Reset</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table id="invoiceTable" class="table table-sm table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-right">Total:</th>
                                    <th id="totalAmount">₹0.00</th>
                                    <th id="totalPaid">₹0.00</th>
                                    <th id="totalDue">₹0.00</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    const table = $('#invoiceTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: false,
        pageLength: 40,
        lengthMenu: [
            [40, 100, -1], // Options for number of items per page
            [40, 100, "All"] // Labels for those options
        ],
        ajax: {
            url: '<?php echo base_url("admin/invoices/fetch"); ?>',
            type: 'POST',
            data: function(d) {
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.payment_status = $('#payment_status').val();
                d.type = $('#type').val();
                d.created_by = $('#created_by').val();
            },
            dataSrc: function(json) {
                // Update the totals in the footer
                $('#totalAmount').text(json.totals.total_amount);
                $('#totalPaid').text(json.totals.total_paid);
                $('#totalDue').text(json.totals.total_due);
                return json.data;
            }
        },
        columns: [{
                data: 0
            },
            {
                data: 1
            },
            {
                data: 2
            },
            {
                data: 3
            },
            {
                data: 4
            },
            {
                data: 5
            },
            {
                data: 6
            },
            {
                data: 7
            },
            {
                data: 8
            },
            {
                data: 9
            },
        ]
    });

    $('.filter-input').on('change', function() {
        table.ajax.reload();
    });

    $('#resetFilter').on('click', function() {
        $('#filterForm')[0].reset();
        table.ajax.reload();
    });
</script>