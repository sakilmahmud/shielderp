<!DOCTYPE html>
<html>

<head>
    <title>Profit & Loss Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px 10px;
            border: 1px solid #333;
            text-align: right;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .text-success {
            color: green;
        }

        .text-danger {
            color: red;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Profit & Loss Report<br><small><?= date('d M Y', strtotime($from)) ?> to <?= date('d M Y', strtotime($to)) ?></small></h2>

    <table>
        <tr>
            <th>Total Income</th>
            <td class="text-success"><?= number_format($total_income, 2) ?></td>
        </tr>
        <tr>
            <th>Total Expense</th>
            <td class="text-danger"><?= number_format($total_expense, 2) ?></td>
        </tr>
        <tr>
            <th>Profit / Loss</th>
            <td class="<?= $net_profit >= 0 ? 'text-success' : 'text-danger' ?>">
                <?= number_format($net_profit, 2) ?>
            </td>
        </tr>
    </table>
</body>

</html>