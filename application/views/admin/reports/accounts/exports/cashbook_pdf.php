<!DOCTYPE html>
<html>

<head>
    <title>Cashbook Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .credit {
            color: green;
        }

        .debit {
            color: red;
        }

        .balance {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h2>Cashbook Report<br><?= $from ?> to <?= $to ?></h2>

    <table>
        <thead>
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
                <td></td>
                <td></td>
                <td></td>
                <td class="balance"><?= number_format($opening_balance, 2) ?></td>
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
                    <td class="debit"><?= $debit > 0 ? number_format($debit, 2) : '-' ?></td>
                    <td class="credit"><?= $credit > 0 ? number_format($credit, 2) : '-' ?></td>
                    <td><?= $txn->payment_method ?></td>
                    <td><?= number_format($balance, 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td><?= $to ?></td>
                <td><strong>Closing Balance</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="balance"><?= number_format($closing_balance, 2) ?></td>
            </tr>
        </tbody>
    </table>

</body>

</html>