<div class="content-wrapper">
    <section class="content-header">
        <h2><?= isset($hsn) ? 'Edit' : 'Add' ?> HSN Code</h2>
    </section>
    <section class="content">
        <form method="post">
            <div class="form-group">
                <label>HSN Code</label>
                <input type="text" name="hsn_code" class="form-control" required value="<?= $hsn['hsn_code'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"><?= $hsn['description'] ?? '' ?></textarea>
            </div>
            <div class="form-group">
                <label>GST Rate (%)</label>
                <input type="number" step="0.01" name="gst_rate" class="form-control" required value="<?= $hsn['gst_rate'] ?? '' ?>">
            </div>
            <button type="submit" class="btn btn-success mt-2">Save</button>
            <a href="<?= base_url('admin/hsn-codes') ?>" class="btn btn-secondary mt-2">Cancel</a>
        </form>
    </section>
</div>