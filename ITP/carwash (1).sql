-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 11:54 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carwash`
--

-- --------------------------------------------------------

--
-- Table structure for table `addressbook`
--

CREATE TABLE `addressbook` (
  `address_id` int(11) NOT NULL,
  `user_id` int(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addressbook`
--

INSERT INTO `addressbook` (`address_id`, `user_id`, `address`, `city`, `postal_code`, `country`) VALUES
(4, 0, '16, IS 21', 'KUANTAN', '25150', 'Malaysia'),
(5, 0, '16, IS 21 taman IS', 'KUANTAN', '25150', 'Malaysia'),
(7, 9, '20 Taman tas', 'Kuantan', '25150', 'Pahang'),
(8, 9, '16, IS 21 taman IS', 'KUANTAN', '25150', 'Malaysia'),
(9, 1, '16, Jalan  senang 3,', 'PARIT RAJA', '86400', 'Malaysia');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `service_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending',
  `customer_request` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `car_id`, `date`, `time`, `service_id`, `created_at`, `status`, `customer_request`) VALUES
(28, 9, 2, '2024-10-03', '10:00:00', 3, '2024-10-20 14:16:40', 'Pending', 'test'),
(29, 9, 2, '2024-10-03', '10:00:00', 3, '2024-10-20 14:19:40', 'Pending', 'test'),
(31, 9, 2, '2024-10-03', '10:00:00', 3, '2024-10-20 14:20:39', 'Pending', 'test'),
(32, 9, 1, '2024-10-22', '17:00:00', 4, '2024-10-21 02:44:32', 'Approved', 'wax'),
(33, 1, 6, '2024-10-22', '17:30:00', 3, '2024-10-21 03:06:42', 'Approved', 'book1');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `car_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `color` varchar(30) DEFAULT NULL,
  `license_plate` varchar(10) DEFAULT NULL,
  `car_photo` blob DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`car_id`, `user_id`, `make`, `model`, `year`, `color`, `license_plate`, `car_photo`, `created_at`) VALUES
