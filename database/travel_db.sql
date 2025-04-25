    -- Create database if it doesn't exist
    CREATE DATABASE IF NOT EXISTS travel_db;
    USE travel_db;

    -- Create tables
    CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        role_id INT NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        address TEXT,
        profile_img VARCHAR(255) DEFAULT 'default-user.jpg',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
        FOREIGN KEY (role_id) REFERENCES roles(id)
    );

    CREATE TABLE IF NOT EXISTS destinations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        country VARCHAR(100) DEFAULT 'Philippines',
        region VARCHAR(100) NOT NULL,
        description TEXT,
        image_url VARCHAR(255),
        featured BOOLEAN DEFAULT 0,
        popular BOOLEAN DEFAULT 0,
        best_time_to_visit VARCHAR(100),
        things_to_do TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS attractions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        destination_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        image_url VARCHAR(255),
        rating DECIMAL(3,1),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS packages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        destination_id INT NOT NULL,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        duration INT NOT NULL COMMENT 'in days',
        max_travelers INT DEFAULT 10,
        itinerary TEXT,
        inclusions TEXT,
        exclusions TEXT,
        featured BOOLEAN DEFAULT 0,
        discount_percent INT DEFAULT 0,
        created_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        status ENUM('active', 'inactive', 'sold-out') DEFAULT 'active',
        FOREIGN KEY (destination_id) REFERENCES destinations(id),
        FOREIGN KEY (created_by) REFERENCES users(id)
    );

    CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        package_id INT NOT NULL,
        booking_date DATE NOT NULL,
        travel_date DATE NOT NULL,
        num_travelers INT NOT NULL DEFAULT 1,
        total_price DECIMAL(10,2) NOT NULL,
        payment_status ENUM('pending', 'paid', 'refunded', 'cancelled') DEFAULT 'pending',
        confirmation_number VARCHAR(20) UNIQUE,
        special_requests TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (package_id) REFERENCES packages(id)
    );

    CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        package_id INT NOT NULL,
        rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
        comment TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        status ENUM('approved', 'pending', 'rejected') DEFAULT 'approved',
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (package_id) REFERENCES packages(id)
    );

    CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        subject VARCHAR(200),
        message TEXT NOT NULL,
        status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        replied_at TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );

    -- Insert default data
    INSERT INTO roles (name) VALUES 
    ('Admin'),
    ('Tour Operator'),
    ('Traveler');

    -- Insert admin user (password: admin1234)
    INSERT INTO users (role_id, full_name, username, email, password, phone) VALUES 
    (1, 'Admin User', 'admin', 'admin@wanderlust.com', '$2y$10$Jrjwyrxcn621teAORnZdCeAa/oXK/ez70XSeoA7ngFy5IxAGKFwKG', '+639123456789');

    -- Insert operator (password: ope1234)
    INSERT INTO users (role_id, full_name, username, email, password, phone) VALUES 
    (2, 'Tour Operator', 'operator', 'operator@wanderlust.com', '$2y$10$UxIMrOjCLaBc1UJ6/5uRCuIfnNtuEkD4prnBKHkKKYMZlW5YumPmC', '+639123456790');

    -- Insert Philippines destinations
    INSERT INTO destinations (name, region, description, image_url, featured, popular, best_time_to_visit, things_to_do) VALUES 
    ('Boracay', 'Western Visayas', 'Boracay is a small island in the Philippines located approximately 315 km south of Manila and 2 km off the northwest tip of Panay Island in Western Visayas region. Boracay\'s White Beach is often cited as one of the world\'s best beaches.', 'https://static.saltinourhair.com/wp-content/uploads/2019/03/23151931/things-to-do-boracay-sunset-header.jpg', 1, 1, 'November to May', 'Beach activities, island hopping, water sports, dining, shopping, nightlife'),

    ('Palawan', 'MIMAROPA', 'Palawan is an archipelagic province of the Philippines that is located in the region of MIMAROPA. It is the largest province in the country in terms of total area of jurisdiction. Palawan is known as the Philippines\' Last Ecological Frontier and one of the most biodiverse islands in the Philippines.', 'https://images.squarespace-cdn.com/content/v1/5a87961cbe42d637c54cab93/1547736420211-X6E5VTDKUCB249SL1QNX/things-to-do-in-coron-palawan-1.jpg', 1, 1, 'December to May', 'Island hopping, diving, snorkeling, beach activities, cave exploration'),

    ('Cebu', 'Central Visayas', 'Cebu is a province of the Philippines located in the Central Visayas region, and consists of a main island and 167 surrounding islands and islets. Its capital is Cebu City, the oldest city and first capital of the Philippines, which is politically independent from the provincial government.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/14/8c/1f/dd/cebu-city.jpg?w=700&h=-1&s=1', 1, 1, 'January to May', 'Historical tours, island hopping, diving, whale shark watching, waterfalls'),

    ('Bohol', 'Central Visayas', 'Bohol is a province of the Philippines, in the country's Central Visayas region. It comprises Bohol Island and numerous smaller surrounding islands. Bohol is known for coral reefs and unusual geological formations, notably the Chocolate Hills.', 'https://a.cdn-hotels.com/gdcs/production97/d1523/0103cc6c-791d-41c2-9d57-42fb0c4a0c09.jpg', 1, 1, 'January to May', 'Chocolate Hills tour, tarsier sanctuary, river cruise, beaches, diving'),

    ('Siargao', 'Caraga', 'Siargao is a tear-drop shaped island in the Philippine Sea situated 800 kilometers southeast of Manila in the province of Surigao del Norte. It has a land area of approximately 437 square kilometers. Siargao is known as the "Surfing Capital of the Philippines" with the perfect surf break called Cloud 9.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/16/d4/1a/3c/sugba-lagoon.jpg?w=1200&h=-1&s=1', 1, 1, 'March to September', 'Surfing, island hopping, lagoon visits, diving, beach activities');

    -- Insert attractions for each destination
    -- Boracay Attractions
    INSERT INTO attractions (destination_id, name, description, image_url, rating) VALUES
    (1, 'White Beach', 'Famous 4km stretch of white sand beach divided into three stations, known for its stunning sunsets and vibrant atmosphere.', 'https://a.cdn-hotels.com/gdcs/production143/d357/42fb6908-dcd5-4edb-9f8c-76208494af80.jpg', 4.8),
    (1, 'Puka Beach', 'A quieter alternative to White Beach, known for its natural beauty and puka shells that wash up on shore.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/09/4a/95/7a/puka-beach.jpg?w=1200&h=-1&s=1', 4.6),
    (1, 'Ariel\'s Point', 'A popular day trip destination offering cliff diving, snorkeling, and kayaking in a beautiful natural setting.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/07/1d/0c/8a/ariel-s-point.jpg?w=1200&h=-1&s=1', 4.7);

    -- Palawan Attractions
    INSERT INTO attractions (destination_id, name, description, image_url, rating) VALUES
    (2, 'Underground River', 'Puerto Princesa Subterranean River National Park features an 8.2 km navigable underground river that winds through a spectacular cave.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/15/a7/92/89/puerto-princesa-underground.jpg?w=1200&h=-1&s=1', 4.7),
    (2, 'Honda Bay', 'Popular destination for island hopping, featuring beautiful islands like Starfish Island, Luli Island, and Cowrie Island.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0d/45/8c/d3/honda-bay.jpg?w=1200&h=-1&s=1', 4.5),
    (2, 'Tubbataha Reef', 'UNESCO World Heritage Site and National Marine Park, recognized as one of the most remarkable coral reefs on the planet.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/1c/cd/20/e6/tubbataha-reef.jpg?w=1200&h=-1&s=1', 4.9);

    -- Example packages created by the operator
    INSERT INTO packages (destination_id, title, description, price, duration, max_travelers, itinerary, inclusions, exclusions, featured, discount_percent, created_by, status) VALUES
    -- Boracay Package
    (1, 'Boracay Beach Getaway', 'Experience the stunning white sand beaches and vibrant nightlife of Boracay with this all-inclusive package.', 15000.00, 4, 10,
    'Day 1: Arrival and Hotel Check-in\nDay 2: White Beach and Water Activities\nDay 3: Island Hopping Tour\nDay 4: Free Time and Departure',
    'Hotel accommodation\nDaily breakfast\nIsland hopping tour\nAirport transfers\nWelcome drink',
    'Flights\nTravel insurance\nPersonal expenses\nOptional activities\nMeals not specified', 1, 0, 2, 'active'),

    -- Cebu Package  
    (3, 'Cebu Heritage & Adventure', 'Discover the rich history and natural wonders of Cebu.', 18000.00, 5, 8,
    'Day 1: Arrival and City Tour\nDay 2: Historical Sites Visit\nDay 3: Whale Shark Watching\nDay 4: Kawasan Falls\nDay 5: Departure',
    'Hotel accommodation\nDaily breakfast\nGuided tours\nEntrance fees\nTransport',
    'Flights\nTravel insurance\nPersonal expenses\nOptional activities', 1, 0, 2, 'active'),

    -- Bohol Package
    (4, 'Bohol Natural Wonders', 'Experience the unique attractions of Bohol including Chocolate Hills.', 16500.00, 4, 10,
    'Day 1: Arrival\nDay 2: Chocolate Hills Tour\nDay 3: Tarsier Sanctuary & River Cruise\nDay 4: Beach Day & Departure',
    'Resort stay\nDaily breakfast\nTours and activities\nRiver cruise lunch\nTransfers',
    'Flights\nPersonal expenses\nOptional activities', 1, 0, 2, 'active'),

    -- Siargao Package
    (5, 'Siargao Surf & Nature', 'Perfect package for surf enthusiasts and nature lovers.', 17500.00, 5, 8,
    'Day 1: Arrival\nDay 2: Surf Lessons\nDay 3: Island Hopping\nDay 4: Sugba Lagoon\nDay 5: Free Day & Departure',
    'Surf lessons\nAccommodation\nIsland hopping\nDaily breakfast\nTransport',
    'Flights\nTravel insurance\nBoard rental\nMeals not specified', 1, 0, 2, 'active') 
    'Day 1: Arrival and Hotel Check-in
    Day 2: White Beach and Water Activities
    Day 3: Island Hopping Tour
    Day 4: Free Time and Departure', 
    'Accommodation
    Daily breakfast
    Island hopping tour
    Airport transfers
    Welcome drink', 
    'Flights
    Travel insurance
    Personal expenses
    Optional activities not mentioned
    Meals not specified', 1, 0, 2, 'active'),

    (2, 'Palawan Adventure', 'Discover the natural wonders of Palawan, from the Underground River to pristine beaches and limestone cliffs.', 25000.00, 5, 8, 
    'Day 1: Arrival in Puerto Princesa and Hotel Check-in
    Day 2: Underground River Tour
    Day 3: Honda Bay Island Hopping
    Day 4: City Tour and Firefly Watching
    Day 5: Free Time and Departure', 
    'Accommodation (4 nights)
    Daily breakfast
    Underground River tour with lunch
    Honda Bay island hopping with lunch
    City tour
    Airport transfers', 
    'Flights
    Travel insurance
    Personal expenses
    Optional activities not mentioned
    Meals not specified', 1, 5, 2, 'active');