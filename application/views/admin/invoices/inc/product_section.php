<div class="row product-row p-2 border rounded bg-light shadow-sm">
    <!-- Product Selector -->
    <div class="col-12 col-md-3 mb-2">
        <div class="form-group">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="product_id" class="form-label mb-0">Product</label>
                <a href="javascript:void(0)" class="text-sm add_product">
                    <span class="badge bg-success">Add Product</span>
                </a>
            </div>
            <select class="form-control product_id">
                <option value="">Select Product</option>
                <?php foreach ($products as $product) : ?>
                    <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Quantity -->
    <div class="col-6 col-md-1 mb-2">
        <label class="form-label">Qty <span class="product_unit text-muted small"></span></label>
        <input type="number" min="1" class="form-control quantity" value="1">
        <input type="hidden" class="unit">
    </div>

    <!-- Price -->
    <div class="col-12 col-md-3 mb-2 d-flex gap-2">
        <div class="w-100">
            <label class="form-label">Price</label>
            <input type="number" class="form-control price" value="0" id="modal_price">
        </div>

        <div class="w-100 hide_non_gst">
            <label class="form-label">With GST</label>
            <input type="number" class="form-control" value="0" id="modal_net_price">
        </div>
    </div>

    <!-- Discount Type -->
    <div class="col-12 col-md-2 mb-2 d-flex gap-2">
        <div class="w-100">
            <label class="form-label">Disc Type</label>
            <select class="form-control discount_type">
                <option value="0" selected>No</option>
                <option value="1">Flat</option>
                <option value="2">%</option>
            </select>
        </div>
        <div class="w-100 discount_amount_sec" style="display: none;">
            <label class="form-label">Disc Amt</label>
            <input type="number" min="0" value="0" class="form-control discount_amount" readonly>
        </div>
    </div>

    <!-- GST -->
    <div class="col-12 col-md-3 mb-2 d-flex gap-2">
        <div class="w-100 hide_non_gst">
            <label class="form-label">GST</label>
            <input type="number" class="form-control gst_rate" value="0">
            <input type="hidden" class="gst_amount">
        </div>

        <div class="w-100">
            <label class="form-label">Total</label>
            <input type="text" class="form-control total" readonly>
        </div>
        <div class="w-25">
            <label class="form-label"><small>(Ctrl+A)</small></label>
            <button type="button" class="btn btn-outline-secondary btn-sm add-product w-100 d-inline-block mb-1">+</button>
        </div>
    </div>


    <!-- Extra Section: HSN Code -->
    <div class="col-12 product_extra_section d-none">
        <div class="text-muted small">
            HSN Code: <span class="hsn_code"></span>
            <small class="highlight_text ms-2"></small>
        </div>
        <input type="hidden" class="hsn_code_val">
    </div>

    <!-- Extra Section: Description -->
    <div class="col-12 product_extra_section mt-3" style="display: none;">
        <input type="text" class="form-control product_descriptions" placeholder="Write Product Details">
    </div>

    <!-- Last Purchase Prices -->
    <div class="col-12 last_purchase_prices"></div>

</div>


<div class="no_stocks p-2 my-3 border rounded-3" style="display: none;">
    <p class="no_stock_txt"></p>
</div>
<div class="product_details p-2 my-3 border rounded-3" style="display: none;">
    <div class="d-flex justify-content-between">
        <div class="d-flex gap-3">
            <h5>In Stock <span class="in_stock"></span></h5>
        </div>
        <div class="stock_show_action">
            <button class="btn btn-outline-info show_details">+</button>
        </div>
    </div>

    <table id="stock-details-table" class="table table-hover table-bordered table-sm" style="display: none;">
        <thead>
            <tr>
                <th>Supplier Name</th>
                <th>Available Stock</th>
                <th>Purchase Price</th>
                <th>Purchase Date</th>
                <th>Batch No</th>
            </tr>
        </thead>
        <tbody>
            <!-- Stock rows will be added here by JavaScript -->
        </tbody>
    </table>
</div>