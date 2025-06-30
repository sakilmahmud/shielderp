<style>
    .profile-photo-wrapper {
        position: relative;
        display: inline-block;
    }

    .profile-photo-wrapper img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #ddd;
    }

    .photo-action-icons {
        position: absolute;
        bottom: -10%;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        background-color: rgba(67, 65, 65, 0.8);
        padding: 5px;
        border-radius: 12px;
    }

    .photo-action-icons i {
        cursor: pointer;
        color: #333;
        font-size: 14px;
    }

    .photo-action-icons i:hover {
        color: #007bff;
    }

    #photoInput {
        display: none;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
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

                        <div class="card-body text-center">
                            <div class="profile-photo-wrapper">
                                <img id="profileImage" src="<?= !empty($customer['photo']) ? base_url($customer['photo']) : base_url('assets/admin/img/user.jpg') ?>" alt="Customer Photo">
                                <div class="photo-action-icons">
                                    <i class="bi bi-pencil-square text-light" title="Edit Photo" onclick="document.getElementById('photoInput').click()" style="cursor:pointer;"></i>
                                    <i class="bi bi-x-circle text-light" title="Remove Photo" onclick="removePhoto()" style="cursor:pointer;"></i>
                                </div>
                            </div>

                            <form id="photoForm" method="post" enctype="multipart/form-data" action="<?= base_url('admin/customers/upload_photo') ?>">
                                <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">
                                <input type="file" name="photo" id="photoInput" accept="image/*" onchange="document.getElementById('photoForm').submit()">
                            </form>

                            <h4 class="mt-3"><?= !empty($customer['customer_name']) ? $customer['customer_name'] : 'Unnamed' ?></h4>

                            <p>ID: <strong><?= $customer['id'] ?></strong></p>

                            <p>Balance:
                                <strong class="<?= $customer['balance'] < 0 ? 'text-danger' : 'text-success' ?>">
                                    ₹<?= number_format($customer['balance'], 2) ?>
                                </strong>
                            </p>

                            <p>Status:
                                <strong class="<?= $customer['status'] == 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= $customer['status'] == 0 ? 'Deactivated' : 'Active' ?>
                                </strong>
                            </p>
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
<script>
    function removePhoto() {
        if (confirm("Are you sure you want to remove this photo?")) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "<?= base_url('admin/customers/remove_photo') ?>";

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'customer_id';
            input.value = "<?= $customer['id'] ?>";

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>