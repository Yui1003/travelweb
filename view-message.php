
<?php
require_once 'includes/auth.php';
requireAdmin();
include 'includes/header.php';

if (isset($_GET['id'])) {
    $messageId = (int)$_GET['id'];
    $sql = "SELECT m.*, u.full_name, u.email FROM messages m 
            LEFT JOIN users u ON m.user_id = u.id 
            WHERE m.id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $messageId);
    $stmt->execute();
    $result = $stmt->get_result();
    $message = $result->fetch_assoc();

    // Update message status to read
    $updateSql = "UPDATE messages SET status = 'read' WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("i", $messageId);
    $updateStmt->execute();
} else {
    header("Location: admin-dashboard.php");
    exit();
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply'])) {
    $reply = sanitizeInput($_POST['reply']);
    $adminId = $_SESSION['user_id'];
    
    $sql = "INSERT INTO message_replies (message_id, admin_id, reply) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $messageId, $adminId, $reply);
    
    if ($stmt->execute()) {
        $success = "Reply sent successfully!";
    } else {
        $error = "Failed to send reply.";
    }
}

// Get existing replies
$repliesSql = "SELECT mr.*, u.full_name FROM message_replies mr 
               JOIN users u ON mr.admin_id = u.id 
               WHERE mr.message_id = ? 
               ORDER BY mr.created_at ASC";
$repliesStmt = $conn->prepare($repliesSql);
$repliesStmt->bind_param("i", $messageId);
$repliesStmt->execute();
$replies = $repliesStmt->get_result();
?>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header">
            <h1>View Message</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="messages.php">Messages</a></li>
                    <li class="breadcrumb-item active">View Message</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Message Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>From:</h6>
                            <p><?php echo htmlspecialchars($message['name']); ?> (<?php echo htmlspecialchars($message['email']); ?>)</p>
                        </div>
                        <div class="mb-3">
                            <h6>Subject:</h6>
                            <p><?php echo htmlspecialchars($message['subject']); ?></p>
                        </div>
                        <div class="mb-3">
                            <h6>Message:</h6>
                            <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                        </div>
                        <div class="text-muted">
                            <small>Sent on: <?php echo date('M d, Y h:i A', strtotime($message['created_at'])); ?></small>
                        </div>
                    </div>
                </div>

                <!-- Replies Section -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Replies</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <!-- Display existing replies -->
                        <div class="replies-list mb-4">
                            <?php if ($replies->num_rows > 0): ?>
                                <?php while ($reply = $replies->fetch_assoc()): ?>
                                    <div class="reply-item mb-3 p-3 border rounded">
                                        <div class="reply-header d-flex justify-content-between mb-2">
                                            <strong><?php echo htmlspecialchars($reply['full_name']); ?></strong>
                                            <small class="text-muted">
                                                <?php echo date('M d, Y h:i A', strtotime($reply['created_at'])); ?>
                                            </small>
                                        </div>
                                        <div class="reply-content">
                                            <?php echo nl2br(htmlspecialchars($reply['reply'])); ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-muted">No replies yet.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Reply Form -->
                        <form method="post" class="reply-form">
                            <div class="mb-3">
                                <label for="reply" class="form-label">Your Reply</label>
                                <textarea class="form-control" id="reply" name="reply" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Reply</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="messages.php" class="btn btn-secondary w-100 mb-2">Back to Messages</a>
                        <a href="delete-message.php?id=<?php echo $messageId; ?>" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this message?')">Delete Message</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
