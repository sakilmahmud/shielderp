<?php if (!empty($due_customers)): ?>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Mobile</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($due_customers as $cust): ?>
                <tr>
                    <td><?= $cust['name'] ?></td>
                    <td><?= $cust['mobile'] ?></td>
                    <td>₹<?= number_format($cust['due_amount'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No dues for customers.</p>
<?php endif; ?>