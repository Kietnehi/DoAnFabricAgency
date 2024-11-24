-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3307
-- Thời gian đã tạo: Th10 24, 2024 lúc 03:52 AM
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
-- Cơ sở dữ liệu: `fabric_new`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bolt`
--

CREATE TABLE `bolt` (
  `BCode` int(11) NOT NULL,
  `Length` int(11) DEFAULT NULL,
  `CCode` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bolt`
--

INSERT INTO `bolt` (`BCode`, `Length`, `CCode`) VALUES
(1, 50, 1),
(2, 75, 2),
(3, 35, 3),
(4, 40, 4),
(5, 25, 5),
(6, 15, 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `CCode` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Color` varchar(50) DEFAULT NULL,
  `RemainQuantity` int(11) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `AppliedDate` date DEFAULT NULL,
  `SCode` int(11) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`CCode`, `Name`, `Color`, `RemainQuantity`, `Price`, `AppliedDate`, `SCode`, `img`) VALUES
(1, 'Silk', 'Red', 98, 25.00, '2023-01-13', 4, 'img9.jpg'),
(2, 'Kaki', 'Green', 193, 30.00, '2023-02-01', 1, 'img2.jpg'),
(3, 'Embroidered', 'Blue', 139, 35.00, '2023-03-01', 2, 'img3.jpg'),
(4, 'Jacquard', 'Yellow', 91, 18.00, '2023-04-01', 2, 'img4.jpg'),
(5, 'Polyester', 'Black', 289, 40.00, '2023-05-01', 3, 'img5.jpg'),
(6, 'Linen', 'White', 241, 12.00, '2023-06-01', 3, 'img6.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer`
--

CREATE TABLE `customer` (
  `CusId` int(11) NOT NULL,
  `Fname` varchar(100) DEFAULT NULL,
  `Lname` varchar(100) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `Dept` decimal(10,2) DEFAULT NULL,
  `ECode` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customer`
--

INSERT INTO `customer` (`CusId`, `Fname`, `Lname`, `Phone`, `Address`, `Dept`, `ECode`) VALUES
(1, 'Tom', 'Hanks', '333444555', '123 River St', 1.00, 5),
(2, 'Emma', 'Stone', '777888999', '456 Mountain Rd', 0.00, 5),
(3, 'Chris', 'Evans', '111222333', '789 Hill Ln', 20.00, 5),
(11, 'Phát', 'Lê', '12383838', '123 trung son', 3000.00, 2),
(12, 'Kiệt', 'Trương', '12937736', '123j ksjdf', 5000.00, 2),
(13, 'Quyền', 'Lê', '12739123', 'klj3213', 1000.00, 2),
(14, 'Trí', 'Trần', '12478395', '273 An Dương Vương P3, Q.5', 4000.00, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customerstatus`
--

CREATE TABLE `customerstatus` (
  `CusId` int(11) NOT NULL,
  `Alert` tinyint(1) DEFAULT NULL,
  `BadDebt` tinyint(1) DEFAULT NULL,
  `AlertStartDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customerstatus`
--

INSERT INTO `customerstatus` (`CusId`, `Alert`, `BadDebt`, `AlertStartDate`) VALUES
(1, 0, 0, NULL),
(2, 0, 0, '2024-01-01'),
(3, 0, 0, '2024-01-01'),
(11, 1, 0, '2024-11-24'),
(12, 1, 0, '2024-11-24'),
(13, 0, 0, '2024-11-24'),
(14, 1, 0, '2024-11-24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_partialpayments`
--

CREATE TABLE `customer_partialpayments` (
  `CusId` int(11) NOT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `PaymentTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `OCode` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customer_partialpayments`
--

INSERT INTO `customer_partialpayments` (`CusId`, `Amount`, `PaymentTime`, `OCode`, `id`) VALUES
(1, 50.00, '2024-03-01 05:00:00', NULL, 1),
(1, 444.00, '2024-11-21 20:56:35', NULL, 2),
(1, 1.00, '2024-11-21 21:03:34', NULL, 3),
(1, 1.00, '2024-11-21 21:04:54', NULL, 4),
(1, 1.00, '2024-11-21 21:04:58', NULL, 5),
(1, 100.00, '2024-11-22 09:29:32', 1, 6),
(1, 150.00, '2024-11-22 09:33:46', 1, 7),
(2, 100.00, '2024-03-02 06:00:00', NULL, 8),
(2, 111.00, '2024-11-22 09:31:52', 2, 9),
(3, 75.00, '2024-03-03 07:00:00', NULL, 10),
(3, 111.00, '2024-11-22 09:33:37', 3, 11),
(3, 1.00, '2024-11-22 09:34:03', 3, 12),
(3, 1.00, '2024-11-22 09:35:56', 3, 13),
(3, 1.00, '2024-11-22 09:35:56', 3, 14),
(1, 11.00, '2024-11-22 09:41:51', 8, 15),
(1, 11.00, '2024-11-22 03:42:13', NULL, 16),
(1, 41.00, '2024-11-22 03:49:57', NULL, 17),
(1, 12.00, '2024-11-22 09:56:26', 10, 18),
(1, 12.00, '2024-11-22 09:57:55', 10, 19),
(1, 12.00, '2024-11-22 09:57:58', 10, 20),
(11, 20.00, '2024-11-23 05:08:01', 11, 21),
(12, 85.00, '2024-11-23 06:42:36', 13, 22);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `employee`
--

CREATE TABLE `employee` (
  `ECode` int(11) NOT NULL,
  `Fname` varchar(100) DEFAULT NULL,
  `Lname` varchar(100) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `employee`
--

INSERT INTO `employee` (`ECode`, `Fname`, `Lname`, `Gender`, `Address`, `Phone`, `Role`) VALUES
(1, 'John', 'Doe', 'Male', '123 Main St', '123456789', 'Manager'),
(2, 'Jane', 'Smith', 'Female', '456 Elm St', '987654321', 'OfficeStaff'),
(3, 'Alice', 'Brown', 'Female', '789 Oak St', '543216789', 'PartnerStaff'),
(4, 'Bob', 'Johnson', 'Male', '321 Pine Rd', '678954321', 'PartnerStaff'),
(5, 'Pam', 'Beesly', 'Female', '1830 Maple Rd', '9876543210', 'OfficeStaff'),
(6, 'Angela', 'Martin', 'Female', '789 Paws Ave', '1118882222', 'OperationalStaff'),
(7, 'Bold', 'David', 'Female', '1234 Kays St', '1236231233', 'OperationalStaff');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `OCode` int(11) NOT NULL,
  `TotalPrice` decimal(10,2) DEFAULT NULL,
  `OrderTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` varchar(50) DEFAULT NULL,
  `HandleTime` timestamp NULL DEFAULT NULL,
  `ECode` int(11) DEFAULT NULL,
  `CusId` int(11) DEFAULT NULL,
  `Cancellation_Reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`OCode`, `TotalPrice`, `OrderTime`, `Status`, `HandleTime`, `ECode`, `CusId`, `Cancellation_Reason`) VALUES
(1, 250.00, '2024-03-01 03:30:00', 'paid', '2024-03-01 08:00:00', 6, 1, ''),
(2, 500.00, '2024-03-02 04:00:00', 'partial_payment', '2024-11-07 09:15:07', 6, 1, NULL),
(3, 300.00, '2024-03-03 05:00:00', 'partial_payment', '2024-03-03 07:30:00', 7, 3, NULL),
(7, 54.00, '2024-11-21 20:22:00', 'partial_payment', '2024-11-04 09:15:12', 6, 1, '1'),
(8, 90.00, '2024-11-21 20:46:15', 'partial_payment', '2024-11-28 09:15:15', 6, 1, NULL),
(10, 90.00, '2024-11-22 03:47:41', 'cancelled', '2024-11-22 03:47:41', 6, 1, 'quá tệ '),
(11, 90.00, '2024-11-22 23:04:00', 'partial_payment', '2024-11-22 23:04:38', 7, 11, ''),
(12, 60.00, '2024-11-22 23:06:02', 'new', '2024-11-22 23:06:02', 6, 1, NULL),
(13, 86.00, '2024-11-23 00:41:23', 'partial_payment', '2024-11-23 00:41:23', 7, 12, NULL),
(14, 100.00, '2024-11-23 20:48:42', 'new', '2024-11-23 20:48:42', 7, 14, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_detail`
--

CREATE TABLE `order_detail` (
  `DetailId` int(11) NOT NULL,
  `OCode` int(11) NOT NULL,
  `BCode` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitPrice` decimal(10,2) NOT NULL,
  `TotalPrice` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_detail`
--

INSERT INTO `order_detail` (`DetailId`, `OCode`, `BCode`, `Quantity`, `UnitPrice`, `TotalPrice`) VALUES
(4, 7, 4, 3, 18.00, 54.00),
(5, 8, 4, 5, 18.00, 90.00),
(7, 10, 4, 5, 18.00, 90.00),
(8, 11, 4, 5, 18.00, 90.00),
(9, 12, 2, 2, 30.00, 60.00),
(10, 13, 1, 2, 25.00, 50.00),
(11, 13, 6, 3, 12.00, 36.00),
(12, 14, 3, 2, 35.00, 70.00),
(13, 14, 4, 1, 18.00, 18.00),
(14, 14, 6, 1, 12.00, 12.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `supplier`
--

CREATE TABLE `supplier` (
  `SCode` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `BankAccount` varchar(50) DEFAULT NULL,
  `TaxCode` varchar(50) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `ECode` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `supplier`
--

INSERT INTO `supplier` (`SCode`, `Name`, `Address`, `BankAccount`, `TaxCode`, `Phone`, `ECode`) VALUES
(1, 'ABC Supplies', '123 Supply Rd', '123-456-789', 'TAX001', '111222333', 3),
(2, 'XYZ Materials', '456 Warehouse Ln', '987-654-321', 'TAX002', '444555666', 3),
(3, 'Global Textiles', '789 Fabric Blvd', '456-789-123', 'TAX003', '777888999', 4),
(4, 'Prime Fabrics', '987 Silk Ave', '789-123-456', 'TAX004', '555666777', 4),
(5, '1', '1', '1', '1', '1', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `supplyhistory`
--

CREATE TABLE `supplyhistory` (
  `CCode` int(11) NOT NULL,
  `SuppliedDate` date NOT NULL,
  `SuppliedPrice` decimal(10,2) DEFAULT NULL,
  `SuppliedQuantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `supplyhistory`
--

INSERT INTO `supplyhistory` (`CCode`, `SuppliedDate`, `SuppliedPrice`, `SuppliedQuantity`) VALUES
(1, '2024-01-15', 2.00, 300),
(2, '2024-02-20', 4.50, 150),
(3, '2024-03-10', 3.80, 200),
(4, '2024-03-25', 3.20, 100);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$fSXilBpCLsuqIle4zyi7ne5MMTHNS8/HUGVuowtsgRlrYNFhD2eqO'),
(2, 'phat', 'cb83aea2a76c47853d1bad74f3ee82157afb34c62dd1e0a37a1f03fdcec520c9'),
(3, 'kiet', '1e5058d9633ee8a85d76fb0d883720b33721471180e28bdcb5869e17da86cebe');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bolt`
--
ALTER TABLE `bolt`
  ADD PRIMARY KEY (`BCode`),
  ADD KEY `CCode` (`CCode`);

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CCode`),
  ADD KEY `SCode` (`SCode`);

--
-- Chỉ mục cho bảng `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CusId`),
  ADD KEY `ECode` (`ECode`);

--
-- Chỉ mục cho bảng `customerstatus`
--
ALTER TABLE `customerstatus`
  ADD PRIMARY KEY (`CusId`);

--
-- Chỉ mục cho bảng `customer_partialpayments`
--
ALTER TABLE `customer_partialpayments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_partialpayments_ibfk_1` (`CusId`);

--
-- Chỉ mục cho bảng `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`ECode`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OCode`),
  ADD KEY `ECode` (`ECode`),
  ADD KEY `CusId` (`CusId`);

--
-- Chỉ mục cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`DetailId`),
  ADD KEY `OCode` (`OCode`),
  ADD KEY `BCode` (`BCode`);

--
-- Chỉ mục cho bảng `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SCode`),
  ADD KEY `ECode` (`ECode`);

--
-- Chỉ mục cho bảng `supplyhistory`
--
ALTER TABLE `supplyhistory`
  ADD PRIMARY KEY (`CCode`,`SuppliedDate`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bolt`
--
ALTER TABLE `bolt`
  MODIFY `BCode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `CCode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `customer`
--
ALTER TABLE `customer`
  MODIFY `CusId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `customer_partialpayments`
--
ALTER TABLE `customer_partialpayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `employee`
--
ALTER TABLE `employee`
  MODIFY `ECode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `OCode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `DetailId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SCode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bolt`
--
ALTER TABLE `bolt`
  ADD CONSTRAINT `bolt_ibfk_1` FOREIGN KEY (`CCode`) REFERENCES `category` (`CCode`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`SCode`) REFERENCES `supplier` (`SCode`);

--
-- Các ràng buộc cho bảng `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`ECode`) REFERENCES `employee` (`ECode`);

--
-- Các ràng buộc cho bảng `customerstatus`
--
ALTER TABLE `customerstatus`
  ADD CONSTRAINT `customerstatus_ibfk_1` FOREIGN KEY (`CusId`) REFERENCES `customer` (`CusId`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `customer_partialpayments`
--
ALTER TABLE `customer_partialpayments`
  ADD CONSTRAINT `customer_partialpayments_ibfk_1` FOREIGN KEY (`CusId`) REFERENCES `customer` (`CusId`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`ECode`) REFERENCES `employee` (`ECode`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`CusId`) REFERENCES `customer` (`CusId`);

--
-- Các ràng buộc cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`OCode`) REFERENCES `orders` (`OCode`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`BCode`) REFERENCES `bolt` (`BCode`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `supplier_ibfk_1` FOREIGN KEY (`ECode`) REFERENCES `employee` (`ECode`);

--
-- Các ràng buộc cho bảng `supplyhistory`
--
ALTER TABLE `supplyhistory`
  ADD CONSTRAINT `supplyhistory_ibfk_1` FOREIGN KEY (`CCode`) REFERENCES `category` (`CCode`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
