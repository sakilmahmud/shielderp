<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Purchase Entries</h2>
            <a href="<?php echo base_url('admin/purchase_entries/add'); ?>" class="btn btn-primary">Add Purchase Entry</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <?php if ($this->session->flashdata('message')) : ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('message'); ?>
                        </div>
                    <?php endif; ?>

                    <form id="filterForm" class="mb-2 bg-light p-1">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <input type="date" id="from_date" class="form-control filter-input" value="<?php echo date('Y-m-d', strtotime('-30 days')); ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" id="to_date" class="form-control filter-input" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-2">
                                <select id="payment_status" class="form-control filter-input">
                                    <option value="">Payment Status</option>
                                    <option value="0">Due</option>
                                    <option value="1">Paid</option>
                                    <option value="2">Partial</option>
                                    <option value="3">Return</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <select id="type" class="form-control filter-input">
                                    <option value="">Type</option>
                                    <option value="0">NON-GST</option>
                                    <option value="1">GST</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="supplier_id" class="form-control filter-input">
                                    <option value="">Suppliers</option>
                                    <?php foreach ($suppliers as $user): ?>
                                        <option value="<?php echo $user['id']; ?>"><?php echo $user['supplier_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="resetFilter" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-bordered" id="purchaseTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Supplier</th>
                                    <th>Purchase Date</th>
                                    <th>Invoice No</th>
                                    <th>Payable</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total:</th>
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
    const table = $('#purchaseTable').DataTable({
        processing: true,
        serverSide: true,
        order: false,
        pageLength: 40,
        lengthMenu: [
            [40, 100, -1], // Options for number of items per page
            [40, 100, "All"] // Labels for those options
        ],
        ajax: {
            url: '<?php echo base_url("admin/purchases/fetch"); ?>',
            type: 'POST',
            data: function(d) {
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.payment_status = $('#payment_status').val();
                d.type = $('#type').val();
                d.supplier_id = $('#supplier_id').val();
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
            }
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