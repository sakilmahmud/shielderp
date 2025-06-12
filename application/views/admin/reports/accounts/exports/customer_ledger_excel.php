<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Date</th>
            <th>Customer</th>
            <th>Invoice</th>
            <th>Description</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php $total = 0;
        foreach ($ledger as $row): $total += $row->amount; ?>
            <tr>
                <td><?= date('d-m-Y', strtotime($row->trans_date)) ?></td>
                <td><?= $row->customer_name ?></td>
                <td><?= $row->invoice_no ?></td>
                <td><?= $row->descriptions ?></td>
                <td><?= number_format($row->amount, 2) ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" style="text-align:right;">Total</th>
            <th><?= number_format($total, 2) ?></th>
        </tr>
    </tfoot>
</table>