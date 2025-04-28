
<?php
require_once 'includes/auth.php';
requireAdmin();
include 'includes/header.php';

$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';

// Get user data
$sql = "SELECT u.*, r.name as role FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header("Location: users.php");
    exit;
}

// Get all roles
$rolesQuery = "SELECT * FROM roles ORDER BY name";
$roles = $conn->query($rolesQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'change_password') {
        // Handle password change
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword === $confirmPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updatePwdSql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($updatePwdSql);
            $stmt->bind_param("si", $hashedPassword, $userId);
            
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Password updated successfully.</div>';
            } else {
                $message = '<div class="alert alert-danger">Error updating password.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">Passwords do not match.</div>';
        }
    } else {
        // Handle user details update
        $fullName = sanitizeInput($_POST['full_name']);
        $email = sanitizeInput($_POST['email']);
        $roleId = (int)$_POST['role_id'];
        $status = sanitizeInput($_POST['status']);
        
        $updateSql = "UPDATE users SET full_name = ?, email = ?, role_id = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ssisi", $fullName, $email, $roleId, $status, $userId);
        
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">User updated successfully.</div>';
            // Refresh user data
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
        } else {
            $message = '<div class="alert alert-danger">Error updating user.</div>';
        }
    }
}
?>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header" data-aos="fade-up">
            <h1>Edit User</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="users.php">Users</a></li>
                    <li class="breadcrumb-item active">Edit User</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="dashboard-card" data-aos="fade-up">
                    <div class="dashboard-card-header">
                        <h2><i class="fas fa-user-edit me-2"></i>Edit User Details</h2>
                    </div>
                    <div class="dashboard-card-body">
                        <?php echo $message; ?>
                        
                        <form method="post" action="edit-user.php?id=<?php echo $userId; ?>">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="role_id" class="form-label">Role</label>
                                <select class="form-control" id="role_id" name="role_id" required>
                                    <?php while($role = $roles->fetch_assoc()): ?>
                                    <option value="<?php echo $role['id']; ?>" 
                                            <?php echo $role['name'] === $user['role'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($role['name']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="banned" <?php echo $user['status'] === 'banned' ? 'selected' : ''; ?>>Banned</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="users.php" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="dashboard-card" data-aos="fade-up">
                    <div class="dashboard-card-header">
                        <h2><i class="fas fa-key me-2"></i>Change Password</h2>
                    </div>
                    <div class="dashboard-card-body">
                        <form method="post" action="edit-user.php?id=<?php echo $userId; ?>">
                            <input type="hidden" name="action" value="change_password">
                            
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
