<?php
// Main table
$table = 'tbl_shipmentplaning';

// Primary key
$primaryKey = 'idtbl_shipmentplaning';

// Columns mapping (db → datatable)
$columns = array(
    array(
        'db' => 'sp.idtbl_shipmentplaning',
        'dt' => 'id',
        'field' => 'idtbl_shipmentplaning',
        'formatter' => function($d, $row) {
            return $d;
        }
    ),
    array(
        'db' => 'sp.shipment_id',
        'dt' => 'shipment_id',
        'field' => 'shipment_id'
    ),
    array(
        'db' => 'sp.created_at',
        'dt' => 'created_at',
        'field' => 'created_at',
        'formatter' => function($d, $row) {
            return date('Y-m-d H:i:s', strtotime($d));
        }
    ),
    array(
        'db' => 'sp.exporter',
        'dt' => 'exporter',
        'field' => 'exporter'
    ),
    array(
        'db' => 'sp.consignee',
        'dt' => 'consignee',
        'field' => 'consignee'
    ),
    array(
        'db' => 'sp.total_items',
        'dt' => 'total_items',
        'field' => 'total_items'
    ),
    array(
        'db' => 'sp.total_weight',
        'dt' => 'total_weight',
        'field' => 'total_weight'
    ),
    array(
        'db' => 'sp.status',
        'dt' => 'status',
        'field' => 'status',
        'formatter' => function($d, $row) {
            $statuses = [
                1 => '<span class="badge badge-success">Approved</span>',
                2 => '<span class="badge badge-warning">Pending</span>',
                3 => '<span class="badge badge-danger">Rejected</span>',
                0 => '<span class="badge badge-secondary">Deleted</span>'
            ];
            return $statuses[$d] ?? '<span class="badge badge-secondary">Unknown</span>';
        }
    ),
    array(
        'db' => 'u.username',
        'dt' => 'created_by_name',
        'field' => 'username'
    )
);

// DB connection
require('config.php');
$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db' => $db_name,
    'host' => $db_host
);

// JOIN query
$joinQuery = "
FROM tbl_shipmentplaning AS sp
LEFT JOIN tbl_user AS u 
    ON u.idtbl_user = sp.created_by
";

// WHERE condition (exclude deleted)
$extraWhere = "sp.status != 0";

// GROUP BY
$groupBy = "sp.idtbl_shipmentplaning";

// Load SSP class
require('ssp.customized.class.php');

// Output
echo json_encode(
    SSP::simple(
        $_POST,
        $sql_details,
        $table,
        $primaryKey,
        $columns,
        $joinQuery,
        $extraWhere,
        $groupBy
    )
);
?>