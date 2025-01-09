<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Bulk Add Contacts</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div class="form-group">
                        <a href="<?php echo base_url('contacts_demo_csv.csv'); ?>" class="btn btn-info">Download Sample File</a>
                    </div>
                    <form action="<?php echo base_url('admin/contacts/bulk-add'); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="contacts_file">Upload CSV File</label>
                            <input type="file" name="contacts_file" id="contacts_file" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>