<?php
/*
 * DataTables server-side processing script for Pending Stock Transfers
 */

// DB table to use
$table = 'tbl_stock_transfer';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables
$columns = array(
    array(
        'db' => 'tbl_stock_transfer.id',
        'dt' => 'id',
        'field' => 'id'
    ),
    array(
        'db' => 'tbl_stock_transfer.transfer_date',
        'dt' => 'transfer_date',
        'field' => 'transfer_date',
        'formatter' => function ($d, $row) {
            return date('Y-m-d H:i', strtotime($d));
        }
    ),
    array(
        'db' => 'from_loc.location',
        'dt' => 'from_location',
        'field' => 'from_location',
        'as' => 'from_location'
    ),
    array(
        'db' => 'to_loc.location',
        'dt' => 'to_location',
        'field' => 'to_location',
        'as' => 'to_location'
    ),
    array(
        'db' => 'tbl_stock_transfer.status',
        'dt' => 'status',
        'field' => 'status',
        'formatter' => function ($d, $row) {
            switch ($d) {
                case 0:
                    return 'Rejected';
                case 1:
                    return 'Approved';
                case 2:
                    return 'Pending';
                default:
                    return 'Unknown';
            }
        }
    )
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

// Simplified join query - removed reel_transfers reference
$joinQuery = "FROM tbl_stock_transfer 
              LEFT JOIN (
                  SELECT transfer_id, MIN(from_location) as from_loc_id, MIN(to_location) as to_loc_id
                  FROM tbl_stock_transfer_details 
                  GROUP BY transfer_id
              ) std ON std.transfer_id = tbl_stock_transfer.id
              LEFT JOIN tbl_location AS from_loc ON from_loc.idtbl_location = std.from_loc_id
              LEFT JOIN tbl_location AS to_loc ON to_loc.idtbl_location = std.to_loc_id";

$extraWhere = "tbl_stock_transfer.status = 2";

// Ensure the columns array is properly formatted
$columns = array_values($columns);

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);