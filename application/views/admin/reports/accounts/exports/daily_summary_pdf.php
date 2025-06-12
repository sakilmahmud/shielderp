<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #999;
        padding: 5px;
        text-align: right;
    }

    th {
        background-color: #f2f2f2;
    }

    .text-success {
        color: green;
    }

    .text-danger {
        color: red;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .bold {
        font-weight: bold;
    }
</style>

<h3 class="text-center">Daily Summary Report</h3>
<p class="text-center"><?= date('d-m-Y', strtotime($from)) ?> to <?= date('d-m-Y', strtotime($to)) ?></p>

<table>
    <thead>
        <tr>
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
                <td class="text-left"><?= date('d-m-Y', strtotime($row->trans_date)) ?></td>
                <td class="text-success"><?= number_format($row->cash_in, 2) ?></td>
                <td class="text-danger"><?= number_format($row->cash_out, 2) ?></td>
                <td class="text-success"><?= number_format($row->bank_in, 2) ?></td>
                <td class="text-danger"><?= number_format($row->bank_out, 2) ?></td>
                <td class="text-success"><?= number_format($row->total_in, 2) ?></td>
                <td class="text-danger"><?= number_format($row->total_out, 2) ?></td>
                <td class="<?= ($net < 0) ? 'text-danger' : 'text-success' ?> bold"><?= number_format($net, 2) ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7" class="text-right">Total Net</th>
            <th class="<?= ($total_net < 0) ? 'text-danger' : 'text-success' ?> bold"><?= number_format($total_net, 2) ?></th>
        </tr>
    </tfoot>
</table>