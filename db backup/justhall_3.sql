-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 11:30 PM
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
-- Database: `justhall`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_department`
--

CREATE TABLE `tbl_department` (
  `department_id` int(11) NOT NULL,
  `status` int(11) DEFAULT 0,
  `department_name` text DEFAULT NULL,
  `department_short_form` varchar(100) DEFAULT NULL,
  `department_total_student` int(11) DEFAULT 0,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_by` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_department`
--

INSERT INTO `tbl_department` (`department_id`, `status`, `department_name`, `department_short_form`, `department_total_student`, `created`, `modified`, `modified_by`) VALUES
(1, 1, 'Computer Science and Engineering', 'CSE', 200, '2025-04-16 21:29:41', '2025-04-19 16:01:05', 0),
(2, 1, 'Industrial and Production Engineering', 'IPE', 50, '2025-04-16 21:29:41', '2025-04-19 16:01:51', 0),
(3, 1, 'Petroleum and Mining Engineering', 'PME', 60, '2025-04-16 21:29:41', '2025-04-19 16:01:54', 0),
(4, 1, 'Chemical Engineering', 'CHE', 70, '2025-04-16 21:29:41', '2025-04-19 16:01:57', 0),
(5, 1, 'Electrical and Electronic Engineering', 'EEE', 80, '2025-04-16 21:29:41', '2025-04-19 16:02:01', 0),
(6, 1, 'Biomedical Engineering', 'BME', 80, '2025-04-16 21:29:41', '2025-04-19 16:02:05', 0),
(7, 1, 'Textile Engineering', 'TE', 60, '2025-04-16 21:29:41', '2025-04-19 16:02:15', 0),
(8, 1, 'Microbiology', 'MB', 50, '2025-04-16 21:29:41', '2025-04-19 16:02:20', 0),
(9, 1, 'Fisheries and Marine Bioscience', 'FMB', 50, '2025-04-16 21:29:41', '2025-04-19 16:02:29', 0),
(10, 1, 'Genetic Engineering and Biotechnology', 'GEBT', 65, '2025-04-16 21:29:41', '2025-04-19 16:02:57', 0),
(11, 1, 'Pharmacy', 'PHARM', 40, '2025-04-16 21:29:41', '2025-04-19 16:03:01', 0),
(12, 1, 'Biochemistry and Molecular Biology', 'BMB', 50, '2025-04-16 21:29:41', '2025-04-19 16:03:06', 0),
(13, 1, 'Environmental Science and Technology', 'EST', 55, '2025-04-16 21:29:41', '2025-04-19 16:03:12', 0),
(14, 1, 'Nutrition and Food Technology', 'NFT', 75, '2025-04-16 21:29:41', '2025-04-19 16:03:24', 0),
(15, 1, 'Food Engineering', 'FE', 60, '2025-04-16 21:29:41', '2025-04-19 16:03:51', 0),
(16, 1, 'Climate and Disaster Management', 'CDM', 40, '2025-04-16 21:29:41', '2025-04-19 16:04:06', 0),
(17, 1, 'Physical Education and Sports Science', 'PESS', 100, '2025-04-16 21:29:41', '2025-04-19 16:02:49', 0),
(18, 1, 'Physiotherapy and Rehabilitation', 'PTR', 65, '2025-04-16 21:29:41', '2025-04-19 16:02:38', 0),
(19, 1, 'Nursing and Health Science', 'NHS', 80, '2025-04-16 21:29:41', '2025-04-19 16:02:43', 0),
(20, 1, 'English', 'ENG', 80, '2025-04-16 21:29:41', '2025-04-19 16:04:17', 0),
(21, 1, 'Physics', 'PHY', 95, '2025-04-16 21:29:41', '2025-04-19 16:23:41', 0),
(22, 1, 'Chemistry', 'CHEM', 110, '2025-04-16 21:29:41', '2025-04-19 16:04:32', 0),
(23, 1, 'Mathematics', 'MATH', 50, '2025-04-16 21:29:41', '2025-04-19 16:04:37', 0),
(24, 1, 'Applied Statistics and Data Science', 'ASDS', 40, '2025-04-16 21:29:41', '2025-04-19 16:04:44', 0),
(25, 1, 'Applied Chemistry', 'APC', 10, '2025-04-18 20:15:18', '2025-04-19 16:30:01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_file`
--

