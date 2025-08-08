<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Suppliers Report</h2>
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
                            <table id="suppliersTable" class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Supplier Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Total Orders</th>
                                        <th>Total Spent</th>
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
        var table = $('#suppliersTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('admin/reports/fetch_suppliers') ?>",
                "type": "POST"
            },
            "columns": [
                { "data": "supplier_name" },
                { "data": "email" },
                { "data": "phone" },
                { "data": "total_orders" },
                { "data": "total_spent" }
            ],
            "pageLength": 50
        });
    });
</script>