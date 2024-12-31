<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Payment Methods</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/PaymentMethods/add'); ?>" class="btn btn-primary">Add Payment Method</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <?php if ($this->session->flashdata('message')) : ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('message'); ?>
                        </div>
                    <?php endif; ?>
                    <table id="commonTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($payment_methods)) : ?>
                                <?php foreach ($payment_methods as $method) : ?>
                                    <tr>
                                        <td><?php echo $method['id']; ?></td>
                                        <td><?php echo $method['title']; ?></td>
                                        <td><?php echo $method['code']; ?></td>
                                        <td><?php echo $method['status'] ? 'Active' : 'Inactive'; ?></td>
                                        <td><?php echo date('d-m-Y H:i:s', strtotime($method['created_at'])); ?></td>
                                        <td>
                                            <a href="<?php echo base_url('admin/PaymentMethods/edit/' . $method['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="<?php echo base_url('admin/PaymentMethods/delete/' . $method['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this payment method?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6">No payment methods found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>