<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Add Purchase Entry</h2>
            <a href="<?php echo base_url('admin/purchase_entries'); ?>" class="btn btn-primary">All Purchases</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <?php if ($this->session->flashdata('message')) : ?>
                                <div class="alert alert-danger">
                                    <?php echo $this->session->flashdata('message'); ?>
                                </div>
                            <?php endif; ?>
                            <form autocomplete="off" action="<?php echo base_url('admin/purchase_entries/add'); ?>" method="post">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="is_gst">GST/Non-GST</label>
                                            <select class="form-control" id="is_gst" name="is_gst">
                                                <option value="1">GST</option>
                                                <option value="0">Non-GST</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="purchase_date">Purchase Date</label>
                                            <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="supplier_id">Supplier</label>
                                            <select class="form-control" id="supplier_id" name="supplier_id" required>
                                                <option value="">Choose a Supplier</option>
                                                <?php foreach ($suppliers as $supplier) : ?>
                                                    <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['supplier_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="invoice_no">Invoice Number</label>
                                            <input type="text" class="form-control" id="invoice_no" name="invoice_no" value="<?php echo set_value('invoice_no'); ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <h4>Products</h4>
                                <div class="row product-row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <div class="my-1 d-flex justify-content-between">
                                                <label for="product_id">Product</label>
                                                <a href="javascript:void(0)" class="text-sm add_product"><span class="badge bg-success">Add Product</span></a>
                                            </div>
                                            <select class="form-control product_id">
                                                <option value="">Choose a Product</option>
                                                <?php foreach ($products as $product) : ?>
                                                    <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group mb-3">
                                            <label for="qnt">Qnt</label>
                                            <input type="number" min="1" value="1" step="1" class="form-control qnt">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-3">
                                            <label for="purchase_price">Price</label>
                                            <input type="number" autocomplete="false" min="1" class="form-control purchase_price">
                                            <input type="hidden" class="single_net_price">
                                            <input type="hidden" class="single_sale_price">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group mb-3">
                                            <label for="discount_type">Disc Type</label>
                                            <select class="form-control discount_type">
                                                <option value="">No</option>
                                                <option value="1">Flat</option>
                                                <option value="2">Percent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group mb-3">
                                            <label for="discount">Discount</label>
                                            <input type="text" class="form-control discount" value="0" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="d-flex gap-3">
                                            <div class="form-group mb-3">
                                                <label for="gst_rate">GST (%)</label>
                                                <input type="text" class="form-control gst_rate" value="0">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="gst_amount">G Amount</label>
                                                <input type="text" class="form-control gst_amount" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="d-flex gap-3">
                                            <div class="form-group mb-3">
                                                <label for="final_price">Final Price</label>
                                                <input type="text" class="form-control final_price" readonly>
                                            </div>
                                            <div><button type="button" class="mt-4 btn btn-secondary add-product">+</button></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="product-rows">
                                    <h4 class="mt-3">All Items</h4>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Net Single Price</th>
                                                <th>Discount</th>
                                                <th>GST</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="total_amount_section">
                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group mb-3">
                                            <label for="sub_total">Sub Total</label>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="text" class="form-control" id="sub_total" name="sub_total" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group mb-3">
                                            <label for="total_discount">Total Discount</label>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="text" class="form-control" id="total_discount" name="total_discount" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group mb-3">
                                            <label for="total_gst">Total GST Amount</label>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="text" class="form-control" id="total_gst" name="total_gst" readonly>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group mb-3">
                                            <label for="round_off">Round Off</label>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="text" id="round_off" name="round_off" class="form-control" value="<?php echo isset($purchase_entry['round_off']) ? $purchase_entry['round_off'] : ''; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group mb-3">
                                            <label for="total_amount">Grand Total</label>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="text" class="form-control" id="total_amount" name="total_amount" readonly>
                                        </div>
                                    </div>
                                    <!-- Payment Section -->
                                    <div id="payment-section">
                                        <div class="payment-row">
                                            <div class="d-flex gap-3 justify-content-end">
                                                <div class="form-group mb-3 payment_method_section">
                                                    <select class="form-control" name="payment_mode[]">
                                                        <?php if (!empty($paymentModes)) :
                                                            foreach ($paymentModes as $index => $paymentMode) :
                                                                echo '<option value="' . $paymentMode['id'] . '">' . $paymentMode['title'] . '</option>';
                                                            endforeach;
                                                        endif; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <input type="date" class="form-control payment_date" name="payment_date[]" value="<?php echo date("Y-m-d") ?>">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <input type="number" class="form-control payment_amount" name="payment_amount[]" placeholder="Enter Amount">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <button type="button" class="btn btn-primary add-payment"><i class="bi bi-plus"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary add_purchase">Add Purchase Entry</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script src="<?php echo base_url('assets/admin/js/purchase_order.js') ?>"></script>