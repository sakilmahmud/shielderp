<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-chart-line"></i> Profit & Loss</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/reports/accounts'); ?>" class="btn btn-primary">Accounts</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form method="get" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <label>From</label>
                        <input type="date" name="from" value="<?= $from ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>To</label>
                        <input type="date" name="to" value="<?= $to ?>" class="form-control">
                    </div>
                    <div class="col-md-2 mt-4">
                        <button type="submit" class="btn btn-primary mt-2">Filter</button>
                        <a href="<?= base_url('admin/reports/accounts/profit-loss') ?>" class="btn btn-danger mt-2">Reset</a>
                    </div>
                    <div class="col-md-4 text-right mt-4">
                        <a target="_blank" href="<?= base_url("admin/reports/accounts/export_profit_loss/pdf?from=$from&to=$to") ?>" class="btn btn-danger btn-sm mt-2"><i class="fas fa-file-pdf"></i> Export PDF</a>
                        <a target="_blank" href="<?= base_url("admin/reports/accounts/export_profit_loss/excel?from=$from&to=$to") ?>" class="btn btn-success btn-sm mt-2"><i class="fas fa-file-excel"></i> Export Excel</a>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Total Income</th>
                            <td class="text-success text-right"><?= number_format($total_income, 2) ?></td>
                        </tr>
                        <tr>
                            <th>Total Expense</th>
                            <td class="text-danger text-right"><?= number_format($total_expense, 2) ?></td>
                        </tr>
                        <tr>
                            <th>Profit / Loss</th>
                            <td class="text-right font-weight-bold <?= ($total_income - $total_expense) >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= number_format($total_income - $total_expense, 2) ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>