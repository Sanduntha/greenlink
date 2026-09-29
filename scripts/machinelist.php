<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'tbl_machine';

// Table's primary key
$primaryKey = 'idtbl_machine';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_machine`', 'dt' => 'idtbl_machine', 'field' => 'idtbl_machine' ),
	array( 'db' => '`u`.`machine`', 'dt' => 'machine', 'field' => 'machine' ),
	array( 'db' => '`u`.`perhour_cost`', 'dt' => 'perhour_cost', 'field' => 'perhour_cost' ),
	array( 'db' => '`u`.`perhour_maxoutput`', 'dt' => 'perhour_maxoutput', 'field' => 'perhour_maxoutput' ),
	array( 'db' => '`u`.`setuptime`', 'dt' => 'setuptime', 'field' => 'setuptime' ),
	array( 'db' => '`u`.`board_size`', 'dt' => 'board_size', 'field' => 'board_size' ),
	array( 'db' => '`ua`.`type`', 'dt' => 'type', 'field' => 'type' ),
	array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' )
);

// SQL server connection information
require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('ssp.customized.class.php' );

$joinQuery = "FROM `tbl_machine` AS `u` JOIN `tbl_machine_type` AS `ua` ON (`ua`.`idtbl_machine_type` = `u`.`tbl_machine_type_idtbl_machine_type`)";
$extraWhere = "`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
);
