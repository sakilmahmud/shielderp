<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>List of Units</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/units/add'); ?>" class="btn btn-primary">Add Unit</a>
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
                            <?php if ($this->session->flashdata('message')) : ?>
                                <div class="alert alert-success"><?php echo $this->session->flashdata('message'); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($units)) : ?>
                                <table class="table table-sm table-bordered table-striped" id="commonTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Symbol</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($units as $unit) : ?>
                                            <tr>
                                                <td><?php echo $unit['id']; ?></td>
                                                <td><?php echo $unit['name']; ?></td>
                                                <td><?php echo $unit['symbol']; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url('admin/units/edit/' . $unit['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="<?php echo base_url('admin/units/delete/' . $unit['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this unit?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No units found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>