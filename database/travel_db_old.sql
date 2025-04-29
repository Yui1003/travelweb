-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2025 at 12:16 PM
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
(7, 6, 'Tagaytay Highlands', 'Perched on a ridge overlooking Taal Volcano, Tagaytay is one of the Philippines’ most beloved travel destinations. Known for its cool climate, breathtaking landscapes, and cozy atmosphere, Tagaytay is perfect for both quick getaways and extended vacations. Enjoy sweeping views at the Taal Vista, explore the gardens of Picnic Grove and Sky Ranch, or savor local favorites like bulalo and fresh coffee from local farms. Whether you\'re seeking adventure, relaxation, or a romantic escape, Tagaytay offers a refreshing break from city life with a mix of nature, food, and fun.', 'https://images.unsplash.com/photo-1652788867857-0e75c2e78aeb?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', NULL, '2025-04-26 09:15:57'),
(8, 3, 'Carnaza Island', 'Carnaza Island is located off the northern coast of Cebu, a hidden gem for those seeking untouched beauty. This small, turtle-shaped island offers you crystal-clear waters, powdery white sand beaches, and vibrant marine life.\r\n\r\nLiog-Liog Twin Beach and the secluded Kailina Beach is where you can relax under the shade of swaying palm trees, enjoy a refreshing swim, or explore the island’s natural caves and rock formations. For adventurers, snorkelling and kayaking are must-try activities.\r\n\r\nBefore leaving, make sure to check out Skull Cave, where you can find an actual human skull! The locals believed that the cave was once used as a shelter for Japanese soldiers during World War 2. Combine with the views of the sunsets that are sure to leave a dramatic scene.', 'https://ik.imagekit.io/tvlk/blog/2025/01/Screenshot-2025-01-06-at-23.55.58.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2', NULL, '2025-04-27 08:09:58'),
(9, 3, 'Basílica del Santo Niño', 'The Basilica del Santo Niño is one of the oldest and most significant religious sites in the Philippines. Established in 1565, this beautiful church is home to the miraculous image of the Santo Niño de Cebu, a wooden statue of the child Jesus believed to have been brought by Ferdinand Magellan himself.\r\n\r\nThe Basilica features stunning architecture and is an important site for both religious and cultural events. Remember to visit the museum within the Basilica that showcases various artefacts, relics, and historical documents.', 'https://ik.imagekit.io/tvlk/blog/2025/01/Screenshot-2025-01-06-at-23.56.06.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2', NULL, '2025-04-27 08:09:58'),
(10, 3, 'Taoist Temple (Challenge to Climb the 81 Steps)', 'The Cebu Taoist Temple is not only a place of worship but also a stunning architectural masterpiece. Climbing the 81 steps, which symbolize the 81 chapters of Taoist scripture, is a serene experience. Visitors can light incense, have their fortune read, or simply admire the intricate dragon carvings and traditional Chinese desi\r\n\r\nThe main altar is adorned with numerous statues and images of Lao Tse and other deities and gods revered in Taoism. The temple is also home to the famous \"wishing well,\" where you can throw coins into the water and make a wish.\r\n\r\nRemember to dress modestly and respect the sanctity of the space while visiting the Cebu Taoist Temple. It is an extraordinary cultural and spiritual experience that should not be missed during your visit to Cebu.', 'https://ik.imagekit.io/tvlk/blog/2025/01/Screenshot-2025-01-06-at-23.56.26.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2', NULL, '2025-04-27 08:09:58'),
(11, 8, 'Hundred Islands National Park', 'This natural wonder is a must-visit destination, boasting over 100 limestone islands dotting the azure waters of the Lingayen Gulf.\r\n\r\nThe park offers a range of activities, from island hopping and snorkeling to zip-lining and hiking, catering to various interests and providing an unforgettable experience.', 'https://ik.imagekit.io/tvlk/blog/2024/10/Screenshot-2024-10-08-at-22.11.01-1024x681.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2', NULL, '2025-04-27 08:24:00'),
(12, 8, 'Bolinao Falls', 'Located in the town of Bolinao, these mesmerizing waterfalls are a refreshing sight for nature lovers. The cascading waters create inviting pools for swimming, and the surrounding area is perfect for picnics and relaxation.\r\n\r\nThe lush greenery and the sound of the falls make this a serene and rejuvenating spot. Also, you can explore different levels of the falls, each with its own charm.\r\n\r\nBolinao Falls 1, 2, and 3 are different from each other, offering a variety of swimming spots, from shallow pools to deeper ones where you can jump.', 'https://ik.imagekit.io/tvlk/blog/2024/10/Screenshot-2024-10-08-at-22.11.11-1024x681.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2', NULL, '2025-04-27 08:24:00'),
(13, 8, 'Cape Bolinao Lighthouse', 'A trip to Pangasinan would not be complete without a visit to the Cape Bolinao Lighthouse, which offers breathtaking panoramic views of the West Philippine Sea. Built in 1905, this historic lighthouse stands atop Punta Piedra Point in the town of Bolinao.\r\n\r\nIt is surrounded by a picturesque landscape, making it an ideal spot for photography enthusiasts and nature lovers alike. The lighthouse is also a romantic setting, perfect for watching the sunset with a loved one.', 'https://ik.imagekit.io/tvlk/blog/2024/10/Screenshot-2024-10-08-at-22.11.40-1024x681.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2', NULL, '2025-04-27 08:24:00');

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
  `payment_proof` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `package_id`, `booking_date`, `travel_date`, `num_travelers`, `total_price`, `status`, `confirmation_number`, `special_requests`, `payment_method`, `payment_status`, `reference_number`, `payment_proof`, `created_at`, `updated_at`) VALUES
