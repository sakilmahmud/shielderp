<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Invoice #<?php echo $invoice['invoice_no']; ?></h2>
            <div class="">
                <a href="<?php echo base_url('admin/invoices'); ?>" class="btn btn-secondary">Back to Invoices</a>
                <a href="<?php echo base_url('admin/invoices/edit/' . $invoice['id']); ?>" class="btn btn-info">Edit Invoice</a>
                <a href="<?php echo base_url('admin/invoices/print/' . $invoice['id']); ?>" target="_blank" class="btn btn-primary">Print Invoice</a>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4>Invoice Details</h4>
                            <table class="table">
                                <tr>
                                    <th>Invoice Number:</th>
                                    <td><?php echo $invoice['invoice_no']; ?></td>
                                </tr>
                                <tr>
                                    <th>Customer Name:</th>
                                    <td>
                                        <?php echo $invoice['customer_name'] . (!empty($invoice['mobile']) ? " ({$invoice['mobile']})" : ""); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Invoice Date:</th>
                                    <td><?php echo $invoice['invoice_date']; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Amount:</th>
                                    <td>₹<?php echo number_format(round($invoice['total_amount']), 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Payment Status:</th>
                                    <td>
                                        <?php if ($invoice['payment_status'] == '1') : ?>
                                            <span class="badge badge-success">Paid</span>
                                        <?php elseif ($invoice['payment_status'] == '0') : ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php elseif ($invoice['payment_status'] == '2') : ?>
                                            <span class="badge badge-info">Partial</span>
                                        <?php elseif ($invoice['payment_status'] == '3') : ?>
                                            <span class="badge badge-danger">Return</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                            <h4>Product Details</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>HSN Code</th>
                                        <th>Discount</th>
                                        <th>GST</th>
                                        <th>Unit Price</th>
                                        <th>Final Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoice_details as $detail) : ?>
                                        <tr>
                                            <td>
                                                <b><?php echo $detail['product_name']; ?></b><br>
                                                <p><?php echo $detail['product_descriptions']; ?></p>
                                            </td>
                                            <td><?php echo $detail['quantity']; ?></td>
                                            <td>₹<?php echo $detail['price']; ?></td>
                                            <td><?php echo $detail['hsn_code']; ?></td>
                                            <td><?php echo $detail['discount']; ?></td>
                                            <td><?php echo $detail['gst_amount']; ?></td>
                                            <td>
                                                <b>₹<?php echo number_format(($detail['price'] + $detail['gst_amount']), 2); ?></b>
                                            </td>
                                            <td>₹<?php echo number_format(round($detail['final_price']), 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <hr>
                            <h4>Transaction History</h4>
                            <table id="commonTable" class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Amount</th>
                                        <th>Transaction Type</th>
                                        <th>Payment Method</th>
                                        <th>Description</th>
                                        <th>Transaction Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($transactions)) : ?>
                                        <?php foreach ($transactions as $transaction) :
                                            if ($transaction['amount'] <= 0) continue; ?>
                                            <tr>
                                                <td>₹<?php echo number_format($transaction['amount'], 2); ?></td>
                                                <td>
                                                    <?php
                                                    if ($transaction['trans_type'] == 1) {
                                                        echo "Credit";
                                                    } elseif ($transaction['trans_type'] == 2) {
                                                        echo "Debit";
                                                    } elseif ($transaction['trans_type'] == 3) {
                                                        echo "Refund";
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $transaction['title']; ?></td>
                                                <td><?php echo $transaction['descriptions']; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($transaction['trans_date'])); ?></td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('admin/payments/print_receipt/' . $transaction['id']) ?>" class="btn btn-sm btn-secondary" target="_blank">
                                                        🖨️ Print
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="5">No transactions found for this invoice.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>