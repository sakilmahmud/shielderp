<style>
    .text-success {
        color: #198754 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }
</style>

<form method="get" class="row g-3 mb-3">
    <input type="hidden" name="tab" value="invoices">
    <div class="col-md-3">
        <label>From Date</label>
        <input type="date" name="from_date" class="form-control" value="<?= $this->input->get('from_date') ?? date('Y-01-01') ?>">
    </div>
    <div class="col-md-3">
        <label>To Date</label>
        <input type="date" name="to_date" class="form-control" value="<?= $this->input->get('to_date') ?? date('Y-m-d') ?>">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>Invoice No</th>
                    <th>Invoice Date</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($invoices)) : ?>
                    <?php foreach ($invoices as $inv) :
                        $actions = '<a href="' . base_url('admin/invoices/view/' . $inv['id']) . '" class="btn btn-info btn-sm me-1">View</a>';
                        $actions .= '<a href="' . base_url('admin/invoices/edit/' . $inv['id']) . '" class="btn btn-warning btn-sm me-1">Edit</a>';
                        $actions .= '<a href="' . base_url('admin/invoices/delete/' . $inv['id']) . '" class="btn btn-danger btn-sm me-1" onclick="return confirm(\'Are you sure you want to delete this invoice?\');">Delete</a>';
                        $actions .= '<a href="' . base_url('admin/invoices/print/' . $inv['id']) . '" target="_blank" class="btn btn-primary btn-sm">Print</a>';

                        $status_badge = match ($inv['payment_status']) {
                            '1' => '<span class="badge text-bg-success">Paid</span>',
                            '0' => '<span class="badge text-bg-warning">Pending</span>',
                            '2' => '<span class="badge text-bg-info">Partial</span>',
                            '3' => '<span class="badge text-bg-danger">Return</span>',
                            default => '<span class="badge badge-secondary">Unknown</span>',
                        };
                    ?>
                        <tr>
                            <td><?= $inv['invoice_no'] ?></td>
                            <td><?= date('d-m-Y', strtotime($inv['invoice_date'])) ?></td>
                            <td><?= date('d-m-Y', strtotime($inv['due_date'])) ?></td>
                            <td class="text-end">₹<?= number_format($inv['total_amount'], 2) ?></td>
                            <td><?= $status_badge; ?></td>
                            <td><?= $actions; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center">No invoices found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

            <?php if (!empty($invoices)): ?>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total</td>
                        <td class="text-end fw-bold text-primary">
                            ₹<?= number_format(array_sum(array_column($invoices, 'total_amount')), 2) ?>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>

    </div>
</div>