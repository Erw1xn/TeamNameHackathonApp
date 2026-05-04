-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: May 04, 2026 at 04:02 PM
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
-- Database: `schooldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `assignment_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `subject_id` int(11) DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`assignment_id`, `teacher_id`, `room_id`, `created_at`, `subject_id`, `day`, `start_time`, `end_time`) VALUES
(9, 16, 14, '2026-05-04 04:13:25', 9, 'Mon', '07:30:00', '10:30:00'),
(11, 20, 15, '2026-05-04 09:50:42', 11, 'Wed', '07:48:00', '07:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_users`
--

CREATE TABLE `faculty_users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_users`
--

INSERT INTO `faculty_users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `role`) VALUES
(7, 'Erwin', 'Jacaba', 'jacabaerwin15@gmail.com', '$2y$10$YF9.1YupwsI4PjY3lmy8JO5ZXEW8L7PzVOOWoPFMFQbqMIlIn8zly', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `has_ac` tinyint(1) DEFAULT 0,
  `has_projector` tinyint(1) DEFAULT 0,
  `is_lab` tinyint(1) DEFAULT 0,
  `day_mon` tinyint(1) DEFAULT 0,
  `day_tue` tinyint(1) DEFAULT 0,
  `day_wed` tinyint(1) DEFAULT 0,
  `day_thu` tinyint(1) DEFAULT 0,
  `day_fri` tinyint(1) DEFAULT 0,
  `day_sat` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `assigned_teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `capacity`, `has_ac`, `has_projector`, `is_lab`, `day_mon`, `day_tue`, `day_wed`, `day_thu`, `day_fri`, `day_sat`, `created_at`, `start_time`, `end_time`, `assigned_teacher_id`) VALUES
(14, 'Lab 01', 30, 0, 0, 1, 1, 0, 0, 0, 0, 0, '2026-05-04 04:12:18', '07:30:00', '10:30:00', NULL),
(15, 'Lab 02', 35, 1, 0, 0, 0, 1, 0, 0, 0, 0, '2026-05-04 04:13:59', '14:00:00', '17:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subj_code` varchar(255) NOT NULL,
  `units` int(11) NOT NULL,
  `type` enum('lecture','lab') NOT NULL,
  `expertise` varchar(100) NOT NULL,
  `req_ac` tinyint(1) DEFAULT 0,
  `req_proj` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subj_code`, `units`, `type`, `expertise`, `req_ac`, `req_proj`, `created_at`) VALUES
(9, 'Programming LAB 1', 30, 'lab', 'Programming', 0, 0, '2026-05-04 04:13:15'),
(10, 'Quantitative Methods', 30, 'lab', 'Math', 0, 0, '2026-05-04 04:14:24'),
(11, 'Science 2', 30, 'lab', 'General Science', 0, 0, '2026-05-04 09:45:44');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `instructor_name` varchar(255) NOT NULL,
  `ID` varchar(50) NOT NULL,
  `expertise` varchar(255) DEFAULT NULL,
  `availability` text DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `instructor_name`, `ID`, `expertise`, `availability`, `start_time`, `end_time`) VALUES
(16, 'Mr. Robert Cruz', '2023-23-2313', 'Programming', 'Mon', '07:30:00', '10:30:00'),
(20, 'Mrs. Arnel Santiago', '2023-23-2303', 'General Science', 'Wed', '07:48:00', '07:50:00'),
(21, 'Dr. Angelo Amican', 'T-9666', 'Math', 'Monday, Wednesday', '09:00:00', '12:00:00'),
(22, 'Mr. Michael Rodriguez', '2023-23-2309', 'Math', 'Wed', '06:54:00', '07:55:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`assignment_id`);

--
-- Indexes for table `faculty_users`
--
ALTER TABLE `faculty_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `faculty_users`
--
ALTER TABLE `faculty_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
