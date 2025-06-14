<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        padding: 8px;
        border: 1px solid #ddd;
    }

    th {
        background: #f2f2f2;
    }

    .text-right {
        text-align: right;
    }

    .text-success {
        color: green;
    }
</style>

<h3>Payment Received Report</h3>
<p><strong>From:</strong> <?= $from ?> <strong>To:</strong> <?= $to ?></p>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Customer</th>
            <th>Invoice No</th>
            <th class="text-right">Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php $total = 0;
        foreach ($transactions as $t): $total += $t->amount; ?>
            <tr>
                <td><?= date('d-m-Y', strtotime($t->trans_date)) ?></td>
                <td><?= $t->customer_name ?></td>
                <td><?= $t->invoice_no ?></td>
                <td class="text-right text-success"><?= number_format($t->amount, 2) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="3" class="text-right">Total</th>
            <th class="text-right text-success"><?= number_format($total, 2) ?></th>
        </tr>
    </tbody>
</table>