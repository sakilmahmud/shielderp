<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Stock Availability</h2>
            <div class="back">
                <a href="<?= base_url('admin/reports/inventory') ?>" class="btn btn-secondary" style="margin-right: 10px;">Back to Inventory Reports</a>
            </div>
            <div class="export_items">
                <a href="<?= base_url('admin/reports/inventory/export_stock_availability/xlsx') ?>" class="btn btn-info" style="margin-right: 10px;">Export to XLSX</a>
                <a href="<?= base_url('admin/reports/inventory/export_stock_availability/pdf') ?>" class="btn btn-dark">Export to PDF</a>
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
                                        <label for="category_id">Category</label>
                                        <select id="category_id" class="form-control">
                                            <option value="">All</option>
                                            <?php foreach ($categories as $category) : ?>
                                                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="brand_id">Brand</label>
                                        <select id="brand_id" class="form-control">
                                            <option value="">All</option>
                                            <?php foreach ($brands as $brand) : ?>
                                                <option value="<?= $brand['id'] ?>"><?= $brand['brand_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table id="stockTable" class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Valuation</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Unit</th>
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
        var table = $('#stockTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('admin/reports/inventory/fetch_stock_availability') ?>",
                "type": "POST",
                "data": function(d) {
                    d.category_id = $('#category_id').val();
                    d.brand_id = $('#brand_id').val();
                }
            },
            "columns": [{
                    "data": "product_name"
                },
                {
                    "data": "quantity"
                },
                {
                    "data": "price"
                },
                {
                    "data": "valuation"
                },
                {
                    "data": "category_name"
                },
                {
                    "data": "brand_name"
                },
                {
                    "data": "unit_name"
                }
            ],
            "pageLength": 50
        });

        $('#category_id, #brand_id').change(function() {
            table.ajax.reload();
        });
    });
</script>