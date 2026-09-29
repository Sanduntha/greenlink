<?php
/*
 * Server-side processing script for GRN DataTable
 */

// DB table to use
$table = 'tbl_grn';

// Table's primary key
$primaryKey = 'idtbl_grn';

// Array of database columns which should be read and sent back to DataTables
$columns = array(
    array('db' => '`u`.`idtbl_grn`', 'dt' => 'idtbl_grn', 'field' => 'idtbl_grn'),
    array('db' => '`u`.`grn_no`', 'dt' => 'grn_no', 'field' => 'grn_no'),
    array(
        'db' => "CONCAT(`u`.`date`, ' ', `u`.`grn_time`) AS `datetime`",
        'dt' => 'datetime',
        'field' => 'datetime'
    ),
    array(
        'db' => '`s`.`name` AS `supplier_name`',
        'dt' => 'supplier_name',
        'field' => 'supplier_name'
    ),
    array('db' => '`u`.`total`', 'dt' => 'total', 'field' => 'total'),
    array('db' => '`u`.`grntype`', 'dt' => 'grntype', 'field' => 'grntype'),
    array('db' => '`u`.`grn_source`', 'dt' => 'grn_source', 'field' => 'grn_source'),
    array('db' => '`u`.`batch_number`', 'dt' => 'batch_number', 'field' => 'batch_number'),
    array('db' => '`u`.`approval_status`', 'dt' => 'approval_status', 'field' => 'approval_status'),
);

// SQL server connection information
require('config.php');
$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db' => $db_name,
    'host' => $db_host
);

require('ssp.customized.class.php');

$joinQuery = "
FROM `tbl_grn` AS `u`
LEFT JOIN `tbl_supplier` AS `s`
    ON `s`.`idtbl_supplier` = `u`.`supplier_id`
";

$extraWhere = "`u`.`status` IN (1,2)";

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
?>