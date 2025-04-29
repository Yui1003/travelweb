
<?php
require_once 'includes/auth.php';
require_once 'includes/db_connect.php';

if (isset($_GET['user_id'])) {
    $userId = (int)$_GET['user_id'];
    
    // Delete all messages between the user and admin
    $sql = "DELETE FROM messages WHERE (user_id = ? OR to_user_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $userId);
    
    if ($stmt->execute()) {
        echo "Conversation deleted successfully";
    } else {
        http_response_code(500);
        echo "Error deleting conversation";
    }
} else {
    http_response_code(400);
    echo "User ID not provided";
}
