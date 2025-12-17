-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2025 at 01:28 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `support_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_account`
--

CREATE TABLE `admin_account` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_account`
--

INSERT INTO `admin_account` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$UJZDhv7NQWV2lKmkSkHFS.UhhLpMrNpAILxy9EQUtO6Ae.ABkg5/e'),
(2, 'admin2', 'admin2\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `gov_id` int(11) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `ext` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `location_type_id` int(11) DEFAULT NULL,
  `parent_location_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`location_id`, `location_name`, `location_type_id`, `parent_location_id`, `created_at`, `is_deleted`) VALUES
(3, 'Office of the Department Manager', 1, NULL, '2025-02-16 08:02:03', '0'),
(4, 'ICT Unit', 4, 7, '2025-02-16 08:02:03', '0'),
(5, 'Public Relation Office Unit', 4, 3, '2025-02-16 08:02:03', '0'),
(6, 'Legal Services', 4, 8, '2025-02-16 08:02:03', '0'),
(7, 'Office of the EOD Manager', 2, NULL, '2025-02-16 08:02:03', '0'),
(8, 'Office of the ADFIN Manager', 2, NULL, '2025-02-16 08:02:03', '0'),
(9, 'Administrative Section', 3, 24, '2025-02-16 08:02:03', '0'),
(10, 'Finance Section', 3, 24, '2025-02-16 08:02:03', '0'),
(11, 'Property Unit', 4, 9, '2025-02-16 08:02:03', '0'),
(12, 'General Services Security Unit ', 4, 9, '2025-02-16 08:02:03', '0'),
(13, 'Pantabangan Lake Resort and Hotel', 4, 9, '2025-02-16 08:02:03', '0'),
(14, 'Medical Services Unit', 4, 9, '2025-02-16 08:02:03', '0'),
(15, 'Cashiering Unit', 4, 10, '2025-02-16 08:02:03', '0'),
(16, 'Fisa Unit', 4, 10, '2025-02-16 08:02:03', '0'),
(18, 'Engineering Section', 3, 23, '2025-02-16 08:02:03', '0'),
(19, 'Operation Section', 3, 23, '2025-02-16 08:02:03', '0'),
(20, 'Equipment Management Section', 3, 23, '2025-02-16 08:02:03', '0'),
(21, 'Institutional Development Section', 3, 23, '2025-02-16 08:02:03', '0'),
(22, 'BAC Unit', 4, 3, '2025-02-18 07:24:43', '0'),
(23, 'Engineering and Operation Division', 2, NULL, '2025-03-03 06:38:42', '0'),
(24, 'Administrative and Finance Division', 2, NULL, '2025-03-03 06:44:12', '0'),
(25, 'Personnel and Records Unit', 4, 9, '2025-03-03 08:28:04', '0'),
(26, 'DM Secretary', 4, 3, '2025-03-03 08:30:12', '0'),
(27, 'DM Secretary', 4, 7, '2025-03-03 08:30:12', '0'),
(28, 'DM Secretary', 4, 8, '2025-03-03 08:30:12', '0');

-- --------------------------------------------------------

--
-- Table structure for table `location_type`
--

CREATE TABLE `location_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `location_type`
--

INSERT INTO `location_type` (`id`, `name`) VALUES
(1, 'Department'),
(2, 'Division'),
(3, 'Section'),
(4, 'Unit');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_activity`
--

