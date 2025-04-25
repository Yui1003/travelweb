<?php
// Global functions for the travel booking system

// Get featured destinations
function getFeaturedDestinations($conn, $limit = 4) {
    $sql = "SELECT * FROM destinations WHERE featured = 1 LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $destinations = [];
    
    while($row = $result->fetch_assoc()) {
        $destinations[] = $row;
    }
    
    return $destinations;
}

// Get all destinations
function getAllDestinations($conn) {
    $sql = "SELECT * FROM destinations ORDER BY name";
    $result = $conn->query($sql);
    $destinations = [];
    
    while($row = $result->fetch_assoc()) {
        $destinations[] = $row;
    }
    
    return $destinations;
}

// Get destination by ID
function getDestinationById($conn, $id) {
    $sql = "SELECT * FROM destinations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return null;
    }
    
    return $result->fetch_assoc();
}

// Get packages by destination ID
function getPackagesByDestination($conn, $destinationId) {
    $sql = "SELECT p.*, d.name as destination_name 
            FROM packages p 
            JOIN destinations d ON p.destination_id = d.id 
            WHERE d.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $destinationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $packages = [];
    
    while($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
    
    return $packages;
}

// Get featured packages
function getFeaturedPackages($conn, $limit = 6) {
    $sql = "SELECT p.*, d.name as destination_name, d.image_url 
            FROM packages p 
            JOIN destinations d ON p.destination_id = d.id 
            WHERE p.featured = 1 
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $packages = [];
    
    while($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
    
    return $packages;
}

// Get all packages
function getAllPackages($conn) {
    $sql = "SELECT p.*, d.name as destination_name, d.image_url 
            FROM packages p 
            JOIN destinations d ON p.destination_id = d.id 
            ORDER BY p.id DESC";
    $result = $conn->query($sql);
    $packages = [];
    
    while($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
    
    return $packages;
}

// Get package by ID
function getPackageById($conn, $id) {
    $sql = "SELECT p.*, d.name as destination_name, d.country, d.image_url 
            FROM packages p 
            JOIN destinations d ON p.destination_id = d.id 
            WHERE p.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return null;
    }
    
    return $result->fetch_assoc();
}

// Format currency (Philippine Peso)
function formatCurrency($amount) {
    return '₱' . number_format($amount, 2);
}

// Sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Format itinerary for display
function formatItinerary($itinerary) {
    $lines = explode("\n", $itinerary);
    $html = '<div class="itinerary-timeline">';
    
    foreach ($lines as $line) {
        if (!empty(trim($line))) {
            $parts = explode(':', $line, 2);
            if (count($parts) == 2) {
                $day = trim($parts[0]);
                $activity = trim($parts[1]);
                $html .= '<div class="timeline-item">';
                $html .= '<div class="timeline-badge"><i class="fas fa-map-marker-alt"></i></div>';
                $html .= '<div class="timeline-panel">';
                $html .= '<div class="timeline-heading"><h4>' . $day . '</h4></div>';
                $html .= '<div class="timeline-body"><p>' . $activity . '</p></div>';
                $html .= '</div></div>';
            }
        }
    }
    
    $html .= '</div>';
    return $html;
}

// Get attractions by destination
function getAttractionsByDestination($conn, $destinationId) {
    $sql = "SELECT * FROM attractions WHERE destination_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $destinationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $attractions = [];
    
    while($row = $result->fetch_assoc()) {
        $attractions[] = $row;
    }
    
    return $attractions;
}

// Format inclusions/exclusions for display
function formatListItems($items) {
    $lines = explode("\n", $items);
    $html = '<ul class="feature-list">';
    
    foreach ($lines as $line) {
        if (!empty(trim($line))) {
            $html .= '<li><i class="fas fa-check-circle"></i> ' . trim($line) . '</li>';
        }
    }
    
    $html .= '</ul>';
    return $html;
}
?>


