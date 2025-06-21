<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .header,
        .footer {
            text-align: center;
        }

        .receipt-box {
            border: 1px solid #000;
            padding: 20px;
            margin-top: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="<?= $biller['logo'] ?>" height="60" alt="Logo"><br>
        <h2><?= $biller['name'] ?></h2>
        <p><?= $biller['address'] ?><br>
            <?= $biller['contact'] ?> | <?= $biller['email'] ?> | <?= $biller['website'] ?></p>
    </div>

    <div class="receipt-box">
        <h3>Payment Receipt</h3>
        <p><strong>Date:</strong> <?= date('d-m-Y', strtotime($payment['trans_date'])) ?></p>
        <p><strong>Receipt No:</strong> <?= 'TXN' . str_pad($payment['id'], 5, '0', STR_PAD_LEFT) ?></p>

        <?php if ($customer): ?>
            <p><strong>Customer:</strong> <?= $customer['customer_name'] ?></p>
            <p><strong>Phone:</strong> <?= $customer['phone'] ?></p>
            <p><strong>Address:</strong> <?= $customer['address'] ?></p>
        <?php endif; ?>

        <p><strong>Amount Paid:</strong> ₹<?= number_format($payment['amount'], 2) ?></p>
        <p><strong>Payment Method:</strong> <?= $payment['payment_method'] ?></p>
        <p><strong>Description:</strong> <?= $payment['descriptions'] ?></p>
        <?php if ($invoice): ?>
            <p><strong>Against Invoice:</strong> <?= $invoice['invoice_no'] ?> (₹<?= number_format($invoice['total_amount'], 2) ?>)</p>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p><strong>Bank Details:</strong><br>
            <?= $bank_details['bank_name'] ?> | A/C: <?= $bank_details['account_no'] ?> | IFSC: <?= $bank_details['ifsc_code'] ?> | Branch: <?= $bank_details['branch'] ?></p>

        <?php if (!empty($terms)): ?>
            <p><em><?= $terms ?></em></p>
        <?php endif; ?>
    </div>
</body>

</html>