<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>List of Doers</h2>
            <a href="<?php echo base_url('admin/doers/add'); ?>" class="btn btn-info">Add Doer</a>
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

                    <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($doers)) : ?>
                        <table id="doerTable" class="table table-sm table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Full Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($doers as $doer) : ?>
                                    <tr>
                                        <td><?php echo $doer['id']; ?></td>
                                        <td><?php echo $doer['username']; ?></td>
                                        <td><?php echo $doer['email']; ?></td>
                                        <td><?php echo $doer['mobile']; ?></td>
                                        <td><?php echo $doer['full_name']; ?></td>
                                        <td><?php echo ($doer['status'] == 1) ? 'Active' : 'Inactive'; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('admin/doers/edit/') . $doer['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="<?php echo base_url('admin/doers/delete/') . $doer['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this doer?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p>No doers found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#doerTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    });
</script>