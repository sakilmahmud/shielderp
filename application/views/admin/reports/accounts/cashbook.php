<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-book"></i> Cash Book</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/reports/accounts'); ?>" class="btn btn-primary">Accounts</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Filter Form -->
            <div class="row">
                <div class="col-md-9">
                    <form method="get" class="mb-3">
                        <div class="row">
                            <div class="col-md-5">
                                <label>From Date</label>
                                <input type="date" name="from" value="<?= $from ?>" class="form-control">
                            </div>
                            <div class="col-md-5">
                                <label>To Date</label>
                                <input type="date" name="to" value="<?= $to ?>" class="form-control">
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="<?= base_url('admin/reports/accounts/cashbook') ?>" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-3 text-right mt-4">
                    <div class="mb-3">
                        <a target="_blank" href="<?= base_url('admin/reports/accounts/export_cashbook/pdf?from=' . $from . '&to=' . $to) ?>" class="btn btn-info btn-sm"><i class="fas fa-file-pdf"></i> Export PDF</a>
                        <a target="_blank" href="<?= base_url('admin/reports/accounts/export_cashbook/excel?from=' . $from . '&to=' . $to) ?>" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Export Excel</a>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Particular</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Payment Mode</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5"><strong>Opening Balance</strong></td>
                                <td><strong>₹<?= number_format($opening_balance, 2) ?></strong></td>
                            </tr>
                            <?php foreach ($transactions as $t): ?>
                                <tr>
                                    <td><?= date('d-m-Y', strtotime($t->trans_date)) ?></td>
                                    <td><?= $t->descriptions ?></td>
                                    <td class="text-danger"><?= $t->trans_type == 2 ? '₹' . number_format($t->amount, 2) : '' ?></td>
                                    <td class="text-success"><?= $t->trans_type == 1 ? '₹' . number_format($t->amount, 2) : '' ?></td>
                                    <td><?= $t->payment_method ?></td>
                                    <td><strong>₹<?= number_format($t->running_balance, 2) ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="5"><strong>Closing Balance</strong></td>
                                <td><strong>₹<?= number_format($closing_balance, 2) ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>