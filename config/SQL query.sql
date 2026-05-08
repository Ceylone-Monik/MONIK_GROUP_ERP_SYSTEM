-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2026 at 06:26 AM
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
-- Database: `monik_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Good',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`asset_id`, `branch_id`, `name`, `category`, `status`, `created_at`) VALUES
(6, 5, 'Land', 'Property', 'Good', '2026-05-06 04:26:13'),
(7, 5, 'Land 3', 'Property', 'Good', '2026-05-06 04:27:00'),
(8, 7, 'table', 'Furniture', 'Good', '2026-05-06 04:33:01');

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

CREATE TABLE `audits` (
  `audit_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `auditor_id` int(11) DEFAULT NULL,
  `score` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `audit_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audits`
--

INSERT INTO `audits` (`audit_id`, `branch_id`, `auditor_id`, `score`, `comments`, `audit_date`) VALUES
(7, 5, 1, 91, 'good', '2026-05-06'),
(8, 5, 1, 50, 'ok', '2026-05-01'),
(9, 5, 1, 20, 'bad', '2026-05-03'),
(10, 6, 1, 100, 'best', '2026-05-05');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `branch_id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `region` varchar(100) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`branch_id`, `org_id`, `name`, `region`, `status`) VALUES
(5, 2, 'Badulla', 'Uva', 'Active'),
(6, 2, 'Mahiyanganaya', 'Uva', 'Active'),
(7, 3, 'Badulla', 'Uva', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `doc_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `category` enum('Branch','Legal','Contract') NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`doc_id`, `branch_id`, `title`, `file_path`, `category`, `expiry_date`, `uploaded_by`, `uploaded_at`) VALUES
(2, 5, 'Document 1', '1778042374_???? MONIK GROUP ERP.pdf', 'Legal', '2026-05-04', 1, '2026-05-06 04:39:34');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `org_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(10) DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`org_id`, `name`, `short_name`, `industry`, `created_at`, `status`) VALUES
(2, 'Monik Agro Ventures Pvt Ltd', 'MNA', 'Agriculture Services', '2026-05-05 05:10:26', 'Active'),
(3, 'Monik Evermark Pvt Ltd', 'MNE', 'Constructions', '2026-05-05 05:10:26', 'Active'),
(4, 'Monik Lands Pvt Ltd', 'MNL', 'Property Need Solutions', '2026-05-05 05:10:26', 'Active'),
(5, 'Ceylon Monik Building Society Ltd', 'CMB', 'Financial Services', '2026-05-05 05:10:26', 'Active'),
(6, 'Monik Trading Pvt Ltd', 'MNT', 'Electronic Appliances Sales', '2026-05-05 05:10:26', 'Active'),
(7, 'Monik Water Pvt Ltd', 'MNW', 'Drinking bottled Water Manufacturing', '2026-05-05 05:10:26', 'Active'),
(8, 'Monik Homes Pvt Ltd', 'MNH', 'Furniture Manufacturing', '2026-05-05 05:10:26', 'Active'),
(9, 'Monik International Pvt Ltd', 'MNK', 'Financial Services', '2026-05-05 05:10:26', 'Active'),
(10, 'Commercial Micro Credit Investment Trust Pvt Ltd.', 'CMC', 'Financial Services', '2026-05-05 05:10:26', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Regional Manager'),
(3, 'Branch Manager'),
(4, 'Technician'),
(5, 'Auditor');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `asset_id` int(11) DEFAULT NULL,
  `issue_description` text NOT NULL,
  `priority` enum('Low','Medium','High','Urgent') DEFAULT 'Medium',
  `status` enum('New','In Progress','Completed') DEFAULT 'New',
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticket_id`, `asset_id`, `issue_description`, `priority`, `status`, `assigned_to`, `created_at`) VALUES
(8, 8, 'brocken leg', 'High', 'Completed', NULL, '2026-05-06 04:33:36'),
(9, 6, 'clean', 'Urgent', 'In Progress', NULL, '2026-05-06 04:38:41'),
(10, 6, 'clean 2', 'Medium', 'New', NULL, '2026-05-06 04:38:56'),
(11, 8, 'scratch', 'Medium', 'New', NULL, '2026-05-06 05:09:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `full_name`, `role_id`, `branch_id`) VALUES
(1, 'admin', '$2y$10$xOCKiBqy4Iwvv7KkA3icLuBBNQXtAQ3zeECQLd8jZdCiAnyxkVPkm', 'Pawan', 1, NULL),
(6, 'chamuditha@gmail.com', '$2y$10$l4f90HZUEt0q18.d77sReuKngl/yJ6j2WlqKzk6ObIukUhYv5boHG', 'chamuditha', 2, 5),
(7, 'sahan@gmail.com', '$2y$10$2FXee7Z8RzSvUA6YmRJvT.vot5lUllzUXEDTmOQS1EbfkrH8aWwfC', 'Sahan', 3, 6),
(8, 'nimsara@gmail.com', '$2y$10$t/3xCGtglFJBZ3WDhEqIq.kDgTXlZgfw5wlSRPT.GAWHcPYugM5py', 'Nimsara', 4, 7),
(9, 'dharmasena@gmail.com', '$2y$10$7Qr3sRXmV/w3eleqPkGWDO146YkZYSIb5kKChqNBq7J/cNBaez/cy', 'Dharmasena', 5, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `auditor_id` (`auditor_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`branch_id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`doc_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`org_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `asset_id` (`asset_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `audits`
--
ALTER TABLE `audits`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `org_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`);

--
-- Constraints for table `audits`
--
ALTER TABLE `audits`
  ADD CONSTRAINT `audits_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`),
  ADD CONSTRAINT `audits_ibfk_2` FOREIGN KEY (`auditor_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`),
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
