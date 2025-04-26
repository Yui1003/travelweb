
<?php
require_once 'includes/auth.php';
requireAdmin(); // Only admin can access all bookings
include 'includes/header.php';

// Get all bookings with related information
$sql = "SELECT b.*, u.full_name, p.title as package_title, p.price 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        JOIN packages p ON b.package_id = p.id 
        ORDER BY b.booking_date DESC";
$result = $conn->query($sql);

// Handle status update
if (isset($_POST['update_status'])) {
    $bookingId = (int)$_POST['booking_id'];
    $newStatus = $_POST['status'];
    $updateSql = "UPDATE bookings SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $newStatus, $bookingId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
        exit;
    }
    echo json_encode(['success' => false]);
    exit;
}


// Handle booking deletion
if (isset($_GET['delete_booking'])) {
    $bookingId = (int)$_GET['delete_booking'];
    $deleteSql = "DELETE FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $bookingId);
    if ($stmt->execute()) {
        header("Location: bookings.php?success=Booking+deleted+successfully");
        exit();
    } else {
        $error = "Failed to delete booking";
    }
}
?>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header" data-aos="fade-up">
            <h1>All Bookings</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Bookings</li>
                </ol>
            </nav>
        </div>

        <div class="dashboard-card" data-aos="fade-up">
            <div class="dashboard-card-header">
                <h2><i class="fas fa-ticket-alt me-2"></i>Booking Management</h2>
            </div>
            <div class="dashboard-card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Package</th>
                                <th>Travel Date</th>
                                <th>Travelers</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($booking = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $booking['id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['package_title']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($booking['travel_date'])); ?></td>
                                <td><?php echo $booking['num_travelers']; ?></td>
                                <td><?php echo formatCurrency($booking['total_price']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $booking['status'] == 'confirmed' ? 'success' : 
                                            ($booking['status'] == 'pending' ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="view-booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <select class="form-select form-select-sm d-inline-block w-auto me-2 booking-status-select" 
                                            data-booking-id="<?php echo $booking['id']; ?>"
                                            data-current-status="<?php echo $booking['status']; ?>">
                                        <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        <option value="paid" <?php echo $booking['status'] == 'paid' ? 'selected' : ''; ?>>Paid</option>
                                        <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    </select>
                                    <a href="bookings.php?delete_booking=<?php echo $booking['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this booking?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('.booking-status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const bookingId = this.dataset.bookingId;
            const newStatus = this.value;
            const currentStatus = this.dataset.currentStatus;
            
            const confirmChange = confirm(`Are you sure you want to change the status from "${currentStatus}" to "${newStatus}"?`);
            
            if (confirmChange) {
                fetch('bookings.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `update_status=1&booking_id=${bookingId}&status=${newStatus}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to update status. Please try again.');
                    }
                });
            } else {
                // Reset the select to the previous value if user cancels
                this.value = currentStatus;
            }
        });
    });
});
</script>
