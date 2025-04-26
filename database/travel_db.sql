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
('Palawan', 'MIMAROPA', 'Palawan is an archipelagic province of the Philippines that is known as the Philippines\' Last Ecological Frontier and one of the most biodiverse islands in the Philippines.', 'https://images.squarespace-cdn.com/content/v1/5a87961cbe42d637c54cab93/1547736420211-X6E5VTDKUCB249SL1QNX/things-to-do-in-coron-palawan-1.jpg', 1, 1, 'December to May', 'Island hopping, diving, snorkeling, beach activities, cave exploration'),
('Cebu', 'Central Visayas', 'Cebu is a province of the Philippines located in the Central Visayas region and consists of a main island and surrounding islands. Its capital is Cebu City.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/14/8c/1f/dd/cebu-city.jpg?w=700&h=-1&s=1', 1, 1, 'January to May', 'Historical tours, island hopping, diving, whale shark watching, waterfalls'),
('Bohol', 'Central Visayas', 'Bohol is famous for coral reefs and unusual geological formations, notably the Chocolate Hills.', 'https://a.cdn-hotels.com/gdcs/production97/d1523/0103cc6c-791d-41c2-9d57-42fb0c4a0c09.jpg', 1, 1, 'January to May', 'Chocolate Hills tour, tarsier sanctuary, river cruise, beaches, diving'),
('Siargao', 'Caraga', 'Siargao is known as the "Surfing Capital of the Philippines" with the perfect surf break called Cloud 9.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/16/d4/1a/3c/sugba-lagoon.jpg?w=1200&h=-1&s=1', 1, 1, 'March to September', 'Surfing, island hopping, lagoon visits, diving, beach activities');

-- Insert attractions for each destination
-- Boracay Attractions
INSERT INTO attractions (destination_id, name, description, image_url, rating) VALUES
(1, 'White Beach', 'Famous 4km stretch of white sand beach.', 'https://images.unsplash.com/photo-1656521161419-ac6889a753f1?q=80&w=2075&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 4.8),
(1, 'Puka Beach', 'A quieter alternative to White Beach.', 'https://boracayinformer.com/wp-content/uploads/2023/06/pukabeach.jpg', 4.6),
(1, 'Ariel\'s Point', 'A popular day trip destination.', 'https://a.travel-assets.com/findyours-php/viewfinder/images/res70/186000/186744-Ariels-Point.jpg', 4.7);

-- Palawan Attractions
INSERT INTO attractions (destination_id, name, description, image_url, rating) VALUES
(2, 'Underground River', 'A navigable underground river.', 'https://static.wixstatic.com/media/43ce38_d9d9a0c56e7740a1978a6c9f17a3d3b9~mv2.jpg/v1/fill/w_1600,h_1071,al_c/43ce38_d9d9a0c56e7740a1978a6c9f17a3d3b9~mv2.jpg', 4.7),
(2, 'Honda Bay', 'Destination for island hopping.', 'https://gttp.images.tshiftcdn.com/408207/x/0/.jpg?w=360&h=220&fit=crop&crop=center&auto=format%2Ccompress&q=32&dpr=2&fm=pjpg&ixlib=react-9.8.1', 4.5);

-- Newly added packages for other destinations
INSERT INTO packages (destination_id, title, description, price, duration, max_travelers, itinerary, inclusions, exclusions, featured, discount_percent, created_by, status) VALUES
(1, 'Boracay Beach Getaway', 'Experience the stunning white sand beaches and vibrant nightlife of Boracay with this all-inclusive package.', 15000.00, 4, 10, 
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
Meals not specified', 1, 5, 2, 'active'),
(3, 'Cebu Island Adventure', 'Explore the rich culture and beautiful beaches of Cebu.', 15000.00, 5, 10, 
'Day 1: Arrival,
Day 2: Island Hopping, 
Day 3: city tour, 
Day 4: Waterfall tour, 
Day 5: Departure',
 'Accommodation, 
 Breakfast, 
 Sightseeing', 
 'Airfare', 1, 10, 2, 'active'),
(4, 'Bohol and Chocolate Hills Tour', 'Discover Bohol and its famous Chocolate Hills.', 12000.00, 3, 8, 
'Day 1: Arrival and tour, 
Day 2: Adventure activities, 
Day 3: Departure', 
'Accommodation, 
Breakfast', 
'Meals', 1, 5, 2, 'active'),
(5, 'Siargao Surfing Package', 'Surf the best waves in Siargao.', 18000.00, 4, 6, 
'Day 1: Arrival, 
Day 2: Surf lessons, 
Day 3: Island hopping, 
Day 4: Departure', 
'Accommodation,
Surfboard rental', 
'Meals', 1, 15, 2, 'active');