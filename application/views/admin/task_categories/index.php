<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h3>Task Categories</h3>
            <a href="<?= base_url('admin/task-categories/add') ?>" class="btn btn-info">Add Category</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <?php if ($this->session->flashdata('message')) : ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('message'); ?>
                        </div>
                    <?php endif; ?>
                    <table id="categoryTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Descriptions</th>
                                <th>Parent</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#categoryTable').DataTable({
            ajax: '<?= base_url('admin/task-categories/ajax-list') ?>',
            columns: [{
                    data: 0
                }, {
                    data: 1
                }, {
                    data: 2
                }, {
                    data: 3
                },
                {
                    data: 4
                }, {
                    data: 5
                }, {
                    data: 6
                }
            ]
        });

        $(document).on('click', '.delete-category', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure?')) {
                $.get('<?= base_url('admin/task-categories/delete/') ?>' + id, function(response) {
                    $('#categoryTable').DataTable().ajax.reload();
                });
            }
        });
    });
</script>