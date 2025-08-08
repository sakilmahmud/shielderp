<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Items Not Moving</h2>
            <div class="back">
                <a href="<?= base_url('admin/reports/inventory') ?>" class="btn btn-secondary" style="margin-right: 10px;">Back to Inventory Reports</a>
            </div>
            <div class="export_items">
                <a href="<?= base_url('admin/reports/inventory/export_items_not_moving/xlsx') ?>" class="btn btn-info" style="margin-right: 10px;">Export to XLSX</a>
                <a href="<?= base_url('admin/reports/inventory/export_items_not_moving/pdf') ?>" class="btn btn-dark">Export to PDF</a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if (!empty($items_not_moving)) : ?>
                                <table id="itemsNotMovingTable" class="table table-sm table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items_not_moving as $item) : ?>
                                            <tr>
                                                <td><?= $item['product_name'] ?></td>
                                                <td><?= $item['quantity'] ?></td>
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
        $('#itemsNotMovingTable').DataTable({
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