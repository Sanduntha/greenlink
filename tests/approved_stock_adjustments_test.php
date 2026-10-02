<?php


// Test approved GRN stock adjustments, PO updates, permissions, rollback and adjustment history.
// Run with: php tests/approved_stock_adjustments_test.php
// Uses an in-memory database double; no application database is accessed.
if (PHP_SAPI !== 'cli') {
    exit;
}
define('BASEPATH', __DIR__);
class CI_Model { public function __construct() {} }
class CI_Controller {}
require __DIR__ . '/../application/models/Goodreceiveinfo.php';
require __DIR__ . '/../application/controllers/Goodreceive.php';

function check($condition, $message) {
    if (!$condition) throw new RuntimeException($message);
}

class AdjustmentTestRows {
    private $rows;
    public function __construct($rows) { $this->rows = array_values($rows); }
    public function row_array() { return $this->rows[0] ?? null; }
    public function row() { return (object) $this->row_array(); }
    public function result_array() { return $this->rows; }
}

class AdjustmentTestDb {
    public $db_debug = true;
    public $tables;
    public $failLog = false;
    private $snapshot;
    private $filter;
    public function trans_begin() { $this->snapshot = $this->tables; return true; }
    public function trans_commit() { return true; }
    public function trans_status() { return true; }
    public function trans_rollback() { $this->tables = $this->snapshot; }
    public function where($key, $value) { $this->filter = array($key, $value); return $this; }
    public function update($table, $values) {
        foreach ($this->tables[$table] as &$row) {
            if ($row[$this->filter[0]] == $this->filter[1]) $row = array_replace($row, $values);
        }
        return true;
    }
    public function insert($table, $values) {
        if ($this->failLog) return false;
        $this->tables[$table][] = $values;
        return true;
    }
    public function query($sql, $bindings) {
        check(preg_match('/FROM (tbl_\w+)/', $sql, $match) === 1, 'Unexpected query');
        $table = $match[1];
        $keys = array(
            'tbl_grn' => array('idtbl_grn'),
            'tbl_grndetail' => array('tbl_grn_idtbl_grn'),
            'tbl_porder' => array('idtbl_porder'),
            'tbl_porder_detail' => array('tbl_porder_idtbl_porder'),
            'tbl_stock' => array('tbl_row_material_idtbl_row_material', 'warehouse_location_name', 'site_location'),
            'tbl_batchstock' => array('tbl_row_material_idtbl_row_material', 'warehouse_location_name', 'site_location', 'batchnumber')
        );
        $rows = array_filter($this->tables[$table], function ($row) use ($keys, $table, $bindings) {
            foreach ($keys[$table] as $index => $key) {
                if ($row[$key] != $bindings[$index]) return false;
            }
            return true;
        });
        if (strpos($sql, 'SUM(total)') !== false) $rows = array(array('total' => array_sum(array_column($rows, 'total'))));
        return new AdjustmentTestRows($rows);
    }
}

class AdjustmentTestConfig {
    public $enabled = true;
    public function item($name) { return $name === 'existing_stock_edit_enabled' ? $this->enabled : null; }
}
class AdjustmentTestSession {
    public function userdata($name) { return $name === 'userid' ? 7 : true; }
}
class AdjustmentTestModel extends Goodreceiveinfo {
    public $db;
    public $config;
    public $session;
    public $access = true;
    public $edit = true;
    public function HasGrnPermission($permission = 'access_status') {
        return $this->access && ($permission !== 'edit' || $this->edit);
    }
    public function GetGrnForEdit($id) {
        foreach ($this->db->tables['tbl_grn'] as $row) {
            if ($row['idtbl_grn'] == $id) return $row;
        }
        return null;
    }
    public function GetExistingStockHistory($id) {
        return array_values(array_filter($this->db->tables['tbl_stock_adjustment_log'], function ($row) use ($id) {
            return $row['grn_id'] == $id;
        }));
    }
}

