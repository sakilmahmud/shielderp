<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Supplier Management</h2>
            <a href="<?php echo base_url('admin/suppliers/add'); ?>" class="btn btn-primary">Add Supplier</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if ($this->session->flashdata('message')) : ?>
                                <div class="alert alert-success">
                                    <?php echo $this->session->flashdata('message'); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($suppliers)) : ?>
                                <table class="table table-sm table-striped table-bordered" id="commonTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Supplier Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>GST Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($suppliers as $supplier) : ?>
                                            <tr>
                                                <td><?php echo $supplier['id']; ?></td>
                                                <td><?php echo $supplier['supplier_name']; ?></td>
                                                <td><?php echo $supplier['phone']; ?></td>
                                                <td><?php echo $supplier['email']; ?></td>
                                                <td><?php echo $supplier['address']; ?></td>
                                                <td><?php echo $supplier['gst_number']; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url('admin/suppliers/edit/' . $supplier['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="<?php echo base_url('admin/suppliers/delete/' . $supplier['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <div class="alert alert-info">No suppliers found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>