<?php if (!empty($due_suppliers)): ?>
    <div class="card card-min-height">
        <div class="card-header">
            <h3 class="card-title">Suppliers Due <span class="ms-3 text-right badge text-bg-info">₹<?= round($total_supplier_due ?? 0) ?></span></h3>
            <div class="card-tools">
                <button
                    type="button"
                    class="btn btn-tool"
                    data-lte-toggle="card-collapse">
                    <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                    <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                </button>
                <button
                    type="button"
                    class="btn btn-tool"
                    data-lte-toggle="card-remove">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <div class="table-responsive">
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
                                        class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"><?= $sup['name'] ?></a>
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
        </div>
        <div class="card-footer clearfix">
            <a
                href="<?= base_url('admin/purchase_entries/add'); ?>"
                class="btn btn-sm btn-primary float-start">
                New Purchase
            </a>
            <a
                href="<?= base_url('admin/purchase_entries'); ?>"
                class="btn btn-sm btn-secondary float-end">
                All Purchases
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-success text-center mb-0">
        No dues for suppliers.
    </div>
<?php endif; ?>