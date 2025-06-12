<!DOCTYPE html>
<html>

<head>
    <title>Supplier Ledger PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2 class="text-center">Supplier Ledger</h2>
    <p><strong>From:</strong> <?= date('d-m-Y', strtotime($from)) ?> &nbsp;&nbsp;
        <strong>To:</strong> <?= date('d-m-Y', strtotime($to)) ?>
    </p>

    <table>
        <thead>
            <tr>
                <th>Payment Date</th>
                <th>Supplier</th>
                <th>PO No</th>
                <th>PO Date</th>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0;
            foreach ($ledger as $row): $total += $row->amount; ?>
                <tr>
                    <td><?= date('d-m-Y', strtotime($row->trans_date)) ?></td>
                    <td><?= $row->supplier_name ?></td>
                    <td><?= $row->invoice_no ?></td>
                    <td><?= $row->purchase_date ?></td>
                    <td><?= $row->descriptions ?></td>
                    <td class="text-right">₹<?= number_format($row->amount, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Total</th>
                <th class="text-right">₹<?= number_format($total, 2) ?></th>
            </tr>
        </tfoot>
    </table>

</body>

</html>