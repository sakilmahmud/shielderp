<?php if (!empty($due_suppliers)): ?>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Due Date</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($due_suppliers as $sup): ?>
                <tr>
                    <td><?= $sup['name'] ?></td>
                    <td><?= $sup['due_date'] ?></td>
                    <td>₹<?= number_format($sup['due_amount'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No dues for suppliers.</p>
<?php endif; ?>