function fixture($source = 'no_po', $id = 99) {
    $model = new AdjustmentTestModel();
    $model->config = new AdjustmentTestConfig();
    $model->session = new AdjustmentTestSession();
    $model->db = new AdjustmentTestDb();
    $model->db->tables = array(
        'tbl_grn' => array(array('idtbl_grn' => $id, 'approval_status' => 'approved', 'status' => 1,
            'grn_source' => $source, 'ponumber' => $source === 'po' ? 10 : null, 'site_location' => 2, 'total' => 100)),
        'tbl_grndetail' => array(array('idtbl_grndetail' => 3, 'tbl_grn_idtbl_grn' => $id,
            'tbl_row_material_idtbl_row_material' => 5, 'warehouse_location_name' => 6, 'batch_number' => 'B1',
            'qty' => '10.00', 'po_qty' => '10.00', 'received_qty' => $source === 'po' ? '12.00' : '10.00',
            'accepted_qty' => '10.00', 'rejected_qty' => $source === 'po' ? '2.00' : '0.00', 'unitprice' => 10, 'total' => 100)),
        'tbl_stock' => array(array('idtbl_stock' => 1, 'tbl_row_material_idtbl_row_material' => 5,
            'warehouse_location_name' => 6, 'site_location' => 2, 'qty' => '15.00', 'status' => 1)),
        'tbl_batchstock' => array(array('idtbl_batchstock' => 4, 'tbl_row_material_idtbl_row_material' => 5,
            'warehouse_location_name' => 6, 'site_location' => 2, 'batchnumber' => 'B1',
            'qty' => '10.00', 'balanceqty' => '8.00', 'status' => 1)),
        'tbl_porder' => array(array('idtbl_porder' => 10, 'completedstatus' => 1)),
        'tbl_porder_detail' => array(array('idtbl_porder_detail' => 11, 'tbl_porder_idtbl_porder' => 10,
            'tbl_row_material_idtbl_row_material' => 5, 'qty' => '20.00', 'received_qty' => '20.00')),
        'tbl_stock_adjustment_log' => array()
    );
    return $model;
}
function correctQuantity($model, $new, $old = '10.00') {
    return $model->UpdateExistingStockQuantities($model->db->tables['tbl_grn'][0]['idtbl_grn'],
        array(array('detail_id' => '3', 'old_qty' => $old, 'qty' => $new)), 'Receipt correction');
}

foreach (array('no_po', 'po') as $source) {
    foreach (array(15, 99) as $id) {
        $model = fixture($source, $id);
        check($model->CanEditExistingStock($model->db->tables['tbl_grn'][0]), 'Every approved GRN must be editable');
    }
}
foreach (array('disabled', 'permission', 'pending', 'deleted') as $case) {
    $model = fixture();
    if ($case === 'disabled') $model->config->enabled = false;
    if ($case === 'permission') $model->edit = false;
    if ($case === 'pending') $model->db->tables['tbl_grn'][0]['approval_status'] = 'pending';
    if ($case === 'deleted') $model->db->tables['tbl_grn'][0]['status'] = 3;
    $before = $model->db->tables;
    check(correctQuantity($model, '12.00')['status'] === 0, "$case must reject correction");
    check($before === $model->db->tables, "$case must leave inventory unchanged");
}

$model = fixture();
check(correctQuantity($model, '12.50')['status'] === 1, 'Manual correction must succeed');
check($model->db->tables['tbl_stock'][0]['qty'] === '17.50', 'Apply only the stock difference');
check($model->db->tables['tbl_batchstock'][0]['balanceqty'] === '10.50', 'Apply difference to batch balance');
check($model->db->tables['tbl_grn'][0]['total'] == 125, 'Recalculate GRN total');
check($model->db->tables['tbl_stock_adjustment_log'][0]['grn_id'] === 99, 'History must reference selected GRN');

