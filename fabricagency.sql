-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 13, 2024 lúc 04:20 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `fabricagency`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `outstanding_balance` decimal(10,2) DEFAULT NULL,
  `warning_status` tinyint(1) DEFAULT 0,
  `bad_debt_status` tinyint(1) DEFAULT 0,
  `warning_start_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`customer_id`, `first_name`, `last_name`, `address`, `phone`, `outstanding_balance`, `warning_status`, `bad_debt_status`, `warning_start_date`) VALUES
(1, 'John', 'Doe', '789 Oak St', '0123456780', 2000000.00, 1, 0, '2024-11-12'),
(2, 'Jane', 'Smith', '101 Pine St', '0987654320', 2200.00, 1, 0, '2024-11-08'),
(3, 'Alice', 'Johnson', '202 Maple St', '0123456781', 21313131.00, 1, 0, '2024-11-13'),
(6, 'James', 'Martinez', '106 Willow St', '0890123456', 2200.00, 1, 0, '2024-11-13'),
(7, 'Patricia', 'Hernandez', '107 Ash St', '0901234567', 250.00, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_care`
--

CREATE TABLE `customer_care` (
  `employee_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customer_care`
--

INSERT INTO `customer_care` (`employee_id`, `customer_id`) VALUES
(4, 1),
(4, 2),
(4, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_payments`
--

CREATE TABLE `customer_payments` (
  `payment_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customer_payments`
--

INSERT INTO `customer_payments` (`payment_id`, `customer_id`, `payment_date`, `amount`) VALUES
(1, 1, '2023-06-01', 200.00),
(2, 2, '2023-06-10', 1000.00),
(3, 2, '2023-07-01', 300.00),
(4, 3, '2023-07-15', 500.00),
(13, 6, '2023-07-10', 250.00),
(14, 7, '2023-07-20', 100.00),
(18, 1, '2024-11-08', 111111.00),
(19, 1, '2024-11-12', 5000000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `gender` enum('Nam','Nữ') DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `role` enum('Manager','Partner','Operations','Office') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `gender`, `address`, `phone`, `role`) VALUES
(1, 'Michael', 'Brown', 'Nam', '345 Elm St', '0123456782', 'Manager'),
(2, 'Sarah', 'Wilson', 'Nữ', '456 Birch St', '0987654322', 'Partner'),
(3, 'David', 'Taylor', 'Nam', '567 Cedar St', '0123456783', 'Operations'),
(4, 'Emily', 'Clark', 'Nữ', '678 Fir St', '0987654323', 'Office'),
(5, '11', '1', 'Nam', '1', '1', 'Operations');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `fabric_rolls`
--

CREATE TABLE `fabric_rolls` (
  `roll_id` int(11) NOT NULL,
  `fabric_type_id` int(11) DEFAULT NULL,
  `length` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `fabric_rolls`
--

INSERT INTO `fabric_rolls` (`roll_id`, `fabric_type_id`, `length`) VALUES
(1, 1, 50.50),
(2, 2, 45.00),
(3, 3, 60.30),
(4, 4, 40.00),
(5, 5, 55.00),
(6, 5, 60.00),
(7, 5, 50.00),
(8, 6, 45.50),
(9, 6, 40.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `fabric_types`
--

CREATE TABLE `fabric_types` (
  `fabric_type_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `color` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `current_price` decimal(10,2) DEFAULT NULL,
  `price_effective_date` date DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `fabric_types`
--

INSERT INTO `fabric_types` (`fabric_type_id`, `name`, `color`, `current_price`, `price_effective_date`, `quantity`, `supplier_id`, `image`) VALUES
(1, 'Silk', 'Red', 20.00, '2023-01-01', 100, 1, 'img2.jpg'),
(2, 'Kaki', 'Green', 15.00, '2023-02-01', 200, 1, 'img3.jpg'),
(3, 'Embroidered', 'Blue', 25.00, '2023-03-01', 150, 2, 'img4.jpg'),
(4, 'Jacquard', 'Yellow', 30.00, '2023-04-01', 120, 2, 'img5.jpg'),
(5, 'Polyester', 'Black', 12.00, '2023-05-01', 300, 3, 'img2.jpg'),
(6, 'Linen', 'White', 18.00, '2023-06-01', 250, 3, 'img6.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `fabric_type_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `fabric_type_id`, `quantity`, `purchase_date`, `purchase_price`) VALUES
(1, 1, 100, '2023-01-15', 18.00),
(2, 2, 200, '2023-02-15', 13.50),
(3, 3, 150, '2023-03-15', 22.00),
(4, 4, 120, '2023-04-15', 27.50),
(5, 5, 300, '2023-05-15', 10.00),
(6, 6, 250, '2023-06-15', 15.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('new','ordered','partial_payment','paid','cancelled') DEFAULT NULL,
  `cancellation_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `employee_id`, `order_date`, `total_amount`, `status`, `cancellation_reason`) VALUES
(1, 1, 3, '2023-08-01 10:00:00', 500.00, 'paid', NULL),
(2, 2, 3, '2023-08-15 14:30:00', 1500.00, 'paid', NULL),
(3, 3, 3, '2023-09-01 16:45:00', 800.00, 'paid', NULL),
(4, 1, 1, '2024-11-07 16:04:00', 123.00, 'partial_payment', NULL),
(7, 1, 1, '2024-11-08 14:47:19', 1200.00, 'new', NULL),
(8, 1, 1, '2024-11-08 14:50:27', 4392.50, 'new', NULL),
(10, 1, 1, '2024-11-09 01:19:55', 321.00, 'new', NULL),
(12, 1, 1, '2024-11-12 14:01:00', 358.00, 'paid', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_fabric_rolls`
--

CREATE TABLE `order_fabric_rolls` (
  `order_roll_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `roll_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_fabric_rolls`
--

INSERT INTO `order_fabric_rolls` (`order_roll_id`, `order_id`, `roll_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 3),
(4, 3, 4),
(5, 4, 6),
(6, 4, 8),
(7, 4, 9),
(12, 7, 4),
(13, 8, 1),
(14, 8, 2),
(15, 8, 3),
(16, 8, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_payments`
--

CREATE TABLE `order_payments` (
  `order_payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_payments`
--

INSERT INTO `order_payments` (`order_payment_id`, `order_id`, `payment_date`, `amount`) VALUES
(1, 1, '2023-08-02', 500.00),
(2, 2, '2023-08-16', 500.00),
(3, 2, '2023-08-20', 500.00),
(4, 3, '2023-09-02', 400.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `bank_account` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `tax_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `name`, `address`, `bank_account`, `tax_code`, `phone`) VALUES
(1, 'Supplier A', '123 Main St', '123456789', 'TAX001', '0123456789'),
(2, 'Supplier B', '456 Central Ave', '987654321', 'TAX002', '0987654321'),
(3, 'Supplier C', '789 Elm St', '333222111', 'TAX003', '0234567890'),
(4, 'Supplier D', '101 Birch St', '444555666', 'TAX004', '0345678901'),
(5, 'Supplier E', '102 Cedar St', '777888999', 'TAX005', '0456789012'),
(6, 'Supplier F', '103 Pine St', '111222333', 'TAX006', '0567890123');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `supplier_care`
--

CREATE TABLE `supplier_care` (
  `employee_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `supplier_care`
--

INSERT INTO `supplier_care` (`employee_id`, `supplier_id`) VALUES
(2, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$Rby21tCqpDqdW1ZFqJoE9OUS6YQTNz.hb/Jnz5LQsW2pZWssS6pIG'),
(2, 'kiet', '$2y$10$CkV5nP5ERT6alS2vZuyoeO4r/r/hxTzWWZ/TtO52QxYNMXXYbPlxC'),
(6, 'phat', '$2y$10$N.mQeAEUPMXh4dbcoRlADe3A1mttlejk2XgmLmK7Kj/6h3E7AJ1Gq'),
(7, 'quyen', '$2y$10$z4ybUO0SuCBTkIMTcDeBi.e0sBjTlJaIVZ3OYTx/Ro/tPcplJpKgC');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Chỉ mục cho bảng `customer_care`
--
ALTER TABLE `customer_care`
  ADD PRIMARY KEY (`employee_id`,`customer_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`);

--
-- Chỉ mục cho bảng `fabric_rolls`
--
ALTER TABLE `fabric_rolls`
  ADD PRIMARY KEY (`roll_id`),
  ADD KEY `fabric_type_id` (`fabric_type_id`);

--
-- Chỉ mục cho bảng `fabric_types`
--
ALTER TABLE `fabric_types`
  ADD PRIMARY KEY (`fabric_type_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Chỉ mục cho bảng `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `fabric_type_id` (`fabric_type_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Chỉ mục cho bảng `order_fabric_rolls`
--
ALTER TABLE `order_fabric_rolls`
  ADD PRIMARY KEY (`order_roll_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `roll_id` (`roll_id`);

--
-- Chỉ mục cho bảng `order_payments`
--
ALTER TABLE `order_payments`
  ADD PRIMARY KEY (`order_payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Chỉ mục cho bảng `supplier_care`
--
ALTER TABLE `supplier_care`
  ADD PRIMARY KEY (`employee_id`,`supplier_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `customer_payments`
--
ALTER TABLE `customer_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `fabric_rolls`
--
ALTER TABLE `fabric_rolls`
  MODIFY `roll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `fabric_types`
--
ALTER TABLE `fabric_types`
  MODIFY `fabric_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `order_fabric_rolls`
--
ALTER TABLE `order_fabric_rolls`
  MODIFY `order_roll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `order_payments`
--
ALTER TABLE `order_payments`
  MODIFY `order_payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `customer_care`
--
ALTER TABLE `customer_care`
  ADD CONSTRAINT `customer_care_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `customer_care_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD CONSTRAINT `customer_payments_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Các ràng buộc cho bảng `fabric_rolls`
--
ALTER TABLE `fabric_rolls`
  ADD CONSTRAINT `fabric_rolls_ibfk_1` FOREIGN KEY (`fabric_type_id`) REFERENCES `fabric_types` (`fabric_type_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `fabric_types`
--
ALTER TABLE `fabric_types`
  ADD CONSTRAINT `fabric_types_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Các ràng buộc cho bảng `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`fabric_type_id`) REFERENCES `fabric_types` (`fabric_type_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Các ràng buộc cho bảng `order_fabric_rolls`
--
ALTER TABLE `order_fabric_rolls`
  ADD CONSTRAINT `order_fabric_rolls_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_fabric_rolls_ibfk_2` FOREIGN KEY (`roll_id`) REFERENCES `fabric_rolls` (`roll_id`);

--
-- Các ràng buộc cho bảng `order_payments`
--
ALTER TABLE `order_payments`
  ADD CONSTRAINT `order_payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Các ràng buộc cho bảng `supplier_care`
--
ALTER TABLE `supplier_care`
  ADD CONSTRAINT `supplier_care_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `supplier_care_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
