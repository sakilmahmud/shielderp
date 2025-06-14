<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-user-tie"></i> Supplier Ledger</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?= base_url('admin/reports/accounts/ledger') ?>" class="btn btn-primary">Back to Ledger</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form method="get" class="mb-3">
                <div class="row">
                    <div class="col-md-2">
                        <label>From</label>
                        <input type="date" name="from" value="<?= $from ?>" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>To</label>
                        <input type="date" name="to" value="<?= $to ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Supplier</label>
                        <select name="party_id" class="form-control">
                            <option value="">All Suppliers</option>
                            <?php foreach ($parties as $p): ?>
                                <option value="<?= $p->id ?>" <?= $party_id == $p->id ? 'selected' : '' ?>>
                                    <?= $p->supplier_name ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-2 mt-4">
                        <button type="submit" class="btn btn-primary mt-2">Filter</button>
                        <a href="<?= base_url('admin/reports/accounts/ledger/suppliers') ?>" class="btn btn-danger mt-2">Reset</a>
                    </div>
                    <div class="col-md-3 text-right mt-4">
                        <a target="_blank" href="<?= base_url("admin/reports/accounts/export_supplier_ledger/pdf?from=$from&to=$to&party_id=$party_id") ?>" class="btn btn-info btn-sm mt-2"><i class="fas fa-file-pdf"></i> Export PDF</a>
                        <a target="_blank" href="<?= base_url("admin/reports/accounts/export_supplier_ledger/excel?from=$from&to=$to&party_id=$party_id") ?>" class="btn btn-success btn-sm mt-2"><i class="fas fa-file-excel"></i> Export Excel</a>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Payment Date</th>
                                <th>Supplier</th>
                                <th>PO No</th>
                                <th>PO Date</th>
                                <th>Description</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0;
                            foreach ($ledger as $row): $total += $row->amount; ?>
                                <tr>
                                    <td><?= date('d-m-Y', strtotime($row->trans_date)) ?></td>
                                    <td><?= $row->supplier_name ?></td>
                                    <td><?= $row->invoice_no ?></td>
                                    <td><?= $row->purchase_date ?></td>
                                    <td><?= $row->descriptions ?></td>
                                    <td class="text-right">₹<?= number_format($row->amount, 2) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-right">Total</th>
                                <th class="text-right">₹<?= number_format($total, 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>