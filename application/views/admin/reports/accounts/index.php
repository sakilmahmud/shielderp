<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid my-2">
            <h2 class="mb-4"><i class="bi bi-graph-up"></i> Accounts Reports</h2>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php
                $pages = [
                    ['url' => 'cashbook', 'icon' => 'bi-journal-bookmark-fill', 'label' => 'Cash Book'],
                    ['url' => 'ledger', 'icon' => 'bi-journals', 'label' => 'Ledger'],
                    ['url' => 'payment-paid', 'icon' => 'bi-cash-stack', 'label' => 'Payment Paid'],
                    ['url' => 'payment-received', 'icon' => 'bi-currency-dollar', 'label' => 'Payment Received'],
                    ['url' => 'daily-summary', 'icon' => 'bi-calendar3', 'label' => 'Daily Summary'],
                    ['url' => 'profit-loss', 'icon' => 'bi-pie-chart-fill', 'label' => 'Profit & Loss Summary'],
                    ['url' => 'balance-sheet', 'icon' => 'bi-bar-chart-steps', 'label' => 'Balance Sheet'],
                    ['url' => 'tax', 'icon' => 'bi-receipt', 'label' => 'Input / Output Tax'],
                    ['url' => 'chart-of-accounts', 'icon' => 'bi-diagram-3-fill', 'label' => 'Chart of Accounts'],
                ];
                foreach ($pages as $page): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <a href="<?= base_url('admin/reports/accounts/' . $page['url']) ?>" class="mt-2 text-dark">
                                <h5 class="card-title p-3 d-flex align-items-center justify-content-center gap-2">
                                    <i class="<?= $page['icon'] ?> fs-2" style="color:#ff6600"></i>
                                    <?= $page['label'] ?>
                                </h5>
                            </a>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </section>
</div>