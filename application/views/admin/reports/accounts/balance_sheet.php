<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><i class="fas fa-balance-scale"></i> Demo Balance Sheet</h2>
            <a href="<?php echo base_url('admin/reports/accounts'); ?>" class="btn btn-primary">Accounts</a>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form method="get" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <label>As On Date</label>
                        <input type="date" name="as_on" value="<?= $as_on ?>" class="form-control">
                    </div>
                    <div class="col-md-4 mt-4">
                        <button class="btn btn-primary mt-2">Generate</button>
                    </div>
                    <div class="col-md-4 text-right mt-4">
                        <a target="_blank" href="<?= base_url("admin/reports/accounts/export_balance_sheet/pdf?as_on=$as_on") ?>" class="btn btn-danger btn-sm mt-2"><i class="fas fa-file-pdf"></i> Export PDF</a>
                        <a target="_blank" href="<?= base_url("admin/reports/accounts/export_balance_sheet/excel?as_on=$as_on") ?>" class="btn btn-success btn-sm mt-2"><i class="fas fa-file-excel"></i> Export Excel</a>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th colspan="2">Assets</th>
                            </tr>
                        </thead>
                        <?php
                        $total_assets = 0;
                        foreach ($assets as $item):
                            $total_assets += $item->amount;
                        ?>
                            <tr>
                                <td><?= $item->title ?></td>
                                <td class="text-right text-success"><?= number_format($item->amount, 2) ?></td>
                            </tr>
                        <?php endforeach ?>
                        <tr class="font-weight-bold">
                            <td>Total Assets</td>
                            <td class="text-right text-success"><?= number_format($total_assets, 2) ?></td>
                        </tr>

                        <thead class="thead-dark mt-3">
                            <tr>
                                <th colspan="2">Liabilities</th>
                            </tr>
                        </thead>
                        <?php
                        $total_liabilities = 0;
                        foreach ($liabilities as $item):
                            $total_liabilities += $item->amount;
                        ?>
                            <tr>
                                <td><?= $item->title ?></td>
                                <td class="text-right text-danger"><?= number_format($item->amount, 2) ?></td>
                            </tr>
                        <?php endforeach ?>

                        <thead class="thead-dark">
                            <tr>
                                <th colspan="2">Equity</th>
                            </tr>
                        </thead>
                        <?php
                        $total_equity = 0;
                        foreach ($equity as $item):
                            $total_equity += $item->amount;
                        ?>
                            <tr>
                                <td><?= $item->title ?></td>
                                <td class="text-right text-primary"><?= number_format($item->amount, 2) ?></td>
                            </tr>
                        <?php endforeach ?>

                        <tr class="font-weight-bold">
                            <td>Total Liabilities + Equity</td>
                            <td class="text-right text-primary"><?= number_format($total_liabilities + $total_equity, 2) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>