(140, 3, 4, '2025-04-27', '2025-04-30', 1, 8000.00, 'confirmed', 'BK17457140359004', '', 'gcash', 'pending', NULL, 'uploads/receipts/gcash_receipt_680d7b737a311.jpg', '2025-04-27 00:33:55', '2025-04-27 00:37:33'),
(141, 3, 1, '2025-04-27', '2025-04-29', 1, 15000.00, 'pending', 'BK17457140586442', '', 'gcash', 'pending', NULL, 'uploads/receipts/gcash_receipt_680d7b8acd843.jpg', '2025-04-27 00:34:18', '2025-04-27 00:37:18'),
(142, 3, 1, '2025-04-27', '2025-04-30', 1, 15000.00, 'pending', 'BK17457140694833', '', 'bank_transfer', 'pending', NULL, 'uploads/receipts/bank_receipt_680d7b95b24b9.png', '2025-04-27 00:34:29', '2025-04-27 00:37:22');

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
(1, 'Boracay', 'Philippines', 'Western Visayas', 'Boracay is a small island in the Philippines located approximately 315 km south of Manila and 2 km off the northwest tip of Panay Island in Western Visayas region. Boracay&#039;s White Beach is often cited as one of the world&#039;s best beaches.', 'https://images.unsplash.com/photo-1553195029-754fbd369560?q=80&amp;w=2076&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 1, 1, 'November to May', 'Beach activities, island hopping, water sports, dining, shopping, nightlife', '2025-04-26 09:08:14', '2025-04-27 08:34:17'),
(2, 'Palawan', 'Philippines', 'MIMAROPA', 'Palawan is an archipelagic province of the Philippines that is known as the Philippines&#039; Last Ecological Frontier and one of the most biodiverse islands in the Philippines.', 'https://images.unsplash.com/photo-1518509562904-e7ef99cdcc86?q=80&amp;w=1974&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 1, 1, 'December to May', 'Island hopping, diving, snorkeling, beach activities, cave exploration', '2025-04-26 09:08:14', '2025-04-27 08:35:25'),
(3, 'Cebu', 'Philippines', 'Central Visayas', 'Cebu is a province of the Philippines located in the Central Visayas region and consists of a main island and surrounding islands. Its capital is Cebu City.', 'https://images.unsplash.com/photo-1495162048225-6b3b37b8a69e?q=80&amp;w=1933&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 1, 1, 'January to May', 'Historical tours, island hopping, diving, whale shark watching, waterfalls', '2025-04-26 09:08:14', '2025-04-27 08:35:02'),
(4, 'Bohol', 'Philippines', 'Central Visayas', 'Bohol is famous for coral reefs and unusual geological formations, notably the Chocolate Hills.', 'https://images.unsplash.com/photo-1591506578484-d496b18a6908?q=80&amp;w=2070&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 1, 1, 'January to May', 'Chocolate Hills tour, tarsier sanctuary, river cruise, beaches, diving', '2025-04-26 09:08:14', '2025-04-27 08:33:42'),
(5, 'Siargao', 'Philippines', 'Caraga', 'Siargao is known as the &quot;Surfing Capital of the Philippines&quot; with the perfect surf break called Cloud 9.', 'https://plus.unsplash.com/premium_photo-1707028781390-93bd5c1372c3?q=80&amp;w=2071&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 1, 1, 'March to September', 'Surfing, island hopping, lagoon visits, diving, beach activities', '2025-04-26 09:08:14', '2025-04-27 08:35:46'),
(6, 'Cavite', 'Philippines', '', 'Nestled just south of Metro Manila, Cavite is a vibrant blend of rich history, scenic landscapes, and thrilling adventures. Known as the &quot;Historical Capital of the Philippines,&quot; Cavite is home to iconic landmarks like the Aguinaldo Shrine, Corregidor Island, and charming heritage towns. Explore beautiful beaches in Ternate and Naic, trek the lush trails of Mount Pico de Loro, or unwind in cozy farm resorts scattered across the countryside. Whether you&#039;re a history buff, a nature lover, or a leisure traveler, Cavite offers an exciting escape filled with culture, flavor, and breathtaking experiences — all just a short drive from the city.', 'https://images.unsplash.com/photo-1679391890628-7db3e82ea70b?q=80&amp;w=2070&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 0, 0, NULL, NULL, '2025-04-26 09:15:57', '2025-04-26 09:15:57'),
(8, 'Pangasinan', 'Philippines', '', 'Pangasinan province in the Philippines offers a diverse range of tourist attractions, from beautiful beaches and islands to historic landmarks and natural wonders. Known for its rich culture, diverse heritage, and delicious food, Pangasinan is a great destination for a variety of travel interests.', 'https://ik.imagekit.io/tvlk/blog/2023/07/shutterstock_95133928.jpg?tr=q-70,c-at_max,w-500,h-250,dpr-2', 0, 0, NULL, NULL, '2025-04-27 08:24:00', '2025-04-27 08:30:24');

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

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `to_user_id`, `name`, `email`, `subject`, `message`, `status`, `created_at`, `replied_at`) VALUES
(11, 3, NULL, 'Maurice Montano', 'gawagawa1@gmail.com', 'Discount po idol', 'Hehehe', 'unread', '2025-04-27 00:24:15', NULL),
(12, 1, 3, 'Admin User', NULL, 'Re: Discount po idol', 'Tanga ka ba', 'unread', '2025-04-27 00:25:04', NULL);

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
(1, 1, 'Boracay Beach Getaway', 'Experience the stunning white sand beaches and vibrant nightlife of Boracay with this all-inclusive package.', 15000.00, 4, 10, 'Day 1: Arrival and Hotel Check-in\r\nDay 2: White Beach and Water Activities\r\nDay 3: Island Hopping Tour\r\nDay 4: Free Time and Departure', 'Accommodation\r\nDaily breakfast\r\nIsland hopping tour\r\nAirport transfers\r\nWelcome drink', 'Flights\r\nTravel insurance\r\nPersonal expenses\r\nOptional activities not mentioned\r\nMeals not specified', 1, 0, 2, 'https://images.unsplash.com/photo-1553195029-754fbd369560?q=80&amp;amp;w=2076&amp;amp;auto=format&amp;amp;fit=crop&amp;amp;ixlib=rb-4.0.3&amp;amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', '2025-04-26 09:08:14', '2025-04-27 08:55:00', 'active'),
(2, 2, 'Palawan Adventure', 'Discover the natural wonders of Palawan, from the Underground River to pristine beaches and limestone cliffs.', 25000.00, 5, 10, 'Day 1: Arrival in Puerto Princesa and Hotel Check-in\r\nDay 2: Underground River Tour\r\nDay 3: Honda Bay Island Hopping\r\nDay 4: City Tour and Firefly Watching\r\nDay 5: Free Time and Departure', 'Accommodations (4 nights)\r\nDaily Breakfast\r\nUnderground River tour with lunch\r\nHonda Bay island hopping with lunch\r\nCity tour\r\nAirport transfers', 'Flights\r\nTravel Insurance\r\nPersonal Expenses\r\nOptional activities not mentioned\r\nMeals not specified', 1, 0, 2, 'https://images.unsplash.com/photo-1584640161267-869f0aa03af6?q=80&amp;w=1964&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', '2025-04-26 09:08:14', '2025-04-27 08:52:48', 'active'),
(3, 3, 'Cebu Island Adventure', 'Explore the rich culture and beautiful beaches of Cebu.', 8000.00, 5, 10, 'Day 1: Arrival\r\nDay 2: Island Hopping \r\nDay 3: City Tour\r\nDay 4: Waterfall Tour\r\nDay 5: Departure', 'Accommodations\r\nBreakfast\r\nSightseeing', 'Airfare', 1, 0, 2, 'https://images.unsplash.com/photo-1548780772-e21fa3f2cfd7?q=80&amp;w=1932&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', '2025-04-26 09:08:14', '2025-04-26 10:27:14', 'active'),
(4, 4, 'Bohol and Chocolate Hills Tour', 'Discover Bohol and its famous Chocolate Hills.', 8000.00, 3, 10, 'Day 1: Arrival and Tour\r\nDay 2: Adventure Activities\r\nDay 3: Departure', 'Accommodations\r\nBreakfast', 'Meal', 1, 0, 2, 'https://images.unsplash.com/photo-1581521801296-5bdb5065472f?q=80&amp;w=1934&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', '2025-04-26 09:08:14', '2025-04-26 10:24:36', 'active'),
(5, 5, 'Siargao Surfing Package', 'Surf the best waves in Siargao.', 15000.00, 4, 10, 'Day 1: Arrival\r\nDay 2: Surfing\r\nDay 3: Island Hopping\r\nDay 4: Departure', 'Accommodations\r\nSurfboard Rental', 'Meals', 1, 0, 2, 'https://gttp.images.tshiftcdn.com/316099/x/0/surigao-del-norte-siargao-guyam-island-shutterstock-1177486879-min.jpg?w=380&amp;amp;h=411&amp;amp;fit=crop&amp;amp;crop=center&amp;amp;auto=compress&amp;amp;q=62&amp;amp;dpr=2&amp;amp;fm=pjpg&amp;amp;ixlib=rea', '2025-04-26 09:08:14', '2025-04-27 08:47:56', 'active'),
(7, 6, 'Tagaytay City', 'g', 500.00, 1, 10, '1', '1', '1', 0, 0, 1, 'https://images.unsplash.com/photo-1604237233847-0cd10a179521?q=80&amp;w=2070&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', '2025-04-27 08:15:07', '2025-04-27 08:44:57', 'active'),
(11, 6, 'Balite Adventures', 'In the historical province of Cavite the Majestic Balite Falls lies in Barangay Halang and Barangay Banay-Banay in Amadeo, it comes with a variety of natural resources, species, and natural beauty which is considered as one of the Nature Wonder tourist attractions in Cavite.', 1500.00, 1, 10, 'Day 1: Arrival and Activities', 'Entrance Fee\r\nEco Fee\r\nCottage', 'Meals\r\nTravel Expenses', 1, 0, 2, 'https://shoestringdiary.wordpress.com/wp-content/uploads/2017/08/balite_falls14newss_diaries.jpg', '2025-04-27 09:33:51', '2025-04-27 10:12:17', 'active');

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
(1, 1, 'Admin User', 'admin', 'admin@lakwartsero.com', '$2y$10$Jrjwyrxcn621teAORnZdCeAa/oXK/ez70XSeoA7ngFy5IxAGKFwKG', '+639123456789', NULL, 'default-user.jpg', '2025-04-26 09:08:14', '2025-04-27 09:11:31', 'active'),
(2, 2, 'Tour Operator', 'operator', 'operator@lakwartsero.com', '$2y$10$UxIMrOjCLaBc1UJ6/5uRCuIfnNtuEkD4prnBKHkKKYMZlW5YumPmC', '+639123456790', NULL, 'default-user.jpg', '2025-04-26 09:08:14', '2025-04-27 09:11:50', 'active'),
(3, 3, 'Maurice Montano', 'user1', 'gawagawa1@gmail.com', '$2y$10$Qhd6Ovuq6kZ3sxCTGh8.aeVoi.VOh0WulVI9tP.aCj3Lm71P5ieAW', '09999999999', '', 'default-user.jpg', '2025-04-26 09:10:42', '2025-04-26 09:10:42', 'active'),
(5, 3, 'admin', 'fakeadmin', 'j.jomarie1435@gmail.com', '$2y$10$SVxzvGnM7iy1AFZZfVLz9Oqqprv07cZxbPgeCKRzcqvHBjBJAv21q', '09997914791', '', 'default-user.jpg', '2025-04-27 09:07:24', '2025-04-27 09:07:24', 'active');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `message_replies`
--
ALTER TABLE `message_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
