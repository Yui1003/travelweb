
<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $packageId = (int)$_POST['package_id'];
    $rating = (int)$_POST['rating'];
    $comment = sanitizeInput($_POST['comment']);

    // Verify user has a confirmed booking for this package
    $stmt = $conn->prepare("
        SELECT 1 FROM bookings 
        WHERE user_id = ? AND package_id = ? AND status = 'confirmed'
    ");
    $stmt->bind_param("ii", $userId, $packageId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Check if user has already reviewed this package
        $stmt = $conn->prepare("
            SELECT 1 FROM reviews 
            WHERE user_id = ? AND package_id = ?
        ");
        $stmt->bind_param("ii", $userId, $packageId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Insert the review
            $stmt = $conn->prepare("
                INSERT INTO reviews (user_id, package_id, rating, comment) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("iiis", $userId, $packageId, $rating, $comment);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Your review has been submitted successfully!";
            } else {
                $_SESSION['error_message'] = "Error submitting review. Please try again.";
            }
        } else {
            $_SESSION['error_message'] = "You have already reviewed this package.";
        }
    } else {
        $_SESSION['error_message'] = "You can only review packages from confirmed bookings.";
    }
}

header('Location: traveler-dashboard.php#reviews');
exit();
?>
