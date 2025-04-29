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
    $userId = (int)$_POST['user_id']; // Added to get user ID for messaging
    $updateSql = "UPDATE bookings SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $newStatus, $bookingId);
    if ($stmt->execute()) {
        // Send message if status is confirmed
        if ($newStatus === 'confirmed') {
            sendMessage($userId, $bookingId); // Assumes sendMessage function exists
        }
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
                                <th>Payment Method</th>
                                <th>Receipt</th>
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
                                <td><?php echo htmlspecialchars($booking['payment_method'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php if(!empty($booking['payment_proof'])): ?>
                                        <a href="uploads/receipts/<?php echo basename($booking['payment_proof']); ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-receipt"></i> View
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No receipt</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $booking['status'] == 'confirmed' ? 'success' : 
                                            ($booking['status'] == 'pending' ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <div class="btn-group">
                                        <a href="view-booking.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-info me-2">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <select class="form-select form-select-sm booking-status-select" style="width: auto; min-width: 100px;"
                                                data-booking-id="<?php echo $booking['id']; ?>"
                                                data-user-id="<?php echo $booking['user_id']; ?>"
                                                data-current-status="<?php echo $booking['status']; ?>">
                                            <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        </select>
                                        <span class="status-update-spinner d-none"><i class="fas fa-spinner fa-spin"></i></span>
                                        <a href="bookings.php?delete_booking=<?php echo $booking['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this booking?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
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
    // Handle status updates
    const statusSelects = document.querySelectorAll('.booking-status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', async function() {
            const bookingId = this.dataset.bookingId;
            const userId = this.dataset.userId;
            const newStatus = this.value;
            const currentStatus = this.dataset.currentStatus;
            const spinner = this.nextElementSibling;
            const statusBadge = this.closest('tr').querySelector('.badge');

            try {
                spinner.classList.remove('d-none');
                const response = await fetch('update_booking_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `booking_id=${bookingId}&status=${newStatus}&user_id=${userId}`
                });

                const data = await response.json();
                
                if (data.success) {
                    // Update the data attribute
                    this.dataset.currentStatus = newStatus;
                    
                    // Update the badge
                    statusBadge.textContent = ucfirst(newStatus);
                    statusBadge.className = 'badge ' + getBadgeClass(newStatus);
                    
                    // Show success message
                    showToast('Status updated successfully', 'success');
                } else {
                    throw new Error(data.message || 'Failed to update status');
                }
            } catch (error) {
                console.error('Error:', error);
                this.value = currentStatus;
                showToast('Failed to update status', 'error');
            } finally {
                spinner.classList.add('d-none');
            }
        });
    });

    // Add toast container if it doesn't exist
    if (!document.getElementById('toast-container')) {
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 1000;';
        document.body.appendChild(toastContainer);
    }

    // Update view booking links
    document.querySelectorAll('.btn-info[href^="view-booking.php"]').forEach(btn => {
        if (!btn.querySelector('i')) {
            const icon = document.createElement('i');
            icon.className = 'fas fa-eye';
            btn.innerHTML = '';
            btn.appendChild(icon);
            btn.title = 'View Booking Details';
        }
    });
});

function getBadgeClass(status) {
    switch (status) {
        case 'confirmed':
            return 'bg-success';
        case 'pending':
            return 'bg-warning';
        case 'cancelled':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} m-0 mb-2`;
    toast.style.minWidth = '200px';
    toast.textContent = message;
    
    const container = document.getElementById('toast-container');
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}
</script>