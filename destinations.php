<?php
// Include functions and header
include 'includes/functions.php';
include 'includes/header.php';

// Define destination images
$destinationImages = [
    'Bohol' => 'https://images.unsplash.com/photo-1667823506151-836beb11723d?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', // Chocolate Hills
    'Boracay' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80', // White Beach 
    'Cebu' => 'https://images.unsplash.com/photo-1495162048225-6b3b37b8a69e?q=80&w=1933&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', // Cebu City
    'Palawan' => 'https://images.unsplash.com/photo-1518509562904-e7ef99cdcc86?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', // El Nido Lagoon
    'Siargao' => 'https://images.unsplash.com/photo-1565340076637-825894a74ca6?q=80&w=1964&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' // Cloud 9 Wave
];




// Get all destinations or filter by search
$destinations = [];
$packages = [];
$searchTerm = "";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = sanitizeInput($_GET['search']);
    $packages = searchPackages($conn, $searchTerm);
} elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // If destination ID is provided, show packages for that destination
    $destinationId = (int)$_GET['id'];
    $destination = getDestinationById($conn, $destinationId);
    $packages = getPackagesByDestination($conn, $destinationId);
    $attractions = getAttractionsByDestination($conn, $destinationId);
} else {
    // Show all destinations
    $destinations = getAllDestinations($conn);
}
?>

<!-- Page Header -->
<section class="page-header" style="background-image: url('https://images.unsplash.com/photo-1529260830199-42c24126f198');">
    <div class="container">
        <div class="page-header-content">
            <h1 class="page-header-title">
                <?php
                if (!empty($searchTerm)) {
                    echo "Search Results for \"" . htmlspecialchars($searchTerm) . "\"";
                } elseif (isset($destination)) {
                    echo htmlspecialchars($destination['name']);
                } else {
                    echo "Destinations";
                }
                ?>
            </h1>
            <div class="page-header-breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-angle-right"></i>
                <?php if (isset($destination)): ?>
                    <a href="destinations.php">Destinations</a>
                    <i class="fas fa-angle-right"></i>
                    <span><?php echo htmlspecialchars($destination['name']); ?></span>
                <?php else: ?>
                    <span>Destinations</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Destinations/Packages Content -->
