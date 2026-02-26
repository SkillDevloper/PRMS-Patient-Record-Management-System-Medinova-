-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 06:59 PM
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
-- Database: `prms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `specialty` varchar(255) NOT NULL,
  `contact_number_dr` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `hospital_name` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialty`, `contact_number_dr`, `email`, `experience`, `hospital_name`, `status`, `created_at`) VALUES
(1, 'Dr. John Doe', 'Cardiology', '1234567890', 'john.doe@gmail.com', 10, 'City Hospital', 'Active', '2025-05-02 17:36:18'),
(2, 'Dr. Jane Smith', 'Orthopedics', '1234567891', 'jane.smith@gmail.com', 8, 'City Hospital', 'Active', '2025-05-02 17:36:18'),
(3, 'Dr. Mary Johnson', 'Neurology', '1234567892', 'mary.johnson@gmail.com', 12, 'City Hospital', 'Active', '2025-05-02 17:36:18'),
(4, 'Dr. James Brown', 'Dermatology', '1234567893', 'james.brown@gmail.com', 7, 'General Hospital', 'Active', '2025-05-02 17:36:18'),
(5, 'Dr. Linda Davis', 'Pediatrics', '1234567894', 'linda.davis@gmail.com', 5, 'General Hospital', 'Active', '2025-05-02 17:36:18'),
(6, 'Dr. Patricia Miller', 'Gastroenterology', '1234567895', 'patricia.miller@gmail.com', 6, 'Heart Care Hospital', 'Active', '2025-05-02 17:36:18'),
(7, 'Dr. Robert Wilson', 'Ophthalmology', '1234567896', 'robert.wilson@gmail.com', 15, 'Eye Health Clinic', 'Active', '2025-05-02 17:36:18'),
(8, 'Dr. Michael Moore', 'Psychiatry', '1234567897', 'michael.moore@gmail.com', 10, 'Mind Health Clinic', 'Active', '2025-05-02 17:36:18'),
(9, 'Dr. Elizabeth Taylor', 'Endocrinology', '1234567898', 'elizabeth.taylor@gmail.com', 14, 'Health Center', 'Active', '2025-05-02 17:36:18'),
(10, 'Dr. Joseph Harris', 'ENT', '1234567899', 'joseph.harris@gmail.com', 9, 'General Hospital', 'Active', '2025-05-02 17:36:18');

-- --------------------------------------------------------

--
-- Table structure for table `medications`
--

