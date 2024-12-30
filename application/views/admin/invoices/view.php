<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Invoice #<?php echo $invoice['invoice_no']; ?></h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/invoices'); ?>" class="btn btn-secondary">Back to Invoices</a>
                    <a href="<?php echo base_url('admin/invoices/print/' . $invoice['id']); ?>" target="_blank" class="btn btn-primary">Print Invoice</a>
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
                            <h4>Invoice Details</h4>
                            <table class="table">
                                <tr>
                                    <th>Invoice Number:</th>
                                    <td><?php echo $invoice['invoice_no']; ?></td>
                                </tr>
                                <tr>
                                    <th>Customer Name:</th>
                                    <td><?php echo $invoice['customer_name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Invoice Date:</th>
                                    <td><?php echo $invoice['invoice_date']; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Amount:</th>
                                    <td><?php echo $invoice['total_amount']; ?></td>
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
                                        <th>Discount</th>
                                        <th>GST</th>
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
                                            <td><?php echo $detail['price']; ?></td>
                                            <td><?php echo $detail['discount']; ?></td>
                                            <td><?php echo $detail['gst_amount']; ?></td>
                                            <td><?php echo $detail['final_price']; ?></td>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($transactions)) : ?>
                                        <?php foreach ($transactions as $transaction) : ?>
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