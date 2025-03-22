-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2025 at 09:54 PM
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
-- Database: `treeshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `image_library`
--

CREATE TABLE `image_library` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_time` int(11) NOT NULL,
  `last_updated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `image_library`
--

INSERT INTO `image_library` (`id`, `product_id`, `path`, `created_time`, `last_updated`) VALUES
(1, 10, 'uploads/22-03-2025/Untitled_design_(1).png', 1742673899, 1742673899),
(2, 10, 'uploads/22-03-2025/Untitled_design.png', 1742673899, 1742673899),
(7, 10, 'uploads/22-03-2025/Untitled_design_(1).png', 2025, 2025),
(8, 10, 'uploads/22-03-2025/Untitled_design.png', 2025, 2025),
(9, 13, 'uploads/22-03-2025/Untitled_design_(1).png', 2025, 2025),
(10, 13, 'uploads/22-03-2025/Untitled_design.png', 2025, 2025),
(11, 10, 'uploads/22-03-2025/Untitled_design_(1).png', 2025, 2025),
(12, 10, 'uploads/22-03-2025/Untitled_design.png', 2025, 2025);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_time` int(11) NOT NULL,
  `last_updated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `image`, `price`, `quantity`, `content`, `created_time`, `last_updated`) VALUES
(2, 'Cây Hoa Hồng', 'uploads/06-03-2019/rose.jpg', 540000, 10, 'Hoa hồng có màu sắc rực rỡ, thích hợp trồng trong vườn.', 1552615987, 1552615987),
(3, 'Cây Cảnh Trong Nhà', 'uploads/06-03-2019/indoor_plant.jpg', 1500000, 5, 'Cây cảnh mang lại không khí trong lành cho ngôi nhà.', 1552615987, 1552615987),
(4, 'Cây Thơm Ngon', 'uploads/06-03-2019/herb_plant.jpg', 780000, 15, 'Cây thơm ngon, có thể dùng làm gia vị trong nấu ăn.', 1552615987, 1552615987),
(5, 'Cây Bàng', 'uploads/06-03-2019/maple_tree.jpg', 657000, 8, 'Cây bàng có tán lá rộng, mang đến bóng mát.', 1552615987, 1552615987),
(6, 'Cây Lô Hội', 'uploads/06-03-2019/aloe_vera.jpg', 684000, 12, 'Lô hội có tác dụng làm đẹp và chữa bệnh.', 1552615987, 1552615987),
(7, 'Cây Cọ Mềm', 'uploads/06-03-2019/palm_tree.jpg', 1320000, 6, 'Cây cọ mềm tạo cảm giác nhiệt đới cho không gian.', 1552615987, 1552615987),
(8, 'Cây Xương Rồng', 'uploads/06-03-2019/cactus.jpg', 1450000, 20, 'Cây xương rồng dễ chăm sóc và thích nghi với khí hậu.', 1552615987, 1552615987),
(9, 'Cây Dừa Cạn', 'uploads/06-03-2019/oleander.jpg', 1000000, 3, 'Cây dừa cạn có nhiều hoa, thích hợp trồng ngoài trời.', 1552615987, 1742674082),
(10, 'Cây Lan', 'uploads/06-03-2019/orchid.jpg', 540000, 5, 'Cây lan có những bông hoa đẹp, thu hút ánh nhìn.', 1552615987, 2025),
(13, 'Khang', 'uploads/22-03-2025/Untitled_design.png', 1, 1, 'HAHA', 2025, 2025);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `image_library`
--
ALTER TABLE `image_library`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `image_library`
--
ALTER TABLE `image_library`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `image_library`
--
ALTER TABLE `image_library`
  ADD CONSTRAINT `image_library_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
