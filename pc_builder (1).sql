-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2025 at 09:47 AM
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
-- Database: `pc_builder`
--

-- --------------------------------------------------------

--
-- Table structure for table `builds`
--

CREATE TABLE `builds` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `components` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `builds`
--

INSERT INTO `builds` (`id`, `user_id`, `name`, `components`, `created_at`, `updated_at`) VALUES
(2, 1, 'ALLEN', '{\"CPU\":\"AMD Ryzen 9 5950X\"}', '2025-05-28 08:04:10', '2025-05-28 08:04:10'),
(3, 1, 'dfs', '{\"Storage\":\"Samsung 970 EVO Plus 1TB\"}', '2025-05-28 09:09:15', '2025-05-28 09:09:15'),
(4, 1, 'seniors', '[{\"type\":\"CPU\",\"name\":\"AMD Ryzen 5 5600X\",\"price\":\"699.99\",\"image\":\"http:\\/\\/localhost\\/pc_builder\\/images\\/AMD%20Ryzen%205%205600X.png\",\"specs\":\"16 Cores, 32 Threads, 3.4GHz Base Clock, 4.9GHz Boost Clock, 105W TDP\"},{\"type\":\"Motherboard\",\"name\":\"ASUS ROG STRIX B550-F\",\"price\":\"299.99\",\"image\":\"http:\\/\\/localhost\\/pc_builder\\/images\\/ASUS%20ROG%20STRIX%20B550-F.png\",\"specs\":\"AMD X570, ATX, DDR4, WiFi 6, PCIe 4.0\"},{\"type\":\"GPU\",\"name\":\"NVIDIA GeForce RTX 3080\",\"price\":\"799.99\",\"image\":\"http:\\/\\/localhost\\/pc_builder\\/images\\/NVIDIA%20GeForce%20RTX%203080.png\",\"specs\":\"10GB GDDR6X, PCIe 4.0, HDMI 2.1\"}]', '2025-06-03 05:27:48', '2025-06-03 05:27:48'),
(5, 1, 'IGANG', '[{\"type\":\"CPU\",\"name\":\"AMD Ryzen 5 5600X\",\"price\":\"699.99\",\"image\":\"http:\\/\\/localhost\\/pc_builder\\/images\\/AMD%20Ryzen%205%205600X.png\",\"specs\":\"16 Cores, 32 Threads, 3.4GHz Base Clock, 4.9GHz Boost Clock, 105W TDP\"},{\"type\":\"Motherboard\",\"name\":\"ASUS ROG STRIX B550-F\",\"price\":\"299.99\",\"image\":\"http:\\/\\/localhost\\/pc_builder\\/images\\/ASUS%20ROG%20STRIX%20B550-F.png\",\"specs\":\"AMD X570, ATX, DDR4, WiFi 6, PCIe 4.0\"}]', '2025-06-03 06:57:50', '2025-06-03 06:57:50');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `build_id` int(10) UNSIGNED DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(64) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `build_id`, `total_price`, `status`, `created_at`, `total`) VALUES
(10, 1, NULL, 0.00, 'pending', '2025-06-02 04:46:36', 529.97),
(11, 1, NULL, 0.00, 'pending', '2025-06-02 04:48:54', 699.99),
(12, 1, NULL, 0.00, 'pending', '2025-06-02 04:56:41', 699.99),
(13, 1, NULL, 0.00, 'pending', '2025-06-02 05:09:57', 899.98),
(14, 1, NULL, 0.00, 'pending', '2025-06-02 05:21:02', 699.99),
(15, 1, NULL, 0.00, 'pending', '2025-06-03 00:26:50', 999.98),
(16, 1, NULL, 0.00, 'pending', '2025-06-03 01:21:48', 699.99),
(17, 1, NULL, 0.00, 'pending', '2025-06-03 01:56:42', 999.98);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `component_type` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `specs` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `component_type`, `name`, `price`, `image`, `specs`) VALUES
(10, 10, 'Motherboard', 'ASUS ROG STRIX B550-F', 299.99, 'http://localhost/pc_builder/images/ASUS%20ROG%20STRIX%20B550-F.png', 'AMD X570, ATX, DDR4, WiFi 6, PCIe 4.0'),
(11, 10, 'RAM', 'Corsair Vengeance RGB Pro 32GB', 129.99, 'http://localhost/pc_builder/images/Corsair%20Vengeance%20RGB%20Pro%2032GB.png', '32GB (2x16GB) DDR4-3200, CL16'),
(12, 10, 'Storage', 'Samsung 970 EVO Plus 1TB', 99.99, 'http://localhost/pc_builder/images/Samsung%20970%20EVO%20Plus%201TB.png', '1TB NVMe M.2 SSD, PCIe Gen3, up to 3500MB/s'),
(13, 11, 'CPU', 'AMD Ryzen 5 5600X', 699.99, 'http://localhost/pc_builder/images/AMD%20Ryzen%205%205600X.png', '16 Cores, 32 Threads, 3.4GHz Base Clock, 4.9GHz Boost Clock, 105W TDP'),
(14, 12, 'CPU', 'AMD Ryzen 5 5600X', 699.99, 'http://localhost/pc_builder/images/AMD%20Ryzen%205%205600X.png', '16 Cores, 32 Threads, 3.4GHz Base Clock, 4.9GHz Boost Clock, 105W TDP'),
(15, 13, 'CPU', 'Intel Core i7-12700K', 599.99, 'http://localhost/pc_builder/images/Intel%20Core%20i7-12700K.png', '16 Cores (8P+8E), 24 Threads, 3.2GHz Base Clock, 5.2GHz Boost Clock, 125W TDP'),
(16, 13, 'Motherboard', 'ASUS ROG STRIX B550-F', 299.99, 'http://localhost/pc_builder/images/ASUS%20ROG%20STRIX%20B550-F.png', 'AMD X570, ATX, DDR4, WiFi 6, PCIe 4.0'),
(17, 14, 'CPU', 'AMD Ryzen 5 5600X', 699.99, 'http://localhost/pc_builder/images/AMD%20Ryzen%205%205600X.png', '16 Cores, 32 Threads, 3.4GHz Base Clock, 4.9GHz Boost Clock, 105W TDP'),
(18, 15, 'CPU', 'AMD Ryzen 5 5600X', 699.99, 'http://localhost/pc_builder/images/AMD%20Ryzen%205%205600X.png', '16 Cores, 32 Threads, 3.4GHz Base Clock, 4.9GHz Boost Clock, 105W TDP'),
(19, 15, 'Motherboard', 'ASUS ROG STRIX B550-F', 299.99, 'http://localhost/pc_builder/images/ASUS%20ROG%20STRIX%20B550-F.png', 'AMD X570, ATX, DDR4, WiFi 6, PCIe 4.0'),
(20, 16, 'CPU', 'AMD Ryzen 5 5600X', 699.99, 'http://localhost/pc_builder/images/AMD%20Ryzen%205%205600X.png', '16 Cores, 32 Threads, 3.4GHz Base Clock, 4.9GHz Boost Clock, 105W TDP'),
(21, 17, 'CPU', 'AMD Ryzen 5 5600X', 699.99, 'http://localhost/pc_builder/images/AMD%20Ryzen%205%205600X.png', '16 Cores, 32 Threads, 3.4GHz Base Clock, 4.9GHz Boost Clock, 105W TDP'),
(22, 17, 'Motherboard', 'ASUS ROG STRIX B550-F', 299.99, 'http://localhost/pc_builder/images/ASUS%20ROG%20STRIX%20B550-F.png', 'AMD X570, ATX, DDR4, WiFi 6, PCIe 4.0');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(512) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `specs` text DEFAULT NULL,
  `rating` float DEFAULT 0,
  `reviews` int(11) DEFAULT 0,
  `component_type` varchar(64) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `price`, `specs`, `rating`, `reviews`, `component_type`, `stock`, `created_at`, `updated_at`) VALUES
