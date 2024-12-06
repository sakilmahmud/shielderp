<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Invoices</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/invoices'); ?>" class="btn btn-primary">All Invoices</a>
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
                            <?php if ($this->session->flashdata('error')) : ?>
                                <div class="alert alert-danger">
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                            <?php endif; ?>
                            <form action="<?php echo base_url('admin/invoices/create'); ?>" method="post" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="is_gst">IS GST</label>
                                            <select class="form-control" id="is_gst" name="is_gst">
                                                <option value="0" <?php echo ($is_gst_bill == 0) ? " SELECTED" : ""; ?>>Non-GST</option>
                                                <option value="1" <?php echo ($is_gst_bill == 1) ? " SELECTED" : ""; ?>>GST</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_date">Invoice Date</label>
                                            <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="customer_phone">Mobile</label>
                                            <input type="text" class="form-control" id="customer_phone" name="mobile">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="customer_name">Customer</label>
                                            <input type="text" class="form-control" id="customer_name" name="customer_name" value="Cash">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customer_address">Address</label>
                                            <input type="text" class="form-control" id="customer_address" name="address">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="customer_gst">GST No</label>
                                            <input type="text" class="form-control" id="customer_gst" name="gst">
                                        </div>
                                    </div>
                                    <input type="hidden" name="customer_id" class="customer_id" value="0">
                                </div>
                                <ul class="list-group" id="customer_suggestions" style="display: none; position: absolute; z-index: 1000; width: 400px;"></ul>
                                <hr>
                                <!-- <h4>Add Items</h4> -->
                                <div class="row product-row">
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
                                            <label for="quantity">Qnty</label>
                                            <input type="number" min="1" class="form-control quantity" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input type="text" class="form-control price" value="0">
                                        </div>
                                        <div class="text-sm net_price_section" style="display: none;">Net Price: <span class="net_price"></span></div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="discount_type">Disc Type</label>
                                            <select class="form-control discount_type">
                                                <option value="">No</option>
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
                                            <label for="gst_rate">GST Rate</label>
                                            <select class="form-control gst_rate">
                                                <option value="0">0%</option>
                                                <option value="12">12%</option>
                                                <option value="18">18%</option>
                                                <option value="28">28%</option>
                                            </select>
                                        </div>
                                        <input type="hidden" class="gst_amount">
                                    </div>
                                    <div class="col-md-2">
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
                                </div>

                                <div class="no_stocks p-2 mb-3 border rounded-3" style="display: none;">
                                    <p class="no_stock_txt"></p>
                                </div>
                                <div class="product_details p-2 mb-3 border rounded-3" style="display: none;">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex gap-3">
                                            <h5>In Stock <span class="in_stock"></span></h5>
                                            <input type="hidden" name="total_stocks" id="total_stocks">
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

                                <div id="product-rows">
                                    <h4>All Items</h4>
                                    <table class="table table-hover table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>GST</th>
                                                <th class="text-right">Total</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Rows will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>

                                <div class="total_amount_section">
                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="sub_total">Sub Total</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="sub_total" name="sub_total" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="total_discount">Total Discount</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="total_discount" name="total_discount" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="total_gst">Total GST Amount</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="total_gst" name="total_gst" readonly>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="round_off">Round Off</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" id="round_off" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="total_amount">Grand Total</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="total_amount" name="total_amount" readonly>
                                        </div>
                                    </div>

                                    <!-- Payment Section -->
                                    <div id="payment-section">
                                        <div class="payment-row">
                                            <div class="d-flex gap-3 justify-content-end">
                                                <div class="form-group">
                                                    <select class="form-control payment_mode" name="payment_mode[]">
                                                        <option value="">Payment Mode</option>
                                                        <?php if (!empty($paymentModes)) :
                                                            foreach ($paymentModes as $index => $paymentMode) :
                                                                echo '<option value="' . $index . '">' . $paymentMode . '</option>';
                                                            endforeach;
                                                        endif; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <input type="date" class="form-control payment_date" name="payment_date[]" value="<?php echo date("Y-m-d") ?>">
                                                </div>
                                                <div class="form-group">
                                                    <input type="number" class="form-control payment_amount" name="payment_amount[]" placeholder="Enter Amount">
                                                </div>
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-primary add-payment"><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-3">
                                    <div>Balance Amount: <span class="balance_amount"></span></div>

                                    <button type="submit" class="btn btn-primary">Create Invoice</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    let getLastestStocksUrl = "<?php echo base_url('admin/invoices/getLastestStocks'); ?>";
</script>
<script src="<?php echo base_url('assets/admin/dist/js/invoice.js') ?>"></script>