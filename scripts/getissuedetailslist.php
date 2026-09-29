<?php
require('config.php');

// Get the issue ID from request
$issueId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($issueId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid issue ID']);
    exit;
}

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get header information
$headerQuery = "SELECT h.*, c.name AS customer, 
                CONCAT(po.podate, ' - ', po.remarks) AS job_order,
                m.itemname AS item
                FROM tbl_material_issue_header h
                LEFT JOIN tbl_customer_porder po ON po.idtbl_customer_porder = h.tbl_customer_porder_idtbl_customer_porder
                LEFT JOIN tbl_customer c ON c.idtbl_customer = po.tbl_customer_idtbl_customer
                LEFT JOIN tbl_customer_porder_detail pd ON pd.idtbl_customer_porder_detail = h.tbl_customer_porder_detail_id
                LEFT JOIN tbl_mainitems m ON m.idtbl_mainitems = pd.tbl_mainitems_idtbl_mainitems
                WHERE h.idtbl_material_issue_header = ?";

$stmt = $conn->prepare($headerQuery);
$stmt->bind_param("i", $issueId);
$stmt->execute();
$headerResult = $stmt->get_result();
$headerData = $headerResult->fetch_assoc();

if (!$headerData) {
    echo json_encode(['success' => false, 'message' => 'Issue not found']);
    exit;
}

// Get detail items - UPDATED QUERY TO FIX MATERIAL NAME ISSUE
$detailQuery = "SELECT d.*, 
                IFNULL(rm.material_name, 'N/A') AS material_name,
                IFNULL(l.location, d.location) AS location_name
                FROM tbl_material_issue_detail d
                LEFT JOIN tbl_row_material rm ON rm.idtbl_row_material = d.tbl_row_material_id
                LEFT JOIN tbl_location l ON l.idtbl_location = d.location
                WHERE d.tbl_material_issue_header_id = ?";

$stmt = $conn->prepare($detailQuery);
$stmt->bind_param("i", $issueId);
$stmt->execute();
$detailResult = $stmt->get_result();
$items = [];

while ($row = $detailResult->fetch_assoc()) {
    $items[] = [
        'material' => $row['material_name'] ?: 'N/A',
        'batch_no' => $row['reel_no'] ?: 'N/A',
        'qty' => number_format($row['qty'], 2),
        'location' => $row['location_name'] ?: 'N/A'
    ];
}

$conn->close();

// Prepare response
$response = [
    'success' => true,
    'data' => [
        'customer' => $headerData['customer'],
        'job_order' => $headerData['job_order'],
        'item' => $headerData['item'],
        'issue_date' => date('Y-m-d H:i:s', strtotime($headerData['issue_date'])),
        'remarks' => $headerData['remarks'] ?: 'N/A',
        'items' => $items
    ]
];

header('Content-Type: application/json');
echo json_encode($response);
?>