<?php if ($this->session->flashdata('message')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('message') ?></div>
<?php endif; ?>

<form method="post" class="row g-3">
    <div class="col-md-6">
        <label for="customer_name" class="form-label">Customer Name</label>
        <input type="text" name="customer_name" class="form-control" value="<?= $customer['customer_name'] ?? '' ?>" required>
    </div>

    <div class="col-md-6">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="<?= $customer['phone'] ?? '' ?>" required>
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= $customer['email'] ?? '' ?>">
    </div>

    <div class="col-md-6">
        <label for="gst_number" class="form-label">GST Number</label>
        <input type="text" name="gst_number" class="form-control" value="<?= $customer['gst_number'] ?? '' ?>">
    </div>

    <div class="col-md-12">
        <label for="address" class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="3"><?= $customer['address'] ?? '' ?></textarea>
    </div>

    <div class="col-md-6">
        <label for="state_id" class="form-label">State</label>
        <select name="state_id" id="state_id" class="form-control" required>
            <option value="">Select State</option>
            <?php foreach ($states as $state): ?>
                <option value="<?php echo $state['id']; ?>" <?php echo (set_value('state_id', $customer['state_id']) == $state['id']) ? 'selected' : ''; ?>><?php echo $state['state_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4">
        <label for="status" class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="1" <?= $customer['status'] == 1 ? 'selected' : '' ?>>Active</option>
            <option value="0" <?= $customer['status'] == 0 ? 'selected' : '' ?>>Deactivated</option>
        </select>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-success">Update Profile</button>
    </div>
</form>