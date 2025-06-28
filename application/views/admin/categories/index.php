<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>List of Categories</h2>
            <a href="<?php echo base_url('admin/categories-export-import'); ?>" class="btn btn-info">Export / Import Categories</a>
            <a href="<?php echo base_url('admin/categories/add'); ?>" class="btn btn-primary">Add Category</a>
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

                            <?php if ($this->session->flashdata('error')) : ?>
                                <div class="alert alert-danger">
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($categories)) : ?>
                                <table class="table table-sm table-striped table-bordered" id="commonTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $category) : ?>
                                            <tr>
                                                <td><?php echo $category['id']; ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <img src="<?php echo base_url('uploads/categories/' . $category['featured_image']); ?>" alt="Featured Image" style="max-width: 50px;">
                                                    </div>
                                                </td>
                                                <td><?php echo $category['name']; ?></td>
                                                <td><?php echo $category['description']; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url('admin/categories/edit/') . $category['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="<?php echo base_url('admin/categories/delete/') . $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
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