<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Fast Moving Items</h1>
                </div>
                <div class="col-sm-6">
                    <div class="btn-group float-sm-right">
                        <a href="<?= base_url('admin/reports/inventory') ?>" class="btn btn-secondary" style="margin-right: 10px;">Back to Inventory Reports</a>
                        <a href="<?= base_url('admin/reports/inventory/export_fast_moving_items/xlsx') ?>" class="btn btn-success" style="margin-right: 10px;">Export to XLSX</a>
                        <a href="<?= base_url('admin/reports/inventory/export_fast_moving_items/pdf') ?>" class="btn btn-danger">Export to PDF</a>
                    </div>
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
                            <?php if (!empty($fast_moving_items)) : ?>
                                <table id="fastMovingItemsTable" class="table table-sm table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Total Sold</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($fast_moving_items as $item) : ?>
                                            <tr>
                                                <td><?= $item['product_name'] ?></td>
                                                <td><?= $item['total_sold'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No data found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#fastMovingItemsTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "pageLength": 25
        });
    });
</script>