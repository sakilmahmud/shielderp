<table border="1">
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; text-align: center;">Cashbook Report (<?= $from ?> to <?= $to ?>)</th>
        </tr>
        <tr>
            <th>Date</th>
            <th>Particulars</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Payment Mode</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= $from ?></td>
            <td><strong>Opening Balance</strong></td>
            <td colspan="4"><?= number_format($opening_balance, 2) ?></td>
        </tr>
        <?php
        $balance = $opening_balance;
        foreach ($transactions as $txn):
            $credit = $txn->trans_type == 1 ? $txn->amount : 0;
            $debit  = $txn->trans_type == 2 ? $txn->amount : 0;
            $balance += $credit - $debit;
        ?>
            <tr>
                <td><?= $txn->trans_date ?></td>
                <td><?= $txn->descriptions ?></td>
                <td><?= $debit > 0 ? number_format($debit, 2) : '-' ?></td>
                <td><?= $credit > 0 ? number_format($credit, 2) : '-' ?></td>
                <td><?= $txn->payment_method ?></td>
                <td><?= number_format($balance, 2) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td><?= $to ?></td>
            <td><strong>Closing Balance</strong></td>
            <td colspan="4"><?= number_format($closing_balance, 2) ?></td>
        </tr>
    </tbody>
</table>