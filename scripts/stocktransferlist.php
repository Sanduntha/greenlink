<?php
/*
 * DataTables server-side processing script for Stock Transfer History
 */

// DB table to use
$table = 'tbl_stock_transfer_details';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables
$columns = array(
    array(
        'db' => 'tbl_stock_transfer_details.transfer_id',
        'dt' => 'transfer_id',
        'field' => 'transfer_id'
    ),
    array(
        'db' => 'tbl_stock_transfer.transfer_date',
        'dt' => 'transfer_date',
        'field' => 'transfer_date',
        'formatter' => function($d, $row) {
            return date('Y-m-d H:i', strtotime($d));
        }
    ),
    array(
        'db' => 'from_loc.location',
        'dt' => 'from_location',
        'field' => 'from_location_name',
        'as' => 'from_location_name'
    ),
    array(
        'db' => 'to_loc.location',
        'dt' => 'to_location',
        'field' => 'to_location_name',
        'as' => 'to_location_name'
    ),
    array(
        'db' => 'tbl_row_material.material_name',
        'dt' => 'material_name',
        'field' => 'material_name'
    ),
    array(
        'db' => 'tbl_stock_transfer_details.batch_no',
        'dt' => 'batch_no',
        'field' => 'batch_no'
    ),
    array(
        'db' => 'tbl_stock_transfer_details.quantity',
        'dt' => 'quantity',
        'field' => 'quantity'
    )
);

// SQL server connection information
require('config.php');
$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

require('ssp.customized.class.php');

$joinQuery = "FROM tbl_stock_transfer_details 
              INNER JOIN tbl_stock_transfer ON tbl_stock_transfer.id = tbl_stock_transfer_details.transfer_id
              LEFT JOIN tbl_row_material ON tbl_row_material.idtbl_row_material = tbl_stock_transfer_details.material_id
              LEFT JOIN tbl_location AS from_loc ON from_loc.idtbl_location = tbl_stock_transfer_details.from_location
              LEFT JOIN tbl_location AS to_loc ON to_loc.idtbl_location = tbl_stock_transfer_details.to_location";
$extraWhere = "tbl_stock_transfer_details.status = 1";

// Ensure the columns array is properly formatted
$columns = array_values($columns);

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);