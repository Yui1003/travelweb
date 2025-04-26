-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 03:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attractions`
--

CREATE TABLE `attractions` (
  `id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attractions`
--

INSERT INTO `attractions` (`id`, `destination_id`, `name`, `description`, `image_url`, `rating`, `created_at`) VALUES
(1, 1, 'White Beach', 'Famous 4km stretch of white sand beach.', 'https://images.unsplash.com/photo-1656521161419-ac6889a753f1?q=80&w=2075&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 4.8, '2025-04-26 09:08:14'),
(2, 1, 'Puka Beach', 'A quieter alternative to White Beach.', 'https://boracayinformer.com/wp-content/uploads/2023/06/pukabeach.jpg', 4.6, '2025-04-26 09:08:14'),
(3, 1, 'Ariel\'s Point', 'A popular day trip destination.', 'https://a.travel-assets.com/findyours-php/viewfinder/images/res70/186000/186744-Ariels-Point.jpg', 4.7, '2025-04-26 09:08:14'),
(4, 2, 'Underground River', 'A navigable underground river.', 'https://static.wixstatic.com/media/43ce38_d9d9a0c56e7740a1978a6c9f17a3d3b9~mv2.jpg/v1/fill/w_1600,h_1071,al_c/43ce38_d9d9a0c56e7740a1978a6c9f17a3d3b9~mv2.jpg', 4.7, '2025-04-26 09:08:14'),
(5, 2, 'Honda Bay', 'Destination for island hopping.', 'https://gttp.images.tshiftcdn.com/408207/x/0/.jpg?w=360&h=220&fit=crop&crop=center&auto=format%2Ccompress&q=32&dpr=2&fm=pjpg&ixlib=react-9.8.1', 4.5, '2025-04-26 09:08:14'),
(6, 6, 'Balite Falls - Amadeo', 'Tucked away in Amadeo, Cavite, Balite Falls is a serene natural paradise perfect for relaxation and adventure. Featuring twin cascading waterfalls surrounded by lush greenery, Balite Falls offers a refreshing escape from the city’s hustle. Its cool, crystal-clear waters form natural pools where visitors can swim, unwind, and enjoy the soothing sounds of nature. With picnic spots, shaded areas, and a peaceful ambiance, Balite Falls is ideal for family trips, barkada getaways, or romantic retreats. Experience Cavite’s hidden gem and reconnect with nature at Balite Falls!', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJZvJ15DMmiYjL0r4VNO1dZhh4zrocDI3R5Q&s', NULL, '2025-04-26 09:15:57'),
(7, 6, 'Tagaytay Highlands', 'Perched on a ridge overlooking Taal Volcano, Tagaytay is one of the Philippines’ most beloved travel destinations. Known for its cool climate, breathtaking landscapes, and cozy atmosphere, Tagaytay is perfect for both quick getaways and extended vacations. Enjoy sweeping views at the Taal Vista, explore the gardens of Picnic Grove and Sky Ranch, or savor local favorites like bulalo and fresh coffee from local farms. Whether you\'re seeking adventure, relaxation, or a romantic escape, Tagaytay offers a refreshing break from city life with a mix of nature, food, and fun.', 'https://images.unsplash.com/photo-1652788867857-0e75c2e78aeb?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', NULL, '2025-04-26 09:15:57');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `travel_date` date NOT NULL,
  `num_travelers` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `confirmation_number` varchar(20) DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `reference_number` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `package_id`, `booking_date`, `travel_date`, `num_travelers`, `total_price`, `status`, `confirmation_number`, `special_requests`, `payment_method`, `payment_status`, `reference_number`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2025-04-26', '2025-04-28', 1, 25000.00, 'confirmed', 'BK17456585172909', '', '', 'pending', NULL, '2025-04-26 09:08:37', '2025-04-26 13:08:17'),
(5, 3, 4, '2025-04-26', '2025-04-28', 1, 8000.00, 'confirmed', 'BK17456720041461', '', '0', 'pending', NULL, '2025-04-26 12:53:24', '2025-04-26 13:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `country` varchar(100) DEFAULT 'Philippines',
  `region` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `popular` tinyint(1) DEFAULT 0,
  `best_time_to_visit` varchar(100) DEFAULT NULL,
  `things_to_do` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `name`, `country`, `region`, `description`, `image_url`, `featured`, `popular`, `best_time_to_visit`, `things_to_do`, `created_at`, `updated_at`) VALUES
(1, 'Boracay', 'Philippines', 'Western Visayas', 'Boracay is a small island in the Philippines located approximately 315 km south of Manila and 2 km off the northwest tip of Panay Island in Western Visayas region. Boracay\'s White Beach is often cited as one of the world\'s best beaches.', 'https://static.saltinourhair.com/wp-content/uploads/2019/03/23151931/things-to-do-boracay-sunset-header.jpg', 1, 1, 'November to May', 'Beach activities, island hopping, water sports, dining, shopping, nightlife', '2025-04-26 09:08:14', '2025-04-26 09:08:14'),
(2, 'Palawan', 'Philippines', 'MIMAROPA', 'Palawan is an archipelagic province of the Philippines that is known as the Philippines\' Last Ecological Frontier and one of the most biodiverse islands in the Philippines.', 'https://images.squarespace-cdn.com/content/v1/5a87961cbe42d637c54cab93/1547736420211-X6E5VTDKUCB249SL1QNX/things-to-do-in-coron-palawan-1.jpg', 1, 1, 'December to May', 'Island hopping, diving, snorkeling, beach activities, cave exploration', '2025-04-26 09:08:14', '2025-04-26 09:08:14'),
(3, 'Cebu', 'Philippines', 'Central Visayas', 'Cebu is a province of the Philippines located in the Central Visayas region and consists of a main island and surrounding islands. Its capital is Cebu City.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/14/8c/1f/dd/cebu-city.jpg?w=700&h=-1&s=1', 1, 1, 'January to May', 'Historical tours, island hopping, diving, whale shark watching, waterfalls', '2025-04-26 09:08:14', '2025-04-26 09:08:14'),
(4, 'Bohol', 'Philippines', 'Central Visayas', 'Bohol is famous for coral reefs and unusual geological formations, notably the Chocolate Hills.', 'https://a.cdn-hotels.com/gdcs/production97/d1523/0103cc6c-791d-41c2-9d57-42fb0c4a0c09.jpg', 1, 1, 'January to May', 'Chocolate Hills tour, tarsier sanctuary, river cruise, beaches, diving', '2025-04-26 09:08:14', '2025-04-26 09:08:14'),
(5, 'Siargao', 'Philippines', 'Caraga', 'Siargao is known as the \"Surfing Capital of the Philippines\" with the perfect surf break called Cloud 9.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/16/d4/1a/3c/sugba-lagoon.jpg?w=1200&h=-1&s=1', 1, 1, 'March to September', 'Surfing, island hopping, lagoon visits, diving, beach activities', '2025-04-26 09:08:14', '2025-04-26 09:08:14'),
(6, 'Cavite', 'Philippines', '', 'Nestled just south of Metro Manila, Cavite is a vibrant blend of rich history, scenic landscapes, and thrilling adventures. Known as the &quot;Historical Capital of the Philippines,&quot; Cavite is home to iconic landmarks like the Aguinaldo Shrine, Corregidor Island, and charming heritage towns. Explore beautiful beaches in Ternate and Naic, trek the lush trails of Mount Pico de Loro, or unwind in cozy farm resorts scattered across the countryside. Whether you&#039;re a history buff, a nature lover, or a leisure traveler, Cavite offers an exciting escape filled with culture, flavor, and breathtaking experiences — all just a short drive from the city.', 'https://images.unsplash.com/photo-1679391890628-7db3e82ea70b?q=80&amp;w=2070&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 0, 0, NULL, NULL, '2025-04-26 09:15:57', '2025-04-26 09:15:57');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `replied_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_replies`
--

CREATE TABLE `message_replies` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `reply` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'in days',
  `max_travelers` int(11) DEFAULT 10,
  `itinerary` text DEFAULT NULL,
  `inclusions` text DEFAULT NULL,
  `exclusions` text DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `discount_percent` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive','sold-out') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `destination_id`, `title`, `description`, `price`, `duration`, `max_travelers`, `itinerary`, `inclusions`, `exclusions`, `featured`, `discount_percent`, `created_by`, `image_url`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 'Boracay Beach Getaway', 'Experience the stunning white sand beaches and vibrant nightlife of Boracay with this all-inclusive package.', 15000.00, 4, 10, 'Day 1: Arrival and Hotel Check-in\nDay 2: White Beach and Water Activities\nDay 3: Island Hopping Tour\nDay 4: Free Time and Departure', 'Accommodation\nDaily breakfast\nIsland hopping tour\nAirport transfers\nWelcome drink', 'Flights\nTravel insurance\nPersonal expenses\nOptional activities not mentioned\nMeals not specified', 1, 0, 2, NULL, '2025-04-26 09:08:14', '2025-04-26 09:08:14', 'active'),
(2, 2, 'Palawan Adventure', 'Discover the natural wonders of Palawan, from the Underground River to pristine beaches and limestone cliffs.', 25000.00, 5, 8, 'Day 1: Arrival in Puerto Princesa and Hotel Check-in\nDay 2: Underground River Tour\nDay 3: Honda Bay Island Hopping\nDay 4: City Tour and Firefly Watching\nDay 5: Free Time and Departure', 'Accommodation (4 nights)\nDaily breakfast\nUnderground River tour with lunch\nHonda Bay island hopping with lunch\nCity tour\nAirport transfers', 'Flights\nTravel insurance\nPersonal expenses\nOptional activities not mentioned\nMeals not specified', 1, 5, 2, NULL, '2025-04-26 09:08:14', '2025-04-26 09:08:14', 'active'),
(3, 3, 'Cebu Island Adventure', 'Explore the rich culture and beautiful beaches of Cebu.', 8000.00, 5, 10, 'Day 1: Arrival\r\nDay 2: Island Hopping \r\nDay 3: City Tour\r\nDay 4: Waterfall Tour\r\nDay 5: Departure', 'Accommodations\r\nBreakfast\r\nSightseeing', 'Airfare', 1, 0, 2, 'https://images.unsplash.com/photo-1548780772-e21fa3f2cfd7?q=80&amp;w=1932&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', '2025-04-26 09:08:14', '2025-04-26 10:27:14', 'active'),
(4, 4, 'Bohol and Chocolate Hills Tour', 'Discover Bohol and its famous Chocolate Hills.', 8000.00, 3, 10, 'Day 1: Arrival and Tour\r\nDay 2: Adventure Activities\r\nDay 3: Departure', 'Accommodations\r\nBreakfast', 'Meal', 1, 0, 2, 'https://images.unsplash.com/photo-1581521801296-5bdb5065472f?q=80&amp;w=1934&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', '2025-04-26 09:08:14', '2025-04-26 10:24:36', 'active'),
(5, 5, 'Siargao Surfing Package', 'Surf the best waves in Siargao.', 18000.00, 4, 10, 'Day 1: Arrival, \r\nDay 2: Surf lessons, \r\nDay 3: Island hopping, \r\nDay 4: Departure', 'Accommodation,\r\nSurfboard rental', 'Meals', 1, 0, 2, 'https://gttp.images.tshiftcdn.com/316099/x/0/surigao-del-norte-siargao-guyam-island-shutterstock-1177486879-min.jpg?w=380&amp;h=411&amp;fit=crop&amp;crop=center&amp;auto=compress&amp;q=62&amp;dpr=2&amp;fm=pjpg&amp;ixlib=react-9.8.1', '2025-04-26 09:08:14', '2025-04-26 10:13:42', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('approved','pending','rejected') DEFAULT 'approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`) VALUES
(1, 'Admin', '2025-04-26 09:08:14'),
(2, 'Tour Operator', '2025-04-26 09:08:14'),
(3, 'Traveler', '2025-04-26 09:08:14');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT 'default-user.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive','banned') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `full_name`, `username`, `email`, `password`, `phone`, `address`, `profile_img`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 'Admin User', 'admin', 'admin@wanderlust.com', '$2y$10$Jrjwyrxcn621teAORnZdCeAa/oXK/ez70XSeoA7ngFy5IxAGKFwKG', '+639123456789', NULL, 'default-user.jpg', '2025-04-26 09:08:14', '2025-04-26 09:08:14', 'active'),
(2, 2, 'Tour Operator', 'operator', 'operator@wanderlust.com', '$2y$10$UxIMrOjCLaBc1UJ6/5uRCuIfnNtuEkD4prnBKHkKKYMZlW5YumPmC', '+639123456790', NULL, 'default-user.jpg', '2025-04-26 09:08:14', '2025-04-26 09:08:14', 'active'),
(3, 3, 'Maurice Montano', 'user1', 'gawagawa1@gmail.com', '$2y$10$Qhd6Ovuq6kZ3sxCTGh8.aeVoi.VOh0WulVI9tP.aCj3Lm71P5ieAW', '09999999999', '', 'default-user.jpg', '2025-04-26 09:10:42', '2025-04-26 09:10:42', 'active'),
(4, 3, 'Jom', 'user2', 'admin1@lakwartsero.com', '$2y$10$rdm5E1wH4TkjehTABqeoL.Qyw12p8mo9L.OPz3WZ8/h/ESz/09U9e', '09999999998', '', 'default-user.jpg', '2025-04-26 12:34:19', '2025-04-26 12:34:19', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attractions`
--
ALTER TABLE `attractions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_id` (`destination_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `confirmation_number` (`confirmation_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_messages_to_user` (`to_user_id`);

--
-- Indexes for table `message_replies`
--
ALTER TABLE `message_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_id` (`destination_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attractions`
--
ALTER TABLE `attractions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `message_replies`
--
ALTER TABLE `message_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attractions`
--
ALTER TABLE `attractions`
  ADD CONSTRAINT `attractions_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_to_user` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `message_replies`
--
ALTER TABLE `message_replies`
  ADD CONSTRAINT `message_replies_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_replies_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`),
  ADD CONSTRAINT `packages_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
