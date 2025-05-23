<?php
session_start();

// Include required files
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user profile
$user = getUserProfile($conn, $_SESSION['user_id']);
$message = '';

// Get user bookings
$bookings = [];
try {
    if (isset($_SESSION['user_id'])) {
        $stmt = $conn->prepare("SELECT b.*, p.title as package_title, d.name as destination_name, 
                               b.confirmation_number, b.travel_date, b.num_travelers, b.total_price, b.status,
                               b.special_requests 
                               FROM bookings b 
                               JOIN packages p ON b.package_id = p.id 
                               JOIN destinations d ON p.destination_id = d.id 
                               WHERE b.user_id = ? 
                               ORDER BY b.booking_date DESC");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
} catch (Exception $e) {
    $message = '<div class="alert alert-danger">Error loading bookings. Please try again later.</div>';
}

// Handle booking cancellation
if (isset($_GET['cancel_booking'])) {
    $bookingId = (int)$_GET['cancel_booking'];
    $updateSql = "UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ii", $bookingId, $_SESSION['user_id']);
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Booking cancelled successfully.</div>';
    } else {
        $message = '<div class="alert alert-danger">Failed to cancel booking. Please try again.</div>';
    }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $fullName = sanitizeInput($_POST['full_name']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);

    if (updateUserProfile($conn, $_SESSION['user_id'], $fullName, $phone, $address)) {
        $message = '<div class="alert alert-success">Profile updated successfully.</div>';
        $user = getUserProfile($conn, $_SESSION['user_id']);
        $_SESSION['full_name'] = $fullName;
    } else {
        $message = '<div class="alert alert-danger">Failed to update profile. Please try again.</div>';
    }
}

// Handle password update
// Process message submission
// Handle message deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_all_messages'])) {
    $userId = $_SESSION['user_id'];
    $sql = "DELETE FROM messages WHERE user_id = ? OR to_user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $userId);
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">All messages deleted successfully.</div>';
    } else {
        $message = '<div class="alert alert-danger">Error deleting messages.</div>';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $subject = sanitizeInput($_POST['subject']);
    $messageText = sanitizeInput($_POST['message']);
    $userId = $_SESSION['user_id'];
    $userName = $_SESSION['full_name'];

    // Get user's email from the database
    $userStmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $userStmt->bind_param("i", $userId);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    $userEmail = $userResult->fetch_assoc()['email'];

    $sql = "INSERT INTO messages (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $userId, $userName, $userEmail, $subject, $messageText);

    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Message sent successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Failed to send message.</div>';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $message = '<div class="alert alert-danger">New password and confirm password do not match.</div>';
    } else {
        $result = updateUserPassword($conn, $_SESSION['user_id'], $currentPassword, $newPassword);
        if ($result['success']) {
            $message = '<div class="alert alert-success">' . $result['message'] . '</div>';
        } else {
            $message = '<div class="alert alert-danger">' . $result['message'] . '</div>';
        }
    }
}

// Include header
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="background-image: url('https://images.unsplash.com/photo-1550399504-8953e1a6ac87');">
    <div class="container">
        <div class="page-header-content">
            <h1>Traveler Dashboard</h1>
            <div class="page-header-breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-angle-right"></i>
                <span>Dashboard</span>
            </div>
        </div>
    </div>
</section>

