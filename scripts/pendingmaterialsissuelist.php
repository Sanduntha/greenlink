<?php
require('config.php');
require('ssp.customized.class.php');

$table = 'tbl_material_issue_header';
$primaryKey = 'idtbl_material_issue_header';

$columns = array(
    array( 'db' => 'h.idtbl_material_issue_header', 'dt' => 'id', 'field' => 'idtbl_material_issue_header' ),
    array( 'db' => 'c.name', 'dt' => 'customer', 'field' => 'name' ),
    array( 'db' => 'CONCAT(po.podate, " - ", po.remarks)', 'dt' => 'job_order', 'field' => 'job_order', 'as' => 'job_order' ),
    array( 'db' => 'm.itemname', 'dt' => 'item', 'field' => 'itemname' ),
    array(
        'db' => 'h.issue_date',
        'dt' => 'issue_date',
        'field' => 'issue_date',
        'formatter' => function($d,$row){ return date('Y-m-d H:i:s', strtotime($d)); }
    ),
    array(
        'db' => 'h.status',
        'dt' => 'status',
        'field' => 'status'
    ),
    array(
        'db' => 'h.idtbl_material_issue_header',
        'dt' => 'action',
        'field' => 'idtbl_material_issue_header',
        'formatter' => function($d,$row){
            return '<button class="btn btn-info btn-sm btn-view" data-id="'.$d.'" title="View Details"><i class="fas fa-eye"></i></button>';
        }
    )
);

$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db' => $db_name,
    'host' => $db_host
);

$joinQuery = "FROM tbl_material_issue_header AS h
              LEFT JOIN tbl_customer_porder AS po ON po.idtbl_customer_porder = h.tbl_customer_porder_idtbl_customer_porder
              LEFT JOIN tbl_customer AS c ON c.idtbl_customer = po.tbl_customer_idtbl_customer
              LEFT JOIN tbl_customer_porder_detail AS pd ON pd.idtbl_customer_porder_detail = h.tbl_customer_porder_detail_id
              LEFT JOIN tbl_mainitems AS m ON m.idtbl_mainitems = pd.tbl_mainitems_idtbl_mainitems";

$extraWhere = "h.status = 2"; // Only pending

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
