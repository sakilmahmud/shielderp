<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>List of Clients</h2>
            <a href="<?php echo base_url('admin/clients/add'); ?>" class="btn btn-info">Add Client</a>
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

                            <?php if (!empty($clients)) : ?>
                                <table id="clientTable" class="table table-sm table-striped table-bordered">
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
                                        <?php foreach ($clients as $client) : ?>
                                            <tr>
                                                <td><?php echo $client['id']; ?></td>
                                                <td><?php echo !empty($client['username']) ? $client['username'] : $client['mobile']; ?></td>
                                                <td><?php echo !empty($client['email']) ? $client['email'] : '-'; ?></td>
                                                <td><?php echo $client['mobile']; ?></td>

                                                <td><?php echo $client['full_name']; ?></td>
                                                <td><?php echo ($client['status'] == 1) ? 'Active' : 'Inactive'; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url('admin/clients/edit/') . $client['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="<?php echo base_url('admin/clients/delete/') . $client['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this client?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No clients found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        $('#clientTable').DataTable({
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