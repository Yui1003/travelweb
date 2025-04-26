
<?php
require_once 'includes/auth.php';
requireAdmin();
include 'includes/header.php';

$message = '';
$destination = null;
$attractions = [];

if (isset($_GET['id'])) {
    $destinationId = (int)$_GET['id'];
    $destination = getDestinationById($conn, $destinationId);
    $attractions = getAttractionsByDestination($conn, $destinationId);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $destinationId = (int)$_POST['destination_id'];
    $name = sanitizeInput($_POST['name']);
    $country = sanitizeInput($_POST['country']);
    $description = sanitizeInput($_POST['description']);
    $image_url = sanitizeInput($_POST['image_url']);
    
    // Update destination
    $sql = "UPDATE destinations SET name = ?, country = ?, description = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $country, $description, $image_url, $destinationId);
    
    if ($stmt->execute()) {
        // Handle attractions
        if (isset($_POST['attraction_names'])) {
            $attractionNames = $_POST['attraction_names'];
            $attractionDescs = $_POST['attraction_descriptions'];
            $attractionUrls = $_POST['attraction_urls'];
            $attractionIds = $_POST['attraction_ids'];
            
            for ($i = 0; $i < count($attractionNames); $i++) {
                if (!empty($attractionNames[$i])) {
                    if (!empty($attractionIds[$i])) {
                        // Update existing attraction
                        $sql = "UPDATE attractions SET name = ?, description = ?, image_url = ? WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sssi", $attractionNames[$i], $attractionDescs[$i], $attractionUrls[$i], $attractionIds[$i]);
                    } else {
                        // Add new attraction
                        $sql = "INSERT INTO attractions (destination_id, name, description, image_url) VALUES (?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("isss", $destinationId, $attractionNames[$i], $attractionDescs[$i], $attractionUrls[$i]);
                    }
                    $stmt->execute();
                }
            }
        }
        
        $message = '<div class="alert alert-success">Destination updated successfully!</div>';
        $destination = getDestinationById($conn, $destinationId);
        $attractions = getAttractionsByDestination($conn, $destinationId);
    } else {
        $message = '<div class="alert alert-danger">Error updating destination.</div>';
    }
}
?>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header" data-aos="fade-up">
            <h1>Edit Destination</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Edit Destination</li>
                </ol>
            </nav>
        </div>

        <?php if ($destination): ?>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="dashboard-card" data-aos="fade-up">
                    <?php echo $message; ?>
                    
                    <form method="post" action="edit-destination.php">
                        <input type="hidden" name="destination_id" value="<?php echo $destination['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Destination Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($destination['name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="<?php echo htmlspecialchars($destination['country']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($destination['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image_url" class="form-label">Image URL</label>
                            <input type="url" class="form-control" id="image_url" name="image_url" value="<?php echo htmlspecialchars($destination['image_url']); ?>" required>
                        </div>

                        <h3 class="mt-4 mb-3">Top Attractions</h3>
                        <div id="attractions-container">
                            <?php foreach ($attractions as $index => $attraction): ?>
                            <div class="attraction-entry border rounded p-3 mb-3">
                                <input type="hidden" name="attraction_ids[]" value="<?php echo $attraction['id']; ?>">
                                <div class="mb-3">
                                    <label class="form-label">Attraction Name</label>
                                    <input type="text" class="form-control" name="attraction_names[]" value="<?php echo htmlspecialchars($attraction['name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="attraction_descriptions[]" rows="2" required><?php echo htmlspecialchars($attraction['description']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Image URL</label>
                                    <input type="url" class="form-control" name="attraction_urls[]" value="<?php echo htmlspecialchars($attraction['image_url']); ?>" required>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm remove-attraction">Remove Attraction</button>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="button" class="btn btn-secondary mb-3" id="add-attraction">Add New Attraction</button>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Destination</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-danger">Destination not found.</div>
        <?php endif; ?>
    </div>
</section>

<script>
document.getElementById('add-attraction').addEventListener('click', function() {
    const container = document.getElementById('attractions-container');
    const template = `
        <div class="attraction-entry border rounded p-3 mb-3">
            <input type="hidden" name="attraction_ids[]" value="">
            <div class="mb-3">
                <label class="form-label">Attraction Name</label>
                <input type="text" class="form-control" name="attraction_names[]" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="attraction_descriptions[]" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Image URL</label>
                <input type="url" class="form-control" name="attraction_urls[]" required>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-attraction">Remove Attraction</button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-attraction')) {
        e.target.closest('.attraction-entry').remove();
    }
});
</script>

<?php include 'includes/footer.php'; ?>
