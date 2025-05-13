-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 06:45 PM
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
-- Database: `library_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_details`
--

CREATE TABLE `admin_details` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_level` enum('super','regular') NOT NULL,
  `department` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `publisher` varchar(100) DEFAULT NULL,
  `publication_year` int(11) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `edition` varchar(20) DEFAULT NULL,
  `total_copies` int(11) NOT NULL DEFAULT 1,
  `available_copies` int(11) NOT NULL DEFAULT 1,
  `shelf_location` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `added_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_issues`
--

CREATE TABLE `book_issues` (
  `issue_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `issue_date` datetime DEFAULT current_timestamp(),
  `due_date` datetime NOT NULL,
  `return_date` datetime DEFAULT NULL,
  `issued_by` int(11) NOT NULL,
  `returned_to` int(11) DEFAULT NULL,
  `status` enum('issued','returned','overdue','lost') DEFAULT 'issued'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_reservations`
--

CREATE TABLE `book_reservations` (
  `reservation_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `reservation_date` datetime DEFAULT current_timestamp(),
  `expiry_date` datetime NOT NULL,
  `status` enum('pending','fulfilled','cancelled','expired') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `fine_id` int(11) NOT NULL,
  `issue_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `fine_amount` decimal(10,2) NOT NULL,
  `fine_date` datetime DEFAULT current_timestamp(),
  `fine_reason` enum('overdue','damage','lost') NOT NULL,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `payment_date` datetime DEFAULT NULL,
  `status` enum('pending','partially_paid','paid') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member_details`
--

CREATE TABLE `member_details` (
  `member_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `membership_number` varchar(20) NOT NULL,
  `membership_type` enum('regular','premium','student','faculty') NOT NULL,
  `membership_start_date` date NOT NULL,
  `membership_end_date` date DEFAULT NULL,
  `max_books_allowed` int(11) DEFAULT 5,
  `student_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_details`
--

INSERT INTO `member_details` (`member_id`, `user_id`, `membership_number`, `membership_type`, `membership_start_date`, `membership_end_date`, `max_books_allowed`, `student_id`) VALUES
(3, 18, 'MEM000018', 'regular', '2025-04-29', NULL, 5, NULL),
(4, 19, 'MEM000019', 'regular', '2025-04-29', NULL, 5, NULL),
(5, 20, 'MEM000020', 'regular', '2025-04-29', NULL, 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_bookings`
--

CREATE TABLE `room_bookings` (
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `booking_date` datetime DEFAULT current_timestamp(),
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `booked_by` int(11) NOT NULL,
  `status` enum('reserved','checked_in','checked_out','cancelled') DEFAULT 'reserved',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_rooms`
--

CREATE TABLE `study_rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `amenities` text DEFAULT NULL,
  `status` enum('available','maintenance','reserved') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_affected` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `action_timestamp` datetime DEFAULT current_timestamp(),
  `ip_address` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `user_type` enum('admin','librarian','member') NOT NULL,
  `registration_date` datetime DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `first_name`, `last_name`, `phone`, `address`, `user_type`, `registration_date`, `last_login`, `status`) VALUES
(18, 'Khairul', '$2y$10$pQ2OktGxYi2FdFsJrcMLHuQzbQgnUqfhPZMgHS0tJtIBzDgibb7k2', 'khairulamin131@gmail.com', 'khairul', 'Amin', '0989878', 'hdakjDKJnadkjn', 'member', '2025-04-29 17:29:37', NULL, 'active'),
(19, 'jonson', '$2y$10$jdb65PCXbnqm.1u6v5MzBeVZ1KYQ2Vndlf2E.iOK2E5B7dTmRir1u', 'jon@gmail.com', 'jon', 'son', '019**********8', 'Narayanganj', 'member', '2025-04-29 23:34:18', NULL, 'active'),
(20, 'abul', '$2y$10$MKTVLnfeNQugfHOTGXUwGepTw6.2/JywD0Ppi0M/ZCWvR/S8x0aY.', 'abul@gmail.com', 'abul', 'bashar', '01*********', 'Zila porishad', 'member', '2025-04-30 01:02:43', NULL, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_details`
--
ALTER TABLE `admin_details`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `book_issues`
--
ALTER TABLE `book_issues`
  ADD PRIMARY KEY (`issue_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `issued_by` (`issued_by`),
  ADD KEY `returned_to` (`returned_to`);

--
-- Indexes for table `book_reservations`
--
ALTER TABLE `book_reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `parent_category_id` (`parent_category_id`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`fine_id`),
  ADD KEY `issue_id` (`issue_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `member_details`
--
ALTER TABLE `member_details`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `membership_number` (`membership_number`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `room_bookings`
--
ALTER TABLE `room_bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `booked_by` (`booked_by`);

--
-- Indexes for table `study_rooms`
--
ALTER TABLE `study_rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_details`
--
ALTER TABLE `admin_details`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `book_issues`
--
ALTER TABLE `book_issues`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `book_reservations`
--
ALTER TABLE `book_reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `fine_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member_details`
--
ALTER TABLE `member_details`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `room_bookings`
--
ALTER TABLE `room_bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_rooms`
--
ALTER TABLE `study_rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_details`
--
ALTER TABLE `admin_details`
  ADD CONSTRAINT `admin_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `book_issues`
--
ALTER TABLE `book_issues`
  ADD CONSTRAINT `book_issues_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `book_issues_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `member_details` (`member_id`),
  ADD CONSTRAINT `book_issues_ibfk_3` FOREIGN KEY (`issued_by`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `book_issues_ibfk_4` FOREIGN KEY (`returned_to`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `book_reservations`
--
ALTER TABLE `book_reservations`
  ADD CONSTRAINT `book_reservations_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `book_reservations_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `member_details` (`member_id`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_ibfk_1` FOREIGN KEY (`issue_id`) REFERENCES `book_issues` (`issue_id`),
  ADD CONSTRAINT `fines_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `member_details` (`member_id`);

--
-- Constraints for table `member_details`
--
ALTER TABLE `member_details`
  ADD CONSTRAINT `member_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `room_bookings`
--
ALTER TABLE `room_bookings`
  ADD CONSTRAINT `room_bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `study_rooms` (`room_id`),
  ADD CONSTRAINT `room_bookings_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `member_details` (`member_id`),
  ADD CONSTRAINT `room_bookings_ibfk_3` FOREIGN KEY (`booked_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
