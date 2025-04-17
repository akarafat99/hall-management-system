-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2025 at 08:46 AM
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
(28, 1, 2, '0.jpg', '0.jpg', '', '2025-04-11 17:54:57', '2025-04-11 17:54:57');

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
  `seat_distribution_quota` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_hall_seat_allocation_event`
--

INSERT INTO `tbl_hall_seat_allocation_event` (`event_id`, `status`, `title`, `details`, `application_start_date`, `application_end_date`, `viva_notice_date`, `viva_date_list`, `viva_student_count`, `seat_allotment_result_notice_date`, `seat_allotment_result_notice_text`, `seat_confirm_deadline_date`, `priority_list`, `seat_distribution_quota`, `created`, `modified`) VALUES
(1, 3, 'Hall seat winter session 1', 'Hall seat event creation involves defining key details such as the event title, description, and important dates like the application start and end dates, viva notice date, and seat confirmation deadline. It also includes scheduling the viva sessions, specifying the number of students, and providing a seat allotment result notice with its date and text. A priority list ranks criteria like district, academic performance, and parental income, while the seat distribution quota allocates seats per category. The event status progresses through stages—from application collection to viva, result publication, and seat confirmation. Creation and modification timestamps track updates, ensuring a structured and transparent seat allocation process.', '2025-04-11', '2025-04-12', '2025-04-13', '2025-04-12,2025-04-13', '1,0', '2025-04-16', 'Viva start time will be at 10am on that day. The result will publish on given date around 10am as office open.', '0000-00-00', '', '1,1,1,1,1,1,1,0,0,0,0,0', '2025-04-10 20:02:49', '2025-04-14 21:30:14'),
(2, 1, 'Anim vitae minim dig', 'Aut nostrum nihil et', '2025-04-11', '2025-04-15', '2025-04-11', '', '', NULL, NULL, NULL, '1,2,3', '1,0,0,0,0,0,0,0,0,0,0,0', '2025-04-10 21:47:57', '2025-04-15 07:45:37');

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
(2, 2, 1, 2, 1, 0, '2025-04-12', 0, NULL, '2025-04-12 11:53:03', '2025-04-14 21:30:01'),
(3, 1, 2, 2, 1, 0, NULL, 0, NULL, '2025-04-15 07:45:59', '2025-04-15 07:45:59');

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
(1, 2, 1, 0, 0, 1, '2025-04-10 20:01:42', '2025-04-10 20:02:49', -1),
(2, 2, 1, 0, 0, 1, '2025-04-10 20:01:42', '2025-04-10 20:02:49', -1),
(3, 2, 1, 0, 0, 1, '2025-04-10 20:01:42', '2025-04-10 20:02:49', -1),
(4, 2, 1, 0, 0, 1, '2025-04-10 20:01:42', '2025-04-10 20:02:49', -1),
(5, 2, 1, 0, 1, 1, '2025-04-10 20:01:47', '2025-04-10 20:02:49', -1),
(6, 2, 1, 0, 1, 1, '2025-04-10 20:01:47', '2025-04-10 20:02:49', -1),
(7, 2, 1, 0, 1, 1, '2025-04-10 20:01:47', '2025-04-10 20:02:49', -1),
(8, 2, 2, 0, 1, 1, '2025-04-10 20:01:47', '2025-04-10 21:47:57', -1),
(9, 0, 0, 0, 2, 1, '2025-04-15 08:37:49', '2025-04-15 08:37:49', -1),
(10, 0, 0, 0, 2, 1, '2025-04-15 08:37:49', '2025-04-15 08:37:49', -1),
(11, 0, 0, 0, 2, 1, '2025-04-15 08:37:49', '2025-04-15 08:37:49', -1),
(12, 0, 0, 0, 2, 1, '2025-04-15 08:37:49', '2025-04-15 08:37:49', -1);

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
(1, 1, 'admin@admin', '$2y$10$RM7RxWYU/Lvg2LS6bGN/M.MSWcBMnlEv9YjLcCf5nplSU1e0OFhkC', 'admin', '2025-04-09 16:39:44', '2025-04-09 16:39:44'),
(2, 1, '190101.cse@student.just.edu.bd', '$2y$10$DbhQD.n5TO5tq15v69OE3OQ7j/7D0kbLdywBmz0mb1nGK0CsaSf/O', 'user', '2025-04-09 16:44:26', '2025-04-10 17:07:59'),
(3, 1, '190102.cse@student.just.edu.bd', '$2y$10$3mNNUeH6T0AdilyuGL4Dj.8geI6p54mMIkZp6QAfEWiZVFRh2fqOS', 'user', '2025-04-09 16:49:07', '2025-04-10 17:08:16'),
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
(14, 0, '190119.cse@student.just.edu.bd', '$2y$10$D9lsz455MDw89WJ83bNa8.jmUTifE/6okCUU99UWrQABOzduyj3jO', 'user', '2025-04-09 21:08:01', '2025-04-09 21:08:01');

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
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user_details`
--

