
<?php
require_once 'includes/auth.php';
require_once 'includes/db_connect.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookingId = (int)$_POST['booking_id'];
    $paymentMethod = $_POST['payment_method'];
    
    // Verify booking belongs to user
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $bookingId, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Invalid booking";
        header('Location: traveler-dashboard.php');
        exit();
    }
    
    // Simulate payment processing
    // In reality, this would integrate with a payment gateway
    sleep(1); // Simulate processing time
    
    // Update booking payment status
    $stmt = $conn->prepare("UPDATE bookings SET payment_status = 'paid', payment_method = ? WHERE id = ?");
    $stmt->bind_param("si", $paymentMethod, $bookingId);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Payment processed successfully!";
    } else {
        $_SESSION['error'] = "Payment processing failed. Please try again.";
    }
    
    header('Location: traveler-dashboard.php');
    exit();
}

header('Location: traveler-dashboard.php');
exit();
