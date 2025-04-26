
<?php
include 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["proof_of_payment"])) {
    $target_dir = "uploads/receipts/";
    $file_extension = strtolower(pathinfo($_FILES["proof_of_payment"]["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Check if file is an actual image
    if(getimagesize($_FILES["proof_of_payment"]["tmp_name"]) !== false) {
        if (move_uploaded_file($_FILES["proof_of_payment"]["tmp_name"], $target_file)) {
            // Update database with file path
            $booking_id = $_POST['booking_id'];
            $sql = "UPDATE bookings SET payment_proof = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $target_file, $booking_id);
            
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Receipt uploaded successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error updating database"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Error uploading file"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "File is not an image"]);
    }
}
?>
