<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>DTP Categories</h2>
            <a href="<?php echo base_url('admin/dtp/categories/add'); ?>" class="btn btn-primary">Add Category</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
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
                            <?php if (!empty($dtp_categories)) : ?>
                                <table id="commonTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Category Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($dtp_categories as $category) : ?>
                                            <tr>
                                                <td><?php echo $category['id']; ?></td>
                                                <td><?php echo $category['cat_title']; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url('admin/dtp/categories/edit/' . $category['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="<?php echo base_url('admin/dtp/categories/delete/' . $category['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No categories found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>