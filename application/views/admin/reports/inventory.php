<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid py-3">
            <div class="row mb-2">
                <div class="col text-center">
                    <h1 class="display-5 fw-bold">📦 Inventory Reports</h1>
                    <p class="text-muted">Analyze your inventory performance and stock status</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row g-4 justify-content-center">

                <!-- Stock Availability -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 bg-primary text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-box-seam-fill" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="card-bold-title">Stock Availability</h4>
                            <p class="card-text">View current stock levels of all products.</p>
                            <a href="<?= base_url('admin/reports/inventory/stock-availability') ?>" class="btn btn-outline-light mt-2">View Report</a>
                        </div>
                    </div>
                </div>

                <!-- Fast Moving Items -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 bg-success text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-lightning-charge-fill" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="card-bold-title">Fast Moving Items</h4>
                            <p class="card-text">Check out the most sold products.</p>
                            <a href="<?= base_url('admin/reports/inventory/fast-moving-items') ?>" class="btn btn-outline-light mt-2">View Report</a>
                        </div>
                    </div>
                </div>

                <!-- Items Not Moving -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 bg-danger text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-box-fill" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="card-bold-title">Items Not Moving</h4>
                            <p class="card-text">Products that haven't been sold recently.</p>
                            <a href="<?= base_url('admin/reports/inventory/items-not-moving') ?>" class="btn btn-outline-light mt-2">View Report</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>