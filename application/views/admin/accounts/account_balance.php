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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Balances by Payment Methods</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Payment Method ID</th>
                                <th>Total Credit</th>
                                <th>Total Debit</th>
                                <th>Net Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($balances as $balance): ?>
                                <tr>
                                    <td><?php echo $balance['payment_method_title']; ?></td>
                                    <td><?php echo number_format($balance['credit'], 2); ?></td>
                                    <td><?php echo number_format($balance['debit'], 2); ?></td>
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