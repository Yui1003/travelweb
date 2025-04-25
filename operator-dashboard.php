<?php
// Include header
include 'includes/header.php';

// Require operator access
requireOperator();

// Get operator ID
$operatorId = $_SESSION['user_id'];

// Get operator's packages
$operatorPackages = getOperatorPackages($conn, $operatorId);

// Get operator's bookings
$operatorBookings = getOperatorBookings($conn, $operatorId);

// Get statistics
$totalOperatorPackages = count($operatorPackages);
$totalOperatorBookings = count($operatorBookings);
$totalEarnings = calculateTotalEarnings($operatorBookings);

// Get destinations for package creation
$allDestinations = getAllDestinations($conn);
?>

<!-- Page Content -->
<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header" data-aos="fade-up">
            <h1>Tour Operator Dashboard</h1>
            <p>Welcome back, <?php echo $_SESSION['full_name']; ?>!</p>
        </div>
        
        <!-- Statistics Cards -->
        <div class="dashboard-stats" data-aos="fade-up">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-suitcase"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $totalOperatorPackages; ?></h3>
                    <p>Your Packages</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $totalOperatorBookings; ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo formatCurrency($totalEarnings); ?></h3>
                    <p>Total Earnings</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="stat-info">
                    <a href="add-package.php" class="btn btn-primary btn-sm">Add New Package</a>
                </div>
            </div>
        </div>
        
        <!-- My Packages -->
        <div class="dashboard-card mt-4" data-aos="fade-up">
            <div class="dashboard-card-header">
                <h2><i class="fas fa-suitcase me-2"></i>My Tour Packages</h2>
            </div>
            <div class="dashboard-card-body">
                <?php if (count($operatorPackages) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Package</th>
                                <th>Destination</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($operatorPackages as $package): ?>
                            <tr>
                                <td><?php echo $package['title']; ?></td>
                                <td><?php echo $package['destination_name']; ?></td>
                                <td><?php echo $package['duration']; ?> days</td>
                                <td><?php echo formatCurrency($package['price']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $package['status'] == 'active' ? 'success' : 
                                            ($package['status'] == 'inactive' ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo ucfirst($package['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="package-details.php?id=<?php echo $package['id']; ?>" class="btn btn-sm btn-info">View</a>
                                    <a href="edit-package.php?id=<?php echo $package['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    <p>You haven't created any tour packages yet.</p>
                    <a href="add-package.php" class="btn btn-primary mt-3">Create Your First Tour Package</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Recent Bookings -->
        <div class="dashboard-card mt-4" data-aos="fade-up">
            <div class="dashboard-card-header">
                <h2><i class="fas fa-clipboard-list me-2"></i>Recent Bookings</h2>
            </div>
            <div class="dashboard-card-body">
                <?php if (count($operatorBookings) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer</th>
                                <th>Package</th>
                                <th>Travel Date</th>
                                <th>Travelers</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($operatorBookings as $booking): ?>
                            <tr>
                                <td>#<?php echo $booking['id']; ?></td>
                                <td><?php echo $booking['full_name']; ?></td>
                                <td><?php echo $booking['package_title']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($booking['travel_date'])); ?></td>
                                <td><?php echo $booking['num_travelers']; ?></td>
                                <td><?php echo formatCurrency($booking['total_price']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $booking['payment_status'] == 'paid' ? 'success' : 
                                            ($booking['payment_status'] == 'pending' ? 'warning' : 
                                            ($booking['payment_status'] == 'cancelled' ? 'danger' : 'info')); 
                                    ?>">
                                        <?php echo ucfirst($booking['payment_status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-info">No bookings for your packages yet.</div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Earning Reports -->
        <div class="dashboard-card mt-4" data-aos="fade-up">
            <div class="dashboard-card-header">
                <h2><i class="fas fa-chart-line me-2"></i>Earning Reports</h2>
            </div>
            <div class="dashboard-card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="earning-summary text-center">
                            <h3 class="total-earnings"><?php echo formatCurrency($totalEarnings); ?></h3>
                            <p>Total Earnings</p>
                            <div class="earning-period-selector">
                                <button class="btn btn-sm btn-outline-primary active">All Time</button>
                                <button class="btn btn-sm btn-outline-primary">This Month</button>
                                <button class="btn btn-sm btn-outline-primary">This Year</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="earning-chart">
                            <canvas id="earningsChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h4>Earnings by Package</h4>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Package</th>
                                    <th>Bookings</th>
                                    <th>Earnings</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $packageEarnings = calculatePackageEarnings($operatorBookings);
                                foreach($packageEarnings as $packageId => $data): 
                                ?>
                                <tr>
                                    <td><?php echo $data['title']; ?></td>
                                    <td><?php echo $data['bookings']; ?></td>
                                    <td><?php echo formatCurrency($data['earnings']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chart.js for earnings chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample data for the earnings chart
    // In a real application, this would come from the server based on actual earnings data
    const ctx = document.getElementById('earningsChart').getContext('2d');
    const earningsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Monthly Earnings (PHP)',
                data: [
                    <?php
                    // Generate some sample data for the chart
                    // In a real application, this would be actual monthly earnings
                    $sampleData = [];
                    for ($i = 0; $i < 12; $i++) {
                        $sampleData[] = rand(10000, 50000);
                    }
                    echo implode(', ', $sampleData);
                    ?>
                ],
                backgroundColor: 'rgba(48, 120, 198, 0.2)',
                borderColor: 'rgba(48, 120, 198, 1)',
                borderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₱' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>

<!-- Additional styles for the operator dashboard -->
<style>
.total-earnings {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 5px;
}

.earning-period-selector {
    margin-top: 15px;
    display: flex;
    justify-content: center;
    gap: 10px;
}

.earning-chart {
    height: 300px;
}
</style>

<?php
// Include footer
include 'includes/footer.php';

// Helper functions for the operator dashboard
function getOperatorPackages($conn, $operatorId) {
    $sql = "SELECT p.*, d.name as destination_name 
            FROM packages p 
            JOIN destinations d ON p.destination_id = d.id 
            WHERE p.created_by = ? 
            ORDER BY p.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $operatorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $packages = [];
    
    while($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
    
    return $packages;
}

function getOperatorBookings($conn, $operatorId) {
    $sql = "SELECT b.*, u.full_name, p.title as package_title 
            FROM bookings b 
            JOIN users u ON b.user_id = u.id 
            JOIN packages p ON b.package_id = p.id 
            WHERE p.created_by = ? 
            ORDER BY b.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $operatorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = [];
    
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    
    return $bookings;
}

function calculateTotalEarnings($bookings) {
    $total = 0;
    foreach ($bookings as $booking) {
        if ($booking['payment_status'] == 'paid') {
            $total += $booking['total_price'];
        }
    }
    return $total;
}

function calculatePackageEarnings($bookings) {
    $packageEarnings = [];
    
    foreach ($bookings as $booking) {
        $packageId = $booking['package_id'];
        $packageTitle = $booking['package_title'];
        $amount = $booking['total_price'];
        $status = $booking['payment_status'];
        
        if (!isset($packageEarnings[$packageId])) {
            $packageEarnings[$packageId] = [
                'title' => $packageTitle,
                'bookings' => 0,
                'earnings' => 0
            ];
        }
        
        $packageEarnings[$packageId]['bookings']++;
        
        if ($status == 'paid') {
            $packageEarnings[$packageId]['earnings'] += $amount;
        }
    }
    
    return $packageEarnings;
}
?>