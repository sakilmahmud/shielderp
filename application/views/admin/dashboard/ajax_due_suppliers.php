<?php if (!empty($due_suppliers)): ?>
    <div class="list-group">
        <?php foreach ($due_suppliers as $sup): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <div class="flex-grow-1">
                    <h6 class="mb-1 text-info"><?= $sup['name'] ?></h6>
                    <small class="text-muted"><i class="far fa-calendar-alt"></i> Due: <?= date('d M Y', strtotime($sup['due_date'])) ?></small>
                </div>
                <div class="text-right">
                    <span class="badge badge-pill badge-warning py-2 px-3" style="font-size: 1rem;">
                        ₹<?= number_format($sup['due_amount'], 2) ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-success text-center mb-0">
        No dues for suppliers.
    </div>
<?php endif; ?>