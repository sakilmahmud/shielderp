<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Products Export & Import</h2>
            <a href="<?php echo base_url('admin/products'); ?>" class="btn btn-secondary">Back to Products</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('message')) : ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('message'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>
            <div class="card">
                <div class="card-body">
                    <a href="<?= base_url('admin/products-export-import/export'); ?>" class="btn btn-success mb-3">Export Products</a>
                    <form action="<?= base_url('admin/products-export-import/import'); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Select CSV file to import</label>
                            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Import Products</button>
                        <a href="<?= base_url('uploads/samples/products_sample.csv') ?>" class="btn btn-secondary mt-2">Download Sample CSV</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>