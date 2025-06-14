<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>Date</th>
            <th>Cash In</th>
            <th>Cash Out</th>
            <th>Bank In</th>
            <th>Bank Out</th>
            <th>Total In</th>
            <th>Total Out</th>
            <th>Net</th>
        </tr>
    </thead>
    <tbody>
        <?php $total_net = 0; ?>
        <?php foreach ($summary as $row): ?>
            <?php $net = $row->total_in - $row->total_out;
            $total_net += $net; ?>
            <tr>
                <td><?= date('d-m-Y', strtotime($row->trans_date)) ?></td>
                <td><?= number_format($row->cash_in, 2) ?></td>
                <td><?= number_format($row->cash_out, 2) ?></td>
                <td><?= number_format($row->bank_in, 2) ?></td>
                <td><?= number_format($row->bank_out, 2) ?></td>
                <td><?= number_format($row->total_in, 2) ?></td>
                <td><?= number_format($row->total_out, 2) ?></td>
                <td><?= number_format($net, 2) ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7" style="text-align:right;">Total Net</th>
            <th><?= number_format($total_net, 2) ?></th>
        </tr>
    </tfoot>
</table>