(1, 'AMD Ryzen 5 5600X', 'images\\AMD Ryzen 5 5600X.png', 699.99, '16 Cores, 32 Threads, 3.4GHz Base Clock, 4.9GHz Boost Clock, 105W TDP', 4.5, 128, 'CPU', 10, '2025-05-25 15:52:15', '2025-06-02 08:46:49'),
(2, 'Intel Core i7-12700K', 'images\\Intel Core i7-12700K.png', 599.99, '16 Cores (8P+8E), 24 Threads, 3.2GHz Base Clock, 5.2GHz Boost Clock, 125W TDP', 4, 97, 'CPU', 8, '2025-05-25 15:52:15', '2025-06-02 08:47:58'),
(3, 'ASUS ROG STRIX B550-F', 'images\\ASUS ROG STRIX B550-F.png', 299.99, 'AMD X570, ATX, DDR4, WiFi 6, PCIe 4.0', 4.7, 210, 'Motherboard', 15, '2025-05-25 15:52:15', '2025-06-02 08:48:11'),
(4, 'MSI MPG Z690 CARBON WIFI', 'images\\MSI MPG Z690 CARBON WIFI.png', 249.99, 'Intel Z690, ATX, DDR5, PCIe 5.0, 2.5G LAN', 4.3, 85, 'Motherboard', 12, '2025-05-25 15:52:15', '2025-06-02 08:48:29'),
(5, 'Corsair Vengeance RGB Pro 32GB', 'images\\Corsair Vengeance RGB Pro 32GB.png', 129.99, '32GB (2x16GB) DDR4-3200, CL16', 4.8, 340, 'RAM', 25, '2025-05-25 15:52:15', '2025-06-02 08:48:38'),
(6, 'G.Skill Trident Z RGB 16GB', 'images\\G.Skill Trident Z RGB 16GB.png', 159.99, '32GB (2x16GB) DDR5-6000, CL36', 4.6, 120, 'RAM', 18, '2025-05-25 15:52:15', '2025-06-02 08:49:02'),
(7, 'NVIDIA GeForce RTX 3080', 'images\\NVIDIA GeForce RTX 3080.png', 799.99, '10GB GDDR6X, PCIe 4.0, HDMI 2.1', 4.9, 500, 'GPU', 7, '2025-05-25 15:52:15', '2025-06-02 08:49:36'),
(8, 'AMD Radeon RX 6800', 'images\\AMD Radeon RX 6800.png', 749.99, '16GB GDDR6, PCIe 4.0, HDMI 2.1', 4.7, 320, 'GPU', 9, '2025-05-25 15:52:15', '2025-06-02 08:49:46'),
(9, 'Samsung 970 EVO Plus 1TB', 'images\\Samsung 970 EVO Plus 1TB.png', 99.99, '1TB NVMe M.2 SSD, PCIe Gen3, up to 3500MB/s', 4.8, 600, 'Storage', 30, '2025-05-25 15:52:15', '2025-06-02 08:49:55'),
(10, 'WD Black SN850X 2TB', 'images\\WD Black SN850X 2TB.png', 54.99, '2TB 3.5\" SATA HDD, 5400RPM', 4.5, 200, 'Storage', 40, '2025-05-25 15:52:15', '2025-06-02 08:50:11'),
(11, 'Corsair RM850x 850W', 'https://via.placeholder.com/200x150?text=Corsair+RM850x+850W', 139.99, '850W, 80+ Gold, Fully Modular', 4.9, 180, 'Power Supply', 14, '2025-05-25 15:52:15', '2025-05-25 15:52:15'),
(12, 'EVGA 600 BR 600W', 'https://via.placeholder.com/200x150?text=EVGA+600+BR+600W', 49.99, '600W, 80+ Bronze, Non-Modular', 4.2, 90, 'Power Supply', 20, '2025-05-25 15:52:15', '2025-05-25 15:52:15'),
(13, 'NZXT H510', 'https://via.placeholder.com/200x150?text=NZXT+H510', 79.99, 'ATX Mid Tower, Tempered Glass, White', 4.6, 220, 'Case', 16, '2025-05-25 15:52:15', '2025-05-25 15:52:15'),
(14, 'Cooler Master MasterBox Q300L', 'https://via.placeholder.com/200x150?text=MasterBox+Q300L', 49.99, 'MicroATX Mini Tower, Magnetic Dust Filters', 4.4, 110, 'Case', 22, '2025-05-25 15:52:15', '2025-05-25 15:52:15'),
(15, 'Noctua NH-D15', 'https://via.placeholder.com/200x150?text=Noctua+NH-D15', 89.99, 'Dual Tower Air Cooler, 2x140mm Fans', 4.9, 150, 'CPU Cooler', 11, '2025-05-25 15:52:15', '2025-05-25 15:52:15'),
(16, 'Corsair iCUE H100i RGB', 'https://via.placeholder.com/200x150?text=Corsair+iCUE+H100i+RGB', 119.99, '240mm Liquid Cooler, RGB, Intel/AMD', 4.7, 95, 'CPU Cooler', 13, '2025-05-25 15:52:15', '2025-05-25 15:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `address`, `created_at`, `last_login`, `avatar`) VALUES
(1, 'Allen Jomes Beronio', 'admin@gmail.com', '$2a$10$OCsQoztd8tcP0qL/AmbnDOFXGrjS.N5fH7/B4TIQ1tMofeQG4IMa.', NULL, NULL, '2025-06-01 13:51:03', NULL, NULL),
(15, 'ALLEN BERONIO', 'aj@gmail.com', '$2y$10$B85v2z4ngu9Zwg./MrBAe.om4xvm0urpfQvXo8F6bZlIuq2p1KLsu', NULL, NULL, '2025-06-01 13:51:03', NULL, NULL),
(16, 'AJ Beronio', 'ajey@gmail.com', '$2y$10$kcgXIWuFhD3AOqnl2ATCEuYDWNDBYvZG91dfxCJwNhBJDhEkjDLAO', '09222128018', 'DUAY, DUERO, BOHOL', '2025-06-01 13:51:03', NULL, 'uploads/avatars/avatar_16_1748933703.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `builds`
--
ALTER TABLE `builds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `builds`
--
ALTER TABLE `builds`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
