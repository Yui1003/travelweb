
<?php
require_once 'includes/auth.php';
require_once 'includes/db_connect.php';

if (isset($_GET['user_id'])) {
    $userId = (int)$_GET['user_id'];
    $currentUserId = $_SESSION['user_id'];

    // Get messages between the two users
    $sql = "SELECT m.*, 
            CASE 
                WHEN m.user_id = ? THEN u.full_name
                ELSE u.full_name
            END as sender_name,
            m.user_id as message_user_id
            FROM messages m
            LEFT JOIN users u ON m.user_id = u.id
            WHERE m.user_id = ? OR m.user_id = ? AND m.to_user_id = ? OR m.to_user_id = ?
            ORDER BY m.created_at ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiii", $currentUserId, $userId, $currentUserId, $currentUserId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = array();
    while ($row = $result->fetch_assoc()) {
        $messages[] = array(
            'id' => $row['id'],
            'user_id' => $row['message_user_id'],
            'message' => htmlspecialchars($row['message']),
            'created_at' => $row['created_at'],
            'sender_name' => $row['sender_name']
        );
    }

    header('Content-Type: application/json');
    echo json_encode($messages);
}
?>