INSERT INTO `tbl_user_details` (`details_id`, `status`, `user_id`, `profile_picture_id`, `full_name`, `student_id`, `gender`, `contact_no`, `session`, `year`, `semester`, `last_semester_cgpa_or_merit`, `district`, `division`, `permanent_address`, `present_address`, `father_name`, `father_contact_no`, `father_profession`, `father_monthly_income`, `mother_name`, `mother_contact_no`, `mother_profession`, `mother_monthly_income`, `guardian_name`, `guardian_contact_no`, `guardian_address`, `document_id`, `note_ids`, `created`, `modified`) VALUES
(1, 1, 2, 27, 'Masum Billah', 190101, 'male', '01971636762', '2019-2020', 4, 2, 3.45, 'Satkhira', 'Khulna', 'House No-12 Road-55, Satkhira, Khulna, Bangladesh.', 'Ambottola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Md. Azad billah', '01713079023', 'Businessman', 15000, 'Halima Khatun', '01715579054', 'Housewife', 0, 'Mehedi Billah', '01710079023', 'House No-15, Road No-55, Moylapota, Khulna.', 2, '', '2025-04-09 16:44:26', '2025-04-11 17:32:01'),
(2, 1, 3, 3, 'Joydip Das', 190102, 'male', '01995140040', '2019-2020', 4, 2, 3.65, 'Jashore', 'Khulna', 'House No-12 Road No-1, Rupdiya, Jashore, Khulna, Bangladesh.', 'Ambottola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Joyanto Das', '01713079023', 'Businessman', 12000, 'kakoli Das', '01715579054', 'Housewife', 0, 'Jahir Das', '01712032021', 'House No-15, Road No-55,Noapara, Jashore, Khulna, Bangladesh', 4, '', '2025-04-09 16:49:07', '2025-04-10 17:08:16'),
(3, 1, 4, 5, 'Md. Shojib Hossain', 190103, 'male', '01971636762', '2019-2020', 4, 1, 3.67, 'Pabna', 'Rajshahi', 'House No-12 Road No-5, Pabna, Rajshahi, Bangladesh.', 'Beltola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Md. Shahin Hossain', '01713079023', 'Teacher', 20000, 'Salma Begum', '01712079044', 'Teacher', 20000, 'Md. Sajal Hossain', '01718079021', 'House No-15, Road No-15, Pabna, Rajshahi, Bangladesh.', 6, '', '2025-04-09 16:53:44', '2025-04-10 17:08:17'),
(4, 1, 5, 7, 'Joytiran Mondol Joy', 190104, 'male', '01797428597', '2019-2020', 4, 1, 3.44, 'Khulna', 'Khulna', 'House No-12 Road No-1, Dumuriya, Khulna, Bangladesh.', 'Beltola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Joyanto Das', '01713079023', 'Teacher', 15000, 'kakoli Mondol', '01712079032', 'Housewife', 0, 'Joyonto Mondal', '01713079023', 'House No-12 Road No-1, Dumuriya, Khulna, Bangladesh.', 8, '', '2025-04-09 16:57:48', '2025-04-10 17:08:18'),
(5, 1, 6, 9, 'Tahmidul Kabir Rafi', 200105, 'male', '01740672465', '2020-2021', 3, 2, 3.33, 'Rajbari', 'Dhaka', 'House No-1A Road-15, Rajbari, Dhaka, Bangladesh.', 'Palbari Royel Mor, Jashore, Khulna', 'Md. Tamim Kabir', '01713179023', 'Businessman', 20000, 'Salma Begum', '01711079044', 'Housewife', 0, 'Md. Tamim Kabir', '01713179023', 'House No-1A Road-15, Rajbari, Dhaka, Bangladesh.', 10, '', '2025-04-09 18:20:31', '2025-04-10 17:08:20'),
(6, 1, 7, 11, 'Mirza Mahfuj Hossain', 200106, 'male', '01825787572', '2020-2021', 3, 2, 3.22, 'Tangail', 'Dhaka', 'House No-11 Road No-122, Tangail, Dhaka, Bangladesh.', 'Ambottola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Mirza Mamun Hossain', '01742079023', 'Govertment Service Holder', 15000, 'Amina Hossain', '01712079031', 'Housewife', 0, 'Mirza Shakhawat Hossain', '01718079021', 'House No-11 Road No-122, Tangail, Dhaka, Bangladesh.', 12, '', '2025-04-09 18:27:12', '2025-04-10 17:08:21'),
(7, 1, 8, 13, 'Srijon Sarker', 200107, 'male', '01744110978', '2020-2021', 4, 1, 3.2, 'Sherpur', 'Mymensingh', 'House No-1A Road-11, Sherpur, Dhaka, Bangladesh.', 'Ambottola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Gabindo Sarker', '01710379023', 'Govertment Service Holder', 20000, 'Shikha Sarker', '01715679054', 'Housewife', 0, 'Gabindo Sarker', '01710379023', 'House No-1A Road-11, Sherpur, Dhaka, Bangladesh.', 14, '', '2025-04-09 18:32:51', '2025-04-10 17:08:22'),
(8, 1, 9, 15, 'Md. Tosiqul  Islam', 200108, 'male', '01704305651', '2020-2021', 2, 2, 3.65, 'Chapai Nawabganj', 'Rajshahi', 'House No-12, Road No-22, Chapai Nawabganj, Rajshahi, Bangladesh.', 'Palbari-12, Jashore, Khulna, Bangladesh ', 'Md. Ansar Islam', '01713579023', 'Farmer', 10000, 'Salma Akhter', '01912079032', 'Housewife', 0, 'Md. Ansar Islam', '01713579023', 'House No-12, Road No-22, Chapai Nawabganj, Rajshahi, Bangladesh.', 16, '', '2025-04-09 18:41:06', '2025-04-10 17:08:31'),
(9, 1, 10, 17, 'Sabbir Ahmed', 210110, 'male', '01878038097', '2021-2022', 2, 2, 3.21, 'Khulna', 'Khulna', 'House No-12 Road No-3, Power House, Khulna, Bangladesh.', 'Campus Gate, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Md. Rasel Ahmed', '01913779023', 'Businessman', 15000, 'Amina Ahmed', '01714579032', 'Housewife', 0, 'Md. Rasel Ahmed', '01913779023', 'House No-12 Road No-3, Power House, Khulna, Bangladesh.', 18, '', '2025-04-09 20:25:39', '2025-04-10 17:08:32'),
(10, 1, 11, 19, 'Tanvir Hossain', 220113, 'male', '01643620848', '2022-2023', 2, 1, 3.13, 'Bogura', 'Rajshahi', 'House No-3A Block1 Road No-5, Bogura, Rajshahi, Bangladesh.', 'Palbari-15, Jashore, Khulna', 'Md. Zakir Hossain', '01683079023', 'Govertment Service Holder', 15000, 'Zakia Parvin', '01812079032', 'Housewife', 0, 'Md. Sajal Hossain', '01713279023', 'House No-15, Road No-55, College Road, Rajshahi , Bangladesh', 20, '', '2025-04-09 20:31:57', '2025-04-10 17:08:54'),
(11, 1, 12, 21, 'Sakib Hasan Prangon', 220114, 'male', '01743620844', '2022-2023', 2, 1, 3.14, 'Khulna', 'Khulna', 'House No-21. Road-55, Moylapota, Khulna, Bangladesh.', 'Arabpur-12A, Jashore, Khulna, Bangladesh.', 'Sadman Hasan', '01412079023', 'Businessman', 12000, 'Sonia Akhter', '01714079054', 'Teacher', 10000, 'Shakil Hasan', '01712079021', 'House No-15, Road No-55, Moylapota, Khulna.', 22, '', '2025-04-09 20:49:51', '2025-04-10 17:08:56'),
(12, 0, 13, 23, 'Md. Masrafi Bin Seraj Sakib', 230116, 'male', '01886420246', '2023-2024', 1, 1, 33, 'Khulna', 'Khulna', 'House No-12 Road No-1, Nirala, Khulna, Bangladesh.', 'Arabpur, Jashore, Khulna, Bangladesh.', 'Md. Shahin Seraj', '01643079023', 'Govertment Service Holder', 15000, 'Nasima Begum', '01413079032', 'Housewife', 0, 'Md. Shahin Seraj', '01643079023', 'House No-12 Road No-1, Nirala, Khulna, Bangladesh.', 24, '', '2025-04-09 20:55:49', '2025-04-09 20:55:50'),
(13, 0, 14, 25, 'Mirza Mohibul Hasan ', 230119, 'male', '01991347811', '2023-2024', 1, 1, 44, 'Faridpur', 'Dhaka', 'House No-1A Road-15, Faridpur, Dhaka, Bangladesh.', 'New Market, Jashore, Khulna, Bangladesh.', 'Mirza Amzad Hasan', '01712078123', 'Businessman', 15000, 'Nasrin Hasan', '01325579054', 'Housewife', 0, 'Mirza Mahfuz Hasan', '01710076743', 'House No-12 Road No-1, Faridpur, Dhaka Bangladesh.', 26, '', '2025-04-09 21:08:01', '2025-04-09 21:08:02'),
(14, -2, 2, 27, 'Masum Billah', 190101, 'male', '01971636762', '2019-2020', 4, 2, 3.45, 'Satkhira', 'Khulna', 'House No-12 Road-55, Satkhira, Khulna, Bangladesh.', 'Ambottola, Chowagacha Road, Jashore, Khulna, Bangladesh.', 'Md. Azad billah', '01713079023', 'Businessman', 15000, 'Halima Khatun', '01715579054', 'Housewife', 0, 'Mehedi Billah', '01710079023', 'House No-15, Road No-55, Moylapota, Khulna-8899.', 2, '', '2025-04-11 17:54:57', '2025-04-11 19:18:33');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `tbl_file`
--
ALTER TABLE `tbl_file`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_hall_seat_allocation_event`
--
ALTER TABLE `tbl_hall_seat_allocation_event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_hall_seat_application`
--
ALTER TABLE `tbl_hall_seat_application`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_hall_seat_details`
--
ALTER TABLE `tbl_hall_seat_details`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_user_details`
--
ALTER TABLE `tbl_user_details`
  MODIFY `details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
