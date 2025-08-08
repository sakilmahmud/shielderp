<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid py-3">
            <div class="row mb-2">
                <div class="col text-center">
                    <h1 class="display-5 fw-bold">📊 All Reports</h1>
                    <p class="text-muted">Access various financial and operational reports</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row g-4 justify-content-center">

                <!-- Accounts Report -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 bg-primary text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-wallet2" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="card-bold-title">Accounts</h4>
                            <p class="card-text">Manage and view account balances and transactions.</p>
                            <a href="<?= base_url('admin/reports/accounts') ?>" class="btn btn-outline-light mt-2">View Report</a>
                        </div>
                    </div>
                </div>

                <!-- Inventory Report -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 bg-success text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-box-seam-fill" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="card-bold-title">Inventory</h4>
                            <p class="card-text">View stock levels, fast-moving, and slow-moving items.</p>
                            <a href="<?= base_url('admin/reports/inventory') ?>" class="btn btn-outline-light mt-2">View Report</a>
                        </div>
                    </div>
                </div>

                <!-- Sales Report -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 bg-info text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-graph-up-arrow" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="card-bold-title">Sales</h4>
                            <p class="card-text">Analyze sales performance and trends.</p>
                            <a href="<?= base_url('admin/reports/sales') ?>" class="btn btn-outline-light mt-2">View Report</a>
                        </div>
                    </div>
                </div>

                <!-- Customers Report -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 bg-warning text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-people-fill" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="card-bold-title">Customers</h4>
                            <p class="card-text">View customer data and purchase history.</p>
                            <a href="<?= base_url('admin/reports/customers') ?>" class="btn btn-outline-light mt-2">View Report</a>
                        </div>
                    </div>
                </div>

                <!-- Purchases Report -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 bg-danger text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-cart-fill" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="card-bold-title">Purchases</h4>
                            <p class="card-text">Track purchase orders and supplier information.</p>
                            <a href="<?= base_url('admin/reports/purchases') ?>" class="btn btn-outline-light mt-2">View Report</a>
                        </div>
                    </div>
                </div>

                <!-- Suppliers Report -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 bg-secondary text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-truck-front-fill" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="card-bold-title">Suppliers</h4>
                            <p class="card-text">Manage supplier details and purchase records.</p>
                            <a href="<?= base_url('admin/reports/suppliers') ?>" class="btn btn-outline-light mt-2">View Report</a>
                        </div>
                    </div>
                </div>

                <!-- Expenses Report -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 bg-dark text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-cash-coin" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="card-bold-title">Expenses</h4>
                            <p class="card-text">Monitor and categorize business expenses.</p>
                            <a href="<?= base_url('admin/reports/expenses') ?>" class="btn btn-outline-light mt-2">View Report</a>
                        </div>
                    </div>
                </div>

                <!-- Staff Report -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 bg-light text-dark">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-person-badge-fill" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="card-bold-title">Staff</h4>
                            <p class="card-text">View staff details and performance.</p>
                            <a href="<?= base_url('admin/reports/staff') ?>" class="btn btn-outline-dark mt-2">View Report</a>
                        </div>
                    </div>
                </div>

                <!-- GSTR Report -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 bg-secondary text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-file-text" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="card-bold-title">GSTR</h4>
                            <p class="card-text">Generate GST reports for tax compliance.</p>
                            <a href="<?= base_url('admin/reports/gstr') ?>" class="btn btn-outline-light mt-2">View Report</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>