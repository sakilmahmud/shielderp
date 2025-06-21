<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Customer Management</h2>
            <a href="<?php echo base_url('admin/customers/add'); ?>" class="btn btn-primary">Add Customer</a>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <table class="table table-sm table-bordered table-striped" id="customerTable" style="width: 100% !important;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
    $(function() {
        const table = $('#customerTable').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "pageLength": 25,
            "ajax": {
                "url": "<?= base_url('admin/customers/ajax-list') ?>",
                "type": "POST"
            }
        });

        $('#customerTable').on('click', '.delete-btn', function() {
            if (confirm("Are you sure you want to delete this customer?")) {
                const id = $(this).data('id');
                $.get("<?= base_url('admin/customers/delete/') ?>" + id, function() {
                    table.ajax.reload();
                });
            }
        });
    });
</script>