<?php
/*
 * DataTables example server-side processing script.
 */

// DB table to use
$table = 'tbl_porder';

// Table's primary key
$primaryKey = 'idtbl_porder';

// Array of database columns
$columns = array(
    array( 'db' => '`u`.`idtbl_porder`', 'dt' => 'idtbl_porder', 'field' => 'idtbl_porder' ),
    array( 'db' => '`u`.`podate`', 'dt' => 'podate', 'field' => 'podate' ),
    array( 'db' => '`s`.`name`', 'dt' => 'suppliername', 'field' => 'name' ),
    array( 'db' => '`u`.`total`', 'dt' => 'total', 'field' => 'total' ),
    array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' ),
    array( 'db' => '`u`.`completedstatus`', 'dt' => 'completedstatus', 'field' => 'completedstatus' ),
    array( 'db' => '`u`.`remarks`', 'dt' => 'remarks', 'field' => 'remarks' ),
);

// SQL server connection information
require('config.php');
$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

require('ssp.customized.class.php' );

// Join with supplier table
$joinQuery = "FROM `tbl_porder` AS `u` 
              LEFT JOIN `tbl_supplier` AS `s` ON `u`.`tbl_supplier_idtbl_supplier` = `s`.`idtbl_supplier`";
$extraWhere = "`u`.`confirmedstatus` = 1";


echo json_encode(
    SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
?>