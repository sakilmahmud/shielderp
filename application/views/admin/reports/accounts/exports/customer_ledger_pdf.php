<!DOCTYPE html>
<html>

<head>
    <title>Customer Ledger PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h3>Customer Ledger (<?= $from ?> to <?= $to ?>)</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Invoice</th>
                <th>Description</th>
                <th class="text-right">Amount</th>
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
                    <td class="text-right"><?= number_format($row->amount, 2) ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total</th>
                <th class="text-right"><?= number_format($total, 2) ?></th>
            </tr>
        </tfoot>
    </table>
</body>

</html>