CREATE TABLE `tbl_file` (
  `file_id` int(11) NOT NULL,
  `status` int(11) DEFAULT 1,
  `file_owner_id` int(11) NOT NULL,
  `file_original_name` text NOT NULL,
  `file_new_name` text NOT NULL,
  `note_ids` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_file`
--

INSERT INTO `tbl_file` (`file_id`, `status`, `file_owner_id`, `file_original_name`, `file_new_name`, `note_ids`, `created`, `modified`) VALUES
(1, 1, 2, 'musemind-ux-agency-9Jt1l0wECXs-unsplash.jpg', 'yG4ea908yuwI1.jpg', '', '2025-04-09 16:44:26', '2025-04-09 16:44:26'),
(2, 1, 2, 'HMS (1).pdf', '9kisCZ0C1wqA2.pdf', '', '2025-04-09 16:44:26', '2025-04-09 16:44:26'),
(3, 1, 3, 'darling-arias-zchzY7fpgUo-unsplash.jpg', 'UtHpRIX3IcrO3.jpg', '', '2025-04-09 16:49:07', '2025-04-09 16:49:07'),
(4, 1, 3, 'HMS (1).pdf', '0gffsfZhpGGg4.pdf', '', '2025-04-09 16:49:07', '2025-04-09 16:49:07'),
(5, 1, 4, 'masum-millat-pJOgDjC2uSc-unsplash.jpg', 'NEMdXq71Fhh85.jpg', '', '2025-04-09 16:53:44', '2025-04-09 16:53:44'),
(6, 1, 4, 'HMS (1).pdf', 'B01nkevNz1R6.pdf', '', '2025-04-09 16:53:44', '2025-04-09 16:53:44'),
(7, 1, 5, 'ali-morshedlou-WMD64tMfc4k-unsplash.jpg', 'DeIcvfSW4DSv7.jpg', '', '2025-04-09 16:57:48', '2025-04-09 16:57:48'),
(8, 1, 5, 'HMS (1).pdf', 'j0R3W7vskfO08.pdf', '', '2025-04-09 16:57:48', '2025-04-09 16:57:48'),
(9, 1, 6, 'man-7799486_640.jpg', 'mX2BRgkxuiO69.jpg', '', '2025-04-09 18:20:31', '2025-04-09 18:20:31'),
(10, 1, 6, 'HMS (1).pdf', 'ctJdlNNhN4Uw10.pdf', '', '2025-04-09 18:20:31', '2025-04-09 18:20:31'),
(11, 1, 7, 'pexels-italo-melo-881954-2379004.jpg', 'dLMDpbIGiQRC11.jpg', '', '2025-04-09 18:27:12', '2025-04-09 18:27:12'),
(12, 1, 7, 'HMS (1).pdf', 'yveHhdSkZuaz12.pdf', '', '2025-04-09 18:27:12', '2025-04-09 18:27:12'),
(13, 1, 8, 'pexels-chloekalaartist-1043474.jpg', '9pSuQxkdoE313.jpg', '', '2025-04-09 18:32:51', '2025-04-09 18:32:51'),
(14, 1, 8, 'HMS (1).pdf', 'HQkgPYavOlGG14.pdf', '', '2025-04-09 18:32:51', '2025-04-09 18:32:51'),
(15, 1, 9, 'pexels-olly-874158.jpg', 'YSXAOdoUUQhc15.jpg', '', '2025-04-09 18:41:06', '2025-04-09 18:41:06'),
(16, 1, 9, 'HMS (1).pdf', 'SgOeMJVxqQC916.pdf', '', '2025-04-09 18:41:06', '2025-04-09 18:41:07'),
(17, 1, 10, 'pexels-mastercowley-1300402.jpg', '6BijjoxJoblO17.jpg', '', '2025-04-09 20:25:39', '2025-04-09 20:25:39'),
(18, 1, 10, 'HMS (1).pdf', 'n6zPIqJIbGWv18.pdf', '', '2025-04-09 20:25:39', '2025-04-09 20:25:39'),
(19, 1, 11, 'pexels-danxavier-1121796.jpg', 'pHO1CozGdFCd19.jpg', '', '2025-04-09 20:31:57', '2025-04-09 20:31:57'),
(20, 1, 11, 'HMS (1).pdf', 'OLpmR1doEfT20.pdf', '', '2025-04-09 20:31:57', '2025-04-09 20:31:57'),
(21, 1, 12, 'pexels-nkhajotia-1516680.jpg', 'P2Qh5MN64FZO21.jpg', '', '2025-04-09 20:49:51', '2025-04-09 20:49:51'),
(22, 1, 12, 'HMS (1).pdf', 'KzbDr8Fvmc22.pdf', '', '2025-04-09 20:49:51', '2025-04-09 20:49:51'),
(23, 1, 13, '0.jpg', '0.jpg', '', '2025-04-09 20:55:50', '2025-04-09 20:55:50'),
(24, 1, 13, 'pexels-olly-845434.jpg', '50cVLFcD2LSJ24.jpg', '', '2025-04-09 20:55:50', '2025-04-09 20:55:50'),
(25, 1, 14, 'pexels-stefanstefancik-91227.jpg', 'ZQrqmPb4OtjE25.jpg', '', '2025-04-09 21:08:01', '2025-04-09 21:08:01'),
(26, 1, 14, 'HMS (1).pdf', 'qwKyoPsj7kca26.pdf', '', '2025-04-09 21:08:01', '2025-04-09 21:08:02'),
(27, 1, 2, 'screencapture-chatgpt-c-67bf7a38-7cd0-8003-8872-7057a63cb0d6-2025-03-06-02_21_13.png', 'eHMIZ2gobB27.png', '', '2025-04-11 17:32:01', '2025-04-11 17:32:01'),
(28, 1, 2, '0.jpg', '0.jpg', '', '2025-04-11 17:54:57', '2025-04-11 17:54:57'),
(29, 1, 15, 'yG4ea908yuwI1.jpg', 'W6fK9R3X2xBY29.jpg', '', '2025-04-17 19:08:05', '2025-04-17 19:08:05'),
(30, 1, 15, 'JUST_Thesis_.pdf', 'I7yggxtguqk30.pdf', '', '2025-04-17 19:08:05', '2025-04-17 19:08:05'),
(31, 1, 2, 'yG4ea908yuwI1.jpg', '33izgTomH78f31.jpg', '', '2025-04-17 19:31:44', '2025-04-17 19:31:44'),
(32, 1, 16, 'yG4ea908yuwI1.jpg', 'GI4bQIp4kS9a32.jpg', '', '2025-04-28 12:06:27', '2025-04-28 12:06:28'),
(33, 1, 17, 'yG4ea908yuwI1.jpg', '81MRORZ8UD1233.jpg', '', '2025-04-28 13:17:33', '2025-04-28 13:17:33'),
(34, 1, 18, 'yG4ea908yuwI1.jpg', '0nRZZmTTAGQ34.jpg', '', '2025-04-28 13:23:06', '2025-04-28 13:23:06'),
(35, 1, 19, 'fotor-ai-20250227213547.jpg', 'dxW4hMyhi96i35.jpg', '', '2025-04-28 19:25:23', '2025-04-28 19:25:23'),
(36, 1, 19, 'fotor-ai-20250227213547.jpg', 'xzWUsnXWpZEn36.jpg', '', '2025-04-28 19:25:23', '2025-04-28 19:25:23'),
(37, 1, 20, 'fotor-ai-20250227213547.jpg', 'xOp7GY2rdOLO37.jpg', '', '2025-04-28 20:13:45', '2025-04-28 20:13:45'),
(38, 1, 20, 'fotor-ai-20250227213547.jpg', 'DYIZ6gxg6BND38.jpg', '', '2025-04-28 20:13:45', '2025-04-28 20:13:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hall_seat_allocation_event`
--

CREATE TABLE `tbl_hall_seat_allocation_event` (
  `event_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `application_start_date` date DEFAULT NULL,
  `application_end_date` date DEFAULT NULL,
  `viva_notice_date` date DEFAULT NULL,
  `viva_date_list` text DEFAULT NULL,
  `viva_student_count` text DEFAULT NULL,
  `seat_allotment_result_notice_date` date DEFAULT NULL,
  `seat_allotment_result_notice_text` text DEFAULT NULL,
  `seat_confirm_deadline_date` date DEFAULT NULL,
  `priority_list` text DEFAULT NULL,
  `semester_priority` text DEFAULT NULL,
  `scoring_factor` text DEFAULT NULL,
  `seat_distribution_quota` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_hall_seat_allocation_event`
--

INSERT INTO `tbl_hall_seat_allocation_event` (`event_id`, `status`, `title`, `details`, `application_start_date`, `application_end_date`, `viva_notice_date`, `viva_date_list`, `viva_student_count`, `seat_allotment_result_notice_date`, `seat_allotment_result_notice_text`, `seat_confirm_deadline_date`, `priority_list`, `semester_priority`, `scoring_factor`, `seat_distribution_quota`, `created`, `modified`) VALUES
(1, 5, 'Hall seat winter session 1', 'Hall seat winter session 1.', '2025-04-25', '2025-04-26', '2025-04-27', '2025-04-27,2025-04-28', '4,3', '2025-04-29', 'Viva start time will be at 10am on that day. The result will publish on given date around 10am as office open. DO NOT MISS THE VIVA DATE', '2025-04-29', '', '6,1,4,2,3,5,7,8,9,10,11,12', '0.03,3,0.1', '1=>1,2=>1,3=>1,4=>1,5=>1,6=>1,7=>1,8=>1,9=>1,10=>1,11=>1,12=>1,13=>1,14=>1,15=>1,16=>1,17=>1,18=>1,19=>1,20=>1,21=>1,22=>1,23=>1,24=>1,25=>0', '2025-04-25 10:02:59', '2025-04-28 09:37:54'),
(2, 5, 'Hall seat winter session 2', 'Hall seat winter session 2.', '2025-04-25', '2025-04-25', '2025-04-25', '2025-04-25,2025-04-26', '4,3', '2025-04-27', 'Viva start time will be at 10am on that day. The result will publish on given date around 10am as office open.', '2025-04-30', '', '8,7,1,2,3,4,5,6,9,10,11,12', '0.05,20,0.1', '1=>1,2=>2,3=>1,4=>1,5=>1,6=>1,7=>1,8=>1,9=>1,10=>1,11=>1,12=>1,13=>1,14=>1,15=>1,16=>1,17=>2,18=>1,19=>1,20=>1,21=>1,22=>2,23=>1,24=>1,25=>1', '2025-04-25 10:16:31', '2025-04-27 20:58:10'),
(3, 1, 'Hall seat winter session 3', 'Hall seat winter session 3.', '2025-04-28', '2025-04-30', '2025-05-03', NULL, '', NULL, NULL, NULL, '', '1,2,3,4,5,6,7,8,9,10,11,12', '10,40,10', '1=>1,2=>1,3=>1,4=>1,5=>1,6=>1,7=>1,8=>1,9=>1,10=>1,11=>1,12=>1,13=>1,14=>1,15=>1,16=>1,17=>1,18=>1,19=>1,20=>1,21=>1,22=>1,23=>1,24=>1,25=>1', '2025-04-28 08:16:28', '2025-04-28 08:16:28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hall_seat_application`
--

CREATE TABLE `tbl_hall_seat_application` (
  `application_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_details_id` int(11) NOT NULL,
  `serial_no` int(11) NOT NULL,
  `viva_date` date DEFAULT NULL,
  `allotted_seat_id` int(11) NOT NULL,
  `seat_confirm_date` date DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_hall_seat_application`
--

INSERT INTO `tbl_hall_seat_application` (`application_id`, `status`, `event_id`, `user_id`, `user_details_id`, `serial_no`, `viva_date`, `allotted_seat_id`, `seat_confirm_date`, `created`, `modified`) VALUES
(1, 4, 1, 2, 1, 0, '2025-04-27', 0, NULL, '2025-04-25 16:08:58', '2025-04-27 21:30:29'),
(2, 4, 2, 2, 1, 0, '2025-04-25', 0, NULL, '2025-04-25 16:09:08', '2025-04-27 19:20:35'),
(3, 4, 1, 3, 2, 0, '2025-04-27', 0, NULL, '2025-04-25 16:24:36', '2025-04-27 21:30:29'),
(4, 6, 2, 3, 2, 0, '2025-04-25', 51, NULL, '2025-04-25 16:24:42', '2025-04-27 20:25:37'),
(5, 6, 1, 4, 3, 0, '2025-04-27', 19, NULL, '2025-04-25 16:25:23', '2025-04-27 21:39:50'),
(6, 4, 2, 4, 3, 0, '2025-04-25', 0, NULL, '2025-04-25 16:25:33', '2025-04-27 19:20:35'),
(7, 3, 1, 5, 4, 0, '2025-04-27', 0, NULL, '2025-04-25 16:26:20', '2025-04-27 21:26:30'),
(8, 4, 2, 5, 4, 0, '2025-04-25', 0, NULL, '2025-04-25 16:26:24', '2025-04-27 19:20:35'),
(9, 6, 1, 6, 5, 0, '2025-04-28', 10, NULL, '2025-04-25 16:26:48', '2025-04-27 21:39:52'),
(10, 4, 2, 6, 5, 0, '2025-04-26', 0, NULL, '2025-04-25 16:26:52', '2025-04-27 19:20:35'),
(11, 4, 1, 8, 7, 0, '2025-04-28', 0, NULL, '2025-04-25 16:27:11', '2025-04-27 21:30:29'),
(12, 5, 2, 8, 7, 0, '2025-04-26', 35, NULL, '2025-04-25 16:27:16', '2025-04-27 19:20:35'),
(13, -4, 1, 9, 8, 0, '2025-04-28', 0, NULL, '2025-04-25 16:27:45', '2025-04-27 21:21:22'),
(14, 5, 2, 9, 8, 0, '2025-04-26', 44, NULL, '2025-04-25 16:27:51', '2025-04-27 19:20:35'),
(15, 1, 3, 2, 1, 0, NULL, 0, NULL, '2025-04-28 21:07:20', '2025-04-28 21:07:20'),
(16, 1, 3, 5, 4, 0, NULL, 0, NULL, '2025-04-28 21:08:55', '2025-04-28 21:08:55'),
(17, 1, 3, 7, 6, 0, NULL, 0, NULL, '2025-04-28 21:09:32', '2025-04-28 21:09:32'),
(18, 1, 3, 8, 7, 0, NULL, 0, NULL, '2025-04-28 21:09:53', '2025-04-28 21:09:53'),
(19, 1, 3, 9, 8, 0, NULL, 0, NULL, '2025-04-28 21:10:31', '2025-04-28 21:10:31'),
(20, 1, 3, 15, 15, 0, NULL, 0, NULL, '2025-04-28 21:11:02', '2025-04-28 21:11:02'),
(21, 1, 3, 11, 10, 0, NULL, 0, NULL, '2025-04-28 21:12:03', '2025-04-28 21:12:03'),
(22, 1, 3, 12, 11, 0, NULL, 0, NULL, '2025-04-28 21:12:33', '2025-04-28 21:12:33'),
(23, 1, 3, 20, 23, 0, NULL, 0, NULL, '2025-04-28 21:16:39', '2025-04-28 21:16:39');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hall_seat_details`
--

CREATE TABLE `tbl_hall_seat_details` (
  `seat_id` int(11) NOT NULL,
  `status` int(11) DEFAULT 0,
  `reserved_by_event_id` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT 0,
  `floor_no` int(11) DEFAULT NULL,
  `room_no` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_by` int(11) DEFAULT -1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_hall_seat_details`
--

INSERT INTO `tbl_hall_seat_details` (`seat_id`, `status`, `reserved_by_event_id`, `user_id`, `floor_no`, `room_no`, `created`, `modified`, `modified_by`) VALUES
(1, 2, 3, 0, 0, 1, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(2, 2, 3, 0, 0, 1, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(3, 2, 3, 0, 0, 1, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(4, 2, 3, 0, 0, 1, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(5, 2, 3, 0, 0, 2, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(6, 2, 3, 0, 0, 2, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(7, 2, 3, 0, 0, 2, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(8, 2, 3, 0, 0, 2, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(9, 2, 3, 0, 0, 3, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(10, 1, 1, 6, 0, 3, '2025-04-25 09:59:06', '2025-04-27 21:39:52', -1),
(11, 2, 3, 0, 0, 3, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(12, 2, 3, 0, 0, 3, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(13, 2, 3, 0, 0, 4, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(14, 2, 3, 0, 0, 4, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(15, 2, 3, 0, 0, 4, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(16, 2, 3, 0, 0, 4, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(17, 2, 3, 0, 0, 5, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(18, 2, 3, 0, 0, 5, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(19, 1, 1, 4, 0, 5, '2025-04-25 09:59:06', '2025-04-27 21:39:50', -1),
(20, 2, 3, 0, 0, 5, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(21, 2, 3, 0, 0, 6, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(22, 2, 3, 0, 0, 6, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(23, 2, 3, 0, 0, 6, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(24, 2, 3, 0, 0, 6, '2025-04-25 09:59:06', '2025-04-28 08:16:28', -1),
(25, 2, 3, 0, 1, 1, '2025-04-25 10:15:49', '2025-04-28 08:16:28', -1),
(26, 2, 3, 0, 1, 1, '2025-04-25 10:15:49', '2025-04-28 08:16:28', -1),
(27, 2, 3, 0, 1, 1, '2025-04-25 10:15:49', '2025-04-28 08:16:28', -1),
(28, 0, 0, 0, 1, 1, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(29, 0, 0, 0, 1, 2, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(30, 0, 0, 0, 1, 2, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(31, 0, 0, 0, 1, 2, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(32, 0, 0, 0, 1, 2, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(33, 0, 0, 0, 1, 3, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(34, 0, 0, 0, 1, 3, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(35, 0, 0, 0, 1, 3, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(36, 0, 0, 0, 1, 3, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(37, 0, 0, 0, 1, 4, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(38, 0, 0, 0, 1, 4, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(39, 0, 0, 0, 1, 4, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(40, 0, 0, 0, 1, 4, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(41, 0, 0, 0, 1, 5, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(42, 0, 0, 0, 1, 5, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(43, 0, 0, 0, 1, 5, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(44, 0, 0, 0, 1, 5, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(45, 0, 0, 0, 1, 6, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(46, 0, 0, 0, 1, 6, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(47, 0, 0, 0, 1, 6, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(48, 0, 0, 0, 1, 6, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(49, 0, 0, 0, 1, 7, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(50, 0, 0, 0, 1, 7, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(51, 1, 2, 3, 1, 7, '2025-04-25 10:15:49', '2025-04-28 20:55:59', -1),
(52, 0, 0, 0, 1, 7, '2025-04-25 10:15:49', '2025-04-27 20:58:10', -1),
(53, 0, 0, 0, 2, 1, '2025-04-28 09:22:25', '2025-04-28 09:22:25', -1),
(54, 0, 0, 0, 2, 1, '2025-04-28 09:22:25', '2025-04-28 09:22:25', -1),
(55, 0, 0, 0, 2, 1, '2025-04-28 09:22:25', '2025-04-28 09:22:25', -1),
(56, 0, 0, 0, 2, 1, '2025-04-28 09:22:25', '2025-04-28 09:22:25', -1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notes`
--

CREATE TABLE `tbl_notes` (
  `note_id` int(11) NOT NULL,
  `status` int(11) DEFAULT 1,
  `owner_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notice`
--

CREATE TABLE `tbl_notice` (
  `notice_id` int(11) NOT NULL,
  `status` int(11) DEFAULT 1,
  `title` text NOT NULL,
  `description` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notice`
--

INSERT INTO `tbl_notice` (`notice_id`, `status`, `title`, `description`, `created`, `modified`) VALUES
(1, 1, 'Hall Seat Allottment', 'Hall Seat Allottment will publish after end of April.', '2025-04-27 22:33:19', '2025-04-27 22:45:18'),
(2, 1, 'Hall Seat Management', 'Will update soon.', '2025-04-27 22:43:54', '2025-04-27 22:44:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `user_type` varchar(100) DEFAULT 'user',
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `status`, `email`, `password`, `user_type`, `created`, `modified`) VALUES
(1, 1, 'admin@admin', '$2y$10$RM7RxWYU/Lvg2LS6bGN/M.MSWcBMnlEv9YjLcCf5nplSU1e0OFhkC', 'admin', '2025-04-09 16:39:44', '2025-04-28 13:45:02'),
(2, 1, '190101.cse@student.just.edu.bd', '$2y$10$vnkcgV.Kkxyu/D8ilIoGbezkpZx/r2eFyzBob9PvGNIcqnf3fpNsS', 'user', '2025-04-09 16:44:26', '2025-04-28 19:11:34'),
(3, 1, '190102.cse@student.just.edu.bd', '$2y$10$yjzpVvZ3eTu4dhaCk5PMJOlStwCurvM7LEvodqnAmJtHdmFT58RSS', 'user', '2025-04-09 16:49:07', '2025-04-28 18:49:16'),
(4, 1, '190103.cse@student.just.edu.bd', '$2y$10$bWDdEBe/nCDhK7Hzi5QNpuZTCaTO17rfBMhctP/vyXPEpAwU21f9q', 'user', '2025-04-09 16:53:44', '2025-04-10 17:08:17'),
(5, 1, '190104.cse@student.just.edu.bd', '$2y$10$qft7alOAtu.G579BWoq.iOueNiXa9e4oUdv/AFS8Cf5uyX6988fOC', 'user', '2025-04-09 16:57:48', '2025-04-10 17:08:18'),
(6, 1, '190105.cse@student.just.edu.bd', '$2y$10$PhZaDBpace.4EMcXhvEs9ua.k8JCGvHvyEuf4UkFQ1LlNhawRVn16', 'user', '2025-04-09 18:20:31', '2025-04-10 17:08:20'),
(7, 1, '190106.cse@student.just.edu.bd', '$2y$10$tmfMrse1wDMYBLZrDZ0Oru84wxkoSR.1bOe1KmSKNxQ/t6tIRurci', 'user', '2025-04-09 18:27:12', '2025-04-10 17:08:21'),
(8, 1, '190107.cse@student.just.edu.bd', '$2y$10$4NkYtmtlJvhjL9CQOc77muZjH/9aTL1cYEBloXEg2uJkXW9k4kI92', 'user', '2025-04-09 18:32:51', '2025-04-10 17:08:22'),
(9, 1, '190108.cse@student.just.edu.bd', '$2y$10$ZXEGJ7ajCXedqT4SV5eUceSYu3k4/F8tMknX89F.aE5dAv8e7UH0C', 'user', '2025-04-09 18:41:06', '2025-04-10 17:08:31'),
(10, 1, '190110.cse@student.just.edu.bd', '$2y$10$uhG36oUMS5erW6k0YRtEVO2KGkgt9wOZ0Po9gws1cEYRad/N9SsHC', 'user', '2025-04-09 20:25:39', '2025-04-10 17:08:32'),
(11, 1, '190113.cse@student.just.edu.bd', '$2y$10$DBXexfV8v5ygcFA8mkC/X.MWwKoVE3ImGZPFRHIjBQstXQuQLmrlO', 'user', '2025-04-09 20:31:57', '2025-04-10 17:08:54'),
(12, 1, '190114.cse@student.just.edu.bd', '$2y$10$xWYG6UP87WJu0s5e45wkoObzYSRapXvWoNA2IkkH9.S.lPgknsZhy', 'user', '2025-04-09 20:49:51', '2025-04-10 17:08:56'),
(13, 0, '190116.cse@student.just.edu.bd', '$2y$10$30VIVdAyeni2xQHztLQ5ZujpWMqv7awSfK6n5B9fHrC6SsqY5X2..', 'user', '2025-04-09 20:55:49', '2025-04-09 20:55:49'),
(14, 0, '190119.cse@student.just.edu.bd', '$2y$10$D9lsz455MDw89WJ83bNa8.jmUTifE/6okCUU99UWrQABOzduyj3jO', 'user', '2025-04-09 21:08:01', '2025-04-09 21:08:01'),
(15, 1, '190109.cse@student.just.edu.bd', '$2y$10$gsDwRycajTfKQAb8V91GEuvbfw1BcfCv6J/0CePTLWA9JHS3.qqqa', 'user', '2025-04-17 19:08:05', '2025-04-28 09:12:43'),
(16, 1, 'super@admin', '$2y$10$oKVmtZUe7jXG4cpUP7uffezHmP/KosqjoGteAkRNBwFuGu302fpf.', 'super-admin', '2025-04-28 11:39:40', '2025-04-28 17:44:13'),
(18, 1, 'shakil@just.edu.bd', '$2y$10$RqZuXdkBLw1v1NxoP6ftuu/uhMDHOGEeR.aUbhhA3dA83I4zLxhie', 'admin', '2025-04-28 13:23:06', '2025-04-28 20:21:55'),
(19, -2, '190150.cse@student.just.edu.bd', '$2y$10$gJPIBAht7IIimhqzJSBtUeKcPMVvNLmSPUuCUg6OGGRqV97APaB8m', 'user', '2025-04-28 19:25:23', '2025-04-28 19:34:37'),
(20, 1, '190122.cse@student.just.edu.bd', '$2y$10$Pf6c3QsKVuTDIrUqq9H68OIhTVl2h.sN3zpyzcn69S0IKNtbzrxXm', 'user', '2025-04-28 20:13:45', '2025-04-28 20:19:46');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_details`
--

CREATE TABLE `tbl_user_details` (
  `details_id` int(11) NOT NULL,
  `status` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `profile_picture_id` int(11) DEFAULT 0,
  `full_name` text DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `contact_no` text DEFAULT NULL,
  `session` text DEFAULT NULL,
  `department_id` int(11) DEFAULT 0,
  `year_semester_code` int(11) DEFAULT 0,
  `year` int(11) DEFAULT 0,
  `semester` int(11) DEFAULT 0,
  `last_semester_cgpa_or_merit` double DEFAULT 0,
  `district` text DEFAULT NULL,
  `division` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `present_address` text DEFAULT NULL,
  `father_name` text DEFAULT NULL,
  `father_contact_no` text DEFAULT NULL,
  `father_profession` text DEFAULT NULL,
  `father_monthly_income` double DEFAULT 0,
  `mother_name` text DEFAULT NULL,
  `mother_contact_no` text DEFAULT NULL,
  `mother_profession` text DEFAULT NULL,
  `mother_monthly_income` double DEFAULT 0,
  `guardian_name` text DEFAULT NULL,
  `guardian_contact_no` text DEFAULT NULL,
  `guardian_address` text DEFAULT NULL,
  `document_id` int(11) DEFAULT 0,
  `note_ids` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_by` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user_details`
--

INSERT INTO `tbl_user_details` (`details_id`, `status`, `user_id`, `profile_picture_id`, `full_name`, `student_id`, `gender`, `contact_no`, `session`, `department_id`, `year_semester_code`, `year`, `semester`, `last_semester_cgpa_or_merit`, `district`, `division`, `permanent_address`, `present_address`, `father_name`, `father_contact_no`, `father_profession`, `father_monthly_income`, `mother_name`, `mother_contact_no`, `mother_profession`, `mother_monthly_income`, `guardian_name`, `guardian_contact_no`, `guardian_address`, `document_id`, `note_ids`, `created`, `modified`, `modified_by`) VALUES
(1, 1, 2, 31, 'Masum Billah', 190101, 'male', '01971636762', '2019-2020', 1, 8, 4, 2, 3.45, 'Satkhira', 'Khulna', 'House No-12 Road-55, Satkhira, Khulna, Bangladesh.', 'Ambottola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Md. Azad billah', '01713079023', 'Businessman', 15000, 'Halima Khatun', '01715579054', 'Housewife', 0, 'Mehedi Billah', '01710079023', 'House No-15, Road No-55, Moylapota, Khulna.', 2, '', '2025-04-09 16:44:26', '2025-04-23 11:51:11', 0),
(2, 1, 3, 3, 'Joydip Das', 190102, 'male', '01995140040', '2019-2020', 1, 8, 4, 2, 3.65, 'Jashore', 'Khulna', 'House No-12 Road No-1, Rupdiya, Jashore, Khulna, Bangladesh.', 'Ambottola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Joyanto Das', '01713079023', 'Businessman', 12000, 'kakoli Das', '01715579054', 'Housewife', 0, 'Jahir Das', '01712032021', 'House No-15, Road No-55,Noapara, Jashore, Khulna, Bangladesh', 4, '', '2025-04-09 16:49:07', '2025-04-23 11:51:15', 0),
(3, 1, 4, 5, 'Md. Shojib Hossain', 190103, 'male', '01971636762', '2019-2020', 1, 7, 4, 1, 3.67, 'Pabna', 'Rajshahi', 'House No-12 Road No-5, Pabna, Rajshahi, Bangladesh.', 'Beltola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Md. Shahin Hossain', '01713079023', 'Teacher', 20000, 'Salma Begum', '01712079044', 'Teacher', 20000, 'Md. Sajal Hossain', '01718079021', 'House No-15, Road No-15, Pabna, Rajshahi, Bangladesh.', 6, '', '2025-04-09 16:53:44', '2025-04-23 11:51:18', 0),
(4, 1, 5, 7, 'Joytiran Mondol Joy', 190104, 'male', '01797428597', '2019-2020', 1, 7, 4, 1, 3.44, 'Khulna', 'Khulna', 'House No-12 Road No-1, Dumuriya, Khulna, Bangladesh.', 'Beltola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Joyanto Das', '01713079023', 'Teacher', 15000, 'kakoli Mondol', '01712079032', 'Housewife', 0, 'Joyonto Mondal', '01713079023', 'House No-12 Road No-1, Dumuriya, Khulna, Bangladesh.', 8, '', '2025-04-09 16:57:48', '2025-04-23 11:51:23', 0),
(5, 1, 6, 9, 'Tahmidul Kabir Rafi', 200105, 'male', '01740672465', '2020-2021', 2, 6, 3, 2, 3.33, 'Rajbari', 'Dhaka', 'House No-1A Road-15, Rajbari, Dhaka, Bangladesh.', 'Palbari Royel Mor, Jashore, Khulna', 'Md. Tamim Kabir', '01713179023', 'Businessman', 20000, 'Salma Begum', '01711079044', 'Housewife', 0, 'Md. Tamim Kabir', '01713179023', 'House No-1A Road-15, Rajbari, Dhaka, Bangladesh.', 10, '', '2025-04-09 18:20:31', '2025-04-23 11:51:27', 0),
(6, 1, 7, 11, 'Mirza Mahfuj Hossain', 200106, 'male', '01825787572', '2020-2021', 2, 6, 3, 2, 3.22, 'Tangail', 'Dhaka', 'House No-11 Road No-122, Tangail, Dhaka, Bangladesh.', 'Ambottola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Mirza Mamun Hossain', '01742079023', 'Govertment Service Holder', 15000, 'Amina Hossain', '01712079031', 'Housewife', 0, 'Mirza Shakhawat Hossain', '01718079021', 'House No-11 Road No-122, Tangail, Dhaka, Bangladesh.', 12, '', '2025-04-09 18:27:12', '2025-04-23 11:51:31', 0),
(7, 1, 8, 13, 'Srijon Sarker', 200107, 'male', '01744110978', '2020-2021', 2, 7, 4, 1, 3.2, 'Sherpur', 'Mymensingh', 'House No-1A Road-11, Sherpur, Dhaka, Bangladesh.', 'Ambottola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Gabindo Sarker', '01710379023', 'Govertment Service Holder', 20000, 'Shikha Sarker', '01715679054', 'Housewife', 0, 'Gabindo Sarker', '01710379023', 'House No-1A Road-11, Sherpur, Dhaka, Bangladesh.', 14, '', '2025-04-09 18:32:51', '2025-04-23 11:51:39', 0),
(8, 1, 9, 15, 'Md. Tosiqul  Islam', 200108, 'male', '01704305651', '2020-2021', 2, 4, 2, 2, 3.65, 'Chapai Nawabganj', 'Rajshahi', 'House No-12, Road No-22, Chapai Nawabganj, Rajshahi, Bangladesh.', 'Palbari-12, Jashore, Khulna, Bangladesh ', 'Md. Ansar Islam', '01713579023', 'Farmer', 10000, 'Salma Akhter', '01912079032', 'Housewife', 0, 'Md. Ansar Islam', '01713579023', 'House No-12, Road No-22, Chapai Nawabganj, Rajshahi, Bangladesh.', 16, '', '2025-04-09 18:41:06', '2025-04-23 11:51:46', 0),
(9, 1, 10, 17, 'Sabbir Ahmed', 210110, 'male', '01878038097', '2021-2022', 2, 4, 2, 2, 3.21, 'Khulna', 'Khulna', 'House No-12 Road No-3, Power House, Khulna, Bangladesh.', 'Campus Gate, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Md. Rasel Ahmed', '01913779023', 'Businessman', 15000, 'Amina Ahmed', '01714579032', 'Housewife', 0, 'Md. Rasel Ahmed', '01913779023', 'House No-12 Road No-3, Power House, Khulna, Bangladesh.', 18, '', '2025-04-09 20:25:39', '2025-04-23 12:00:51', 0),
(10, 1, 11, 19, 'Tanvir Hossain', 220113, 'male', '01643620848', '2022-2023', 3, 3, 2, 1, 3.13, 'Bogura', 'Rajshahi', 'House No-3A Block1 Road No-5, Bogura, Rajshahi, Bangladesh.', 'Palbari-15, Jashore, Khulna', 'Md. Zakir Hossain', '01683079023', 'Govertment Service Holder', 15000, 'Zakia Parvin', '01812079032', 'Housewife', 0, 'Md. Sajal Hossain', '01713279023', 'House No-15, Road No-55, College Road, Rajshahi , Bangladesh', 20, '', '2025-04-09 20:31:57', '2025-04-23 12:00:54', 0),
(11, 1, 12, 21, 'Sakib Hasan Prangon', 220114, 'male', '01743620844', '2022-2023', 3, 3, 2, 1, 3.14, 'Khulna', 'Khulna', 'House No-21. Road-55, Moylapota, Khulna, Bangladesh.', 'Arabpur-12A, Jashore, Khulna, Bangladesh.', 'Sadman Hasan', '01412079023', 'Businessman', 12000, 'Sonia Akhter', '01714079054', 'Teacher', 10000, 'Shakil Hasan', '01712079021', 'House No-15, Road No-55, Moylapota, Khulna.', 22, '', '2025-04-09 20:49:51', '2025-04-23 12:00:57', 0),
(12, 0, 13, 23, 'Md. Masrafi Bin Seraj Sakib', 230116, 'male', '01886420246', '2023-2024', 4, 1, 1, 1, 33, 'Khulna', 'Khulna', 'House No-12 Road No-1, Nirala, Khulna, Bangladesh.', 'Arabpur, Jashore, Khulna, Bangladesh.', 'Md. Shahin Seraj', '01643079023', 'Govertment Service Holder', 15000, 'Nasima Begum', '01413079032', 'Housewife', 0, 'Md. Shahin Seraj', '01643079023', 'House No-12 Road No-1, Nirala, Khulna, Bangladesh.', 24, '', '2025-04-09 20:55:49', '2025-04-23 12:00:59', 0),
(13, 0, 14, 25, 'Mirza Mohibul Hasan ', 230119, 'male', '01991347811', '2023-2024', 3, 1, 1, 1, 44, 'Faridpur', 'Dhaka', 'House No-1A Road-15, Faridpur, Dhaka, Bangladesh.', 'New Market, Jashore, Khulna, Bangladesh.', 'Mirza Amzad Hasan', '01712078123', 'Businessman', 15000, 'Nasrin Hasan', '01325579054', 'Housewife', 0, 'Mirza Mahfuz Hasan', '01710076743', 'House No-12 Road No-1, Faridpur, Dhaka Bangladesh.', 26, '', '2025-04-09 21:08:01', '2025-04-23 12:01:03', 0),
(15, 1, 15, 29, 'Md Arafat', 190109, 'male', '01971636763', '2020-2021', 21, 5, 3, 1, 3.8, 'Narayanganj', 'Dhaka', 'Residential area 51/4, Narayanganj, Dhaka.', 'Residential area 51/4, Narayanganj, Dhaka.', 'Md Hassan', '01713079023', 'Businessman', 16000, 'Mrs Nusrat', '01512312301', 'Housewife', 0, 'Mehedi', '015123123122', '34/1 Palbari, Jashore.', 30, '', '2025-04-17 19:08:05', '2025-04-28 09:12:43', 0),
(17, 0, 2, 31, 'Masum Billah', 190101, 'male', '01971636762', '2019-2020', 1, 7, 0, 0, 3.45, 'Satkhira', 'Khulna', 'House No-12 Road-55, Satkhira, Khulna, Bangladesh.', 'Ambottola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Md. Azad billah', '01713079023', 'Businessman', 15000, 'Halima Khatun', '01715579054', 'Housewife', 0, 'Mehedi Billah', '01710079023', 'House No-15, Road No-55, Moylapota, Khulna.', 2, '', '2025-04-23 18:42:32', '2025-04-23 18:42:32', 0),
(18, 1, 16, 32, 'Super Admin', 0, 'male', '', '', 0, 0, 0, 0, 0, 'Barguna', 'Barishal', '', '', '', '', '', 0, '', '', '', 0, '', '', '', 0, '', '2025-04-28 11:39:40', '2025-04-28 12:13:53', 0),
(19, 1, 17, 33, 'Md Shakil', 0, 'male', '01971636762', '', 0, 0, 0, 0, 0, 'Barguna', 'Barishal', 'Jashore 7408', 'Jashore 7408', '', '', '', 0, '', '', '', 0, '', '', '', 0, '', '2025-04-28 13:17:33', '2025-04-28 13:17:33', 0),
(20, 1, 18, 34, 'Md Shakil', 0, 'male', '01971636762', '', 0, 0, 0, 0, 0, 'Barguna', 'Barishal', 'Jashore 7408', 'Jashore 7408', '', '', '', 0, '', '', '', 0, '', '', '', 0, '', '2025-04-28 13:23:06', '2025-04-28 13:23:06', 0),
(21, -1, 19, 35, 'Abdulla Al Galib', 190150, 'male', '01812312344', '2019-2020', 1, 8, 0, 0, 3.5, 'Barguna', 'Barishal', 'Jashore, Khulna', 'Jashore, Khulna', 'Myra Weiss', '01512312301', 'Business', 19000, 'Halima Khatun', '01715579054', 'Housewife', 0, 'Mehedi Billah', '015123123123', 'Jashore, Khulna', 36, '', '2025-04-28 19:25:23', '2025-04-28 19:26:41', 0),
(22, -2, 3, 3, 'Joydip Dass', 190102, 'male', '01995140040', '2019-2020', 1, 8, 0, 0, 3.65, 'Jashore', 'Khulna', 'House No-12 Road No-1, Rupdiya, Jashore, Khulna, Bangladesh.', 'Ambottola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Joyanto Das', '01713079023', 'Businessman', 12000, 'kakoli Das', '01715579054', 'Housewife', 0, 'Jahir Das', '01712032021', 'House No-15, Road No-55,Noapara, Jashore, Khulna, Bangladesh', 4, '', '2025-04-28 19:39:08', '2025-04-28 19:39:47', 0),
(23, 1, 20, 37, 'Abdul Khaled Arafat', 190122, 'male', '01812312301', '2019-2020', 1, 8, 0, 0, 3.7, 'Barguna', 'Barishal', '166/12, Narayanganj, Dhaka.', '166/12, Narayanganj, Dhaka.', 'Md Akhteruzzaman', '01512312301', 'Business', 15000, 'Razia Sultana', '01851212312', 'Housewife', 0, 'Rifat', '01710079023', '166/12, Narayanganj, Dhaka.', 38, '', '2025-04-28 20:13:45', '2025-04-28 20:19:46', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_department`
--
ALTER TABLE `tbl_department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `tbl_file`
--
ALTER TABLE `tbl_file`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `tbl_hall_seat_allocation_event`
--
ALTER TABLE `tbl_hall_seat_allocation_event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `tbl_hall_seat_application`
--
ALTER TABLE `tbl_hall_seat_application`
  ADD PRIMARY KEY (`application_id`);

--
-- Indexes for table `tbl_hall_seat_details`
--
ALTER TABLE `tbl_hall_seat_details`
  ADD PRIMARY KEY (`seat_id`);

--
-- Indexes for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
  ADD PRIMARY KEY (`note_id`);

--
-- Indexes for table `tbl_notice`
--
ALTER TABLE `tbl_notice`
  ADD PRIMARY KEY (`notice_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_user_details`
--
ALTER TABLE `tbl_user_details`
  ADD PRIMARY KEY (`details_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_department`
--
ALTER TABLE `tbl_department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_file`
--
ALTER TABLE `tbl_file`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tbl_hall_seat_allocation_event`
--
ALTER TABLE `tbl_hall_seat_allocation_event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_hall_seat_application`
--
ALTER TABLE `tbl_hall_seat_application`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_hall_seat_details`
--
ALTER TABLE `tbl_hall_seat_details`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_notice`
--
ALTER TABLE `tbl_notice`
  MODIFY `notice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_user_details`
--
ALTER TABLE `tbl_user_details`
  MODIFY `details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
