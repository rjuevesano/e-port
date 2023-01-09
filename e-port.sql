-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 09, 2023 at 04:17 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-port`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `user_id_client` int(11) NOT NULL,
  `user_id_supplier` int(11) NOT NULL,
  `schedule_date` datetime NOT NULL,
  `status` text NOT NULL,
  `note` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id_client`, `user_id_supplier`, `schedule_date`, `status`, `note`, `created`, `updated`) VALUES
(4, 21, 22, '2023-01-09 08:00:00', 'CANCELLED', 'Wedding anniversary', '2023-01-07 19:26:21', '2023-01-07 19:26:21'),
(5, 21, 22, '2023-01-09 13:00:00', 'COMPLETED', 'Wedding anniversary', '2023-01-07 19:31:23', '2023-01-07 19:31:23'),
(6, 23, 22, '2023-01-10 09:00:00', 'PENDING', 'Birthday party', '2023-01-07 19:37:38', '2023-01-07 19:37:38'),
(7, 21, 25, '2023-01-09 12:00:00', 'CANCELLED', 'Wedding', '2023-01-09 10:48:27', '2023-01-09 10:48:27'),
(8, 21, 25, '2023-01-09 12:00:00', 'PENDING', 'testtttt', '2023-01-09 10:57:00', '2023-01-09 10:57:00');

-- --------------------------------------------------------

--
-- Table structure for table `Comment`
--

CREATE TABLE `Comment` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Comment`
--

INSERT INTO `Comment` (`comment_id`, `user_id`, `post_id`, `message`, `created`, `updated`) VALUES
(30, 21, 11, 'test comment', '2023-01-07 19:21:11', '2023-01-07 19:21:11'),
(31, 22, 11, 'thank you comment', '2023-01-07 19:32:51', '2023-01-07 19:32:51'),
(32, 21, 12, 'testtt comment', '2023-01-09 10:47:05', '2023-01-09 10:47:05'),
(33, 25, 12, 'reply comment', '2023-01-09 10:47:31', '2023-01-09 10:47:31'),
(34, 21, 13, 'tetttt', '2023-01-09 10:59:10', '2023-01-09 10:59:10'),
(35, 21, 13, 'asdfasdf', '2023-01-09 10:59:11', '2023-01-09 10:59:11');

-- --------------------------------------------------------

--
-- Table structure for table `Image`
--

CREATE TABLE `Image` (
  `image_id` int(11) NOT NULL,
  `path` text NOT NULL,
  `created` date NOT NULL DEFAULT current_timestamp(),
  `updated` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Image`
--

INSERT INTO `Image` (`image_id`, `path`, `created`, `updated`) VALUES
(58, '1673090280_im2.jpg', '2023-01-07', '2023-01-07'),
(59, '1673090280_im6.jpg', '2023-01-07', '2023-01-07'),
(60, '1673232360_IMG_0477.jpg', '2023-01-09', '2023-01-09'),
(61, '1673232360_IMG_0478.jpg', '2023-01-09', '2023-01-09'),
(62, '1673232360_IMG_0479.jpg', '2023-01-09', '2023-01-09'),
(63, '1673232360_IMG_0735.jpg', '2023-01-09', '2023-01-09'),
(64, '1673233080_9603-200 copy.jpg', '2023-01-09', '2023-01-09'),
(65, '1673233080_27012341.jpg', '2023-01-09', '2023-01-09');

-- --------------------------------------------------------

--
-- Table structure for table `Likes`
--

