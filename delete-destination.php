
<?php
require_once 'includes/auth.php';
requireAdmin();
require_once 'includes/db_connect.php';

if (isset($_GET['id'])) {
    $destinationId = (int)$_GET['id'];
    
    // Delete the destination
    $sql = "DELETE FROM destinations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $destinationId);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Destination deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting destination.";
    }
}

header("Location: admin-dashboard.php");
exit;
?>
