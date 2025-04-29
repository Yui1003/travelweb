<?php
require_once 'includes/auth.php';
requireAdmin();
include 'includes/header.php';

// Get selected role filter
$roleFilter = isset($_GET['role']) ? $_GET['role'] : '';

// Get all users with role filter if set
$sql = "SELECT u.*, r.name AS role FROM users u JOIN roles r ON u.role_id = r.id";
if (!empty($roleFilter)) {
    $sql .= " WHERE r.name = ?";
}
$sql .= " ORDER BY u.created_at DESC";

if (!empty($roleFilter)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $roleFilter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

// Handle user deletion
if (isset($_GET['delete_user_id'])) {
    $userId = (int)$_GET['delete_user_id'];

    // Don't allow deleting the main admin account
    if ($userId === 1) {
        header("Location: users.php?error=Cannot+delete+main+admin+account");
        exit;
    }

    $deleteSql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        header("Location: users.php?success=User+deleted+successfully");
        exit;
    } else {
        header("Location: users.php?error=Failed+to+delete+user");
        exit;
    }
}

// Get all roles for filter dropdown
$rolesQuery = "SELECT DISTINCT name FROM roles ORDER BY name";
$rolesResult = $conn->query($rolesQuery);
?>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header" data-aos="fade-up">
            <h1>User Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </nav>
        </div>

        <div class="dashboard-card" data-aos="fade-up">
            <div class="dashboard-card-header d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-users me-2"></i>All Users</h2>
                <div class="filter-section">
                    <form method="get" class="d-flex align-items-center">
                        <label for="role" class="me-2">Filter by Role:</label>
                        <select name="role" id="role" class="form-select" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            <?php while($role = $rolesResult->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($role['name']); ?>" 
                                    <?php echo $roleFilter === $role['name'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($role['name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="dashboard-card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $user['status'] == 'active' ? 'success' : 'danger'; ?>">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="users.php?delete_user_id=<?php echo $user['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this user?');" 
                                       class="btn btn-sm btn-danger">
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