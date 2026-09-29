<?php
/*
 * DataTables server-side processing script for allocations
 * Updated to use foreign keys: rack_id → tbl_rack, site_location_id → tbl_location
 */

// DB table to use
$table = 'tbl_allocation';

// Table's primary key
$primaryKey = 'idtbl_allocation';

// Array of database columns → dt = DataTables column index / name
$columns = array(
    array('db' => '`a`.`idtbl_allocation`',       'dt' => 'idtbl_allocation',     'field' => 'idtbl_allocation'),
    
    array('db' => '`g`.`idtbl_grn`',              'dt' => 'grn_number',           'field' => 'idtbl_grn',
          'formatter' => function($d) { return 'GRN - ' . $d; } ),
    
    array('db' => '`m`.`material_name`',          'dt' => 'material_name',        'field' => 'material_name'),
    
    array('db' => '`a`.`batch_number`',           'dt' => 'batchnumber',          'field' => 'batch_number'),
    
    // ─── New: proper location fields ───────────────────────────────
    array('db' => '`r`.`rack_number`',            'dt' => 'rack_number',          'field' => 'rack_number'),   // alias for clarity
    
    array(
    'db' => '`l`.`location`',
    'dt' => 'site_location',
    'field' => 'location'
),
    
    array('db' => '`a`.`qty`',                    'dt' => 'qty',                  'field' => 'qty'),
    
    array('db' => '`a`.`status`',                 'dt' => 'status',               'field' => 'status'),
    
    array('db' => '`a`.`sorting_complete`',       'dt' => 'sorting_complete',     'field' => 'sorting_complete'),
    
    array('db' => '`a`.`insertdatetime`',         'dt' => 'insertdatetime',       'field' => 'insertdatetime',
          'formatter' => function($d) {
              return $d ? date('Y-m-d', strtotime($d)) : '';
          })
);

// SQL server connection
require('config.php');

$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

require('ssp.customized.class.php');

// ─── Updated JOIN ────────────────────────────────────────────────────────
$joinQuery = "FROM `tbl_allocation` AS `a`
    LEFT JOIN `tbl_grn`            AS `g`  ON `a`.`grn_id`              = `g`.`idtbl_grn`
    LEFT JOIN `tbl_row_material`   AS `m`  ON `a`.`material_id`         = `m`.`idtbl_row_material`
    LEFT JOIN `tbl_rack`           AS `r`  ON `a`.`rack_id`             = `r`.`idtbl_rack`
    LEFT JOIN `tbl_location`       AS `l`  ON `a`.`site_location_id`    = `l`.`idtbl_location`";

// Optional: filter by status from query string (?status=1, ?status=2, etc.)
$status = isset($_GET['status']) ? (int)$_GET['status'] : 1;
$extraWhere = "`a`.`status` = $status";

// Optional: you can add more conditions here, example:
// $extraWhere .= " AND a.sorting_complete = 0";   // only show incomplete sorting, etc.

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
?>