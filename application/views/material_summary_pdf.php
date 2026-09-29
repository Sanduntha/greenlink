<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Material Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            color: #333;
        }

        .subheader {
            text-align: center;
            margin-bottom: 15px;
        }

        .summary-info {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #666;
        }

        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>DAILY MATERIAL REQUIREMENT SUMMARY</h2>
        <div class="subheader">
            <strong>Date:</strong> <?= date('F j, Y', strtotime($date)) ?>
        </div>
    </div>

    <div class="summary-info">
        <strong>Selected Purchase Orders:</strong>
        <ul>
            <?php foreach ($pos as $po): ?>
                <li>PO-<?= str_pad($po['idtbl_customer_porder'], 5, '0', STR_PAD_LEFT) ?> - <?= $po['customer_name'] ?>
                    (Qty: <?= $po['total_qty'] ?>)</li>
            <?php endforeach; ?>
        </ul>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Material Name</th>
                <th class="text-right">Required Quantity</th>
                <th>Unit</th>
                <th class="text-right">Total Required</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_materials = [];
            $count = 1;

            // Calculate totals
            foreach ($material_summary as $po_data) {
                foreach ($po_data['materials'] as $material) {
                    $key = $material['material_id'] . '_' . $material['unit'];
                    if (!isset($total_materials[$key])) {
                        $total_materials[$key] = [
                            'name' => $material['material_name'],
                            'unit' => $material['unit'],
                            'total' => 0
                        ];
                    }
                    $total_materials[$key]['total'] += $material['required_qty'];
                }
            }

            // Display materials
            foreach ($total_materials as $material) {
                ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><?= $material['name'] ?></td>
                    <td class="text-right"><?= number_format($material['total'], 2) ?></td>
                    <td><?= $material['unit'] ?></td>
                    <td class="text-right"><?= number_format($material['total'], 2) ?>     <?= $material['unit'] ?></td>
                </tr>
                <?php
            }

            if (empty($total_materials)) {
                ?>
                <tr>
                    <td colspan="5" class="text-center">No material data found</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on: <?= $generated_date ?></p>
        <p>This is a system generated report for internal use.</p>
    </div>
</body>

</html>