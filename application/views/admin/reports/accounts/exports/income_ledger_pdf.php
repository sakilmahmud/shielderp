<!DOCTYPE html>
<html>

<head>
    <title>Income Ledger</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <h2>Income Ledger</h2>
    <p>From: <?= date('d-m-Y', strtotime($from)) ?> &nbsp; To: <?= date('d-m-Y', strtotime($to)) ?></p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Head</th>
                <th>Particulars</th>
                <th class="text-right">Cash</th>
                <th class="text-right">Bank</th>
                <th class="text-right">Wallet</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_cash = $total_bank = $total_wallet = 0;
            $grouped = [];

            foreach ($ledger as $row) {
                $key = $row->trans_date . '|' . $row->head_title;
                $grouped[$key]['rows'][] = $row;
            }

            foreach ($grouped as $key => $data_group):
                list($date, $head) = explode('|', $key);
                $group_cash = $group_bank = $group_wallet = $group_total = 0;
                $particulars = '';

                foreach ($data_group['rows'] as $r) {
                    $cash = $r->pm_type == 1 ? $r->amount : 0;
                    $bank = $r->pm_type == 2 ? $r->amount : 0;
                    $wallet = $r->pm_type == 3 ? $r->amount : 0;
                    $group_cash += $cash;
                    $group_bank += $bank;
                    $group_wallet += $wallet;
                    $group_total += $r->amount;

                    $desc = $r->income_title ?? '';
                    $inv  = $r->invoice_no ? " ({$r->invoice_no})" : '';
                    $particulars .= $desc . $inv . "<br>";
                }

                $total_cash += $group_cash;
                $total_bank += $group_bank;
                $total_wallet += $group_wallet;
            ?>
                <tr>
                    <td><?= date('d-m-Y', strtotime($date)) ?></td>
                    <td><?= $head ?></td>
                    <td><?= $particulars ?></td>
                    <td class="text-right"><?= number_format($group_cash, 2) ?></td>
                    <td class="text-right"><?= number_format($group_bank, 2) ?></td>
                    <td class="text-right"><?= number_format($group_wallet, 2) ?></td>
                    <td class="text-right"><?= number_format($group_total, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Grand Total</th>
                <th class="text-right"><?= number_format($total_cash, 2) ?></th>
                <th class="text-right"><?= number_format($total_bank, 2) ?></th>
                <th class="text-right"><?= number_format($total_wallet, 2) ?></th>
                <th class="text-right"><?= number_format($total_cash + $total_bank + $total_wallet, 2) ?></th>
            </tr>
        </tfoot>
    </table>

</body>

</html>