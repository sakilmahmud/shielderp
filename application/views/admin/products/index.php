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
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>List of Products</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/products/add'); ?>" class="btn btn-primary">Add Product</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if (!empty($error)) : ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('duplicate')) : ?>
                                <div class="alert alert-warning">
                                    <?php echo $this->session->flashdata('duplicate'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('update')) : ?>
                                <div class="alert alert-info">
                                    <?php echo $this->session->flashdata('update'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('message')) : ?>
                                <div class="alert alert-success">
                                    <?php echo $this->session->flashdata('message'); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($this->session->flashdata('error')) : ?>
                                <div class="alert alert-danger">
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="card_header">
                                <form method="get" action="<?php echo base_url('admin/products'); ?>">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="category_id">Category</label>
                                            <select class="form-control category_id" id="category_id" name="category_id">
                                                <option value="">All</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>" <?php echo ($this->input->get('category_id') == $category['id']) ? 'selected' : ''; ?>>
                                                        <?php echo $category['name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="brand_id">Brand</label>
                                            <select class="form-control brand_id" id="brand_id" name="brand_id">
                                                <option value="">All</option>
                                                <?php foreach ($brands as $brand): ?>
                                                    <option value="<?php echo $brand['id']; ?>" <?php echo ($this->input->get('brand_id') == $brand['id']) ? 'selected' : ''; ?>>
                                                        <?php echo $brand['brand_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="product_type_id">Product Type</label>
                                            <select class="form-control" id="product_type_id" name="product_type_id">
                                                <option value="">All</option>
                                                <?php foreach ($product_types as $product_type): ?>
                                                    <option value="<?php echo $product_type['id']; ?>" <?php echo ($this->input->get('product_type_id') == $product_type['id']) ? 'selected' : ''; ?>>
                                                        <?php echo $product_type['product_type_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-4">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <?php if (!empty($products)) : ?>
                                <table class="table table-sm table-striped table-bordered" id="commonTable">

                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Price</th>
                                            <th>Stocks</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product) :
                                            $getProductStocks = getProductStocks($product['id']);
                                            if (!empty($getProductStocks)) {
                                                $total_quantity = $getProductStocks['total_quantity'];
                                                $total_available_stocks = $getProductStocks['total_available_stocks'];
                                            } else {

                                                $total_quantity = 0;
                                                $total_available_stocks = 0;
                                            }
                                            $endpoint = ($product['slug'] != "") ? $product['slug'] : $product['id'];
                                        ?>
                                            <tr>
                                                <td><?php echo $product['id']; ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <img src="<?php echo base_url('uploads/products/' . $product['featured_image']); ?>" alt="Featured Image" style="max-width: 50px;">
                                                    </div>
                                                </td>
                                                <td><a href="<?php echo base_url('products/' . $endpoint); ?>" target="_blank"><?php echo $product['name']; ?></a></td>
                                                <td><?php echo $product['category_name']; ?></td>
                                                <td><?php echo $product['brand_name']; ?></td>
                                                <td>
                                                    <p>MRP: ₹<?php echo number_format($product['regular_price'], 2); ?></p>
                                                    <p>Sale: ₹<?php echo number_format($product['sale_price'], 2); ?></p>
                                                    <p>
                                                        Purchase:
                                                        <span class="purchase-price" data-product-id="<?php echo $product['id']; ?>" style="display: none;">
                                                            ₹<?php echo number_format($product['purchase_price'], 2); ?>
                                                        </span>
                                                        <a href="javascript:void(0);"
                                                            class="show_pp"
                                                            data-product-id="<?php echo $product['id']; ?>"
                                                            data-purchase-price="₹<?php echo number_format($product['purchase_price'], 2); ?>">
                                                            Show
                                                        </a>
                                                    </p>
                                                    <a href="javascript:void(0);"
                                                        class="quick-edit"
                                                        data-product-id="<?php echo $product['id']; ?>"
                                                        data-product-name="<?php echo $product['name']; ?>"
                                                        <i class="fa fa-edit"></i> Quick Edit
                                                    </a>
                                                </td>

                                                <td>
                                                    Total Purchased: <?php echo $total_quantity; ?><br>
                                                    In stocks: <?php echo $total_available_stocks; ?><br>
                                                    <a href="javascript:void(0);"
                                                        class="quick-stock-update"
                                                        data-product-id="<?php echo $product['id']; ?>"
                                                        data-product-name="<?php echo $product['name']; ?>"
                                                        data-total-purchased="<?php echo $total_quantity; ?>"
                                                        data-current-stocks="<?php echo $total_available_stocks; ?>">
                                                        <i class="fa fa-plus-circle"></i> Quick Stock Update
                                                    </a>
                                                </td>

                                                <td>
                                                    <a href="<?php echo base_url('admin/products/edit/') . $product['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="<?php echo base_url('admin/products/delete/') . $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No products found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Stock Update Modal -->
<div class="modal fade" id="stockUpdateModal" tabindex="-1" role="dialog" aria-labelledby="stockUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockUpdateModalLabel">Update Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="stockInfo"></p>
                <form id="stockUpdateForm">
                    <div class="form-group">
                        <label>Action:</label><br>
                        <input type="radio" name="action" value="increase" id="increase" required>
                        <label for="increase">Increase</label><br>
                        <input type="radio" name="action" value="decrease" id="decrease" required>
                        <label for="decrease">Decrease</label>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="sale_price">Sale Price</label>
                        <input type="number" class="form-control" id="st_sale_price" name="sale_price" required>
                    </div>
                    <div class="form-group">
                        <label for="purchase_price">Purchase Price</label>
                        <input type="number" class="form-control" id="st_purchase_price" name="purchase_price" required>
                    </div>
                    <input type="hidden" id="stock_product_id" name="product_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateStock">Update Stock</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="quickEditModal" tabindex="-1" role="dialog" aria-labelledby="quickEditModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickEditModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="quickEditForm">
                    <div class="form-group">
                        <label for="mrp">MRP</label>
                        <input type="number" class="form-control" id="mrp" name="mrp" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="sale_price">Sale Price</label>
                        <input type="number" class="form-control" id="sale_price" name="sale_price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="purchase_price">Purchase Price</label>
                        <input type="number" class="form-control" id="purchase_price" name="purchase_price" step="0.01" required>
                    </div>
                    <input type="hidden" id="product_id" name="product_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateProduct">Update</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Show modal with product data
        $('.quick-stock-update').on('click', function() {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            const totalPurchased = $(this).data('total-purchased');
            const currentStocks = $(this).data('current-stocks');

            // Set modal header and stock information
            $('#stockUpdateModalLabel').text(`Update Stock: ${productName}`);
            $('#stockInfo').html(`Total Purchased: ${totalPurchased} | Current Stocks: ${currentStocks}`);
            $('#stock_product_id').val(productId);

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

                        // Use 'last_purchase_price' if available, otherwise fallback to 'purchase_price'
                        const purchasePrice = response.last_purchase_price !== null ?
                            response.last_purchase_price :
                            response.prices.purchase_price;

                        $('#st_purchase_price').val(purchasePrice || 0);
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
        $('#updateStock').on('click', function() {
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
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert(response.message || 'Failed to update stock.');
                    }
                },
                error: function() {
                    alert('An error occurred while updating stock.');
                }
            });
        });
    });

    $(document).ready(function() {
        // Show modal with product data
        $('.quick-edit').on('click', function() {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');

            // Populate modal fields
            $('#quickEditModalLabel').text(`Edit Product: ${productName}`);
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

                        // Use 'last_purchase_price' if available, otherwise fallback to 'purchase_price'
                        const purchasePrice = response.last_purchase_price !== null ?
                            response.last_purchase_price :
                            response.prices.purchase_price;

                        $('#mrp').val(response.prices.regular_price);
                        $('#sale_price').val(response.prices.sale_price);
                        $('#purchase_price').val(purchasePrice);
                    } else {
                        $('#mrp').val(0);
                        $('#sale_price').val(0);
                        $('#purchase_price').val(0);
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
        $('#updateProduct').on('click', function() {
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
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert(response.message || 'Failed to update the product.');
                    }
                },
                error: function() {
                    alert('An error occurred while updating the product.');
                }
            });
        });
    });

    $(document).ready(function() {
        $('.show_pp').on('click', function() {
            const purchasePrice = $(this).data('purchase-price');
            $(this).siblings('.purchase-price').text(purchasePrice).show(); // Show the purchase price
            $(this).hide(); // Hide the "Show" link
        });
    });
    $(document).ready(function() {
        $('.show_pp').on('click', function() {
            const productId = $(this).data('product-id'); // Add a `data-product-id` attribute to your HTML element

            $.ajax({
                url: '<?php echo base_url('/'); ?>admin/products/last_purchase_price',
                type: 'POST',
                data: {
                    product_id: productId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const purchasePrice = response.purchase_price;
                        $(`.purchase-price[data-product-id="${productId}"]`).text(`₹${purchasePrice}`).show();
                        $(`.show_pp[data-product-id="${productId}"]`).hide();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while fetching the purchase price.');
                }
            });
        });
    });
</script>