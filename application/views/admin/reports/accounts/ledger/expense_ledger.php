<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-rupee-sign"></i> Expense Ledger</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?= base_url('admin/reports/accounts/ledger') ?>" class="btn btn-primary">Back to Ledger</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form method="get" class="mb-3">
                <div class="row">
                    <div class="col-md-2">
                        <label>From</label>
                        <input type="date" name="from" value="<?= $from ?>" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>To</label>
                        <input type="date" name="to" value="<?= $to ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Head</label>
                        <select name="head_id" class="form-control">
                            <option value="">All Heads</option>
                            <?php foreach ($heads as $h): ?>
                                <option value="<?= $h->id ?>" <?= $head_id == $h->id ? 'selected' : '' ?>>
                                    <?= $h->head_title ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-2 mt-4">
                        <button type="submit" class="btn btn-primary mt-2">Filter</button>
                        <a href="<?= current_url() ?>" class="btn btn-danger mt-2">Reset</a>
                    </div>
                    <div class="col-md-3 text-right mt-4">
                        <a target="_blank" href="<?= base_url("admin/reports/accounts/export_expense_ledger/pdf?from=$from&to=$to&head_id=$head_id") ?>" class="btn btn-info btn-sm mt-2"><i class="fas fa-file-pdf"></i> Export PDF</a>
                        <a target="_blank" href="<?= base_url("admin/reports/accounts/export_expense_ledger/excel?from=$from&to=$to&head_id=$head_id") ?>" class="btn btn-success btn-sm mt-2"><i class="fas fa-file-excel"></i> Export Excel</a>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Head</th>
                                <th>Particulars</th>
                                <th class="text-right">Cash</th>
                                <th class="text-right">Bank</th>
                                <th class="text-right">Wallet</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grouped = [];
                            $total_cash = $total_bank = $total_wallet = 0;

                            foreach ($ledger as $row) {
                                $date = date('d-m-Y', strtotime($row->trans_date));
                                $head = $row->head_title ?? 'Unknown';
                                $groupKey = $date . '|' . $head;

                                if (!isset($grouped[$groupKey])) {
                                    $grouped[$groupKey] = [
                                        'date' => $date,
                                        'head' => $head,
                                        'particulars' => [],
                                        'cash' => 0,
                                        'bank' => 0,
                                        'wallet' => 0,
                                        'total' => 0,
                                    ];
                                }

                                $particular = ($row->expense_title ?? '') . ' - ' . $row->invoice_no;
                                $grouped[$groupKey]['particulars'][] = $particular;

                                if ($row->pm_type == 1) {
                                    $grouped[$groupKey]['cash'] += $row->amount;
                                } elseif ($row->pm_type == 2) {
                                    $grouped[$groupKey]['bank'] += $row->amount;
                                } elseif ($row->pm_type == 3) {
                                    $grouped[$groupKey]['wallet'] += $row->amount;
                                }

                                $grouped[$groupKey]['total'] += $row->amount;
                            }

                            foreach ($grouped as $group):
                                $total_cash += $group['cash'];
                                $total_bank += $group['bank'];
                                $total_wallet += $group['wallet'];
                            ?>
                                <tr>
                                    <td><?= $group['date'] ?></td>
                                    <td><?= $group['head'] ?></td>
                                    <td>
                                        <?php foreach ($group['particulars'] as $p): ?>
                                            • <?= $p ?><br>
                                        <?php endforeach; ?>
                                    </td>
                                    <td class="text-right"><?= number_format($group['cash'], 2) ?></td>
                                    <td class="text-right"><?= number_format($group['bank'], 2) ?></td>
                                    <td class="text-right"><?= number_format($group['wallet'], 2) ?></td>
                                    <td class="text-right"><?= number_format($group['total'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total</th>
                                <th class="text-right"><?= number_format($total_cash, 2) ?></th>
                                <th class="text-right"><?= number_format($total_bank, 2) ?></th>
                                <th class="text-right"><?= number_format($total_wallet, 2) ?></th>
                                <th class="text-right"><?= number_format($total_cash + $total_bank + $total_wallet, 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>