(1, 9, 'perodua', 'viva', 2009, 'white', 'qaq3193', 0x75706c6f6164732f7061727432286365292e706e67, '2024-10-01 14:29:39'),
(2, 9, 'perodua', 'viva', 2009, 'white', 'jnj7607', 0x75706c6f6164732f70617274312e706e67, '2024-10-01 14:39:29'),
(3, 9, 'peroton', 'waja', 2006, 'silver', 'jgl7607', 0x75706c6f6164732f486f6e64612d4c6f676f2d566563746f722d3230343878323034382e6a7067, '2024-10-03 07:48:33'),
(4, 9, 'perodua', 'aruz', 2014, 'maroon', 'wou7608', 0x75706c6f6164732f424d572e6a706567, '2024-10-03 08:05:37'),
(5, 9, 'perodua', 'aruz', 2014, 'maroon', 'wou7608', 0x75706c6f6164732f424d572e6a706567, '2024-10-04 17:49:29'),
(6, 1, 'BMW', 'BMW', 2020, 'SILVER', 'JSU337', 0x75706c6f6164732f424d572e6a706567, '2024-10-10 07:59:10'),
(7, 1, 'perodua', 'viva', 2009, 'white', 'qaq3193', 0x75706c6f6164732f7065726f6475612d766976612d323031342e6a7067, '2024-10-15 06:24:08'),
(8, 9, 'lambo', 'svk', 2024, 'white', 'wer123', 0x75706c6f6164732f4c522e6a7067, '2024-10-21 02:28:30'),
(9, 9, 'lambo', 'svk', 2024, 'white', 'asd223', 0x75706c6f6164732f4d7573742e6a706567, '2024-10-21 02:39:47'),
(10, 9, 'peroton', 'aruz', 2022, 'SILVER', 'abc123', 0x75706c6f6164732f4c522e6a7067, '2024-10-21 02:41:48'),
(11, 9, 'BMW', '350i', 2014, 'silver', 'def123', 0x75706c6f6164732f4d7573742e6a706567, '2024-10-21 03:04:40');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `product_id`, `user_id`, `quantity`, `price`, `created_at`) VALUES
(34, 12, 1, 1, 20.00, '2024-10-20 15:31:28'),
(35, 11, 9, 1, 10.00, '2024-10-21 02:45:31'),
(36, 15, 1, 1, 12.50, '2024-10-21 03:08:03');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `address_id`, `payment_method`, `total`, `order_date`, `status`) VALUES
(1, 9, 7, 'credit_card', 60.00, '2024-10-05 12:12:57', 'Processing'),
(2, 9, 7, 'online', 20.00, '2024-10-13 13:20:01', 'Pending'),
(3, 9, 7, 'online', 35.00, '2024-10-13 14:04:27', 'Pending'),
(4, 1, 9, 'online', 75.00, '2024-10-15 13:40:42', 'Pending'),
(5, 1, 9, 'online', 20.00, '2024-10-15 13:50:13', 'Pending'),
(6, 9, 7, 'fpx', 10.00, '2024-10-16 15:56:36', 'pending'),
(7, 9, 7, 'fpx', 15.90, '2024-10-16 16:05:28', 'pending'),
(8, 1, 9, 'fpx', 20.00, '2024-10-16 16:11:27', 'pending'),
(9, 1, 9, 'fpx', 20.00, '2024-10-16 16:15:41', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 7, 1, 15.00),
(2, 1, 7, 1, 15.00),
(3, 1, 7, 1, 15.00),
(4, 1, 7, 1, 15.00),
(5, 2, 6, 2, 10.00),
(6, 3, 8, 1, 15.00),
(7, 3, 6, 1, 10.00),
(8, 3, 6, 1, 10.00),
(9, 4, 6, 1, 10.00),
(10, 4, 6, 1, 10.00),
(11, 4, 6, 1, 10.00),
(12, 4, 6, 1, 10.00),
(13, 4, 8, 1, 15.00),
(14, 4, 12, 1, 20.00),
(15, 5, 12, 1, 20.00),
(16, 6, 11, 1, 10.00),
(17, 7, 14, 1, 15.90),
(18, 8, 12, 1, 20.00),
(19, 9, 12, 1, 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(100) NOT NULL,
  `service_id` int(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `bank_selection` varchar(50) DEFAULT NULL,
  `payment_status` enum('Pending','Completed','Failed') DEFAULT 'Pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `booking_id`, `user_id`, `car_id`, `service_id`, `amount`, `payment_method`, `bank_selection`, `payment_status`, `transaction_id`, `created_at`, `updated_at`) VALUES
(1, 0, 9, 1, 3, 10.00, 'fpx', 'maybank', 'Pending', NULL, '2024-10-15 22:49:08', '2024-10-15 22:49:08'),
(2, 0, 9, 1, 3, 10.00, 'fpx', 'maybank', 'Pending', NULL, '2024-10-15 22:53:30', '2024-10-15 22:53:30'),
(3, 0, 9, 1, 3, 10.00, 'fpx', 'maybank', 'Pending', NULL, '2024-10-17 16:41:12', '2024-10-17 16:41:12'),
(5, 0, 9, 1, 3, 10.00, 'fpx', 'maybank', 'Pending', NULL, '2024-10-18 21:17:10', '2024-10-18 21:17:10'),
(6, 0, 9, 1, 3, 10.00, 'fpx', 'maybank', 'Pending', NULL, '2024-10-18 21:19:18', '2024-10-18 21:19:18'),
(7, 0, 9, 3, 3, 10.00, 'fpx', 'maybank', 'Pending', NULL, '2024-10-18 21:20:52', '2024-10-18 21:20:52'),
(8, 0, 9, 1, 3, 10.00, 'fpx', 'maybank', 'Pending', NULL, '2024-10-18 21:27:07', '2024-10-18 21:27:07'),
(9, 0, 9, 2, 3, 10.00, 'fpx', 'bank_islam', 'Pending', NULL, '2024-10-20 21:37:05', '2024-10-20 21:37:05'),
(10, 0, 9, 1, 3, 10.00, 'fpx', 'bank_islam', 'Pending', NULL, '2024-10-20 21:40:42', '2024-10-20 21:40:42'),
(11, 0, 9, 1, 3, 10.00, 'fpx', 'maybank', 'Pending', NULL, '2024-10-20 21:46:54', '2024-10-20 21:46:54'),
(12, 0, 9, 1, 3, 10.00, 'fpx', 'cimb', 'Pending', NULL, '2024-10-20 21:50:16', '2024-10-20 21:50:16'),
(13, 0, 9, 1, 3, 10.00, 'fpx', 'cimb', 'Pending', NULL, '2024-10-20 21:53:27', '2024-10-20 21:53:27'),
(14, 0, 9, 1, 3, 10.00, 'fpx', 'cimb', 'Pending', NULL, '2024-10-20 21:56:24', '2024-10-20 21:56:24'),
(15, 0, 9, 1, 3, 10.00, 'fpx', 'cimb', 'Pending', NULL, '2024-10-20 21:58:22', '2024-10-20 21:58:22'),
(16, 0, 9, 1, 3, 10.00, 'fpx', 'cimb', 'Pending', NULL, '2024-10-20 21:59:19', '2024-10-20 21:59:19'),
(17, 0, 9, 1, 3, 10.00, 'fpx', 'cimb', 'Pending', NULL, '2024-10-20 22:01:28', '2024-10-20 22:01:28');

-- --------------------------------------------------------

--
-- Table structure for table `paymentshop`
--

CREATE TABLE `paymentshop` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('fpx','credit_card') NOT NULL,
  `bank_selection` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paymentshop`
--

INSERT INTO `paymentshop` (`payment_id`, `user_id`, `address_id`, `amount`, `payment_method`, `bank_selection`, `created_at`) VALUES
(1, 1, 9, 50.00, 'credit_card', NULL, '2024-10-16 16:30:33'),
(2, 1, 9, 100.00, 'fpx', 'maybank', '2024-10-20 15:11:36'),
(3, 1, 9, 100.00, 'fpx', 'maybank', '2024-10-20 15:12:17'),
(5, 1, 9, 10.00, 'credit_card', NULL, '2024-10-20 15:21:05'),
(6, 1, 9, 30.00, 'credit_card', NULL, '2024-10-20 15:22:58'),
(7, 1, 9, 30.00, 'credit_card', NULL, '2024-10-20 15:25:57');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `image_path` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `category`, `price`, `stock`, `description`, `image_path`) VALUES
(11, 'Car Shampoo', 'Cleaning', 10.00, 10, 'Product details 3M Car Wash with Wax 1 liter of wax mixed with quality wax to get features. Clear and glossy In one step Specially formulated for cars to help remove dirt without damaging the surface of the car. Mix quality wax to get features Clear and glossy In one step, PH- Balance is safe for us', 'uploads/car sabun.jpg'),
(12, 'Ceramic Coat', 'Cleaning', 20.00, 10, '473ml - the ultimate solution for protecting your vehicle\'s paintwork. This advanced ceramic coating is specially formulated to provide water-repellent and anti-fouling properties, making it easy to maintain a clean and clear vehicle exterior.\r\n\r\n473ml makes it easy to protect your vehicle\'s paintwo', 'uploads/ceramic coat.jpeg'),
(13, 'Initial Car Perfume', 'Accessories', 50.00, 10, 'Initial Perfume Peach Nectar Gred A Car Perfume Car Fresheners Initial Wangian Kereta Ruang Tamu Tandas Pejabat \r\n\r\nINTIAL PERFUME (PEACH) GRED ✅\r\n\r\n📌 Boleh digunakan di semua tempat (KERETA, RUANG TAMU, TANDAS, PEJABAT & DLL) \r\n\r\n📌 Boleh tahan 2-3 bulan bergantung pada cara penggunaan\r\n\r\n📌 Wangian ', 'uploads/initial perfume.jpeg'),
(14, 'Car Phone Holder', 'Accessories', 15.90, 10, '- Flexible Fit: Fits most standard air vents and both horizontal And vertical vent blades. Features an adjustable air vent mounting grip to attach to thicker or thinner air vents\r\n\r\n- Simple Setup: Easily place and remove your phone using the widely adjustable phone cradle with instant release butto', 'uploads/car phone holder.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `service_packages`
--

CREATE TABLE `service_packages` (
  `service_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_packages`
--

INSERT INTO `service_packages` (`service_id`, `name`, `description`, `price`) VALUES
(3, 'Basic Wash', '-Full Exterior Hand Wash\r\n-Basic Wheels and Wheel Wells Cleaned\r\n-Full Exterior Hand Dry', 10.00),
(4, 'Interior Car Detailing', 'Car Vacuum Cleaning\r\nDashboard Cleaning + Coating\r\nCar Seat Detailing (leather or fabric) + Coating\r\nEngine Bay Cleaning\r\nFloor Mat / Carpet Cleaning', 99.00),
(5, 'Exterior Car Detailing', 'Car Snow Wash \r\nPolishing & Waxing\r\nWheel Coating\r\nHeadlamp Restoration\r\nWindscreen Watermark Removal\r\nWindow Coating', 139.00);

-- --------------------------------------------------------

--
-- Table structure for table `support_requests`
--

CREATE TABLE `support_requests` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_requests`
--

INSERT INTO `support_requests` (`id`, `subject`, `message`, `created_at`) VALUES
(1, 'HElp', 'test', '2024-10-13 15:51:42'),
(2, 'HElp', 'test2', '2024-10-13 15:53:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` int(11) DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`, `role`) VALUES
(1, 'Amirul', 'amirulhakimierman@gmail.com', '1376007', NULL, 'admin'),
(9, 'Hakimi', 'BPN221210303@student.kptm.edu.my', '514863', 112345678, 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addressbook`
--
ALTER TABLE `addressbook`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`car_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `paymentshop`
--
ALTER TABLE `paymentshop`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `service_packages`
--
ALTER TABLE `service_packages`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `support_requests`
--
ALTER TABLE `support_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addressbook`
--
ALTER TABLE `addressbook`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `paymentshop`
--
ALTER TABLE `paymentshop`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `service_packages`
--
ALTER TABLE `service_packages`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `support_requests`
--
ALTER TABLE `support_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
