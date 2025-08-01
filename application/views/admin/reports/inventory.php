<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Inventory Reports</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Stock Availability</h5>
                            <p class="card-text">View current stock levels of all products.</p>
                            <a href="<?= base_url('admin/reports/inventory/stock-availability') ?>" class="btn btn-primary">Go to Report</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Fast Moving Items</h5>
                            <p class="card-text">View the most sold products.</p>
                            <a href="<?= base_url('admin/reports/inventory/fast-moving-items') ?>" class="btn btn-primary">Go to Report</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Items Not Moving</h5>
                            <p class="card-text">View products that have not been sold.</p>
                            <a href="<?= base_url('admin/reports/inventory/items-not-moving') ?>" class="btn btn-primary">Go to Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>