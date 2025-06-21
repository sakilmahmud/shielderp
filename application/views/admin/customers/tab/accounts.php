<h5>Accounts Summary</h5>
<table class="table table-bordered">
    <tr>
        <th>Total Invoiced</th>
        <td>₹<?= number_format($accounts['total_invoiced'] ?? 0, 2) ?></td>
    </tr>
    <tr>
        <th>Total Paid</th>
        <td>₹<?= number_format($accounts['total_paid'] ?? 0, 2) ?></td>
    </tr>
    <tr>
        <th>Outstanding Balance</th>
        <td>₹<?= number_format($accounts['balance'] ?? 0, 2) ?></td>
    </tr>
</table>