<!-- Dashboard Section -->
<section class="dashboard">
    <div class="container">
        <?php echo $message; ?>

        <div class="row">
            <div class="col-lg-4">
                <!-- Sidebar -->
                <div class="dashboard-card" data-aos="fade-right">
                    <div class="text-center mb-4">
                        <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                        <p class="text-muted"><?php echo ucfirst($user['role']); ?></p>
                    </div>

                    <ul class="nav flex-column dashboard-tabs nav-pills mb-4" id="dashboardTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="bookings-tab" data-bs-toggle="tab" href="#bookings" role="tab" aria-controls="bookings" aria-selected="true">
                                <i class="fas fa-ticket-alt me-2"></i> My Bookings
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                <i class="fas fa-user me-2"></i> Profile Settings
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">
                                <i class="fas fa-lock me-2"></i> Change Password
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">
                                <i class="fas fa-star me-2"></i> Add Review
                            </a>
                        </li>
                    </ul>

                    <div class="d-grid">
                        <a href="logout.php" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Tab Content -->
                <div class="tab-content" id="dashboardTabContent">
                    <!-- Bookings Tab -->
                    <div class="tab-pane fade show active" id="bookings" role="tabpanel" aria-labelledby="bookings-tab">
                        <div class="dashboard-card" data-aos="fade-left">
                            <h3>My Bookings</h3>

                            <?php if (empty($bookings)): ?>
                                <div class="alert alert-info">
                                    You haven't made any bookings yet. <a href="destinations.php">Start exploring</a> to book your next adventure!
                                </div>
                            <?php else: ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <div class="booking-item">
                                        <div class="booking-header">
                                            <div class="booking-title">
                                                <h4><?php echo htmlspecialchars($booking['package_title']); ?></h4>
                                                <div class="text-muted">Booking ID: <?php echo $booking['confirmation_number']; ?></div>
                                            </div>
                                            <div class="booking-status <?php echo $booking['status']; ?> badge bg-<?php 
                                            echo $booking['status'] == 'paid' ? 'success' : 
                                                ($booking['status'] == 'confirmed' ? 'primary' : 
                                                ($booking['status'] == 'pending' ? 'warning' : 'danger')); 
                                            ?>">
                                                <?php echo ucfirst($booking['status']); ?>
                                            </div>
                                        </div>

                                        <div class="booking-details">
                                            <div class="booking-detail">
                                                <span>Destination</span>
                                                <p><?php echo htmlspecialchars($booking['destination_name']); ?></p>
                                            </div>
                                            <div class="booking-detail">
                                                <span>Travel Date</span>
                                                <p><?php echo $booking['travel_date']; ?></p>
                                            </div>
                                            <div class="booking-detail">
                                                <span>Travelers</span>
                                                <p><?php echo $booking['num_travelers']; ?> person(s)</p>
                                            </div>
                                            <div class="booking-detail">
                                                <span>Total Price</span>
                                                <p><?php echo $booking['total_price']; ?></p>
                                            </div>
                                        </div>

                                        <?php if (!empty($booking['special_requests'])): ?>
                                            <div class="booking-special-requests">
                                                <strong>Special Requests:</strong>
                                                <p><?php echo htmlspecialchars($booking['special_requests']); ?></p>
                                            </div>
                                        <?php endif; ?>

                                        <div class="booking-actions">
                                            <a href="package-details.php?id=<?php echo $booking['package_id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View Package
                                            </a>

                                            <?php if ($booking['status'] == 'pending' || $booking['status'] == 'confirmed'): ?>
                                                <a href="traveler-dashboard.php?cancel_booking=<?php echo $booking['id']; ?>" class="btn btn-sm btn-outline-danger cancel-booking-btn" data-id="<?php echo $booking['id']; ?>">
                                                    <i class="fas fa-times"></i> Cancel Booking
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Profile Tab -->
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="dashboard-card" data-aos="fade-left">
                            <h3>Profile Settings</h3>

                            <form method="post" action="traveler-dashboard.php" class="mt-4">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                                    <div class="form-text">Username cannot be changed.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                    <div class="form-text">Email cannot be changed.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                                </div>

                                <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>

                    <!-- Password Tab -->
                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <div class="dashboard-card" data-aos="fade-left">
                            <h3>Change Password</h3>

                            <form method="post" action="traveler-dashboard.php" class="mt-4 password-form">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        <span class="input-group-text">
                                            <i class="fas fa-eye password-toggle" role="button"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                                        <span class="input-group-text">
                                            <i class="fas fa-eye password-toggle" role="button"></i>
                                        </span>
                                    </div>
                                    <div class="form-text">Password must be at least 6 characters long.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        <span class="input-group-text">
                                            <i class="fas fa-eye password-toggle" role="button"></i>
                                        </span>
                                    </div>
                                </div>

                                <button type="submit" name="update_password" class="btn btn-primary">Change Password</button>
                            </form>
                        </div>
                    </div>


                    <!-- Reviews Tab -->
                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <div class="dashboard-card" data-aos="fade-left">
                            <h3>Add Review</h3>
                            <?php
                            // Get confirmed bookings
                            $stmt = $conn->prepare("
                                SELECT b.*, p.title as package_title 
                                FROM bookings b 
                                JOIN packages p ON b.package_id = p.id 
                                WHERE b.user_id = ? AND b.status = 'confirmed'
                                AND NOT EXISTS (
                                    SELECT 1 FROM reviews r 
                                    WHERE r.user_id = b.user_id 
                                    AND r.package_id = b.package_id
                                )
                            ");
                            $stmt->bind_param("i", $_SESSION['user_id']);
                            $stmt->execute();
                            $confirmedBookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                            
                            if (empty($confirmedBookings)): ?>
                                <div class="alert alert-info">
                                    You don't have any confirmed bookings available for review.
                                </div>
                            <?php else: ?>
                                <form action="add_review.php" method="POST" class="review-form">
                                    <div class="mb-3">
                                        <label for="package_id" class="form-label">Select Package</label>
                                        <select class="form-select" name="package_id" id="package_id" required>
                                            <option value="">Choose a package...</option>
                                            <?php foreach ($confirmedBookings as $booking): ?>
                                                <option value="<?php echo $booking['package_id']; ?>">
                                                    <?php echo htmlspecialchars($booking['package_title']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Rating</label>
                                        <div class="star-rating">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" required>
                                                <label for="star<?php echo $i; ?>"><i class="fas fa-star"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Your Review</label>
                                        <textarea class="form-control" name="comment" id="comment" rows="4" required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit Review</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <style>
                    .star-rating {
                        display: inline-flex;
                        flex-direction: row-reverse;
                        font-size: 1.5rem;
                    }
                    .star-rating input {
                        display: none;
                    }
                    .star-rating label {
                        cursor: pointer;
                        color: #ddd;
                        padding: 0 0.2em;
                    }
                    .star-rating label:hover,
                    .star-rating label:hover ~ label,
                    .star-rating input:checked ~ label {
                        color: #ffd700;
                    }
                    </style>
                </div>

                <!-- Message Admin Section -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Messages</h5>
                    </div>
                    <div class="card-body">
                        <div class="messages-container mb-4" style="height: 400px; overflow-y: auto;">
                            <?php
                            // Get conversation history
                            $sql = "SELECT m.*, COALESCE(u.full_name, 'Admin') as sender_name 
                                   FROM messages m 
                                   LEFT JOIN users u ON m.user_id = u.id 
                                   WHERE m.user_id = ? OR m.to_user_id = ?
                                   ORDER BY m.created_at ASC";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ii", $_SESSION['user_id'], $_SESSION['user_id']);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($msg = $result->fetch_assoc()):
                                $isOwn = $msg['user_id'] == $_SESSION['user_id'];
                            ?>
                                <div class="message-bubble <?php echo $isOwn ? 'own-message' : 'other-message'; ?> mb-3">
                                    <div class="message-header">
                                        <strong><?php echo htmlspecialchars($msg['sender_name']); ?></strong>
                                        <small class="text-muted"><?php echo date('M d, Y h:i A', strtotime($msg['created_at'])); ?></small>
                                    </div>
                                    <?php if (!empty($msg['subject'])): ?>
                                        <div class="message-subject">
                                            <em>Subject: <?php echo htmlspecialchars($msg['subject']); ?></em>
                                        </div>
                                    <?php endif; ?>
                                    <div class="message-content">
                                        <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete all messages?');">
                                <button type="submit" name="delete_all_messages" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete All Messages
                                </button>
                            </form>
                        </div>
                        <form id="messageForm" class="message-form">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject (optional)">
                            </div>
                            <div class="input-group">
                                <textarea class="form-control" id="message" name="message" rows="2" placeholder="Type your message..." required></textarea>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <style>
                .messages-container {
                    padding: 1rem;
                    background: #f8f9fa;
                    border-radius: 0.5rem;
                }
                .message-bubble {
                    max-width: 80%;
                    padding: 0.75rem;
                    border-radius: 1rem;
                    position: relative;
                }
                .own-message {
                    background: #0d6efd;
                    color: white;
                    margin-left: auto;
                    border-bottom-right-radius: 0.25rem;
                }
                .other-message {
                    background: white;
                    margin-right: auto;
                    border-bottom-left-radius: 0.25rem;
                    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
                }
                .own-message .text-muted {
                    color: rgba(255,255,255,0.7) !important;
                }
                .message-header {
                    font-size: 0.875rem;
                    margin-bottom: 0.5rem;
                }
                .message-subject {
                    font-size: 0.875rem;
                    margin-bottom: 0.5rem;
                }
                .message-content {
                    word-break: break-word;
                }
                .message-form .input-group {
                    border: 1px solid #dee2e6;
                    border-radius: 0.5rem;
                    overflow: hidden;
                }
                .message-form textarea {
                    border: none;
                    resize: none;
                }
                .message-form .btn {
                    border-radius: 0;
                }
                </style>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Message form handling
    const messageForm = document.getElementById('messageForm');
    const messagesContainer = document.querySelector('.messages-container');

    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('send_message', '1');

        fetch('traveler-dashboard.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            // Clear the form
            messageForm.reset();

            // Create a temporary container to parse the HTML
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;

            // Find the messages container in the response
            const newMessages = tempDiv.querySelector('.messages-container').innerHTML;

            // Update the messages
            messagesContainer.innerHTML = newMessages;

            // Scroll to bottom
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send message. Please try again.');
        });
    });
    // Password visibility toggle
    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const passwordInput = this.closest('.input-group').querySelector('input');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });

    // Booking cancellation confirmation
    const cancelButtons = document.querySelectorAll('.cancel-booking-btn');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                const bookingId = this.getAttribute('data-id');
                window.location.href = 'traveler-dashboard.php?cancel_booking=' + bookingId;
            }
        });
    });

    // Handle tab persistence with URL hash
    const hash = window.location.hash;
    if (hash) {
        const tab = document.querySelector(`a[href="${hash}"]`);
        if (tab) {
            tab.click();
        }
    }

    // Update URL hash when tab changes
    const tabLinks = document.querySelectorAll('.nav-link');
    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            window.location.hash = this.getAttribute('href');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>