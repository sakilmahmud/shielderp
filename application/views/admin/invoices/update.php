<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Update Invoice : #<?= $invoice['invoice_no'] ?></h2>
            <div>
                <a href="<?php echo base_url('admin/invoices/view/' . $invoice['id']); ?>" class="btn btn-info">View Invoice</a>
                <a href="<?php echo base_url('admin/invoices'); ?>" class="btn btn-primary">All Invoices</a>
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
                                            <select class="form-control" id="is_gst" name="is_gst" disabled>
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
                                            <input type="text" class="form-control" id="customer_gst" name="gst" value="<?php echo $customer_details['gst_number']; ?>">
                                        </div>
                                    </div>
                                    <input type="hidden" name="customer_id" class="customer_id" value="<?php echo $invoice['customer_id']; ?>">
                                </div>
                                <ul class="list-group" id="customer_suggestions" style="display: none; position: absolute; z-index: 1000; width: 400px;"></ul>
                                <hr>
                                <!-- product section -->
                                <?php $this->load->view('admin/invoices/inc/product_section'); ?>

                                <!-- All added items -->
                                <?php $this->load->view('admin/invoices/inc/items'); ?>

                                <div class="total_amount_section">
                                    <!-- Amount Section -->
                                    <?php $this->load->view('admin/invoices/inc/amount_section'); ?>

                                    <!-- Payment Section -->
                                    <div id="payment-section">
                                        <div class="row">
                                            <div class="col-md-5 offset-md-7">
                                                <?php
                                                $paid_amount = 0;

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
                                                            /* echo "<pre>";
                                                            print_r($paymentModes);
                                                            die; */
                                                            foreach ($invoice_payments as $payment) :
                                                                $paid_amount += $payment['amount'];
                                                                //echo $payment['payment_method_id'];
                                                            ?>
                                                                <tr data-id="<?php echo $payment['id'] ?>">
                                                                    <td>₹<?php echo $payment['amount'] ?></td>
                                                                    <td><?php echo $payment['payment_mode_title'] ?? "-"; ?></td>
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

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel">Add Payment for Invoice #<span id="invoice_number_modal"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>Balance Amount: <span class="balance_amount">₹<?php echo number_format(($balance > 0) ? $balance : 0, 2); ?></span></div>
                <input type="hidden" id="balance_amount" value="<?php echo ($balance > 0) ? $balance : 0; ?>">
                <form id="addPaymentForm">
                    <div class="form-group payment_method_section">
                        <select class="form-control payment_method_id" id="payment_method" name="payment_method" required>
                            <?php if (!empty($paymentModes)) :
                                foreach ($paymentModes as $index => $paymentMode) :
                                    echo '<option value="' . $paymentMode['id'] . '">' . $paymentMode['title'] . '</option>';
                                endforeach;
                            endif; ?>
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
                    <button type="submit" class="btn btn-primary mt-3" id="add_payment_btn">Add Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let productDetailsUrl = "<?php echo base_url('admin/products/product_details'); ?>";
    let getLastestStocksUrl = "<?php echo base_url('admin/invoices/getLastestStocks'); ?>";
    let latestSalePricesUrl = "<?php echo base_url('admin/invoices/latest-sale-prices'); ?>";
    let default_gst_rate = <?= $gst_rate; ?>;
</script>
<script>
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
<script src="<?php echo base_url('assets/admin/js/invoice.js') ?>"></script>