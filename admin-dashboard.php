<?php
// Include header
include 'includes/header.php';

// Require admin access
requireAdmin();

// Get statistics
$totalUsers = getTotalUsers($conn);
$totalBookings = getTotalBookings($conn);
$totalPackages = getTotalPackages($conn);
$totalDestinations = getTotalDestinations($conn);
$pendingMessages = getTotalPendingMessages($conn);

// Get recent bookings
$recentBookings = getRecentBookings($conn, 5);

// Get unread messages
$unreadMessages = getUnreadMessages($conn, 5);

// Get popular packages
$popularPackages = getPopularPackages($conn, 5);

// Process message marking as read
if (isset($_GET['mark_read']) && isset($_GET['message_id'])) {
    $messageId = (int)$_GET['message_id'];
    markMessageAsRead($conn, $messageId);
    header("Location: admin-dashboard.php#messages");
    exit;
}
?>

<!-- Page Content -->
<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header" data-aos="fade-up">
            <h1>Admin Dashboard</h1>
            <p>Welcome back, <?php echo $_SESSION['full_name']; ?>!</p>
        </div>
        
        <!-- Statistics Cards -->
        <div class="dashboard-stats" data-aos="fade-up">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $totalUsers; ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $totalBookings; ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-suitcase"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $totalPackages; ?></h3>
                    <p>Tour Packages</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $totalDestinations; ?></h3>
                    <p>Destinations</p>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <!-- Recent Bookings -->
            <div class="col-lg-7" data-aos="fade-up">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h2><i class="fas fa-clipboard-list me-2"></i>Recent Bookings</h2>
                    </div>
                    <div class="dashboard-card-body">
                        <?php if (count($recentBookings) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Customer</th>
                                        <th>Package</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recentBookings as $booking): ?>
                                    <tr>
                                        <td>#<?php echo $booking['id']; ?></td>
                                        <td><?php echo $booking['full_name']; ?></td>
                                        <td><?php echo $booking['package_title']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                                        <td><?php echo formatCurrency($booking['total_price']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $booking['payment_status'] == 'paid' ? 'success' : 
                                                    ($booking['payment_status'] == 'pending' ? 'warning' : 
                                                    ($booking['payment_status'] == 'cancelled' ? 'danger' : 'info')); 
                                            ?>">
                                                <?php echo ucfirst($booking['payment_status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3">
                            <a href="bookings.php" class="btn btn-sm btn-outline-primary">View All Bookings</a>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">No recent bookings found.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Messages -->
            <div class="col-lg-5" data-aos="fade-up" id="messages">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h2><i class="fas fa-envelope me-2"></i>Unread Messages <span class="badge bg-danger"><?php echo $pendingMessages; ?></span></h2>
                    </div>
                    <div class="dashboard-card-body">
                        <?php if (count($unreadMessages) > 0): ?>
                        <div class="message-list">
                            <?php foreach($unreadMessages as $message): ?>
                            <div class="message-item">
                                <div class="message-header">
                                    <div class="message-sender">
                                        <strong><?php echo $message['name']; ?></strong>
                                        <span class="text-muted"><?php echo $message['email']; ?></span>
                                    </div>
                                    <div class="message-date">
                                        <?php echo date('M d, Y', strtotime($message['created_at'])); ?>
                                    </div>
                                </div>
                                <div class="message-subject">
                                    <strong>Subject:</strong> <?php echo $message['subject']; ?>
                                </div>
                                <div class="message-preview">
                                    <?php echo substr($message['message'], 0, 100); ?>...
                                </div>
                                <div class="message-actions">
                                    <a href="view-message.php?id=<?php echo $message['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                    <a href="admin-dashboard.php?mark_read=1&message_id=<?php echo $message['id']; ?>" class="btn btn-sm btn-outline-success">Mark as Read</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-end mt-3">
                            <a href="messages.php" class="btn btn-sm btn-outline-primary">View All Messages</a>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">No unread messages.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <!-- Popular Packages -->
            <div class="col-md-6" data-aos="fade-up">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h2><i class="fas fa-fire me-2"></i>Popular Packages</h2>
                    </div>
                    <div class="dashboard-card-body">
                        <?php if (count($popularPackages) > 0): ?>
                        <div class="list-group">
                            <?php foreach($popularPackages as $package): ?>
                            <a href="package-details.php?id=<?php echo $package['id']; ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?php echo $package['title']; ?></h5>
                                    <span class="badge bg-primary rounded-pill"><?php echo $package['bookings_count']; ?> bookings</span>
                                </div>
                                <p class="mb-1"><?php echo substr($package['description'], 0, 100); ?>...</p>
                                <small class="text-muted"><?php echo formatCurrency($package['price']); ?> / person</small>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-end mt-3">
                            <a href="packages.php" class="btn btn-sm btn-outline-primary">Manage Packages</a>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">No package data available.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="col-md-6" data-aos="fade-up">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <h2><i class="fas fa-bolt me-2"></i>Quick Actions</h2>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="add-package.php" class="btn btn-outline-primary w-100 p-3">
                                    <i class="fas fa-plus-circle mb-2 d-block fs-4"></i>
                                    Add New Package
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="add-destination.php" class="btn btn-outline-primary w-100 p-3">
                                    <i class="fas fa-map-marked-alt mb-2 d-block fs-4"></i>
                                    Add New Destination
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="users.php" class="btn btn-outline-primary w-100 p-3">
                                    <i class="fas fa-user-cog mb-2 d-block fs-4"></i>
                                    Manage Users
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="reports.php" class="btn btn-outline-primary w-100 p-3">
                                    <i class="fas fa-chart-line mb-2 d-block fs-4"></i>
                                    View Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional styles for the admin dashboard -->
<style>
.message-list {
    max-height: 500px;
    overflow-y: auto;
}

.message-item {
    padding: 15px;
    border-bottom: 1px solid var(--border-color);
}

.message-item:last-child {
    border-bottom: none;
}

.message-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.message-sender {
    display: flex;
    flex-direction: column;
}

.message-subject {
    margin-bottom: 5px;
}

.message-preview {
    color: #6c757d;
    margin-bottom: 10px;
}

.message-actions {
    display: flex;
    gap: 10px;
}
</style>

<?php
// Include footer
include 'includes/footer.php';

// Helper functions for the admin dashboard
function getTotalUsers($conn) {
    $sql = "SELECT COUNT(*) as total FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getTotalBookings($conn) {
    $sql = "SELECT COUNT(*) as total FROM bookings";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getTotalPackages($conn) {
    $sql = "SELECT COUNT(*) as total FROM packages";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getTotalDestinations($conn) {
    $sql = "SELECT COUNT(*) as total FROM destinations";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getTotalPendingMessages($conn) {
    $sql = "SELECT COUNT(*) as total FROM messages WHERE status = 'unread'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getRecentBookings($conn, $limit) {
    $sql = "SELECT b.*, u.full_name, p.title as package_title 
            FROM bookings b 
            JOIN users u ON b.user_id = u.id 
            JOIN packages p ON b.package_id = p.id 
            ORDER BY b.created_at DESC 
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = [];
    
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    
    return $bookings;
}

function getUnreadMessages($conn, $limit) {
    $sql = "SELECT * FROM messages WHERE status = 'unread' ORDER BY created_at DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = [];
    
    while($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    
    return $messages;
}

function getPopularPackages($conn, $limit) {
    $sql = "SELECT p.*, COUNT(b.id) as bookings_count 
            FROM packages p 
            LEFT JOIN bookings b ON p.id = b.package_id 
            GROUP BY p.id 
            ORDER BY bookings_count DESC 
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $packages = [];
    
    while($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
    
    return $packages;
}

function markMessageAsRead($conn, $messageId) {
    $sql = "UPDATE messages SET status = 'read' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $messageId);
    return $stmt->execute();
}
?>