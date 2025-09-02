-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2025 at 09:42 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `diamond_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `created_at`) VALUES
(1, 'Tiffany & Co', '2025-08-07 18:47:13'),
(2, 'Swarovski', '2025-08-07 18:47:20'),
(3, 'Pandora', '2025-08-07 18:47:27'),
(4, 'Cartier', '2025-08-07 18:47:34'),
(5, 'maria', '2025-08-07 18:47:42');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Earrings', '2025-08-07 18:46:42'),
(2, 'Necklace', '2025-08-07 18:46:50'),
(3, 'Bracelet', '2025-08-07 18:46:58');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `total_products` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `amount_due`, `invoice_number`, `total_products`, `order_date`, `order_status`, `created_at`, `updated_at`) VALUES
(1, 2, '1199.97', 'INV6894F9BB7AA5C', 3, '2025-08-07 19:08:43', 'Complete', '2025-08-07 19:08:43', '2025-08-07 19:20:10'),
(2, 2, '500.00', 'INV6894FAF39FDEC', 1, '2025-08-07 19:13:55', 'Pending (Offline)', '2025-08-07 19:13:55', '2025-08-07 19:13:55');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, '399.99', '2025-08-07 19:08:43', '2025-08-07 19:08:43'),
(2, 2, 4, 1, '500.00', '2025-08-07 19:13:55', '2025-08-07 19:13:55');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `invoice_number`, `amount`, `payment_mode`, `created_at`, `updated_at`) VALUES
(1, 1, 'INV6894F9BB7AA5C', 1199, 'Cash on Delivery', '2025-08-07 19:20:10', '2025-08-07 19:20:10');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `image_1` varchar(255) DEFAULT NULL,
  `image_2` varchar(255) DEFAULT NULL,
  `image_3` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `keywords`, `category_id`, `brand_id`, `image_1`, `image_2`, `image_3`, `price`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Attract elegant circle necklace', 'timeless and elegant with simple design', 'Attract elegant circle necklace', 1, 1, 'uploads/products/1754592639_1_attract elegant circle necklace  description-timeless and elegant with simple design.png', 'uploads/products/1754592639_2_attract elegant circle necklace (2).png', 'uploads/products/1754592639_3_attract elegant circle necklace.png', '399.99', '2025-08-07 18:50:39', '2025-08-07 18:50:39', 'active'),
(2, 'Family heart bracelet', 'family heart bracelet with hearts around it,so authentic and classy bracelet', 'family heart bracelet with hearts around it,so authentic and classy bracelet', 3, 2, 'uploads/products/1754592695_1_family heart bracelet                        description-family heart bracelet with hearts around it,so authentic and classy bracelet.png', 'uploads/products/1754592695_2_family heart bracelet (2).png', 'uploads/products/1754592695_3_family heart bracelet.png', '299.00', '2025-08-07 18:51:35', '2025-08-07 18:51:35', 'active'),
(3, 'Sparkle dance necklace', 'elegant and powerfull necklace with red diamond', 'elegant and powerfull necklace with red diamond Sparkle dance necklace', 2, 3, 'uploads/products/1754592730_1_gold shinning.png', 'uploads/products/1754592730_2_gold shinning1.png', 'uploads/products/1754592730_3_product title-sparkle dance necklace                  description-elegant and powerfull necklace with red diamond                   price-.png', '300.00', '2025-08-07 18:52:10', '2025-08-07 18:52:10', 'active'),
(4, 'Gold shinning necklace', 'classic with modern design,14 carat gold plating', 'classic with modern design,14 carat gold plating Gold shinning necklace', 3, 4, 'uploads/products/1754592761_1_product title-sparkle dance necklace (2).png', 'uploads/products/1754592761_2_product title-sparkle dance necklace                  description-elegant and powerfull necklace with red diamond                   price-.png', 'uploads/products/1754592761_3_product title-sparkle dance necklace.png', '500.00', '2025-08-07 18:52:41', '2025-08-07 18:52:41', 'active'),
(5, 'Rose gold sunshine earrings', 'Crafted in rose gold plating with sparkling white centerpieces', 'Crafted in rose gold plating with sparkling white centerpieces Rose gold sunshine earrings', 1, 5, 'uploads/products/1754592798_1_sunshine earings 2.png', 'uploads/products/1754592798_2_sunshine earings.png', 'uploads/products/1754592798_3_title-Constella cocktail ring            description-elegant and chik ring with 24 carat diamond.png', '700.00', '2025-08-07 18:53:18', '2025-08-07 18:53:18', 'active'),
(6, 'Constella cocktail ring', 'elegant and chik ring with 24 carat diamond Constella cocktail ring', 'elegant and chik ring with 24 carat diamond', 3, 3, 'uploads/products/1754592829_1_title-Constella cocktail ring.png', 'uploads/products/1754592829_2_title-Constella cocktail ring (2).png', 'uploads/products/1754592829_3_title-Crystal hoop silver earrings (2).png', '1200.00', '2025-08-07 18:53:49', '2025-08-07 18:53:49', 'active'),
(7, 'Sparkle diamond crown', 'shinning and powerfull silver ring', 'shinning and powerfull silver ring Sparkle diamond crown', 2, 5, 'uploads/products/1754595234_1_title-lovely earrings  heart                       description-so beauty and lovely earrings.png', 'uploads/products/1754595234_2_title-princess wish gold ring                 description-royal and elegant ring,set with half diamonds..png', 'uploads/products/1754595234_3_title-princess wish gold ring.png', '300.00', '2025-08-07 19:33:54', '2025-08-07 19:33:54', 'active'),
(8, 'Sparkle stars bracelet', 'inspired from the cosmos,full of beauty and classy', 'inspired from the cosmos,full of beauty and classy Sparkle stars bracelet', 1, 3, 'uploads/products/1754595265_1_title-sparkle stars bracelet                         description-inspired from the cosmos,full of beauty and classy.png', 'uploads/products/1754595265_2_title-sparkle stars bracelet.png', 'uploads/products/1754595265_3_title-sparkle diamond crown.png', '2100.00', '2025-08-07 19:34:25', '2025-08-07 19:34:25', 'active'),
(9, 'Sky diamond bracelet', 'so fantastic and feminine bracelet with touch of the sky', 'so fantastic and feminine bracelet with touch of the sky Sky diamond bracelet', 1, 2, 'uploads/products/1754595299_1_title-sky diamond bracelet.png', 'uploads/products/1754595299_2_title-sparkle diamond crown      description-shinning and powerfull silver ring.png', 'uploads/products/1754595299_3_title-sparkle diamond crown (2).png', '1200.00', '2025-08-07 19:34:59', '2025-08-07 19:34:59', 'active'),
(10, 'Princess wish gold ring', 'royal and elegant ring,set with half diamonds.', 'royal and elegant ring,set with half diamonds. Princess wish gold ring', 3, 3, 'uploads/products/1754595334_1_title-sparkle stars bracelet (2).png', 'uploads/products/1754595334_2_title-Constella cocktail ring            description-elegant and chik ring with 24 carat diamond.png', 'uploads/products/1754595334_3_sunshine earings.png', '3000.00', '2025-08-07 19:35:34', '2025-08-07 19:35:34', 'active'),
(11, 'Lovely earrings  heart', 'so beauty and lovely earrings', 'so beauty and lovely earrings Lovely earrings  heart', 3, 2, 'uploads/products/1754595365_1_title-gold shinning necklace  description-classic with modern design,14 carat gold plating.png', 'uploads/products/1754595365_2_title-princess wish gold ring.png', 'uploads/products/1754595365_3_title-sky diamond bracelet                      desccription-so fantastic and feminine bracelet with touch of the sky.png', '1500.00', '2025-08-07 19:36:05', '2025-08-07 19:36:05', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile_image`, `address`, `mobile`, `role`, `created_at`, `updated_at`) VALUES
(1, 'test', 'test@gmail.com', '$2y$10$vPgl8Gmxmreq8pNIa3FbJOhMIESLe1DZcuYa/DKzCmkUCs1JrGBkm', '1754591176_image.png', '11111', NULL, 'admin', '2025-08-07 18:26:16', '2025-08-07 18:27:55'),
(2, 'tester', 'tester@gmail.com', '$2y$10$E9Jjx/LsYyUVGy.leilwiemFuQsZ8CT.gncgTIpfNXtHxFwDCay7a', '1754593095_product title-sparkle dance necklace.png', 'test', NULL, 'customer', '2025-08-07 18:58:16', '2025-08-07 18:58:16'),
(3, 'smith', 'pyou0210@gmail.com', '$2y$10$wl2TzqPXhb/AFLUo5stUZuH7VE/q7m0/wUnEYuDPsfO6mplpZjDiq', '', '121212', NULL, 'customer', '2025-08-07 19:40:48', '2025-08-07 19:40:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shopping_cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
