<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Account Balances</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <!-- Date Range Filter Form -->
            <div class="card">
                <div class="card-header">
                    <form method="get" action="<?php echo base_url('admin/accounts/account_balance'); ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="from_date">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo $from_date; ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="to_date">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control" value="<?php echo $to_date; ?>">
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp;</label><br>
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="<?php echo base_url('admin/accounts/account_balance'); ?>" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Balances Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Balances by Payment Methods</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Payment Method</th>
                                <?php if ($this->session->userdata('role') == 1) : ?>
                                    <th>Total Credit</th>
                                    <th>Total Debit</th>
                                <?php endif; ?>
                                <th>Net Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($balances as $balance):
                                if (($balance['credit'] - $balance['debit']) == 0) continue;
                            ?>
                                <tr>
                                    <td><?php echo $balance['payment_method_title']; ?></td>
                                    <?php if ($this->session->userdata('role') == 1) : ?>
                                        <td><?php echo number_format($balance['credit'], 2); ?></td>
                                        <td><?php echo number_format($balance['debit'], 2); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo number_format($balance['credit'] - $balance['debit'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <h4>Total Balance: <?php echo number_format($total_balance, 2); ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>