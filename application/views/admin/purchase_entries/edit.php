<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Edit Purchase Entry</h2>
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
                            <form autocomplete="off" action="<?php echo base_url('admin/purchase_entries/edit/' . $purchase_entry['id']); ?>" method="post">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="is_gst">GST/Non-GST</label>
                                            <select class="form-control" id="is_gst" name="is_gst" readonly>
                                                <option value="1" <?php echo $purchase_entry['is_gst'] == 1 ? 'selected' : ''; ?>>GST</option>
                                                <option value="0" <?php echo $purchase_entry['is_gst'] == 0 ? 'selected' : ''; ?>>Non-GST</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="purchase_date">Purchase Date</label>
                                            <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?php echo $purchase_entry['purchase_date']; ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="supplier_id">Supplier</label>
                                            <select class="form-control" id="supplier_id" name="supplier_id" readonly>
                                                <option value="">Choose a Supplier</option>
                                                <?php foreach ($suppliers as $supplier) : ?>
                                                    <option value="<?php echo $supplier['id']; ?>" <?php echo $supplier['id'] == $purchase_entry['supplier_id'] ? 'selected' : ''; ?>><?php echo $supplier['supplier_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label for="invoice_no">Invoice Number</label>
                                            <input type="text" class="form-control" id="invoice_no" name="invoice_no" value="<?php echo $purchase_entry['invoice_no']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <h4>Products</h4>
                                <div class="row product-row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <div class="mt-1 d-flex justify-content-between">
                                                <label for="product_id">Product</label>
                                                <a href="javascript:void(0)" class="text-sm add_product">Add Product</a>
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
                                    <div class="col-md-1">
                                        <div class="form-group mb-3">
                                            <label for="gst_rate">GST Rate</label>
                                            <select class="form-control gst_rate">
                                                <option value="0">0%</option>
                                                <option value="12">12%</option>
                                                <option value="18" selected>18%</option>
                                                <option value="28">28%</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group mb-3">
                                            <label for="gst_amount">GST Amount</label>
                                            <input type="text" class="form-control gst_amount" readonly>
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
                                    <table class="table table-hover table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Net Single Price</th>
                                                <th>Discount</th>
                                                <th>GST</th>
                                                <th>Total</th>
                                                <th width="10%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($purchase_order_products as $product) : ?>
                                                <tr data-product-id="<?php echo $product['product_id']; ?>">
                                                    <td><?php echo $product['product_name']; ?> x <b><?php echo $product['qnt']; ?></b></td>
                                                    <td>₹<?php echo $product['purchase_price']; ?></td>
                                                    <td>₹<?php echo $product['single_net_price']; ?></td>
                                                    <td><?php echo ($product['discount_type'] === 1) ? "₹" . $product['discount'] : $product['discount'] . "%"; ?></td>
                                                    <td>₹<?php echo $product['gst_amount']; ?> (<?php echo $product['gst_rate']; ?>%)</td>
                                                    <td>₹<?php echo $product['final_price']; ?></td>
                                                    <td class="text-center"><button type="button" class="btn btn-info btn-sm edit-item me-2"><i class="bi bi-pencil-square"></i></button><button type="button" class="btn btn-danger btn-sm remove-item"><i class="bi bi-x-circle"></i></button>
                                                    </td>
                                                    <input type="hidden" name="product_id[]" value="<?php echo $product['product_id']; ?>">
                                                    <input type="hidden" name='qnt[]' value="<?php echo $product['qnt']; ?>">
                                                    <input type="hidden" name="purchase_price[]" value="<?php echo $product['purchase_price']; ?>">
                                                    <input type="hidden" name="discount_type[]" value="<?php echo $product['discount_type']; ?>">
                                                    <input type="hidden" name="discount[]" value="<?php echo $product['discount']; ?>">
                                                    <input type="hidden" name="gst_rate[]" value="<?php echo $product['gst_rate']; ?>">
                                                    <input type="hidden" name="gst_amount[]" value="<?php echo $product['gst_amount']; ?>">
                                                    <input type="hidden" name="final_price[]" value="<?php echo $product['final_price']; ?>">
                                                    <input type="hidden" name="single_net_price[]" value="<?php echo $product['single_net_price']; ?>">
                                                    <input type="hidden" name="sale_price[]" value="<?php echo $product['single_net_price']; ?>">
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="total_amount_section">
                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group mb-3">
                                            <label for="sub_total">Sub Total</label>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="text" class="form-control" id="sub_total" name="sub_total" value="<?php echo $purchase_entry['sub_total']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group mb-3">
                                            <label for="total_discount">Total Discount</label>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="text" class="form-control" id="total_discount" name="total_discount" value="<?php echo $purchase_entry['total_discount']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group mb-3">
                                            <label for="total_gst">Total GST Amount</label>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="text" class="form-control" id="total_gst" name="total_gst" value="<?php echo $purchase_entry['total_gst']; ?>" readonly>
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
                                            <input type="text" class="form-control" id="total_amount" name="total_amount" value="<?php echo $purchase_entry['total_amount']; ?>" readonly>
                                        </div>
                                    </div>

                                    <!-- Payment Section -->
                                    <div id="payment-section">
                                        <div class="row">
                                            <div class="col-md-5 offset-md-7">

                                                <table class="payment_table table table-hover table-bordered table-sm <?= empty($invoice_payments) ? 'd-none' : '' ?>">
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
                                                        $paid_amount = 0;

                                                        if (!empty($invoice_payments)) :

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
                                                        <?php endforeach;
                                                        endif;
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <?php
                                                $balance = 0;
                                                //echo $paid_amount;
                                                $display  = 'hide';
                                                if ($purchase_entry['total_amount'] > $paid_amount) {
                                                    $balance = $purchase_entry['total_amount'] - $paid_amount;
                                                    $display  = 'show';
                                                }
                                                echo '<div class="my-5 text-right add_update_payment_btn" style="display:' . $display . '"><button type="button" class="btn btn-success btn-sm add-payment">Add Payment</button> (or press <b>F2</b> key)</div>';

                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary edit_purchase">Update Purchase Entry</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    <div class="form-group mb-3">
                        <div class="mt-1 d-flex justify-content-between">
                            <label for="category_id">Category</label>
                            <a href="javascript:void(0)" class="text-sm add_category">Add Category</a>
                        </div>
                        <select class="form-control category_id" name="category_id">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category) { ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <div class="mt-1 d-flex justify-content-between">
                            <label for="brand_id">Brand</label>
                            <a href="javascript:void(0)" class="text-sm add_brand">Add Brand</a>
                        </div>
                        <select class="form-control brand_id" id="brand_id" name="brand_id">
                            <option value="">Select Brand</option>
                            <?php foreach ($brands as $brand) { ?>
                                <option value="<?php echo $brand['id']; ?>"><?php echo $brand['brand_name']; ?></option>
                            <?php } ?>
                        </select>
                        <?php echo form_error('brand_id'); ?>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group mb-3">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="form-group mb-3">
                        <label for="category_name">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" role="dialog" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBrandModalLabel">Add Brand</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addBrandForm">
                    <div class="form-group mb-3">
                        <label for="brand_name">Brand Name</label>
                        <input type="text" class="form-control" id="brand_name" name="brand_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Brand</button>
                </form>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>Balance Amount: <span class="balance_amount">₹<?php echo number_format(($balance > 0) ? $balance : 0, 2); ?></span></div>
                <input type="hidden" id="balance_amount" value="<?php echo ($balance > 0) ? $balance : 0; ?>">
                <form id="addPaymentForm">
                    <div class="form-group mb-3 payment_method_section">
                        <select class="form-control payment_method_id" id="payment_method" name="payment_method" required>
                            <?php if (!empty($paymentModes)) :
                                foreach ($paymentModes as $index => $paymentMode) :
                                    echo '<option value="' . $paymentMode['id'] . '">' . $paymentMode['title'] . '</option>';
                                endforeach;
                            endif; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="payment_amount">Amount</label>
                        <input type="number" class="form-control" min="1" id="payment_amount" name="payment_amount" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="payment_date">Date</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="payment_note">Payment Note</label>
                        <textarea class="form-control" id="payment_note" name="payment_note" rows="2"></textarea>
                    </div>
                    <input type="hidden" id="payment_id" name="payment_id">
                    <input type="hidden" id="purchase_order_id" name="purchase_order_id" value="<?= $purchase_entry['id'] ?>">
                    <button type="submit" class="btn btn-primary mt-3" id="add_payment_btn">Add Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $("body").on("click", ".add-payment", function() {
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
            $('#invoice_number_modal').text('<?= $purchase_entry['invoice_no'] ?>'); // Set invoice number
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
            let url = '<?= base_url("admin/purchases/addPayment") ?>';
            let isEdit = false;
            // If editing a payment, set the correct URL and append payment ID
            if ($('#add_payment_btn').text() === 'Update Payment') {
                let isEdit = true;
                url = '<?= base_url("admin/purchases/updatePayment") ?>';
            }

            // AJAX request to add payment
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                success: function(response) {
                    if (response.success) {
                        $('.payment_table').removeClass('d-none');
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
                url: '<?= base_url("admin/purchases/getPaymentDetails") ?>/' + paymentId,
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
            let invoiceId = '<?php echo $purchase_entry['invoice_no']; ?>';
            // Confirm delete
            if (confirm('Are you sure you want to delete this payment?')) {
                // AJAX request to delete payment
                $.ajax({
                    url: '<?= base_url("admin/purchases/deletePayment") ?>', // URL without the ID in the URL
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
<script src="<?php echo base_url('assets/admin/js/purchase_order.js') ?>"></script>