CREATE TABLE `maintenance_activity` (
  `id` int(11) NOT NULL,
  `other_status` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `service_status_id` int(11) DEFAULT NULL,
  `personnel_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_personnel`
--

CREATE TABLE `maintenance_personnel` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `new_employee`
--

CREATE TABLE `new_employee` (
  `id` int(11) NOT NULL,
  `gov_id` int(11) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `ext` varchar(255) NOT NULL,
  `status` enum('accepted','rejected','pending') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `ref_no` varchar(255) NOT NULL,
  `status` enum('request','pending','completed','reject') DEFAULT 'request',
  `sub_category_id` int(11) DEFAULT NULL,
  `other_category` varchar(255) DEFAULT NULL,
  `location_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `emp_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `name`, `created_at`, `is_deleted`) VALUES
(1, 'Hardware Issue', '2025-02-25 16:39:38', '0'),
(2, 'Software Issue', '2025-02-25 16:39:38', '0'),
(3, 'Network and Connectivity', '2025-02-25 16:39:38', '0'),
(4, 'System Account and Access Management', '2025-02-25 16:39:38', '0'),
(5, 'Additional ICT Services', '2025-02-25 16:39:38', '0');

-- --------------------------------------------------------

--
-- Table structure for table `service_status`
--

CREATE TABLE `service_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `service_status`
--

INSERT INTO `service_status` (`id`, `name`) VALUES
(1, 'Resolved/Completed'),
(2, 'For Outsource Service'),
(3, 'For Parts Replacement');

-- --------------------------------------------------------

--
-- Table structure for table `sub_service`
--

CREATE TABLE `sub_service` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `service_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sub_service`
--

INSERT INTO `sub_service` (`id`, `name`, `service_id`, `created_at`, `is_deleted`) VALUES
(1, 'Computer/Desktop/Laptop Issues', 1, '2025-02-25 16:40:43', '0'),
(2, 'Printer/Scanner Problems', 1, '2025-02-25 16:40:43', '0'),
(3, 'Peripheral Devices (Mouse, Keyboard, Monitor)', 1, '2025-02-25 16:40:43', '0'),
(4, 'Network Equipment(Routers, Switches, Access Points)', 1, '2025-02-25 16:40:43', '0'),
(5, 'Others Hardware Repair and Troubleshooting', 1, '2025-02-25 16:40:43', '0'),
(6, 'Application erros', 2, '2025-02-25 16:41:24', '0'),
(7, 'License and Activation Issues', 2, '2025-02-25 16:41:24', '0'),
(8, 'Installation of New Software', 2, '2025-02-25 16:41:24', '0'),
(9, 'Virus Scanning and Removal', 2, '2025-02-25 16:41:24', '0'),
(10, 'File Backup Assistance', 2, '2025-02-25 16:41:24', '0'),
(11, 'Internet Connectivity Issues', 3, '2025-02-25 16:41:35', '0'),
(12, 'Slow Network Speed', 3, '2025-02-25 16:41:35', '0'),
(13, 'Records Management System Issue', 4, '2025-02-25 16:42:02', '0'),
(14, 'Online Leave Application System Issue', 4, '2025-02-25 16:42:02', '0'),
(15, 'Document Tracking System Issue', 4, '2025-02-25 16:42:02', '0'),
(16, 'Other Information System Issue', 4, '2025-02-25 16:42:02', '0'),
(17, 'LED Wall and Sound System Assistance', 5, '2025-02-25 16:42:40', '0'),
(18, 'Virtual Meeting Assistance', 5, '2025-02-25 16:42:40', '0'),
(19, 'CCTV Maintenance', 5, '2025-02-25 16:42:40', '0'),
(20, 'ID Printing Request', 5, '2025-02-25 16:42:40', '0'),
(29, 'testr2', 4, '2025-04-29 03:06:15', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_account`
--
ALTER TABLE `admin_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`location_id`),
  ADD KEY `parent_location_id` (`parent_location_id`),
  ADD KEY `location_location_type_id` (`location_type_id`);

--
-- Indexes for table `location_type`
--
ALTER TABLE `location_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_activity`
--
ALTER TABLE `maintenance_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_activity_service_status_fk` (`service_status_id`),
  ADD KEY `maintenance_activity_personnel_fk` (`personnel_id`),
  ADD KEY `maintenance_activity_request_fk` (`request_id`);

--
-- Indexes for table `maintenance_personnel`
--
ALTER TABLE `maintenance_personnel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new_employee`
--
ALTER TABLE `new_employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_sub_category_fk` (`sub_category_id`),
  ADD KEY `request_location_fk` (`location_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_status`
--
ALTER TABLE `service_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_service`
--
ALTER TABLE `sub_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_category_category_fk` (`service_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_account`
--
ALTER TABLE `admin_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `location_type`
--
ALTER TABLE `location_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `maintenance_activity`
--
ALTER TABLE `maintenance_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_personnel`
--
ALTER TABLE `maintenance_personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `new_employee`
--
ALTER TABLE `new_employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_status`
--
ALTER TABLE `service_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sub_service`
--
ALTER TABLE `sub_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_location_type_id` FOREIGN KEY (`location_type_id`) REFERENCES `location_type` (`id`),
  ADD CONSTRAINT `parent_location_id` FOREIGN KEY (`parent_location_id`) REFERENCES `location` (`location_id`);

--
-- Constraints for table `maintenance_activity`
--
ALTER TABLE `maintenance_activity`
  ADD CONSTRAINT `maintenance_activity_personnel_fk` FOREIGN KEY (`personnel_id`) REFERENCES `maintenance_personnel` (`id`),
  ADD CONSTRAINT `maintenance_activity_request_fk` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`),
  ADD CONSTRAINT `maintenance_activity_service_status_fk` FOREIGN KEY (`service_status_id`) REFERENCES `service_status` (`id`);

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_location_fk` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`),
  ADD CONSTRAINT `request_sub_category_fk` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_service` (`id`);

--
-- Constraints for table `sub_service`
--
ALTER TABLE `sub_service`
  ADD CONSTRAINT `sub_category_category_fk` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
