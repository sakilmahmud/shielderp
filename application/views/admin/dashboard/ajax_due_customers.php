<?php if (!empty($due_customers)): ?>
    <div class="list-group">
        <?php foreach ($due_customers as $cust): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <div class="flex-grow-1">
                    <h6 class="mb-1 text-primary"><?= $cust['name'] ?></h6>
                    <small class="text-muted"><i class="fas fa-phone-alt"></i> <?= $cust['mobile'] ?></small>
                </div>
                <div class="text-right">
                    <span class="badge badge-pill badge-danger py-2 px-3" style="font-size: 1rem;">
                        ₹<?= number_format($cust['due_amount'], 2) ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-success text-center mb-0">
        No dues for customers.
    </div>
<?php endif; ?>