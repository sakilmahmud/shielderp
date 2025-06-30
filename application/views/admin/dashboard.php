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
        <div class="col-6 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-primary shadow-sm">
                    <i class="bi bi-arrow-down-circle-fill fs-3 fs-md-2 fs-lg-1"></i>
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
        <div class="col-6 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-danger shadow-sm">
                    <i class="bi bi-arrow-up-circle-fill fs-3 fs-md-2 fs-lg-1"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Payout</span>
                    <span class="info-box-number text-danger">
                        ₹<?= number_format($payout, 2) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Sales -->
        <div class="col-6 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="bi bi-cart-check-fill fs-3 fs-md-2 fs-lg-1"></i>
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
        <div class="col-6 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="bi bi-bag-check-fill fs-3 fs-md-2 fs-lg-1"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Purchase</span>
                    <span class="info-box-number text-danger">
                        ₹<?= number_format($purchase['total_amount'] ?? 0, 2) ?> (<?= $purchase['purchase_count'] ?? 0 ?>)
                    </span>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-md-4 mb-3">
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
        <div class="col-md-4 mb-3">
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
        <div class="col-md-4 mb-3">
            <div class="card card-min-height card-info card-outline">
                <!--begin::Header-->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">
                        Reminder
                        <?php if (!empty($reminders)): ?>
                            <span class="badge bg-dark ms-2"><?= count($reminders) ?></span>
                        <?php endif; ?>
                    </div>
                    <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#addReminderModal">Add</button>
                </div>

                <?php
                $alert_classes = ['alert-secondary', 'alert-success', 'alert-info', 'alert-warning'];
                ?>

                <div class="card-body p-3" style="max-height: 260px; overflow-y: auto;">
                    <?php if (!empty($reminders)): ?>
                        <?php foreach ($reminders as $index => $reminder): ?>
                            <?php
                            $content = strip_tags($reminder['content']);
                            $short_content = (strlen($content) > 40) ? substr($content, 0, 40) . '...' : $content;
                            $alert_class = $alert_classes[$index % count($alert_classes)];
                            $created_at = date('d M, Y h:i A', strtotime($reminder['created_at']));
                            ?>
                            <div class="alert <?= $alert_class ?> mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?= $short_content ?>
                                        <a href="#" class="ms-2 text-dark" data-bs-toggle="modal" data-bs-target="#viewReminderModal" data-id="<?= $reminder['id'] ?>" title="View Full">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </div>
                                    <small class="text-muted"><?= $created_at ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-light text-center py-4">
                            <i class="bi bi-emoji-smile fs-2 text-muted"></i><br>
                            <strong>No reminders yet!</strong><br>
                            You're all caught up for now 😊
                        </div>
                    <?php endif; ?>
                </div>
                <!--end::Body-->
            </div>

        </div>
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
<script>
    $(document).ready(function() {
        $('#viewReminderModal').on('show.bs.modal', function(e) {
            const reminderId = $(e.relatedTarget).data('id');
            $.get('<?= base_url('admin/reminder/detail/') ?>' + reminderId, function(data) {
                const reminder = JSON.parse(data);
                $('#reminderDetailContent').text(reminder.content);
                $('#reminderCreatedAt').text('Created at: ' + reminder.created_at);
                $('#markAsDoneBtn').attr('href', '<?= base_url('admin/reminder/done/') ?>' + reminder.id);
            });
        });
    });
</script>