<!-- Chart -->
<link rel="stylesheet" href="<?php echo base_url('assets/admin/css/calc.css/chart.min.css') ?>">
<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Dashboard</h3>
        <form method="get" id="dashboardFilterForm" class="form-inline">
            <div class="d-flex align-items-center">
                <select name="filter" id="filter" class="form-control me-2">
                    <option value="today" <?= ($filter == 'today') ? 'selected' : '' ?>>Today</option>
                    <option value="last_7" <?= ($filter == 'last_7') ? 'selected' : '' ?>>Last 7 Days</option>
                    <option value="last_30" <?= ($filter == 'last_30') ? 'selected' : '' ?>>Last 30 Days</option>
                    <option value="custom" <?= ($filter == 'custom') ? 'selected' : '' ?>>Custom</option>
                </select>

                <div id="customDateRange">
                    <div class="d-flex align-items-center mt-2">
                        <input type="date" name="from_date" class="form-control me-2" value="<?= $from_date ?>">
                        <input type="date" name="to_date" class="form-control me-2" value="<?= $to_date ?>">
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        // On change of the filter dropdown
                        $('#filter').on('change', function() {
                            if ($(this).val() === 'custom') {
                                $('#customDateRange').show();
                            } else {
                                $('#customDateRange').hide();
                                $('#dashboardFilterForm').submit();
                            }
                        });

                        // Hide date fields if not "custom" on load
                        if ($('#filter').val() !== 'custom') {
                            $('#customDateRange').hide();
                        }
                    });
                </script>

            </div>
        </form>
    </div>

    <div class="row">
        <!-- Payin -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-primary shadow-sm">
                    <i class="bi bi-arrow-down-circle-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Payin</span>
                    <span class="info-box-number text-success">
                        ₹<?= number_format($payin, 2) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Payout -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-danger shadow-sm">
                    <i class="bi bi-arrow-up-circle-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Payout</span>
                    <span class="info-box-number text-danger">₹<?= number_format($payout, 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Sales -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="bi bi-cart-check-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Sales</span>
                    <span class="info-box-number text-success">
                        ₹<?= number_format($sales['total_amount'] ?? 0, 2) ?> (<?= $sales['invoice_count'] ?? 0 ?>)
                    </span>
                </div>
            </div>
        </div>

        <!-- Purchase -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="bi bi-bag-check-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Purchase</span>
                    <span class="info-box-number text-danger">
                        ₹<?= number_format($purchase['total_amount'] ?? 0, 2) ?>(<?= $purchase['purchase_count'] ?? 0 ?>)
                    </span>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <?php /*<div class="col-md-4">
            <div class="card card-min-height">
                <div class="card-header">
                    <h3 class="card-title"><i class="nav-icon fas fa-chart-bar"></i> Quick Reports</h3>
                </div>

                <!-- Quick Reports Tabs -->
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sale" type="button" role="tab">
                            <i class="bi bi-currency-rupee"></i> Sales
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#purchase" type="button" role="tab">
                            <i class="bi bi-cart-check"></i> Purchases
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Sales Tab -->
                    <div class="tab-pane fade show active" id="sale" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card text-white bg-success mx-2 mb-2">
                                    <div class="card-body d-flex justify-content-center align-items-center gap-3 p-2">
                                        <h5 class="card-title"><i class="bi bi-calendar-day"></i> Today’s Sale</h5>
                                        <p class="card-text fs-4">₹<?= number_format($sales_report['daily'], 2) ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card text-white bg-primary mx-2 mb-2">
                                    <div class="card-body d-flex justify-content-center align-items-center gap-3 p-2">
                                        <h5 class="card-title"><i class="bi bi-calendar-week"></i> Last 7 Days</h5>
                                        <p class="card-text fs-4">₹<?= number_format($sales_report['weekly'], 2) ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card text-dark bg-info mx-2 mb-2">
                                    <div class="card-body d-flex justify-content-center align-items-center gap-3 p-2">
                                        <h5 class="card-title"><i class="bi bi-calendar3"></i> Last 30 Days</h5>
                                        <p class="card-text fs-4">₹<?= number_format($sales_report['monthly'], 2) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Tab -->
                    <div class="tab-pane fade" id="purchase" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card text-dark bg-warning mx-2 mb-2">
                                    <div class="card-body d-flex justify-content-center align-items-center gap-3 p-2">
                                        <h5 class="card-title"><i class="bi bi-bag-plus"></i> Today’s Purchase</h5>
                                        <p class="card-text fs-4">₹<?= number_format($purchase_report['daily'], 2) ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card text-white bg-secondary mx-2 mb-2">
                                    <div class="card-body d-flex justify-content-center align-items-center gap-3 p-2">
                                        <h5 class="card-title"><i class="bi bi-calendar-week"></i> Last 7 Days</h5>
                                        <p class="card-text fs-4">₹<?= number_format($purchase_report['weekly'], 2) ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card text-white bg-dark mx-2 mb-2">
                                    <div class="card-body d-flex justify-content-center align-items-center gap-3 p-2">
                                        <h5 class="card-title"><i class="bi bi-calendar3"></i> Last 30 Days</h5>
                                        <p class="card-text fs-4">₹<?= number_format($purchase_report['monthly'], 2) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div> */ ?>
        <div class="col-md-4">
            <div class="card card-min-height card-success card-outline">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Customer Due</h3>
                    <h3 class="ms-3 badge text-bg-dark">
                        ₹<?= round($total_customer_due ?? 0) ?>
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div id="ajax-customer-due-content"></div>
                </div>
                <div class="card-footer clearfix">
                    <a
                        href="<?= base_url('admin/invoices/create'); ?>"
                        class="btn btn-sm btn-primary float-start">
                        New Invoice
                    </a>
                    <a
                        href="<?= base_url('admin/invoices'); ?>"
                        class="btn btn-sm btn-secondary float-end">
                        All Invoices
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-min-height card-danger card-outline">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Suppliers Due</h3>
                    <h3 class="ms-3 badge text-bg-dark">
                        ₹<?= round($total_supplier_due ?? 0) ?>
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div id="ajax-supplier-due-content"></div>
                </div>
                <div class="card-footer clearfix">
                    <a
                        href="<?= base_url('admin/purchase_entries/add'); ?>"
                        class="btn btn-sm btn-primary float-start">
                        New Purchase
                    </a>
                    <a
                        href="<?= base_url('admin/purchase_entries'); ?>"
                        class="btn btn-sm btn-secondary float-end">
                        All Purchases
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-min-height card-info card-outline">
                <!--begin::Header-->
                <div class="card-header">
                    <div class="card-title">Reminder</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-secondary" role="alert">
                        A simple secondary alert with
                        <a href="#" class="alert-link">an example link</a>. Give it a click.
                    </div>
                    <div class="alert alert-success" role="alert">
                        A simple success alert with
                        <a href="#" class="alert-link">an example link</a>. Give it a click .
                    </div>
                    <div class="alert alert-danger" role="alert">
                        A simple danger alert with <a href="#" class="alert-link">an example link</a>.
                        Give it a click if you like.
                    </div>
                </div>
                <!--end::Body-->
            </div>
        </div>
        <?php /*<div class="col-md-4">
            <div class="card card-min-height">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="nav-icon fas fa-warehouse"></i> Quick Stocks
                    </h3>
                    <span class="badge badge-pill badge-info">
                        <?= $low_stock_count ?? 0 ?> / <?= $total_products_count ?? 0 ?>
                    </span>
                </div>
                <div class="p-2" id="ajax-low-stock-content">
                    <p>Loading...</p>
                </div>
            </div>
        </div>
        */ ?>
    </div>
    <div class="row mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <canvas id="salesChart" height="140"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <canvas id="categorySalesChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/admin/js/chart.min.js') ?>"></script>

<?php
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
?>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($months) ?>,
            datasets: [{
                    label: 'Sales',
                    data: <?= json_encode($monthly_sales) ?>,
                    backgroundColor: '#28a745'
                },
                {
                    label: 'Purchases',
                    data: <?= json_encode($monthly_purchases) ?>,
                    backgroundColor: '#dc3545'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php
$category_labels = [];
$category_totals = [];

foreach ($top_categories as $cat) {
    $category_labels[] = $cat['name'];
    $category_totals[] = $cat['total_sales'];
}
?>
<script>
    const categorySalesChart = new Chart(document.getElementById('categorySalesChart'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($category_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($category_totals); ?>,
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6610f2', '#fd7e14', '#20c997', '#6f42c1', '#e83e8c'],
                borderWidth: 1
            }]
        }
    });
</script>

<script>
    $(document).ready(function() {
        // Load customer due by default
        loadCustomerDue();
        loadSupplierDue();

        function loadCustomerDue() {
            $('#ajax-customer-due-content').html(`<div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                    <div class="spinner-border text-primary me-2"></div>
                    <div class="spinner-border text-success me-2"></div>
                    <div class="spinner-border text-info me-2"></div>
                    <div class="spinner-border text-warning me-2"></div>
                    <div class="spinner-border text-danger me-2"></div>
                    </div>
                `);

            $.get('<?= base_url('admin/dashboard/due_customers') ?>', function(data) {
                $('#ajax-customer-due-content').html(data);
            });
        }


        function loadSupplierDue() {
            $('#ajax-supplier-due-content').html(`<div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                    <div class="spinner-border text-primary me-2"></div>
                    <div class="spinner-border text-success me-2"></div>
                    <div class="spinner-border text-info me-2"></div>
                    <div class="spinner-border text-warning me-2"></div>
                    <div class="spinner-border text-danger me-2"></div>
                    </div>
                `);
            $.get('<?= base_url('admin/dashboard/due_suppliers') ?>', function(data) {
                $('#ajax-supplier-due-content').html(data);
            });
        }
    });
</script>
<script>
    $(document).ready(function() {
        $.get('<?= base_url("admin/ajax/low-stock") ?>', function(data) {
            $('#ajax-low-stock-content').html(data);
        });
    });
</script>