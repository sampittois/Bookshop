-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2026 at 11:36 PM
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
-- Database: `bookshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `bio` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`, `bio`, `created_at`) VALUES
(1, 'Lynn Painter', '', '2025-11-30 21:57:22'),
(2, 'Stephen King', '', '2025-11-30 21:59:43'),
(3, 'Erin Morgenstern', '', '2025-11-30 22:00:52'),
(4, 'Rebecca Yarros', NULL, '2025-11-30 11:43:09'),
(5, 'Emily Henry', NULL, '2025-11-30 11:43:09'),
(6, 'SenLinYu', NULL, '2025-11-30 11:43:09'),
(7, 'Joe Abercrombie', NULL, '2025-11-30 11:43:09'),
(8, 'Bill Clinton', NULL, '2025-11-30 11:43:09'),
(9, 'James Patterson', NULL, '2025-11-30 11:43:09'),
(10, 'Abby Jimenez', NULL, '2025-11-30 11:43:09'),
(11, 'Clare Leslie Hall', NULL, '2025-11-30 11:43:09'),
(12, 'Lauren Roberts', NULL, '2025-11-30 11:43:09'),
(13, 'Freida McFadden', NULL, '2025-11-30 11:43:09'),
(14, 'Demo', NULL, '2026-01-07 23:14:03');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `category_id`, `title`, `description`, `isbn`, `price`, `cover_image`, `stock`, `created_at`) VALUES
(1, 1, 'Better Than the Movies', NULL, NULL, 14.99, 'img/better-than-the-movies.jpg', 5, '2025-11-23 16:29:33'),
(2, 2, 'Pet Sematary', NULL, NULL, 12.50, 'img/pet-sematary.jpg', 6, '2025-11-23 16:29:33'),
(3, 3, 'Powerless', NULL, NULL, 16.75, 'img/powerless.jpg', 3, '2025-11-23 16:29:33'),
(4, 3, 'Onyx Storm', 'Dramatic romantasy: a heroine fights political intrigue and dark magic to protect her people while discovering love and sacrifice.', NULL, 19.99, 'img/onyx-storm.jpg', 10, '2025-11-30 11:46:14'),
(5, 1, 'Great Big Beautiful Life', 'Two rival journalists compete to write the biography of a reclusive heiress — secrets, ambition and an unexpected romance ensue.', NULL, 17.50, 'img/great-big-beautiful-life.jpg', 10, '2025-11-30 11:46:14'),
(6, 3, 'Alchemised', 'Dark fantasy / dark romance: after a war, a former alchemist must reclaim her lost memories — but betrayal, power and grief blur the line between salvation and destruction.', NULL, 22.00, 'img/alchemised.jpg', 10, '2025-11-30 11:46:14'),
(7, 3, 'The Devils', 'Grimdark fantasy with multiple POVs — a perilous journey, moral grey zones, and epic magical conflict.', NULL, 21.99, 'img/the-devils.jpg', 10, '2025-11-30 11:46:14'),
(8, 2, 'The First Gentleman', 'Political thriller: a former NFL star–turned–First Gentleman is accused of a decades-old crime, threatening a presidency — suspense, scandal, and courtroom drama.', NULL, 18.99, 'img/the-first-gentleman.jpg', 10, '2025-11-30 11:46:14'),
(9, 1, 'Say You’ll Remember Me', 'A contemporary romance about love, memory, and sacrifice — two people forming a deep connection while navigating difficult personal circumstances.', NULL, 16.50, 'img/say-youll-remember-me.jpg', 10, '2025-11-30 11:46:14'),
(10, 1, 'Broken Country', 'Emotional fiction: a woman returns to her hometown and confronts secrets from her past when her first love reappears — grief, redemption, and heartache.', NULL, 16.99, 'img/broken-country.jpg', 10, '2025-11-30 11:46:14'),
(11, 3, 'Fearless', 'Romantic fantasy: in a kingdom rife with political tension, the heroine fights for love and survival — perfect for fans of magical kingdoms and high stakes romance.', NULL, 17.99, 'img/fearless.jpg', 10, '2025-11-30 11:46:14'),
(12, 3, 'Fourth Wing', 'Epic fantasy romance — the dragon-rider book that kicked off the “romantasy” boom and remains a top pick for readers craving magic, adventure and love.', NULL, 18.50, 'img/fourth-wing.jpg', 10, '2025-11-30 11:46:14'),
(13, 2, 'The Housemaid', 'Psychological thriller: an addictive page-turner about secrets, lies, and a shocking twist — ideal for thriller lovers who like suspense and dark secrets.', NULL, 14.99, 'img/the-housemaid.jpg', 10, '2025-11-30 11:46:14');

-- --------------------------------------------------------

--
-- Table structure for table `book_author`
--

CREATE TABLE `book_author` (
  `book_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_authors`
--

CREATE TABLE `book_authors` (
  `book_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_authors`
--

INSERT INTO `book_authors` (`book_id`, `author_id`) VALUES
(1, 1),
(2, 2),
(3, 12),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(8, 9),
(9, 10),
(10, 11),
(11, 12),
(12, 4),
(13, 13);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Romance', 'romance', '2025-11-23 16:29:14'),
(2, 'Thriller', 'thriller', '2025-11-23 16:29:14'),
(3, 'Fantasy', 'fantasy', '2025-11-23 16:29:14');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','shipped','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `created_at`) VALUES
(1, 312, 10.00, 'paid', '2026-01-07 23:28:45');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_each` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `book_id`, `rating`, `comment`, `created_at`) VALUES
(1, 312, 12, 5, 'Grote fan van de worlbuilding', '2026-01-07 23:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` datetime DEFAULT current_timestamp(),
  `balance` decimal(10,2) NOT NULL DEFAULT 100.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`, `balance`) VALUES
(341, 'Admin', 'admin@admin.com', '$2y$12$liAhF9WYFsZxtlj2TF84cuT7Cuoz2GRJc/4yKLIa531U41g879PSi', 'admin', '2026-01-07 23:34:56', 1000.00),
(342, 'User', 'user@user.com', '$2y$12$JR3TOP97ZWUeyiznuJPveeWjwfP0L9.lSSnj9.egG51XGrKvilA6.', 'customer', '2026-01-07 23:34:57', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `book_author`
--
ALTER TABLE `book_author`
  ADD PRIMARY KEY (`book_id`,`author_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `book_authors`
--
ALTER TABLE `book_authors`
  ADD PRIMARY KEY (`book_id`,`author_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

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
  ADD KEY `order_id` (`order_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`user_id`,`book_id`),
  ADD KEY `book_id` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=343;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `book_author`
--
ALTER TABLE `book_author`
  ADD CONSTRAINT `book_author_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_author_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `book_authors`
--
ALTER TABLE `book_authors`
  ADD CONSTRAINT `book_authors_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_authors_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