$model = fixture('po');
check(correctQuantity($model, '8.00')['status'] === 1, 'Approved PO correction must succeed');
check($model->db->tables['tbl_porder_detail'][0]['received_qty'] === '18.00', 'Correct cumulative PO received quantity');
check($model->db->tables['tbl_porder'][0]['completedstatus'] === 0, 'Reduced receipt must reopen PO');
$detail = $model->db->tables['tbl_grndetail'][0];
check($detail['qty'] === '10.00' && $detail['po_qty'] === '10.00', 'Preserve ordered quantities');
check($detail['rejected_qty'] === '2.00' && $detail['received_qty'] === '10.00', 'Preserve rejects and reconcile received quantity');
check(correctQuantity($model, '10.00', '8.00')['status'] === 1, 'Restoring accepted quantity must succeed');
check($model->db->tables['tbl_porder'][0]['completedstatus'] === 1, 'Restored receipt must complete PO');

foreach (array('stale', 'precision', 'consumed', 'po_limit', 'missing_po', 'missing_po_item', 'ambiguous_po_item', 'log_failure') as $case) {
    $model = fixture(strpos($case, 'po') !== false ? 'po' : 'no_po');
    $old = $case === 'stale' ? '9.00' : '10.00';
    $new = $case === 'precision' ? '9.123' : ($case === 'consumed' ? '1.00' : '12.00');
    if ($case === 'missing_po') $model->db->tables['tbl_porder'] = array();
    if ($case === 'missing_po_item') $model->db->tables['tbl_porder_detail'] = array();
    if ($case === 'ambiguous_po_item') $model->db->tables['tbl_porder_detail'][] = $model->db->tables['tbl_porder_detail'][0];
    if ($case === 'log_failure') $model->db->failLog = true;
    $before = $model->db->tables;
    check(correctQuantity($model, $new, $old)['status'] === 0, "$case must reject correction");
    check($before === $model->db->tables, "$case must roll back all changes");
    check($model->db->db_debug === true, 'Restore database debug setting');
}

$model = fixture('po');
$model->db->tables['tbl_porder_detail'][0]['received_qty'] = '18.00';
$model->db->tables['tbl_porder_detail'][] = array('idtbl_porder_detail' => 12,
    'tbl_porder_idtbl_porder' => 10, 'tbl_row_material_idtbl_row_material' => 9, 'qty' => '5.00', 'received_qty' => '0.00');
check(correctQuantity($model, '12.00')['status'] === 1, 'PO quantity increase within ordered quantity must succeed');
check($model->db->tables['tbl_porder'][0]['completedstatus'] === 0, 'PO must stay open while another item is pending');

$model = fixture();
$before = $model->db->tables;
check(correctQuantity($model, '10.00')['status'] === 1, 'No-change save must succeed');
check($before === $model->db->tables, 'No-change save must not add history');

class AdjustmentTestInput {
    public function post($key) { return $key === 'grn_id' ? 99 : null; }
}
class AdjustmentTestOutput {
    public $status = 200;
    public $data;
    public function set_status_header($code) { $this->status = $code; return $this; }
    public function set_content_type($type) { return $this; }
    public function set_output($json) { $this->data = json_decode($json, true); return $this; }
}
foreach (array('approved', 'po', 'readonly', 'pending', 'deleted', 'denied') as $case) {
    $controller = (new ReflectionClass('Goodreceive'))->newInstanceWithoutConstructor();
    $controller->Goodreceiveinfo = fixture($case === 'po' ? 'po' : 'no_po');
    if ($case === 'readonly') $controller->Goodreceiveinfo->edit = false;
    if ($case === 'pending') $controller->Goodreceiveinfo->db->tables['tbl_grn'][0]['approval_status'] = 'pending';
    if ($case === 'deleted') $controller->Goodreceiveinfo->db->tables['tbl_grn'][0]['status'] = 3;
    if ($case === 'denied') $controller->Goodreceiveinfo->access = false;
    $controller->input = new AdjustmentTestInput();
    $controller->output = new AdjustmentTestOutput();
    $controller->GetExistingStockAdjustmentData();
    $expected = $case === 'denied' ? 403 : (in_array($case, array('pending', 'deleted')) ? 404 : 200);
    check($controller->output->status === $expected, "$case adjustment endpoint status");
    if ($expected === 200) check($controller->output->data['editable'] === ($case !== 'readonly'), "$case edit permission");
}
echo "Approved stock permission, adjustment, rollback, PO synchronization and endpoint checks passed.\n";
