<footer class="app-footer">
    <div class="float-end d-none d-sm-inline"><b>Version</b> 2.1.1</div>
    <strong><?php echo getSetting('footer_text'); ?></strong>
</footer>

<!-- Task Details Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Description:</strong> <span id="taskDescription"></span></p>
                <p><strong>Category:</strong> <span id="taskCategory"></span></p>
                <p><strong>Start Time:</strong> <span id="taskStartTime"></span></p>
                <p><strong>End Time:</strong> <span id="taskEndTime"></span></p>
                <p><strong>Status:</strong> <span id="taskStatus"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="addProductForm">
                            <div class="form-group">
                                <div class="mt-1 d-flex justify-content-between">
                                    <label for="category_id">Category <sup>*</sup></label>
                                    <a href="javascript:void(0)" class="text-sm add_category">Add Category</a>
                                </div>
                                <select class="form-control category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category) { ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="mt-1 d-flex justify-content-between">
                                    <label for="brand_id">Brand <sup>*</sup></label>
                                    <a href="javascript:void(0)" class="text-sm add_brand">Add Brand</a>
                                </div>
                                <select class="form-control brand_id" id="brand_id" name="brand_id" required>
                                    <option value="">Select Brand</option>
                                    <?php foreach ($brands as $brand) { ?>
                                        <option value="<?php echo $brand['id']; ?>"><?php echo $brand['brand_name']; ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo form_error('brand_id'); ?>
                            </div>
                            <div class="form-group">
                                <label for="name">Product Name <sup>*</sup></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="mrp_price">MRP Price <sup>*</sup></label>
                                <input type="text" class="form-control" id="mrp_price" name="mrp_price" required>
                            </div>
                            <div class="form-group">
                                <label for="sale_price">Sale Price <sup>*</sup></label>
                                <input type="text" class="form-control" id="sale_price" name="sale_price" required>
                            </div>
                            <div class="form-group">
                                <label for="purchase_price">Purchase Price <sup>*</sup></label>
                                <input type="text" class="form-control" id="purchase_price" name="purchase_price" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Add Product</button>
                        </form>
                    </div>
                    <!-- <div class="col-md-4">
                        <div class="all_products_of_category"></div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" role="dialog" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBrandModalLabel">Add Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addBrandForm">
                    <div class="form-group">
                        <label for="brand_name">Brand Name</label>
                        <input type="text" class="form-control" id="brand_name" name="brand_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Add Brand</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addHsnCodeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Add HSN Code</h5>
            </div>
            <div class="modal-body">
                <form id="addHsnCodeForm">
                    <div class="form-group">
                        <label for="hsn_code">HSN Code</label>
                        <input type="text" class="form-control" name="hsn_code" required>
                    </div>
                    <div class="form-group">
                        <label for="gst_rate">GST Rate (%)</label>
                        <input type="number" step="0.01" class="form-control" name="gst_rate" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Add HSN</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addReminderModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" action="<?= base_url('admin/reminder/add') ?>" method="post">
            <div class="modal-header">
                <h5 class="modal-title">Add Reminder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea name="reminder_content" class="form-control" rows="4" placeholder="Write reminder..." required></textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="viewReminderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reminder Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="reminderDetailContent"></p>
                <small class="text-muted" id="reminderCreatedAt"></small>
            </div>
            <div class="modal-footer">
                <a href="#" id="markAsDoneBtn" class="btn btn-success">Mark as Done</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>