<?php
/*
 * DataTables server-side processing script for All Stock View
 */

// DB table to use
$table = 'tbl_stock';

// Table's primary key
$primaryKey = 'idtbl_stock';

// Array of database columns
$columns = array(
    array(
        'db' => 'tbl_stock.idtbl_stock',
        'dt' => 'idtbl_stock',
        'field' => 'idtbl_stock'
    ),
    array(
        'db' => 'tbl_row_material.material_name',
        'dt' => 'material_name',
        'field' => 'material_name'
    ),
    array(
        'db' => 'tbl_location.location',
        'dt' => 'location',
        'field' => 'location',
        'as' => 'location'
    ),
    array(
        'db' => 'tbl_rack.rack_number',
        'dt' => 'rack_number',
        'field' => 'rack_number',
        'as' => 'rack_number'
    ),
    array(
        'db' => 'tbl_stock.qty',
        'dt' => 'qty',
        'field' => 'qty',
        'className' => 'text-right'
    ),
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

$joinQuery = "FROM tbl_stock 
             LEFT JOIN tbl_row_material ON tbl_row_material.idtbl_row_material = tbl_stock.tbl_row_material_idtbl_row_material
             LEFT JOIN tbl_location ON tbl_location.idtbl_location = tbl_stock.warehouse_location_name
             LEFT JOIN tbl_rack ON tbl_rack.idtbl_rack = tbl_row_material.tbl_rack_idtbl_rack";

$extraWhere = "tbl_stock.status = 1 AND tbl_stock.qty > 0";

// Use $_REQUEST to work with both POST and GET
$request = $_REQUEST;

// Ensure required parameters exists
if (!isset($request['draw']))
    $request['draw'] = 0;
if (!isset($request['columns']))
    $request['columns'] = array();
if (!isset($request['order']))
    $request['order'] = array();
if (!isset($request['start']))
    $request['start'] = 0;
if (!isset($request['length']))
    $request['length'] = 10;
if (!isset($request['search']))
    $request['search'] = array('value' => '', 'regex' => false);

echo json_encode(
    SSP::simple($request, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
?>