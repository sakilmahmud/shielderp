<?php if (!empty($due_customers)): ?>
    <div class="table-responsive table-body-scroll">
        <table class="table m-0">
            <thead class="table-header">
                <tr>
                    <th>Customer</th>
                    <th>Mobile</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($due_customers as $cust): ?>
                    <tr>
                        <td>
                            <a href="<?= base_url('admin/customers/show/' . $cust['c_id']) ?>"
                                class="ms-3 link-dark link-underline-opacity-100-hover">
                                <?= $cust['name'] ?></a>
                        </td>
                        <td><?= $cust['mobile'] ?></td>
                        <td>
                            <b>
                                ₹<?= number_format($cust['due_amount'], 2) ?>
                            </b>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-success text-center mb-0">
        No dues for customers.
    </div>
<?php endif; ?>