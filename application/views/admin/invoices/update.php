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
                    <h2>Update Invoice</h2>
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
                            <form action="<?php echo base_url('admin/invoices/edit/' . $invoice['id']); ?>" method="post" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="is_gst">IS GST</label>
                                            <select class="form-control" id="is_gst" name="is_gst">
                                                <option value="0" <?php echo ($invoice['is_gst'] == 0) ? "SELECTED" : ""; ?>>Non-GST</option>
                                                <option value="1" <?php echo ($invoice['is_gst'] == 1) ? "SELECTED" : ""; ?>>GST</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_date">Invoice Date</label>
                                            <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="<?php echo $invoice['invoice_date']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="customer_phone">Mobile</label>
                                            <input type="text" class="form-control" id="customer_phone" name="mobile" value="<?php echo $invoice['mobile']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="customer_name">Customer</label>
                                            <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo $invoice['customer_name']; ?>">
                                            <ul class="list-group" id="customer_suggestions" style="display: none;"></ul>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="customer_address">Address</label>
                                            <input type="text" class="form-control" id="customer_address" name="address" value="<?php echo $invoice['address']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="customer_gst">GST No</label>
                                            <input type="text" class="form-control" id="customer_gst" name="gst" value="<?php echo $invoice['gst']; ?>">
                                        </div>
                                    </div>
                                    <input type="hidden" name="customer_id" class="customer_id" value="<?php echo $invoice['customer_id']; ?>">
                                </div>
                                <ul class="list-group" id="customer_suggestions" style="display: none; position: absolute; z-index: 1000; width: 400px;"></ul>
                                <hr>
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
                                            <div class="text-sm net_price_section" style="display: none;">Net Price: <span class="net_price"></span> <a href="javascript:void(0);" class="quick-edit"> <i class="fa fa-edit"></i></a></div>
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
                                                <th>Total</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($invoice_details as $product) : ?>
                                                <tr>
                                                    <td><?php echo $product['product_name']; ?> x <b><?php echo $product['quantity']; ?></b></td>
                                                    <td>₹<?php echo $product['price']; ?></td>
                                                    <td><?php echo ($product['discount_type'] == 1) ? "₹" . $product['discount'] : $product['discount'] . "%"; ?></td>
                                                    <td>₹<?php echo $product['gst_amount']; ?> (<?php echo $product['gst_rate']; ?>%)</td>
                                                    <td>₹<?php echo $product['final_price']; ?></td>
                                                    <td width="5%" class="text-center"><button type="button" class="btn btn-danger btn-sm remove-item">X</button></td>
                                                    <input type="hidden" name="product_id[]" value="<?php echo $product['product_id']; ?>">
                                                    <input type="hidden" name='qnt[]' value="<?php echo $product['quantity']; ?>">
                                                    <input type="hidden" name="purchase_price[]" value="<?php echo $product['price']; ?>">
                                                    <input type="hidden" name="discount_type[]" value="<?php echo $product['discount_type']; ?>">
                                                    <input type="hidden" name="discount[]" value="<?php echo $product['discount']; ?>">
                                                    <input type="hidden" name="gst_rate[]" value="<?php echo $product['gst_rate']; ?>">
                                                    <input type="hidden" name="gst_amount[]" value="<?php echo $product['gst_amount']; ?>">
                                                    <input type="hidden" name="final_price[]" value="<?php echo $product['final_price']; ?>">
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <hr>

                                <div class="total_amount_section">
                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="sub_total">Sub Total</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="sub_total" name="sub_total" value="<?php echo $invoice['sub_total']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="total_discount">Total Discount</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="total_discount" name="total_discount" value="<?php echo $invoice['total_discount']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="total_gst">Total GST Amount</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="total_gst" name="total_gst" value="<?php echo $invoice['total_gst']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="total_amount">Grand Total</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="total_amount" name="total_amount" value="<?php echo $invoice['total_amount']; ?>" readonly>
                                        </div>
                                    </div>

                                    <!-- Payment Section -->
                                    <div id="payment-section">
                                        <div class="row">
                                            <div class="col-md-5 offset-md-7">
                                                <?php
                                                $paid_amount = 0;
                                                $paymentModes = getPaymentModes();
                                                //print_r($paymentModes);

                                                if (!empty($invoice_payments)) :
                                                ?>
                                                    <table class="payment_table table table-hover table-bordered table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Amount</th>
                                                                <th>Mode</th>
                                                                <th>Date</th>
                                                                <th width="30%"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php

                                                            foreach ($invoice_payments as $payment) :
                                                                $paid_amount += $payment['amount'];
                                                                //echo $payment['payment_method_id'];
                                                            ?>
                                                                <tr data-id="<?php echo $payment['id'] ?>">
                                                                    <td>₹<?php echo $payment['amount'] ?></td>
                                                                    <td><?php echo $paymentModes[$payment['payment_method_id']] ?? "-"; ?></td>
                                                                    <td><?php echo $payment['trans_date'] ?></td>
                                                                    <td width="25%" class="text-center">
                                                                        <button type="button" class="btn btn-info btn-sm edit-payment">Edit</button>
                                                                        <button type="button" class="btn btn-danger btn-sm remove-payment">Delete</button>
                                                                    </td>
                                                                    <input type="hidden" class="payment_amount" value="<?php echo $payment['amount'] ?>">
                                                                </tr>
                                                            <?php endforeach;  ?>
                                                        </tbody>
                                                    </table>
                                                <?php endif;
                                                $balance = 0;
                                                //echo $paid_amount;
                                                $display  = 'hide';
                                                if ($invoice['total_amount'] > $paid_amount) {
                                                    $balance = $invoice['total_amount'] - $paid_amount;
                                                    $display  = 'show';
                                                }
                                                echo '<div class="my-5 text-right add_update_payment_btn" style="display:' . $display . '"><button type="button" class="btn btn-success btn-sm add-payment">Add Payment</button> (or press <b>F2</b> key)</div>';

                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-3">
                                    <div class="alert alert-success">Balance Amount: <span class="balance_amount">₹<?php echo number_format(($balance > 0) ? $balance : 0, 2); ?></span></div>

                                    <button type="submit" class="btn btn-primary">Update Invoice</button>
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

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel">Add Payment for Invoice #<span id="invoice_number_modal"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>Balance Amount: <span class="balance_amount">₹<?php echo number_format(($balance > 0) ? $balance : 0, 2); ?></span></div>
                <input type="hidden" id="balance_amount" value="<?php echo ($balance > 0) ? $balance : 0; ?>">
                <form id="addPaymentForm">
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <?php foreach ($paymentModes as $index => $method) : ?>
                                <option value="<?= $index ?>" <?= $index == 1 ? 'selected' : '' ?>><?= $method ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="payment_amount">Amount</label>
                        <input type="number" class="form-control" min="1" id="payment_amount" name="payment_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_date">Date</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_note">Payment Note</label>
                        <textarea class="form-control" id="payment_note" name="payment_note" rows="2"></textarea>
                    </div>
                    <input type="hidden" id="payment_id" name="payment_id">
                    <input type="hidden" id="invoice_id" name="invoice_id" value="<?= $invoice['id'] ?>">
                    <button type="submit" class="btn btn-primary" id="add_payment_btn" disabled>Add Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
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
<script>
    let getLastestStocksUrl = "<?php echo base_url('admin/invoices/getLastestStocks'); ?>";
    $(document).ready(function() {

        $('.add-payment').on('click', function() {
            openAddPaymentModal();
        });

        // Detect "F2" key press to open the modal
        $(document).on('keydown', function(e) {
            if (e.key === 'F2') {
                openAddPaymentModal();
            }
        });

        // Function to open the modal and set the invoice number
        function openAddPaymentModal() {
            $('#payment_id').val(''); // Add a hidden field for payment ID

            // Change button text to "Update Payment"
            $('#add_payment_btn').text('Add Payment');

            $('#addPaymentModal').modal('show');
            $('#invoice_number_modal').text('<?= $invoice["invoice_no"] ?>'); // Set invoice number
        }

        // Enable submit button only when amount and method are provided
        $('#payment_amount, #payment_method').on('input change', function() {
            let balance_amount_val = $('#balance_amount').val();
            //alert(balance_amount_val);
            let amount = $('#payment_amount').val();
            let method = $('#payment_method').val();
            console.log('balance_amount_val', balance_amount_val);
            console.log(amount);

            //if ((amount && method) && parseFloat(amount) <= parseFloat(balance_amount_val)) {
            if ((amount && method)) {
                $('#add_payment_btn').removeAttr('disabled');
            } else {
                $('#add_payment_btn').attr('disabled', true);
            }
        });

        // Handle the form submission
        $('#addPaymentForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let url = '<?= base_url("admin/invoices/addPayment") ?>';
            let isEdit = false;
            // If editing a payment, set the correct URL and append payment ID
            if ($('#add_payment_btn').text() === 'Update Payment') {
                let isEdit = true;
                url = '<?= base_url("admin/invoices/updatePayment") ?>';
            }

            // AJAX request to add payment
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        // Update payment log and balance
                        updatePaymentLog(response.data, response.action);
                        updateBalance(response.balance);

                        if (parseFloat(response.balance) <= 0) {
                            $('.add_update_payment_btn').hide();
                        } else {
                            $('.add_update_payment_btn').show();
                        }
                        // Close modal and reset form
                        $('#addPaymentModal').modal('hide');
                        $('#addPaymentForm')[0].reset();
                    } else {
                        alert('Failed to add payment. Please try again.');
                    }
                }
            });
        });

        // Edit Payment Functionality
        $(document).on('click', '.edit-payment', function() {
            let paymentId = $(this).closest('tr').data('id'); // Assuming the row contains a data-id attribute with payment ID

            // Fetch payment details using AJAX
            $.ajax({
                url: '<?= base_url("admin/invoices/getPaymentDetails") ?>/' + paymentId,
                type: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    if (response.success) {
                        // Populate modal fields with fetched data
                        $('#payment_amount').val(response.data.amount);
                        $('#payment_method').val(response.data.payment_method_id);
                        $('#payment_date').val(response.data.trans_date);
                        $('#payment_note').val(response.data.descriptions);
                        $('#payment_id').val(paymentId); // Add a hidden field for payment ID

                        // Change button text to "Update Payment"
                        $('#add_payment_btn').text('Update Payment');

                        // Show modal for editing
                        $('#addPaymentModal').modal('show');
                    } else {
                        alert('Failed to fetch payment details.');
                    }
                }
            });
        });

        // Delete Payment Functionality
        $(document).on('click', '.remove-payment', function() {
            let $row = $(this).closest('tr'); // Store the row element
            let paymentId = $row.data('id');
            let invoiceId = '<?php echo $invoice['id']; ?>';
            // Confirm delete
            if (confirm('Are you sure you want to delete this payment?')) {
                // AJAX request to delete payment
                $.ajax({
                    url: '<?= base_url("admin/invoices/deletePayment") ?>', // URL without the ID in the URL
                    type: 'POST',
                    data: {
                        invoice_id: invoiceId,
                        payment_id: paymentId
                    }, // Send the payment ID in the request data
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.success) {
                            // Remove the row and update balance
                            $row.remove(); // Remove the row after successful deletion
                            updateBalance(response.balance);

                            if (parseFloat(response.balance) <= 0) {
                                $('.add_update_payment_btn').hide();
                            } else {
                                $('.add_update_payment_btn').show();
                            }
                        } else {
                            alert('Failed to delete payment. Please try again.');
                        }
                    },
                    error: function() {
                        alert('An error occurred while trying to delete the payment.');
                    }
                });
            }
        });

    });


    // Function to update payment log dynamically
    function updatePaymentLog(data, action) {
        if (action === 'edit') {
            // Update the existing row for edited payment
            let row = $('tr[data-id="' + data.id + '"]');
            row.find('td:eq(0)').text('₹' + data.amount);
            row.find('td:eq(1)').text(data.payment_method);
            row.find('td:eq(2)').text(data.trans_date);

            // Update the hidden input value for payment amount
            row.find('.payment_amount').val(data.amount);
        } else {
            // Add a new payment row if adding payment
            let newRow = `
            <tr data-id="${data.id}">
                <td>₹${data.amount}</td>
                <td>${data.payment_method}</td>
                <td>${data.trans_date}</td>
                <td width="25%" class="text-center">
                    <button type="button" class="btn btn-info btn-sm edit-payment">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm remove-payment">Delete</button>
                </td>
                <input type="hidden" class="payment_amount" value="${data.amount}">
            </tr>`;
            $('table.payment_table tbody').append(newRow);
        }
    }

    // Function to update balance
    function updateBalance(balance) {
        $('.balance_amount').text('₹' + balance.toFixed(2));
    }
</script>
<script src="<?php echo base_url('assets/admin/dist/js/invoice.js') ?>"></script>