-- CREATE DATABASE car_rental_system;
USE car_rental_system;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'customer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cars table
CREATE TABLE IF NOT EXISTS cars (
    car_id INT PRIMARY KEY AUTO_INCREMENT,
    make VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    registration_number VARCHAR(20) UNIQUE NOT NULL,
    daily_rate DECIMAL(10,2) NOT NULL,
    status ENUM('available', 'rented', 'maintenance') DEFAULT 'available',
    image_url VARCHAR(255),
    features TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rentals table
CREATE TABLE IF NOT EXISTS rentals (
    rental_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('pending', 'approved', 'active', 'completed', 'cancelled') DEFAULT 'pending',
    total_cost DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (car_id) REFERENCES cars(car_id)
);

-- Rental History for AI recommendations
CREATE TABLE IF NOT EXISTS rental_history (
    history_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (car_id) REFERENCES cars(car_id)
);

-- new tables 
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `rental_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('credit_card','debit_card','paypal','bank_transfer') NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`payment_id`),
  KEY `rental_id` (`rental_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`rental_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `promotions` (
  `promotion_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_percentage` decimal(5,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`promotion_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

CREATE TABLE IF NOT EXISTS `maintenance_records` (
  `maintenance_id` int(11) NOT NULL AUTO_INCREMENT,
  `car_id` int(11) NOT NULL,
  `maintenance_type` enum('routine','repair','inspection') NOT NULL,
  `description` text NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed') NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`maintenance_id`),
  KEY `car_id` (`car_id`),
  CONSTRAINT `maintenance_records_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

CREATE TABLE IF NOT EXISTS `user_preferences` (
  `preference_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `preferred_car_type` varchar(50) DEFAULT NULL,
  `preferred_features` text DEFAULT NULL,
  `price_range_min` decimal(10,2) DEFAULT NULL,
  `price_range_max` decimal(10,2) DEFAULT NULL,
  `notification_preferences` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`preference_id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `type` enum('rental','payment','system','promotion') NOT NULL,
  `related_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`notification_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

CREATE TABLE IF NOT EXISTS `car_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

CREATE TABLE IF NOT EXISTS `user_favorites` (
  `favorite_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`favorite_id`),
  UNIQUE KEY `user_car_unique` (`user_id`,`car_id`),
  KEY `car_id` (`car_id`),
  CONSTRAINT `user_favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `user_favorites_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

CREATE TABLE IF NOT EXISTS `system_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

ALTER TABLE `cars` 
ADD COLUMN `category_id` int(11) DEFAULT NULL AFTER `car_id`,
ADD COLUMN `mileage` int(11) DEFAULT NULL AFTER `year`,
ADD COLUMN `fuel_type` enum('petrol','diesel','electric','hybrid') DEFAULT NULL AFTER `mileage`,
ADD COLUMN `transmission` enum('manual','automatic') DEFAULT NULL AFTER `fuel_type`,
ADD COLUMN `seats` int(11) DEFAULT NULL AFTER `transmission`,
ADD COLUMN `base_rate` decimal(10,2) NOT NULL AFTER `daily_rate`,
ADD COLUMN `weekend_rate` decimal(10,2) DEFAULT NULL AFTER `base_rate`,
ADD COLUMN `weekly_rate` decimal(10,2) DEFAULT NULL AFTER `weekend_rate`,
ADD COLUMN `monthly_rate` decimal(10,2) DEFAULT NULL AFTER `weekly_rate`,
ADD KEY `category_id` (`category_id`),
ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `car_categories` (`category_id`);


ALTER TABLE `rentals` 
ADD COLUMN `pickup_location` varchar(100) DEFAULT NULL AFTER `end_date`,
ADD COLUMN `return_location` varchar(100) DEFAULT NULL AFTER `pickup_location`,
ADD COLUMN `promotion_id` int(11) DEFAULT NULL AFTER `return_location`,
ADD COLUMN `discount_amount` decimal(10,2) DEFAULT 0.00 AFTER `promotion_id`,
ADD COLUMN `additional_charges` decimal(10,2) DEFAULT 0.00 AFTER `discount_amount`,
ADD COLUMN `notes` text DEFAULT NULL AFTER `additional_charges`,
ADD KEY `promotion_id` (`promotion_id`),
ADD CONSTRAINT `rentals_ibfk_3` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`promotion_id`);

ALTER TABLE `users` 
ADD COLUMN `phone` varchar(20) DEFAULT NULL AFTER `email`,
ADD COLUMN `address` text DEFAULT NULL AFTER `phone`,
ADD COLUMN `driver_license` varchar(50) DEFAULT NULL AFTER `address`,
ADD COLUMN `profile_image` varchar(255) DEFAULT NULL AFTER `driver_license`,
ADD COLUMN `last_login` timestamp NULL DEFAULT NULL AFTER `created_at`,
ADD COLUMN `status` enum('active','inactive','suspended') DEFAULT 'active' AFTER `last_login`;

ALTER TABLE `rental_history` 
ADD COLUMN `rental_id` int(11) NOT NULL AFTER `history_id`,
ADD COLUMN `return_date` datetime DEFAULT NULL AFTER `car_id`,
ADD COLUMN `return_condition` text DEFAULT NULL AFTER `return_date`,
ADD COLUMN `additional_charges` decimal(10,2) DEFAULT 0.00 AFTER `return_condition`,
ADD KEY `rental_id` (`rental_id`),
ADD CONSTRAINT `rental_history_ibfk_3` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`rental_id`);

-- Sample data for car_categories
INSERT INTO `car_categories` (`name`, `description`) VALUES
('Economy', 'Affordable, fuel-efficient cars for budget-conscious travelers'),
('Compact', 'Small cars ideal for city driving and easy parking'),
('Midsize', 'Comfortable cars with more space than compact models'),
('SUV', 'Spacious vehicles with higher ground clearance'),
('Luxury', 'Premium vehicles with high-end features and comfort');

-- Update existing cars with categories
UPDATE `cars` SET `category_id` = 3, `fuel_type` = 'petrol', `transmission` = 'automatic', `seats` = 5, `base_rate` = 50.00, `weekend_rate` = 60.00, `weekly_rate` = 300.00, `monthly_rate` = 1200.00 WHERE `car_id` = 1;
UPDATE `cars` SET `category_id` = 2, `fuel_type` = 'petrol', `transmission` = 'automatic', `seats` = 5, `base_rate` = 45.00, `weekend_rate` = 55.00, `weekly_rate` = 270.00, `monthly_rate` = 1080.00 WHERE `car_id` = 2;
UPDATE `cars` SET `category_id` = 5, `fuel_type` = 'electric', `transmission` = 'automatic', `seats` = 5, `base_rate` = 80.00, `weekend_rate` = 95.00, `weekly_rate` = 480.00, `monthly_rate` = 1920.00 WHERE `car_id` = 3;

-- Sample data for promotions (completing the truncated statement)
INSERT INTO `promotions` (`code`, `description`, `discount_percentage`, `discount_amount`, `start_date`, `end_date`, `is_active`) VALUES
('SUMMER2025', 'Summer special discount', 15.00, NULL, '2025-06-01', '2025-08-31', 1),
('WELCOME10', 'New user discount', 10.00, NULL, '2025-01-01', '2025-12-31', 1),
('WEEKEND25', 'Weekend special offer', 25.00, NULL, '2025-01-01', '2025-12-31', 1),
('HOLIDAY50', 'Holiday season discount', 50.00, NULL, '2025-12-15', '2026-01-05', 1),
('FLAT20OFF', 'Flat discount on weekly rentals', NULL, 20.00, '2025-05-01', '2025-11-30', 1);

-- Additional sample data for new tables
INSERT INTO `maintenance_records` (`car_id`, `maintenance_type`, `description`, `cost`, `start_date`, `end_date`, `status`) VALUES
(1, 'routine', 'Regular oil change and inspection', 75.00, '2025-02-15', '2025-02-15', 'completed'),
(2, 'repair', 'Brake pad replacement', 150.00, '2025-03-10', '2025-03-11', 'completed'),
(3, 'inspection', 'Annual vehicle inspection', 50.00, '2025-04-20', NULL, 'scheduled');

INSERT INTO `user_preferences` (`user_id`, `preferred_car_type`, `preferred_features`, `price_range_min`, `price_range_max`, `notification_preferences`) VALUES
(1, 'SUV', 'GPS, Bluetooth, Backup Camera', 40.00, 100.00, '{"email": true, "sms": false, "promotions": true}'),
(2, 'Economy', 'Fuel Efficient, Automatic', 30.00, 60.00, '{"email": true, "sms": true, "promotions": true}');

INSERT INTO `notifications` (`user_id`, `title`, `message`, `type`, `related_id`, `is_read`) VALUES
(1, 'Booking Confirmation', 'Your booking for Toyota Camry has been confirmed.', 'rental', 1, 0),
(1, 'Payment Received', 'We have received your payment of $250.00', 'payment', 1, 0),
(2, 'Weekend Special Offer', 'Get 25% off on all rentals this weekend!', 'promotion', NULL, 0);

INSERT INTO `user_favorites` (`user_id`, `car_id`) VALUES
(1, 3),
(2, 1);

INSERT INTO `payments` (`rental_id`, `amount`, `payment_method`, `transaction_id`, `status`) VALUES
(1, 250.00, 'credit_card', 'TXN123456789', 'completed'),
(2, 225.00, 'paypal', 'PAYPAL987654321', 'completed'),
(3, 400.00, 'debit_card', 'TXN567891234', 'pending');

INSERT INTO `system_logs` (`user_id`, `action`, `details`, `ip_address`) VALUES
(1, 'login', 'User logged in successfully', '192.168.1.1'),
(1, 'rental_created', 'Created rental ID: 1', '192.168.1.1'),
(2, 'login', 'User logged in successfully', '192.168.1.2');


/* improved query */
CREATE TABLE faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    keywords VARCHAR(255),
    active TINYINT(1) DEFAULT 1
);
