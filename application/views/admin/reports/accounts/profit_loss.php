<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><i class="bi bi-bar-chart-fill"></i> Profit & Loss</h2>
            <a href="<?php echo base_url('admin/reports/accounts'); ?>" class="btn btn-primary">Accounts</a>
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
                    <div class="col-md-4 align-content-end">
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        <a href="<?= base_url('admin/reports/accounts/profit-loss') ?>" class="btn btn-danger btn-sm">Reset</a>
                        -
                        <a target="_blank" href="<?= base_url("admin/reports/accounts/export_profit_loss/pdf?from=$from&to=$to") ?>" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> Export PDF</a>
                        <a target="_blank" href="<?= base_url("admin/reports/accounts/export_profit_loss/excel?from=$from&to=$to") ?>" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Export Excel</a>
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