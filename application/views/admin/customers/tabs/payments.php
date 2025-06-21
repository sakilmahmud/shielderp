<h5>Payment History</h5>
<?php if (!empty($payments)): ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Mode</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $p): ?>
                <tr>
                    <td><?= date('d M Y', strtotime($p['date'])) ?></td>
                    <td>₹<?= number_format($p['amount'], 2) ?></td>
                    <td><?= esc($p['mode']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">No payments found.</div>
<?php endif; ?>