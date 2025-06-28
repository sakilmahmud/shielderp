<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Export / Import Categories</h2>
            <a href="<?php echo base_url('admin/categories'); ?>" class="btn btn-secondary">Back to Categories</a>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('message')): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('message'); ?></div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <h4>Export Categories</h4>
                    <a href="<?php echo base_url('admin/export/categories'); ?>" class="btn btn-success mb-3">
                        Export All to CSV
                    </a>

                    <hr>

                    <h4>Import Categories</h4>
                    <form action="<?php echo base_url('admin/import/categories'); ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="csv_file">Upload CSV File</label>
                            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                        <a href="<?php echo base_url('uploads/samples/sample_categories.csv'); ?>" class="btn btn-link">Download Sample File</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>