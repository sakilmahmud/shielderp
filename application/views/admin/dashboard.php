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
</style>


<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-min-height">
                        <div class="">
                            <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="sale-tab" data-toggle="tab" href="#sale" role="tab" aria-controls="sale" aria-selected="true">Sales</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="purchase-tab" data-toggle="tab" href="#purchase" role="tab" aria-controls="purchase" aria-selected="false">Purchases</a>
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
                        <div class="">
                            <ul class="nav nav-tabs" id="dueTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="customers-tab" data-toggle="tab" href="#customers" role="tab">Customers Due</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="suppliers-tab" data-toggle="tab" href="#suppliers" role="tab">Suppliers Due</a>
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
                        <div class="card-header">
                            <h3 class="card-title">Quick Stocks</h3>
                        </div>
                        <div class="p-2" id="ajax-low-stock-content">
                            <p>Loading...</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
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