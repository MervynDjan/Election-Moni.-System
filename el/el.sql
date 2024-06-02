-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 19, 2024 at 09:05 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `el`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
CREATE TABLE IF NOT EXISTS `assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `agent_id` int NOT NULL,
  `polling_station_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_id` (`agent_id`),
  KEY `polling_station_id` (`polling_station_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `agent_id`, `polling_station_id`) VALUES
(1, 2, 3),
(2, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `polling_stations`
--

DROP TABLE IF EXISTS `polling_stations`;
CREATE TABLE IF NOT EXISTS `polling_stations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `registered_voters` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `polling_stations`
--

INSERT INTO `polling_stations` (`id`, `name`, `registered_voters`) VALUES
(1, 'Polling Station 1', 1000),
(2, 'Polling Station 2', 1500),
(3, 'Polling Station 3', 1200);

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
CREATE TABLE IF NOT EXISTS `submissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `agent_id` int NOT NULL,
  `polling_station_id` int NOT NULL,
  `party_www` int NOT NULL,
  `party_xxx` int NOT NULL,
  `party_yyy` int NOT NULL,
  `party_zzz` int NOT NULL,
  `media_proof` text,
  `video_proof` text,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `rejection_reason` text,
  PRIMARY KEY (`id`),
  KEY `polling_station_id` (`polling_station_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `agent_id`, `polling_station_id`, `party_www`, `party_xxx`, `party_yyy`, `party_zzz`, `media_proof`, `video_proof`, `status`, `rejection_reason`) VALUES
(1, 3, 2, 12, 13, 14, 15, 'images/1716109304_verified_xcic9g.png,images/1716109304_gaffvubpchmdfgimfhdw.png,images/1716109304_SEO-Background_ffveye.png', 'videos/1716109304_udKZ0XsjrVLBbjIU.mp4', 'approved', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('agent','admin') NOT NULL,
  `constituency` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `constituency`) VALUES
(1, 'q', '$2y$10$EqO48QmS7ZJ92S2tvcNzse9bLYHOtScEb6XMhSdJks54WexM9JzGq', 'admin', 'Const A'),
(2, 'w', '$2y$10$EvrdslV/52cxafvpGBTNsufleBeniUvs3y1HKyrqHOe7HIbGR/YL.', 'agent', 'Const A'),
(3, 'ww', '$2y$10$JL4gky2K82meUjtgfHxHVOGE5OmNcOkOVLv78D415Q7PN.GuegxGy', 'agent', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
