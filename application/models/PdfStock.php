<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfStock extends CI_Model
{
    public function getRowMaterials($mainId)
    {
        $this->db->select('idtbl_row_material, material_name');
        $this->db->from('tbl_row_material');
        $this->db->where('tbl_material_main_cat_idtbl_material_main_cat', $mainId);
        $query = $this->db->get();
        return $query->result();
    }

    public function getMainMaterials()
    {
        $this->db->select('idtbl_material_main_cat, categoryname');
        $this->db->from('tbl_material_main_cat');
        $query = $this->db->get();
        return $query->result();
    }

    public function generatePdf($material_id, $month, $year)
    {
        $this->load->library('pdf');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        // Step 1: Get Material Name
        $this->db->select('material_name');
        $this->db->from('tbl_row_material');
        $this->db->where('idtbl_row_material', $material_id);
        $material_result = $this->db->get()->row();
        $material_name = $material_result ? $material_result->material_name : 'Unknown Material';

        // Step 2: Get Issued Data
        $this->db->select('GD.qty, G.date');
        $this->db->from('tbl_grndetail as GD');
        $this->db->join('tbl_grn as G', 'G.idtbl_grn = GD.tbl_grn_idtbl_grn');
        $this->db->where('GD.tbl_row_material_idtbl_row_material', $material_id);
        $this->db->where('MONTH(G.date)', $month);
        $this->db->where('YEAR(G.date)', $year);
        $query = $this->db->get();
        $results = $query->result();

        $dataByDate = [];
        $total = 0;

        foreach ($results as $row) {
            $day = date('Y-m-d', strtotime($row->date));
            if (!isset($dataByDate[$day])) {
                $dataByDate[$day] = 0;
            }
            $dataByDate[$day] += $row->qty;
            $total += $row->qty;
        }

        // Step 2: HTML Output
        ob_start();
        ?>
        <html>

        <head>
            <style>
                body {
                    font-family: sans-serif;
                    font-size: 10px;
                }

                table {
                    border-collapse: collapse;
                    width: 100%;
                }

                th,
                td {
                    border: 1px solid #000;
                    padding: 4px;
                    text-align: center;
                }

                th {
                    background-color: #f2f2f2;
                }

                .title {
                    text-align: center;
                    font-size: 16px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }

                .footer {
                    position: fixed;
                    bottom: 40px;
                    left: 0;
                    right: 0;
                    width: 100%;
                    font-size: 12px;
                }
            </style>
        </head>

        <body>
            <div class="title"><?= strtoupper($material_name) ?> STOCK REPORT -
                <?= strtoupper(date('F', mktime(0, 0, 0, $month, 10))) ?>         <?= $year ?></div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataByDate as $date => $qty): ?>
                        <tr>
                            <td><?= $date ?></td>
                            <td><?= $qty ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <th>Total</th>
                        <th><?= $total ?></th>
                    </tr>
                </tbody>
            </table>
            <div class="footer">
                <div style="float: left;">
                    ..................................................<br>
                    <strong>Store Keeper</strong>
                </div>
                <div style="float: right; text-align: right;">
                    ..................................................<br>
                    <strong>Factory Manager / Assistant Manager</strong>
                </div>
            </div>
        </body>

        </html>
        <?php
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Material_Issue_{$material_id}_{$month}_{$year}.pdf", ["Attachment" => false]);
    }

    public function GetBalanceStock($month, $year)
    {
        $this->load->library('pdf');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        // Step 1: Fetch total quantity per item
        $this->db->select('RM.material_name, SUM(S.qty) as total_qty');
        $this->db->from('tbl_stock as S');
        $this->db->join('tbl_row_material as RM', 'RM.idtbl_row_material = S.tbl_row_material_idtbl_row_material');
        $this->db->where('MONTH(S.updatedatetime)', $month);
        $this->db->where('YEAR(S.updatedatetime)', $year);
        $this->db->where('S.status', 1);
        $this->db->group_by('S.tbl_row_material_idtbl_row_material');
        $query = $this->db->get();
        $results = $query->result();

        // Step 2: Generate HTML
        ob_start();
        ?>
        <html>

        <head>
            <style>
                body {
                    font-family: sans-serif;
                    font-size: 12px;
                }

                table {
                    border-collapse: collapse;
                    width: 100%;
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

                .title {
                    text-align: center;
                    font-size: 16px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }

                .footer {
                    position: fixed;
                    bottom: 40px;
                    left: 0;
                    right: 0;
                    width: 100%;
                    font-size: 12px;
                }
            </style>
        </head>

        <body>
            <div class="title"><?= strtoupper(date('F', mktime(0, 0, 0, $month, 10))) ?> - STOCK BALANCE REPORT (<?= $year ?>)
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Balance Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;
                    $grandTotal = 0;
                    foreach ($results as $row):
                        $grandTotal += $row->total_qty;
                        ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><?= $row->material_name ?></td>
                            <td><?= number_format($row->total_qty, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="footer">
                <div style="float: left;">
                    ..................................................<br>
                    <strong>Store Keeper</strong>
                </div>
                <div style="float: right; text-align: right;">
                    ..................................................<br>
                    <strong>Factory Manager / Assistant Manager</strong>
                </div>
            </div>
        </body>

        </html>
        <?php
        $html = ob_get_clean();

        // Step 3: Generate PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Stock_Balance_{$month}_{$year}.pdf", array("Attachment" => false));
    }


    public function generateSummaryReport($material_ids)
    {
        $this->load->library('pdf');

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        // Get material names
        $this->db->select('idtbl_row_material, material_name');
        $this->db->from('tbl_row_material');
        $this->db->where_in('idtbl_row_material', $material_ids);
        $materials_result = $this->db->get()->result();

        $material_names = [];
        foreach ($materials_result as $material) {
            $material_names[$material->idtbl_row_material] = $material->material_name;
        }

        // Get current stock data for selected materials
        $this->db->select('RM.idtbl_row_material, RM.material_name, SUM(S.qty) as total_qty');
        $this->db->from('tbl_stock as S');
        $this->db->join('tbl_row_material as RM', 'RM.idtbl_row_material = S.tbl_row_material_idtbl_row_material');
        $this->db->where_in('S.tbl_row_material_idtbl_row_material', $material_ids);
        $this->db->where('S.status', 1);
        $this->db->group_by('S.tbl_row_material_idtbl_row_material');
        $query = $this->db->get();
        $results = $query->result();

        // Generate HTML
        ob_start();
        ?>
        <html>

        <head>
            <style>
                body {
                    font-family: sans-serif;
                    font-size: 12px;
                }

                table {
                    border-collapse: collapse;
                    width: 100%;
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

                .title {
                    text-align: center;
                    font-size: 16px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }

                .footer {
                    position: fixed;
                    bottom: 40px;
                    left: 0;
                    right: 0;
                    width: 100%;
                    font-size: 12px;
                }

                .no-data {
                    text-align: center;
                    padding: 20px;
                    font-style: italic;
                }
            </style>
        </head>

        <body>
            <div class="title">MATERIALS SUMMARY STOCK REPORT</div>
            <div class="subtitle" style="text-align: center; font-size: 14px; margin-bottom: 10px;">
                Generated on: <?= date('Y-m-d') ?>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Balance Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;
                    if (!empty($results)):
                        foreach ($results as $row):
                            ?>
                            <tr>
                                <td><?= $count++ ?></td>
                                <td><?= $row->material_name ?></td>
                                <td><?= number_format($row->total_qty, 2) ?></td>
                            </tr>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <tr>
                            <td colspan="3" class="no-data">No stock data found for selected materials</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="footer">
                <div style="float: left;">
                    ..................................................<br>
                    <strong>Store Keeper</strong>
                </div>
                <div style="float: right; text-align: right;">
                    ..................................................<br>
                    <strong>Factory Manager / Assistant Manager</strong>
                </div>
            </div>
        </body>

        </html>
        <?php
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $material_ids_str = implode('_', $material_ids);
        $dompdf->stream("Material_Summary_{$material_ids_str}.pdf", ["Attachment" => false]);
    }

}
