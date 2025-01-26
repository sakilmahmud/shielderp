<style>
    .product-row {
        background: #efefef;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

    li.list-group-item.customer-suggestion {
        padding: 5px 0 5px 10px;
        background: #ddd;
        cursor: pointer;
    }

    ul#customer_suggestions {
        position: absolute;
        z-index: 1000;
        width: 250px !important;
        left: 6px;
        top: 70px;
    }
</style>
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
                                        <ul class="list-group" id="customer_suggestions" style="display: none;"></ul>
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

                                <hr>
                                <!-- <h4>Add Items</h4> -->
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
                                            <label for="quantity">Qnty</label>
                                            <input type="number" min="1" class="form-control quantity" value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input type="text" class="form-control price" value="0">
                                            <div class="text-sm net_price_section" style="display: none;">Net Price: <span class="net_price"></span> <a href="javascript:void(0);"
                                                    class="quick-edit"> <i class="fa fa-edit"></i></a></div>
                                        </div>
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
                                    <div class="col-md-12 product_descriptions_section">
                                        <input type="text" class="form-control product_descriptions" placeholder="Write Product Details">
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

                                <!-- All added items -->
                                <?php $this->load->view('admin/invoices/inc/items'); ?>

                                <div class="total_amount_section">
                                    <!-- Amount Section -->
                                    <?php $this->load->view('admin/invoices/inc/amount_section'); ?>

                                    <!-- Payment Section -->
                                    <div id="payment-section">
                                        <div class="payment-row">
                                            <div class="d-flex gap-3 justify-content-end">
                                                <div class="form-group payment_method_section">
                                                    <select class="form-control" name="payment_mode[]">
                                                        <?php if (!empty($paymentModes)) :
                                                            foreach ($paymentModes as $index => $paymentMode) :
                                                                echo '<option value="' . $paymentMode['id'] . '">' . $paymentMode['title'] . '</option>';
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
<!-- Quick Edit Modal -->
<div class="modal fade" id="quickEditModal" tabindex="-1" aria-labelledby="quickEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickEditModalLabel">Quick Edit</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="modal_price">Price</label>
                        <input type="text" class="form-control" id="modal_price">
                    </div>
                    <div class="form-group">
                        <label for="modal_net_price">Net Price</label>
                        <input type="text" class="form-control" id="modal_net_price">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save-quick-edit">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    let getLastestStocksUrl = "<?php echo base_url('admin/invoices/getLastestStocks'); ?>";

    $(document).ready(function() {
        // Variables to store the current target element
        let currentPriceInput = null;
        let currentNetPriceInput = null;

        // Open the modal on quick-edit click
        $(document).on("click", ".quick-edit", function() {
            // Get the target price and net price inputs
            currentPriceInput = $(this).closest(".form-group").find(".price");
            currentNetPriceInput = $(this).closest(".form-group").find(".net_price_section .net_price");

            // Fill modal fields with current values
            $("#modal_price").val(currentPriceInput.val());
            $("#modal_net_price").val(currentNetPriceInput.text());

            // Show the modal
            $("#quickEditModal").modal("show");
        });

        // Save changes from modal
        $(".save-quick-edit").on("click", function() {
            // Update the values in the form fields
            const updatedPrice = parseFloat($("#modal_price").val()) || 0;
            const updatedNetPrice = parseFloat($("#modal_net_price").val()) || 0;

            currentPriceInput.val(updatedPrice.toFixed(2));
            currentNetPriceInput.text(updatedNetPrice.toFixed(2)).closest(".net_price_section").show();

            // Trigger input event to recalculate on the fields
            currentPriceInput.trigger("input");
            currentNetPriceInput.trigger("input");

            // Hide the modal
            $("#quickEditModal").modal("hide");
        });

        // Handle price input
        $(document).on("input", "#modal_price", function() {
            const price = parseFloat($(this).val()) || 0;
            const gstRate = parseFloat($(".gst_rate").val()) || 0;
            const netPrice = price + (price * gstRate) / 100;
            console.log('GST: ' + gstRate);
            // Update the price field
            $("#modal_net_price").val(netPrice.toFixed(2));
        });

        // Handle net price input
        $(document).on("input", "#modal_net_price", function() {
            const netPrice = parseFloat($(this).val()) || 0;
            const gstRate = parseFloat($(".gst_rate").val()) || 0;
            const price = netPrice / (1 + gstRate / 100);

            // Update the price field
            $("#modal_price").val(price.toFixed(2));
        });
    });
</script>
<script src="<?php echo base_url('assets/admin/dist/js/invoice.js') ?>"></script>