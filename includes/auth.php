<?php
// Authentication functions

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Register a new user
function registerUser($conn, $username, $email, $password, $fullName, $phone = null, $address = null, $role = 'traveler') {
    // Check if username exists
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return ["success" => false, "message" => "Username already exists. Please choose another."];
    }
    
    // Check if email exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return ["success" => false, "message" => "Email already registered. Please use another email or login."];
    }
    
    // Get role ID based on role name
    $sql = "SELECT id FROM roles WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ["success" => false, "message" => "Invalid role specified."];
    }
    
    $roleRow = $result->fetch_assoc();
    $roleId = $roleRow['id'];
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user into database
    $sql = "INSERT INTO users (role_id, username, email, password, full_name, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $roleId, $username, $email, $hashedPassword, $fullName, $phone, $address);
    
    if ($stmt->execute()) {
        // Get the new user's ID
        $userId = $stmt->insert_id;
        
        // Set user session
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['full_name'] = $fullName;
        $_SESSION['role'] = $role;
        
        return ["success" => true, "message" => "Registration successful! Welcome to our travel platform."];
    } else {
        return ["success" => false, "message" => "Registration failed. Please try again later."];
    }
}

// Login an existing user
function loginUser($conn, $username, $password) {
    $sql = "SELECT u.id, u.username, u.password, u.full_name, r.name as role 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.username = ? OR u.email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ["success" => false, "message" => "User not found. Please check your username or email."];
    }
    
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        // Set user session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        
        return ["success" => true, "message" => "Login successful! Welcome back."];
    } else {
        return ["success" => false, "message" => "Incorrect password. Please try again."];
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current user info
function getCurrentUser() {
    if (isset($_SESSION['user_id'])) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'full_name' => $_SESSION['full_name'],
            'role' => $_SESSION['role']
        ];
    }
    return null;
}

// Check if user has specific role
function hasRole($role) {
    if (!isLoggedIn()) {
        return false;
    }
    
    return $_SESSION['role'] === $role;
}

// Check if user is an admin
function isAdmin() {
    return hasRole('admin');
}

// Check if user is a tour operator
function isOperator() {
    return hasRole('operator');
}

// Check if user is a traveler
function isTraveler() {
    return hasRole('traveler');
}

// Logout user
function logoutUser() {
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}

// Get user profile details
function getUserProfile($conn, $userId) {
    $sql = "SELECT u.id, u.username, u.email, u.full_name, u.phone, u.address, r.name as role, 
            u.created_at, u.profile_img 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return null;
    }
    
    return $result->fetch_assoc();
}

// Update user profile
function updateUserProfile($conn, $userId, $fullName, $phone, $address) {
    $sql = "UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $fullName, $phone, $address, $userId);
    
    return $stmt->execute();
}

// Update user password
function updateUserPassword($conn, $userId, $currentPassword, $newPassword) {
    // Verify current password
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ["success" => false, "message" => "User not found."];
    }
    
    $user = $result->fetch_assoc();
    
    if (!password_verify($currentPassword, $user['password'])) {
        return ["success" => false, "message" => "Current password is incorrect."];
    }
    
    // Hash and update new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashedPassword, $userId);
    
    if ($stmt->execute()) {
        return ["success" => true, "message" => "Password updated successfully."];
    } else {
        return ["success" => false, "message" => "Failed to update password. Please try again."];
    }
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

// Redirect if not an admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("Location: index.php");
        exit;
    }
}

// Redirect if not an operator
function requireOperator() {
    requireLogin();
    if (!isOperator() && !isAdmin()) {
        header("Location: index.php");
        exit;
    }
}
?>
