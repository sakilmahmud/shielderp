<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>States</h1>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <a href="<?php echo base_url('admin/settings/states/add'); ?>" class="btn btn-primary mb-3">Add State</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>State Name</th>
                                <th>State Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($states as $state): ?>
                                <tr>
                                    <td><?php echo $state['id']; ?></td>
                                    <td><?php echo $state['state_name']; ?></td>
                                    <td><?php echo $state['state_code']; ?></td>
                                    <td>
                                        <a href="<?php echo base_url('admin/settings/states/edit/' . $state['id']); ?>" class="btn btn-info btn-sm">Edit</a>
                                        <a href="<?php echo base_url('admin/settings/states/delete/' . $state['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this state?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>