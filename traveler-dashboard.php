
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
                                            <div class="booking-status <?php echo $booking['status']; ?>">
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
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
