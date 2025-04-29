<?php
// Include database connection
include 'includes/db_connect.php';

// Generate correct password hashes
$adminPassword = 'admin1234';
$operatorPassword = 'ope1234';

$adminHash = password_hash($adminPassword, PASSWORD_DEFAULT);
$operatorHash = password_hash($operatorPassword, PASSWORD_DEFAULT);

echo "Generated password hashes:<br>";
echo "Admin hash: " . $adminHash . "<br>";
echo "Operator hash: " . $operatorHash . "<br><br>";

// Update admin password
$sqlAdmin = "UPDATE users SET password = ? WHERE username = 'admin'";
$stmtAdmin = $conn->prepare($sqlAdmin);
$stmtAdmin->bind_param("s", $adminHash);
$resultAdmin = $stmtAdmin->execute();

echo "Admin password update: " . ($resultAdmin ? "Success" : "Failed - " . $conn->error) . "<br>";

// Update operator password
$sqlOperator = "UPDATE users SET password = ? WHERE username = 'operator'";
$stmtOperator = $conn->prepare($sqlOperator);
$stmtOperator->bind_param("s", $operatorHash);
$resultOperator = $stmtOperator->execute();

echo "Operator password update: " . ($resultOperator ? "Success" : "Failed - " . $conn->error) . "<br>";

// Check if users exist
$sqlCheck = "SELECT u.username, r.name as role FROM users u JOIN roles r ON u.role_id = r.id";
$result = $conn->query($sqlCheck);

echo "<br>Current users in database:<br>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Username: " . $row["username"] . ", Role: " . $row["role"] . "<br>";
    }
} else {
    echo "No users found in database<br>";
}

$conn->close();
?>