<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>HSN Codes</h2>
                        <div>
                <a href="<?= base_url('admin/hsn-codes-export-import') ?>" class="btn btn-info btn-sm">Export / Import</a>
                <a href="<?= base_url('admin/hsn-codes/create') ?>" class="btn btn-primary btn-sm">Add HSN Code</a>
            </div>
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
                    <div class="table-responsive">
                        <table id="hsnTable" class="table table-striped" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>HSN Code</th>
                                    <th>Description</th>
                                    <th>GST Rate</th>
                                    <th>Action</th>
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
        $('#hsnTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('admin/hsn-codes/ajax-list') ?>",
                "type": "POST"
            }
        });
    });
</script>