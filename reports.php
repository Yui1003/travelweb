
<?php
require_once 'includes/auth.php';
requireAdmin();
include 'includes/header.php';

// Get booking statistics
$sql = "SELECT 
            COUNT(*) as total_bookings,
            SUM(total_price) as total_revenue,
            COUNT(DISTINCT user_id) as unique_customers
        FROM bookings 
        WHERE status = 'confirmed'";
$result = $conn->query($sql);
$stats = $result->fetch_assoc();

// Get popular destinations
$sql = "SELECT d.name, COUNT(b.id) as booking_count, SUM(b.total_price) as revenue
        FROM destinations d
        JOIN packages p ON d.id = p.destination_id
        JOIN bookings b ON p.id = b.package_id
        WHERE b.status = 'confirmed'
        GROUP BY d.id
        ORDER BY booking_count DESC
        LIMIT 5";
$popularDestinations = $conn->query($sql);
?>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header" data-aos="fade-up">
            <h1>Reports</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </nav>
        </div>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card h-100 bg-primary text-white" data-aos="fade-up">
                    <div class="card-body">
                        <h5 class="card-title">Total Bookings</h5>
                        <h2 class="mb-0"><?php echo number_format($stats['total_bookings']); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 bg-success text-white" data-aos="fade-up">
                    <div class="card-body">
                        <h5 class="card-title">Total Revenue</h5>
                        <h2 class="mb-0"><?php echo formatCurrency($stats['total_revenue']); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 bg-info text-white" data-aos="fade-up">
                    <div class="card-body">
                        <h5 class="card-title">Total Customers</h5>
                        <h2 class="mb-0"><?php echo number_format($stats['unique_customers']); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Destinations -->
        <div class="dashboard-card" data-aos="fade-up">
            <div class="dashboard-card-header">
                <h2><i class="fas fa-map-marker-alt me-2"></i>Popular Destinations</h2>
            </div>
            <div class="dashboard-card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Destination</th>
                                <th>Bookings</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($destination = $popularDestinations->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($destination['name']); ?></td>
                                <td><?php echo number_format($destination['booking_count']); ?></td>
                                <td><?php echo formatCurrency($destination['revenue']); ?></td>
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
