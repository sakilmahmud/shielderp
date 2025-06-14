<table border="1">
    <thead>
        <tr>
            <th colspan="4" style="text-align:center;">Payment Paid Report (<?= $from ?> to <?= $to ?>)</th>
        </tr>
        <tr>
            <th>Date</th>
            <th>Supplier</th>
            <th>Invoice No</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php $total = 0;
        foreach ($transactions as $t): $total += $t->amount; ?>
            <tr>
                <td><?= date('d-m-Y', strtotime($t->trans_date)) ?></td>
                <td><?= $t->supplier_name ?></td>
                <td><?= $t->invoice_no ?></td>
                <td><?= number_format($t->amount, 2) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="3" style="text-align:right;">Total</th>
            <th><?= number_format($total, 2) ?></th>
        </tr>
    </tbody>
</table>