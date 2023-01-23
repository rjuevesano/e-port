-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 23, 2023 at 06:42 AM
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
(15, 72, 70, '2023-01-30 12:24:00', 'PENDING', 'Wedding Information', '2023-01-23 04:24:50', '2023-01-23 04:24:50'),
(16, 72, 70, '2023-01-11 12:30:00', 'PENDING', 'WEDDING PHOTOS', '2023-01-23 04:30:55', '2023-01-23 04:30:55');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `user_id`, `post_id`, `message`, `created`, `updated`) VALUES
(48, 30, 24, 'hi', '2023-01-22 11:38:22', '2023-01-22 11:38:22'),
(54, 1, 40, 'hihihihi\n', '2023-01-22 12:53:45', '2023-01-22 12:53:45'),
(55, 1, 36, 'akoka o\n', '2023-01-22 12:54:15', '2023-01-22 12:54:15'),
(56, 49, 50, 'nice', '2023-01-22 13:15:45', '2023-01-22 13:15:45'),
(57, 49, 50, 'nice uy', '2023-01-22 13:16:04', '2023-01-22 13:16:04'),
(58, 52, 50, 'kjbkjnkn', '2023-01-22 13:47:07', '2023-01-22 13:47:07'),
(59, 52, 50, 'kjbkj', '2023-01-22 13:47:07', '2023-01-22 13:47:07'),
(60, 52, 50, 'kjbkj', '2023-01-22 13:47:07', '2023-01-22 13:47:07'),
(61, 52, 50, 'kjbkj', '2023-01-22 13:47:07', '2023-01-22 13:47:07'),
(62, 52, 50, 'kjbkj', '2023-01-22 13:47:07', '2023-01-22 13:47:07'),
(63, 52, 50, 'kjbkj', '2023-01-22 13:47:07', '2023-01-22 13:47:07'),
(64, 52, 50, 'kjbkj', '2023-01-22 13:47:07', '2023-01-22 13:47:07'),
(65, 52, 50, 'kjbkj', '2023-01-22 13:47:07', '2023-01-22 13:47:07'),
(66, 52, 50, 'kjbkj', '2023-01-22 13:47:07', '2023-01-22 13:47:07'),
(67, 52, 50, 'kjbkj', '2023-01-22 13:47:07', '2023-01-22 13:47:07'),
(68, 52, 50, 'kjbkj', '2023-01-22 13:47:07', '2023-01-22 13:47:07'),
(69, 52, 50, 'kjbkjnkn', '2023-01-22 13:47:07', '2023-01-22 13:47:07'),
(70, 52, 50, 'gy', '2023-01-22 13:47:15', '2023-01-22 13:47:15'),
(71, 52, 50, 'kjbgkj', '2023-01-22 13:47:56', '2023-01-22 13:47:56'),
(79, 70, 76, 'PM SENT\n', '2023-01-22 19:46:32', '2023-01-22 19:46:32'),
(80, 73, 78, 'Dol', '2023-01-23 01:36:55', '2023-01-23 01:36:55'),
(81, 75, 84, 'okies', '2023-01-23 13:32:59', '2023-01-23 13:32:59'),
(82, 75, 84, 'heeey', '2023-01-23 13:33:14', '2023-01-23 13:33:14'),
(83, 75, 84, 'testttt', '2023-01-23 13:33:56', '2023-01-23 13:33:56');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `image_id` int(11) NOT NULL,
  `path` text NOT NULL,
  `created` date NOT NULL DEFAULT current_timestamp(),
  `updated` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`image_id`, `path`, `created`, `updated`) VALUES
