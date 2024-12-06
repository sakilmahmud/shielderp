<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Invoice #<?php echo $invoice['invoice_no']; ?></h2>
    <p><strong>Customer Name:</strong> <?php echo $invoice['customer_name']; ?></p>
    <p><strong>Invoice Date:</strong> <?php echo $invoice['invoice_date']; ?></p>
    <p><strong>Total Amount:</strong> <?php echo $invoice['total_amount']; ?></p>
    <p><strong>Payment Status:</strong>
        <?php if ($invoice['payment_status'] == '1') : ?>
            Paid
        <?php elseif ($invoice['payment_status'] == '0') : ?>
            Pending
        <?php elseif ($invoice['payment_status'] == '2') : ?>
            Partial
        <?php elseif ($invoice['payment_status'] == '3') : ?>
            Return
        <?php endif; ?>
    </p>

    <h3>Product Details</h3>
    <table class="table">
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
                    <td><?php echo $detail['product_name']; ?></td>
                    <td><?php echo $detail['quantity']; ?></td>
                    <td><?php echo $detail['price']; ?></td>
                    <td><?php echo $detail['discount']; ?></td>
                    <td><?php echo $detail['gst_amount']; ?></td>
                    <td><?php echo $detail['final_price']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>