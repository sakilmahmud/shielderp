<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><i class="bi bi-currency-dollar"></i> Payment Received</h2>
            <a href="<?php echo base_url('admin/reports/accounts'); ?>" class="btn btn-primary">Accounts</a>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Filter Form -->
            <div class="row">
                <div class="col-md-12">
                    <form method="get" class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label>From Date</label>
                                <input type="date" name="from" class="form-control" value="<?= $from ?>">
                            </div>
                            <div class="col-md-2">
                                <label>To Date</label>
                                <input type="date" name="to" class="form-control" value="<?= $to ?>">
                            </div>
                            <div class="col-md-2">
                                <label>Customer</label>
                                <select name="customer_id" class="form-control">
                                    <option value="">All</option>
                                    <?php foreach ($customers as $cust): ?>
                                        <option value="<?= $cust->id ?>" <?= $cust->id == $selected_customer ? 'selected' : '' ?>>
                                            <?= $cust->customer_name ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Invoice No</label>
                                <input type="text" name="invoice_no" class="form-control" value="<?= $selected_invoice ?>">
                            </div>
                            <div class="col-md-4 align-content-end">
                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                <a href="<?= base_url('admin/reports/accounts/payment-received') ?>" class="btn btn-danger btn-sm">Reset</a> -
                                <a target="_blank" href="<?= base_url('admin/reports/accounts/export_payment_received/pdf?from=' . $from . '&to=' . $to . '&customer_id=' . $selected_customer . '&invoice_no=' . $selected_invoice) ?>" class="btn btn-info btn-sm"><i class="bi bi-file-pdf"></i> Export PDF</a>
                                <a target="_blank" href="<?= base_url('admin/reports/accounts/export_payment_received/excel?from=' . $from . '&to=' . $to . '&customer_id=' . $selected_customer . '&invoice_no=' . $selected_invoice) ?>" class="btn btn-success btn-sm"><i class="bi bi-file-excel"></i> Export Excel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Report Table -->
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Invoice No</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            <?php foreach ($transactions as $t): ?>
                                <?php $total += $t->amount; ?>
                                <tr>
                                    <td><?= date('d-m-Y', strtotime($t->trans_date)) ?></td>
                                    <td><?= $t->customer_name ?></td>
                                    <td><?= $t->invoice_no ?></td>
                                    <td class="text-right text-success"><?= number_format($t->amount, 2) ?></td>
                                </tr>
                            <?php endforeach ?>
                            <tr>
                                <th colspan="3" class="text-right">Total</th>
                                <th class="text-right text-success"><?= number_format($total, 2) ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>