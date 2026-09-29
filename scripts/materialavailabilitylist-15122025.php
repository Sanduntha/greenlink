<?php
/*
 * DataTables server-side processing script for Material Availability View
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
        'db' => 'tbl_material_main_cat.categoryname',
        'dt' => 'categoryname',
        'field' => 'categoryname'
    ),
    array(
        'db' => 'tbl_location.location',
        'dt' => 'location',
        'field' => 'location'
    ),
    array(
        'db' => 'tbl_stock.qty',
        'dt' => 'quantity',
        'field' => 'qty'
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

$joinQuery = "FROM tbl_stock 
              LEFT JOIN tbl_row_material ON tbl_row_material.idtbl_row_material = tbl_stock.tbl_row_material_idtbl_row_material
              LEFT JOIN tbl_material_main_cat ON tbl_material_main_cat.idtbl_material_main_cat = tbl_row_material.tbl_material_main_cat_idtbl_material_main_cat
              LEFT JOIN tbl_location ON tbl_location.idtbl_location = tbl_stock.warehouse_location_name";

$extraWhere = "tbl_stock.status = 1 AND tbl_stock.qty > 0";

$categoryId = isset($_POST['categoryId']) ? $_POST['categoryId'] : '';
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';

if (!empty($categoryId) && $categoryId != 'all') {
    $extraWhere .= " AND tbl_material_main_cat.idtbl_material_main_cat = '" . $categoryId . "'";
}

if (!empty($searchTerm)) {
    $extraWhere .= " AND (tbl_row_material.material_name LIKE '%" . $searchTerm . "%' 
                         OR tbl_location.location LIKE '%" . $searchTerm . "%'
                         OR tbl_material_main_cat.categoryname LIKE '%" . $searchTerm . "%')";
}

$length = isset($_POST['length']) ? $_POST['length'] : 10;
if ($length == -1) {
    $length = 18441110000; 
}

$request = $_POST;

if (!isset($request['draw']))
    $request['draw'] = 0;
if (!isset($request['columns']))
    $request['columns'] = array();
if (!isset($request['order']))
    $request['order'] = array();
if (!isset($request['start']))
    $request['start'] = 0;
if (!isset($request['length']))
    $request['length'] = $length;
if (!isset($request['search']))
    $request['search'] = array('value' => '', 'regex' => false);

echo json_encode(
    SSP::simple($request, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);