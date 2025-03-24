-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2025 at 09:55 AM
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
-- Database: `treeshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `ho_ten` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(15) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `trang_thai` enum('Hoạt động','Bị khóa'),
  `vai_tro` enum('Admin','Nhân viên','Khách hàng'),
  `created_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ho_ten`, `email`, `so_dien_thoai`, `mat_khau`, `trang_thai`, `vai_tro`, `created_time`, `last_updated`) VALUES
(15, 'Nguyễn Văn Ánh', 'nguyenvana@example.com', '0123456789', 'e99a18c428cb38d5f260853678922e03', 'Hoạt động', 'Khách hàng', '2025-03-24 06:59:31', '2025-03-24 01:12:41'),
(16, 'Tran Thi B', 'tranthib@example.com', '0987654321', 'd8578edf8458ce06fbc5bb76a58c5ca4', 'Bị khóa', 'Khách hàng', '2025-03-24 06:59:31', '2025-03-24 06:59:31'),
(17, 'Le Van C', 'levancc@example.com', '0911223344', '81dc9bdb52d04dc20036dbd8313ed055', 'Hoạt động', 'Khách hàng', '2025-03-24 06:59:31', '2025-03-24 06:59:31'),
(18, 'Pham Van D', 'phamvand@example.com', '0901234567', '482c811da5d5b4bc6d497ffa98491e38', 'Hoạt động', 'Khách hàng', '2025-03-24 06:59:31', '2025-03-24 06:59:31'),
(19, 'Nguyen Thi E', 'nguyenthie@example.com', '0912345678', 'bb77d0d3b3f239fa5db73bdf27b8d29a', 'Hoạt động', 'Khách hàng', '2025-03-24 06:59:31', '2025-03-24 06:59:31'),
(23, 'phương', 'lengocphuong6205@gmail.com', '0123456789', '$2y$10$dZk3M3xq3JVdH4.Bmst55./ukryApOKiVLtMOnDSN8hAOxDPo8Vea', 'Hoạt động', 'Admin', '2025-03-24 02:45:21', '2025-03-24 02:45:21'),
(24, 'Le Van Chị', 'lengocphuong62@gmail.com', '0911223344', '$2y$10$7Egk/UIwHaPOe6UyjjBxaejpNrtCy3PLBAT/3esJC4pXHy9hbdLlS', 'Hoạt động', 'Nhân viên', '2025-03-24 02:48:06', '2025-03-24 02:48:06');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
