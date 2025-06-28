<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Brands Export / Import</h2>
            <a href="<?php echo base_url('admin/brands'); ?>" class="btn btn-secondary">Back to Brands</a>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('message')): ?>
                <div class="alert alert-success"><?= $this->session->flashdata('message') ?></div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <strong>Export Brands</strong>
                </div>
                <div class="card-body">
                    <a href="<?= base_url('admin/brands-export') ?>" class="btn btn-success">Export All Brands to CSV</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info text-white">
                    <strong>Import Brands</strong>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/brands-import') ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="csv_file">Upload CSV File</label>
                            <input type="file" name="csv_file" id="csv_file" class="form-control" required accept=".csv">
                            <small class="text-muted">Allowed format: .csv | Columns: brand_name, brand_descriptions</small>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Import</button>
                        <a href="<?= base_url('uploads/samples/brands_sample.csv') ?>" class="btn btn-secondary mt-2">Download Sample CSV</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>