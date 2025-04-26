
<?php
require_once 'includes/auth.php';
requireAdmin();
include 'includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitizeInput($_POST['name']);
    $country = sanitizeInput($_POST['country']);
    $description = sanitizeInput($_POST['description']);
    $image_url = sanitizeInput($_POST['image_url']);
    
    $sql = "INSERT INTO destinations (name, country, description, image_url) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $country, $description, $image_url);
    
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Destination added successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Error adding destination.</div>';
    }
}
?>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header" data-aos="fade-up">
            <h1>Add New Destination</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Add Destination</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="dashboard-card" data-aos="fade-up">
                    <div class="dashboard-card-header">
                        <h2><i class="fas fa-map-marker-alt me-2"></i>New Destination</h2>
                    </div>
                    <div class="dashboard-card-body">
                        <?php echo $message; ?>
                        
                        <form method="post" action="add-destination.php">
                            <div class="mb-3">
                                <label for="name" class="form-label">Destination Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image_url" class="form-label">Image URL</label>
                                <input type="url" class="form-control" id="image_url" name="image_url" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Add Destination</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
