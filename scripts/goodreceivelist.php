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
        'db' => "CONCAT(`u`.`date`, ' ', `u`.`grn_time`)",
        'dt' => 'datetime',
        'field' => 'datetime',
        'as' => 'datetime'
    ),
    array(
        'db' => '`s`.`name`',
        'dt' => 'supplier_name',
        'field' => 'supplier_name',
        'as' => 'supplier_name'
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

// Handle search manually if needed
if (isset($_POST['search']) && $_POST['search']['value'] != '') {
    $searchValue = $_POST['search']['value'];
    
    // Build search conditions using actual column names without aliases
    $searchConditions = array();
    
    // Search in grn_no
    $searchConditions[] = "`u`.`grn_no` LIKE '%" . addslashes($searchValue) . "%'";
    
    // Search in supplier name
    $searchConditions[] = "`s`.`name` LIKE '%" . addslashes($searchValue) . "%'";
    
    // Search in grn type
    $searchConditions[] = "`u`.`grntype` LIKE '%" . addslashes($searchValue) . "%'";
    
    // Search in grn source
    $searchConditions[] = "`u`.`grn_source` LIKE '%" . addslashes($searchValue) . "%'";
    
    // Search in batch number
    $searchConditions[] = "`u`.`batch_number` LIKE '%" . addslashes($searchValue) . "%'";
    
    // Search in approval status
    $searchConditions[] = "`u`.`approval_status` LIKE '%" . addslashes($searchValue) . "%'";
    
    // Search in date (if value looks like a date)
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $searchValue)) {
        $searchConditions[] = "`u`.`date` = '" . addslashes($searchValue) . "'";
    }
    
    // Search in total (if value is numeric)
    if (is_numeric($searchValue)) {
        $searchConditions[] = "`u`.`total` = '" . addslashes($searchValue) . "'";
    }
    
    // Search in concatenated datetime
    $searchConditions[] = "CONCAT(`u`.`date`, ' ', `u`.`grn_time`) LIKE '%" . addslashes($searchValue) . "%'";
    
    if (!empty($searchConditions)) {
        $extraWhere .= " AND (" . implode(' OR ', $searchConditions) . ")";
    }
}

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
?>