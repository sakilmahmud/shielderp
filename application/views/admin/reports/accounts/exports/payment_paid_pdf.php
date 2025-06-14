<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Payment Paid Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #000;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-danger {
            color: red;
        }
    </style>
</head>

<body>

    <h2>Payment Paid Report<br><small><?= date('d-m-Y', strtotime($from)) ?> to <?= date('d-m-Y', strtotime($to)) ?></small></h2>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Supplier</th>
                <th>Invoice No</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0;
            foreach ($transactions as $t): $total += $t->amount; ?>
                <tr>
                    <td><?= date('d-m-Y', strtotime($t->trans_date)) ?></td>
                    <td><?= $t->supplier_name ?></td>
                    <td><?= $t->invoice_no ?></td>
                    <td class="text-right text-danger"><?= number_format($t->amount, 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th class="text-right text-danger"><?= number_format($total, 2) ?></th>
            </tr>
        </tbody>
    </table>

</body>

</html>