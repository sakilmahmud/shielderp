<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Expenses</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/expense/add'); ?>" class="btn btn-primary">Add Expense</a>
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
                    <?php if ($this->session->flashdata('payment')) : ?>
                        <div class="alert alert-info">
                            <?php echo $this->session->flashdata('payment'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="card_header">
                        <form id="filterForm">
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <input type="date" id="from_date" class="form-control filter-input" placeholder="From Date" value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" id="to_date" class="form-control filter-input" placeholder="To Date" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-2">
                                    <select id="category" class="form-control filter-input">
                                        <option value="">Expense Head</option>
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
                                    <select id="created_by" class="form-control filter-input">
                                        <option value="">Created By</option>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?php echo $user['id']; ?>"><?php echo $user['full_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="resetFilter" class="btn btn-secondary">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table id="expensesTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Expense Head</th>
                                <th>Expense Title</th>
                                <th>Amount</th>
                                <th>Mode</th>
                                <th>Date</th>
                                <th>Made By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total:</th>
                                <th id="total_amount"></th>
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
    $(document).ready(function() {
        const table = $('#expensesTable').DataTable({
            processing: true,
            serverSide: true,
            order: false,
            pageLength: 100,
            lengthMenu: [
                [100, -1],
                [100, "All"]
            ],
            ajax: {
                url: "<?php echo base_url('admin/expense/fetch'); ?>",
                type: "POST",
                data: function(d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.category = $('#category').val();
                    d.payment_method = $('#payment_method').val();
                    d.created_by = $('#created_by').val();
                },
                dataSrc: function(json) {
                    $('#total_amount').text(json.footer.total_amount);
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
    });
</script>