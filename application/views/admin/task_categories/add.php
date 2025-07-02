<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><?= $isUpdate ? 'Edit' : 'Add' ?> Task Category</h2>
            <a href="<?= base_url('admin/task-categories') ?>" class="btn btn-info">Task Categories</a>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="<?= base_url('admin/task-categories/save') ?>">
                        <?php if ($isUpdate): ?>
                            <input type="hidden" name="id" value="<?= $category['id'] ?>">
                        <?php endif; ?>
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" name="cat_name" class="form-control" required value="<?= set_value('cat_name', $category['cat_name'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Descriptions</label>
                            <textarea name="cat_descriptions" class="form-control"><?= set_value('cat_descriptions', $category['cat_descriptions'] ?? '') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Parent Category</label>
                            <select name="parent_id" class="form-control">
                                <option value="0">-- None --</option>
                                <?php foreach ($parents as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= set_select('parent_id', $cat['id'], (isset($category['parent_id']) && $category['parent_id'] == $cat['id'])) ?>>
                                        <?= $cat['cat_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Order</label>
                            <input type="number" name="cat_order" class="form-control" value="<?= set_value('cat_order', $category['cat_order'] ?? 0) ?>">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="1" <?= (isset($category['status']) && $category['status'] == 1) ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= (isset($category['status']) && $category['status'] == 0) ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success mt-2"><?= $isUpdate ? 'Update' : 'Add' ?></button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>