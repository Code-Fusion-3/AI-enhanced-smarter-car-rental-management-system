-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2025 at 04:56 PM
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
-- Database: `car_rental_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `car_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `mileage` int(11) DEFAULT NULL,
  `fuel_type` enum('petrol','diesel','electric','hybrid') DEFAULT NULL,
  `transmission` enum('manual','automatic') DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `registration_number` varchar(20) NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `base_rate` decimal(10,2) NOT NULL,
  `weekend_rate` decimal(10,2) DEFAULT NULL,
  `weekly_rate` decimal(10,2) DEFAULT NULL,
  `monthly_rate` decimal(10,2) DEFAULT NULL,
  `status` enum('available','rented','maintenance') DEFAULT 'available',
  `image_url` varchar(255) DEFAULT NULL,
  `features` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`car_id`, `category_id`, `make`, `model`, `year`, `mileage`, `fuel_type`, `transmission`, `seats`, `registration_number`, `daily_rate`, `base_rate`, `weekend_rate`, `weekly_rate`, `monthly_rate`, `status`, `image_url`, `features`, `created_at`) VALUES
(1, 3, 'Toyota', 'Camry', 2022, 0, 'petrol', 'automatic', 5, 'ABC123', 50.00, 50.00, 60.00, 300.00, 1200.00, 'available', 'assets/images/cars/1745340701_download (11).jpeg', 'Automatic, GPS, Bluetooth', '2025-03-09 00:09:36'),
(2, 2, 'Honda', 'Civic', 2021, 0, 'petrol', 'automatic', 5, 'XYZ789', 45.00, 45.00, 55.00, 270.00, 1080.00, 'available', 'assets/images/cars/1745340607_download (10).jpeg', 'Automatic, Backup Camera', '2025-03-09 00:09:36'),
(3, 5, 'Tesla', 'Model 3', 2023, 0, 'electric', 'automatic', 5, 'EV1234', 80.00, 80.00, 95.00, 480.00, 1920.00, 'available', 'assets/images/cars/1745340534_Tesla Roadster Matte Black.jpeg', 'Electric, Autopilot, Premium Sound', '2025-03-09 00:09:36'),
(4, 4, 'BMW', 'X5', 2021, 45000, 'diesel', 'automatic', 7, 'BMX500', 100.00, 100.00, 120.00, 600.00, 2400.00, 'available', 'assets/images/cars/1745341237_pexels-mikebirdy-93615.jpg', '', '2025-04-22 17:00:37'),
(5, 5, 'Mercedes-Benz', 'C-Class', 2023, 15000, 'petrol', 'automatic', 5, 'MB2023', 90.00, 90.00, 108.00, 540.00, 2160.00, 'available', 'assets/images/cars/1745341523_download (12).jpeg', '', '2025-04-22 17:05:23'),
(6, 5, 'Nissan', 'Leaf', 2021, 18000, 'electric', 'automatic', 5, 'EL789N', 60.00, 60.00, 72.00, 360.00, 1440.00, 'rented', 'assets/images/cars/1745341999_2023 Nissan Versa SR review_ Baby bargain - Hagerty Media.jpeg', '', '2025-04-22 17:13:19'),
(10, NULL, 'Ferrari', 'C-Class', 2025, 21, NULL, NULL, 2, 'EL789YX', 5.00, 5.00, 6.00, 30.00, 120.00, 'available', 'assets/images/cars/1747135985_download (13).jpeg', 'fgvbrgbvr', '2025-05-13 11:33:05');

-- --------------------------------------------------------

--
-- Table structure for table `car_categories`
--

CREATE TABLE `car_categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_categories`
--

