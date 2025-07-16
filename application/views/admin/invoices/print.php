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
            background-color: #fff;
        }

        .invoice-container {
            padding: 5px;
            margin: 0 auto;
        }

        .header {
            padding-bottom: 0px;
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

        }

        .grand_total_sec h5 {
            margin: 15px 0;
        }

        .table-section th {
            border: 1px solid #ddd;
            padding: 7px;
            text-align: center;
            font-size: 12px;
        }

        .table-section td {
            border: 1px solid #ddd;
            padding: 3px;
            text-align: left;
            font-size: 12px;
        }

        .table-section td p {
            padding: 2px;
            margin: 2px 0;
        }

        .hsn_gst_sec table {
            margin: 15px 0;
            border: 1px solid #ddd;
        }

        .hsn_gst_sec th,
        .hsn_gst_sec td {
            padding: 3px;
            text-align: center;
            font-size: 11px;
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
            margin-top: 5px;
            font-size: 12px;
            color: #555;
            bottom: 10;
            position: absolute;
        }

        .terms h3 {
            color: #0073e6;
            margin-bottom: 10px;
        }

        .terms p {
            padding: 0px;
        }

        .footer {
            margin-top: 10px;
            text-align: right;
            bottom: 10;
            position: absolute;
            font-weight: bold;
            color: #0073e6;
        }

        /** 22.12.2024*/
        .top_part {
            border-bottom: 2px solid #0073e6;
            margin-bottom: 20px;
        }

        .hsc_n_total {
            min-height: 200px;
        }

        .top_part,
        .biller_seller,
        .hsc_n_total,
        .footer_sec {
            clear: both;
        }

        .hsc_n_total .left_sec {
            float: left;
            width: 60%;
            text-align: left;
        }

        .hsc_n_total .right_sec {
            float: right;
            width: 40%;
            text-align: right;
        }

        .left_sec {
            float: left;
            width: 50%;
            text-align: left;
        }

        .right_sec {
            float: right;
            width: 50%;
            text-align: right;
        }

        img {
            max-width: 100%;
            /* Ensures image is responsive */
        }

        /* Clear both floated elements */
        .top_part::after,
        .biller_seller::after,
        .hsc_n_total::after,
        .footer_sec::after {
            content: "";
            display: block;
            clear: both;
        }

        .grand_total_sec {
            line-height: .8;
        }

        /** end 22.12.2024*/
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            <div class="top_part">
                <div class="left_sec">
                    <img src="<?php echo $biller['logo']; ?>" alt="<?php echo $biller['name']; ?>" width="200">
                </div>
                <div class="invoice-details right_sec">
                    <small>(Original Copy)</small>
                    <h1>INVOICE <?php echo $invoice['invoice_no']; ?></h1>
                    <p>Date: <?php echo date('l d F Y', strtotime($invoice['invoice_date'])); ?></p>
                    <p>Due Date: <?php echo date('d-m-Y', strtotime($invoice['due_date'])); ?></p>
                </div>
            </div>
            <div class="biller_seller">
                <div class="left_sec">
                    <h2><?php echo $biller['name']; ?></h2>

                    <?php if (!empty($biller['address'])): ?>
                        <p><?php echo $biller['address']; ?></p>
                    <?php endif; ?>

                    <?php if (!empty($biller['contact'])): ?>
                        <p>Contact: <?php echo $biller['contact']; ?></p>
                    <?php endif; ?>

                    <?php if (!empty($biller['email'])): ?>
                        <p>Email: <?php echo $biller['email']; ?></p>
                    <?php endif; ?>

                    <?php if (!empty($biller['website'])): ?>
                        <p>Website: <?php echo $biller['website']; ?></p>
                    <?php endif; ?>

                    <?php if (!empty($biller['gstin'])): ?>
                        <p>GSTIN: <?php echo $biller['gstin']; ?></p>
                    <?php endif; ?>

                </div>
                <div class="right_sec">
                    <h3 style="margin-top: -5px;">Bill To</h3>
                    <h2 style="margin-top: -5px;"><?php echo $invoice['customer_name']; ?></h2>
                    <p><?php echo ($invoice['address'] != "") ? $invoice['address'] : ""; ?></p>
                    <p><?php echo ($invoice['mobile'] != "") ? "Contact: " . $invoice['mobile'] : ""; ?></p>
                    <p><?php echo ($customer['gst_number'] != "") ? "GSTIN: " . $customer['gst_number'] : ""; ?></p>
                </div>
            </div>
        </div>

        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>PARTICULARS</th>
                        <th>HSN/SAC</th>
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
                            <td>
                                <b><?php echo $detail['product_name']; ?></b><br>
                                <p><?php echo $detail['product_descriptions']; ?></p>
                            </td>
                            <td><?php echo isset($detail['hsn_code']) ? $detail['hsn_code'] : '8471'; ?></td>
                            <td><?php echo $detail['quantity']; ?> PCS</td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><?php echo number_format($detail['price'], 2); ?></td>
                            <td><?php echo $detail['cgst'] + $detail['sgst']; ?>%</td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><?php echo number_format(round($detail['final_price']), 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" align="left">
                            <strong>Amount (in words):</strong> <?php echo ucwords(numberTowords(round($total_amount))); ?>
                        </td>
                        <td>TOTAL</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><?php echo number_format(round($total_amount), 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="hsc_n_total">
            <div class="left_sec">
                <?php if ($invoice['is_gst'] != 0): ?>
                    <div class="hsn_gst_sec">
                        <table width="300">
                            <tr>
                                <th>HSN/SAC</th>
                                <th>GST%</th>
                                <th>Amount</th>
                                <th>CGST</th>
                                <th>SGST</th>
                            </tr>
                            <?php
                            // Group the invoice details by HSN code
                            $groupedDetails = [];
                            foreach ($invoice_details as $detail) {
                                $hsn_code = isset($detail['hsn_code']) ? $detail['hsn_code'] : '8471';
                                $cgst = $detail['cgst'];
                                $sgst = $detail['sgst'];

                                if (!isset($groupedDetails[$hsn_code])) {
                                    $groupedDetails[$hsn_code] = [
                                        'gst_rate' => 18,
                                        'amount' => 0,
                                        'cgst' => 0,
                                        'sgst' => 0
                                    ];
                                }

                                // Calculate GST amount
                                $cgst_price = ($detail['price'] * $cgst) / 100;
                                $sgst_price = ($detail['price'] * $sgst) / 100;

                                // Add to the grouped details
                                $groupedDetails[$hsn_code]['gst_rate'] = $cgst + $sgst;
                                $groupedDetails[$hsn_code]['amount'] += $detail['price'];
                                $groupedDetails[$hsn_code]['cgst'] += $cgst_price;
                                $groupedDetails[$hsn_code]['sgst'] += $sgst_price;
                            }

                            // Render the grouped details
                            foreach ($groupedDetails as $hsn_code => $data) :
                            ?>
                                <tr style="padding: 0;">
                                    <td style="padding: 0;"><?php echo $hsn_code; ?></td>
                                    <td style="padding: 0;"><?php echo $data['gst_rate']; ?>%</td>
                                    <td style="padding: 0;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><?php echo number_format($data['amount'], 2); ?></td>
                                    <td style="padding: 0;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><?php echo number_format($data['cgst'], 2); ?></td>
                                    <td style="padding: 0;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><?php echo number_format($data['sgst'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>

                <?php endif; ?>
            </div>
            <div class="grand_total_sec right_sec">
                <h5>Sub Total: <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><?php echo number_format($invoice['sub_total'], 2); ?></h5>
                <?php if ($invoice['is_gst'] != 0): ?>
                    <h5>GST: <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><?php echo number_format(($invoice['total_gst']), 2); ?></h5>
                <?php endif; ?>
                <h5>Discount <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><?php echo number_format($invoice['total_discount'], 2); ?></h5>
                <?php if ($invoice['round_off'] != 0): ?>
                    <h5>Round Off: <?php echo $invoice['round_off']; ?></h5>
                <?php endif; ?>
                <h4>TOTAL AMOUNT <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><?php echo number_format(round($invoice['total_amount']), 2); ?></h4>
                <?php if (!empty($transactions)) : ?>
                    <?php
                    $total_paid = 0;
                    foreach ($transactions as $transaction) :
                        $total_paid += $transaction['amount'];
                    endforeach; ?>
                    <h5>Total Paid: <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><?php echo number_format(round($total_paid), 2); ?></h5>
                    <h5>Balance: <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span><?php echo number_format(round($invoice['total_amount'] - $total_paid), 2); ?></h5>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer_sec">
            <div class="terms left_sec">
                <?php if (!empty($terms)): ?>
                    <h3>Terms / Declaration: <small style="color:#000"><?php echo $terms; ?></small></h3>
                <?php endif; ?>

                <?php if (!empty($bank_details['bank_name']) || !empty($bank_details['account_no']) || !empty($bank_details['ifsc_code']) || !empty($bank_details['branch'])): ?>
                    <p><strong>Bank Details -</strong></p>

                    <?php if (!empty($bank_details['bank_name'])): ?>
                        <p>Bank Name: <?php echo $bank_details['bank_name']; ?></p>
                    <?php endif; ?>

                    <?php if (!empty($bank_details['account_no'])): ?>
                        <p>Account No.: <?php echo $bank_details['account_no']; ?></p>
                    <?php endif; ?>

                    <?php if (!empty($bank_details['ifsc_code']) || !empty($bank_details['branch'])): ?>
                        <p>
                            <?php if (!empty($bank_details['ifsc_code'])): ?>
                                IFSC: <?php echo $bank_details['ifsc_code']; ?>
                            <?php endif; ?>

                            <?php if (!empty($bank_details['ifsc_code']) && !empty($bank_details['branch'])): ?>
                                &nbsp;|&nbsp;
                            <?php endif; ?>

                            <?php if (!empty($bank_details['branch'])): ?>
                                Branch: <?php echo $bank_details['branch']; ?>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="footer right_sec">
                <p>For <?php echo $biller['name']; ?></p>
                <!-- <img src="<?php echo base_url('assets/frontend/images/sign.png'); ?>" alt="SMM" width="200"> -->
            </div>
        </div>
    </div>
</body>

</html>