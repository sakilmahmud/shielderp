<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .card-min-height {
        min-height: 370px;
    }

    #ajax-low-stock-content .list-group-item {
        padding: 5px 10px;
        font-size: 13px;
    }

    .due-scroll-container {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: hidden;
        /* hide horizontal scrollbar */
        padding-right: 10px;
        scrollbar-width: thin;
        scrollbar-color: #ccc transparent;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    /* Webkit (Chrome, Safari) Scrollbar Customization */
    .due-scroll-container::-webkit-scrollbar {
        width: 6px;
    }

    .due-scroll-container::-webkit-scrollbar-thumb {
        background-color: #adb5bd;
        border-radius: 10px;
    }

    .due-scroll-container::-webkit-scrollbar-track {
        background: transparent;
    }

    .due-scroll-container {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .list-group-item {
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>

<div class="content-wrapper mt-3">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-min-height">
                        <div class="card-header">
                            <h3 class="card-title"><i class="nav-icon fas fa-chart-bar"></i> Quick Reports</h3>
                        </div>
                        <div class="">
                            <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="sale-tab" data-toggle="tab" href="#sale" role="tab" aria-controls="sale" aria-selected="true"><i class="nav-icon fas fa-shopping-cart icon-yellow"></i> Sales</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="purchase-tab" data-toggle="tab" href="#purchase" role="tab" aria-controls="purchase" aria-selected="false"><i class="nav-icon fas fa-truck icon-blue"></i> Purchases</a>
                                </li>
                            </ul>
                        </div>
                        <div class="pl-1">

                            <div class="tab-content" id="reportTabsContent">
                                <!-- Sale Tab -->
                                <div class="tab-pane fade show active" id="sale" role="tabpanel" aria-labelledby="sale-tab">
                                    <ul class="list-group">
                                        <li class="list-group-item">Today Sale: ₹<?= number_format($sales_report['daily'], 2) ?></li>
                                        <li class="list-group-item">Last 7 Days Sale: ₹<?= number_format($sales_report['weekly'], 2) ?></li>
                                        <li class="list-group-item">Last 30 Days Sale: ₹<?= number_format($sales_report['monthly'], 2) ?></li>
                                    </ul>
                                </div>

                                <!-- Purchase Tab -->
                                <div class="tab-pane fade" id="purchase" role="tabpanel" aria-labelledby="purchase-tab">
                                    <ul class="list-group">
                                        <li class="list-group-item">Today Purchase: ₹<?= number_format($purchase_report['daily'], 2) ?></li>
                                        <li class="list-group-item">Last 7 Days Purchase: ₹<?= number_format($purchase_report['weekly'], 2) ?></li>
                                        <li class="list-group-item">Last 30 Days Purchase: ₹<?= number_format($purchase_report['monthly'], 2) ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-min-height">
                        <div class="card-header">
                            <h3 class="card-title"><i class="nav-icon fas fa-receipt"></i> Due List</h3>
                        </div>
                        <div class="">
                            <ul class="nav nav-tabs" id="dueTab" role="tablist">
                                <li class="nav-item">
                                    <a style="font-size: 13px;" class="nav-link active d-flex justify-content-between align-items-center" id="customers-tab" data-toggle="tab" href="#customers" role="tab">
                                        <span><i class="fas fa-users text-primary mr-1"></i> Customers</span>
                                        <span class="badge badge-pill badge-info ml-2">₹<?= round($total_customer_due ?? 0) ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a style="font-size: 13px;" class="nav-link d-flex justify-content-between align-items-center" id="suppliers-tab" data-toggle="tab" href="#suppliers" role="tab">
                                        <span><i class="fas fa-truck text-warning mr-1"></i> Suppliers</span>
                                        <span class="badge badge-pill badge-info ml-2">₹<?= round($total_supplier_due ?? 0) ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="pl-1">
                            <div class="tab-content" id="dueTabContent">
                                <div class="tab-pane fade show active" id="customers" role="tabpanel">
                                    <div id="ajax-customer-due-content" class="due-scroll-container">
                                        <p>Loading...</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="suppliers" role="tabpanel">
                                    <div id="ajax-supplier-due-content" class="due-scroll-container">
                                        <p>Loading...</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
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

            </div>
            <div class="row">
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
                            <canvas id="categorySalesChart" height="100"></canvas>
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
    </section>
</div>
<script>
    $(document).ready(function() {
        // Load customer due by default
        loadCustomerDue();

        $('#customers-tab').on('click', function() {
            loadCustomerDue();
        });

        $('#suppliers-tab').on('click', function() {
            loadSupplierDue();
        });

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