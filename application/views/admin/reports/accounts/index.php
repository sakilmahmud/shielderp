<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="mb-4"><i class="fas fa-chart-line"></i> Accounts Reports</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php
                $pages = [
                    ['url' => 'cashbook', 'icon' => 'fas fa-book', 'label' => 'Cash Book'],
                    ['url' => 'ledger', 'icon' => 'fas fa-book', 'label' => 'Ledger'],
                    ['url' => 'payment-paid', 'icon' => 'fas fa-money-bill-wave', 'label' => 'Payment Paid'],
                    ['url' => 'payment-received', 'icon' => 'fas fa-hand-holding-usd', 'label' => 'Payment Received'],
                    ['url' => 'daily-summary', 'icon' => 'fas fa-calendar-day', 'label' => 'Daily Summary'],
                    //['url' => 'tax', 'icon' => 'fas fa-receipt', 'label' => 'Input / Output Tax'],
                    ['url' => 'profit-loss', 'icon' => 'fas fa-chart-pie', 'label' => 'Profit & Loss Summary'],
                    //['url' => 'chart-of-accounts', 'icon' => 'fas fa-project-diagram', 'label' => 'Chart of Accounts'],
                    ['url' => 'balance-sheet', 'icon' => 'fas fa-balance-scale', 'label' => 'Balance Sheet'],
                ];
                foreach ($pages as $page): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <a href="<?= base_url('admin/reports/accounts/' . $page['url']) ?>" class="mt-2">
                                <div class="card-body text-center">
                                    <i class="<?= $page['icon'] ?> fa-2x mb-2 text-primary"></i>
                                    <h5 class="card-title"><?= $page['label'] ?></h5>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </section>
</div>