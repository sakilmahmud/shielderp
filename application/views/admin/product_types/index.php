<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Product Types</h2>
            <a href="<?php echo base_url('admin/product-type/add'); ?>" class="btn btn-primary">Add Product Type</a>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('message')) : ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('message'); ?>
                        </div>
                    <?php endif; ?>
                    <table id="commonTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Type Name</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($product_types)) : ?>
                                <?php foreach ($product_types as $product_type) : ?>
                                    <tr>
                                        <td><?php echo $product_type['id']; ?></td>
                                        <td><?php echo $product_type['product_type_name']; ?></td>
                                        <td><?php echo $product_type['product_type_descriptions']; ?></td>
                                        <td><?php echo date('d-m-Y H:i:s', strtotime($product_type['created_at'])); ?></td>
                                        <td>
                                            <a href="<?php echo base_url('admin/product-type/edit/' . $product_type['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="<?php echo base_url('admin/product-type/delete/' . $product_type['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product_type?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5">No product types found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>