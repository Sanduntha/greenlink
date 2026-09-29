<?php

$table = 'tbl_movement';
$primaryKey = 'idtbl_movement';

$columns = array(

    array('db' => '`m`.`idtbl_movement`', 'dt' => 'id', 'field' => 'idtbl_movement'),
    array('db' => '`m`.`movement_id`', 'dt' => 'movement_id', 'field' => 'movement_id'),
    array('db' => '`m`.`movement_date`', 'dt' => 'movement_date', 'field' => 'movement_date'),
    array('db' => '`m`.`movement_type`', 'dt' => 'movement_type', 'field' => 'movement_type'),

    array(
        'db' => 'CONCAT(`fl`.`location`, " - ", `fr`.`rack_number`) AS from_location',
        'dt' => 'from_location',
        'field' => 'from_location'
    ),

    array(
        'db' => 'CONCAT(`tl`.`location`, " - ", `tr`.`rack_number`) AS to_location',
        'dt' => 'to_location',
        'field' => 'to_location'
    ),

    array('db' => '`m`.`moved_by`', 'dt' => 'moved_by', 'field' => 'moved_by'),
    array('db' => '`m`.`status`', 'dt' => 'status', 'field' => 'status')

);

require('config.php');

$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db' => $db_name,
    'host' => $db_host
);

require('ssp.customized.class.php');

$joinQuery = "FROM `tbl_movement` AS `m`
LEFT JOIN `tbl_location` AS `fl` ON `fl`.`idtbl_location` = `m`.`from_site_location_id`
LEFT JOIN `tbl_location` AS `tl` ON `tl`.`idtbl_location` = `m`.`to_site_location_id`
LEFT JOIN `tbl_rack` AS `fr` ON `fr`.`idtbl_rack` = `m`.`from_zone_id`
LEFT JOIN `tbl_rack` AS `tr` ON `tr`.`idtbl_rack` = `m`.`to_zone_id`";
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