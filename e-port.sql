-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 19, 2023 at 09:04 AM
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
(37, 21, 18, 'hahahah', '2023-01-19 15:49:36', '2023-01-19 15:49:36'),
(38, 23, 18, 'greeet', '2023-01-19 15:49:40', '2023-01-19 15:49:40'),
(39, 25, 18, 'heeeheh', '2023-01-19 15:58:09', '2023-01-19 15:58:09');

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
(68, '1673244000_9603-200 copy.jpg', '2023-01-09', '2023-01-09'),
(69, '1673244000_17492805_1985718911444551_216000712957372490_o.jpg', '2023-01-09', '2023-01-09'),
(70, '1674109980_4_869cd6ce-7f7e-47b5-9888-b9340ea498a4_720x.jpg', '2023-01-19', '2023-01-19'),
(71, '1674113040_Untitled design.png', '2023-01-19', '2023-01-19');

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
(12, 25, 17, '2023-01-19', '2023-01-19'),
(13, 25, 18, '2023-01-19', '2023-01-19');

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
(15, 25, 'Testtt', '[68,69]', 'PUBLISHED', '2023-01-09 14:00:28', '2023-01-09 14:00:28'),
(16, 28, 'Testtt', '[70]', 'PUBLISHED', '2023-01-19 14:33:03', '2023-01-19 14:33:03'),
(17, 21, 'client post', '[71]', 'PUBLISHED', '2023-01-19 15:24:12', '2023-01-19 15:24:12'),
(18, 23, 'client22222', NULL, 'PUBLISHED', '2023-01-19 15:49:25', '2023-01-19 15:49:25');

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
  `file` text DEFAULT NULL,
  `about` text DEFAULT NULL,
  `created` date NOT NULL DEFAULT current_timestamp(),
  `updated` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `type`, `status`, `firstname`, `lastname`, `mobile`, `address`, `avatar`, `facebook_url`, `portfolio_url`, `file`, `about`, `created`, `updated`) VALUES
(1, 'admin1', 'e00cf25ad42683b3df678c61f42c6bda', 'ADMIN', 'ACTIVE', 'Admin', '1', '', '', '1673089800_axehead.jpg', '', '', NULL, NULL, '2023-01-05', '2023-01-05'),
(20, 'admin2', 'c84258e9c39059a89ab77d846ddab909', 'ADMIN', 'ACTIVE', 'Admin', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-07', '2023-01-07'),
(21, 'client1', 'a165dd3c2e98d5d607181d0b87a4c66b', 'CLIENT', 'ACTIVE', 'Client', '1', '09121234567', 'Cebu City', '1673233440_27012341.jpg', '', '', NULL, NULL, '2023-01-07', '2023-01-07'),
(22, 'supplier1', 'bc2f63acd339f45e9575fe30e00950b3', 'SUPPLIER', 'APPROVED', 'Supplier', '1', '09121234567', 'Talisay City', NULL, '', '', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2023-01-07', '2023-01-07'),
(23, 'client2', '2c66045d4e4a90814ce9280272e510ec', 'CLIENT', 'ACTIVE', 'Client', '2', '09121234567', 'Cebu City', NULL, '', '', NULL, NULL, '2023-01-07', '2023-01-07'),
(25, 'supplier2', 'f9e95ee553f7954b8bd113060450720d', 'SUPPLIER', 'APPROVED', 'Supplier', '2', '09121234567', 'Cebu City', '1673232660_AAD2A2F5-6901-44A6-A8A3-15546C89C995.PNG', 'http://www.facebook.com', 'http://www.facebook.com', '1674108540_XX859094 RICHARD BUENAVENTURA JUEVESANO[58].pdf', 'Lorem', '2023-01-09', '2023-01-09'),
(26, 'admin3', '32cacb2f994f6b42183a1300d9a3e8d6', 'ADMIN', 'ACTIVE', 'Admin', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-09', '2023-01-09'),
(28, 'supplier3', 'a9f10294d6a1f965fad9924ae613d999', 'SUPPLIER', 'APPROVED', 'Supplier', '3', '', '', NULL, '', '', '1674104640_ADMIN_-_Features.pdf', NULL, '2023-01-19', '2023-01-19');

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
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `Image`
--
ALTER TABLE `Image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `Likes`
--
ALTER TABLE `Likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `Post`
--
ALTER TABLE `Post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `Rating`
--
ALTER TABLE `Rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
