<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between">
            <h2>Customer Details</h2>
            <a href="<?= base_url('admin/customers') ?>" class="btn btn-secondary">Back to List</a>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <img src="<?= $customer['photo'] ?? base_url('assets/img/default-user.png') ?>" class="img-fluid rounded-circle mb-3" width="100">
                            <h4><?= esc($customer['customer_name']) ?></h4>
                            <p>ID: <strong><?= $customer['id'] ?></strong></p>
                            <p>Balance: <strong>₹<?= number_format($customer['balance'] ?? 0, 2) ?></strong></p>
                            <p>Status: <strong class="text-success">Active</strong></p>

                            <div class="d-grid gap-2 mt-4">
                                <a href="<?= base_url("admin/invoices/create?customer_id=" . $customer['id']) ?>" class="btn btn-primary">New Invoice</a>
                                <a href="#" class="btn btn-warning">Send Reminder</a>
                                <a href="#" class="btn btn-secondary">Disable A/c</a>
                                <a href="<?= base_url("admin/customers/delete/" . $customer['id']) ?>" class="btn btn-danger" onclick="return confirm('Delete this account?')">Delete Account</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content -->
                <div class="col-md-9">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <a class="nav-link <?= $tab === 'profile' ? 'active' : '' ?>" href="<?= base_url("admin/customers/show/{$customer['id']}?tab=profile") ?>">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $tab === 'accounts' ? 'active' : '' ?>" href="<?= base_url("admin/customers/show/{$customer['id']}?tab=accounts") ?>">Accounts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $tab === 'payments' ? 'active' : '' ?>" href="<?= base_url("admin/customers/show/{$customer['id']}?tab=payments") ?>">Payment History</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $tab === 'invoices' ? 'active' : '' ?>" href="<?= base_url("admin/customers/show/{$customer['id']}?tab=invoices") ?>">Invoices</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="card">
                        <div class="card-body">
                            <?php $this->load->view("admin/customers/tabs/{$tab}"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>