CREATE TABLE `Likes` (
  `like_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created` date NOT NULL DEFAULT current_timestamp(),
  `updated` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Likes`
--

INSERT INTO `Likes` (`like_id`, `user_id`, `post_id`, `created`, `updated`) VALUES
(8, 21, 11, '2023-01-07', '2023-01-07'),
(9, 21, 12, '2023-01-09', '2023-01-09'),
(10, 21, 13, '2023-01-09', '2023-01-09');

-- --------------------------------------------------------

--
-- Table structure for table `Post`
--

CREATE TABLE `Post` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `caption` text NOT NULL,
  `image_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `status` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Post`
--

INSERT INTO `Post` (`post_id`, `user_id`, `caption`, `image_ids`, `status`, `created`, `updated`) VALUES
(11, 22, 'Coffee Photography', '[58,59]', 'PUBLISHED', '2023-01-07 19:18:54', '2023-01-07 19:18:54'),
(12, 25, 'Supplier 2 post', '[60,61,62,63]', 'PUBLISHED', '2023-01-09 10:46:06', '2023-01-09 10:46:06'),
(13, 25, 'Supplier 2 another post', '[64,65]', 'PUBLISHED', '2023-01-09 10:58:44', '2023-01-09 10:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `Rating`
--

CREATE TABLE `Rating` (
  `rating_id` int(11) NOT NULL,
  `user_id_client` int(11) NOT NULL,
  `user_id_supplier` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `rate` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Rating`
--

INSERT INTO `Rating` (`rating_id`, `user_id_client`, `user_id_supplier`, `message`, `rate`, `created`, `updated`) VALUES
(10, 21, 22, 'Quality', 5, '2023-01-07 19:22:33', '2023-01-07 19:22:33'),
(11, 21, 25, 'Neutral', 3, '2023-01-09 10:50:31', '2023-01-09 10:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `type` text NOT NULL,
  `status` text NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `mobile` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `facebook_url` text DEFAULT NULL,
  `portfolio_url` text DEFAULT NULL,
  `about` text DEFAULT NULL,
  `created` date NOT NULL DEFAULT current_timestamp(),
  `updated` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `type`, `status`, `firstname`, `lastname`, `mobile`, `address`, `avatar`, `facebook_url`, `portfolio_url`, `about`, `created`, `updated`) VALUES
(1, 'admin1', 'e00cf25ad42683b3df678c61f42c6bda', 'ADMIN', 'ACTIVE', 'Admin', '1', '', '', '1673089800_axehead.jpg', '', '', NULL, '2023-01-05', '2023-01-05'),
(20, 'admin2', 'c84258e9c39059a89ab77d846ddab909', 'ADMIN', 'ACTIVE', 'Admin', '2', NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-07', '2023-01-07'),
(21, 'client1', 'a165dd3c2e98d5d607181d0b87a4c66b', 'CLIENT', 'ACTIVE', 'Client', '1', '09121234567', 'Cebu City', '1673233440_27012341.jpg', '', '', NULL, '2023-01-07', '2023-01-07'),
(22, 'supplier1', 'bc2f63acd339f45e9575fe30e00950b3', 'SUPPLIER', 'APPROVED', 'Supplier', '1', '09121234567', 'Talisay City', NULL, '', '', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2023-01-07', '2023-01-07'),
(23, 'client2', '2c66045d4e4a90814ce9280272e510ec', 'CLIENT', 'ACTIVE', 'Client', '2', '09121234567', 'Cebu City', NULL, '', '', NULL, '2023-01-07', '2023-01-07'),
(24, 'client3', 'c27af3f6460eb10979adb366fc7f6856', 'CLIENT', 'ACTIVE', 'Client', '3', '09121234567', 'Cebu', NULL, '', '', NULL, '2023-01-07', '2023-01-07'),
(25, 'supplier2', 'f9e95ee553f7954b8bd113060450720d', 'SUPPLIER', 'APPROVED', 'Supplier', '2', '09121234567', 'Cebu City', '1673232660_AAD2A2F5-6901-44A6-A8A3-15546C89C995.PNG', 'http://www.facebook.com', 'http://www.facebook.com', 'Lorem', '2023-01-09', '2023-01-09'),
(26, 'admin3', '32cacb2f994f6b42183a1300d9a3e8d6', 'ADMIN', 'ACTIVE', 'Admin', '3', NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-09', '2023-01-09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `Comment`
--
ALTER TABLE `Comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `Image`
--
ALTER TABLE `Image`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `Likes`
--
ALTER TABLE `Likes`
  ADD PRIMARY KEY (`like_id`);

--
-- Indexes for table `Post`
--
ALTER TABLE `Post`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `Rating`
--
ALTER TABLE `Rating`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Comment`
--
ALTER TABLE `Comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `Image`
--
ALTER TABLE `Image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `Likes`
--
ALTER TABLE `Likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Post`
--
ALTER TABLE `Post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `Rating`
--
ALTER TABLE `Rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
