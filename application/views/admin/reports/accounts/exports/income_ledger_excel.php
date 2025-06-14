<table border="1">
    <thead>
        <tr>
            <th colspan="7"><b>Income Ledger</b></th>
        </tr>
        <tr>
            <th colspan="7">From: <?= date('d-m-Y', strtotime($from)) ?> To: <?= date('d-m-Y', strtotime($to)) ?></th>
        </tr>
        <tr>
            <th>Date</th>
            <th>Head</th>
            <th>Particulars</th>
            <th>Cash</th>
            <th>Bank</th>
            <th>Wallet</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total_cash = $total_bank = $total_wallet = 0;
        $grouped = [];

        foreach ($ledger as $row) {
            $key = $row->trans_date . '|' . $row->head_title;
            $grouped[$key]['rows'][] = $row;
        }

        foreach ($grouped as $key => $data_group):
            list($date, $head) = explode('|', $key);
            $group_cash = $group_bank = $group_wallet = $group_total = 0;
            $particulars = '';

            foreach ($data_group['rows'] as $r) {
                $cash = $r->pm_type == 1 ? $r->amount : 0;
                $bank = $r->pm_type == 2 ? $r->amount : 0;
                $wallet = $r->pm_type == 3 ? $r->amount : 0;
                $group_cash += $cash;
                $group_bank += $bank;
                $group_wallet += $wallet;
                $group_total += $r->amount;

                $desc = $r->income_title ?? '';
                $inv  = $r->invoice_no ? " ({$r->invoice_no})" : '';
                $particulars .= $desc . $inv . " | ";
            }

            $total_cash += $group_cash;
            $total_bank += $group_bank;
            $total_wallet += $group_wallet;
        ?>
            <tr>
                <td><?= date('d-m-Y', strtotime($date)) ?></td>
                <td><?= $head ?></td>
                <td><?= rtrim($particulars, " | ") ?></td>
                <td><?= number_format($group_cash, 2) ?></td>
                <td><?= number_format($group_bank, 2) ?></td>
                <td><?= number_format($group_wallet, 2) ?></td>
                <td><?= number_format($group_total, 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">Grand Total</th>
            <th><?= number_format($total_cash, 2) ?></th>
            <th><?= number_format($total_bank, 2) ?></th>
            <th><?= number_format($total_wallet, 2) ?></th>
            <th><?= number_format($total_cash + $total_bank + $total_wallet, 2) ?></th>
        </tr>
    </tfoot>
</table>