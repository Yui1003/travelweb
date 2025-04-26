<?php
require_once 'includes/auth.php';
requireAdmin(); // Ensure only admins can access this page
include 'includes/header.php';

// Include the database connection file
require_once 'includes/db_connect.php'; // Ensure this line is added to establish a database connection

// Assuming user_id is being passed from the URL
if (isset($_GET['delete_user_id'])) {
    $userId = (int)$_GET['delete_user_id'];

    // Check for associated bookings
    $bookingCheckSql = "SELECT COUNT(*) as count FROM bookings WHERE user_id = ?";
    $stmt = $conn->prepare($bookingCheckSql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookingCount = $result->fetch_assoc()['count'];

    if ($bookingCount > 0) {
        // User has bookings, cannot delete
        echo '<div class="alert alert-danger">You need to delete the bookings first before deleting the account.</div>';
    } else {
        // No bookings, proceed to delete user
        $deleteUserSql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($deleteUserSql);
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            header("Location: users.php?success=User+deleted+successfully");
            exit();
        } else {
            echo "Error deleting user.";
        }
    }
}

// Get all users
$sql = "SELECT u.*, r.name AS role FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC";
$result = $conn->query($sql);
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
            <div class="dashboard-card-header">
                <h2><i class="fas fa-users me-2"></i>All Users</h2>
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
                                    <a href="users.php?delete_user_id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="btn btn-sm btn-danger">
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