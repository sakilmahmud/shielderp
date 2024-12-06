<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?php echo $invoice['invoice_no']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .invoice-container {
            background-color: #fff;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #0073e6;
            padding-bottom: 20px;
        }

        .header .right_sec {
            text-align: right;
        }

        .header .left_sec {
            text-align: left;
        }

        .header h1 {
            font-size: 20px;
            color: #0073e6;
            margin: 0;
        }

        .header h2 {
            font-size: 17px;
            color: #333;
            margin: 0 0 5px 0;
        }

        .header p {
            margin: 5px 0;
            color: #555;
        }

        .bill-to h3 {
            font-size: 15px;
            color: #0073e6;
            margin: 0;
        }

        .bill-to h2 {
            font-size: 17px;
            color: #333;
            margin: 5px 0;
        }

        .table-section {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        .table-section table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-section th,
        .table-section td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 12px;
        }

        .table-section th {
            background-color: #0073e6;
            color: #fff;
        }

        .table-section tfoot td {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .tax_section {
            margin-top: 20px;
        }

        .tax_section table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .tax_section th,
        .tax_section td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }

        .amount-words {
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        }

        .total-section {
            margin-top: 20px;
            text-align: right;
        }

        .total-section h4,
        .total-section h3 {
            margin: 5px 0;
        }

        .total-section h3 {
            font-size: 18px;
            color: #0073e6;
        }

        .terms {
            margin-top: 20px;
            font-size: 12px;
            color: #555;
        }

        .terms h3 {
            color: #0073e6;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-weight: bold;
            color: #0073e6;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            <div class="invoice-details right_sec">
                <small>(Original Copy)</small>
                <h1>INVOICE <?php echo $invoice['invoice_no']; ?></h1>
                <p>Date: <?php echo date('l d F Y', strtotime($invoice['invoice_date'])); ?></p>
                <p>Due Date: <?php echo date('d-m-Y', strtotime($invoice['due_date'])); ?></p>
            </div>

            <div>
                <table>
                    <tr>
                        <td>
                            <div class="biller left_sec">
                                <h2><?php echo $biller['name']; ?></h2>
                                <p><?php echo $biller['address']; ?></p>
                                <p>Contact: <?php echo $biller['contact']; ?></p>
                                <p>Email: <?php echo $biller['email']; ?></p>
                                <p>Website: <?php echo $biller['website']; ?></p>
                                <p>GSTIN: <?php echo $biller['gstin']; ?></p>
                            </div>
                        </td>
                        <td align="left" style="text-align: right;">
                            <div class="bill-to">
                                <h3>Bill To :</h3>
                                <h2><?php echo $invoice['customer_name']; ?></h2>
                                <p><?php echo ($invoice['address'] != "") ? $invoice['address'] : ""; ?></p>
                                <p><?php echo ($invoice['mobile'] != "") ? "Contact: " . $invoice['mobile'] : ""; ?></p>
                                <p><?php echo ($invoice['gst'] != "") ? "GSTIN: " . $invoice['gst'] : ""; ?></p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

        </div>

        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>PARTICULARS</th>
                        <th>QTY</th>
                        <th>UNIT PRICE</th>
                        <th>GST</th>
                        <th>AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_qty = 0;
                    $total_amount = 0;
                    foreach ($invoice_details as $index => $detail) :
                        $total_qty += $detail['quantity'];
                        $total_amount += $detail['final_price'];
                    ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo $detail['product_name']; ?></td>
                            <td><?php echo $detail['quantity']; ?> PCS</td>
                            <td>₹<?php echo number_format($detail['price'], 2); ?></td>
                            <td><?php echo $detail['gst_rate']; ?>%</td>
                            <td>₹ <?php echo number_format(round($detail['final_price']), 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" align="left">
                            <strong>Amount (in words):</strong> <?php echo ucwords(numberTowords(round($total_amount))); ?>
                        </td>
                        <td>TOTAL</td>
                        <td>₹ <?php echo number_format(round($total_amount), 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="total-section right_sec">
            <h4>Sub Total ₹ <?php echo number_format($invoice['sub_total'], 2); ?></h4>
            <?php if ($invoice['is_gst'] != 0): ?>
                <h4>CGST ₹ <?php echo number_format(($invoice['total_gst']) / 2, 2); ?></h4>
                <h4>SGST ₹ <?php echo number_format(($invoice['total_gst']) / 2, 2); ?></h4>
            <?php endif; ?>

            <h4>Discount ₹ <?php echo number_format($invoice['total_discount'], 2); ?></h4>
            <h3>TOTAL AMOUNT ₹ <?php echo number_format(round($invoice['total_amount']), 2); ?></h3>
        </div>

        <div>
            <div class="terms left_sec">
                <h3>Terms / Declaration</h3>
                <p><small><?php echo $terms; ?></small></p>
                <p></p>
                <p><strong>Bank Details -</strong></p>
                <p>Bank Name: <?php echo $bank_details['bank_name']; ?></p>
                <p>Account No.: <?php echo $bank_details['account_no']; ?></p>
                <p>IFSC: <?php echo $bank_details['ifsc_code']; ?></p>
                <p>Branch & IFSC: <?php echo $bank_details['branch']; ?></p>
            </div>
            <div class="footer right_sec">
                <p>For <?php echo $biller['name']; ?></p>
            </div>
        </div>
    </div>
</body>

</html>