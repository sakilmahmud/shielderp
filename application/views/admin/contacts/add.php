<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1><?php echo $isUpdate ? 'Edit' : 'Add'; ?> Contact</h1>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <form method="post">
                <div class="form-group">
                    <label>Group</label>
                    <select name="contacts_group_id" class="form-control" required>
                        <option value="">Select Group</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?php echo $group['id']; ?>"
                                <?php echo isset($contact) && $contact['contacts_group_id'] == $group['id'] ? 'selected' : ''; ?>>
                                <?php echo $group['title']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control"
                        value="<?php echo isset($contact) ? $contact['full_name'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Contact</label>
                    <input type="text" name="contact" class="form-control"
                        value="<?php echo isset($contact) ? $contact['contact'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" class="form-control"
                        value="<?php echo isset($contact) ? $contact['dob'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control"><?php echo isset($contact) ? $contact['address'] : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1" <?php echo isset($contact) && $contact['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?php echo isset($contact) && $contact['status'] == 0 ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><?php echo $isUpdate ? 'Update' : 'Add'; ?> Contact</button>
            </form>
        </div>
    </div>
</div>