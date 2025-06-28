<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Brands</h2>
            <a href="<?php echo base_url('admin/brands-export-import'); ?>" class="btn btn-info">Brands Export / Import</a>
            <a href="<?php echo base_url('admin/brands/add'); ?>" class="btn btn-primary">Add Brand</a>
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
                    <table id="commonTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Brand Name</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($brands)) : ?>
                                <?php foreach ($brands as $brand) : ?>
                                    <tr>
                                        <td><?php echo $brand['id']; ?></td>
                                        <td><?php echo $brand['brand_name']; ?></td>
                                        <td><?php echo $brand['brand_descriptions']; ?></td>
                                        <td><?php echo date('d-m-Y H:i:s', strtotime($brand['created_at'])); ?></td>
                                        <td>
                                            <a href="<?php echo base_url('admin/brands/edit/' . $brand['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="<?php echo base_url('admin/brands/delete/' . $brand['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this brand?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5">No brands found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>