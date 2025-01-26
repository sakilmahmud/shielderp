<!-- View for displaying expense heads -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Expense Heads</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/expense/head/add'); ?>" class="btn btn-primary">Add Expense Head</a>
                </div>
            </div>
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
                            <table id="commonTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Head Title</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($expenseHeads as $head): ?>
                                        <tr>
                                            <td><?php echo $head['id']; ?></td>
                                            <td><?php echo $head['head_title']; ?></td>
                                            <td><?php echo $head['description']; ?></td>
                                            <td><?php echo ($head['status'] == 1) ? 'Active' : 'Inactive'; ?></td>
                                            <td>
                                                <a href="<?php echo base_url('admin/expense/head/edit/' . $head['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="<?php echo base_url('admin/expense/head/delete/' . $head['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>