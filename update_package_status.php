
<?php
require_once 'includes/auth.php';
require_once 'includes/db_connect.php';

// Ensure user is admin or operator
if (!isAdmin() && !isOperator()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $packageId = isset($_POST['package_id']) ? (int)$_POST['package_id'] : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    
    // Validate status
    $validStatuses = ['active', 'inactive', 'sold-out'];
    if (!in_array($status, $validStatuses)) {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        exit;
    }
    
    // Update package status
    $sql = "UPDATE packages SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $packageId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
