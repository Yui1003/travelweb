<?php
// Include header
include 'includes/header.php';

// Require operator access
requireOperator();

// Get all destinations for the dropdown
$destinations = getAllDestinations($conn);

// Get package ID from URL
$packageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get package details
$package = getPackageById($conn, $packageId);

if (!$package) {
    header("Location: " . (isAdmin() ? "admin-dashboard.php" : "operator-dashboard.php"));
    exit;
}

// Set operator ID based on package creator or current user
$operatorId = isAdmin() ? $package['created_by'] : $_SESSION['user_id'];

// Verify access
if (!isAdmin() && $package['created_by'] != $_SESSION['user_id']) {
    header("Location: operator-dashboard.php");
    exit;
}

// Process form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_package'])) {
    // Get form data
    $title = sanitizeInput($_POST['title']);
    $destinationId = (int)$_POST['destination_id'];
    $description = sanitizeInput($_POST['description']);
    $price = (float)$_POST['price'];
    $duration = (int)$_POST['duration'];
    $maxTravelers = (int)$_POST['max_travelers'];
    $itinerary = sanitizeInput($_POST['itinerary']);
    $inclusions = sanitizeInput($_POST['inclusions']);
    $exclusions = sanitizeInput($_POST['exclusions']);
    $featured = isset($_POST['featured']) ? 1 : 0;
    $discountPercent = (int)$_POST['discount_percent'];
    $status = sanitizeInput($_POST['status']);
    $imageUrl = sanitizeInput($_POST['image_url']); // Added image_url

    // Validate form data
    if (empty($title) || empty($description) || $price <= 0 || $duration <= 0) {
        $message = '<div class="alert alert-danger">Please fill all required fields with valid data.</div>';
    } else {
        // Update package in database
        $sql = "UPDATE packages SET 
                destination_id = ?, 
                title = ?, 
                description = ?, 
                price = ?, 
                duration = ?, 
                max_travelers = ?, 
                itinerary = ?, 
                inclusions = ?, 
                exclusions = ?, 
                featured = ?, 
                discount_percent = ?, 
                status = ?,
                image_url = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdiisssiissi", $destinationId, $title, $description, $price, $duration, 
                        $maxTravelers, $itinerary, $inclusions, $exclusions, $featured, $discountPercent, $status, $imageUrl, $packageId);

        if ($stmt->execute()) {
            // Redirect to the package details page
            header("Location: package-details.php?id=" . $packageId . "&updated=1");
            exit;
        } else {
            $message = '<div class="alert alert-danger">Failed to add package. Please try again. Error: ' . $conn->error . '</div>';
        }
    }
}
?>

<!-- Page Content -->
<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header" data-aos="fade-up">
            <h1>Edit Tour Package</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <?php if (isAdmin()): ?>
                        <a href="admin-dashboard.php" class="text-decoration-none">Dashboard</a>  
                    <?php else: ?>
                        <a href="operator-dashboard.php" class="text-decoration-none">Dashboard</a>
                    <?php endif; ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Package</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="dashboard-card" data-aos="fade-up">
                    <div class="dashboard-card-header">
                        <h2><i class="fas fa-edit me-2"></i>Edit Package</h2>
                    </div>
                    <div class="dashboard-card-body">
                        <?php echo $message; ?>

                        <form action="edit-package.php?id=<?php echo $packageId; ?>" method="post" id="editPackageForm">
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Package Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($package['title']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="destination_id" class="form-label">Destination <span class="text-danger">*</span></label>
                                        <select class="form-select" id="destination_id" name="destination_id" required>
                                            <option value="">Select a destination</option>
                                            <?php foreach ($destinations as $destination): ?>
                                            <option value="<?php echo $destination['id']; ?>" <?php echo ($destination['id'] == $package['destination_id']) ? 'selected' : ''; ?>>
                                                <?php echo $destination['name']; ?> (<?php echo $destination['region']; ?>)
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($package['description']); ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="package-info-card">
                                        <h4>Package Details</h4>

                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price per person (PHP) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" value="<?php echo $package['price']; ?>" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="duration" class="form-label">Duration (days) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="duration" name="duration" min="1" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="max_travelers" class="form-label">Maximum Travelers</label>
                                            <input type="number" class="form-control" id="max_travelers" name="max_travelers" min="1" value="10">
                                        </div>

                                        <div class="mb-3">
                                            <label for="discount_percent" class="form-label">Discount Percentage</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="discount_percent" name="discount_percent" min="0" max="100" value="0">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                                <option value="sold-out">Sold Out</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="image_url" class="form-label">Package Image URL</label>
                                            <input type="url" class="form-control" id="image_url" name="image_url" value="<?php echo htmlspecialchars($package['image_url']); ?>" placeholder="Enter image URL">
                                        </div>

                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="featured" name="featured">
                                            <label class="form-check-label" for="featured">Feature this package</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4>Itinerary <span class="text-danger">*</span></h4>
                                <p class="text-muted">Provide a day-by-day breakdown of the tour. Format each day as "Day X: Description"</p>
                                <textarea class="form-control" id="itinerary" name="itinerary" rows="6" required></textarea>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h4>Inclusions <span class="text-danger">*</span></h4>
                                    <p class="text-muted">List items included in the package price (one per line)</p>
                                    <textarea class="form-control" id="inclusions" name="inclusions" rows="6" required></textarea>
                                </div>

                                <div class="col-md-6">
                                    <h4>Exclusions</h4>
                                    <p class="text-muted">List items not included in the package price (one per line)</p>
                                    <textarea class="form-control" id="exclusions" name="exclusions" rows="6"></textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?php echo isAdmin() ? 'admin-dashboard.php' : 'operator-dashboard.php'; ?>" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" name="edit_package" class="btn btn-primary">Update Package</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional styles for the add package page -->
<style>
.package-info-card {
    background-color: #f8f9fa;
    border-radius: var(--border-radius);
    padding: 20px;
}

.package-info-card h4 {
    margin-bottom: 20px;
    font-size: 1.2rem;
}
</style>

<!-- Form validation script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editPackageForm'); // Corrected ID

    form.addEventListener('submit', function(event) {
        let isValid = true;

        // Basic validation for required fields
        const requiredFields = ['title', 'destination_id', 'description', 'price', 'duration', 'itinerary', 'inclusions'];

        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        // Validate numeric fields
        const numericFields = ['price', 'duration', 'max_travelers', 'discount_percent'];

        numericFields.forEach(field => {
            const input = document.getElementById(field);
            const value = parseFloat(input.value);

            if (isNaN(value) || value < 0) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            event.preventDefault();
            alert('Please fill all required fields with valid data.');
        }
    });

    // Example values for itinerary and inclusions to help users
    document.getElementById('title').addEventListener('focus', function() {
        const itineraryExample = document.getElementById('itinerary');
        if (!itineraryExample.value) {
            itineraryExample.placeholder = "Day 1: Arrival and hotel check-in\nDay 2: City tour and cultural activities\nDay 3: Beach day and water activities\nDay 4: Free time for shopping and departure";
        }

        const inclusionsExample = document.getElementById('inclusions');
        if (!inclusionsExample.value) {
            inclusionsExample.placeholder = "Hotel accommodation\nDaily breakfast\nAirport transfers\nEnglish-speaking guide\nEntrance fees to attractions";
        }

        const exclusionsExample = document.getElementById('exclusions');
        if (!exclusionsExample.value) {
            exclusionsExample.placeholder = "Flights\nTravel insurance\nPersonal expenses\nMeals not mentioned\nOptional activities";
        }
    });
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?>