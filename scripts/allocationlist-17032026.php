<?php
/*
 * DataTables server-side processing script for allocations
 */

// DB table to use
$table = 'tbl_allocation';

// Table's primary key
$primaryKey = 'idtbl_allocation';

// Array of database columns which should be read and sent back to DataTables.
$columns = array(
    array('db' => '`a`.`idtbl_allocation`', 'dt' => 'idtbl_allocation', 'field' => 'idtbl_allocation'),
    array('db' => '`g`.`idtbl_grn`', 'dt' => 'grn_number', 'field' => 'idtbl_grn'),
    array('db' => '`m`.`material_name`', 'dt' => 'material_name', 'field' => 'material_name'),
    array('db' => '`a`.`batch_number`', 'dt' => 'batchnumber', 'field' => 'batch_number'),
    array('db' => '`a`.`location_name`', 'dt' => 'location', 'field' => 'location_name'),
    array('db' => '`a`.`locationname`', 'dt' => 'locationname', 'field' => 'locationname'),
    array('db' => '`a`.`qty`', 'dt' => 'qty', 'field' => 'qty'),
    array('db' => '`a`.`status`', 'dt' => 'status', 'field' => 'status'),
    array('db' => '`a`.`sorting_complete`', 'dt' => 'sorting_complete', 'field' => 'sorting_complete'), // NEW FIELD
    array('db' => '`a`.`insertdatetime`', 'dt' => 'insertdatetime', 'field' => 'insertdatetime')
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


$joinQuery = "FROM `tbl_allocation` AS `a` 
    LEFT JOIN `tbl_grn` AS `g` 
        ON (`a`.`grn_id` = `g`.`idtbl_grn`)
    LEFT JOIN `tbl_row_material` AS `m` 
        ON (`a`.`material_id` = `m`.`idtbl_row_material`)
    LEFT JOIN `tbl_location` AS `l`
        ON (`l`.`idtbl_location` = `a`.`location_name`)";

// Get status from query parameter
$status = isset($_GET['status']) ? $_GET['status'] : 1;
$extraWhere = "`a`.`status` = " . intval($status);

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
?>