(68, '1673244000_9603-200 copy.jpg', '2023-01-09', '2023-01-09'),
(69, '1673244000_17492805_1985718911444551_216000712957372490_o.jpg', '2023-01-09', '2023-01-09'),
(70, '1674109980_4_869cd6ce-7f7e-47b5-9888-b9340ea498a4_720x.jpg', '2023-01-19', '2023-01-19'),
(74, '1674384480_logo.jpg', '2023-01-22', '2023-01-22'),
(75, '1674385260_C14E98F7-52B6-4BBD-A698-CE78356687BF.jpeg', '2023-01-22', '2023-01-22'),
(76, '1674385260_C14E98F7-52B6-4BBD-A698-CE78356687BF.jpeg', '2023-01-22', '2023-01-22'),
(77, '1674385680_GPF00702.jpg', '2023-01-22', '2023-01-22'),
(93, '1674393300_20220830145823_IMG_9036.jpg', '2023-01-22', '2023-01-22'),
(95, '1674395040_Screenshot_20221217-105248.png', '2023-01-22', '2023-01-22'),
(96, '1674396360_IMG_3631.jpg', '2023-01-22', '2023-01-22'),
(97, '1674396360_IMG_3634.jpg', '2023-01-22', '2023-01-22'),
(98, '1674396360_IMG_3748.jpg', '2023-01-22', '2023-01-22'),
(99, '1674396360_IMG_3839.jpg', '2023-01-22', '2023-01-22'),
(100, '1674396360_IMG_3849.jpg', '2023-01-22', '2023-01-22'),
(101, '1674400920_Screenshot_20230122_105652.png', '2023-01-22', '2023-01-22'),
(102, '1674401940_299295.png', '2023-01-22', '2023-01-22'),
(106, '1674412440_IMG_9272.jpg', '2023-01-22', '2023-01-22'),
(107, '1674412440_IMG_9314.jpg', '2023-01-22', '2023-01-22'),
(108, '1674437760_images (6).jpeg', '2023-01-23', '2023-01-23'),
(109, '1674447000_istockphoto-1207046924-612x612.jpg', '2023-01-23', '2023-01-23'),
(110, '1674447000_istockphoto-1207046924-612x612.jpg', '2023-01-23', '2023-01-23'),
(111, '1674448080_IMG_3684.jpg', '2023-01-23', '2023-01-23'),
(112, '1674448080_IMG_3810.jpg', '2023-01-23', '2023-01-23');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created` date NOT NULL DEFAULT current_timestamp(),
  `updated` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `user_id`, `post_id`, `created`, `updated`) VALUES
(51, 30, 23, '2023-01-22', '2023-01-22'),
(64, 49, 50, '2023-01-22', '2023-01-22'),
(65, 52, 50, '2023-01-22', '2023-01-22'),
(66, 52, 50, '2023-01-22', '2023-01-22'),
(67, 52, 50, '2023-01-22', '2023-01-22'),
(68, 52, 50, '2023-01-22', '2023-01-22');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `message_id` int(11) NOT NULL,
  `user_id_client` int(11) NOT NULL,
  `user_id_supplier` int(11) NOT NULL,
  `is_main` tinyint(1) NOT NULL,
  `sender` text NOT NULL,
  `text` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`message_id`, `user_id_client`, `user_id_supplier`, `is_main`, `sender`, `text`, `created`, `updated`) VALUES
(65, 72, 73, 1, 'SUPPLIER', 'Dol', '2023-01-23 01:37:45', '2023-01-23 01:38:19'),
(66, 72, 73, 0, 'CLIENT', 'Hello dol', '2023-01-23 01:38:19', '2023-01-23 01:38:19'),
(67, 72, 70, 1, 'CLIENT', 'Hi! Sir, Looking for A Photographer', '2023-01-23 03:49:19', '2023-01-23 03:51:06'),
(68, 72, 70, 0, 'SUPPLIER', 'HELLO GOOD MORNING!', '2023-01-23 03:51:04', '2023-01-23 03:51:04'),
(69, 72, 70, 0, 'SUPPLIER', '', '2023-01-23 03:51:06', '2023-01-23 03:51:06'),
(70, 72, 70, 0, 'SUPPLIER', '', '2023-01-23 03:51:06', '2023-01-23 03:51:06'),
(71, 72, 76, 1, 'SUPPLIER', 'test', '2023-01-23 12:44:11', '2023-01-23 12:44:11'),
(72, 75, 76, 1, 'SUPPLIER', 'testttt', '2023-01-23 12:56:07', '2023-01-23 13:42:06'),
(73, 75, 76, 0, 'SUPPLIER', 'heeyy', '2023-01-23 12:56:29', '2023-01-23 12:56:29'),
(74, 75, 76, 0, 'SUPPLIER', 'okies', '2023-01-23 13:21:11', '2023-01-23 13:21:11'),
(75, 75, 76, 0, 'SUPPLIER', 'heeeeh', '2023-01-23 13:21:45', '2023-01-23 13:21:45'),
(76, 75, 76, 0, 'CLIENT', 'okies', '2023-01-23 13:23:04', '2023-01-23 13:23:04'),
(77, 75, 76, 0, 'SUPPLIER', '123213', '2023-01-23 13:31:27', '2023-01-23 13:31:27'),
(78, 75, 76, 0, 'SUPPLIER', 'heyyyy', '2023-01-23 13:42:06', '2023-01-23 13:42:06');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `is_read` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `message_id`, `is_read`, `created`, `updated`) VALUES
(1, 72, 1, '2023-01-23 12:56:07', '2023-01-23 12:56:07'),
(2, 73, 1, '2023-01-23 12:56:29', '2023-01-23 12:56:29'),
(3, 75, 1, '2023-01-23 13:21:45', '2023-01-23 13:21:45'),
(4, 76, 1, '2023-01-23 13:23:04', '2023-01-23 13:23:04'),
(5, 77, 1, '2023-01-23 13:31:27', '2023-01-23 13:31:27'),
(6, 78, 1, '2023-01-23 13:42:06', '2023-01-23 13:42:06');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `caption` text NOT NULL,
  `image_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `status` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`post_id`, `user_id`, `caption`, `image_ids`, `status`, `created`, `updated`) VALUES
