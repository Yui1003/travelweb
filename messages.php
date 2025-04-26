
<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/db_connect.php';
requireAdmin();

include 'includes/header.php';

// Handle message actions
if (isset($_POST['action'])) {
    $messageId = (int)$_POST['message_id'];

    if ($_POST['action'] === 'delete') {
        $sql = "DELETE FROM messages WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $messageId);
        $stmt->execute();
        echo "<script>window.location.href = 'messages.php';</script>";
        exit();
    } elseif ($_POST['action'] === 'mark_read') {
        $sql = "UPDATE messages SET status = 'read' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $messageId);
        $stmt->execute();
        echo "<script>window.location.href = 'messages.php';</script>";
        exit();
    }
}

// Handle reply submission
if (isset($_POST['send_reply'])) {
    $userId = $_SESSION['user_id'];
    $toUserId = (int)$_POST['to_user_id'];
    $subject = "Re: " . sanitizeInput($_POST['reply_subject']);
    $message = sanitizeInput($_POST['reply_message']);

    $sql = "INSERT INTO messages (user_id, to_user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissss", $userId, $toUserId, $_SESSION['full_name'], $_SESSION['email'], $subject, $message);
    
    if ($stmt->execute()) {
        echo "<script>
            alert('Reply sent successfully!');
            window.location.href = 'messages.php';
        </script>";
        exit();
    } else {
        echo "<script>alert('Error sending reply: " . $stmt->error . "');</script>";
    }
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$whereClause = $filter === 'unread' ? "WHERE status = 'unread'" : "";

$sql = "SELECT m.*, u.full_name as sender_name FROM messages m LEFT JOIN users u ON m.user_id = u.id $whereClause ORDER BY m.created_at DESC";
$result = $conn->query($sql);
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
?>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header" data-aos="fade-up">
            <h1>Messages</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Messages</li>
                </ol>
            </nav>
        </div>

        <div class="dashboard-card" data-aos="fade-up">
            <div class="dashboard-card-header d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-envelope me-2"></i>All Messages</h2>
                <div class="filter-buttons">
                    <a href="messages.php" class="btn btn-sm btn-<?php echo $filter === 'all' ? 'primary' : 'outline-primary'; ?>">All Messages</a>
                    <a href="messages.php?filter=unread" class="btn btn-sm btn-<?php echo $filter === 'unread' ? 'warning' : 'outline-warning'; ?>">Unread Messages</a>
                </div>
            </div>
            <div class="dashboard-card-body">
                <?php if (count($messages) > 0): ?>
                    <div class="message-list">
                        <?php foreach($messages as $message): ?>
                            <div class="message-item <?php echo $message['status'] === 'unread' ? 'unread' : ''; ?>">
                                <div class="message-header">
                                    <div class="message-sender">
                                        <strong><?php echo htmlspecialchars($message['sender_name'] ?? $message['name']); ?></strong>
                                        <span class="text-muted"><?php echo htmlspecialchars($message['email']); ?></span>
                                    </div>
                                    <div class="message-date">
                                        <?php echo date('M d, Y h:i A', strtotime($message['created_at'])); ?>
                                    </div>
                                </div>
                                <div class="message-subject">
                                    <strong>Subject:</strong> <?php echo htmlspecialchars($message['subject']); ?>
                                </div>
                                <div class="message-content">
                                    <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                </div>
                                <div class="message-actions mt-3">
                                    <button class="btn btn-sm btn-primary toggle-reply" data-message-id="<?php echo $message['id']; ?>">
                                        <i class="fas fa-reply"></i> Reply
                                    </button>
                                    <?php if ($message['status'] === 'unread'): ?>
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                            <input type="hidden" name="action" value="mark_read">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Mark as Read
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>

                                <div class="reply-form mt-3" id="replyForm<?php echo $message['id']; ?>" style="display: none;">
                                    <form method="post">
                                        <input type="hidden" name="to_user_id" value="<?php echo $message['user_id']; ?>">
                                        <input type="hidden" name="reply_subject" value="<?php echo htmlspecialchars($message['subject']); ?>">
                                        <div class="mb-3">
                                            <label for="reply_message<?php echo $message['id']; ?>" class="form-label">Your Reply</label>
                                            <textarea class="form-control" id="reply_message<?php echo $message['id']; ?>" name="reply_message" rows="3" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" name="send_reply" class="btn btn-primary">Send Reply</button>
                                            <button type="button" class="btn btn-secondary ms-2 cancel-reply" data-message-id="<?php echo $message['id']; ?>">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">No messages found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle reply form
    document.querySelectorAll('.toggle-reply').forEach(button => {
        button.addEventListener('click', function() {
            const messageId = this.dataset.messageId;
            const replyForm = document.getElementById('replyForm' + messageId);
            replyForm.style.display = 'block';
            this.style.display = 'none';
        });
    });

    // Cancel reply
    document.querySelectorAll('.cancel-reply').forEach(button => {
        button.addEventListener('click', function() {
            const messageId = this.dataset.messageId;
            const replyForm = document.getElementById('replyForm' + messageId);
            const replyButton = replyForm.previousElementSibling.querySelector('.toggle-reply');
            replyForm.style.display = 'none';
            replyButton.style.display = 'inline-block';
        });
    });
});
</script>

<style>
.message-list {
    max-height: none;
}

.message-item {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.message-item.unread {
    background: #f8f9fa;
    border-left: 4px solid #ffc107;
}

.message-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}

.message-sender {
    display: flex;
    flex-direction: column;
}

.message-date {
    color: #6c757d;
    font-size: 0.9em;
}

.message-subject {
    margin-bottom: 10px;
}

.message-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 15px;
    white-space: pre-wrap;
}

.message-actions {
    display: flex;
    gap: 10px;
}

.filter-buttons {
    gap: 10px;
    display: flex;
}
</style>

<?php include 'includes/footer.php'; ?>
