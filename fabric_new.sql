-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 22, 2024 lúc 05:33 AM
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
(4, 40, 4);

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
(1, 'Silk', 'Red', 100, 25.00, '2023-01-13', 4, 'img9.jpg'),
(2, 'Kaki', 'Green', 195, 30.00, '2023-02-01', 1, 'img2.jpg'),
(3, 'Embroidered', 'Blue', 141, 35.00, '2023-03-01', 2, 'img3.jpg'),
(4, 'Jacquard', 'Yellow', 102, 18.00, '2023-04-01', 2, 'img4.jpg'),
(5, 'Polyester', 'Black', 289, 40.00, '2023-05-01', 3, 'img5.jpg'),
(6, 'Linen', 'White', 245, 12.00, '2023-06-01', 3, 'img6.jpg');

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
(1, 'Tom', 'Hanks', '333444555', '123 River St', 53.00, 4),
(2, 'Emma', 'Stone', '777888999', '456 Mountain Rd', 0.00, 4),
(3, 'Chris', 'Evans', '111222333', '789 Hill Ln', 20.00, 4),
(9, '1', '1', '1', '1', 1.00, 1),
(10, '1', '1', '1', '1', 1111111.00, 2);

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
(3, 0, 1, '2024-01-01'),
(9, 0, 0, '2024-01-01'),
(10, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_partialpayments`
--

CREATE TABLE `customer_partialpayments` (
  `CusId` int(11) NOT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `PaymentTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `OCode` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customer_partialpayments`
--

INSERT INTO `customer_partialpayments` (`CusId`, `Amount`, `PaymentTime`, `OCode`) VALUES
(1, 50.00, '2024-03-01 05:00:00', NULL),
(1, 444.00, '2024-11-21 20:56:35', NULL),
(1, 1.00, '2024-11-21 21:03:34', NULL),
(1, 1.00, '2024-11-21 21:04:54', NULL),
(1, 1.00, '2024-11-21 21:04:58', NULL),
(2, 100.00, '2024-03-02 06:00:00', NULL),
(3, 75.00, '2024-03-03 07:00:00', NULL);

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
(2, 'Jane', 'Smith', 'Female', '456 Elm St', '987654321', 'OperationalStaff'),
(3, 'Alice', 'Brown', 'Female', '789 Oak St', '543216789', 'PartnerStaff'),
(4, 'Bob', 'Johnson', 'Male', '321 Pine Rd', '678954321', 'PartnerStaff');

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
  `CusId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`OCode`, `TotalPrice`, `OrderTime`, `Status`, `HandleTime`, `ECode`, `CusId`) VALUES
(1, 250.00, '2024-03-01 03:30:00', 'Completed', '2024-03-01 08:00:00', 2, 1),
(2, 500.00, '2024-03-02 04:00:00', 'Completed', NULL, 2, 2),
(3, 300.00, '2024-03-03 05:00:00', 'Completed', '2024-03-03 07:30:00', 2, 3),
(6, 222.00, '2024-11-21 08:27:38', 'new', NULL, 1, 1),
(7, 54.00, '2024-11-21 20:22:07', 'new', NULL, 1, 1),
(8, 90.00, '2024-11-21 20:46:15', 'new', NULL, 1, 1),
(9, 54.00, '2024-11-21 20:51:58', 'new', '2024-11-21 20:51:58', 1, 1);

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
(2, 6, 2, 5, 30.00, 150.00),
(3, 6, 4, 4, 18.00, 72.00),
(4, 7, 4, 3, 18.00, 54.00),
(5, 8, 4, 5, 18.00, 90.00),
(6, 9, 4, 3, 18.00, 54.00);

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
(3, 'Global Textiles', '789 Fabric Blvd', '456-789-123', 'TAX003', '777888999', 3),
(4, 'Prime Fabrics', '987 Silk Ave', '789-123-456', 'TAX004', '555666777', 3);

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
  ADD PRIMARY KEY (`CusId`,`PaymentTime`);

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
  MODIFY `CusId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `employee`
--
ALTER TABLE `employee`
  MODIFY `ECode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `OCode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `DetailId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
