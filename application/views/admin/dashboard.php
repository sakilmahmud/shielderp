<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-primary shadow-sm">
                    <i class="bi bi-gear-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">CPU Traffic</span>
                    <span class="info-box-number">
                        10
                        <small>%</small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-danger shadow-sm">
                    <i class="bi bi-hand-thumbs-up-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Likes</span>
                    <span class="info-box-number">41,410</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- fix for small devices only -->
        <!-- <div class="clearfix hidden-md-up"></div> -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="bi bi-cart-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Sales</span>
                    <span class="info-box-number">760</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="bi bi-people-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">New Members</span>
                    <span class="info-box-number">2,000</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-md-4">
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
                                <div class="card text-white bg-info mx-2 mb-2">
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
        </div>
        <div class="col-md-4">
            <div id="ajax-customer-due-content">
                <p>Loading...</p>
            </div>
        </div>
        <div class="col-md-4">
            <div id="ajax-supplier-due-content" class="due-scroll-container">
                <p>Loading...</p>
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
            $('#ajax-customer-due-content').html('<p>Loading...</p>');
            $.get('<?= base_url('admin/dashboard/due_customers') ?>', function(data) {
                $('#ajax-customer-due-content').html(data);
            });
        }

        function loadSupplierDue() {
            $('#ajax-supplier-due-content').html('<p>Loading...</p>');
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