<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Create Invoices</h2>
            <a href="<?php echo base_url('admin/invoices'); ?>" class="btn btn-primary">All Invoices</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?php echo base_url('admin/invoices/create'); ?>" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-6 col-md-1">
                                <div class="form-group">
                                    <label for="is_gst">IS GST</label>
                                    <select class="form-control" id="is_gst" name="is_gst">
                                        <option value="0" <?php echo ($is_gst_bill == 0) ? " SELECTED" : ""; ?>>Non-GST</option>
                                        <option value="1" <?php echo ($is_gst_bill == 1) ? " SELECTED" : ""; ?>>GST</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-group">
                                    <label for="invoice_date">Invoice Date</label>
                                    <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-group">
                                    <label for="customer_phone">Mobile</label>
                                    <input type="text" class="form-control" id="customer_phone" name="mobile">
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="form-group">
                                    <label for="customer_name">Customer</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="Cash">
                                </div>
                                <ul class="list-group" id="customer_suggestions" style="display: none;"></ul>
                            </div>
                            <div class="col-12 col-md-3">
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
                        <!-- product section -->
                        <?php $this->load->view('admin/invoices/inc/product_section'); ?>

                        <!-- All added items -->
                        <?php $this->load->view('admin/invoices/inc/items'); ?>

                        <div class="total_amount_section" style="display: none;">
                            <!-- Amount Section -->
                            <?php $this->load->view('admin/invoices/inc/amount_section'); ?>

                            <!-- Payment Section -->
                            <div id="payment-section">
                                <div class="payment-row">
                                    <div class="d-flex gap-3 justify-content-end mb-3">
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
                                            <button type="button" class="btn btn-primary add-payment">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-3 final_btn_section" style="display: none !important;">
                            <div>Balance Amount: <span class="balance_amount"></span></div>

                            <button type="submit" class="btn btn-primary">Create Invoice</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    let productDetailsUrl = "<?php echo base_url('admin/products/product_details'); ?>";
    let getLastestStocksUrl = "<?php echo base_url('admin/invoices/getLastestStocks'); ?>";
    let latestSalePricesUrl = "<?php echo base_url('admin/invoices/latest-sale-prices'); ?>";
    let default_gst_rate = <?= $gst_rate; ?>;
</script>
<script src="<?php echo base_url('assets/admin/js/invoice.js') ?>"></script>