INSERT INTO `car_categories` (`category_id`, `name`, `description`, `created_at`) VALUES
(1, 'Economy', 'Affordable, fuel-efficient cars for budget-conscious travelers', '2025-04-19 10:16:51'),
(2, 'Compact', 'Small cars ideal for city driving and easy parking', '2025-04-19 10:16:51'),
(3, 'Midsize', 'Comfortable cars with more space than compact models', '2025-04-19 10:16:51'),
(4, 'SUV', 'Spacious vehicles with higher ground clearance', '2025-04-19 10:16:51'),
(5, 'Luxury', 'Premium vehicles with high-end features and comfort', '2025-04-19 10:16:51');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_records`
--

CREATE TABLE `maintenance_records` (
  `maintenance_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `maintenance_type` enum('routine','repair','inspection') NOT NULL,
  `description` text NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed') NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_records`
--

INSERT INTO `maintenance_records` (`maintenance_id`, `car_id`, `maintenance_type`, `description`, `cost`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 1, 'routine', 'Regular oil change and inspection', 75.00, '2025-02-15', '2025-02-15', 'completed', '2025-04-19 10:16:51'),
(2, 2, 'repair', 'Brake pad replacement', 150.00, '2025-03-10', '2025-03-11', 'completed', '2025-04-19 10:16:51'),
(3, 3, 'inspection', 'Annual vehicle inspection', 50.00, '2025-04-20', NULL, 'scheduled', '2025-04-19 10:16:51'),
(6, 3, 'repair', 'repairing of wheel', 10.00, '2025-05-10', '2025-05-15', 'in_progress', '2025-05-13 11:35:44');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `type` enum('rental','payment','system','promotion') NOT NULL,
  `related_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `title`, `message`, `type`, `related_id`, `is_read`, `created_at`) VALUES
(1, 1, 'Booking Confirmation', 'Your booking for Toyota Camry has been confirmed.', 'rental', 1, 0, '2025-04-19 10:16:51'),
(2, 1, 'Payment Received', 'We have received your payment of $250.00', 'payment', 1, 0, '2025-04-19 10:16:51'),
(3, 2, 'Weekend Special Offer', 'Get 25% off on all rentals this weekend!', 'promotion', NULL, 0, '2025-04-19 10:16:51'),
(4, 3, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 7', 'rental', 7, 0, '2025-04-22 17:56:20'),
(5, 3, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 8', 'rental', 8, 0, '2025-04-22 17:56:48'),
(6, 3, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 9', 'rental', 9, 0, '2025-04-22 17:57:17'),
(7, 3, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 10', 'rental', 10, 0, '2025-04-25 20:22:09'),
(8, 3, 'Rental Cancelled', 'Your rental booking has been cancelled. Rental ID: 10', 'rental', 10, 0, '2025-04-25 20:22:19'),
(9, 4, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 11', 'rental', 11, 0, '2025-04-26 15:50:32'),
(10, 7, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 12', 'rental', 12, 0, '2025-04-26 16:24:28'),
(11, 7, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 13', 'rental', 13, 0, '2025-04-27 23:12:27'),
(12, 7, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 14', 'rental', 14, 0, '2025-05-13 10:19:26'),
(13, 7, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 15', 'rental', 15, 0, '2025-05-13 10:19:26'),
(14, 7, 'Rental Cancelled', 'Your rental booking has been cancelled. Rental ID: 15', 'rental', 15, 0, '2025-05-13 10:19:36'),
(15, 7, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 16', 'rental', 16, 0, '2025-05-13 10:19:51'),
(16, 9, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 17', 'rental', 17, 0, '2025-05-13 10:27:17'),
(17, 12, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 18', 'rental', 18, 0, '2025-05-13 10:55:31'),
(18, 12, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 19', 'rental', 19, 0, '2025-05-13 11:19:24'),
(19, 12, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 20', 'rental', 20, 0, '2025-06-05 12:56:47'),
(20, 13, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 21', 'rental', 21, 0, '2025-06-12 09:45:06'),
(21, 13, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 22', 'rental', 22, 0, '2025-06-12 11:08:38'),
(22, 13, 'Rental Extended', 'Your rental has been successfully extended. New end date: Jun 26, 2025', 'rental', 22, 0, '2025-06-12 11:10:55'),
(23, 13, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 23', 'rental', 23, 0, '2025-06-12 11:14:53'),
(24, 13, 'Rental Returned', 'Your rental has been successfully returned. Rental ID: 22', 'rental', 22, 0, '2025-06-12 11:58:12'),
(25, 13, 'Rental Confirmation', 'Your rental booking has been confirmed. Rental ID: 24', 'rental', 24, 0, '2025-06-12 13:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `rental_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('credit_card','debit_card','stripe','bank_transfer') NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `rental_id`, `amount`, `payment_method`, `transaction_id`, `status`, `payment_date`) VALUES
(1, 1, 250.00, 'credit_card', 'TXN123456789', 'completed', '2025-04-19 10:16:51'),
(2, 2, 225.00, '', 'PAYPAL987654321', 'completed', '2025-04-19 10:16:51'),
(3, 3, 400.00, 'debit_card', 'TXN567891234', 'pending', '2025-04-19 10:16:51'),
(4, 21, 114.75, 'stripe', 'ch_3RZ8mHB791T8Zvw81d9axl0u', 'completed', '2025-06-12 10:54:39'),
(5, 21, 114.75, 'stripe', 'ch_3RZ8sOB791T8Zvw8148kf3GD', 'completed', '2025-06-12 11:00:58'),
(6, 22, 135.00, 'stripe', 'ch_3RZ91bB791T8Zvw81jD8LNgZ', 'completed', '2025-06-12 11:10:29'),
(7, 23, 13.50, 'stripe', 'ch_3RZ99HB791T8Zvw80GirZmQs', 'completed', '2025-06-12 11:18:24'),
(8, 24, 360.00, 'stripe', 'ch_3RZBBuB791T8Zvw80CBFXrC4', 'completed', '2025-06-12 13:29:16'),
(9, 24, 360.00, 'stripe', 'ch_3RZBBwB791T8Zvw80E5QkRqK', 'completed', '2025-06-12 13:29:17');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `promotion_id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_percentage` decimal(5,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`promotion_id`, `code`, `description`, `discount_percentage`, `discount_amount`, `start_date`, `end_date`, `is_active`, `created_at`) VALUES
(1, 'SUMMER2025', 'Summer special discount', 15.00, 25.00, '2025-06-01', '2025-08-31', 1, '2025-04-19 10:16:51'),
(2, 'WELCOME10', 'New user discount', 10.00, NULL, '2025-01-01', '2025-12-31', 1, '2025-04-19 10:16:51'),
(3, 'WEEKEND25', 'Weekend special offer', 25.00, NULL, '2025-01-01', '2025-12-31', 1, '2025-04-19 10:16:51'),
(9, 'weekend vibes', 'enjoy vibes with us', 20.00, 12.00, '2025-05-12', '2025-05-30', 1, '2025-05-13 11:25:54');

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `rental_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `pickup_location` varchar(100) DEFAULT NULL,
  `return_location` varchar(100) DEFAULT NULL,
  `promotion_id` int(11) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `additional_charges` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','active','completed','cancelled') DEFAULT 'pending',
  `total_cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`rental_id`, `user_id`, `car_id`, `start_date`, `end_date`, `pickup_location`, `return_location`, `promotion_id`, `discount_amount`, `additional_charges`, `notes`, `status`, `total_cost`, `created_at`) VALUES
