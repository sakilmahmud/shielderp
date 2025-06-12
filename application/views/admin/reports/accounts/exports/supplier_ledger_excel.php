<table border="1" cellspacing="0" cellpadding="4">
    <thead>
        <tr>
            <th colspan="5" style="text-align:center; font-weight:bold;">Supplier Ledger</th>
        </tr>
        <tr>
            <th colspan="5">From: <?= date('d-m-Y', strtotime($from)) ?> &nbsp;&nbsp; To: <?= date('d-m-Y', strtotime($to)) ?></th>
        </tr>
        <tr>
            <th>Payment Date</th>
            <th>Supplier</th>
            <th>PO No</th>
            <th>PO Date</th>
            <th>Description</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php $total = 0;
        foreach ($ledger as $row): $total += $row->amount; ?>
            <tr>
                <td><?= date('d-m-Y', strtotime($row->trans_date)) ?></td>
                <td><?= $row->supplier_name ?></td>
                <td><?= $row->invoice_no ?></td>
                <td><?= $row->purchase_date ?></td>
                <td><?= $row->descriptions ?></td>
                <td><?= number_format($row->amount, 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" style="text-align:right;">Total</th>
            <th><?= number_format($total, 2) ?></th>
        </tr>
    </tfoot>
</table>