<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Admin Users</h2>
            <a href="<?php echo base_url('admin/adminAccounts/add'); ?>" class="btn btn-info">Add User</a>
        </div>
    </section>

    <!-- Admin user list -->
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

                            <?php if (count($users) > 0) { ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Full Name</th>
                                            <th>Mobile</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user) { ?>
                                            <tr>
                                                <td><?php echo $user['id']; ?></td>
                                                <td><?php echo $user['username']; ?></td>
                                                <td><?php echo $user['full_name']; ?></td>
                                                <td><?php echo $user['mobile']; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url('admin/adminAccounts/edit/' . $user['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                                    <a href="<?php echo base_url('admin/adminUsers/deleteAdminUser/' . $user['id']); ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="btn btn-sm btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <p>No users found.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(function() {
        $('#user').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": true,
            "responsive": true,
        });
    });
</script>