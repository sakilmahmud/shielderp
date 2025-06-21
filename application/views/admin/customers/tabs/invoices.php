<h5>Invoices</h5>
<?php if (!empty($invoices)): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $inv): ?>
                <tr>
                    <td><?= $inv['invoice_no'] ?></td>
                    <td><?= date('d M Y', strtotime($inv['date'])) ?></td>
                    <td>₹<?= number_format($inv['total'], 2) ?></td>
                    <td><span class="badge bg-success"><?= $inv['status'] ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-warning">No invoices available.</div>
<?php endif; ?>