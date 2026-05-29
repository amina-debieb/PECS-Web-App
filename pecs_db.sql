-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2026 at 03:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pecs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` varchar(7) DEFAULT '#4CAF50',
  `icon_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `color`, `icon_path`) VALUES
(1, 'Nourriture', '#4CAF50', 'images/Nourriture.png'),
(2, 'Boissons', '#2196F3', NULL),
(3, 'Actions', '#FF9800', NULL),
(4, 'Sentiments', '#9C27B0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pictograms`
--

CREATE TABLE `pictograms` (
  `id` int(11) NOT NULL,
  `label` varchar(100) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pictograms`
--

INSERT INTO `pictograms` (`id`, `label`, `image_path`, `category_id`) VALUES
(1, 'Banane', 'images/Banane.png', 1),
(2, 'Fromage', 'images/Fromage.jpg', 1),
(3, 'Oeuf', 'images/Oeuf.png', 1),
(4, 'Pain', 'images/pain.png', 1),
(5, 'Pomme', 'images/pomme.jpg.png', 1),
(6, 'Eau', 'images/Eau.jpg.png', 2),
(7, 'Jus', 'images/jusjpg.png', 2),
(8, 'Lait', 'images/Lait.png', 2),
(9, 'Boire', 'images/boire.png', 3),
(10, 'Dormir', 'images/Dormir.jpg', 3),
(11, 'Jouer', 'images/Jouer.jpg.png', 3),
(12, 'Heureux', 'images/Heureux.jpg', 4),
(13, 'Pleure', 'images/Pleure.jpg', 4),
(14, 'Rit', 'images/Rit.jpg', 4),
(15, 'Triste', 'images/Triste.jpg.png', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `pictograms`
--
ALTER TABLE `pictograms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pictograms`
--
ALTER TABLE `pictograms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pictograms`
--
ALTER TABLE `pictograms`
  ADD CONSTRAINT `pictograms_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
