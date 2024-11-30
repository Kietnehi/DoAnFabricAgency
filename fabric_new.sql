-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3307
-- Thời gian đã tạo: Th10 29, 2024 lúc 01:16 PM
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
(1, 'Silk', 'Red', 97, 25.00, '2023-01-13', 4, 'img9.jpg'),
(2, 'Kaki', 'Green', 192, 30.00, '2023-02-01', 1, 'img2.jpg'),
(3, 'Embroidered', 'Blue', 137, 35.00, '2023-03-01', 2, 'img3.jpg'),
(4, 'Jacquard', 'Yellow', 89, 18.00, '2023-04-01', 2, 'img4.jpg'),
(5, 'Polyester', 'Black', 288, 40.00, '2023-05-01', 3, 'img5.jpg'),
(6, 'Linen', 'White', 240, 12.00, '2023-06-01', 3, 'img6.jpg');

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
(1, 'Tom', 'Hanks', '333444555', '123 River St', 10.00, 5),
(2, 'Emma', 'Stone', '777888999', '456 Mountain Rd', 0.00, 5),
(3, 'Chris', 'Evans', '111222333', '789 Hill Ln', 20.00, 5),
(4, 'Quyền', 'Lê', '0993246149', '273 An Dương Vương P3, Q.5', 2000.00, 2),
(5, 'Phát', 'Lê', '2025550187', '1600 Pennsylvania Avenue NW, Washington, DC 20500, USA', 3000.00, 5),
(6, 'Kiệt', 'Trương', '2079464458', '10 Downing Street, Westminster, London SW1A 2AA, United Kingdom', 5000.00, 5),
(7, 'Phúc', 'Nguyễn', '8394373421', '392 Clifford Place, Fontanelle, Arizona, 2687', 1000.00, 5),
(8, 'Nguyên', 'Lý', '8614602317', '203 Allen Avenue, Elrama, North Carolina, 4453', 1000.00, 5),
(9, 'Minh', 'Nguyễn', '9415692231', '111 Schroeders Avenue, Suitland, Louisiana, 7042', 1000.00, 2),
(10, 'Trí', 'Trần', '8085002793', '190 Just Court, Canoochee, Alabama, 325', 1000.00, 5),
(11, 'Keith', 'Dawn', '9285243812', '757 Beaumont Street, Santel, West Virginia, 3370', 1000.00, 2),
(12, 'Eaton', 'Bettie', '9435423591', '766 Cooke Court, Dunbar, Connecticut, 9512', 1000.00, 2),
(13, 'Ayala', 'Margie', '8105212350', '210 Pleasant Place, Lloyd, Mississippi, 1636', 1000.00, 2),
(14, 'Townsend', 'Baker', '9555642002', '220 Drew Street, Ventress, Puerto Rico, 8432', 1000.00, 2),
(15, 'Atkinson', 'Annmarie', '8364322542', '532 Amity Street, Yukon, Palau, 3561', 1000.00, 2),
(16, 'Jenkins', 'Stark', '9585222724', '273 Temple Court, Shelby, Georgia, 8682', 1000.00, 2),
(17, 'Rollins', 'Odonnell', '8994643878', '909 Wyona Street, Adelino, Hawaii, 6449', 1000.00, 2),
(18, 'Chang', 'Rachelle', '8294063577', '887 Willoughby Street, Wyoming, American Samoa, 6237', 1000.00, 2),
(19, 'Wade', 'Davis', '9834233866', '793 Tabor Court, Coultervillle, Colorado, 4179', 1000.00, 2),
(20, 'Everett', 'Oneill', '8914703257', '411 Verona Place, Franklin, Arkansas, 9044', 1000.00, 2),
(21, 'Serrano', 'Howard', '8284972089', '705 Halsey Street, Datil, Florida, 7036', 1000.00, 2),
(22, 'Reyes', 'Mills', '8834612362', '558 Tapscott Avenue, Vernon, Washington, 807', 1000.00, 2),
(23, 'Chase', 'Tommie', '9764903620', '799 Jefferson Street, Cuylerville, Delaware, 4823', 1000.00, 2),
(24, 'Mayer', 'Frost', '9755492709', '819 Scott Avenue, Brutus, Missouri, 2819', 1000.00, 2),
(25, 'Sweeney', 'Mercado', '8584022835', '572 Woodpoint Road, Kempton, Minnesota, 2645', 1000.00, 2),
(26, 'Humphrey', 'Potts', '9844642373', '195 Cass Place, Siglerville, Montana, 9548', 1000.00, 2),
(27, 'Richardson', 'Garrison', '9215252437', '153 Autumn Avenue, Veguita, South Carolina, 743', 1000.00, 2),
(28, 'Hood', 'Owen', '8315252895', '885 Seton Place, Steinhatchee, Kentucky, 1146', 1000.00, 2),
(29, 'Cline', 'Erna', '8785342114', '273 Ludlam Place, Darrtown, Northern Mariana Islands, 8800', 1000.00, 2),
(30, 'Cochran', 'Milagros', '8395802646', '844 Sands Street, Brady, Iowa, 5320', 1000.00, 2),
(31, 'Cummings', 'Roseann', '9925313322', '978 Baycliff Terrace, Caberfae, Wyoming, 1363', 1000.00, 2),
(32, 'Hubbard', 'Stuart', '8505583418', '622 Lloyd Street, Hampstead, Kansas, 4635', 1000.00, 2),
(33, 'Whitley', 'Tonia', '8535233393', '739 Village Court, Driftwood, New York, 5476', 1000.00, 2),
(34, 'Herman', 'Burris', '9374973711', '759 Prince Street, Dubois, Guam, 5099', 1000.00, 2),
(35, 'Sanchez', 'Minnie', '9234822721', '711 Hubbard Street, Wheatfields, Ohio, 4944', 1000.00, 2),
(36, 'Shannon', 'Francis', '8835362259', '300 Jewel Street, Sugartown, Federated States Of Micronesia, 9305', 1000.00, 2),
(37, 'Sanders', 'Webster', '8765773338', '146 Polar Street, Linganore, Illinois, 8549', 1000.00, 2),
(38, 'Guzman', 'Osborne', '8365192274', '534 Grimes Road, Calvary, Virginia, 3901', 1000.00, 2),
(39, 'Puckett', 'Juana', '9415542284', '610 Crawford Avenue, Guthrie, Nevada, 1300', 1000.00, 2),
(40, 'Nixon', 'Tillman', '9064462149', '242 Arkansas Drive, Homeland, South Dakota, 3032', 1000.00, 2),
(41, 'Mcclain', 'Marisol', '8085702185', '366 Seigel Street, Limestone, Oregon, 1914', 1000.00, 2),
(42, 'Stevens', 'Shelley', '8085702185', '366 Seigel Street, Limestone, Oregon, 1914', 1000.00, 2),
(43, 'YGKddhk', 'Tihxk', '03761375996', 'Street 300, City 39', 2102.00, 2),
(44, 'eeCvncyngM', 'SBACvEvOJV', '03455776591', 'Street 391, City 60', 4854.00, 5),
(45, 'xBNGTvh', 'OWJiK', '03904196061', 'Street 250, City 70', 3238.00, 2),
(46, 'TXfOu', 'gfFVLDDajH', '03795621904', 'Street 204, City 71', 3833.00, 2),
(47, 'dJzDUw', 'hnCwrpydCI', '03991968531', 'Street 91, City 31', 3339.00, 2),
(48, 'VPewTd', 'YSZbdfdwA', '03196339390', 'Street 188, City 87', 1357.00, 5),
(49, 'lGsasqnBA', 'VMYRmH', '03660330418', 'Street 8, City 97', 1592.00, 5),
(50, 'PtUYYtkypF', 'klJUHTRrQ', '03138664950', 'Street 370, City 7', 834.00, 2),
(51, 'TmKbBE', 'RCPRY', '03115391792', 'Street 49, City 24', 2614.00, 5),
(52, 'VygAo', 'IOlHvlD', '03130456704', 'Street 9, City 73', 184.00, 2),
(53, 'nLIbgvtRj', 'tCQraYud', '03480689557', 'Street 389, City 80', 1907.00, 5),
(54, 'OyMPa', 'KgINDOtt', '03676859487', 'Street 31, City 5', 1398.00, 2),
(55, 'wWKtFOPP', 'HZDvWqb', '03474076425', 'Street 383, City 78', 4403.00, 2),
(56, 'sHxNrqLe', 'zaoIif', '03110371796', 'Street 192, City 30', 1760.00, 2),
(57, 'VZsdpSmC', 'OdnZjcLYdN', '03628834931', 'Street 112, City 66', 3365.00, 2),
(58, 'ILubK', 'wgFoqafQ', '03545444703', 'Street 185, City 52', 4437.00, 2),
(59, 'zgypuiX', 'BmllPzvH', '03832974217', 'Street 233, City 50', 1555.00, 2),
(60, 'zmAxzVF', 'gbujnleaDg', '03129048982', 'Street 433, City 31', 2966.00, 2),
(61, 'PxmMGc', 'rSeQWRv', '03378907425', 'Street 408, City 100', 2548.00, 2),
(62, 'vGxJLZXhW', 'qNEaWvp', '03687510116', 'Street 327, City 29', 4870.00, 2),
(63, 'wBpBHEbBu', 'TIAlan', '03698026835', 'Street 465, City 92', 1501.00, 2),
(64, 'WARyLcVnnE', 'qDmlDxCaX', '03817627902', 'Street 42, City 16', 4860.00, 2),
(65, 'ZuDcaoq', 'BVOIntNBZg', '03560988685', 'Street 274, City 48', 3042.00, 2),
(66, 'hMlgl', 'dfKNNh', '03558393001', 'Street 406, City 42', 33.00, 2),
(67, 'ygpoJW', 'eJlsMOvE', '03703329887', 'Street 423, City 30', 1628.00, 5),
(68, 'RwxABRMWK', 'JnhtoZwQk', '03439709254', 'Street 42, City 95', 3183.00, 2),
(69, 'LuYSmGECo', 'WEliJ', '03938177409', 'Street 386, City 87', 2722.00, 2),
(70, 'oatsg', 'BvIaOJlf', '03819084444', 'Street 291, City 15', 3496.00, 2),
(71, 'iKYkiD', 'Yjmxbhee', '03317483469', 'Street 70, City 7', 4197.00, 5),
(72, 'RzRYvlbAp', 'UPAWvk', '03148186573', 'Street 491, City 29', 1994.00, 5),
(73, 'rYMZBlSWRZ', 'LPLYNez', '03513185694', 'Street 3, City 81', 1138.00, 5),
(74, 'YlZjRbEv', 'HwRrfeFjQc', '03483528223', 'Street 133, City 28', 502.00, 5),
(75, 'VnhkiGmAiR', 'sOdWBJX', '03382849949', 'Street 92, City 50', 1397.00, 2),
(76, 'ueqvg', 'IdYUfU', '03771148189', 'Street 259, City 17', 3417.00, 5),
(77, 'UVmzN', 'wWdll', '03999319102', 'Street 360, City 47', 4073.00, 5),
(78, 'TtYfoWfNVY', 'dohrAv', '03481793558', 'Street 70, City 61', 3624.00, 5),
(79, 'TWPiwGAEP', 'EFXGHtlGRI', '03275157386', 'Street 424, City 40', 1591.00, 5),
(80, 'plYDHzrl', 'LQCgXwTo', '03880406210', 'Street 49, City 95', 3836.00, 5),
(81, 'omlyXaxu', 'legJtCh', '03493421870', 'Street 437, City 82', 2899.00, 5),
(82, 'rcgijDk', 'umEpPBRzKm', '03406098734', 'Street 437, City 72', 4918.00, 5),
(83, 'xiwZloI', 'FmqIIcKmqa', '03856827709', 'Street 377, City 94', 1665.00, 5),
(84, 'sbZdl', 'zhhQrd', '03591144075', 'Street 481, City 40', 3604.00, 5),
(85, 'myWPbiMgjn', 'JsAmANDy', '03930638516', 'Street 214, City 91', 3526.00, 2),
(86, 'LKvuGtXbU', 'JDxioJHqEm', '03454555724', 'Street 142, City 30', 3341.00, 5),
(87, 'ZrLKZox', 'COmdFWcNw', '03324970499', 'Street 322, City 45', 2661.00, 2),
(88, 'zuKSac', 'tvNtMlkT', '03426198921', 'Street 223, City 13', 2370.00, 2),
(89, 'FBSgKoalT', 'mEqsvDg', '03690503555', 'Street 70, City 77', 763.00, 5),
(90, 'NeIRg', 'MmyEFSSh', '03412582500', 'Street 483, City 42', 591.00, 2),
(91, 'JbGQCKk', 'xxtaVi', '03328032169', 'Street 23, City 59', 3422.00, 5),
(92, 'uGJalGYsE', 'pvZUWpyCGq', '03429515622', 'Street 415, City 98', 4401.00, 5),
(93, 'HCsnLrbUD', 'UnhAvDVmTO', '03146509469', 'Street 64, City 7', 1282.00, 5),
(94, 'TGuUmYHT', 'oxJxk', '03446027439', 'Street 238, City 14', 3951.00, 5),
(95, 'SXkZkC', 'yLHECwb', '03308138762', 'Street 59, City 56', 2613.00, 5),
(96, 'aRhdWDyuNe', 'aSnBlYvL', '03143166810', 'Street 457, City 18', 3388.00, 5),
(97, 'AzqbpW', 'hnaUZAG', '03892254880', 'Street 67, City 54', 4726.00, 5),
(98, 'tqYWotKW', 'iWUrtGagK', '03674106834', 'Street 146, City 29', 4614.00, 2),
(99, 'EtiqGN', 'GlVIbnoYs', '03631808633', 'Street 96, City 50', 2987.00, 2),
(100, 'UkwDEHCdmJ', 'UBQspVb', '03330989414', 'Street 4, City 38', 2526.00, 5),
(101, 'zQclDk', 'OPpCldEB', '03505853997', 'Street 47, City 88', 4095.00, 5),
(102, 'PQXdBZm', 'ZdXKZ', '03360726251', 'Street 362, City 100', 3622.00, 2),
(103, 'tXGlkd', 'AuWqwjkDqP', '03297786651', 'Street 99, City 67', 2686.00, 5),
(104, 'NbsYBZf', 'MmKsAGvo', '03704155433', 'Street 198, City 5', 3371.00, 2),
(105, 'fUZClMy', 'cLLJAHeuuZ', '03128958916', 'Street 371, City 92', 3617.00, 5),
(106, 'XeoEirDNU', 'JAWclIaCa', '03217398521', 'Street 454, City 22', 207.00, 5),
(107, 'qRdiRXbL', 'YrWJLtyB', '03173801467', 'Street 303, City 4', 1285.00, 5),
(108, 'WpjmHa', 'csCRbpZR', '03747745103', 'Street 201, City 19', 970.00, 2),
(109, 'jbCpK', 'gDhYCOZRKl', '03155889638', 'Street 493, City 7', 4476.00, 2),
(110, 'YSewTubvA', 'LppFYmSjy', '03433944623', 'Street 89, City 69', 3087.00, 2),
(111, 'OsCnBABz', 'YuBHQQwg', '03766184754', 'Street 362, City 89', 1994.00, 5),
(112, 'nSxgfIqfN', 'TUWmu', '03654209407', 'Street 166, City 9', 1979.00, 5),
(113, 'ahGBuFcbRm', 'mvObF', '03781079605', 'Street 161, City 8', 4717.00, 2),
(114, 'mrJoqPKRvp', 'WSvCMUjZh', '03572972252', 'Street 435, City 23', 3117.00, 5),
(115, 'WDEnBpLPFN', 'GUleHMYDi', '03460362212', 'Street 434, City 88', 1761.00, 2),
(116, 'rBLdflk', 'uVxzoagC', '03894038059', 'Street 468, City 18', 1086.00, 2),
(117, 'ZnUGPMipC', 'DYoZtUpE', '03593410944', 'Street 374, City 38', 2879.00, 5),
(118, 'wnlzBXfxXa', 'mTCTxD', '03214519811', 'Street 132, City 42', 4466.00, 2),
(119, 'CQPJGmBhG', 'uhKbtzdghs', '03598139672', 'Street 447, City 39', 1211.00, 2),
(120, 'CVzqDLwrj', 'CfgdAQ', '03672409047', 'Street 99, City 81', 3442.00, 5),
(121, 'zMLxyGtY', 'DFgFQe', '03861016933', 'Street 411, City 17', 4654.00, 2),
(122, 'oDFSGopu', 'zkyoEjEH', '03543459276', 'Street 53, City 93', 18.00, 2),
(123, 'ekShsgHSuC', 'VLbICMbtDz', '03569612412', 'Street 251, City 98', 1395.00, 5),
(124, 'khRfGU', 'FqMFZabM', '03291736550', 'Street 218, City 64', 981.00, 5),
(125, 'cBrTOIdEYO', 'vnuOb', '03673426546', 'Street 263, City 47', 859.00, 5),
(126, 'xxLpXT', 'VrDEEpJoY', '03971402128', 'Street 64, City 47', 533.00, 5),
(127, 'YeJOdq', 'ckcbMjUFN', '03685833231', 'Street 471, City 24', 1335.00, 2),
(128, 'tlNFM', 'KyBwNdE', '03317176848', 'Street 310, City 96', 1430.00, 5),
(129, 'eRpkXXfBe', 'EZZzNchMD', '03445601920', 'Street 459, City 68', 3855.00, 2),
(130, 'TvhjeFEy', 'XoZerAud', '03748082953', 'Street 265, City 58', 4750.00, 5),
(131, 'DctRtJvOIX', 'xnPfKVL', '03536141356', 'Street 264, City 49', 532.00, 5),
(132, 'VEhxKEIPx', 'avNUIX', '03324157209', 'Street 2, City 96', 3336.00, 2),
(133, 'ijTjhftC', 'aCfpw', '03185961714', 'Street 332, City 71', 4941.00, 2),
(134, 'aZADeJg', 'dmBGW', '03324915059', 'Street 277, City 94', 4267.00, 5),
(135, 'EqnrVBwXXf', 'PGyiRn', '03380707552', 'Street 402, City 80', 4248.00, 5),
(136, 'orTef', 'vUKQPJE', '03328260115', 'Street 137, City 24', 986.00, 2),
(137, 'VpIatl', 'eRPXxd', '03807216069', 'Street 425, City 92', 3703.00, 5),
(138, 'NcpDZOsB', 'LkNiIvd', '03271513358', 'Street 484, City 72', 3156.00, 5),
(139, 'RDsxh', 'HEflPCJvea', '03116687963', 'Street 185, City 45', 1373.00, 2),
(140, 'TAlSYZp', 'HinEF', '03197606725', 'Street 67, City 90', 3343.00, 5),
(141, 'burHgPvuU', 'kXWxJ', '03572592989', 'Street 131, City 28', 3148.00, 5),
(142, 'bjIDKDJx', 'TMXpQHbmnn', '03516862963', 'Street 288, City 61', 2301.00, 2),
(143, 'ajsaqFEky', 'PzpXj', '03217251838', 'Street 327, City 95', 2032.00, 2),
(144, 'wdQOOtYYL', 'pWSFY', '03834632990', 'Street 374, City 59', 192.00, 5),
(145, 'vozQij', 'mKvvyrr', '03784902323', 'Street 444, City 58', 4565.00, 5),
(146, 'odNVv', 'kFyFsnD', '03614745049', 'Street 390, City 80', 1005.00, 5),
(147, 'YLECseaB', 'YYAyahmQO', '03748797057', 'Street 472, City 27', 4009.00, 5),
(148, 'ZFaXfye', 'RYfPUAI', '03595644220', 'Street 293, City 49', 3915.00, 2),
(149, 'BWaUwbJ', 'LSyvP', '03815817520', 'Street 383, City 6', 371.00, 2),
(150, 'ZqxWs', 'gibUo', '03465888630', 'Street 87, City 44', 2074.00, 5),
(151, 'nJyIto', 'rVbRMgzxrn', '03464264946', 'Street 410, City 48', 729.00, 2),
(152, 'yjmpSXP', 'VaPAY', '03216981204', 'Street 454, City 98', 675.00, 5),
(153, 'kGGpUtjFFP', 'XBKcf', '03580309434', 'Street 485, City 85', 1078.00, 5),
(154, 'xjDyHReiiI', 'WiUhzad', '03110912331', 'Street 356, City 100', 4570.00, 5),
(155, 'DbQdhbeXdJ', 'NgAhOEEUp', '03822062790', 'Street 498, City 16', 1869.00, 5),
(156, 'MlXMTt', 'pnJFnsHzMC', '03943967132', 'Street 184, City 85', 4007.00, 5),
(157, 'FIMtAJFdw', 'jLUpMvuyJW', '03965921840', 'Street 78, City 77', 2451.00, 2),
(158, 'FuVWy', 'GCicG', '03294663381', 'Street 211, City 4', 316.00, 2),
(159, 'mHOlcfH', 'GPRkdo', '03855092167', 'Street 295, City 87', 112.00, 2),
(160, 'MQKkJxNfF', 'vJbqUwj', '03766793482', 'Street 133, City 59', 4804.00, 5),
(161, 'WujKONELV', 'imrJaCdHr', '03461403033', 'Street 407, City 33', 3178.00, 5),
(162, 'bDtwBa', 'vlGKzroC', '03844308400', 'Street 85, City 41', 137.00, 5),
(163, 'fUOnpLmH', 'ZijCDxkwH', '03642768647', 'Street 86, City 18', 2702.00, 2),
(164, 'jsuNNrea', 'BIaGAvmPQ', '03654382582', 'Street 439, City 36', 4424.00, 5),
(165, 'tavwcwmGy', 'UJjPROx', '03690200201', 'Street 26, City 1', 1811.00, 5),
(166, 'tUMkuChb', 'ntUZgOMx', '03106288577', 'Street 433, City 86', 3896.00, 5),
(167, 'KQGyuZbp', 'HObwQ', '03394057050', 'Street 7, City 46', 2658.00, 2),
(168, 'xMfBXkv', 'WVLaHuV', '03819955909', 'Street 77, City 77', 2047.00, 5),
(169, 'eBqRv', 'urAXqYFOGj', '03465848918', 'Street 177, City 9', 4753.00, 2),
(170, 'zoPQnezwn', 'fCiVxg', '03571045478', 'Street 435, City 62', 4354.00, 5),
(171, 'zNAUpZhuI', 'kAWwIWmJm', '03604216053', 'Street 63, City 72', 2239.00, 2),
(172, 'uwMUrVfbgq', 'KuGSt', '03576521306', 'Street 116, City 70', 3369.00, 5),
(173, 'UePRzrliLW', 'hQZcpasjFj', '03734473063', 'Street 237, City 27', 3823.00, 2),
(174, 'RGPYaUh', 'tColNGRMY', '03942708321', 'Street 91, City 73', 4325.00, 5),
(175, 'qttgMMSgdx', 'YbCaR', '03932610856', 'Street 250, City 74', 3971.00, 5),
(176, 'xfMZU', 'fXFdRB', '03865983674', 'Street 426, City 91', 3742.00, 5),
(177, 'PeJkPOD', 'AcWKKJRqq', '03835812732', 'Street 174, City 47', 916.00, 2),
(178, 'ccejX', 'FzFNKm', '03516965258', 'Street 469, City 94', 4005.00, 5),
(179, 'IWzHTi', 'eTkPQb', '03256541282', 'Street 139, City 24', 1473.00, 2),
(180, 'vntMtVDrs', 'hTNFWBraCl', '03500937442', 'Street 317, City 52', 1079.00, 2),
(181, 'coNnV', 'PllwXpVZL', '03426526785', 'Street 190, City 46', 4692.00, 2),
(182, 'ymhtqdDq', 'itlpTjWW', '03366124238', 'Street 286, City 1', 1753.00, 2),
(183, 'dcKhVvAGH', 'oJWLdUCd', '03263614131', 'Street 206, City 17', 1165.00, 2),
(184, 'jdUGiQe', 'zIOwpBSRNo', '03870821457', 'Street 104, City 23', 1494.00, 2),
(185, 'vjAYC', 'olUciI', '03777924142', 'Street 54, City 71', 3935.00, 5),
(186, 'AJTFJ', 'exYoRYXcXi', '03129105017', 'Street 432, City 20', 1005.00, 5),
(187, 'qfDJBT', 'WFVSFHosg', '03734684938', 'Street 326, City 4', 4626.00, 2),
(188, 'rmCZXiMaD', 'bZlHMyp', '03868891173', 'Street 173, City 47', 3038.00, 2),
(189, 'XooLbba', 'LjAeGidcpI', '03432939926', 'Street 115, City 67', 716.00, 2),
(190, 'pbQlahoMD', 'DgnpT', '03446092974', 'Street 303, City 65', 2558.00, 5),
(191, 'nYlibg', 'aIsqe', '03511237979', 'Street 119, City 31', 1414.00, 5),
(192, 'YtYDIXmNr', 'XSiuDM', '03836534974', 'Street 475, City 23', 1364.00, 5),
(193, 'qrYjy', 'sdNtDBzLeP', '03239784398', 'Street 394, City 70', 2249.00, 2),
(194, 'KIfbQLH', 'SDhZkyn', '03357292336', 'Street 301, City 63', 2641.00, 2),
(195, 'GuvmEsmxfQ', 'yxwLXLYfP', '03613853299', 'Street 210, City 72', 2693.00, 2),
(196, 'YmuBD', 'QfqJB', '03180786083', 'Street 80, City 31', 4647.00, 5),
(197, 'LdrGDK', 'gnPWvpmV', '03464756558', 'Street 72, City 40', 467.00, 5),
(198, 'nqfUVmjsgv', 'vhfkn', '03936160402', 'Street 441, City 59', 4997.00, 5),
(199, 'yzUliVAkOJ', 'ZKYhEuubfF', '03815669771', 'Street 401, City 98', 4953.00, 2),
(200, 'CRnSrqcGb', 'NySlCj', '03428530268', 'Street 333, City 7', 259.00, 5),
(201, 'maNFt', 'OTQecvsGu', '03798096000', 'Street 94, City 48', 4496.00, 5),
(202, 'CwAeO', 'CWBML', '03318277731', 'Street 189, City 54', 225.00, 2),
(203, 'KfMFMjaZFa', 'KgdcFrYXfK', '03143525973', 'Street 491, City 89', 3553.00, 5),
(204, 'gKtkfUWCt', 'hBlqWpSL', '03261243945', 'Street 470, City 22', 1356.00, 2),
(205, 'MoQuxKtmM', 'RlvMusCIvY', '03940417395', 'Street 217, City 91', 263.00, 5),
(206, 'NxWSrEgI', 'qNNNwJFmC', '03642384775', 'Street 141, City 51', 2340.00, 2),
(207, 'vllUCMBqJ', 'lsqlepilu', '03988849014', 'Street 250, City 80', 2726.00, 2),
(208, 'TNFdjKUlui', 'GjlJnhb', '03460582511', 'Street 394, City 86', 840.00, 2),
(209, 'rjXPRFRFSC', 'BODuu', '03605833006', 'Street 39, City 22', 749.00, 5),
(210, 'NciJhKPjl', 'EtJsU', '03335417159', 'Street 101, City 91', 1104.00, 5),
(211, 'sqdRHs', 'Vafvzu', '03961463856', 'Street 39, City 91', 592.00, 5),
(212, 'tkHtPViMrp', 'TKGrsk', '03312350524', 'Street 93, City 61', 4228.00, 2),
(213, 'iQCTjHIJ', 'jSqKMZ', '03505464161', 'Street 391, City 52', 3979.00, 2),
(214, 'SPxwIIeVl', 'UATDU', '03675498772', 'Street 165, City 29', 4831.00, 5),
(215, 'CeCTg', 'kXLhVa', '03572910087', 'Street 219, City 43', 3315.00, 2),
(216, 'vtmjHKMS', 'jUtHd', '03348159451', 'Street 17, City 59', 4607.00, 5),
(217, 'ucoxLeo', 'FKYubXkF', '03420153309', 'Street 389, City 51', 1727.00, 2),
(218, 'nlRzZEJRxu', 'gsWUkqBm', '03128276715', 'Street 149, City 57', 4466.00, 5),
(219, 'HKiBgR', 'roSGjV', '03346141237', 'Street 457, City 80', 1903.00, 5),
(220, 'vxzhkQA', 'OWKjLdkdmE', '03750506032', 'Street 290, City 29', 4045.00, 2),
(221, 'CRtWGR', 'HWhhkdzu', '03928281157', 'Street 296, City 40', 2363.00, 2),
(222, 'MoNHhhCjD', 'KoYrnJt', '03430423040', 'Street 50, City 38', 3880.00, 5),
(223, 'zwdEPQg', 'agNDzolM', '03667089860', 'Street 420, City 15', 2740.00, 2),
(224, 'KrYTkZfiY', 'TkHgE', '03784351126', 'Street 306, City 100', 4285.00, 5),
(225, 'SNeykjISw', 'rAnSEHTfZn', '03509525321', 'Street 404, City 31', 4067.00, 2),
(226, 'JNZhxpid', 'pvxQNQQo', '03787402110', 'Street 407, City 97', 3771.00, 2),
(227, 'AJNfwardP', 'umHBx', '03938259045', 'Street 446, City 75', 2037.00, 5),
(228, 'HUPxmT', 'rgBRd', '03221654388', 'Street 241, City 23', 2163.00, 2),
(229, 'AEKHL', 'pdmMEpEqU', '03894965385', 'Street 116, City 66', 2343.00, 5),
(230, 'bvRTZnYgZ', 'UdYXe', '03497413793', 'Street 164, City 66', 3450.00, 5),
(231, 'kEmCLfhz', 'UsrTNt', '03147863488', 'Street 226, City 55', 4975.00, 5),
(232, 'lcYrRXoNLm', 'mrViSOKOr', '03616053588', 'Street 462, City 92', 4220.00, 5),
(233, 'LnvnSlt', 'XhVjLZnF', '03334749469', 'Street 441, City 48', 2495.00, 5),
(234, 'GGunjWHAFI', 'HKVFzw', '03785894804', 'Street 242, City 81', 414.00, 2),
(235, 'YXOclKWEVE', 'oZxdBRf', '03623664198', 'Street 384, City 71', 2786.00, 2),
(236, 'bzCyJpPUDN', 'WrPKWTzLUM', '03151208519', 'Street 308, City 74', 173.00, 2),
(237, 'VriAwfLR', 'JYfAmZan', '03903102326', 'Street 322, City 83', 2874.00, 5),
(238, 'gZBslMdC', 'XlJDwCEiQ', '03664166588', 'Street 156, City 44', 904.00, 2),
(239, 'voHsEx', 'pQBxFUJY', '03131201282', 'Street 438, City 60', 3594.00, 2),
(240, 'lMHFeiPx', 'TMcYW', '03243810738', 'Street 282, City 20', 4808.00, 5),
(241, 'bluPRdysz', 'TJtOwsBFcE', '03307695953', 'Street 344, City 73', 3698.00, 5),
(242, 'oslJef', 'VuSjeEsKzU', '03793314467', 'Street 203, City 24', 1112.00, 5),
(243, 'mhFzOhnCwg', 'ZxTMrC', '03176495970', 'Street 260, City 100', 1103.00, 2),
(244, 'NDqckNVo', 'XJbCA', '03896266108', 'Street 255, City 70', 555.00, 5),
(245, 'TkPnngZlaQ', 'sKYPuUUadw', '03660234234', 'Street 7, City 98', 1371.00, 2),
(246, 'SEoKDkyUR', 'UWiIvhURa', '03589689090', 'Street 395, City 49', 1951.00, 2),
(247, 'mKosXjm', 'usPcwbl', '03116923837', 'Street 200, City 74', 2881.00, 5),
(248, 'kiWqISTN', 'XEUGg', '03248404184', 'Street 278, City 80', 717.00, 5),
(249, 'TPlLUjcO', 'LqOfHAHd', '03554503947', 'Street 70, City 80', 1463.00, 5),
(250, 'qjRXWFRi', 'egcgn', '03461664898', 'Street 364, City 37', 31.00, 5),
(251, 'BfqyS', 'XIIIXd', '03654714293', 'Street 220, City 80', 3836.00, 2),
(252, 'IrfiTlqj', 'PMTXfB', '03941063339', 'Street 163, City 78', 2875.00, 5),
(253, 'yjbxWogr', 'yFzVcwR', '03441885281', 'Street 497, City 63', 599.00, 2),
(254, 'xNNRIc', 'GDOUly', '03891492820', 'Street 453, City 87', 202.00, 5),
(255, 'CIDEr', 'kVuDQ', '03435908918', 'Street 146, City 23', 4711.00, 2),
(256, 'CbEVkrCrqk', 'yjEQH', '03350299781', 'Street 153, City 24', 3358.00, 2),
(257, 'bqaBuHpN', 'WZZZdDQm', '03384184430', 'Street 356, City 27', 3107.00, 5),
(258, 'zYiNzJ', 'whjMN', '03828883741', 'Street 66, City 50', 2827.00, 5),
(259, 'sPelabW', 'HcdYcLk', '03510600467', 'Street 155, City 47', 1929.00, 5),
(260, 'DAXMUECT', 'OYcPA', '03113294050', 'Street 134, City 78', 4588.00, 2),
(261, 'BEiajtaDY', 'UtwMLQIK', '03126394073', 'Street 185, City 74', 3843.00, 2),
(262, 'jGXdtJ', 'ZGFGpGjaJ', '03749725122', 'Street 261, City 39', 60.00, 2),
(263, 'NRXgn', 'KBjgW', '03491944076', 'Street 26, City 55', 3788.00, 2),
(264, 'dlpJYqBb', 'MhoumolqNQ', '03631058600', 'Street 447, City 69', 4553.00, 2),
(265, 'cpWqcwo', 'DEgEqK', '03486948010', 'Street 266, City 54', 271.00, 5),
(266, 'pvRtvTB', 'BROZyxLb', '03155404129', 'Street 398, City 87', 1766.00, 5),
(267, 'phEWpLp', 'BJAFgQvafA', '03837283971', 'Street 279, City 71', 4940.00, 2),
(268, 'RQcghHRy', 'DWqqQ', '03641767242', 'Street 398, City 80', 537.00, 2),
(269, 'siCrCJ', 'yIYZPkMbX', '03387234292', 'Street 160, City 98', 974.00, 2),
(270, 'bMOCTnelnx', 'ahRwhZwsW', '03904756101', 'Street 191, City 11', 4178.00, 2),
(271, 'SHurGm', 'cSuAVzwZs', '03789618789', 'Street 450, City 78', 3530.00, 5),
(272, 'nfLfFZkKz', 'psegbQZgNY', '03310296133', 'Street 218, City 86', 286.00, 5),
(273, 'zKnXdurYs', 'mUnpn', '03890981478', 'Street 39, City 80', 2693.00, 5),
(274, 'QGvNvAz', 'yKlDMohJhj', '03352359532', 'Street 488, City 91', 1679.00, 5),
(275, 'mEOlMvsqff', 'HSAZuxl', '03648012204', 'Street 141, City 13', 2851.00, 5),
(276, 'audAicXC', 'UCFzYSh', '03923090029', 'Street 370, City 91', 1750.00, 2),
(277, 'DAfosEI', 'iHwEIoo', '03941048879', 'Street 305, City 28', 4530.00, 2),
(278, 'VgBhkA', 'hNOqL', '03453979463', 'Street 452, City 85', 2529.00, 5),
(279, 'FxaVQbz', 'mLucTq', '03684282877', 'Street 8, City 42', 3352.00, 5),
(280, 'GUzbMeIrDI', 'mkKlQsc', '03492674348', 'Street 245, City 8', 2090.00, 5),
(281, 'MDtnESwpB', 'zuueqt', '03661688551', 'Street 280, City 59', 103.00, 5),
(282, 'BqeMSUG', 'bhhArOvm', '03459184828', 'Street 456, City 71', 3304.00, 2),
(283, 'oZgAWMK', 'zbrSY', '03538663609', 'Street 388, City 61', 1051.00, 2),
(284, 'blMqBbO', 'xccGKV', '03648684449', 'Street 478, City 61', 4716.00, 2),
(285, 'hAsis', 'BpKDRXPiAh', '03245076495', 'Street 332, City 6', 3116.00, 2),
(286, 'sOSqgFN', 'hvwoSA', '03689518358', 'Street 479, City 10', 4151.00, 5),
(287, 'NysaNHe', 'xFOtSDMV', '03315830280', 'Street 475, City 29', 1215.00, 5),
(288, 'XbfQeNmJgk', 'dvhePHoU', '03871110907', 'Street 331, City 54', 2251.00, 2),
(289, 'QeTFkKMkF', 'LQlaUd', '03351055773', 'Street 167, City 61', 934.00, 2),
(290, 'qIjuMe', 'hzZrMKGEXN', '03387235270', 'Street 103, City 43', 3044.00, 2),
(291, 'oiOwtD', 'MblRs', '03258599123', 'Street 336, City 78', 1794.00, 2),
(292, 'ITyZX', 'vQXpHuuy', '03198103371', 'Street 284, City 77', 1049.00, 5),
(293, 'iFIFsZrgcQ', 'VZmEtjQ', '03163542199', 'Street 431, City 95', 2870.00, 2),
(294, 'zlMQAyxf', 'sMLaEhyp', '03693937957', 'Street 422, City 2', 4049.00, 5),
(295, 'TecRAfu', 'cnqojyHL', '03806037827', 'Street 493, City 2', 3910.00, 2),
(296, 'eWEMUgJr', 'Vehbdz', '03533787014', 'Street 301, City 77', 4358.00, 2),
(297, 'FuCGnWB', 'IooqNqEw', '03355043228', 'Street 214, City 74', 3495.00, 2),
(298, 'eEloXvptP', 'PSyjPZd', '03406469753', 'Street 57, City 69', 779.00, 5),
(299, 'zYinGDKNys', 'SWHumWXS', '03812952363', 'Street 191, City 45', 1599.00, 2),
(300, 'NqBNWPx', 'NNBaodRyA', '03739040387', 'Street 115, City 63', 3566.00, 5),
(301, 'hkPXE', 'zzYGU', '03303081387', 'Street 71, City 11', 691.00, 2),
(302, 'QiMVwnv', 'VTSisBRlV', '03167333124', 'Street 160, City 15', 3955.00, 2),
(303, 'TFSUS', 'tcxzC', '03375985101', 'Street 334, City 70', 4171.00, 5),
(304, 'vKLMC', 'ntTYE', '03409055104', 'Street 399, City 45', 1296.00, 2),
(305, 'RQZpkCZdRD', 'ltqdSdVR', '03739202754', 'Street 6, City 32', 3370.00, 2),
(306, 'oaQgacCbg', 'pWpuiej', '03307710586', 'Street 491, City 13', 3430.00, 5),
(307, 'kqqBaQwsk', 'zqEDyXx', '03744985932', 'Street 40, City 89', 1708.00, 2),
(308, 'vALbZTc', 'gHbpZpokmS', '03134278920', 'Street 84, City 93', 4979.00, 2),
(309, 'LwYrBNXNGD', 'ZNvqi', '03374356034', 'Street 130, City 72', 1553.00, 2),
(310, 'OtvLEJSB', 'uucHSFMEBj', '03594100728', 'Street 84, City 92', 618.00, 5),
(311, 'imCNnY', 'uRSSLpIR', '03221225602', 'Street 338, City 58', 68.00, 2),
(312, 'npIpXrXrvE', 'QdODTTm', '03131972171', 'Street 412, City 96', 3941.00, 2),
(313, 'XNZKqkle', 'EbQiN', '03823544573', 'Street 108, City 21', 1474.00, 2),
(314, 'kuYZonFm', 'qOVksZruPC', '03202795196', 'Street 107, City 5', 472.00, 5),
(315, 'GPGlmMew', 'pxEMtMD', '03944339736', 'Street 416, City 49', 7.00, 2),
(316, 'muSyFltV', 'aoDMxOAceN', '03560934139', 'Street 322, City 96', 1037.00, 5),
(317, 'ZeOETXkzR', 'uwvVZvdzp', '03558358614', 'Street 391, City 52', 1431.00, 5),
(318, 'QmuqvO', 'hZdiBYObtd', '03747807873', 'Street 477, City 28', 3659.00, 5),
(319, 'CnyzJ', 'YLyNyfGUq', '03930422496', 'Street 164, City 48', 2214.00, 2),
(320, 'oXFtsERgeJ', 'zZtUxnIeTY', '03251716804', 'Street 337, City 97', 4526.00, 5),
(321, 'vdAYbYs', 'AhaZl', '03640432045', 'Street 75, City 71', 1972.00, 2),
(322, 'nyueMn', 'gfxNjP', '03637111778', 'Street 89, City 73', 33.00, 2),
(323, 'xpBAZ', 'TXXbtvxM', '03872475829', 'Street 217, City 95', 2380.00, 2),
(324, 'zZSJLpwAp', 'uWrOda', '03767818509', 'Street 98, City 94', 2077.00, 2),
(325, 'nJQVs', 'tVSmc', '03267865689', 'Street 204, City 77', 3239.00, 2),
(326, 'VQqIWUh', 'LUqzUBzjx', '03674173116', 'Street 74, City 18', 103.00, 2),
(327, 'qmfupeS', 'hlXCTa', '03601715404', 'Street 412, City 43', 3150.00, 2),
(328, 'GbwEouA', 'LFvtUVI', '03923409350', 'Street 62, City 87', 2639.00, 5),
(329, 'VfvdKlTQG', 'uomZMZsSU', '03363399816', 'Street 465, City 72', 519.00, 5),
(330, 'tUcedCmY', 'dwSaMeDOF', '03628557219', 'Street 262, City 4', 522.00, 2),
(331, 'uTLsatCTwe', 'DbhdhqokcC', '03596074996', 'Street 183, City 25', 1825.00, 2),
(332, 'KyNaLiOZ', 'djbSLVktO', '03340197763', 'Street 271, City 39', 3946.00, 5),
(333, 'jtveHFmp', 'MnuQJq', '03468234149', 'Street 439, City 3', 98.00, 5),
(334, 'oJVlrhj', 'FNhkj', '03498457701', 'Street 236, City 59', 2632.00, 5),
(335, 'peoOGcOWMI', 'MTlyUCRX', '03755239009', 'Street 408, City 44', 4780.00, 5),
(336, 'ONbLW', 'PwtSErvAAl', '03544703449', 'Street 187, City 8', 1824.00, 2),
(337, 'RlIvNEETsu', 'cvNknWszPk', '03932524722', 'Street 66, City 80', 3951.00, 5),
(338, 'jXMdGI', 'wqXjSMzRiw', '03795521439', 'Street 472, City 82', 859.00, 2),
(339, 'IpnNvDAD', 'ZzWINcSqW', '03555164251', 'Street 94, City 82', 3562.00, 5),
(340, 'gDoyxx', 'mmHzexQUu', '03326886440', 'Street 345, City 50', 2146.00, 5),
(341, 'GzvNGk', 'fVDCU', '03964733586', 'Street 139, City 49', 1095.00, 2),
(342, 'NIAQrUgElk', 'iAOsQy', '03687845361', 'Street 389, City 61', 2141.00, 2),
(343, 'YPpumpy', 'XyNffh', '03233548898', 'Street 203, City 54', 795.00, 5),
(344, 'FvAWxY', 'gzHsFHwj', '03160551535', 'Street 477, City 10', 3186.00, 5),
(345, 'KSmMC', 'pDCkRDgVYm', '03398165435', 'Street 38, City 69', 3110.00, 2),
(346, 'IURSu', 'ErOEdMU', '03166645423', 'Street 3, City 4', 2281.00, 5),
(347, 'OCmDef', 'EDagSHRR', '03754675775', 'Street 130, City 30', 4425.00, 5),
(348, 'IzxNDoUAkB', 'ZdxGYweb', '03447798571', 'Street 193, City 75', 1402.00, 5),
(349, 'hHIjC', 'BgzvbAu', '03447496073', 'Street 283, City 45', 4769.00, 5),
(350, 'SdPFtPf', 'RismT', '03963708517', 'Street 498, City 39', 306.00, 5),
(351, 'yMfOu', 'NERPjMU', '03711144692', 'Street 266, City 86', 3323.00, 2),
(352, 'thnQBlW', 'NAPIxrzuB', '03538563755', 'Street 374, City 51', 1272.00, 2),
(353, 'eswyAJ', 'sUAOwdfl', '03323459442', 'Street 210, City 22', 3994.00, 2),
(354, 'uTpvN', 'wiDOqUFlDX', '03592314365', 'Street 372, City 60', 1358.00, 5),
(355, 'RcmXcv', 'CbsDruvrQ', '03869240654', 'Street 364, City 5', 2530.00, 5),
(356, 'umpsfkG', 'TCmJgs', '03501840191', 'Street 304, City 77', 2751.00, 5),
(357, 'ElptLGp', 'PWihOzH', '03742439950', 'Street 482, City 76', 2996.00, 5),
(358, 'TotoUVmZV', 'cEtoZIwGQi', '03658145267', 'Street 453, City 17', 2560.00, 2),
(359, 'ZrRGfxsl', 'SnsXqo', '03194751208', 'Street 320, City 100', 3424.00, 5),
(360, 'NGzyeWpoHU', 'xBRLe', '03419286142', 'Street 79, City 4', 2147.00, 5),
(361, 'LGysk', 'yLyLIzlTjS', '03356220128', 'Street 233, City 76', 1689.00, 2),
(362, 'kmGJOZh', 'VXGhv', '03962081283', 'Street 160, City 53', 4359.00, 5),
(363, 'UxIjbRPml', 'hUCIzKr', '03517976491', 'Street 127, City 24', 4726.00, 5),
(364, 'koQINoicTd', 'bsrcXf', '03534817522', 'Street 224, City 87', 3219.00, 5),
(365, 'YRtsgoBU', 'qfiVkfDqir', '03700401312', 'Street 159, City 23', 3123.00, 2),
(366, 'Ocnsvw', 'pPySCypkDr', '03134118739', 'Street 453, City 56', 4062.00, 5),
(367, 'xOIiZoF', 'dqInWTBhX', '03193603769', 'Street 269, City 57', 1368.00, 2),
(368, 'jZqeQXxXK', 'kOBNLvOiuB', '03370114459', 'Street 148, City 97', 429.00, 5),
(369, 'BxnLm', 'FUZhSfVzwO', '03748799208', 'Street 492, City 66', 1921.00, 2),
(370, 'jtqvBw', 'VxfgNBtD', '03339985806', 'Street 181, City 11', 3613.00, 5),
(371, 'DgTRaff', 'Mornpy', '03578820021', 'Street 279, City 26', 1260.00, 2),
(372, 'USRbCs', 'NlBLptW', '03442227529', 'Street 283, City 86', 4625.00, 5),
(373, 'mYCueMEKQk', 'MvYfgM', '03321721998', 'Street 290, City 9', 4390.00, 5),
(374, 'OLqhifmrig', 'rAuOheT', '03573936953', 'Street 294, City 67', 2781.00, 5),
(375, 'jsnWhGs', 'xVenILaDGl', '03986828740', 'Street 147, City 62', 2659.00, 2),
(376, 'GuJEm', 'VApqHD', '03332150749', 'Street 490, City 19', 1895.00, 2),
(377, 'aDAoDsUELz', 'YKIVB', '03550705940', 'Street 12, City 64', 2288.00, 5),
(378, 'UIRDeEp', 'OmSRDVl', '03541819229', 'Street 262, City 93', 3453.00, 2),
(379, 'mbspW', 'EdhUHsYqm', '03158253601', 'Street 320, City 46', 1268.00, 2),
(380, 'njHgdyahw', 'OrAvqGjGgD', '03553660470', 'Street 227, City 61', 518.00, 5),
(381, 'sgEdoRji', 'gvEJfXutk', '03886386911', 'Street 485, City 49', 449.00, 2),
(382, 'oMVGiQA', 'Zuolln', '03160033239', 'Street 456, City 40', 2612.00, 5),
(383, 'XeIUDKHIV', 'YZzrFvdHa', '03190574359', 'Street 402, City 52', 1243.00, 2),
(384, 'mKJThthN', 'fcdmCWy', '03396051463', 'Street 293, City 70', 3178.00, 5),
(385, 'ZIvGRO', 'FSncSMY', '03582408996', 'Street 132, City 85', 3852.00, 2),
(386, 'fYUcNVGd', 'copBGT', '03575943896', 'Street 400, City 77', 4743.00, 2),
(387, 'olleNUCqe', 'biIrt', '03189011330', 'Street 40, City 39', 1166.00, 5),
(388, 'evUTVH', 'kmKejWK', '03801565305', 'Street 73, City 13', 493.00, 5),
(389, 'KwIoUGLys', 'uQWpFhFp', '03230137636', 'Street 87, City 2', 627.00, 2),
(390, 'qXzkOIeakS', 'UTmsUHbU', '03711271420', 'Street 70, City 74', 1318.00, 2),
(391, 'kqHRO', 'AGuwgPySrU', '03512858233', 'Street 291, City 54', 4537.00, 5),
(392, 'hMwTYM', 'ewdvQfehsC', '03935919582', 'Street 490, City 26', 44.00, 2),
(393, 'eLdhMicgqn', 'cEHPUVBED', '03302620873', 'Street 453, City 24', 2242.00, 5),
(394, 'gEqBdm', 'AySNzYupmC', '03305699001', 'Street 454, City 9', 3770.00, 5),
(395, 'IRkzZ', 'LjSxyNIUU', '03677858753', 'Street 205, City 75', 2318.00, 2),
(396, 'hxOgnxS', 'PhYnDrg', '03944007625', 'Street 143, City 76', 148.00, 2),
(397, 'fcSYxtBjJ', 'cmfFpFmk', '03709225836', 'Street 341, City 62', 4.00, 5),
(398, 'CRipU', 'vNerQcZzm', '03716724799', 'Street 461, City 67', 469.00, 5),
(399, 'ceEWot', 'ByBfAmKSn', '03604761548', 'Street 65, City 88', 4581.00, 2),
(400, 'uHJPFTujh', 'lxuFNknnqu', '03484016694', 'Street 95, City 63', 3000.00, 5),
(401, 'DWyrqvd', 'LHvNFm', '03498122204', 'Street 309, City 74', 1925.00, 5),
(402, 'fjAvb', 'GPTzDTYA', '03925773307', 'Street 236, City 12', 2243.00, 2),
(403, 'buUQu', 'GnsWR', '03933417999', 'Street 247, City 33', 3129.00, 2),
(404, 'rAqNpJgng', 'XmieRa', '03622934954', 'Street 90, City 33', 560.00, 2),
(405, 'MCwdfXU', 'BSdRnpJ', '03713523197', 'Street 345, City 73', 3992.00, 2),
(406, 'sbLAs', 'NIeuRBS', '03936997912', 'Street 209, City 91', 4269.00, 5),
(407, 'eASUhhz', 'PAhdQ', '03910045745', 'Street 386, City 20', 1232.00, 5),
(408, 'cgZdH', 'hsDcVK', '03294264040', 'Street 329, City 83', 4912.00, 2),
(409, 'KtPceZG', 'ByoXALJuAd', '03436330477', 'Street 1, City 11', 3342.00, 2),
(410, 'jqeTjc', 'XRIGaqvV', '03932643649', 'Street 194, City 10', 4014.00, 2),
(411, 'iuGiQxhcxl', 'MDfTEHa', '03962292891', 'Street 399, City 25', 1804.00, 5),
(412, 'oLNtpZC', 'HhlbyvWHPN', '03591105313', 'Street 169, City 48', 4580.00, 5),
(413, 'PIErRc', 'eFSUY', '03242522773', 'Street 181, City 22', 3251.00, 5),
(414, 'XSGdCuAdfv', 'KlWTs', '03327379655', 'Street 300, City 37', 801.00, 2),
(415, 'WkWwKkp', 'PefYGKPcn', '03954609170', 'Street 133, City 47', 1676.00, 2),
(416, 'xHeuIaLt', 'oZyEQSKF', '03125884173', 'Street 489, City 86', 3713.00, 2),
(417, 'UliuAkf', 'iTanmqwCG', '03807341845', 'Street 305, City 23', 676.00, 2),
(418, 'PCQsTSAJ', 'vmimH', '03905012372', 'Street 472, City 30', 4386.00, 2),
(419, 'TSXqeZBDdJ', 'WpzaZOtDBI', '03190452491', 'Street 77, City 6', 114.00, 5),
(420, 'KlcFP', 'KjwlY', '03128771128', 'Street 66, City 79', 3584.00, 2),
(421, 'TfZtNDQdlF', 'ppkiGPhhzQ', '03259779758', 'Street 405, City 70', 3628.00, 2),
(422, 'wpCwyaUay', 'bOrNbLGHU', '03612364599', 'Street 142, City 8', 2957.00, 2),
(423, 'iLuXBp', 'FXqjKQWq', '03671311312', 'Street 342, City 49', 84.00, 5),
(424, 'LGvvYIjWdr', 'kYRsBxKT', '03267258119', 'Street 384, City 99', 2058.00, 5),
(425, 'nCmEkDCbbo', 'bihRIJY', '03496713279', 'Street 162, City 99', 2768.00, 5),
(426, 'veQNfxpaT', 'drXwf', '03557384943', 'Street 325, City 4', 2946.00, 2),
(427, 'FOYAWNjIvV', 'HnjkZpC', '03201572886', 'Street 427, City 47', 1611.00, 2),
(428, 'kYUfOSlgms', 'MnSuehNn', '03923086316', 'Street 488, City 5', 4766.00, 2),
(429, 'yQnXrlj', 'sibmpWomu', '03898502709', 'Street 1, City 91', 345.00, 2),
(430, 'XJGsZgo', 'eniVqsgO', '03222489396', 'Street 180, City 97', 3691.00, 5),
(431, 'bbTVZr', 'vYuOY', '03240241714', 'Street 317, City 70', 1304.00, 2),
(432, 'SFycW', 'tippowEJ', '03205482108', 'Street 296, City 32', 4967.00, 2),
(433, 'SVgDE', 'PsuJKVU', '03669243410', 'Street 290, City 90', 2525.00, 5),
(434, 'ajCKJq', 'xQQBq', '03245528769', 'Street 150, City 25', 384.00, 5),
(435, 'QQuoeQ', 'DhsmAuMW', '03596373504', 'Street 231, City 25', 4113.00, 2),
(436, 'niOnBNIUJg', 'amvwydEsqN', '03336113689', 'Street 317, City 21', 293.00, 2),
(437, 'poGeCWo', 'OkBJNNMJ', '03257269795', 'Street 440, City 22', 2647.00, 5),
(438, 'BDEuYyQx', 'gwseb', '03354120503', 'Street 451, City 48', 733.00, 5),
(439, 'uooeco', 'zHivZepHqV', '03796095654', 'Street 143, City 28', 4521.00, 2),
(440, 'SknCs', 'pQqLvlc', '03733055161', 'Street 227, City 46', 1330.00, 5),
(441, 'JMguG', 'rAqQmtFcjt', '03214621044', 'Street 430, City 58', 1189.00, 2),
(442, 'ELxxVm', 'BMnnCuKt', '03709159889', 'Street 17, City 28', 1452.00, 5),
(443, 'jfIEVwhxzB', 'zYxrZ', '03362990657', 'Street 216, City 49', 4785.00, 5),
(444, 'FTlUHxNNT', 'sPBEOZAk', '03313107184', 'Street 46, City 26', 4333.00, 5),
(445, 'VYedM', 'xXyQYSHCOc', '03348314810', 'Street 500, City 44', 1467.00, 2),
(446, 'laQnZYGF', 'aQKwuTj', '03591103248', 'Street 104, City 61', 1509.00, 2),
(447, 'EexwQiO', 'MGeiUazKnQ', '03113120295', 'Street 4, City 72', 2944.00, 2),
(448, 'Igakl', 'ucvAs', '03593069658', 'Street 83, City 40', 3009.00, 2),
(449, 'ShwwFaTkHN', 'FeOGZgZT', '03582946824', 'Street 265, City 30', 1656.00, 2),
(450, 'Ahpkpm', 'fCjrwjc', '03753271138', 'Street 298, City 24', 769.00, 5),
(451, 'utEIm', 'tCmKaeii', '03391198504', 'Street 441, City 60', 1070.00, 2),
(452, 'vdiww', 'bEMRsf', '03431568233', 'Street 260, City 57', 3207.00, 2),
(453, 'UVYcyxjM', 'TdtkBqyRB', '03660060834', 'Street 367, City 20', 403.00, 2),
(454, 'ujFJCgMVoG', 'VTNLDXTHlQ', '03654485110', 'Street 367, City 76', 3361.00, 5),
(455, 'vHxPVgv', 'zoCBWv', '03625081168', 'Street 380, City 23', 2531.00, 2),
(456, 'Jpdpr', 'SdnDf', '03165163210', 'Street 72, City 42', 3843.00, 2),
(457, 'cdsAD', 'ZOtPf', '03202715652', 'Street 160, City 59', 2834.00, 2),
(458, 'lvVADyOx', 'sUZBMg', '03544064617', 'Street 499, City 92', 507.00, 2),
(459, 'ELZQr', 'SAApLVXc', '03488405519', 'Street 69, City 89', 3124.00, 5),
(460, 'gkBpRDK', 'JSomHOKUp', '03150058852', 'Street 52, City 93', 892.00, 2),
(461, 'cvUOxtNOTq', 'FODLDXE', '03305754191', 'Street 349, City 90', 1706.00, 5),
(462, 'OLLsDasskd', 'fCDmGcBO', '03844143891', 'Street 464, City 92', 4816.00, 2),
(463, 'jWbONycUt', 'VoxnGz', '03790884592', 'Street 39, City 3', 2253.00, 2),
(464, 'Qefglw', 'fHLmCOq', '03945616565', 'Street 396, City 53', 1447.00, 2),
(465, 'zJcVJHEW', 'HGvbMAv', '03211568932', 'Street 130, City 93', 193.00, 5),
(466, 'FjfxpB', 'LzvXc', '03444196665', 'Street 440, City 56', 861.00, 5),
(467, 'oWYEHXDE', 'ddFnLMBdic', '03191890312', 'Street 209, City 11', 3816.00, 2),
(468, 'hXNqbhquD', 'wYFkR', '03479402478', 'Street 384, City 57', 1980.00, 5),
(469, 'EHXnUfHd', 'UsgbzgMco', '03516566192', 'Street 472, City 38', 4360.00, 2),
(470, 'BQQJsKS', 'bTvvOXuvWa', '03288675334', 'Street 251, City 96', 2403.00, 5),
(471, 'zQBvO', 'HHlZNz', '03832898460', 'Street 295, City 55', 3491.00, 5),
(472, 'yxfqK', 'mpsJhmBUU', '03897526328', 'Street 494, City 78', 1284.00, 2),
(473, 'GDMnFRh', 'qsbbQ', '03614844187', 'Street 406, City 46', 3978.00, 5),
(474, 'DYGoad', 'YlckTuKjk', '03702984874', 'Street 168, City 80', 842.00, 2),
(475, 'Pbkye', 'aGFbTi', '03517649542', 'Street 459, City 64', 1985.00, 2),
(476, 'MlxnZbLSP', 'qpsQjkTk', '03886102027', 'Street 225, City 43', 3519.00, 2),
(477, 'WXGVeRtr', 'SUrjt', '03188935324', 'Street 72, City 56', 2786.00, 2),
(478, 'IngUaZsxUr', 'kaKDDlVrho', '03883342047', 'Street 478, City 49', 4286.00, 5),
(479, 'sdFmv', 'bAdHDK', '03948014039', 'Street 54, City 80', 3092.00, 5),
(480, 'oTrzwREk', 'hUINWONvS', '03388478233', 'Street 44, City 47', 2181.00, 2),
(481, 'cFfNYiWm', 'jVwYs', '03144588182', 'Street 307, City 90', 1310.00, 5),
(482, 'tnfeUMF', 'CFuLqWrQC', '03517435906', 'Street 346, City 16', 1305.00, 5),
(483, 'oMErg', 'vQXwm', '03850949979', 'Street 353, City 96', 1853.00, 5),
(484, 'vtsbDLyhOz', 'cgKIsqNXj', '03751748695', 'Street 332, City 81', 477.00, 5),
(485, 'CqWBoaPjuK', 'RqCekgkyy', '03198835019', 'Street 321, City 81', 1877.00, 2),
(486, 'CnxxGx', 'IOKBp', '03168421377', 'Street 29, City 57', 1551.00, 5),
(487, 'igGcwGAF', 'wmBzmiggb', '03840133869', 'Street 95, City 5', 2343.00, 2),
(488, 'JycxjYJ', 'zKdkz', '03610677405', 'Street 139, City 52', 927.00, 5),
(489, 'cbkbTNwZlp', 'qcChe', '03521269078', 'Street 265, City 95', 505.00, 5),
(490, 'QlHZm', 'dhexo', '03108462513', 'Street 433, City 59', 819.00, 2),
(491, 'WYSxev', 'NakNy', '03215121040', 'Street 85, City 31', 3497.00, 2),
(492, 'iOiHNPQWo', 'MkiiWZ', '03787361048', 'Street 95, City 78', 2449.00, 2),
(493, 'MQteZe', 'yuGdxk', '03444004534', 'Street 486, City 75', 3579.00, 5),
(494, 'iaJKRYTon', 'qfSWIsnYT', '03396595312', 'Street 98, City 49', 611.00, 5),
(495, 'AecLvXCUW', 'fxHNEP', '03729152810', 'Street 319, City 57', 901.00, 2),
(496, 'UiDges', 'hfKVkd', '03461947823', 'Street 234, City 52', 3328.00, 2),
(497, 'cdNQI', 'oiewyHA', '03304421866', 'Street 493, City 2', 1958.00, 2),
(498, 'yLCAYPEVdC', 'wYktI', '03605123778', 'Street 245, City 65', 624.00, 5),
(499, 'PwLFakWlmh', 'luljUeVi', '03796567104', 'Street 126, City 30', 3833.00, 5),
(500, 'cVZXnQ', 'JnTJyVr', '03433511824', 'Street 309, City 88', 4715.00, 2),
(501, 'nfyTp', 'hymgjEtys', '03663779345', 'Street 55, City 51', 2599.00, 2),
(502, 'vQabzuPtlN', 'IxYGsVYu', '03156260853', 'Street 434, City 24', 1083.00, 2),
(503, 'ZoGBcbx', 'GOxIcPFa', '03525517900', 'Street 478, City 80', 4113.00, 5),
(504, 'dgDyhBCYt', 'wYOTqo', '03758513050', 'Street 12, City 68', 102.00, 5),
(505, 'cpIZm', 'kRURO', '03929464458', 'Street 450, City 25', 2831.00, 5),
(506, 'XtlSdNx', 'cCEuaDZU', '03914976803', 'Street 144, City 37', 479.00, 5),
(507, 'jZAalowuPU', 'jJVHAQqKE', '03727523291', 'Street 173, City 75', 830.00, 2),
(508, 'ediFXYJf', 'OnuMjnYrEF', '03981981931', 'Street 188, City 82', 3260.00, 5),
(509, 'FPLATAzyL', 'OYucj', '03718541527', 'Street 23, City 41', 4836.00, 2),
(510, 'VlsSobfo', 'csGsGHnx', '03787125008', 'Street 335, City 53', 3467.00, 2),
(511, 'PvsaksZlnM', 'nvmWZGU', '03938979593', 'Street 450, City 21', 986.00, 2),
(512, 'eDVmno', 'vvEvAwq', '03577292675', 'Street 165, City 67', 2834.00, 5),
(513, 'ozTOczRfF', 'VOCkgue', '03593165274', 'Street 315, City 22', 2609.00, 5),
(514, 'rxEnVcTtt', 'RYWaL', '03111839841', 'Street 460, City 96', 746.00, 2),
(515, 'cMdfyHoC', 'oXCBSgd', '03894344577', 'Street 129, City 20', 951.00, 2),
(516, 'kfjscpx', 'AlpwmKOVg', '03168733256', 'Street 34, City 56', 1220.00, 2),
(517, 'aseoPYvl', 'dPATFdOlc', '03811148492', 'Street 384, City 62', 298.00, 2),
(518, 'XnaGocU', 'KFoVcbNY', '03218972824', 'Street 284, City 3', 3555.00, 2),
(519, 'YvRTMMR', 'CfLYqz', '03152169470', 'Street 74, City 4', 1255.00, 2),
(520, 'JwUYPAQ', 'keRUV', '03540918225', 'Street 429, City 12', 629.00, 5),
(521, 'IpRmY', 'MuGSe', '03246095370', 'Street 90, City 25', 4201.00, 5),
(522, 'DrfOLhr', 'TbUzZKXFtu', '03241227841', 'Street 415, City 23', 4688.00, 2),
(523, 'nDYCbLia', 'JewIp', '03929999312', 'Street 257, City 51', 786.00, 2),
(524, 'lzMmJgdI', 'XqzNUqxZYR', '03821768230', 'Street 11, City 76', 1171.00, 2),
(525, 'OIsbQFz', 'AgmUwEgy', '03564263006', 'Street 432, City 43', 2707.00, 2),
(526, 'isJVdt', 'xACgW', '03729707298', 'Street 360, City 58', 1633.00, 5),
(527, 'DcQILX', 'vWtmkFzeIu', '03363587961', 'Street 300, City 19', 3205.00, 5),
(528, 'DBVTnUSbnd', 'rPKeGZ', '03404937608', 'Street 215, City 72', 3992.00, 2),
(529, 'pbudYQivTi', 'HdCCqbqsq', '03330987706', 'Street 409, City 92', 4008.00, 2),
(530, 'aqtIFbF', 'cnnjdgKA', '03690953251', 'Street 411, City 25', 4200.00, 5),
(531, 'xvgmlO', 'iQDtNr', '03531566396', 'Street 229, City 96', 3469.00, 2),
(532, 'CgnKQdZAy', 'voOTOzGV', '03247281752', 'Street 463, City 39', 3038.00, 2),
(533, 'YmIjDzkxmF', 'SRnFmBX', '03349287909', 'Street 490, City 93', 261.00, 2),
(534, 'shRlbErpZu', 'LCOQQxgyk', '03324381817', 'Street 406, City 42', 2459.00, 2),
(535, 'peGNCuvl', 'rcsUAGhF', '03308258984', 'Street 4, City 6', 3811.00, 2),
(536, 'CaNvg', 'gAhiZ', '03229480267', 'Street 135, City 21', 3900.00, 5),
(537, 'tukQSbC', 'sBKslhc', '03418693745', 'Street 168, City 43', 1480.00, 2),
(538, 'rbjjqhFv', 'IIMxjdj', '03243801720', 'Street 124, City 87', 972.00, 2),
(539, 'UNnqx', 'MaAjvl', '03311238351', 'Street 223, City 61', 2824.00, 5),
(540, 'OnGzowAGcl', 'iGdyslfWI', '03961470078', 'Street 84, City 42', 4798.00, 2),
(541, 'yvQglqz', 'fwcjPB', '03953607946', 'Street 419, City 84', 3526.00, 5),
(542, 'ZYrLHcwMk', 'LvqGDp', '03327648251', 'Street 326, City 28', 1428.00, 5),
(543, 'VpCwN', 'aErXjsbWp', '03328943424', 'Street 430, City 91', 3649.00, 5),
(544, 'bvKXfc', 'spFdma', '03288928174', 'Street 33, City 58', 2333.00, 5),
(545, 'WPptGXKm', 'fhZWpwJtQZ', '03927380335', 'Street 374, City 93', 3278.00, 2),
(546, 'whCtf', 'yXvGSHSBZb', '03156587096', 'Street 188, City 78', 4969.00, 5),
(547, 'jbVShRPV', 'dZUhWV', '03852042793', 'Street 364, City 83', 4388.00, 5),
(548, 'GWVeDJEjQC', 'eaFVg', '03862624527', 'Street 415, City 10', 1326.00, 2),
(549, 'euSrWO', 'JARhgAraI', '03916868685', 'Street 383, City 27', 1211.00, 2),
(550, 'WLFLjAbgN', 'LhsNAa', '03361205141', 'Street 222, City 36', 298.00, 2),
(551, 'xsrkvKr', 'bArxV', '03897967308', 'Street 421, City 72', 700.00, 5),
(552, 'xVkcSPonE', 'SuxAmkE', '03734861045', 'Street 25, City 51', 450.00, 5),
(553, 'KUtRIKlp', 'gcDFiLoUMr', '03791222919', 'Street 39, City 65', 4459.00, 2),
(554, 'RQuoyFhdYc', 'rZsmY', '03619444154', 'Street 315, City 90', 2112.00, 2),
(555, 'lBjmzUC', 'mRmvYwUR', '03687509578', 'Street 181, City 21', 2229.00, 2),
(556, 'sfxtFUIIA', 'VsWpWz', '03509225188', 'Street 342, City 68', 187.00, 2),
(557, 'FTnAO', 'KRmZwWWTRd', '03205869310', 'Street 148, City 13', 1678.00, 2),
(558, 'qYiYmzcD', 'CCPAsugz', '03671530027', 'Street 113, City 35', 2719.00, 5),
(559, 'eHaypW', 'ERThldjOkJ', '03526146846', 'Street 106, City 34', 4708.00, 2),
(560, 'ZFsYjBazsL', 'wQmOQsegu', '03379772228', 'Street 14, City 48', 3749.00, 2),
(561, 'pfwfk', 'ccFZvsTobY', '03981879643', 'Street 390, City 24', 2061.00, 2),
(562, 'unKefW', 'RJgcoPanQ', '03474658535', 'Street 122, City 85', 653.00, 5),
(563, 'JfKfoQc', 'fNjDXEDW', '03943051071', 'Street 235, City 36', 1511.00, 2),
(564, 'RrkqFgqA', 'WCXMMY', '03343070924', 'Street 345, City 34', 1212.00, 2),
(565, 'lcPtsg', 'tquPxnjvN', '03944365566', 'Street 6, City 31', 4084.00, 2),
(566, 'agzKc', 'ctwVXBJq', '03173535960', 'Street 31, City 21', 2170.00, 2),
(567, 'WEUUuS', 'fbGGI', '03730431212', 'Street 106, City 95', 1315.00, 5),
(568, 'TIXLVpBR', 'NxFRh', '03986484847', 'Street 19, City 78', 1440.00, 2),
(569, 'owwGz', 'mzimr', '03289986546', 'Street 278, City 78', 2544.00, 2),
(570, 'AiuOg', 'jjeuwylDcf', '03476796693', 'Street 458, City 97', 2243.00, 5),
(571, 'cuggSo', 'DLVpTVhmJw', '03474386877', 'Street 196, City 35', 3484.00, 5),
(572, 'KZAUHFlIeg', 'ahpCzKq', '03501956140', 'Street 107, City 34', 1371.00, 2),
(573, 'wuTffaDsDX', 'CHBAHaOat', '03310273162', 'Street 205, City 76', 3531.00, 2),
(574, 'BrsqSTc', 'ABqbZ', '03401657985', 'Street 30, City 58', 1019.00, 2),
(575, 'NsXWgNppJ', 'XNYqNFLLm', '03130177462', 'Street 307, City 77', 2077.00, 5),
(576, 'fvnUpir', 'EtYOb', '03416987254', 'Street 61, City 1', 4226.00, 5),
(577, 'upzwDPkU', 'eUSluj', '03694885370', 'Street 410, City 83', 790.00, 5),
(578, 'TMLIvYiWVi', 'OhLfAf', '03326111741', 'Street 190, City 15', 3449.00, 2),
(579, 'qaFsaw', 'AlnlXG', '03453011084', 'Street 320, City 28', 4878.00, 2),
(580, 'SdYDgl', 'sXaWzGQQC', '03835642926', 'Street 371, City 74', 2917.00, 5),
(581, 'kYKtK', 'WoElDLUqx', '03845534320', 'Street 3, City 64', 3862.00, 2),
(582, 'khAeJkIBel', 'kzEHcAfN', '03224521863', 'Street 256, City 34', 623.00, 5),
(583, 'EEIsvXZOs', 'AycBmovDnH', '03952651451', 'Street 215, City 67', 3895.00, 2),
(584, 'sZUQn', 'WjTsczTCI', '03925768679', 'Street 30, City 80', 4538.00, 5),
(585, 'KIIXCiNIvp', 'KGAnSWvopw', '03677163218', 'Street 372, City 8', 886.00, 2),
(586, 'byUJufhO', 'JxyjvJd', '03362356530', 'Street 450, City 17', 1587.00, 2),
(587, 'pMIPIFv', 'qaSlt', '03303106754', 'Street 26, City 8', 324.00, 2),
(588, 'JoQpjnJfHg', 'DRkwAo', '03506115942', 'Street 114, City 28', 1077.00, 2),
(589, 'MgHxEkMdJ', 'kDGaKHG', '03893152233', 'Street 292, City 10', 3943.00, 5),
(590, 'XTcdTy', 'oLalzFXk', '03736586575', 'Street 442, City 85', 1604.00, 2),
(591, 'yDdyRFjN', 'ybxolFZcBT', '03126116909', 'Street 238, City 7', 3761.00, 5),
(592, 'oCPOeFSqAL', 'XISBOh', '03159418695', 'Street 108, City 54', 1393.00, 2),
(593, 'TNRQMg', 'VMdyBEF', '03452593886', 'Street 197, City 95', 4915.00, 5),
(594, 'mvpQl', 'aMMiHhngju', '03130593801', 'Street 422, City 11', 3267.00, 2),
(595, 'SLqVAdfe', 'wQNEtU', '03616634846', 'Street 81, City 8', 4975.00, 2),
(596, 'LuXOOj', 'toHsAQ', '03394031943', 'Street 166, City 15', 3606.00, 2),
(597, 'PUqQCS', 'ZyJli', '03382829237', 'Street 194, City 27', 4465.00, 5),
(598, 'xVtltXFXEs', 'SuzGhEAWl', '03729811647', 'Street 190, City 1', 4625.00, 5),
(599, 'oFjvYe', 'iJuHkzyUxm', '03729930644', 'Street 299, City 72', 3984.00, 5),
(600, 'tcMIDh', 'IChpJ', '03787047760', 'Street 28, City 70', 1534.00, 5),
(601, 'nCouPuwab', 'cuzQY', '03971178725', 'Street 5, City 30', 212.00, 2),
(602, 'JVkuU', 'WOPFl', '03639777678', 'Street 101, City 16', 1737.00, 5),
(603, 'gYikJYvW', 'gXqQWbXvZG', '03487442957', 'Street 373, City 65', 715.00, 2),
(604, 'nLpNfNiviT', 'kukQCH', '03106028270', 'Street 472, City 67', 3905.00, 5),
(605, 'WvkgtaQ', 'Csdsop', '03237152309', 'Street 478, City 99', 124.00, 2),
(606, 'uNUgRXx', 'avFTtx', '03760708199', 'Street 360, City 63', 2851.00, 5),
(607, 'fIhEKxTjVD', 'tsoOVESsXp', '03276405241', 'Street 245, City 45', 3721.00, 5),
(608, 'wwCSKtk', 'TwfmK', '03652396451', 'Street 344, City 92', 4462.00, 5),
(609, 'kFjPNK', 'eNuHNK', '03832927825', 'Street 84, City 68', 3012.00, 5),
(610, 'ujTEp', 'nYvlljE', '03618608665', 'Street 275, City 51', 1742.00, 5),
(611, 'nUlXQSQY', 'AuJOOLX', '03989839748', 'Street 369, City 60', 1182.00, 2),
(612, 'rdskRQYXGN', 'myKhcCDGC', '03807081090', 'Street 1, City 17', 742.00, 5),
(613, 'lVeOqRaoKP', 'ZOkhEDp', '03424106695', 'Street 480, City 72', 1441.00, 2),
(614, 'lqmncTl', 'CMMbpNXOU', '03768997635', 'Street 205, City 43', 4216.00, 2),
(615, 'CnnaQV', 'GJxYBZS', '03235349978', 'Street 52, City 28', 4699.00, 5),
(616, 'Wbprc', 'ZRYZuSIYR', '03804340953', 'Street 125, City 81', 1507.00, 5),
(617, 'eEioDJM', 'bBeqiUE', '03685895525', 'Street 97, City 38', 2614.00, 5),
(618, 'ZwCsjYllM', 'xsRJrXFVDf', '03821595407', 'Street 4, City 92', 4007.00, 5),
(619, 'kDLwFJKzv', 'dMpcsxwZ', '03336460134', 'Street 333, City 2', 1550.00, 5),
(620, 'syCZFOgc', 'ZbwtuY', '03659211138', 'Street 184, City 88', 2843.00, 5),
(621, 'TWoMcN', 'FLLgriExz', '03838204715', 'Street 120, City 54', 499.00, 5),
(622, 'GPUrB', 'rhkuzSHaD', '03588947229', 'Street 292, City 38', 2794.00, 5),
(623, 'QzRWxFW', 'QBrDCEvbN', '03324633472', 'Street 267, City 99', 2952.00, 2),
(624, 'KzXidjXX', 'RXpVfED', '03899563131', 'Street 149, City 78', 2527.00, 5),
(625, 'FtatEthGE', 'NpyrXbqIuE', '03711585364', 'Street 245, City 74', 1486.00, 2),
(626, 'wUWTN', 'ioVAGNB', '03978428261', 'Street 401, City 11', 4005.00, 5),
(627, 'AiKqDu', 'jRhkxGpk', '03623955151', 'Street 483, City 74', 3637.00, 5),
(628, 'wKPZsbUM', 'jiKrfy', '03137808473', 'Street 464, City 61', 71.00, 2),
(629, 'UCHcM', 'PkyrsIZuG', '03741890306', 'Street 452, City 79', 4648.00, 2),
(630, 'fSuFMbhgj', 'bNMPs', '03217703266', 'Street 447, City 68', 1075.00, 5),
(631, 'hsiEcY', 'WVBiz', '03804597741', 'Street 268, City 39', 4603.00, 5),
(632, 'SAGhR', 'kruQFX', '03524917267', 'Street 138, City 24', 4678.00, 2),
(633, 'OIaSSmoZ', 'dqzpJ', '03442791762', 'Street 118, City 78', 1144.00, 5),
(634, 'TFNyDn', 'EyIApSdab', '03141871756', 'Street 275, City 21', 3782.00, 5),
(635, 'cQFFtzBya', 'PVdxcmk', '03147487489', 'Street 166, City 28', 283.00, 2);
(636, 'zjQcqs', 'fzLQiWHiLZ', '03754504684', 'Street 236, City 7', 1111.00, 2),
(637, 'YvKdUnA', 'GfvRsTV', '03106686502', 'Street 116, City 81', 557.00, 2),
(638, 'pnyPm', 'erBuyw', '03804799889', 'Street 231, City 5', 3357.00, 5),
(639, 'sTxdOcmsRD', 'ZyyeY', '03484668244', 'Street 210, City 23', 1266.00, 2),
(640, 'ysEMYk', 'qAPxH', '03390315640', 'Street 343, City 83', 4297.00, 2),
(641, 'zplDFYn', 'DxtPhjsH', '03205860362', 'Street 408, City 24', 3204.00, 5),
(642, 'ErQxTcaGYp', 'OWICtk', '03765828504', 'Street 106, City 35', 4976.00, 2),
(643, 'ginhumKLj', 'HwCiseS', '03268245507', 'Street 260, City 21', 645.00, 5),
(644, 'mhZjbXn', 'RfTwZtNuSB', '03439704114', 'Street 162, City 79', 3409.00, 5),
(645, 'LpDXQUA', 'uaKpa', '03463775631', 'Street 33, City 72', 2704.00, 2),
(646, 'tvyIw', 'OwsSO', '03579688150', 'Street 49, City 71', 1722.00, 2),
(647, 'ubKMkE', 'StIilq', '03205071580', 'Street 462, City 80', 2191.00, 5),
(648, 'tWyYzUH', 'rLIjdBIn', '03923387998', 'Street 7, City 51', 305.00, 2),
(649, 'rVRUWyVj', 'HtxOORh', '03147888917', 'Street 170, City 52', 2941.00, 2),
(650, 'QdaWTL', 'ktITwzUVP', '03582803983', 'Street 433, City 63', 300.00, 5),
(651, 'sbRUGUIvkb', 'estksJDfDu', '03711055269', 'Street 97, City 18', 3083.00, 2),
(652, 'eEcBzYw', 'lHqYjGrs', '03450325608', 'Street 317, City 100', 2344.00, 2),
(653, 'NwFZme', 'uqgZfsXx', '03333130385', 'Street 458, City 70', 1886.00, 2),
(654, 'nXLbeKHHYp', 'XxZKEMXfEo', '03306506987', 'Street 146, City 43', 2536.00, 5),
(655, 'oudPDhMbc', 'hRrfpfUF', '03616927601', 'Street 303, City 19', 454.00, 2),
(656, 'HbiNzZj', 'GhvQqYQq', '03836851905', 'Street 215, City 77', 4661.00, 2),
(657, 'XEwzKgFS', 'bhiPAHOST', '03430651889', 'Street 140, City 94', 3560.00, 5),
(658, 'IiRiKL', 'INqhBWR', '03605419066', 'Street 442, City 76', 4954.00, 5),
(659, 'NtNSkzMm', 'TbyrtKkSsm', '03187843239', 'Street 236, City 42', 2332.00, 5),
(660, 'iWbdTeZwa', 'kMifNUQe', '03256356929', 'Street 238, City 7', 3834.00, 5),
(661, 'nQSdJfRGig', 'LdbFe', '03975589851', 'Street 475, City 72', 3831.00, 5),
(662, 'dDvSphNKr', 'JvWHRBSe', '03985438772', 'Street 27, City 70', 3064.00, 2),
(663, 'NLpEHlC', 'zTwKaABiI', '03319682127', 'Street 407, City 26', 3259.00, 2),
(664, 'ObgKIs', 'yeptAuE', '03415644701', 'Street 371, City 46', 4794.00, 2),
(665, 'xcRtraVrq', 'PuJWtigMjU', '03840172111', 'Street 308, City 61', 1197.00, 5),
(666, 'snALnErU', 'lwJsDkB', '03474932288', 'Street 24, City 22', 3741.00, 2),
(667, 'bCMOCy', 'wygXM', '03984326729', 'Street 426, City 16', 250.00, 5),
(668, 'CMjVmsDGgp', 'DEVvb', '03224977322', 'Street 246, City 71', 1103.00, 5),
(669, 'cBtbolkTm', 'aTVhzC', '03410067590', 'Street 174, City 36', 1263.00, 2),
(670, 'JqomCmAm', 'LYKxMJW', '03811294460', 'Street 486, City 58', 1665.00, 2),
(671, 'XuGvyme', 'HHEViMQq', '03195959446', 'Street 86, City 56', 160.00, 5),
(672, 'GtLVUQuT', 'nZfdLGKVPM', '03283078250', 'Street 342, City 28', 1338.00, 5),
(673, 'CfpiE', 'nSNVVhxj', '03495734490', 'Street 96, City 24', 3553.00, 5),
(674, 'vYnuhZpRE', 'eJwGuXPn', '03144464360', 'Street 315, City 58', 469.00, 2),
(675, 'FvdfJaxJM', 'LVdsiidx', '03552760205', 'Street 182, City 86', 2375.00, 5),
(676, 'BVqPCQ', 'ASGQuwIVG', '03417819339', 'Street 414, City 81', 88.00, 2),
(677, 'uhczXDkzL', 'RyQHiSPc', '03487723993', 'Street 131, City 41', 836.00, 5),
(678, 'CyStdV', 'OoCrPvuJXa', '03859642078', 'Street 470, City 31', 3161.00, 5),
(679, 'tYzqTnU', 'fvkUXW', '03830787763', 'Street 100, City 75', 578.00, 5),
(680, 'dKwbU', 'ZZzkb', '03141804108', 'Street 447, City 75', 2222.00, 5),
(681, 'XXSYs', 'lHXAraBqA', '03713452668', 'Street 348, City 27', 3379.00, 5),
(682, 'pvuNW', 'XCqQq', '03123096058', 'Street 295, City 3', 1028.00, 5),
(683, 'ngnkv', 'moRkXrNc', '03267621151', 'Street 44, City 82', 4359.00, 5),
(684, 'yiokXtqN', 'SqsYG', '03895523828', 'Street 154, City 56', 1846.00, 2),
(685, 'GRKzqo', 'CvzDsNEmh', '03589261929', 'Street 94, City 72', 1330.00, 5),
(686, 'LQTPiUnb', 'ttykkClC', '03143635122', 'Street 127, City 5', 1867.00, 5),
(687, 'gwuRicGSWI', 'hXurCazbIv', '03508259105', 'Street 199, City 96', 4803.00, 2),
(688, 'qMiKeTGWt', 'inwGl', '03831153265', 'Street 168, City 34', 648.00, 2),
(689, 'IpvWOzc', 'vRDMWL', '03227542444', 'Street 180, City 53', 971.00, 2),
(690, 'GjGMjSec', 'kbhXjXiD', '03743492173', 'Street 42, City 21', 828.00, 2),
(691, 'DpwUiiJ', 'jojEfmae', '03472884456', 'Street 263, City 22', 3125.00, 2),
(692, 'dbQQYSVBC', 'MQIphRUC', '03760199041', 'Street 485, City 80', 3627.00, 5),
(693, 'LHGJjIqV', 'Ezdxcg', '03659729450', 'Street 45, City 36', 3656.00, 2),
(694, 'rImxY', 'dUVAdIdYKA', '03480654149', 'Street 371, City 96', 4667.00, 5),
(695, 'Ytygqe', 'iKRmm', '03652904565', 'Street 491, City 1', 1993.00, 2),
(696, 'btLqvKvCKZ', 'jophOwt', '03491560465', 'Street 398, City 6', 738.00, 5),
(697, 'NmNQp', 'xQdSTR', '03908463650', 'Street 70, City 23', 4259.00, 5),
(698, 'INxRarShSj', 'sTgsQcz', '03696219796', 'Street 14, City 25', 4121.00, 5),
(699, 'VciNn', 'OhXDOiAr', '03846761178', 'Street 3, City 91', 2426.00, 2),
(700, 'rboNU', 'VdCCl', '03683328355', 'Street 337, City 66', 2093.00, 2),
(701, 'ndkacsTD', 'fuqIHVR', '03402601777', 'Street 348, City 52', 3290.00, 2),
(702, 'eOUPBWB', 'YPiWREThu', '03323604037', 'Street 362, City 92', 2745.00, 5),
(703, 'kHQoTqR', 'HnEFJT', '03660962348', 'Street 80, City 78', 3485.00, 2),
(704, 'aBtGely', 'CoQhbUH', '03504770872', 'Street 387, City 53', 3979.00, 5),
(705, 'FZOFuS', 'DmqCPuxsLh', '03638929761', 'Street 450, City 76', 4785.00, 5),
(706, 'jnhlnWKZ', 'pQztGlJ', '03197319056', 'Street 220, City 82', 4809.00, 2),
(707, 'InpCICu', 'SFEXOpmuVl', '03984977002', 'Street 321, City 31', 3631.00, 5),
(708, 'wxPGkS', 'LALLbvBM', '03269358534', 'Street 409, City 79', 2875.00, 5),
(709, 'nxpPpz', 'tVPHDFWlHC', '03938510583', 'Street 340, City 75', 844.00, 5),
(710, 'DLUeoD', 'QNIek', '03973732067', 'Street 142, City 1', 480.00, 2),
(711, 'icUDUHlmG', 'JZvjQk', '03780095869', 'Street 199, City 23', 3660.00, 2),
(712, 'wccvOGdql', 'CMoCjyet', '03657666400', 'Street 421, City 50', 1108.00, 2),
(713, 'DGKXbaM', 'jtoZyXVyWl', '03165148102', 'Street 408, City 81', 2371.00, 2),
(714, 'gjijmOXs', 'rCHNbuLDh', '03438698709', 'Street 322, City 36', 1928.00, 5),
(715, 'WkNAX', 'AipHktDmzj', '03317735914', 'Street 173, City 62', 2874.00, 5),
(716, 'SQtcYet', 'xwqMSf', '03596345235', 'Street 282, City 60', 3864.00, 5),
(717, 'aevGlVJ', 'dxCTGYO', '03392510907', 'Street 465, City 56', 1471.00, 5),
(718, 'sWPbb', 'vSyXgT', '03249177474', 'Street 84, City 58', 880.00, 5),
(719, 'DWlVK', 'LozqE', '03682199165', 'Street 179, City 19', 4844.00, 2),
(720, 'BvHgJ', 'bPbgzflvP', '03880614671', 'Street 320, City 22', 1243.00, 5),
(721, 'ZmtTXiEJ', 'CiQlS', '03448673804', 'Street 31, City 18', 4038.00, 2),
(722, 'DFfoxO', 'aRBAEIq', '03201132396', 'Street 414, City 12', 968.00, 2),
(723, 'KgxJTfSO', 'WqIIL', '03261912475', 'Street 128, City 4', 559.00, 2),
(724, 'QrHiiJwli', 'aFOuN', '03839412608', 'Street 177, City 62', 2318.00, 2),
(725, 'wKKXEjT', 'QRxOXdFjUT', '03580096549', 'Street 390, City 32', 3758.00, 5),
(726, 'kcmsMEbEaJ', 'bYJHFyXY', '03455043302', 'Street 250, City 93', 4966.00, 5),
(727, 'Pinrm', 'GUVUXHndwY', '03302561158', 'Street 168, City 36', 896.00, 5),
(728, 'ebYrrUa', 'YmsvjQxn', '03516909188', 'Street 395, City 99', 2659.00, 2),
(729, 'RBnvztP', 'gMPxw', '03273119124', 'Street 75, City 9', 3338.00, 5),
(730, 'JyEyRlyu', 'vmkNe', '03930351886', 'Street 65, City 9', 4981.00, 5),
(731, 'schMGVEB', 'bvupaUEXPg', '03533797709', 'Street 404, City 29', 2252.00, 2),
(732, 'vbAiHJd', 'NTaLHpKR', '03724614029', 'Street 43, City 41', 3540.00, 5),
(733, 'TcJqBpBI', 'bkfjMXpVaU', '03804695165', 'Street 75, City 94', 2759.00, 2),
(734, 'PHyzPkFCd', 'hKjtKqg', '03507249273', 'Street 337, City 69', 70.00, 5),
(735, 'sjHko', 'wvjOpDH', '03743518169', 'Street 193, City 26', 1216.00, 2),
(736, 'cTlMXaJJww', 'DLmHAlDb', '03407885783', 'Street 57, City 13', 201.00, 2),
(737, 'awGPUxzd', 'xLbbFT', '03680041729', 'Street 325, City 52', 13.00, 5),
(738, 'vXSLDCUys', 'geOxbRq', '03829817560', 'Street 320, City 3', 2442.00, 2),
(739, 'aesTPPWuI', 'WsUVk', '03727967673', 'Street 347, City 40', 722.00, 2),
(740, 'BkjXMZaXy', 'KFTBXjSyaS', '03160642191', 'Street 54, City 13', 3375.00, 2),
(741, 'zltGnbO', 'SyhjdwsEo', '03920508768', 'Street 52, City 42', 643.00, 2),
(742, 'wKgSrv', 'InbtlK', '03328226283', 'Street 425, City 33', 1989.00, 5),
(743, 'tBDoPy', 'oLezDJn', '03608138753', 'Street 453, City 65', 4989.00, 2),
(744, 'FzqEnh', 'rZJCHQe', '03872626178', 'Street 209, City 23', 4236.00, 2),
(745, 'erdGzlYG', 'DISzTC', '03741779365', 'Street 486, City 66', 4905.00, 2),
(746, 'nPVgbo', 'JQQICnp', '03231990365', 'Street 36, City 88', 4346.00, 2),
(747, 'pmrwASU', 'JzTeyfAle', '03750504729', 'Street 83, City 28', 3474.00, 2),
(748, 'rgpICc', 'SqgAabcbTu', '03882126869', 'Street 208, City 21', 4994.00, 5),
(749, 'kssZi', 'GvAloYUfx', '03696112473', 'Street 432, City 91', 4403.00, 2),
(750, 'TwWHXU', 'MjPdzMQGa', '03392749120', 'Street 86, City 94', 3131.00, 5),
(751, 'XEaPa', 'Zabiq', '03899233485', 'Street 382, City 90', 2360.00, 5),
(752, 'zUCBeboKv', 'BbRuvINSzF', '03474196314', 'Street 443, City 83', 483.00, 2),
(753, 'SvQUWM', 'dSFMhdqGD', '03779185651', 'Street 149, City 69', 3500.00, 2),
(754, 'qaaDTWLweM', 'NGLKNezYb', '03165825367', 'Street 466, City 43', 3684.00, 5),
(755, 'PqWOAmVHH', 'VdvYZLPpRW', '03315034818', 'Street 425, City 2', 819.00, 5),
(756, 'GLirm', 'xyxhAeDr', '03181486593', 'Street 233, City 22', 1770.00, 5),
(757, 'kZbfIwyv', 'nOgbgSMif', '03853774888', 'Street 215, City 35', 4144.00, 2),
(758, 'MQxdipyHDX', 'lGYZPFlyk', '03871293875', 'Street 326, City 96', 569.00, 2),
(759, 'dazVoum', 'dbPMNp', '03724608550', 'Street 279, City 76', 1563.00, 2),
(760, 'xgOTmo', 'GzEiDXQ', '03377890684', 'Street 333, City 76', 949.00, 5),
(761, 'flSfx', 'BSqDOEDn', '03957437208', 'Street 122, City 23', 2474.00, 5),
(762, 'aoouuabH', 'FbqoVSl', '03185738964', 'Street 485, City 84', 1724.00, 5),
(763, 'rcolaU', 'uiaEd', '03803764612', 'Street 104, City 9', 2899.00, 2),
(764, 'RzoKlAL', 'QAtGN', '03320896558', 'Street 400, City 73', 282.00, 2),
(765, 'OoVPLSjv', 'fEQrROY', '03341938116', 'Street 255, City 5', 3970.00, 5),
(766, 'lIqQxhIHd', 'bzQWglzMg', '03414416517', 'Street 293, City 71', 4441.00, 2),
(767, 'jrFmDqkTXJ', 'eeFNCClf', '03150958952', 'Street 312, City 1', 4834.00, 5),
(768, 'ycVCtkcXdd', 'yCgGB', '03516850735', 'Street 285, City 41', 1080.00, 5),
(769, 'vEaLx', 'iZRmG', '03103523442', 'Street 435, City 3', 866.00, 5),
(770, 'qVXkVajch', 'pOTpds', '03605979043', 'Street 154, City 97', 4377.00, 2),
(771, 'SAwaHxHhdF', 'sSukpNQgh', '03727433518', 'Street 116, City 89', 3297.00, 5),
(772, 'irwEy', 'ksymhHd', '03315678610', 'Street 251, City 65', 1166.00, 5),
(773, 'xDMAccdP', 'hKjTHt', '03426219614', 'Street 272, City 8', 556.00, 5),
(774, 'RYHNmMh', 'OaQwnltmc', '03805184279', 'Street 282, City 95', 4547.00, 2),
(775, 'IqdYOwyef', 'gIBkdf', '03592514300', 'Street 138, City 65', 4315.00, 5),
(776, 'WeNCdW', 'FNLywDyGIf', '03990716046', 'Street 34, City 50', 1691.00, 5),
(777, 'HpnoeNUCjM', 'riEKZhOkmM', '03203787367', 'Street 153, City 47', 1692.00, 5),
(778, 'XjyMjwsh', 'FsDfTjaymt', '03984157329', 'Street 252, City 58', 2991.00, 2),
(779, 'GRpTIBw', 'UftmuF', '03757083768', 'Street 319, City 99', 676.00, 5),
(780, 'RRFtbCkD', 'pDmqVMST', '03143343939', 'Street 378, City 58', 3377.00, 5),
(781, 'Qpogy', 'MDQxBCp', '03244844046', 'Street 245, City 25', 2413.00, 2),
(782, 'ANLrzrpvM', 'vEMlSkU', '03696428061', 'Street 118, City 48', 1124.00, 2),
(783, 'vRRwvDpH', 'JVDdU', '03208875989', 'Street 363, City 73', 3625.00, 5),
(784, 'PTliAdJ', 'fuOisq', '03828442854', 'Street 20, City 49', 1408.00, 5),
(785, 'PWvXs', 'sVKgMaC', '03824426837', 'Street 179, City 39', 1053.00, 2),
(786, 'TzCGF', 'TERvydno', '03352764228', 'Street 275, City 24', 2436.00, 5),
(787, 'TeXex', 'TCpFFOy', '03551242810', 'Street 439, City 36', 3777.00, 5),
(788, 'EVsMvd', 'iQsSztrri', '03769038148', 'Street 235, City 79', 1193.00, 2),
(789, 'oXpguUTka', 'zpUsDnxUcy', '03143465393', 'Street 307, City 13', 1951.00, 2),
(790, 'AqkMIbJWHh', 'sVJUDPfyxp', '03515331636', 'Street 464, City 16', 3689.00, 5),
(791, 'riTVXxRk', 'GbmGPrtmp', '03615156711', 'Street 427, City 14', 4732.00, 2),
(792, 'EOxZS', 'xJAuPYPE', '03920134454', 'Street 108, City 67', 1620.00, 5),
(793, 'zsBOyhgWf', 'XMYCyfbKyy', '03371829701', 'Street 13, City 26', 1098.00, 2),
(794, 'AoJEJ', 'ucmbrgHgvb', '03196107141', 'Street 326, City 49', 1706.00, 5),
(795, 'BLtkViYyu', 'XjqsC', '03367510069', 'Street 2, City 41', 4986.00, 5),
(796, 'GUlzOk', 'WrjFG', '03394914034', 'Street 467, City 23', 4202.00, 2),
(797, 'AlaFYouIY', 'qaJGmZ', '03625292725', 'Street 19, City 35', 3458.00, 5),
(798, 'KjQsxWVKVP', 'ThfatqkT', '03794360185', 'Street 286, City 45', 3638.00, 2),
(799, 'QvIskQIb', 'VbyJGlMfaS', '03975670727', 'Street 145, City 71', 2740.00, 2),
(800, 'dnfEo', 'RrKGUI', '03738234865', 'Street 494, City 27', 1893.00, 2),
(801, 'mUjWuFqeTw', 'mIwWUznzkY', '03241050044', 'Street 98, City 89', 592.00, 5),
(802, 'sraFpQhzZn', 'DbOiZBPCu', '03144217078', 'Street 438, City 39', 3908.00, 2),
(803, 'geRjCKFOQ', 'TtcLzDw', '03340249888', 'Street 245, City 57', 794.00, 5),
(804, 'wZWoCvDBc', 'YKbLaakNk', '03882428821', 'Street 341, City 71', 4668.00, 2),
(805, 'JYVmdGssg', 'IErMP', '03806366968', 'Street 162, City 69', 3838.00, 5),
(806, 'ptzWeRFlUs', 'cpOlG', '03425249091', 'Street 201, City 34', 621.00, 2),
(807, 'ReRGqlVr', 'ACTDDMddj', '03162470218', 'Street 159, City 34', 2961.00, 2),
(808, 'jQlEr', 'xYKsxmxT', '03447422520', 'Street 337, City 55', 3578.00, 5),
(809, 'XtjhTLkVUK', 'WcMiBgOgQ', '03148917928', 'Street 454, City 29', 3632.00, 5),
(810, 'YQvsY', 'FtPfIDhGps', '03603468779', 'Street 168, City 89', 1859.00, 5),
(811, 'QErdzHL', 'rECWqi', '03348182285', 'Street 421, City 43', 4383.00, 5),
(812, 'ORyUL', 'PDkzux', '03911929394', 'Street 481, City 14', 1983.00, 5),
(813, 'uPtbEh', 'pfDIOQyXP', '03848726547', 'Street 417, City 88', 105.00, 2),
(814, 'GxjMJKF', 'qpbFo', '03232853655', 'Street 183, City 40', 2216.00, 5),
(815, 'AorsBp', 'TuvrPepP', '03730139132', 'Street 277, City 66', 3911.00, 5),
(816, 'WPkDomaYIJ', 'fubSW', '03807627642', 'Street 349, City 2', 3040.00, 2),
(817, 'ouZgEkNcZ', 'dWUgUWa', '03310384572', 'Street 451, City 32', 1768.00, 5),
(818, 'IZQAXCpc', 'cqIrkHVtS', '03212134460', 'Street 154, City 85', 3646.00, 5),
(819, 'hLEguM', 'uxRzu', '03491874438', 'Street 353, City 87', 4822.00, 2),
(820, 'USRhgu', 'MpiWRCWfk', '03772209712', 'Street 6, City 44', 1454.00, 2),
(821, 'sJCumqc', 'lcucpKra', '03109015375', 'Street 308, City 62', 2009.00, 2),
(822, 'TNnqQCC', 'qRNsz', '03688162682', 'Street 403, City 49', 3154.00, 2),
(823, 'hChKBnjs', 'psQRFsXhF', '03766565800', 'Street 142, City 22', 899.00, 5),
(824, 'uqaAQiuxW', 'IVGNdYOu', '03437604758', 'Street 262, City 24', 322.00, 5),
(825, 'lNSTs', 'fsZaUAqUp', '03911485784', 'Street 273, City 78', 4694.00, 2),
(826, 'WsMeyq', 'OulEkQoM', '03114758970', 'Street 297, City 46', 3792.00, 5),
(827, 'FvSyFvn', 'HMhYZg', '03688442964', 'Street 349, City 73', 2185.00, 2),
(828, 'QVQQTUSGyb', 'URzJwLAlr', '03854990613', 'Street 2, City 61', 4898.00, 2),
(829, 'NnWNzY', 'cZHsRKf', '03556597587', 'Street 433, City 18', 4830.00, 2),
(830, 'nbBHEdq', 'ruZdUflti', '03806075550', 'Street 213, City 60', 1606.00, 2),
(831, 'CizQJjo', 'mWWAEN', '03711696189', 'Street 383, City 86', 26.00, 5),
(832, 'jxdikx', 'XsoGz', '03890560861', 'Street 136, City 45', 467.00, 5),
(833, 'fePWuF', 'hnlUkI', '03310553832', 'Street 388, City 82', 1183.00, 2),
(834, 'oZwkoJNNUo', 'kNxHb', '03636797734', 'Street 32, City 50', 4191.00, 5),
(835, 'YzpVzxbxLr', 'EcWpu', '03886939485', 'Street 497, City 75', 2440.00, 5),
(836, 'CByYKL', 'cUfaPc', '03624324222', 'Street 317, City 52', 4507.00, 5),
(837, 'NFDyQYKV', 'Jehyqd', '03754173816', 'Street 401, City 1', 556.00, 5),
(838, 'DJGUfThyR', 'vyhvMfaIkb', '03856214551', 'Street 72, City 81', 56.00, 5),
(839, 'ZTaEIJGi', 'pmqMtD', '03656250037', 'Street 197, City 34', 1388.00, 2),
(840, 'uRCUTSx', 'rSnGYxtMOz', '03417041137', 'Street 255, City 95', 4411.00, 2),
(841, 'OtIZEnjL', 'BlktbV', '03102400536', 'Street 329, City 29', 4545.00, 5),
(842, 'CoYkPU', 'hIHgBKRH', '03112025728', 'Street 317, City 35', 2309.00, 5),
(843, 'QtbpA', 'xDPco', '03247741403', 'Street 471, City 10', 2195.00, 2),
(844, 'ccIfYgjA', 'qvswtd', '03791733700', 'Street 155, City 52', 2438.00, 5),
(845, 'phxswgm', 'gZFsDtPhUN', '03309403878', 'Street 3, City 1', 1833.00, 5),
(846, 'uiYexDQ', 'jLivP', '03329922120', 'Street 207, City 27', 3843.00, 2),
(847, 'ufyNxXZT', 'RyLdlM', '03176546532', 'Street 183, City 71', 3323.00, 2),
(848, 'zFaBXZ', 'iDCPnleFhi', '03645411299', 'Street 464, City 93', 1912.00, 2),
(849, 'hJYAqcm', 'YGfqjs', '03988305422', 'Street 37, City 31', 1341.00, 2),
(850, 'Vtvtiu', 'cSSkCnq', '03738784081', 'Street 78, City 35', 936.00, 5),
(851, 'hpoBNL', 'QHWkiTS', '03181563471', 'Street 28, City 54', 2851.00, 5),
(852, 'cSnKmkrBpx', 'LbppJurVK', '03197108698', 'Street 145, City 12', 1253.00, 2),
(853, 'neubMvfJD', 'tyomxMYmu', '03343829679', 'Street 49, City 25', 1932.00, 5),
(854, 'oAvLif', 'pxZmmd', '03978468056', 'Street 472, City 9', 1355.00, 2),
(855, 'MftZSPUO', 'XsxrFvLpn', '03751668230', 'Street 52, City 94', 2703.00, 5),
(856, 'nKMLYvr', 'DkPBwS', '03857078159', 'Street 362, City 9', 1128.00, 2),
(857, 'TCVXFSR', 'idbXVMaF', '03694404591', 'Street 88, City 44', 809.00, 2),
(858, 'MNcHHGr', 'ryXrtm', '03766410240', 'Street 152, City 87', 3882.00, 2),
(859, 'jZYUohZKNZ', 'QsfYrt', '03824438827', 'Street 260, City 72', 3660.00, 2),
(860, 'IaiZS', 'KDtVG', '03327542398', 'Street 127, City 25', 2574.00, 2),
(861, 'haphbiWrIT', 'LbELCJ', '03254565290', 'Street 445, City 15', 3920.00, 2),
(862, 'yxdIkybNTP', 'BYmgWWcf', '03815250073', 'Street 101, City 79', 2807.00, 5),
(863, 'wuqXRRxEDZ', 'nqIKWdaT', '03176148358', 'Street 97, City 16', 1432.00, 5),
(864, 'jGZuMdZUeY', 'bDdQrT', '03791864530', 'Street 390, City 50', 448.00, 2),
(865, 'yVtpjnB', 'HBVVLJNm', '03125054375', 'Street 130, City 10', 746.00, 5),
(866, 'NtRvoA', 'jkqPHq', '03756732527', 'Street 337, City 19', 1506.00, 5),
(867, 'UWxulJAYHn', 'XrxyE', '03570372922', 'Street 383, City 60', 4436.00, 2),
(868, 'mAkwIjT', 'qeYbJnE', '03772603469', 'Street 19, City 10', 2614.00, 5),
(869, 'PPenDb', 'CLHCSmsCX', '03809121116', 'Street 307, City 32', 4642.00, 5),
(870, 'KcpGOH', 'dhLKHXy', '03973647871', 'Street 136, City 60', 2688.00, 5),
(871, 'oQFoo', 'JeqQD', '03536789175', 'Street 305, City 94', 2385.00, 5),
(872, 'bCMbAeVvrJ', 'BCWQkKx', '03370109130', 'Street 188, City 70', 3780.00, 2),
(873, 'YnVWbQPj', 'QSYxDL', '03891651220', 'Street 125, City 100', 2694.00, 2),
(874, 'uRYEb', 'iMQWUAs', '03598599268', 'Street 491, City 83', 1366.00, 2),
(875, 'BqLibPVV', 'sHYcikx', '03936227067', 'Street 210, City 72', 3256.00, 5),
(876, 'iPMCxWHf', 'dbCokg', '03659631430', 'Street 444, City 28', 3264.00, 2),
(877, 'GGsfsm', 'jfHXy', '03372643098', 'Street 211, City 43', 2691.00, 5),
(878, 'eSOTwfZesg', 'sQCGSY', '03689108661', 'Street 398, City 35', 4916.00, 5),
(879, 'jhbdmJTaWT', 'UsRwWWz', '03513237176', 'Street 396, City 74', 1543.00, 2),
(880, 'DMSsOtBqgd', 'RTmpl', '03165243001', 'Street 87, City 82', 459.00, 2),
(881, 'etskFiKGOv', 'eOvpXYt', '03219253944', 'Street 151, City 74', 671.00, 5),
(882, 'fOowe', 'oJoVPE', '03717048001', 'Street 62, City 38', 1657.00, 5),
(883, 'hKvPDf', 'DYNwr', '03399660495', 'Street 317, City 58', 2728.00, 2),
(884, 'kLDlMJa', 'HZnRmBt', '03162193037', 'Street 19, City 14', 1565.00, 2),
(885, 'BWoTptoNDk', 'OTjZiTUkVr', '03700310097', 'Street 371, City 40', 116.00, 2),
(886, 'frXLpv', 'mqVknnwCKx', '03797512557', 'Street 38, City 98', 3765.00, 2),
(887, 'eajxgh', 'QxgTQ', '03935007546', 'Street 471, City 20', 4863.00, 2),
(888, 'PYejlGvEQ', 'WhskR', '03321022976', 'Street 382, City 11', 3083.00, 5),
(889, 'lXiQxIAWNC', 'blnOPXCs', '03557589134', 'Street 256, City 3', 312.00, 2),
(890, 'WPtniQdDV', 'CXNvhHKQj', '03146920034', 'Street 240, City 2', 107.00, 2),
(891, 'wXrHTAZWx', 'hkkHYtvDNn', '03627607391', 'Street 278, City 31', 2920.00, 5),
(892, 'boylhpxdel', 'isAwaLUDT', '03122999726', 'Street 122, City 31', 2191.00, 5),
(893, 'GSULAIuw', 'SdUXAoC', '03148736238', 'Street 404, City 66', 1647.00, 5),
(894, 'gdiRjLZEFn', 'LgaasPf', '03726607064', 'Street 221, City 58', 3221.00, 5),
(895, 'PYqEqRPTFr', 'BsLRkma', '03544232608', 'Street 240, City 84', 2934.00, 5),
(896, 'jJduPvO', 'cEYtyvp', '03919234137', 'Street 224, City 28', 1327.00, 2),
(897, 'hormYxZiU', 'KDWOmZ', '03960140283', 'Street 115, City 24', 3906.00, 5),
(898, 'YQDaeWq', 'uFAYtqc', '03570478987', 'Street 386, City 28', 180.00, 2),
(899, 'QMbsKhvk', 'DvKVekjXff', '03819813465', 'Street 87, City 97', 392.00, 5),
(900, 'ZqmYIF', 'uaIhuV', '03629841466', 'Street 384, City 59', 3327.00, 5),
(901, 'LatVI', 'RlkuXiW', '03454965669', 'Street 318, City 20', 640.00, 5),
(902, 'grHZBwMF', 'YhPqhVsPR', '03691087555', 'Street 110, City 41', 1907.00, 2),
(903, 'vvxvuTbJe', 'IQTCzNydl', '03624409514', 'Street 64, City 4', 3685.00, 2),
(904, 'ypyrLX', 'ZMUNhyqHt', '03463103153', 'Street 377, City 30', 2083.00, 2),
(905, 'ZfXNd', 'rOEzWIuT', '03740668513', 'Street 275, City 5', 2370.00, 5),
(906, 'nJRxiVau', 'UkJcX', '03434978424', 'Street 360, City 33', 3054.00, 2),
(907, 'Uffwl', 'LYEwzNh', '03285637442', 'Street 37, City 8', 4537.00, 2),
(908, 'IvOvO', 'iqDGDxhIW', '03641731425', 'Street 205, City 86', 3488.00, 5),
(909, 'QAAKgWf', 'ubAHoOh', '03649016422', 'Street 470, City 55', 4611.00, 5),
(910, 'KlhjlH', 'stgkddan', '03586138186', 'Street 342, City 12', 1872.00, 5),
(911, 'CGyJfZ', 'YhCdvVqy', '03729037697', 'Street 235, City 29', 2849.00, 2),
(912, 'eWbYUsDVR', 'JeBea', '03282070827', 'Street 28, City 91', 1585.00, 2),
(913, 'NqpXCiEn', 'XzCIVdm', '03773335508', 'Street 219, City 76', 1747.00, 5),
(914, 'OnsAiZsk', 'yIzLyfVwoU', '03591982144', 'Street 122, City 74', 2992.00, 2),
(915, 'sTYTFrBo', 'xnjMQLIlQ', '03259485887', 'Street 241, City 53', 2772.00, 5),
(916, 'ErJCbGL', 'JFyTALVOA', '03606146252', 'Street 285, City 6', 4304.00, 5),
(917, 'HsfoXEsr', 'CUDeMMOZ', '03114923703', 'Street 410, City 55', 4453.00, 2),
(918, 'RZHoA', 'BMDkGYlBI', '03948952040', 'Street 139, City 55', 2140.00, 2),
(919, 'nlJEt', 'MvNEtJCRXM', '03449285984', 'Street 49, City 16', 177.00, 5),
(920, 'ZZXPkDjjOU', 'TTSXveLM', '03867572211', 'Street 236, City 44', 2362.00, 5),
(921, 'JAGvZ', 'yIWbnykWEB', '03788240229', 'Street 277, City 58', 482.00, 2),
(922, 'mcQDUCIWHO', 'PUcErMsL', '03294680069', 'Street 380, City 65', 1078.00, 2),
(923, 'aOYuAU', 'NYDjMB', '03479372324', 'Street 267, City 1', 3927.00, 2),
(924, 'mSDXE', 'TjTsatof', '03282345540', 'Street 154, City 9', 1912.00, 5),
(925, 'AxVYct', 'RhYNZjd', '03244950448', 'Street 212, City 77', 16.00, 5),
(926, 'PvYnlwB', 'pIypO', '03948463342', 'Street 91, City 44', 4956.00, 2),
(927, 'WBUtLGLIr', 'yPneOAQuw', '03200718724', 'Street 224, City 99', 3598.00, 5),
(928, 'QGUyMOK', 'vthyRAjyLg', '03605725017', 'Street 409, City 71', 4579.00, 5),
(929, 'NyTysuKX', 'hbfNdATrTF', '03588977902', 'Street 158, City 1', 654.00, 5),
(930, 'rgohQIyF', 'fPLlZ', '03141686028', 'Street 53, City 70', 851.00, 5),
(931, 'mTeaZbiY', 'lJZxhlu', '03127120033', 'Street 230, City 89', 1065.00, 2),
(932, 'CAgYSyNtJa', 'erKGK', '03976075244', 'Street 413, City 88', 3801.00, 5),
(933, 'SPFFBRX', 'fFBMKhPkV', '03126269239', 'Street 72, City 77', 2303.00, 5),
(934, 'lpAcLwnmw', 'kreqB', '03461054020', 'Street 401, City 68', 786.00, 5),
(935, 'ueQwY', 'fVsrunqOX', '03474450763', 'Street 14, City 14', 980.00, 2),
(936, 'bRDRDLod', 'dFlxQYg', '03567398062', 'Street 149, City 89', 4769.00, 2),
(937, 'qrYnRRVPgn', 'yKJDWa', '03733119820', 'Street 37, City 37', 1444.00, 5),
(938, 'iggdHZtFe', 'QozWaTcFg', '03643028666', 'Street 350, City 39', 2090.00, 5),
(939, 'dDZGtxfi', 'gSzMt', '03671225325', 'Street 291, City 43', 39.00, 2),
(940, 'IAfbHXRqj', 'zyWkw', '03601907481', 'Street 128, City 93', 876.00, 2),
(941, 'qKWdTe', 'DwPFMOC', '03647045650', 'Street 313, City 72', 955.00, 2),
(942, 'ikJjeCIpM', 'xIWBEhIA', '03264511980', 'Street 409, City 33', 977.00, 5),
(943, 'efzvSfTC', 'bnQGxTGufw', '03843261255', 'Street 228, City 29', 1249.00, 2),
(944, 'iHgLmzZuT', 'fLRBXpbBp', '03837218328', 'Street 90, City 97', 3989.00, 2),
(945, 'BEbof', 'vNfAMgVd', '03130857543', 'Street 159, City 46', 3378.00, 5),
(946, 'JWRAQWmLUb', 'LOieEQHw', '03782154082', 'Street 158, City 72', 3758.00, 2),
(947, 'vwKBW', 'mIIEKjbnr', '03715454110', 'Street 483, City 20', 1144.00, 5),
(948, 'kQmBRaExd', 'DtCprMNfl', '03589431761', 'Street 115, City 8', 1839.00, 5),
(949, 'SatBr', 'vIjTIFxHL', '03214826900', 'Street 111, City 94', 1734.00, 5),
(950, 'ooEEnRHQ', 'YFrpJLZqsr', '03556274994', 'Street 307, City 49', 3514.00, 2),
(951, 'zaNHXyYoO', 'JAEoWGzSdH', '03232010189', 'Street 241, City 12', 1603.00, 5),
(952, 'oVXhg', 'CHdHLxj', '03120612926', 'Street 157, City 89', 1022.00, 5),
(953, 'xyQmY', 'Irjad', '03726009816', 'Street 420, City 30', 1467.00, 5),
(954, 'oNKYzyQaU', 'BPWeg', '03539000373', 'Street 274, City 73', 3500.00, 2),
(955, 'mRcZOSGoiR', 'FhjGW', '03657468537', 'Street 203, City 35', 4586.00, 2),
(956, 'nQQkxNaQHw', 'XAGzNwRX', '03643948156', 'Street 55, City 53', 1431.00, 5),
(957, 'qsDtndfpin', 'ULoHiJL', '03182699577', 'Street 113, City 5', 3430.00, 5),
(958, 'heKlU', 'ryVZLl', '03417652802', 'Street 187, City 38', 4691.00, 2),
(959, 'LTJpb', 'qgtHfeFbz', '03597095712', 'Street 169, City 67', 2829.00, 2),
(960, 'rSydPP', 'bTbJHe', '03886126830', 'Street 399, City 90', 1909.00, 5),
(961, 'taSeRl', 'ylWYLm', '03914176252', 'Street 426, City 95', 1784.00, 2),
(962, 'nwBEar', 'mSydg', '03237738989', 'Street 498, City 94', 3671.00, 5),
(963, 'wPIvvsqqC', 'lCZkbhOqN', '03348325658', 'Street 17, City 24', 4174.00, 2),
(964, 'georz', 'CxTeYru', '03302144487', 'Street 257, City 57', 4893.00, 5),
(965, 'YvITIL', 'iXrObpuwp', '03668899577', 'Street 408, City 40', 1743.00, 5),
(966, 'vMIZyd', 'SWIKRcL', '03221042528', 'Street 30, City 76', 484.00, 5),
(967, 'pkrKHIKVfm', 'wlArWKtCx', '03355748236', 'Street 10, City 2', 4301.00, 2),
(968, 'FmWspUZu', 'CjZrj', '03411810475', 'Street 347, City 47', 1511.00, 5),
(969, 'dqsqOEhDl', 'RmKqh', '03522349751', 'Street 312, City 42', 1115.00, 2),
(970, 'VVvYfsB', 'gcZYJelZs', '03723713847', 'Street 359, City 55', 611.00, 2),
(971, 'YAurttfb', 'fVLhQaazCS', '03998303112', 'Street 495, City 36', 4719.00, 2),
(972, 'PIAGR', 'tUQJZ', '03209581339', 'Street 165, City 39', 669.00, 5),
(973, 'YZhWF', 'WyakeUfd', '03797722717', 'Street 104, City 22', 1293.00, 5),
(974, 'JvKlwoyWa', 'LbIIUioG', '03878533859', 'Street 330, City 19', 619.00, 5),
(975, 'AEqdIl', 'NrDdfJe', '03793154910', 'Street 213, City 98', 4797.00, 5),
(976, 'keqrRROtds', 'MGcWmQf', '03239158772', 'Street 404, City 70', 4914.00, 2),
(977, 'RTQLaizf', 'keyUPknQ', '03185109839', 'Street 410, City 67', 1548.00, 2),
(978, 'OePmHHYH', 'MgkCnP', '03620992624', 'Street 322, City 75', 4908.00, 5),
(979, 'XFgnzwlMJi', 'DAZwyX', '03582269005', 'Street 129, City 16', 660.00, 5),
(980, 'rflupUO', 'kXFag', '03282579302', 'Street 316, City 100', 882.00, 5),
(981, 'dsOtcfKr', 'kBXqILsaFu', '03296068464', 'Street 127, City 29', 823.00, 2),
(982, 'AooJxFPDk', 'VHvWveHa', '03856804770', 'Street 265, City 9', 3904.00, 2),
(983, 'CBepJo', 'quEfmQzZ', '03985491473', 'Street 475, City 57', 1896.00, 5),
(984, 'MVxHUbvCPN', 'tXMSNPyo', '03531734931', 'Street 46, City 70', 4520.00, 2),
(985, 'nfhWnsA', 'RhhivMXJ', '03196082179', 'Street 109, City 79', 1.00, 5),
(986, 'TMjOoSnjaT', 'VNnnpYy', '03914541607', 'Street 11, City 67', 2160.00, 2),
(987, 'euwKurSubM', 'pSOAWy', '03149910281', 'Street 210, City 63', 4417.00, 5),
(988, 'PIBgiIOJQ', 'PbUwiS', '03130762081', 'Street 99, City 92', 1920.00, 2),
(989, 'mawtBoXqq', 'iYbDnXlT', '03369235163', 'Street 168, City 97', 487.00, 2),
(990, 'rbNSAknO', 'LXegZR', '03441042095', 'Street 485, City 26', 1308.00, 5),
(991, 'Dnjigpe', 'hchGvJv', '03375723285', 'Street 475, City 68', 2600.00, 2),
(992, 'EaOQFyZRg', 'Rwtjhepf', '03978900887', 'Street 494, City 61', 2807.00, 5),
(993, 'SzxRxl', 'fOebCIxJJ', '03280672082', 'Street 81, City 53', 961.00, 2),
(994, 'kdvotoIy', 'vVTeSZolh', '03234529912', 'Street 192, City 29', 3831.00, 2),
(995, 'fynKNMRE', 'riuujD', '03188099055', 'Street 124, City 71', 3593.00, 2),
(996, 'ElrygC', 'QTUJgexxe', '03967232896', 'Street 415, City 85', 4853.00, 5),
(997, 'fBKRubE', 'XPzewcdoH', '03580936614', 'Street 75, City 40', 4199.00, 5),
(998, 'XDHTOSQv', 'BlcaoaM', '03297381230', 'Street 94, City 99', 3058.00, 2),
(999, 'FYjvmpYyu', 'BiUXUV', '03466931940', 'Street 209, City 15', 1977.00, 2),
(1000, 'aXVXD', 'XXeEmeXa', '03908989451', 'Street 21, City 21', 1149.00, 2);

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
(3, 0, 0, '2024-01-01');

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
(1, 12.00, '2024-11-22 09:57:58', 10, 20);

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
(12, 60.00, '2024-11-22 23:06:00', 'ordered', '2024-11-22 23:06:02', 6, 1, '');

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
(9, 12, 2, 2, 30.00, 60.00);

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
(4, 'Prime Fabrics', '987 Silk Ave', '789-123-456', 'TAX004', '555666777', 4);

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
(8, 'NhatQuyenIT', '$2y$10$pKLR3oeL5ZP/upmOM2seWuQZP7CGb0SaPTlXJjfABbNDU54/HY58u');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bolt`
--
ALTER TABLE `bolt`
  ADD PRIMARY KEY (`BCode`),
  ADD KEY `CCode` (`CCode`),
  ADD KEY `idx_bolt_bcode` (`BCode`);

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CCode`),
  ADD KEY `SCode` (`SCode`),
  ADD KEY `idx_category_ccode` (`CCode`);

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
  ADD KEY `CusId` (`CusId`),
  ADD KEY `idx_orders_cusid` (`CusId`);

--
-- Chỉ mục cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`DetailId`),
  ADD KEY `OCode` (`OCode`),
  ADD KEY `BCode` (`BCode`),
  ADD KEY `idx_orderdetail_ocode` (`OCode`);

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
  MODIFY `CusId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;

--
-- AUTO_INCREMENT cho bảng `customer_partialpayments`
--
ALTER TABLE `customer_partialpayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `employee`
--
ALTER TABLE `employee`
  MODIFY `ECode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `OCode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `DetailId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SCode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`CusId`) REFERENCES `customer` (`CusId`) ON DELETE CASCADE;

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
