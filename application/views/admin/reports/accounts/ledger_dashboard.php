<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-book"></i> Ledger</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/reports/accounts'); ?>" class="btn btn-primary">Accounts</a>
                </div>
            </div>

        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>₹<?= number_format($customer_total, 2) ?></h3>
                            <p>Customer's Invoice</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-friends"></i></div>
                        <a href="<?= base_url('admin/reports/accounts/ledger/customers') ?>" class="small-box-footer">View Ledger <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>₹<?= number_format($supplier_total, 2) ?></h3>
                            <p>Purchase from Suppliers</p>
                        </div>
                        <div class="icon"><i class="fas fa-truck"></i></div>
                        <a href="<?= base_url('admin/reports/accounts/ledger/suppliers') ?>" class="small-box-footer">View Ledger <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>₹<?= number_format($income_total, 2) ?></h3>
                            <p>Income</p>
                        </div>
                        <div class="icon"><i class="fas fa-wallet"></i></div>
                        <a href="<?= base_url('admin/reports/accounts/ledger/income') ?>" class="small-box-footer">View Ledger <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>₹<?= number_format($expense_total, 2) ?></h3>
                            <p>Expenses</p>
                        </div>
                        <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <a href="<?= base_url('admin/reports/accounts/ledger/expense') ?>" class="small-box-footer">View Ledger <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>