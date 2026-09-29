<?php
/*
 * DataTables server-side processing script for Reel Transfer History
 */

// DB table to use
$table = 'tbl_reel_transfers';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables
$columns = array(
    array(
        'db' => 'transfer_id',
        'dt' => 'transfer_id',
        'field' => 'transfer_id'
    ),
    array(
        'db' => 'transfer_date',
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
        'db' => 'reel_no',
        'dt' => 'reel_no',
        'field' => 'reel_no'
    ),
    array(
        'db' => 'quantity',
        'dt' => 'quantity',
        'field' => 'quantity',
        'className' => 'text-right'
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

$joinQuery = "FROM tbl_reel_transfers 
              LEFT JOIN tbl_row_material ON tbl_row_material.idtbl_row_material = tbl_reel_transfers.material_id
              LEFT JOIN tbl_location AS from_loc ON from_loc.idtbl_location = tbl_reel_transfers.from_location
              LEFT JOIN tbl_location AS to_loc ON to_loc.idtbl_location = tbl_reel_transfers.to_location";
$extraWhere = "tbl_reel_transfers.status = 1";

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);