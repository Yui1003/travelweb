<?php
require_once 'includes/auth.php';
requireAdmin();
include 'includes/header.php';

// Get booking details
if (isset($_GET['id'])) {
    $bookingId = (int)$_GET['id'];
    $sql = "SELECT b.*, u.full_name, u.email, u.phone, p.title as package_title, 
            d.name as destination_name, b.payment_method, b.reference_number, b.payment_proof
            FROM bookings b 
            JOIN users u ON b.user_id = u.id 
            JOIN packages p ON b.package_id = p.id 
            JOIN destinations d ON p.destination_id = d.id 
            WHERE b.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
} else {
    header("Location: bookings.php");
    exit();
}
?>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header" data-aos="fade-up">
            <h1>Booking Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="bookings.php">Bookings</a></li>
                    <li class="breadcrumb-item active">View Booking</li>
                </ol>
            </nav>
        </div>

        <div class="dashboard-card" data-aos="fade-up">
            <div class="booking-details">
                <h3>Booking #<?php echo $booking['id']; ?></h3>
                <div class="row">
                    <div class="col-md-6">
                        <h4>Customer Information</h4>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['full_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($booking['phone']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h4>Booking Information</h4>
                        <p><strong>Package:</strong> <?php echo htmlspecialchars($booking['package_title']); ?></p>
                        <p><strong>Destination:</strong> <?php echo htmlspecialchars($booking['destination_name']); ?></p>
                        <p><strong>Travel Date:</strong> <?php echo date('F d, Y', strtotime($booking['travel_date'])); ?></p>
                        <p><strong>Number of Travelers:</strong> <?php echo $booking['num_travelers']; ?></p>
                        <p><strong>Total Amount:</strong> <?php echo formatCurrency($booking['total_price']); ?></p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-<?php 
                                echo $booking['status'] == 'confirmed' ? 'success' : 
                                    ($booking['status'] == 'pending' ? 'warning' : 'danger'); 
                            ?>">
                                <?php echo ucfirst($booking['status']); ?>
                            </span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                            <h4>Payment Information</h4>
                            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($booking['payment_method'] ?? 'N/A'); ?></p>
                            <p><strong>Reference Number:</strong> <?php echo htmlspecialchars($booking['reference_number'] ?? 'N/A'); ?></p>
                            <?php if(!empty($booking['payment_proof'])): ?>
                            <p><strong>Payment Proof:</strong> <a href="<?php echo htmlspecialchars($booking['payment_proof']); ?>" target="_blank">View Receipt</a></p>
                            <?php endif; ?>
                        </div>
                <?php if (!empty($booking['special_requests'])): ?>
                <div class="mt-4">
                    <h4>Special Requests</h4>
                    <p><?php echo nl2br(htmlspecialchars($booking['special_requests'])); ?></p>
                </div>
                <?php endif; ?>
                <div class="mt-4">
                    <a href="bookings.php" class="btn btn-secondary">Back to Bookings</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>