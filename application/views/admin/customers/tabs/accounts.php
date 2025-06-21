<style>
    .table {
        border-color: rgb(137, 148, 150);
    }

    .tbl-bg-success {
        background-color: #d1e7dd !important;
    }

    .tbl-bg-danger {
        background-color: #f8d7da !important;
    }
</style>
<form method="get" class="row g-3 mb-3">
    <input type="hidden" name="tab" value="accounts">
    <div class="col-md-3">
        <label>From Date</label>
        <input type="date" name="from_date" class="form-control" value="<?= $account_summary['from_date'] ?>">
    </div>
    <div class="col-md-3">
        <label>To Date</label>
        <input type="date" name="to_date" class="form-control" value="<?= $account_summary['to_date'] ?>">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="card">
    <div class="card-header fw-bold">
        Opening Balance:
        <span class="<?= $account_summary['opening_balance'] < 0 ? 'text-danger' : 'text-success' ?>">
            ₹<?= number_format($account_summary['opening_balance'], 2) ?>
        </span>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Reference</th>
                    <th class="text-end">Amount</th>
                    <th class="text-end">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($account_summary['entries'])): ?>
                    <?php foreach ($account_summary['entries'] as $entry): ?>
                        <?php
                        if ($entry['type'] == 'Payment' && $entry['amount'] <= 0) continue;

                        $rowClass = '';
                        if ($entry['type'] == 'Invoice') {
                            $rowClass = 'tbl-bg-danger';
                        } elseif ($entry['type'] == 'Payment') {
                            $rowClass = 'tbl-bg-success';
                        }
                        ?>
                        <tr>
                            <td class="<?= $rowClass ?>"><?= $entry['date'] ?></td>
                            <td class="<?= $rowClass ?>"><?= $entry['type'] ?></td>
                            <td class="<?= $rowClass ?>"><?= $entry['ref'] ?></td>
                            <td class="text-end <?= $rowClass ?>">₹<?= number_format($entry['amount'], 2) ?></td>
                            <td class="text-end fw-bold <?= $entry['balance'] < 0 ? 'text-danger' : 'text-success' ?> <?= $rowClass ?>">
                                ₹<?= number_format($entry['balance'], 2) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Closing Balance</th>
                    <th class="text-end fw-bold <?= $account_summary['closing_balance'] < 0 ? 'text-danger' : 'text-success' ?>">
                        ₹<?= number_format($account_summary['closing_balance'], 2) ?>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>