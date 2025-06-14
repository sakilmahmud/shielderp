<?php if (!empty($due_customers)): ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Customer Due <span class="ms-3 badge text-bg-info">
                    ₹<?= round($total_customer_due ?? 0) ?>
                </span></h3>
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
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($due_customers as $cust): ?>
                            <tr>
                                <td>
                                    <a
                                        href="pages/examples/invoice.html"
                                        class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"><?= $cust['name'] ?></a>
                                </td>
                                <td><?= $cust['mobile'] ?></td>
                                <td>
                                    <b class="">
                                        ₹<?= number_format($cust['due_amount'], 2) ?>
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
                href="<?= base_url('admin/invoices/create'); ?>"
                class="btn btn-sm btn-primary float-start">
                New Invoice
            </a>
            <a
                href="<?= base_url('admin/invoices'); ?>"
                class="btn btn-sm btn-secondary float-end">
                All Invoices
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-success text-center mb-0">
        No dues for customers.
    </div>
<?php endif; ?>