CREATE TABLE `medications` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `medication_name` varchar(255) NOT NULL,
  `dosage` varchar(100) NOT NULL,
  `frequency` varchar(50) DEFAULT NULL,
  `prescribed_by` int(11) DEFAULT NULL,
  `start_date` date DEFAULT curdate(),
  `end_date` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medications`
--

INSERT INTO `medications` (`id`, `patient_id`, `medication_name`, `dosage`, `frequency`, `prescribed_by`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(6, 45, 'Losartan', '500mg', 'Once a day', 3, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(7, 55, 'Lisinopril', '20mg', 'Once a day', 5, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(8, 86, 'Clonazepam', '10mg', 'Once a day', 9, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(9, 43, 'Aspirin', '250mg', 'Twice a day', 1, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(10, 1, 'Lisinopril', '20mg', 'Twice a day', 8, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(11, 50, 'Ibuprofen', '20mg', 'Three times a day', 9, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(12, 4, 'Omeprazole', '20mg', 'Once a day', 4, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(13, 89, 'Clonazepam', '500mg', 'Three times a day', 10, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(14, 41, 'Clonazepam', '10mg', 'Twice a day', 10, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(15, 46, 'Amoxicillin', '200mg', 'Once a day', 1, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(16, 17, 'Advil', '10mg', 'Three times a day', 7, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(17, 64, 'Doxycycline', '5mg', 'Three times a day', 1, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(18, 20, 'Metformin', '500mg', 'Three times a day', 7, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(19, 46, 'Azithromycin', '20mg', 'Twice a day', 10, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(20, 1, 'Ciprofloxacin', '250mg', 'Once a day', 2, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(21, 30, 'Azithromycin', '20mg', 'Three times a day', 6, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(22, 92, 'Levothyroxine', '200mg', 'Three times a day', 10, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(23, 81, 'Zoloft', '500mg', 'Once a day', 4, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(24, 4, 'Prednisone', '500mg', 'Three times a day', 6, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(25, 55, 'Doxycycline', '20mg', 'Once a day', 8, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(26, 27, 'Advil', '100mg', 'Twice a day', 6, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(27, 59, 'Prednisone', '200mg', 'Three times a day', 4, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(28, 8, 'Metoprolol', '5mg', 'Twice a day', 8, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(29, 48, 'Atorvastatin', '200mg', 'Once a day', 3, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(30, 42, 'Losartan', '100mg', 'Three times a day', 8, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(31, 39, 'Gabapentin', '100mg', 'Twice a day', 10, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(32, 42, 'Prednisone', '10mg', 'Once a day', 5, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(33, 23, 'Levothyroxine', '10mg', 'Once a day', 4, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(34, 73, 'Prednisone', '10mg', 'Twice a day', 7, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(35, 5, 'Zoloft', '250mg', 'Twice a day', 10, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(36, 66, 'Aspirin', '5mg', 'Once a day', 8, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(37, 68, 'Hydrochlorothiazide', '200mg', 'Once a day', 7, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(38, 106, 'Ibuprofen', '100mg', 'Twice a day', 8, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(39, 22, 'Ibuprofen', '100mg', 'Twice a day', 5, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(40, 11, 'Amoxicillin', '500mg', 'Three times a day', 5, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(41, 76, 'Amoxicillin', '500mg', 'Three times a day', 7, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(42, 1, 'Ventolin', '20mg', 'Twice a day', 7, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(43, 2, 'Ventolin', '100mg', 'Three times a day', 4, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(44, 40, 'Omeprazole', '500mg', 'Once a day', 4, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(45, 40, 'Prednisone', '250mg', 'Once a day', 9, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(46, 38, 'Metoprolol', '10mg', 'Twice a day', 5, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(47, 30, 'Advil', '10mg', 'Three times a day', 7, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(48, 53, 'Prednisone', '20mg', 'Once a day', 5, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(49, 66, 'Metoprolol', '200mg', 'Three times a day', 4, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(50, 52, 'Losartan', '500mg', 'Three times a day', 7, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(51, 84, 'Amoxicillin', '250mg', 'Once a day', 8, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(52, 8, 'Prednisone', '5mg', 'Once a day', 8, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(53, 69, 'Losartan', '20mg', 'Three times a day', 10, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(54, 5, 'Lisinopril', '500mg', 'Twice a day', 9, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(55, 79, 'Metoprolol', '250mg', 'Once a day', 8, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(56, 55, 'Ciprofloxacin', '500mg', 'Once a day', 6, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(57, 82, 'Clonazepam', '200mg', 'Three times a day', 4, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(58, 97, 'Ventolin', '100mg', 'Twice a day', 1, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(59, 5, 'Metoprolol', '200mg', 'Twice a day', 1, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(60, 49, 'Losartan', '250mg', 'Three times a day', 1, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(61, 26, 'Azithromycin', '10mg', 'Three times a day', 8, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(62, 36, 'Metoprolol', '200mg', 'Once a day', 6, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(63, 73, 'Losartan', '20mg', 'Once a day', 8, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(64, 89, 'Advil', '20mg', 'Once a day', 3, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(65, 67, 'Azithromycin', '20mg', 'Three times a day', 3, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(66, 88, 'Doxycycline', '250mg', 'Once a day', 10, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(67, 36, 'Ventolin', '100mg', 'Once a day', 6, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(68, 3, 'Advil', '20mg', 'Once a day', 3, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(69, 30, 'Lisinopril', '200mg', 'Three times a day', 1, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(70, 35, 'Ciprofloxacin', '200mg', 'Twice a day', 2, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(71, 97, 'Ventolin', '10mg', 'Once a day', 4, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(72, 71, 'Clonazepam', '250mg', 'Twice a day', 3, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(73, 46, 'Doxycycline', '200mg', 'Three times a day', 9, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(74, 84, 'Prednisone', '500mg', 'Once a day', 1, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(75, 74, 'Azithromycin', '200mg', 'Once a day', 8, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(76, 101, 'Lisinopril', '250mg', 'Once a day', 7, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(77, 71, 'Zoloft', '5mg', 'Once a day', 3, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(78, 64, 'Azithromycin', '200mg', 'Twice a day', 5, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(79, 70, 'Metformin', '250mg', 'Once a day', 1, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(80, 41, 'Advil', '20mg', 'Once a day', 4, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(81, 26, 'Omeprazole', '10mg', 'Twice a day', 9, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(82, 104, 'Metformin', '10mg', 'Twice a day', 9, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(83, 63, 'Ventolin', '500mg', 'Three times a day', 3, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(84, 47, 'Ibuprofen', '200mg', 'Once a day', 5, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(85, 73, 'Prednisone', '10mg', 'Twice a day', 2, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(86, 3, 'Doxycycline', '500mg', 'Once a day', 5, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(87, 42, 'Gabapentin', '200mg', 'Three times a day', 7, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(88, 46, 'Ciprofloxacin', '20mg', 'Twice a day', 7, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(89, 89, 'Amoxicillin', '200mg', 'Three times a day', 5, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(90, 70, 'Omeprazole', '5mg', 'Three times a day', 7, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(91, 32, 'Ibuprofen', '100mg', 'Once a day', 2, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(92, 40, 'Amoxicillin', '250mg', 'Twice a day', 2, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(93, 67, 'Atorvastatin', '200mg', 'Once a day', 8, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(94, 33, 'Lisinopril', '250mg', 'Three times a day', 5, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(95, 11, 'Clonazepam', '500mg', 'Once a day', 8, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(96, 78, 'Doxycycline', '100mg', 'Twice a day', 10, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(97, 70, 'Zoloft', '100mg', 'Twice a day', 4, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(98, 71, 'Amoxicillin', '100mg', 'Twice a day', 7, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(99, 82, 'Ibuprofen', '10mg', 'Three times a day', 4, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(100, 90, 'Amoxicillin', '5mg', 'Three times a day', 6, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(101, 25, 'Azithromycin', '10mg', 'Once a day', 6, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(102, 15, 'Aspirin', '5mg', 'Twice a day', 8, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(103, 30, 'Ciprofloxacin', '10mg', 'Once a day', 5, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(104, 78, 'Ventolin', '200mg', 'Three times a day', 7, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(105, 86, 'Ibuprofen', '5mg', 'Three times a day', 8, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(106, 77, 'Metoprolol', '5mg', 'Three times a day', 7, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(107, 13, 'Omeprazole', '10mg', 'Three times a day', 9, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(108, 48, 'Prednisone', '500mg', 'Three times a day', 8, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(109, 24, 'Ciprofloxacin', '10mg', 'Twice a day', 6, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(110, 22, 'Doxycycline', '100mg', 'Once a day', 9, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(111, 69, 'Levothyroxine', '20mg', 'Once a day', 6, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(112, 13, 'Ciprofloxacin', '100mg', 'Twice a day', 10, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(113, 70, 'Doxycycline', '20mg', 'Once a day', 7, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(114, 20, 'Clonazepam', '250mg', 'Three times a day', 7, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(115, 56, 'Advil', '100mg', 'Once a day', 8, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(116, 23, 'Atorvastatin', '100mg', 'Once a day', 8, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(117, 2, 'Omeprazole', '200mg', 'Once a day', 7, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(118, 20, 'Atorvastatin', '500mg', 'Twice a day', 5, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(119, 85, 'Ventolin', '20mg', 'Once a day', 1, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(120, 100, 'Losartan', '20mg', 'Three times a day', 6, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(121, 70, 'Levothyroxine', '5mg', 'Twice a day', 2, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18'),
(122, 70, 'Metoprolol', '5mg', 'Twice a day', 5, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(123, 67, 'Amoxicillin', '200mg', 'Three times a day', 5, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(124, 87, 'Prednisone', '20mg', 'Three times a day', 3, '2025-05-02', '2025-12-31', 'inactive', '2025-05-02 17:36:18'),
(125, 57, 'Clonazepam', '100mg', 'Once a day', 7, '2025-05-02', '2025-12-31', 'active', '2025-05-02 17:36:18');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `medical_history` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  `location` varchar(255) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `age`, `gender`, `medical_history`, `created_at`, `status`, `location`, `contact_number`, `image`) VALUES
(1, 'John Smith', 45, 'male', 'Diabetes, Hypertension', '2025-05-02 17:36:18', 'active', 'New York', '1234567890', 'default1.png'),
(2, 'Jane Doe', 38, 'female', 'Asthma', '2025-05-02 17:36:18', 'inactive', 'Los Angeles', '1234567891', 'default1.png'),
(3, 'Tom Lee', 60, 'male', 'Heart Disease', '2025-05-02 17:36:18', 'active', 'San Francisco', '1234567892', 'default1.png'),
(4, 'Emily Davis', 29, 'female', 'Back pain', '2025-05-02 17:36:18', 'inactive', 'Chicago', '1234567893', 'default1.png'),
(5, 'David Clark', 40, 'male', 'Hypertension', '2025-05-02 17:36:18', 'active', 'Miami', '1234567894', 'default1.png'),
(6, 'Nancy Wilson', 52, 'female', 'Arthritis', '2025-05-02 17:36:18', 'active', 'Houston', '1234567895', 'default1.png'),
(7, 'Tyler Jacobs', 50, 'female', 'Back pain', '2025-05-02 17:36:18', 'active', 'Austin', '3344856433', 'default1.png'),
(8, 'Theresa Clark', 24, 'male', 'Anxiety, Cholesterol, Thyroid', '2025-05-02 17:36:18', 'active', 'Austin', '5092707915', 'default1.png'),
(9, 'Megan Williams', 54, 'female', 'Depression', '2025-05-02 17:36:18', 'active', 'Jacksonville', '9714161159', 'default1.png'),
(10, 'Daniel Rangel', 50, 'female', 'Thyroid', '2025-05-02 17:36:18', 'active', 'Dallas', '6832982754', 'default1.png'),
(11, 'Angela Harris', 27, 'male', 'Cholesterol, Obesity, Thyroid', '2025-05-02 17:36:18', 'inactive', 'Fort Worth', '1603740724', 'default1.png'),
(12, 'Kayla Crane', 76, 'male', 'Flu, Allergy, Arthritis', '2025-05-02 17:36:18', 'active', 'Charlotte', '0994960413', 'default1.png'),
(13, 'Brian Stephens', 31, 'female', 'Back pain', '2025-05-02 17:36:18', 'active', 'Philadelphia', '5771373159', 'default1.png'),
(14, 'Jessica Hays', 43, 'male', 'Back pain, Cholesterol', '2025-05-02 17:36:18', 'active', 'Houston', '3225999044', 'default1.png'),
(15, 'Ashley Alvarez', 64, 'female', 'Hypertension', '2025-05-02 17:36:18', 'inactive', 'Dallas', '8555893026', 'default1.png'),
(16, 'Nicole Hardin', 65, 'female', 'Hypertension', '2025-05-02 17:36:18', 'inactive', 'Philadelphia', '5915479025', 'default1.png'),
(17, 'Terry Taylor', 33, 'female', 'Diabetes', '2025-05-02 17:36:18', 'active', 'Phoenix', '4744993225', 'default1.png'),
(18, 'Jodi Rodriguez', 38, 'male', 'Diabetes, Allergy, Obesity', '2025-05-02 17:36:18', 'inactive', 'San Jose', '2773816486', 'default1.png'),
(19, 'Danielle Lane', 29, 'female', 'Hypertension', '2025-05-02 17:36:18', 'inactive', 'Charlotte', '7777127105', 'default1.png'),
(20, 'John Cowan', 55, 'male', 'Diabetes, Back pain', '2025-05-02 17:36:18', 'active', 'Phoenix', '0869800682', 'default1.png'),
(21, 'Meagan Shaw', 43, 'male', 'Depression, Arthritis, Heart Disease', '2025-05-02 17:36:18', 'active', 'New York', '2567147285', 'default1.png'),
(22, 'Benjamin Thomas', 53, 'male', 'Thyroid', '2025-05-02 17:36:18', 'inactive', 'Jacksonville', '8327204002', 'default1.png'),
(23, 'Linda Williams', 77, 'female', 'Thyroid, Back pain', '2025-05-02 17:36:18', 'active', 'Austin', '0822518127', 'default1.png'),
(24, 'Mrs. Andrea Barrett', 58, 'female', 'Heart Disease, Thyroid', '2025-05-02 17:36:18', 'inactive', 'Jacksonville', '5997197386', 'default1.png'),
(25, 'William Jenkins', 76, 'female', 'Allergy', '2025-05-02 17:36:18', 'inactive', 'New York', '9459415760', 'default1.png'),
(26, 'Nathan Carroll', 70, 'male', 'Hypertension, Thyroid, Depression', '2025-05-02 17:36:18', 'active', 'Columbus', '3407448109', 'default1.png'),
(27, 'Frank Sharp', 29, 'male', 'Hypertension, COVID-19, Arthritis', '2025-05-02 17:36:18', 'active', 'Columbus', '6742123543', 'default1.png'),
(28, 'David Schwartz', 36, 'male', 'Flu, Hypertension', '2025-05-02 17:36:18', 'active', 'New York', '6264590921', 'default1.png'),
(29, 'Bridget Melton', 64, 'male', 'Thyroid, Heart Disease', '2025-05-02 17:36:18', 'active', 'San Antonio', '3099590273', 'default1.png'),
(30, 'Christopher Kirk', 38, 'male', 'Anxiety, Hypertension', '2025-05-02 17:36:18', 'inactive', 'San Jose', '2230348875', 'default1.png'),
(31, 'Emily Bright', 72, 'female', 'Diabetes', '2025-05-02 17:36:18', 'inactive', 'Jacksonville', '3267749336', 'default1.png'),
(32, 'Melanie Thompson', 19, 'male', 'Thyroid', '2025-05-02 17:36:18', 'inactive', 'San Antonio', '0093725614', 'default1.png'),
(33, 'Robert Cummings', 44, 'male', 'Asthma, Depression, Diabetes', '2025-05-02 17:36:18', 'inactive', 'San Antonio', '0073517794', 'default1.png'),
(34, 'Deborah Short', 42, 'male', 'Allergy', '2025-05-02 17:36:18', 'active', 'New York', '5899211622', 'default1.png'),
(35, 'Matthew Cervantes', 79, 'female', 'Allergy', '2025-05-02 17:36:18', 'inactive', 'San Antonio', '7299802403', 'default1.png'),
(36, 'James Stevenson', 27, 'male', 'Asthma, Cholesterol', '2025-05-02 17:36:18', 'active', 'Fort Worth', '4449330232', 'default1.png'),
(37, 'Brian Rojas', 51, 'male', 'Diabetes, Allergy', '2025-05-02 17:36:18', 'active', 'Austin', '9167248923', 'default1.png'),
(38, 'Tina Wright', 31, 'male', 'Obesity, Diabetes, Anxiety', '2025-05-02 17:36:18', 'active', 'San Jose', '1159149583', 'default1.png'),
(39, 'Natasha Simpson', 51, 'female', 'Hypertension', '2025-05-02 17:36:18', 'inactive', 'Austin', '4075161573', 'default1.png'),
(40, 'Sandra Singh', 60, 'female', 'COVID-19', '2025-05-02 17:36:18', 'active', 'Los Angeles', '9953044872', 'default1.png'),
(41, 'Charles Davenport', 42, 'male', 'Arthritis', '2025-05-02 17:36:18', 'active', 'Charlotte', '8515495017', 'default1.png'),
(42, 'Kevin Jones', 62, 'female', 'Arthritis', '2025-05-02 17:36:18', 'active', 'Fort Worth', '3544132499', 'default1.png'),
(43, 'Janet Smith', 45, 'female', 'Anxiety, Asthma, Diabetes', '2025-05-02 17:36:18', 'inactive', 'San Antonio', '7737192129', 'default1.png'),
(44, 'Gabrielle Hanson', 49, 'female', 'Allergy', '2025-05-02 17:36:18', 'inactive', 'Austin', '1481856406', 'default1.png'),
(45, 'Kristin Odom', 73, 'female', 'Allergy', '2025-05-02 17:36:18', 'inactive', 'Philadelphia', '8030250618', 'default1.png'),
(46, 'Holly Roth', 77, 'male', 'Depression, Migraines', '2025-05-02 17:36:18', 'inactive', 'San Jose', '7911290186', 'default1.png'),
(47, 'Rose Riley', 31, 'male', 'Thyroid, Allergy, Hypertension', '2025-05-02 17:36:18', 'active', 'Phoenix', '5903172538', 'default1.png'),
(48, 'Angela Mooney', 20, 'female', 'Heart Disease, Allergy', '2025-05-02 17:36:18', 'active', 'Jacksonville', '3319666235', 'default1.png'),
(49, 'Erika Acevedo', 37, 'female', 'Flu, Diabetes', '2025-05-02 17:36:18', 'inactive', 'San Jose', '3931511844', 'default1.png'),
(50, 'Sheila Trujillo', 45, 'male', 'Hypertension, Cholesterol, Thyroid', '2025-05-02 17:36:18', 'active', 'Charlotte', '2977946557', 'default1.png'),
(51, 'Tracy Allen', 45, 'male', 'Cholesterol, Flu', '2025-05-02 17:36:18', 'active', 'New York', '7268347224', 'default1.png'),
(52, 'Adam Webb', 50, 'male', 'Cholesterol, Heart Disease', '2025-05-02 17:36:18', 'active', 'San Jose', '8611216846', 'default1.png'),
(53, 'Alexis Guzman', 18, 'male', 'Thyroid, Depression', '2025-05-02 17:36:18', 'inactive', 'San Antonio', '2988404906', 'default1.png'),
(54, 'William Brandt', 49, 'female', 'Back pain, Allergy, Depression', '2025-05-02 17:36:18', 'inactive', 'Houston', '2064148184', 'default1.png'),
(55, 'Michael Martinez', 29, 'male', 'Migraines, Back pain', '2025-05-02 17:36:18', 'inactive', 'Phoenix', '9430200106', 'default1.png'),
(56, 'Alyssa Jones', 68, 'female', 'Arthritis, Diabetes', '2025-05-02 17:36:18', 'active', 'Chicago', '6962923249', 'default1.png'),
(57, 'Rachel Robinson', 70, 'female', 'Back pain, Depression', '2025-05-02 17:36:18', 'inactive', 'Philadelphia', '0669814669', 'default1.png'),
(58, 'Heather Gallegos', 68, 'male', 'Hypertension', '2025-05-02 17:36:18', 'active', 'Fort Worth', '9148923064', 'default1.png'),
(59, 'Melvin Turner', 70, 'male', 'Anxiety, Allergy', '2025-05-02 17:36:18', 'inactive', 'Austin', '0882099653', 'default1.png'),
(60, 'Donald Clark', 55, 'female', 'Asthma, Heart Disease', '2025-05-02 17:36:18', 'active', 'Charlotte', '9579925487', 'default1.png'),
(61, 'Linda Harris', 21, 'male', 'Asthma, Anxiety, Depression', '2025-05-02 17:36:18', 'active', 'Los Angeles', '4096058154', 'default1.png'),
(62, 'Kathy Patel', 76, 'male', 'Migraines', '2025-05-02 17:36:18', 'inactive', 'New York', '2146455293', 'default1.png'),
(63, 'Evan Livingston', 25, 'female', 'Anxiety', '2025-05-02 17:36:18', 'active', 'Fort Worth', '4716372885', 'default1.png'),
(64, 'Ryan White', 64, 'male', 'Obesity, Migraines, Depression', '2025-05-02 17:36:18', 'inactive', 'New York', '1055796218', 'default1.png'),
(65, 'Julie Lam', 24, 'male', 'Obesity, Cholesterol, Asthma', '2025-05-02 17:36:18', 'inactive', 'San Diego', '5058716096', 'default1.png'),
(66, 'Amanda Thomas', 64, 'female', 'Anxiety, Migraines', '2025-05-02 17:36:18', 'active', 'Charlotte', '6851779902', 'default1.png'),
(67, 'Tammy Bentley', 67, 'female', 'Depression, Hypertension', '2025-05-02 17:36:18', 'inactive', 'Fort Worth', '7087239220', 'default1.png'),
(68, 'Michael Evans DVM', 26, 'male', 'Back pain', '2025-05-02 17:36:18', 'inactive', 'Fort Worth', '8154688626', 'default1.png'),
(69, 'Monica Boyd', 66, 'male', 'Allergy, Diabetes, Obesity', '2025-05-02 17:36:18', 'active', 'Phoenix', '2109993243', 'default1.png'),
(70, 'Teresa Santos', 62, 'male', 'Allergy, Cholesterol, Back pain', '2025-05-02 17:36:18', 'inactive', 'Jacksonville', '4333668240', 'default1.png'),
(71, 'Blake Edwards', 66, 'male', 'Migraines, Anxiety', '2025-05-02 17:36:18', 'active', 'Houston', '6015703565', 'default1.png'),
(72, 'Joseph Rich', 42, 'female', 'Migraines', '2025-05-02 17:36:18', 'active', 'Jacksonville', '1260292888', 'default1.png'),
(73, 'Amy Fuentes', 65, 'male', 'Migraines, Hypertension', '2025-05-02 17:36:18', 'active', 'Los Angeles', '5634869379', 'default1.png'),
(74, 'Michelle Johnson', 74, 'male', 'Thyroid', '2025-05-02 17:36:18', 'inactive', 'Houston', '8142689574', 'default1.png'),
(75, 'David Mcdaniel', 73, 'female', 'Heart Disease', '2025-05-02 17:36:18', 'active', 'Fort Worth', '8976781594', 'default1.png'),
(76, 'Amy Miller', 26, 'male', 'Flu, Arthritis, Anxiety', '2025-05-02 17:36:18', 'inactive', 'San Jose', '7166500734', 'default1.png'),
(77, 'Isaac Bennett', 57, 'male', 'Obesity', '2025-05-02 17:36:18', 'active', 'New York', '2994702313', 'default1.png'),
(78, 'Andrea Richards', 67, 'female', 'Anxiety, Diabetes, Asthma', '2025-05-02 17:36:18', 'inactive', 'Columbus', '1777521029', 'default1.png'),
(79, 'Brittany Porter', 55, 'male', 'Flu, Arthritis, Migraines', '2025-05-02 17:36:18', 'active', 'San Diego', '7601057355', 'default1.png'),
(80, 'Christopher Martinez', 53, 'female', 'Arthritis', '2025-05-02 17:36:18', 'inactive', 'Chicago', '0882794031', 'default1.png'),
(81, 'Robert Adams', 35, 'female', 'Anxiety, Back pain, Arthritis', '2025-05-02 17:36:18', 'active', 'Charlotte', '9932711345', 'default1.png'),
(82, 'Gabriel Daniels', 50, 'female', 'Arthritis, Anxiety', '2025-05-02 17:36:18', 'inactive', 'New York', '9280826522', 'default1.png'),
(83, 'Angela Fuentes', 68, 'female', 'Depression, Heart Disease, Asthma', '2025-05-02 17:36:18', 'inactive', 'Fort Worth', '5105928328', 'default1.png'),
(84, 'Kerri Martin', 58, 'male', 'Hypertension, Allergy, Flu', '2025-05-02 17:36:18', 'inactive', 'New York', '7036181614', 'default1.png'),
(85, 'Charles Curry', 72, 'female', 'Arthritis, Depression', '2025-05-02 17:36:18', 'active', 'Dallas', '9577466284', 'default1.png'),
(86, 'Julie Montoya', 18, 'male', 'Obesity, Diabetes, Anxiety', '2025-05-02 17:36:18', 'inactive', 'Columbus', '7008650069', 'default1.png'),
(87, 'April Estes', 52, 'female', 'Migraines', '2025-05-02 17:36:18', 'inactive', 'San Antonio', '3023205629', 'default1.png'),
(88, 'John Webster', 78, 'female', 'Thyroid, Flu', '2025-05-02 17:36:18', 'active', 'Columbus', '9223976407', 'default1.png'),
(89, 'Brandon Newman', 72, 'female', 'Obesity, Migraines', '2025-05-02 17:36:18', 'active', 'Fort Worth', '9435395978', 'default1.png'),
(90, 'Eric James', 27, 'female', 'Back pain, Anxiety', '2025-05-02 17:36:18', 'inactive', 'Austin', '9963039542', 'default1.png'),
(91, 'Bruce Johnson', 35, 'male', 'Allergy, Obesity', '2025-05-02 17:36:18', 'active', 'Philadelphia', '2605435050', 'default1.png'),
(92, 'Tracy Perez', 32, 'male', 'Arthritis, Diabetes', '2025-05-02 17:36:18', 'inactive', 'Charlotte', '6021040761', 'default1.png'),
(93, 'Jordan Bell', 70, 'male', 'Hypertension', '2025-05-02 17:36:18', 'active', 'Columbus', '2457539109', 'default1.png'),
(94, 'Amy Smith', 24, 'female', 'Migraines, Anxiety', '2025-05-02 17:36:18', 'inactive', 'Chicago', '6609265753', 'default1.png'),
(95, 'Alexander Pena', 31, 'female', 'Migraines, Flu, Obesity', '2025-05-02 17:36:18', 'inactive', 'Jacksonville', '5569237353', 'default1.png'),
(96, 'Justin Chang', 38, 'female', 'Back pain, Cholesterol', '2025-05-02 17:36:18', 'active', 'Los Angeles', '1465477526', 'default1.png'),
(97, 'Julie Frazier', 26, 'female', 'Cholesterol, Asthma, Heart Disease', '2025-05-02 17:36:18', 'inactive', 'San Jose', '9398012603', 'default1.png'),
(98, 'Dennis Schroeder', 35, 'male', 'Obesity', '2025-05-02 17:36:18', 'inactive', 'Austin', '2281561179', 'default1.png'),
(99, 'Rebecca Hobbs', 80, 'male', 'Flu', '2025-05-02 17:36:18', 'inactive', 'San Antonio', '7923556382', 'default1.png'),
(100, 'Sally Carson', 54, 'female', 'Diabetes, Heart Disease', '2025-05-02 17:36:18', 'inactive', 'Phoenix', '2031067174', 'default1.png'),
(101, 'Mr. Ryan Hall', 51, 'female', 'Migraines, Cholesterol, Diabetes', '2025-05-02 17:36:18', 'active', 'Charlotte', '4058046342', 'default1.png'),
(102, 'John Chung', 70, 'male', 'Back pain', '2025-05-02 17:36:18', 'active', 'Austin', '4133524749', 'default1.png'),
(103, 'Michael Woodward', 78, 'male', 'Asthma, Diabetes, Depression', '2025-05-02 17:36:18', 'active', 'Philadelphia', '9543066629', 'default1.png'),
(104, 'Charlotte Fisher', 40, 'male', 'Allergy, Arthritis, Depression', '2025-05-02 17:36:18', 'active', 'Charlotte', '5516292851', 'default1.png'),
(105, 'Rachel Thomas', 34, 'female', 'Depression', '2025-05-02 17:36:18', 'active', 'Phoenix', '9438900347', 'default1.png'),
(106, 'Daniel Calderon', 19, 'male', 'COVID-19, Anxiety', '2025-05-02 17:36:18', 'active', 'San Antonio', '5385030768', 'default1.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Doctor','Receptionist') NOT NULL DEFAULT 'Receptionist',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `profile_picture`) VALUES
(1, 'Admin', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70', 'Admin', '2025-05-02 17:36:18', 'default.png'),
(2, 'Dr. John Doe', 'doc@gmail.com', '202cb962ac59075b964b07152d234b70', 'Doctor', '2025-05-02 17:36:18', 'default.png'),
(3, 'Receptionist', 'rec@gmail.com', '202cb962ac59075b964b07152d234b70', 'Receptionist', '2025-05-02 17:36:18', 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `medications`
--
ALTER TABLE `medications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_medications_patient` (`patient_id`),
  ADD KEY `fk_medications_doctor` (`prescribed_by`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `medications`
--
ALTER TABLE `medications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `medications`
--
ALTER TABLE `medications`
  ADD CONSTRAINT `fk_medications_doctor` FOREIGN KEY (`prescribed_by`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_medications_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
