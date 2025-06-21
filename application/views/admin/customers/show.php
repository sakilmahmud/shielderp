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
                            <img src="<?= !empty($customer['photo']) ? $customer['photo'] : base_url('assets/img/default-user.png') ?>" class="img-fluid rounded-circle mb-3" width="100" alt="Customer Photo">

                            <h4><?= !empty($customer['customer_name']) ? $customer['customer_name'] : 'Unnamed' ?></h4>

                            <?php if (!empty($customer['id'])): ?>
                                <p>ID: <strong><?= $customer['id'] ?></strong></p>
                            <?php endif; ?>

                            <p>Balance:
                                <strong>
                                    ₹<?= isset($customer['balance']) ? number_format($customer['balance'], 2) : '0.00' ?>
                                </strong>
                            </p>

                            <p>Status:
                                <strong class="<?= isset($customer['status']) && $customer['status'] == 'inactive' ? 'text-danger' : 'text-success' ?>">
                                    <?= isset($customer['status']) ? ucfirst($customer['status']) : 'Active' ?>
                                </strong>
                            </p>

                            <div class="d-grid gap-2 mt-4">
                                <?php if (!empty($customer['id'])): ?>
                                    <a href="<?= base_url("admin/invoices/create?customer_id=" . $customer['id']) ?>" class="btn btn-primary">New Invoice</a>
                                    <a href="#" class="btn btn-warning">Send Reminder</a>
                                    <a href="#" class="btn btn-secondary">Disable A/c</a>
                                    <a href="<?= base_url("admin/customers/delete/" . $customer['id']) ?>" class="btn btn-danger" onclick="return confirm('Delete this account?')">Delete Account</a>
                                <?php endif; ?>
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
                            <?php
                            if (!empty($tab) && in_array($tab, ['profile', 'accounts', 'payments', 'invoices'])) {
                                $this->load->view("admin/customers/tabs/{$tab}");
                            } else {
                                echo '<div class="alert alert-warning">Invalid tab selected.</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>