<?php
/*
 * DataTables server-side processing script for Pending Waste Materials
 */

// DB table to use
$table = 'tbl_waste_header';

// Table's primary key
$primaryKey = 'idtbl_waste_header';

// Array of database columns
$columns = array(
    array(
        'db' => 'h.idtbl_waste_header',
        'dt' => 'DT_RowIndex',
        'field' => 'idtbl_waste_header',
        'formatter' => function($d, $row) {
            return '';
        }
    ),
    array(
        'db' => 'h.waste_date',
        'dt' => 'waste_date',
        'field' => 'waste_date',
        'formatter' => function($d, $row) {
            return date('Y-m-d', strtotime($d));
        }
    ),
    array(
        'db' => 'h.remarks',
        'dt' => 'remarks',
        'field' => 'remarks'
    ),
    array(
        'db' => 'h.created_date',
        'dt' => 'created_date',
        'field' => 'created_date',
        'formatter' => function($d, $row) {
            return date('Y-m-d H:i:s', strtotime($d));
        }
    ),
    array(
        'db' => 'h.status',
        'dt' => 'status',
        'field' => 'status'
    ),
    array(
        'db' => 'h.idtbl_waste_header',
        'dt' => 'id',
        'field' => 'idtbl_waste_header'
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

$joinQuery = "FROM tbl_waste_header AS h";

$extraWhere = "h.status = 2"; // Only pending records

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
?>