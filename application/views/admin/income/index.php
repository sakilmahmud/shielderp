<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Incomes</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/income/add'); ?>" class="btn btn-primary">Add Income</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form id="filterForm">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <input type="date" id="from_date" class="form-control filter-input" value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" id="to_date" class="form-control filter-input" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-2">
                                <select id="category" class="form-control filter-input">
                                    <option value="">Income Head</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo $category['head_title']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select id="payment_method" class="form-control filter-input">
                                    <option value="">Payment Method</option>
                                    <?php foreach ($payment_methods as $method): ?>
                                        <option value="<?php echo $method['id']; ?>"><?php echo $method['title']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="resetFilter" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                    <table id="incomeTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Income Head</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Mode</th>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total:</th>
                                <th id="footerTotalAmount"></th>
                                <th colspan="4"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    const table = $('#incomeTable').DataTable({
        processing: true,
        serverSide: true,
        order: false,
        pageLength: 100,
        lengthMenu: [
            [100, -1],
            [100, "All"]
        ],
        ajax: {
            url: '<?php echo base_url("admin/income/fetch"); ?>',
            type: 'POST',
            data: function(d) {
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.category = $('#category').val();
                d.payment_method = $('#payment_method').val();
            },
            dataSrc: function(json) {
                $('#footerTotalAmount').text(json.footer.total_amount);
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