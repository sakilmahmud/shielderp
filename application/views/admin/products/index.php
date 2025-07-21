<style>
    .sale-price {
        color: red;
        font-weight: bold;
    }

    tbody td p {
        margin-bottom: 0 !important;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>List of Products</h2>
            <a href="<?php echo base_url('admin/products-export-import'); ?>" class="btn btn-info">Products Export & Import</a>
            <a href="<?php echo base_url('admin/products/add'); ?>" class="btn btn-primary">Add Product</a>
        </div>
    </section>

    <?php if ($this->session->flashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $this->session->flashdata('message'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error_message')) : ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo $this->session->flashdata('error_message'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form id="filterForm" class="mb-2 bg-light p-1">
                        <div class="row">
                            <div class="col-6 col-md-3">
                                <select class="form-control filter-input category_id" id="category_id">
                                    <option value="">All Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>" <?php echo ($this->input->get('category_id') == $category['id']) ? 'selected' : ''; ?>>
                                            <?php echo $category['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <select class="form-control filter-input brand_id" id="brand_id">
                                    <option value="">All Brand</option>
                                    <?php foreach ($brands as $brand): ?>
                                        <option value="<?php echo $brand['id']; ?>" <?php echo ($this->input->get('brand_id') == $brand['id']) ? 'selected' : ''; ?>>
                                            <?php echo $brand['brand_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="d-none d-md-block col-md-2 mt-md-0">
                                <select class="form-control filter-input" id="product_type_id">
                                    <option value="">All Type</option>
                                    <?php foreach ($product_types as $product_type): ?>
                                        <option value="<?php echo $product_type['id']; ?>" <?php echo ($this->input->get('product_type_id') == $product_type['id']) ? 'selected' : ''; ?>>
                                            <?php echo $product_type['product_type_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6 col-md-2 mt-2 mt-md-0">
                                <select class="form-control filter-input" id="stock_filter">
                                    <option value="">All Stock</option>
                                    <option value="positive">Available</option>
                                    <option value="zero">No Stock</option>
                                    <option value="negative">Negative</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-2 mt-2 mt-md-0">
                                <button type="button" id="resetFilter" class="btn btn-secondary btn-sm">Reset</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped" id="productsTable" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>HSN Code</th>
                                    <th>Price</th>
                                    <th>Stocks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Total:</th>
                                    <th id="totalAmount">₹0.00</th>
                                    <th id="totalStock">0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    const table = $('#productsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: true,
        pageLength: 40,
        lengthMenu: [
            [40, 100, -1], // Options for number of items per page
            [40, 100, "All"] // Labels for those options
        ],
        ajax: {
            url: '<?php echo base_url("admin/products/ajax_list"); ?>',
            type: 'POST',
            data: function(d) {
                d.category_id = $('#category_id').val();
                d.brand_id = $('#brand_id').val();
                d.product_type_id = $('#product_type_id').val();
                d.stock = $('#stock_filter').val();
            },
            dataSrc: function(json) {
                // Update the totals in the footer
                $('#totalAmount').text(json.totals.total_amount);
                $('#totalStock').text(json.totals.total_stock);
                return json.data;
            }
        },
        columns: [{
                data: 0
            },
            {
                data: 1
            },
            {
                data: 2
            },
            {
                data: 3
            },
            {
                data: 4
            },
            {
                data: 5
            },
            {
                data: 6
            },
            {
                data: 7
            },
            {
                data: 8
            }
        ]
    });

    $('.filter-input').on('change', function() {
        table.ajax.reload();
    });

    $('#resetFilter').on('click', function() {
        $('#filterForm')[0].reset();
        table.ajax.reload();
    });
</script>


<!-- Stock Update Modal -->
<div class="modal fade" id="stockUpdateModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockUpdateModalLabel">Update Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="stockInfo" class="mb-3 text-muted"></p>

                <form id="stockUpdateForm">
                    <div class="mb-3">
                        <label class="form-label d-block">Action:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="action" value="increase" id="increase" required>
                            <label class="form-check-label" for="increase">Increase</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="action" value="decrease" id="decrease" required>
                            <label class="form-check-label" for="decrease">Decrease</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>

                    <div class="mb-3">
                        <label for="st_sale_price" class="form-label">Sale Price</label>
                        <input type="number" class="form-control" id="st_sale_price" name="sale_price" required>
                    </div>

                    <div class="mb-3">
                        <label for="st_purchase_price" class="form-label">Purchase Price</label>
                        <input type="number" class="form-control" id="st_purchase_price" name="purchase_price" required>
                    </div>

                    <input type="hidden" id="stock_product_id" name="product_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateStock">Update Stock</button>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="quickEditModal" tabindex="-1" aria-labelledby="quickEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Centered modal -->
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="quickEditModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="quickEditForm">
                    <div class="mb-3">
                        <label for="purchase_price" class="form-label">Purchase Price</label>
                        <input type="number" class="form-control" id="purchase_price" name="purchase_price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="sale_price" class="form-label">Sale Price</label>
                        <input type="number" class="form-control" id="sale_price" name="sale_price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="mrp" class="form-label">MRP</label>
                        <input type="number" class="form-control" id="mrp" name="mrp" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-group">
                            <label for="hsn_code_id">HSN Code</label>
                            <div class="input-group">
                                <select class="form-control" id="hsn_code_id" name="hsn_code_id">
                                    <option value="">Select HSN</option>
                                    <?php foreach ($hsn_codes as $hsn) { ?>
                                        <option value="<?= $hsn['id']; ?>"
                                            data-gst="<?= $hsn['gst_rate']; ?>"
                                            <?= set_select('hsn_code_id', $hsn['id'], isset($product['hsn_code_id']) && $product['hsn_code_id'] == $hsn['id']) ?>>
                                            <?= $hsn['hsn_code']; ?> - <?= $hsn['description']; ?> (<?= $hsn['gst_rate']; ?>%)
                                        </option>
                                    <?php } ?>
                                </select>
                                <button type="button" class="btn btn-outline-secondary" id="add_hsn_code_btn">Add HSN</button>
                            </div>
                            <?php echo form_error('hsn_code_id'); ?>
                        </div>
                    </div>
                    <input type="hidden" id="product_id" name="product_id">
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateProduct">Update</button>
            </div>

        </div>
    </div>
</div>

<script>
    // Show modal with product data
    $(document).on('click', '.quick-stock-update', function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        const totalPurchased = $(this).data('total-purchased');
        const currentStocks = $(this).data('current-stocks');

        // Set modal header and stock information
        $('#stockUpdateModalLabel').text(`Update Stock: ${productName}`);
        $('#stockInfo').html(`Total Purchased: ${totalPurchased} | Current Stocks: ${currentStocks}`);
        $('#stock_product_id').val(productId);
        $('#quantity').val(0);
        $.ajax({
            url: '<?php echo base_url('/'); ?>admin/products/get_product_prices', // Adjust the endpoint as needed
            type: 'POST',
            data: {
                product_id: productId
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.prices) {
                    // Populate form fields with prices
                    $('#st_sale_price').val(response.prices.sale_price || 0);
                    $('#st_purchase_price').val(response.prices.purchase_price || 0);
                } else {
                    $('#st_sale_price').val(0);
                    $('#st_purchase_price').val(0);
                }
            },
            error: function() {
                alert('An error occurred while fetching product prices.');
            }
        });

        // Show the modal
        $('#stockUpdateModal').modal('show');
    });


    // Handle stock update submission
    $(document).on('click', '#updateStock', function() {
        const formData = $('#stockUpdateForm').serialize(); // Serialize form data

        $.ajax({
            url: '<?php echo base_url('/'); ?>admin/products/update_stock', // Adjust the endpoint as needed
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert('Stock updated successfully!');
                    $('#stockUpdateModal').modal('hide');
                    table.ajax.reload();
                } else {
                    alert(response.message || 'Failed to update stock.');
                }
            },
            error: function() {
                alert('An error occurred while updating stock.');
            }
        });
    });

    // Show modal with product data
    $(document).on('click', '.quick-edit', function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');

        // Populate modal fields
        $('#quickEditModalLabel').text(`${productName}`);
        $('#product_id').val(productId);

        $.ajax({
            url: '<?php echo base_url('/'); ?>admin/products/get_product_prices', // Adjust the endpoint as needed
            type: 'POST',
            data: {
                product_id: productId
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.prices) {
                    $('#purchase_price').val(response.prices.purchase_price || 0);
                    $('#mrp').val(response.prices.regular_price || 0);
                    $('#sale_price').val(response.prices.sale_price || 0);
                    $('#hsn_code_id').val(response.prices.hsn_code_id || '');
                } else {
                    $('#mrp').val(0);
                    $('#sale_price').val(0);
                    $('#purchase_price').val(0);
                    $('#hsn_code_id').val('');
                }
            },
            error: function() {
                alert('An error occurred while fetching product prices.');
            }
        });

        // Show the modal
        $('#quickEditModal').modal('show');
    });

    // Handle update button click
    $(document).on('click', '#updateProduct', function() {
        const formData = $('#quickEditForm').serialize(); // Get form data

        $.ajax({
            url: '<?php echo base_url('/'); ?>admin/products/update_price', // Adjust the endpoint as needed
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert('Product updated successfully!');
                    $('#quickEditModal').modal('hide');
                    table.ajax.reload();
                    //location.reload(); // Reload the page to reflect changes
                } else {
                    alert(response.message || 'Failed to update the product.');
                }
            },
            error: function() {
                alert('An error occurred while updating the product.');
            }
        });
    });

    $(document).on('click', '.show_pp', function() {
        const productId = $(this).data('product-id'); // Add a `data-product-id` attribute to your HTML element
        var purchasePrice = $(this).data('purchase-price');
        $(this).siblings('.purchase-price').text(purchasePrice).show(); // Show the purchase price
        $(this).hide(); // Hide the "Show" link
    });
</script>