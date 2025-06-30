<?php if (!empty($due_suppliers)): ?>

    <div class="table-responsive table-body-scroll">
        <table class="table m-0">
            <thead>
                <tr>
                    <th>Suppliers</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($due_suppliers as $sup): ?>
                    <tr>
                        <td>
                            <a
                                href="javascript:void(0)"
                                class="ms-3 link-dark link-underline-opacity-100-hover"><?= $sup['name'] ?></a>
                        </td>
                        <td><small class="text-muted"><i class="far fa-calendar-alt"></i> Due: <?= date('d M Y', strtotime($sup['due_date'])) ?></small></td>
                        <td>
                            <b class="">
                                ₹<?= number_format($sup['due_amount'], 2) ?>
                            </b>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- /.table-responsive -->

<?php else: ?>
    <div class="alert alert-success text-center mb-0">
        No dues for suppliers.
    </div>
<?php endif; ?>