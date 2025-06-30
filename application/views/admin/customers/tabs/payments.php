<form method="get" class="row g-3 mb-3">
    <input type="hidden" name="tab" value="payments">
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
    <div class="card-header">
        <h5 class="m-0">Payment History</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-striped table-sm mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice No</th>
                    <th>Description</th>
                    <th>Payment Mode</th>
                    <th class="text-end">Amount</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($payments)): ?>
                    <?php foreach ($payments as $p):
                        if ($p['amount'] <= 0) continue;
                    ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($p['trans_date'])) ?></td>
                            <td><?= $p['invoice_no'] ?? '-' ?></td>
                            <td><?= $p['descriptions'] ?></td>
                            <td><?= $p['payment_method'] ?? 'N/A' ?></td>
                            <td class="text-end fw-bold text-success">₹<?= number_format($p['amount'], 2) ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/payments/print_receipt/' . $p['id']) ?>" class="btn btn-sm btn-secondary" target="_blank">
                                    🖨️ Print
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No payment history available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

            <?php if (!empty($payments)): ?>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total</td>
                        <td class="text-end fw-bold text-primary">
                            ₹<?= number_format(array_sum(array_column($payments, 'amount')), 2) ?>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>