(1, 1, 1, '2025-03-19', '2025-03-12', NULL, NULL, NULL, 0.00, 0.00, NULL, 'approved', -350.00, '2025-03-09 00:50:11'),
(2, 1, 2, '2025-03-12', '2025-03-17', NULL, NULL, NULL, 0.00, 0.00, NULL, 'pending', 225.00, '2025-03-09 00:50:26'),
(3, 1, 3, '2025-03-11', '2025-03-16', NULL, NULL, NULL, 0.00, 0.00, NULL, 'pending', 400.00, '2025-03-09 00:53:16'),
(4, 1, 1, '2025-03-20', '2025-03-25', NULL, NULL, NULL, 0.00, 0.00, NULL, 'approved', 250.00, '2025-03-09 00:59:30'),
(5, 1, 2, '2025-03-19', '2025-03-23', NULL, NULL, NULL, 0.00, 0.00, NULL, 'pending', 180.00, '2025-03-09 21:12:43'),
(6, 2, 1, '2025-04-03', '2025-04-05', NULL, NULL, NULL, 0.00, 0.00, NULL, 'approved', 100.00, '2025-04-01 10:57:22'),
(7, 3, 1, '2025-04-24', '2025-04-30', NULL, NULL, 3, 0.00, 0.00, NULL, 'pending', 262.50, '2025-04-22 17:56:20'),
(8, 3, 6, '2025-04-24', '2025-04-29', NULL, NULL, 3, 0.00, 0.00, NULL, 'completed', 270.00, '2025-04-22 17:56:48'),
(9, 3, 2, '2025-04-25', '2025-04-30', NULL, NULL, 2, 0.00, 0.00, NULL, 'pending', 243.00, '2025-04-22 17:57:17'),
(10, 3, 2, '2025-04-26', '2025-04-30', NULL, NULL, NULL, 0.00, 0.00, NULL, 'cancelled', 225.00, '2025-04-25 20:22:09'),
(11, 4, 2, '2025-04-27', '2025-04-29', NULL, NULL, NULL, 0.00, 0.00, NULL, 'pending', 135.00, '2025-04-26 15:50:32'),
(12, 7, 2, '2025-04-28', '2025-04-29', NULL, NULL, 2, 0.00, 0.00, NULL, 'approved', 81.00, '2025-04-26 16:24:28'),
(13, 7, 2, '2025-04-29', '2025-04-30', NULL, NULL, NULL, 0.00, 0.00, NULL, 'approved', 90.00, '2025-04-27 23:12:27'),
(14, 7, 6, '2025-05-14', '2025-05-20', NULL, NULL, 3, 0.00, 0.00, NULL, 'approved', 315.00, '2025-05-13 10:19:26'),
(15, 7, 6, '2025-05-14', '2025-05-20', NULL, NULL, 3, 0.00, 0.00, NULL, 'cancelled', 315.00, '2025-05-13 10:19:26'),
(16, 7, 2, '2025-05-14', '2025-05-20', NULL, NULL, 3, 0.00, 0.00, NULL, 'completed', 236.25, '2025-05-13 10:19:50'),
(17, 9, 4, '2025-05-15', '2025-05-20', NULL, NULL, 2, 0.00, 0.00, NULL, 'cancelled', 540.00, '2025-05-13 10:27:17'),
(18, 12, 4, '2025-05-15', '2025-05-20', NULL, NULL, 3, 0.00, 0.00, NULL, 'approved', 450.00, '2025-05-13 10:55:31'),
(19, 12, 3, '2025-05-15', '2025-05-30', NULL, NULL, 3, 0.00, 0.00, NULL, 'approved', 960.00, '2025-05-13 11:19:24'),
(20, 12, 4, '2025-06-06', '2025-06-20', NULL, NULL, 1, 0.00, 0.00, NULL, 'approved', 1275.00, '2025-06-05 12:56:47'),
(21, 13, 2, '2025-06-14', '2025-06-16', NULL, NULL, 1, 0.00, 0.00, NULL, 'active', 114.75, '2025-06-12 09:45:06'),
(22, 13, 2, '2025-06-18', '2025-06-26', NULL, NULL, 3, 0.00, 0.00, '\nExtended from 2025-06-26 to 2025-06-26. Reason: dsjxkc,bmn fvcxk ,.bmn fvck,.m vc', 'completed', 405.00, '2025-06-12 11:08:38'),
(23, 13, 10, '2025-06-28', '2025-06-30', NULL, NULL, 2, 0.00, 0.00, NULL, 'active', 13.50, '2025-06-12 11:14:53'),
(24, 13, 4, '2025-07-05', '2025-07-08', NULL, NULL, 2, 0.00, 0.00, NULL, 'active', 360.00, '2025-06-12 13:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `rental_history`
--

CREATE TABLE `rental_history` (
  `history_id` int(11) NOT NULL,
  `rental_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `return_date` datetime DEFAULT NULL,
  `return_condition` text DEFAULT NULL,
  `additional_charges` decimal(10,2) DEFAULT 0.00,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_history`
--

INSERT INTO `rental_history` (`history_id`, `rental_id`, `user_id`, `car_id`, `return_date`, `return_condition`, `additional_charges`, `rating`, `feedback`, `created_at`) VALUES
(1, 8, 3, 6, '2025-04-26 18:32:24', NULL, 0.00, NULL, NULL, '2025-04-26 15:32:24'),
(2, 16, 7, 2, '2025-05-13 12:43:02', NULL, 0.00, NULL, NULL, '2025-05-13 10:43:02'),
(3, 22, 13, 2, '2025-06-12 13:58:12', 'skdjkjsdks', 0.00, 4, 'the car was too speedy', '2025-06-12 12:33:09');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`log_id`, `user_id`, `action`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'login', 'User logged in successfully', '192.168.1.1', NULL, '2025-04-19 10:16:51'),
(2, 1, 'rental_created', 'Created rental ID: 1', '192.168.1.1', NULL, '2025-04-19 10:16:51'),
(3, 2, 'login', 'User logged in successfully', '192.168.1.2', NULL, '2025-04-19 10:16:51'),
(4, 3, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-22 16:36:53'),
(5, 3, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-22 16:38:03'),
(6, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-22 16:42:19'),
(7, 4, 'update_car', 'Updated car ID: 3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-22 16:48:54'),
(8, 4, 'update_car', 'Updated car ID: 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-22 16:50:07'),
(9, 4, 'update_car', 'Updated car ID: 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-22 16:51:41'),
(10, 4, 'add_car', 'Added new car: BMW X5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-22 17:00:37'),
(11, 4, 'add_car', 'Added new car: Mercedes-Benz C-Class', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-22 17:05:23'),
(12, 4, 'add_car', 'Added new car: Nissan Leaf', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-22 17:13:19'),
(13, 3, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-22 17:17:43'),
(14, 3, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-22 17:18:30'),
(15, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-22 17:58:02'),
(16, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-26 15:25:33'),
(17, 4, 'update_user', 'Updated user ID: 4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-26 15:29:15'),
(18, 4, 'update_car', 'Updated car ID: 6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-26 15:54:02'),
(19, 4, 'update_car', 'Updated car ID: 6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-26 15:54:08'),
(20, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-26 16:23:07'),
(21, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-27 22:15:48'),
(22, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-27 22:18:04'),
(23, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0', '2025-04-27 22:24:38'),
(24, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '2025-04-27 22:27:27'),
(25, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:07:31'),
(26, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:10:43'),
(27, 4, 'update_car', 'Updated car ID: 6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:14:16'),
(28, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:15:53'),
(29, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:22:33'),
(30, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:26:50'),
(31, 11, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:28:16'),
(32, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:32:42'),
(33, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:42:36'),
(34, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:44:43'),
(35, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:45:51'),
(36, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:46:18'),
(37, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:46:43'),
(38, 12, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:53:43'),
(39, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:56:59'),
(40, 4, 'update_car', 'Updated car ID: 6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 10:59:11'),
(41, 12, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 11:11:35'),
(42, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 11:12:28'),
(43, 12, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 11:16:25'),
(44, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 11:21:18'),
(45, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 11:21:18'),
(46, 4, 'add_car', 'Added new car: Ferrari C-Class', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 11:31:10'),
(47, 4, 'delete_car', 'Deleted car ID: 9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 11:32:32'),
(48, 4, 'add_car', 'Added new car: Ferrari C-Class', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 11:33:05'),
(49, 12, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 11:50:29'),
(50, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-13 11:52:20'),
(51, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-14 12:02:19'),
(52, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-14 12:07:19'),
(53, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-14 12:10:05'),
(54, 12, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-14 12:15:01'),
(55, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-14 12:16:02'),
(56, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-05-14 12:22:47'),
(57, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-06-05 12:37:16'),
(58, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-06-05 12:53:00'),
(59, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-06-05 12:53:56'),
(60, 12, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-06-05 12:55:26'),
(61, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', '2025-06-05 12:57:07'),
(62, 13, 'login', 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-12 09:44:44'),
(63, 4, 'login', 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-12 09:46:14'),
(64, 4, 'login', 'User logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-12 13:25:31'),
(65, 4, 'update_car', 'Updated car ID: 3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-06-12 13:34:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `driver_license` varchar(50) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','customer') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `phone`, `address`, `driver_license`, `profile_image`, `full_name`, `role`, `created_at`, `last_login`, `status`) VALUES
(1, 'abayo', '$2y$10$UPdMTF3rDhqJGFihMwrdWOS6W4e3KXzajb9qJt/gnJukX7CDo6ns.', 'abayo@gmail.com', NULL, NULL, NULL, NULL, 'Abirebeye Abayo Sincere Aime Margot', 'customer', '2025-03-09 00:39:19', NULL, 'active'),
(2, 'john', '$2y$10$/gl5iTg.5PCkIVP3PicIrOuQ6XgjLwXZ0i8zbYwbnQ6UNmiqvRIzq', 'john@gmail.com', NULL, NULL, NULL, NULL, 'John Doe', 'customer', '2025-04-01 10:56:22', NULL, 'active'),
(3, 'divine@gmail.com', '$2y$10$j4FYZH.uYrpYY2X5BfyN1eE6h0dltEorowZsw1p0L9fOPI7ikMthy', 'divine@gmail.com', NULL, NULL, NULL, NULL, 'Divine', 'customer', '2025-04-22 16:36:38', '2025-04-22 17:18:29', 'active'),
(4, 'admin', '$2y$10$n3brMf9dRy9kk33GET2Bteh0vd2byl45/ton/BcaBsJ/KpLkfBoBC', 'admin@gmail.com', '0783318208', 'Gasabo', 'AD', 'assets/images/profiles/profile_4_1747137234.jpeg', 'Mutimucyeye Divine', 'admin', '2025-04-22 16:41:00', '2025-06-12 13:25:31', 'active'),
(7, 'imbazazii', '$2y$10$KA4EhLb6GL0FQ8YM4JdgZ.GvwR6af4ufgf1CR0d27mOxDCijfa.9a', 'imbabazii@gmail.com', '0791606530', 'KG St 105', 'AD', 'assets/images/profiles/profile_7_1745684756.jpeg', 'imbabazi', 'customer', '2025-04-26 16:22:53', '2025-06-05 12:53:00', 'active'),
(8, 'Imbabazi', '$2y$10$wjAh70h61qtf4gHrjMnjKuKPK6UcrKRlrEum9UDlnCHXgq36pbUnC', 'niwashikilvan@gmail.com', NULL, NULL, NULL, NULL, 'IMBABAZI Nilvan', 'customer', '2025-05-13 09:58:53', NULL, 'active'),
(9, 'Kenny', '$2y$10$zKaV7jBMwYqCtrWdD1U1Mu7TMbM7sjyE6j.vmzVxaDvXsNgO7KiC2', 'nziza@gmail.com', NULL, NULL, NULL, NULL, 'Nziza Kenny', 'customer', '2025-05-13 10:06:58', '2025-05-13 10:44:43', 'active'),
(11, 'Mutima', '$2y$10$JNHeEsl4EafS5iv1JXs4Qe0k8GAwZjrQF5mg5gUA6dS9MiWPkCzYe', 'mutimukeyeflorence120@gmail.com', NULL, NULL, NULL, NULL, 'Mutimukeye Florence', 'customer', '2025-05-13 10:28:02', '2025-05-13 10:28:16', 'active'),
(12, 'Ange', '$2y$10$y4Q5zF1mzSX5zEVfUexoX.hw8JHpjdNuTffbjUseS41yRLu061Qg2', 'ange@gmail.com', '0798696567', 'KG St 105', 'AB', 'assets/images/profiles/profile_12_1747133788.jpg', 'Muhawenimana Ange', 'customer', '2025-05-13 10:53:26', '2025-06-05 12:55:26', 'active'),
(13, 'ishimwe', '$2y$10$hspKusvuch5Tx4Pbsim0YOBe0L2TZm527fadE42HXg1.JWCR9Z8R6', 'ishimwe@gmail.com', NULL, NULL, NULL, NULL, 'ishimwe', 'customer', '2025-06-12 09:44:25', '2025-06-12 09:44:44', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

CREATE TABLE `user_favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_favorites`
--

INSERT INTO `user_favorites` (`favorite_id`, `user_id`, `car_id`, `created_at`) VALUES
(1, 1, 3, '2025-04-19 10:16:51'),
(2, 2, 1, '2025-04-19 10:16:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `preference_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `preferred_car_type` varchar(50) DEFAULT NULL,
  `preferred_features` text DEFAULT NULL,
  `price_range_min` decimal(10,2) DEFAULT NULL,
  `price_range_max` decimal(10,2) DEFAULT NULL,
  `notification_preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notification_preferences`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_preferences`
--

INSERT INTO `user_preferences` (`preference_id`, `user_id`, `preferred_car_type`, `preferred_features`, `price_range_min`, `price_range_max`, `notification_preferences`, `created_at`, `updated_at`) VALUES
(1, 1, 'SUV', 'GPS, Bluetooth, Backup Camera', 40.00, 100.00, '{\"email\": true, \"sms\": false, \"promotions\": true}', '2025-04-19 10:16:51', NULL),
(2, 2, 'Economy', 'Fuel Efficient, Automatic', 30.00, 60.00, '{\"email\": true, \"sms\": true, \"promotions\": true}', '2025-04-19 10:16:51', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`car_id`),
  ADD UNIQUE KEY `registration_number` (`registration_number`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `car_categories`
--
ALTER TABLE `car_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `maintenance_records`
--
ALTER TABLE `maintenance_records`
  ADD PRIMARY KEY (`maintenance_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `rental_id` (`rental_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`promotion_id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `promotion_id` (`promotion_id`);

--
-- Indexes for table `rental_history`
--
ALTER TABLE `rental_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `rental_id` (`rental_id`);

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
-- Indexes for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `user_car_unique` (`user_id`,`car_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`preference_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `car_categories`
--
ALTER TABLE `car_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `maintenance_records`
--
ALTER TABLE `maintenance_records`
  MODIFY `maintenance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `promotion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `rental_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `rental_history`
--
ALTER TABLE `rental_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `car_categories` (`category_id`);

--
-- Constraints for table `maintenance_records`
--
ALTER TABLE `maintenance_records`
  ADD CONSTRAINT `maintenance_records_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`rental_id`);

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`),
  ADD CONSTRAINT `rentals_ibfk_3` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`promotion_id`);

--
-- Constraints for table `rental_history`
--
ALTER TABLE `rental_history`
  ADD CONSTRAINT `rental_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `rental_history_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`),
  ADD CONSTRAINT `rental_history_ibfk_3` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`rental_id`);

--
-- Constraints for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD CONSTRAINT `user_favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_favorites_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`);

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