<div class="container">
    <!-- Search Form -->
    <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
            <form action="destinations.php" method="get" class="mb-5">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search destinations or packages..." name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($destination)): ?>
        <!-- Single Destination Page -->
        <div class="row mb-5">
            <div class="col-lg-8">
                <div class="destination-header mb-4" data-aos="fade-up">
                    <h2><?php echo htmlspecialchars($destination['name']); ?>, <?php echo htmlspecialchars($destination['country']); ?></h2>
                    <p class="text-muted"><?php echo htmlspecialchars($destination['description']); ?></p>
                </div>

                <!-- Destination Image -->
                <div class="mb-4" data-aos="fade-up">
                    <img src="<?php echo htmlspecialchars($destination['image_url']); ?>" alt="<?php echo htmlspecialchars($destination['name']); ?>" class="img-fluid rounded">
                </div>

                <!-- Attractions -->
                <?php if (!empty($attractions)): ?>
                    <div class="attractions mb-5" data-aos="fade-up">
                        <h3 class="mb-4">Top Attractions</h3>
                        <div class="row">
                            <?php foreach ($attractions as $attraction): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <img src="<?php echo htmlspecialchars($attraction['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($attraction['name']); ?>" style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($attraction['name']); ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars($attraction['description']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Available Packages -->
                <div class="available-packages" data-aos="fade-up">
                    <h3 class="mb-4">Available Tour Packages</h3>
                    <?php if (empty($packages)): ?>
                        <div class="alert alert-info">
                            No packages available for this destination at the moment. Please check back later.
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($packages as $package): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="package-card h-100">
                                        <img src="<?php echo htmlspecialchars($destination['image_url']); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>" class="package-img">
                                        <div class="package-card-content">
                                            <h3><?php echo htmlspecialchars($package['title']); ?></h3>
                                            <div class="package-meta">
                                                <div><i class="fas fa-clock"></i> <?php echo $package['duration']; ?> days</div>
                                                <div><i class="fas fa-users"></i> Max: <?php echo $package['max_travelers']; ?></div>
                                            </div>
                                            <div class="package-price">
                                                <?php echo formatCurrency($package['price']); ?> <span>/ per person</span>
                                            </div>
                                            <a href="package-details.php?id=<?php echo $package['id']; ?>" class="btn btn-primary w-100">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4" data-aos="fade-up">
                    <div class="card-body">
                        <h4 class="card-title">Destination Information</h4>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Country
                                <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($destination['country']); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Available Packages
                                <span class="badge bg-primary rounded-pill"><?php echo count($packages); ?></span>
                            </li>
                            <?php if (!empty($attractions)): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Attractions
                                    <span class="badge bg-primary rounded-pill"><?php echo count($attractions); ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body">
                        <h4 class="card-title">Need Help?</h4>
                        <p class="card-text">Our travel experts are available to help you plan the perfect trip to <?php echo htmlspecialchars($destination['name']); ?>.</p>
                        <a href="contact.php" class="btn btn-outline-primary">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif (!empty($searchTerm)): ?>
        <!-- Search Results -->
        <div class="section-title text-center" data-aos="fade-up">
            <h2>Search Results</h2>
            <?php if (empty($packages)): ?>
                <p>No results found for "<?php echo htmlspecialchars($searchTerm); ?>". Try another search term.</p>
            <?php else: ?>
                <p>We found <?php echo count($packages); ?> packages matching your search</p>
            <?php endif; ?>
        </div>

        <div class="row">
            <?php foreach ($packages as $package): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="package-card h-100" data-aos="fade-up">
                        <img src="<?php echo htmlspecialchars($package['image_url']); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>" class="package-img">
                        <div class="package-card-content">
                            <h3><?php echo htmlspecialchars($package['title']); ?></h3>
                            <div class="package-meta">
                                <div><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($package['destination_name']); ?></div>
                                <div><i class="fas fa-clock"></i> <?php echo isset($package['duration']) ? htmlspecialchars($package['duration']) . ' days' : 'Duration N/A'; ?></div>
                            </div>
                            <div class="package-price">
                                <?php echo isset($package['price']) ? formatCurrency($package['price']) : 'Price on request'; ?> <span>/ per person</span>
                            </div>
                            <a href="package-details.php?id=<?php echo $package['id']; ?>" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($packages)): ?>
            <div class="text-center mb-5">
                <a href="destinations.php" class="btn btn-outline-primary">Browse All Destinations</a>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- All Destinations -->
        <div class="section-title text-center" data-aos="fade-up">
            <h2>Explore Popular Destinations</h2>
            <p>Discover the world's most amazing places</p>
        </div>

        <div class="row">
            <?php foreach ($destinations as $destination): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="destination-card" data-aos="fade-up">
                        <img src="<?php echo isset($destinationImages[$destination['name']]) ? $destinationImages[$destination['name']] : 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80'; ?>" alt="<?php echo $destination['name']; ?>" class="destination-img">
                        <div class="destination-card-overlay">
                            <h3><?php echo $destination['name']; ?></h3>
                            <div class="location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo $destination['country']; ?></span>
                            </div>
                        </div>
                        <div class="destination-card-content">
                            <p><?php echo substr($destination['description'], 0, 100); ?>...</p>
                            <a href="destinations.php?id=<?php echo $destination['id']; ?>" class="btn btn-primary">Explore</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Call to Action -->
<section class="cta">
    <div class="container text-center">
        <h2 data-aos="fade-up">Ready for Your Next Adventure?</h2>
        <p data-aos="fade-up" data-aos-delay="100">Join thousands of satisfied travelers who have experienced the world with us.</p>
        <?php if (!isLoggedIn()): ?>
            <div data-aos="fade-up" data-aos-delay="200">
                <a href="register.php" class="btn btn-light me-2">Sign Up Now</a>
                <a href="login.php" class="btn btn-outline-light">Login</a>
            </div>
        <?php else: ?>
            <a href="contact.php" class="btn btn-light" data-aos="fade-up" data-aos-delay="200">Contact Us</a>
        <?php endif; ?>
    </div>
</section>

<?php
// Include footer
include 'includes/footer.php';
?>