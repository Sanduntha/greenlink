<?php
/*
 * DataTables example server-side processing script for materials with multiple suppliers
 */

// DB table to use
$table = 'tbl_row_material';

// Table's primary key
$primaryKey = 'idtbl_row_material';

// Array of database columns which should be read and sent back to DataTables.
$columns = array(
    array( 'db' => 'u.idtbl_row_material', 'dt' => 'idtbl_row_material', 'field' => 'idtbl_row_material' ),
    array( 'db' => 'u.material_code', 'dt' => 'material_code', 'field' => 'material_code' ), 
    array( 'db' => 'u.material_name', 'dt' => 'material_name', 'field' => 'material_name' ),
    // ROL column removed from display
    array( 'db' => 'uc.measure_type', 'dt' => 'measure_type', 'field' => 'measure_type' ),
    array( 'db' => 'ud.categoryname', 'dt' => 'categoryname', 'field' => 'categoryname' ),
    array( 'db' => 'r.rack_number', 'dt' => 'rack_number', 'field' => 'rack_number' ),
    array( 'db' => 'u.attachment', 'dt' => 'attachment', 'field' => 'attachment' ), 
    array( 'db' => 'u.status', 'dt' => 'status', 'field' => 'status' ),
    // Get comma-separated supplier names instead of JSON
    array( 'db' => 'suppliers_list', 'dt' => 'name', 'field' => 'suppliers_list', 'as' => 'suppliers_list' )
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

// Modified join query to get suppliers as comma-separated list
$joinQuery = "FROM `tbl_row_material` AS `u` 
              LEFT JOIN `tbl_measurements` AS `uc` ON (`u`.`tbl_measurements_idtbl_measurements` = `uc`.`idtbl_mesurements`) 
              LEFT JOIN `tbl_material_main_cat` AS `ud` ON (`u`.`tbl_material_main_cat_idtbl_material_main_cat` = `ud`.`idtbl_material_main_cat`)
              LEFT JOIN `tbl_rack` AS `r` ON (`u`.`tbl_rack_idtbl_rack` = `r`.`idtbl_rack`)
              LEFT JOIN (
                  SELECT 
                      ms.tbl_row_material_id,
                      GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ') as suppliers_list
                  FROM `tbl_material_supplier` ms
                  LEFT JOIN `tbl_supplier` s ON ms.tbl_supplier_idtbl_supplier = s.idtbl_supplier
                  WHERE ms.status = 1
                  GROUP BY ms.tbl_row_material_id
              ) AS ms ON `u`.`idtbl_row_material` = ms.tbl_row_material_id";

$extraWhere = "`u`.`status` IN (1, 2)";

echo json_encode(
    SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
?>