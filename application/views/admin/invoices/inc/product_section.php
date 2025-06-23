<div class="row product-row mb-3 py-3">
    <!-- Input fields for adding a new product -->
    <div class="col-md-3">
        <div class="form-group">
            <label for="product_id">Product</label>
            <select class="form-control product_id">
                <option value="">Select Product</option>
                <?php foreach ($products as $product) : ?>
                    <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="quantity">Qnty <span class="product_unit"></span></label>
            <input type="number" min="1" class="form-control quantity" value="1">
            <input type="hidden" class="unit">
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control price" value="0" id="modal_price">
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="plus">With GST</label>
            <input type="number" class="form-control" value="0" id="modal_net_price">
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="discount_type">Disc Type</label>
            <select class="form-control discount_type">
                <option value="0">No</option>
                <option value="1">Flat</option>
                <option value="2">Percent</option>
            </select>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="discount_amount">Disc Amnt</label>
            <input type="text" class="form-control discount_amount" readonly>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="cgst">CGST</label>
            <input type="number" class="form-control cgst_rate" value="0">
        </div>
        <input type="hidden" class="cgst_amount">
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="sgst">SGST</label>
            <input type="number" class="form-control sgst_rate" value="0">
        </div>
        <input type="hidden" class="sgst_amount">
        <input type="hidden" class="gst_amount">
    </div>
    <div class="col-md-1">
        <div class="d-flex">
            <div class="form-group">
                <label for="total">Total</label>
                <input type="text" class="form-control total" readonly>
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <button type="button" class="mt-4 btn btn-secondary add-product">+</button>
    </div>
    <div class="col-md-12 product_extra_section d-none" style="display: none;">
        HSN Code: <span class="hsn_code"></span><small class="highlight_text"></small>
        <input type="hidden" class="hsn_code_val">
    </div>
    <div class="col-md-12 product_extra_section mt-3" style="display: none;">
        <input type="text" class="form-control product_descriptions" placeholder="Write Product Details">
    </div>
    <div class="col-md-12 last_purchase_prices"></div>
</div>

<div class="no_stocks p-2 mb-3 border rounded-3" style="display: none;">
    <p class="no_stock_txt"></p>
</div>
<div class="product_details p-2 mb-3 border rounded-3" style="display: none;">
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