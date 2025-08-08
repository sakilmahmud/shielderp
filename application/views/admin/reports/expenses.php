<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Expenses Report</h2>
            <div class="back">
                <a href="<?= base_url('admin/reports') ?>" class="btn btn-secondary" style="margin-right: 10px;">Back to Reports</a>
            </div>
            <div class="export_items">
                <a href="#" class="btn btn-info" style="margin-right: 10px;">Export to XLSX</a>
                <a href="#" class="btn btn-dark">Export to PDF</a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="from_date">From Date</label>
                                        <input type="date" id="from_date" class="form-control" value="<?= date('Y-m-d') ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="to_date">To Date</label>
                                        <input type="date" id="to_date" class="form-control" value="<?= date('Y-m-d') ?>">
                                    </div>
                                </div>
                            </div>
                            <table id="expensesTable" class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Expense Title</th>
                                        <th>Expense Head</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        var table = $('#expensesTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('admin/reports/fetch_expenses') ?>",
                "type": "POST",
                "data": function(d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            "columns": [
                { "data": "expense_title" },
                { "data": "expense_head" },
                { "data": "expense_date" },
                { "data": "amount" }
            ],
            "pageLength": 50
        });

        $('#from_date, #to_date').change(function() {
            table.ajax.reload();
        });
    });
</script>