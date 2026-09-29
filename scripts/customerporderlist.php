<?php
// Enable error reporting (for debugging, remove in production)
error_reporting(0);
ini_set('display_errors', 0);

// Set JSON header
header('Content-Type: application/json');

// Database configuration
require('config.php');
$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db' => $db_name,
    'host' => $db_host
);

// Include the SSP class
require('ssp.customized.class.php');

// DB table to use
$table = 'tbl_customer_porder';

// Table's primary key
$primaryKey = 'idtbl_customer_porder';

// Columns array
$columns = array(
    array('db' => '`o`.`idtbl_customer_porder`', 'dt' => 0, 'field' => 'idtbl_customer_porder'),
    array('db' => '`o`.`podate`', 'dt' => 1, 'field' => 'podate'),
    array('db' => '`c`.`name`', 'dt' => 2, 'field' => 'name'),

    array('db' => '`o`.`total_qty`', 'dt' => 3, 'field' => 'total_qty'),

    array('db' => '`o`.`confirmedstatus`', 'dt' => 4, 'field' => 'confirmedstatus'),
    array('db' => '`o`.`remarks`', 'dt' => 5, 'field' => 'remarks'),
     array('db' => '`o`.`job_number`', 'dt' => 6, 'field' => 'job_number')
);

// Join query
$joinQuery = "FROM `tbl_customer_porder` AS `o` 
              LEFT JOIN `tbl_customer` AS `c` 
              ON `c`.`idtbl_customer` = `o`.`tbl_customer_idtbl_customer`";

// Additional where clause
$extraWhere = "`o`.`status` = 1";

try {
    // Output the JSON
    echo json_encode(
        SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
    );
} catch (Exception $e) {
    // Return error if something fails
    echo json_encode([
        'error' => 'Server error: ' . $e->getMessage(),
        'draw' => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => []
    ]);
}
exit;