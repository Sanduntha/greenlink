<?php

$table = 'tbl_movement';
$primaryKey = 'idtbl_movement';

$columns = array(

    array( 'db' => '`m`.`idtbl_movement`', 'dt' => 'id', 'field' => 'idtbl_movement' ),
    array( 'db' => '`m`.`movement_id`', 'dt' => 'movement_id', 'field' => 'movement_id' ),
    array( 'db' => '`m`.`movement_date`', 'dt' => 'movement_date', 'field' => 'movement_date' ),
    array( 'db' => '`m`.`movement_type`', 'dt' => 'movement_type', 'field' => 'movement_type' ),

    array( 
        'db' => 'CONCAT(`m`.`from_site_location_name`, " - ", `m`.`from_zone_name`) AS from_location',
        'dt' => 'from_location',
        'field' => 'from_location'
    ),

    array( 
        'db' => 'CONCAT(`m`.`to_site_location_name`, " - ", `m`.`to_zone_name`) AS to_location',
        'dt' => 'to_location',
        'field' => 'to_location'
    ),

    array( 'db' => '`m`.`moved_by`', 'dt' => 'moved_by', 'field' => 'moved_by' ),
    array( 'db' => '`m`.`status`', 'dt' => 'status', 'field' => 'status' )

);

require('config.php');

$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

require('ssp.customized.class.php');

$joinQuery = "FROM `tbl_movement` AS `m`";
$extraWhere = "`m`.`status` <> 0";

echo json_encode(
    SSP::simple(
        $_POST,
        $sql_details,
        $table,
        $primaryKey,
        $columns,
        $joinQuery,
        $extraWhere
    )
);