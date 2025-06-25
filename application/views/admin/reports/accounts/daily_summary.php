<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><i class="fas fa-calendar-day"></i> Daily Summary</h2>
            <a href="<?php echo base_url('admin/reports/accounts'); ?>" class="btn btn-primary">Accounts</a>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form method="get" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <label>From</label>
                        <input type="date" name="from" value="<?= $from ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>To</label>
                        <input type="date" name="to" value="<?= $to ?>" class="form-control">
                    </div>
                    <div class="col-md-4 mt-4">
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        <a href="<?= base_url('admin/reports/accounts/daily-summary') ?>" class="btn btn-danger btn-sm">Reset</a>
                        -
                        <a target="_blank" href="<?= base_url("admin/reports/accounts/export_daily_summary/pdf?from=$from&to=$to") ?>" class="btn btn-info btn-sm"><i class="bi bi-file-pdf"></i> Export PDF</a>
                        <a target="_blank" href="<?= base_url("admin/reports/accounts/export_daily_summary/excel?from=$from&to=$to") ?>" class="btn btn-success btn-sm "><i class="bi bi-file-excel"></i> Export Excel</a>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Date</th>
                                <th>Cash In</th>
                                <th>Cash Out</th>
                                <th>Bank In</th>
                                <th>Bank Out</th>
                                <th>Total In</th>
                                <th>Total Out</th>
                                <th>Net</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_net = 0;
                            foreach ($summary as $row):
                                $net = $row->total_in - $row->total_out;
                                $total_net += $net;
                                $net_class = $net >= 0 ? 'text-success' : 'text-danger';
                            ?>
                                <tr>
                                    <td><?= date('d-m-Y', strtotime($row->trans_date)) ?></td>
                                    <td class="text-success text-right"><?= number_format($row->cash_in, 2) ?></td>
                                    <td class="text-danger text-right"><?= number_format($row->cash_out, 2) ?></td>
                                    <td class="text-success text-right"><?= number_format($row->bank_in, 2) ?></td>
                                    <td class="text-danger text-right"><?= number_format($row->bank_out, 2) ?></td>
                                    <td class="text-success text-right"><?= number_format($row->total_in, 2) ?></td>
                                    <td class="text-danger text-right"><?= number_format($row->total_out, 2) ?></td>
                                    <td class="text-right font-weight-bold <?= $net_class ?>"><?= number_format($net, 2) ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-right">Total Net</th>
                                <th class="text-right <?= $total_net >= 0 ? 'text-success' : 'text-danger' ?>"><?= number_format($total_net, 2) ?></th>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </section>
</div>