(75, 68, 'Wedding Photographs', '[106,107]', 'PUBLISHED', '2023-01-22 18:34:33', '2023-01-22 18:34:33'),
(76, 71, 'Lf for a Photographer', NULL, 'PUBLISHED', '2023-01-22 19:45:54', '2023-01-22 19:45:54'),
(77, 71, 'lf bridal car', NULL, 'PUBLISHED', '2023-01-22 19:46:12', '2023-01-22 19:46:12'),
(78, 72, 'Good morning ', '[108]', 'PUBLISHED', '2023-01-23 01:36:27', '2023-01-23 01:36:27'),
(79, 72, 'Good morning', NULL, 'PUBLISHED', '2023-01-23 01:46:03', '2023-01-23 01:46:03'),
(80, 74, 'Good morning  everyone!', NULL, 'PUBLISHED', '2023-01-23 01:49:45', '2023-01-23 01:49:45'),
(81, 72, 'good day', '[109]', 'PUBLISHED', '2023-01-23 04:10:04', '2023-01-23 04:10:04'),
(82, 72, 'good day', '[110]', 'PUBLISHED', '2023-01-23 04:10:04', '2023-01-23 04:10:04'),
(83, 70, 'LOOKING FOR AFFORDABLE PHOTOS? PM US DIRECTLY OR SEND US MESSAGE HERE: 09485691629', '[111,112]', 'PUBLISHED', '2023-01-23 04:28:56', '2023-01-23 04:28:56'),
(84, 75, 'Client 1 test post', NULL, 'PUBLISHED', '2023-01-23 12:50:59', '2023-01-23 12:50:59'),
(85, 75, 'client 1 another test', NULL, 'PUBLISHED', '2023-01-23 13:34:58', '2023-01-23 13:34:58');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `user_id_client` int(11) NOT NULL,
  `user_id_supplier` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `rate` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`rating_id`, `user_id_client`, `user_id_supplier`, `message`, `rate`, `created`, `updated`) VALUES
