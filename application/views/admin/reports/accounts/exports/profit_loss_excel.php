<table border="1">
    <thead>
        <tr>
            <th colspan="2" style="text-align:center;">
                Profit & Loss Report (<?= date('d M Y', strtotime($from)) ?> to <?= date('d M Y', strtotime($to)) ?>)
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>Total Income</th>
            <td style="color:green; text-align:right;"><?= number_format($total_income, 2) ?></td>
        </tr>
        <tr>
            <th>Total Expense</th>
            <td style="color:red; text-align:right;"><?= number_format($total_expense, 2) ?></td>
        </tr>
        <tr>
            <th>Profit / Loss</th>
            <td style="text-align:right; font-weight:bold; color:<?= $net_profit >= 0 ? 'green' : 'red' ?>;">
                <?= number_format($net_profit, 2) ?>
            </td>
        </tr>
    </tbody>
</table>