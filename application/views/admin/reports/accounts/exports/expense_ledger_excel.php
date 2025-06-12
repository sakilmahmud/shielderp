<table border="1">
    <thead>
        <tr>
            <th colspan="7"><strong>Expense Ledger</strong></th>
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
                <td><?= implode(', ', $group['particulars']) ?></td>
                <td><?= number_format($group['cash'], 2) ?></td>
                <td><?= number_format($group['bank'], 2) ?></td>
                <td><?= number_format($group['wallet'], 2) ?></td>
                <td><?= number_format($group['total'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" align="right">Total</th>
            <th><?= number_format($total_cash, 2) ?></th>
            <th><?= number_format($total_bank, 2) ?></th>
            <th><?= number_format($total_wallet, 2) ?></th>
            <th><?= number_format($total_cash + $total_bank + $total_wallet, 2) ?></th>
        </tr>
    </tfoot>
</table>