(19, 72, 70, 'Nice Work Sir\n', 3, '2023-01-23 04:26:13', '2023-01-23 04:26:13');

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
(20, 'admin2', 'c84258e9c39059a89ab77d846ddab909', 'ADMIN', 'ACTIVE', 'Admin', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-07', '2023-01-07'),
(26, 'admin3', '32cacb2f994f6b42183a1300d9a3e8d6', 'ADMIN', 'ACTIVE', 'Admin', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-09', '2023-01-09'),
(66, 'Admin1', '2e33a9b0b06aa0a01ede70995674ee23', 'ADMIN', 'ACTIVE', 'Admin', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-22', '2023-01-22'),
(67, 'GLENNCOSE', '202cb962ac59075b964b07152d234b70', 'CLIENT', 'ACTIVE', 'GLENN', 'COSE', '09485691629', 'Tigbawan, Tabuelan, Cebu', NULL, '', '', '1674412260_', NULL, '2023-01-22', '2023-01-22'),
(68, 'NINO', '202cb962ac59075b964b07152d234b70', 'SUPPLIER', 'APPROVED', 'NINO', 'MONZOLIN', '12345678901', 'Tabuelan, Cebu', '1674412380_proof.jpg', 'https://www.facebook.com/Monzolin07', 'https://www.google.com/', '1674412320_crud php.docx', NULL, '2023-01-22', '2023-01-22'),
(69, 'GLENN', '202cb962ac59075b964b07152d234b70', 'CLIENT', 'ACTIVE', 'GLENN', 'COSE', '09485691629', 'Tigbawan, Tabuelan, Cebu', NULL, '', '', '1674414900_', NULL, '2023-01-22', '2023-01-22'),
(70, 'GLENN Photography and Film', '202cb962ac59075b964b07152d234b70', 'SUPPLIER', 'APPROVED', 'GLENN', 'COSE', '', 'Tigbawan, Tabuelan, Cebu', NULL, 'https://www.facebook.com/glenn.cose.9/', 'https://drive.google.com/drive/folders/1inqUllBTJEVuxAwMLKOD2leXpiEJ_4LU?usp=share_link', '1674416400_AXA PRIME QUOTATION.pdf', NULL, '2023-01-22', '2023-01-22'),
(71, '123', '202cb962ac59075b964b07152d234b70', 'CLIENT', 'ACTIVE', 'Nino', 'Monzolin', '09876543', 'Tabuelan', NULL, '', '', '1674416640_', NULL, '2023-01-22', '2023-01-22'),
(72, 'Heii123', '1d0ddb8d6fa486fc68fa31e26a47d1c7', 'CLIENT', 'ACTIVE', 'Heii', 'Loee', '09071190489', 'Saksak', '1674446880_download.jfif', '', '', '1674437220_', NULL, '2023-01-23', '2023-01-23'),
(73, 'Onin', '202cb962ac59075b964b07152d234b70', 'SUPPLIER', 'APPROVED', 'Nino', 'Monzolin', '9778236949', 'Tabuelan', '1674438120_FB_IMG_7798921739779541194.jpg', 'https://www.facebook.com/', 'https://www.google.com/', '1674437460_ERPFinal.pdf', '', '2023-01-23', '2023-01-23'),
(74, 'John123', 'a5391e96f8d48a62e8c85381df108e98', 'CLIENT', 'ACTIVE', 'John', 'One', '09071190465', 'Tuburan, Cebu', '1674438660_images.png', '', '', '1674438540_', NULL, '2023-01-23', '2023-01-23'),
(75, 'client1', 'a165dd3c2e98d5d607181d0b87a4c66b', 'CLIENT', 'ACTIVE', 'Client', 'One', '', '', '1674452220_27012341.jpg', '', '', '1674448380_', NULL, '2023-01-23', '2023-01-23'),
(76, 'supplier1', 'bc2f63acd339f45e9575fe30e00950b3', 'SUPPLIER', 'APPROVED', 'Supplier', 'One', '', '', '1674451800_4_869cd6ce-7f7e-47b5-9888-b9340ea498a4_720x.jpg', '', '', '1674448440_', NULL, '2023-01-23', '2023-01-23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
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
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
