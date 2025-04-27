<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/db_connect.php';
requireAdmin();

include 'includes/header.php';

// Handle reply submission
if (isset($_POST['send_reply'])) {
    $userId = $_SESSION['user_id'];
    $toUserId = (int)$_POST['to_user_id'];
    $message = sanitizeInput($_POST['reply_message']);

    $sql = "INSERT INTO messages (user_id, to_user_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $userId, $toUserId, $message);

    if ($stmt->execute()) {
        echo "<script>window.location.href = 'messages.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error sending reply: " . $stmt->error . "');</script>";
    }
}

// Get all users who have messages
$sql = "SELECT DISTINCT u.id, u.full_name, u.email 
        FROM users u 
        LEFT JOIN messages m ON (m.user_id = u.id OR m.to_user_id = u.id) 
        WHERE u.id != ? AND u.role_id != (SELECT id FROM roles WHERE name = 'admin')
        ORDER BY u.full_name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$usersResult = $stmt->get_result();
?>

<section class="dashboard-section">
    <div class="container-fluid">
        <div class="dashboard-header" data-aos="fade-up">
            <h1>Messages</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Messages</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card users-list">
                    <div class="card-header">
                        <h5 class="mb-0">Conversations</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php while ($user = $usersResult->fetch_assoc()): ?>
                            <a href="#" class="list-group-item list-group-item-action user-chat" 
                               data-user-id="<?php echo $user['id']; ?>"
                               data-user-name="<?php echo htmlspecialchars($user['full_name']); ?>">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar">
                                            <?php echo substr($user['full_name'], 0, 1); ?>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0"><?php echo htmlspecialchars($user['full_name']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                                    </div>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card chat-container">
                    <div class="card-header chat-header d-none">
                        <h5 class="mb-0 selected-user-name"></h5>
                    </div>
                    <div class="card-body chat-messages">
                        <div class="select-chat-prompt">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <h4>Select a conversation to start messaging</h4>
                        </div>
                    </div>
                    <div class="card-footer chat-input d-none">
                        <form class="reply-form" method="post">
                            <input type="hidden" name="to_user_id" class="reply-to-user-id">
                            <div class="input-group">
                                <textarea class="form-control" name="reply_message" rows="1" placeholder="Type your message..."></textarea>
                                <button type="submit" name="send_reply" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.users-list {
    height: calc(100vh - 200px);
    overflow-y: auto;
}

.avatar {
    width: 40px;
    height: 40px;
    background-color: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.chat-container {
    height: calc(100vh - 200px);
    display: flex;
    flex-direction: column;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
}

.select-chat-prompt {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.message-bubble {
    max-width: 75%;
    margin-bottom: 1rem;
    padding: 0.75rem;
    border-radius: 1rem;
    position: relative;
}

.message-bubble.sent {
    margin-left: auto;
    background-color: #007bff;
    color: white;
    border-bottom-right-radius: 0.25rem;
}

.message-bubble.received {
    margin-right: auto;
    background-color: #f8f9fa;
    border-bottom-left-radius: 0.25rem;
}

.message-time {
    font-size: 0.75rem;
    margin-top: 0.25rem;
    opacity: 0.8;
}

.chat-input {
    padding: 1rem;
    background-color: #f8f9fa;
}

.chat-input textarea {
    resize: none;
    border-radius: 1.5rem;
    padding-right: 4rem;
}

.chat-input .btn {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    border-radius: 50%;
    width: 2.5rem;
    height: 2.5rem;
    margin: auto 0.5rem;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userChats = document.querySelectorAll('.user-chat');
    const chatMessages = document.querySelector('.chat-messages');
    const chatHeader = document.querySelector('.chat-header');
    const chatInput = document.querySelector('.chat-input');
    const selectChatPrompt = document.querySelector('.select-chat-prompt');

    function loadMessages(userId) {
        fetch(`get_messages.php?user_id=${userId}`)
            .then(response => response.json())
            .then(messages => {
                chatMessages.innerHTML = messages.map(msg => `
                    <div class="message-bubble ${msg.user_id === <?php echo $_SESSION['user_id']; ?> ? 'sent' : 'received'}">
                        <div class="message-sender">${msg.sender_name}</div>
                        <div class="message-content">${msg.message}</div>
                        <div class="message-time">${new Date(msg.created_at).toLocaleString()}</div>
                    </div>
                `).join('');
                chatMessages.scrollTop = chatMessages.scrollHeight;
            })
            .catch(error => console.error('Error:', error));
    }

    userChats.forEach(chat => {
        chat.addEventListener('click', function(e) {
            e.preventDefault();

            userChats.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            const userId = this.dataset.userId;
            const userName = this.dataset.userName;

            chatHeader.classList.remove('d-none');
            chatInput.classList.remove('d-none');
            selectChatPrompt.style.display = 'none';

            document.querySelector('.selected-user-name').textContent = userName;
            document.querySelector('.reply-to-user-id').value = userId;

            loadMessages(userId);
        });
    });

    // Auto-refresh messages every 5 seconds
    setInterval(() => {
        const activeChat = document.querySelector('.user-chat.active');
        if (activeChat) {
            loadMessages(activeChat.dataset.userId);
        }
    }, 5000);
});
</script>

<?php include 'includes/footer.php'; ?>