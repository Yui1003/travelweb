
<?php
require_once 'includes/auth.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $userId = (int)$_POST['user_id'];
    $fullName = sanitizeInput($_POST['full_name']);
    $email = sanitizeInput($_POST['email']);
    $roleId = (int)$_POST['role_id'];
    
    $updateSql = "UPDATE users SET full_name = ?, email = ?, role_id = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssii", $fullName, $email, $roleId, $userId);
    
    if ($stmt->execute()) {
        header("Location: users.php?success=1");
    } else {
        header("Location: users.php?error=1");
    }
    exit;
}

header("Location: users.php");
exit;
?>
