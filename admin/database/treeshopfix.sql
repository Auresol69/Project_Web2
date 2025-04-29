-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 10:30 AM
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
-- Database: `treeshopfix`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` varchar(20) NOT NULL,
  `macustomer` varchar(20) NOT NULL,
  `city` varchar(50) NOT NULL,
  `district` varchar(50) NOT NULL,
  `street_address` varchar(100) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`address_id`, `macustomer`, `city`, `district`, `street_address`, `is_default`) VALUES
('ADDR001', 'CUS001', 'Hà Nội', 'Ba Đình', '123 Phố Cây', 1),
('ADDR002', 'CUS002', 'Hồ Chí Minh', 'Quận 1', '456 Đường Hoa', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `mabill` varchar(20) NOT NULL,
  `macustomer` varchar(20) NOT NULL,
  `maorder` varchar(20) NOT NULL,
  `mapayby` varchar(20) NOT NULL,
  `ngaymua` datetime(6) DEFAULT current_timestamp(6),
  `tongtien` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`mabill`, `macustomer`, `maorder`, `mapayby`, `ngaymua`, `tongtien`) VALUES
('BIL001', 'CUS001', 'ORD001', 'PAY001', '2025-04-21 15:21:58.000000', 350000),
('BIL002', 'CUS002', 'ORD002', 'PAY002', '2025-04-21 15:21:58.000000', 200000);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `magiohang` varchar(20) NOT NULL,
  `mauser` varchar(20) NOT NULL,
  `maorder` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`magiohang`, `mauser`, `maorder`) VALUES
('CART001', 'CUS001', 'ORD001'),
('CART002', 'CUS002', 'ORD002'),
('CART680f31417e277', 'CUS019', NULL);

--
-- Triggers `cart`
--
DELIMITER $$
CREATE TRIGGER `after_update_cart` AFTER UPDATE ON `cart` FOR EACH ROW BEGIN
    -- Kiểm tra nếu maorder được cập nhật từ NULL thành một giá trị hợp lệ
    IF OLD.maorder IS NULL AND NEW.maorder IS NOT NULL THEN
        -- Cập nhật số lượng sản phẩm trong bảng product
        UPDATE product p
        JOIN product_cart pc ON p.masp = pc.masp
        SET p.soluong = p.soluong - pc.soluong
        WHERE pc.magiohang = NEW.magiohang;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `macomment` int(11) NOT NULL,
  `noidung` text NOT NULL,
  `ngaydang` datetime(6) DEFAULT current_timestamp(6),
  `masp` varchar(20) NOT NULL,
  `mauser` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`macomment`, `noidung`, `ngaydang`, `masp`, `mauser`) VALUES
(1, 'Cây rất đẹp và dễ chăm sóc!', '2025-04-21 15:21:59.089893', 'PRO001', 'CUS001'),
(2, 'Giao hàng nhanh, cây tươi tốt.', '2025-04-21 15:21:59.089893', 'PRO002', 'CUS002');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `macustomer` varchar(20) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `name` varchar(25) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `default_address_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`macustomer`, `username`, `password`, `phone`, `name`, `email`, `default_address_id`) VALUES
('CUS001', 'nguyen_van_a', 'password_hash', '0123456789', 'Nguyễn Văn A', 'a@example.com', 'ADDR001'),
('CUS002', 'tran_thi_b', 'password_hash', '0987654321', 'Trần Thị B', 'b@example.com', 'ADDR002'),
('CUS003', 'nguyen_van_a', 'password_hash', '0123456789', 'Nguyễn Văn A', 'a@example.com', 'ADDR001'),
('CUS004', 'tran_thi_b', 'password_hash', '0987654321', 'Trần Thị B', 'b@example.com', 'ADDR002'),
('CUS005', 'nguyen_van_a', 'password_hash', '0123456789', 'Nguyễn Văn A', 'a@example.com', 'ADDR001'),
('CUS006', 'tran_thi_b', 'password_hash', '0987654321', 'Trần Thị B', 'b@example.com', 'ADDR002'),
('CUS007', 'nguyen_van_a', 'password_hash', '0123456789', 'Nguyễn Văn A', 'a@example.com', 'ADDR001'),
('CUS008', 'tran_thi_b', 'password_hash', '0987654321', 'Trần Thị B', 'b@example.com', 'ADDR002'),
('CUS009', 'nguyen_van_a', 'password_hash', '0123456789', 'Nguyễn Văn A', 'a@example.com', 'ADDR001'),
('CUS010', 'tran_thi_b', 'password_hash', '0987654321', 'Trần Thị B', 'b@example.com', 'ADDR002'),
('CUS011', 'nguyen_van_a', 'password_hash', '0123456789', 'Nguyễn Văn A', 'a@example.com', 'ADDR001'),
('CUS012', 'tran_thi_b', 'password_hash', '0987654321', 'Trần Thị B', 'b@example.com', 'ADDR002'),
('CUS013', 'nguyen_van_a', 'password_hash', '0123456789', 'Nguyễn Văn A', 'a@example.com', 'ADDR001'),
('CUS014', 'tran_thi_b', 'password_hash', '0987654321', 'Trần Thị B', 'b@example.com', 'ADDR002'),
('CUS015', 'nguyen_van_a', 'password_hash', '0123456789', 'Nguyễn Văn A', 'a@example.com', 'ADDR001'),
('CUS016', 'tran_thi_b', 'password_hash', '0987654321', 'Trần Thị B', 'b@example.com', 'ADDR002'),
('CUS017', 'nguyen_van_a', 'password_hash', '0123456789', 'Nguyễn Văn A', 'a@example.com', 'ADDR001'),
('CUS018', 'tran_thi_b', 'password_hash', '0987654321', 'Trần Thị B', 'b@example.com', 'ADDR002'),
('CUS019', 'ngocphuong', '$2y$10$.FBnlfgPcAkjD1fLrHLEse5Xh1y3uQfpKfoPVcgkXSYyoFSf1i276', '0775855922', 'Ngoc Phuong Le', 'lengocphuong6205@gmail.com', NULL);

--
-- Triggers `customer`
--
DELIMITER $$
CREATE TRIGGER `before_insert_customer` BEFORE INSERT ON `customer` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(macustomer,4) AS UNSIGNED)),0) + 1 INTO new_id FROM customer;
    SET NEW.macustomer = CONCAT('CUS', LPAD(CAST(new_id AS CHAR),3,'0')); 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_entry_form`
--

CREATE TABLE `detail_entry_form` (
  `maphieunhap` varchar(20) NOT NULL,
  `masp` varchar(20) NOT NULL,
  `dongianhap` int(11) NOT NULL,
  `soluongnhap` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_entry_form`
--

INSERT INTO `detail_entry_form` (`maphieunhap`, `masp`, `dongianhap`, `soluongnhap`) VALUES
('EFO001', 'PRO001', 1000, 10),
('EFO002', 'PRO001', 1000, 10),
('EFO002', 'PRO031', 1000, 10);

--
-- Triggers `detail_entry_form`
--
DELIMITER $$
CREATE TRIGGER `after_insert_detail_entry_form` AFTER INSERT ON `detail_entry_form` FOR EACH ROW BEGIN
	DECLARE profit DECIMAL(5,2);

	SELECT loinhuan
    INTO profit
    FROM entry_form
    WHERE maphieunhap= NEW.maphieunhap;
    
    UPDATE product SET soluong = soluong + NEW.soluongnhap, dongiasanpham = ROUND(NEW.dongianhap * (1 + profit / 100), -2) where masp = NEW.masp;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `entry_form`
--

CREATE TABLE `entry_form` (
  `maphieunhap` varchar(20) NOT NULL,
  `ngaynhap` datetime(6) DEFAULT NULL,
  `mancc` varchar(20) NOT NULL,
  `mastaff` varchar(20) NOT NULL,
  `loinhuan` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `entry_form`
--

INSERT INTO `entry_form` (`maphieunhap`, `ngaynhap`, `mancc`, `mastaff`, `loinhuan`) VALUES
('EFO001', '2025-04-26 00:41:05.000000', 'SUP001', 'STAFF001', 0),
('EFO002', '2025-04-26 00:41:16.000000', 'SUP001', 'STAFF001', 20);

--
-- Triggers `entry_form`
--
DELIMITER $$
CREATE TRIGGER `before_insert_entry_form` BEFORE INSERT ON `entry_form` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(maphieunhap,4) AS UNSIGNED)),0) + 1 INTO new_id FROM entry_form;
    SET NEW.maphieunhap = CONCAT('EFO', LPAD(CAST(new_id AS CHAR),3,'0')); 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `func`
--

CREATE TABLE `func` (
  `funcid` varchar(20) NOT NULL,
  `funcname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `func`
--

INSERT INTO `func` (`funcid`, `funcname`) VALUES
('FUNC001', 'Quản lý sản phẩm'),
('FUNC002', 'Quản lý đơn hàng');

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

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `maorder` varchar(20) NOT NULL,
  `magiohang` varchar(20) NOT NULL,
  `mauser` varchar(20) NOT NULL,
  `address_deli` varchar(20) DEFAULT NULL,
  `address_id` varchar(20) DEFAULT NULL,
  `mabill` varchar(20) DEFAULT NULL,
  `status` enum('0','1','2','3') DEFAULT '0',
  `status_change_timestamp` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`maorder`, `magiohang`, `mauser`, `address_deli`, `address_id`, `mabill`, `status`, `status_change_timestamp`, `created_at`) VALUES
('ORD001', 'CART001', 'CUS001', 'ADDR001', 'ADDR001', 'BIL001', '1', '2025-04-21 15:46:49', '2025-04-21 15:21:58'),
('ORD002', 'CART002', 'CUS002', 'ADDR002', 'ADDR002', 'BIL002', '1', '2025-04-21 15:21:58', '2025-04-21 15:21:58');

-- --------------------------------------------------------

--
-- Table structure for table `payby`
--

CREATE TABLE `payby` (
  `mapayby` varchar(20) NOT NULL,
  `paybyname` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`details`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payby`
--

INSERT INTO `payby` (`mapayby`, `paybyname`, `address`, `details`) VALUES
('PAY001', 'Tiền mặt', 'Cửa hàng', '{}'),
('PAY002', 'Thẻ tín dụng', 'Cửa hàng', '{}');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `permissionid` varchar(20) NOT NULL,
  `permissionname` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `powergroup`
--

CREATE TABLE powergroup (
    powergroupid INT PRIMARY KEY AUTO_INCREMENT,
    powergroupname VARCHAR(255),
    status INT DEFAULT 1,
    created_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `powergroup`
--

INSERT INTO `powergroup` (`powergroupid`, `powergroupname`) VALUES
('GRP001', 'Quản trị viên');

--
-- Triggers `powergroup`
--
DELIMITER $$
CREATE TRIGGER `before_insert_powergroup` BEFORE INSERT ON `powergroup` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(powergroupid,4) AS UNSIGNED)), 0) + 1 
    INTO new_id 
    FROM powergroup;
    
    SET NEW.powergroupid = CONCAT('GRP', LPAD(CAST(new_id AS CHAR), 3, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `powergroup_func`
--

CREATE TABLE `powergroup_func` (
  `powergroupid` varchar(20) NOT NULL,
  `funcid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `powergroup_func`
--

INSERT INTO `powergroup_func` (`powergroupid`, `funcid`) VALUES
('PG001', 'FUNC001'),
('PG001', 'FUNC002');

-- --------------------------------------------------------

--
-- Table structure for table `powergroup_func_permission`
--

CREATE TABLE `powergroup_func_permission` (
  `powergroupid` varchar(20) NOT NULL,
  `funcid` varchar(20) NOT NULL,
  `permissionid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `masp` varchar(20) NOT NULL,
  `tensp` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `dongiasanpham` int(11) DEFAULT NULL,
  `soluong` int(11) DEFAULT NULL,
  `content` mediumtext DEFAULT NULL,
  `created_time` int(11) DEFAULT NULL,
  `last_updated` int(11) DEFAULT NULL,
  `maloaisp` varchar(20) DEFAULT NULL,
  `mancc` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`masp`, `tensp`, `image`, `dongiasanpham`, `soluong`, `content`, `created_time`, `last_updated`, `maloaisp`, `mancc`) VALUES
('PRO001', 'Cây cà phê', 'img/caycafe.jpg', 150000, 99, 'Cafe cũng là dòng cây trồng trong nhà mới được yêu thích trong thời gian gần đây. Trái ngược với những cây cafe công nghiệp phục vụ mục đích thu hoạch có kích thước lớn, dòng cây dùng làm cảnh có thể đặt vừa trong một chiếc ly. Lá cây xanh đậm, dày dặn, tạo cảm giác rất xanh tốt. Vào khoảng tháng 11 đến tháng 4 là mùa cafe ra hoa. Nếu chăm sóc tốt, bạn có thể ngắm loài hoa cafe trắng muốt với hương thơm toả nhẹ vô cùng dễ chịu.', 1745223237, 1745223237, 'TYP001', 'SUP001'),
('PRO002', 'Cây cam họ quýt', 'img/caycamquytchanh.jpg', 180000, 150, 'Không chỉ mang yếu tố may mắn theo quan niệm Á đông, các dòng cây cam quýt còn đẹp mắt và dễ trồng; rất thích hợp làm cây trồng trong nhà. Cây cam quýt có mùi thơm nhẹ nhàng và dễ chịu, giúp không gian nhà luôn sảng khoái, tươi mới. Trong ẩm thực Việt, lá cây cũng rất hữu ích. Đây là loại cây được các bà các mẹ ưa chuộng trồng từ lâu trong đời sống người Việt.', 1745223237, 1745223237, 'TYP006', 'SUP002'),
('PRO003', 'Cây cọ cảnh', 'img/caycocanh.jpg', 300000, 200, 'Nếu bạn tìm loại cây cao để đặt ở loggia, góc phòng, chân cầu thang,…thì cọ cảnh sẽ là lựa chọn số 1. Loài cây này mang vẻ đẹp đặc trưng của xứ nhiệt đới với lá nhọn và cành dài. Cọ cảnh thích hợp cho mọi phong cách nội thất, đặc biệt là phong cách tối giản và Scandinavia.', 1745223237, 1745223237, 'TYP002', 'SUP003'),
('PRO004', 'Cây dây nhện', 'img/caydaynhen.jpg', 120000, 180, 'Tuy có cái tên không được “lãng mạn” nhưng cây dây nhện lại có vẻ đẹp khá ấn tượng với những lá dài màu gân lá nổi bật. Cây dây nhện có khả năng quang hợp mạnh mẽ trong tình trạng ánh sáng tối thiểu. Bởi vậy, đây là loại cây rất thích hợp trồng trong nhà. Bên cạnh đó, khả năng lọc không khí của cây được đánh giá cao. Một chậu cây cỡ trung bình là đủ cho căn phòng 200m2.', 1745223360, 1745223360, 'TYP001', 'SUP004'),
('PRO005', 'Cây dương xỉ', 'img/cayduongxi.jpg', 130000, 120, 'Những năm gần đây, xương xỉ đã dần trở thành một loại cây cảnh được ưa thích. Vốn là loại cây mọc dại, dương xỉ không cần đất hay nhiều ánh sáng. Chỉ cần có độ ẩm đủ cao là cây có thể phát triển xanh tốt. Đặc biệt, đây là loại cây có kích thước khá nhỏ, thích hợp để bàn làm việc. Dương xỉ cũng nằm trong nhóm những loại cây có chức năng lọc độc trong không khí. Điển hình nhất là loại bỏ thuỷ ngân và asen.', 1745223360, 1745223360, 'TYP001', 'SUP005'),
('PRO006', 'Cây lan ý', 'img/caylany.jpg', 250000, 250, 'Lan ý là một trong những dòng cây ra hoa hiếm hoi làm cây trồng trong nhà. Hoa lan ý có màu trắng, căng phồng lên tựa như một cánh buồm. Lá cây xanh mướt rất đẹp mắt. Đây còn là dòng cây có khả năng lọc khí độc cực tốt, giúp loại bỏ formaldehyde, benzen và trichloroethylene, CO2, ammoniac,…có trong không khí nhà bạn.', 1745223360, 1745223360, 'TYP005', 'SUP006'),
('PRO007', 'Cây sim', 'img/caysim.jpg', 170000, 140, 'Cây sim là loại cây thân gỗ nhỏ, thường mọc thành bụi, hoa màu tím đẹp mắt và có quả chín ăn được. Ngoài giá trị cảnh quan, cây sim còn có ý nghĩa phong thủy mang lại sự bền bỉ, mạnh mẽ và sức sống dồi dào.', 1745223383, 1745223383, 'TYP003', 'SUP007'),
('PRO008', 'Cây thường xuân', 'img/caythuongxuan.jpg', 180000, 160, 'Được mệnh danh là cỗ máy lọc không khí hoàn hảo. Trong 06 tiếng, cây sẽ loại bỏ 58% phân tử nấm mốc và 60% các chất độc xung quanh. Bản chất thường xuân là một dòng dây leo nên nó phù hợp nhất cho các vị trí cạnh cửa sổ hoặc ngoài ban công. Bạn có thể dễ dàng nhận ra dòng cây này bởi sự phổ biến của nó.', 1745223383, 1745223383, 'TYP002', 'SUP008'),
('PRO009', 'Cây tuyến tùng', 'img/caytuyettung.jpg', 220000, 130, 'Cây tuyết tùng xuất phát từ Nhật Bản, mang ý nghĩa vô cùng thiêng liêng. Người Nhật Bản cho rằng trong mỗi cây tuyết tùng đều ẩn chứa một vị thần phù hộ cho gia chủ. Về mặt thẩm mỹ, đây là một dòng cây bonsai cỡ nhỏ vô cùng xinh xắn, thích hợp làm cây trồng trong nhà.', 1745223383, 1745223383, 'TYP003', 'SUP009'),
('PRO010', 'Cây vạn niên thanh', 'img/cayvannienthanh.jpg', 230000, 110, 'Cây vạn niên thanh là loại cây cảnh rất dễ trồng trong nhà với nhiều kích thước lựa chọn. Đây là loại cây ưa bóng râm và cần ít ẩm; nên mỗi tuần bạn chỉ nên tưới nước từ 1 đến 2 lần. Lá cây có màu trắng ở gân lá, chuyển xanh dần ra phần viền lá rất đẹp mắt.', 1745223426, 1745223426, 'TYP003', 'SUP010'),
('PRO011', 'Cẩm tú cầu', 'img/camtucau.png', NULL, NULL, 'Cây cẩm tú cầu nổi bật với những cụm hoa tròn lớn, nhiều màu sắc như hồng, xanh, tím... Cây tượng trưng cho sự biết ơn và lòng chân thành, thích hợp làm cây trang trí sân vườn hoặc ban công.', 1745223426, 1745223426, 'TYP004', 'SUP001'),
('PRO012', 'Cây kim ngân', 'img/caykimngan.jpg', 400000, 200, 'Cây kim ngân là loại cây thân dẻo được ưa chuộng làm cây trồng trong nhà. Cây thường được trồng thành cụm. Các thân cây đan vào nhau mà vươn lên như tết tóc khá lạ mắt. Lá cây xoè ra 5 nhánh, tượng trưng cho ngũ hành: Kim – Mộc – Thuỷ – Hoả – Thổ. Do vậy, kim ngân mang ý nghĩa mọi điều đều thuận lợi, tốt đẹp.', 1745223426, 1745223426, 'TYP003', 'SUP002'),
('PRO013', 'Cây kim tiền', 'img/caykimtien.jpg', 300000, 170, 'Cây kim tiền là loại cây phong thủy rất được ưa chuộng bởi tên gọi và dáng cây mang lại cảm giác giàu sang, phú quý. Cây có thân to, mọng nước, lá xanh bóng tượng trưng cho tiền tài và tài lộc.', 1745223477, 1745223477, 'TYP003', 'SUP003'),
('PRO014', 'Cây lưỡi hổ', 'img/cayluoiho.jpg', 220000, 210, 'Trái ngược với đa số cây xanh trên thế giới, cây lưỡi hổ hấp thụ khí CO2 và thải O2 vào ban đêm. Điều này giúp nó trở thành loại cây cực kỳ thích hợp cho phòng ngủ. Không ngạc nhiên khi đây là cây trồng trong nhà được dùng nhiều nhất hiện nay.', 1745223477, 1745223477, 'TYP001', 'SUP004'),
('PRO015', 'Cây phát tài', 'img/cayphattai.jpg', 450000, 220, 'Cây phát tài có hình dáng đặc biệt với phần thân thường được uốn cong hoặc tết bím. Cây có ý nghĩa mang đến tài lộc, may mắn và thịnh vượng cho gia chủ, thường được trưng bày trong nhà hoặc văn phòng.', 1745223477, 1745223477, 'TYP003', 'SUP005'),
('PRO016', 'Cây sen đá', 'img/caysenda.jpg', 80000, 140, 'Cây sen đá có hình dáng nhỏ gọn, lá mọng nước và sắp xếp như hoa sen. Cây tượng trưng cho sự bền vững và trường tồn, rất dễ chăm sóc, phù hợp làm cây để bàn hoặc quà tặng phong thủy.', 1745223586, 1745223586, 'TYP004', 'SUP006'),
('PRO017', 'Cây trầu bà', 'img/caytrauba.jpg', 180000, 130, 'Trầu bà rất dễ trồng và không cần chăm sóc nhiều. Trong điều kiện thiếu nước và dưỡng chất, cây vẫn có thể phát triển tốt. Vốn là loài cây cây leo, trầu bà thích hợp trồng ở bên cửa sổ, trên kệ tủ hoặc kết hợp bể thuỷ sinh cũng rất đẹp mắt.', 1745223586, 1745223586, 'TYP005', 'SUP007'),
('PRO018', 'Cây ngũ gia bì', 'img/cayngugiabi.jpg', 250000, 100, 'Cây ngũ gia bì hay còn có tên gọi khác là cây chân chim, cây đáng. Cây có tên khoa học là Schefflera heptaphylla. Cây thân gỗ, có chiều cao từ nhỏ đến trung bình, có thể cao tối đa đến 15m. Lá kép chân vịt, có 6-8 lá chét, hoa nhỏ màu trắng đẹp. Cây có mùi thơm nhẹ, mùi xạ hương. Trong một nghiên cứu thì mùi thơm phát ra từ cây có công dụng xua đuổi côn trùng như muỗi rất hiệu quả.', 1745223586, 1745223586, 'TYP001', 'SUP001'),
('PRO019', 'Cây hạnh phúc', 'img/cayhanhphuc.jpg', 200000, 100, 'Cây hạnh phúc là dòng cây cảnh đẹp, sức sống khỏe mạnh, dễ chăm sóc. Trên cây có những tán lá xanh tươi, mượt mà thể hiện cho sự hi vọng và niềm tin mạnh mẽ. Với ý nghĩa mang lại may mắn và hạnh phúc nên cây thường được chọn để làm cây trưng trong nhà hoặc làm quà tặng.', 1745223613, 1745223613, 'TYP001', 'SUP002'),
('PRO020', 'Cây phú quý', 'img/cayphuquy.jpg', 210000, 100, 'Phú Quý thuộc loài cây bụi, lan rất nhanh, có thể nhân giống bằng cách tách bụi. Cây sống được ở cả hai môi trường đất và nước. Gặp điều kiện chăm sóc thuận lợi, cây ra hoa từng cụm vàng được bao bọc trong mo hoa trắng muốt. Cây có tác dụng lọc không khí rất tốt, loại bỏ được formaldehyde, benzen, giảm bớt khói bụi cho môi trường sống trong lành hơn.', 1745223613, 1745223613, 'TYP001', 'SUP003'),
('PRO021', 'Cây trầu bà leo cột', 'img/caytraubaleocot.jpg', 300000, 100, 'Cây trầu bà leo cột còn có tên gọi khác là Cây Trầu Bà Xanh, cây Hoàng kim, Với kích thước khá lớn, đây chắc hẳn là một trong những công cụ đắc lực để tạo thêm mảng xanh cho không gian của bạn. Với sức sống mãnh liệt, dễ sinh tồn, trong phong thủy nó có ý nghĩa mang đến bình an, sung túc, may mắn, thể hiện sự mạnh mẽ và ý chí vươn lên của gia chủ.', 1745223613, 1745223613, 'TYP001', 'SUP004'),
('PRO022', 'Cây đuôi công tím', 'img/cayduoicongtim.jpg', 190000, 100, 'Cây đuôi công còn giúp mang lại không khí tự nhiên xanh mát, và là biểu tượng cho quyền quý, may mắn nên được lựa chọn làm món quà tặng trong các dịp sinh nhật, lễ tết, ngày đặc biệt…', 1745223644, 1745223644, 'TYP001', 'SUP005'),
('PRO023', 'Cây bao thanh thiên', 'img/caybaothanhthien.jpg', 230000, 100, 'Cây bao thanh thiên có bộ lá sặc sỡ và khỏe khoắn nên rất thích hợp để trang hoàng thêm điểm nhấn cho không gian sống. Loại cây này còn được xe là tượng trưng cho sự ngay thẳng, chính trực trong phong thủy. Khi trồng sẽ giúp thu hút năng lượng tích cực, xua đuổi cái xấu.', 1745223644, 1745223644, 'TYP001', 'SUP006'),
('PRO024', 'Cây bàng Singapore', 'img/caybangsingapore.jpg', 700000, 100, 'Cây Bàng Singapore Lớn có thể dễ dàng nhận ra ở những góc quán cafe, bàn làm việc công. Với những chiếc lá căng bóng hình đàn vĩ cầm rất lớn, nhiều gân là hình chân chim nổi bật và sức sống rất mạnh mẽ.', 1745223644, 1745223644, 'TYP001', 'SUP007'),
('PRO025', 'Cây ngọc ngân', 'img/cayngocngan.jpg', 150000, 100, 'Cây ngọc ngân hay còn được gọi là cây Valentine, có tên khoa học là Aglaonema Oblongifolium. Đây là một loại cây thân thảo, thuộc họ Ráy, có nguồn gốc từ ở Châu Mỹ nhiệt đới, Trung Mỹ, Brazil,... Ở Đông Nam Á, loài cây này được trồng nhiều ở Việt Nam và Trung Quốc.', 1745223668, 1745223668, 'TYP001', 'SUP008'),
('PRO028', 'Cây lan chi', 'img/caylanchi.jpg', 150000, 100, 'Cây lan chi (còn gọi là cỏ mệnh môn) có lá dài xanh viền trắng, thường dùng làm cây treo hoặc trồng trong chậu nhỏ để bàn. Cây có khả năng lọc không khí, hấp thu khí độc và tạo không gian xanh mát trong nhà.', 1745223718, 1745223718, 'TYP001', 'SUP001'),
('PRO029', 'Cây trúc nhật', 'img/caytrucnhat.jpg', 150000, 100, 'Cây trúc Nhật có tên khoa học là Dracaena surculosa punctulata, là một loài cây cảnh phổ biến và được ưa chuộng trong giới yêu cây trồng. Đây là một loài cây có nguồn gốc từ khu vực Đông Nam Á, và được gọi bằng nhiều tên khác nhau như Tiểu Hồng Trúc, cây lưỡi hổ hay cây chân voi vì những đặc điểm riêng biệt của nó.', 1745223718, 1745223718, 'TYP001', 'SUP009'),
('PRO030', 'Cây đại phú gia', 'img/caydaiphugia.jpg', 150000, 100, 'Cây đại phú gia thuộc họ cây ráy, có tên khoa học là Aglaoocma SP. Loài cây này mọc phân bổ chủ yếu ở các nước có khí hậu nhiệt đới, ưa ẩm và bóng râm có nguồn gốc từ châu Mỹ.', 1745223718, 2025, 'TYP001', 'SUP010'),
('PRO031', NULL, NULL, NULL, NULL, NULL, 2025, 2025, NULL, NULL);

--
-- Triggers `product`
--
DELIMITER $$
CREATE TRIGGER `before_insert_product` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(masp,4) AS UNSIGNED)),0) + 1 INTO new_id FROM product;
    SET NEW.masp = CONCAT('PRO', LPAD(CAST(new_id AS CHAR),3,'0')); 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `producttype`
--

CREATE TABLE `producttype` (
  `maloaisp` varchar(20) NOT NULL,
  `tenloaisp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `producttype`
--

INSERT INTO `producttype` (`maloaisp`, `tenloaisp`) VALUES
('TYP001', 'Cây dễ chăm'),
('TYP002', 'Cây văn phòng'),
('TYP003', 'Cây phong thủy'),
('TYP004', 'Cây để bàn'),
('TYP005', 'Cây trồng nước'),
('TYP006', 'Cây cao cấp'),
('TYP007', 'Chậu đất nung'),
('TYP008', 'Chậu xi măng');

--
-- Triggers `producttype`
--
DELIMITER $$
CREATE TRIGGER `before_insert_producttype` BEFORE INSERT ON `producttype` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(maloaisp,4) AS UNSIGNED)),0) + 1 INTO new_id FROM producttype;
    SET NEW.maloaisp = CONCAT('TYP', LPAD(CAST(new_id AS CHAR),3,'0')); 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `product_cart`
--

CREATE TABLE `product_cart` (
  `masp` varchar(20) NOT NULL,
  `magiohang` varchar(20) NOT NULL,
  `soluong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_cart`
--

INSERT INTO `product_cart` (`masp`, `magiohang`, `soluong`) VALUES
('PRO001', 'CART001', 1),
('PRO002', 'CART002', 2),
('PRO003', 'CART680f31417e277', 1);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `mastaff` varchar(20) NOT NULL,
  `staffname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `powergroupid` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`mastaff`, `staffname`, `password`, `address`, `powergroupid`, `email`) VALUES
('STAFF001', 'Quản trị viên', 'password_hash', 'Địa chỉ quản trị', 'PG001', 'admin@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `mancc` varchar(20) NOT NULL,
  `tencc` varchar(25) NOT NULL,
  `diachi` varchar(100) NOT NULL,
  `dienthoai` varchar(11) NOT NULL,
  `sofax` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`mancc`, `tencc`, `diachi`, `dienthoai`, `sofax`) VALUES
('SUP001', 'Bao Company', '123/A', '1234567891', '12341231'),
('SUP002', 'Nhà cung cấp A', '123 Đường Cây', '0123456789', '0123456789'),
('SUP003', 'Nhà cung cấp B', '456 Đường Hoa', '0987654321', '0987654321'),
('SUP004', 'Nhà cung cấp A', '123 Đường Cây', '0123456789', '0123456789'),
('SUP005', 'Nhà cung cấp B', '456 Đường Hoa', '0987654321', '0987654321'),
('SUP006', 'Nhà cung cấp A', '123 Đường Cây', '0123456789', '0123456789'),
('SUP007', 'Nhà cung cấp B', '456 Đường Hoa', '0987654321', '0987654321'),
('SUP008', 'Nhà cung cấp A', '123 Đường Cây', '0123456789', '0123456789'),
('SUP009', 'Nhà cung cấp B', '456 Đường Hoa', '0987654321', '0987654321'),
('SUP010', 'Nhà cung cấp A', '123 Đường Cây', '0123456789', '0123456789'),
('SUP011', 'Nhà cung cấp B', '456 Đường Hoa', '0987654321', '0987654321'),
('SUP012', 'Nhà cung cấp A', '123 Đường Cây', '0123456789', '0123456789'),
('SUP013', 'Nhà cung cấp B', '456 Đường Hoa', '0987654321', '0987654321'),
('SUP014', 'Nhà cung cấp A', '123 Đường Cây', '0123456789', '0123456789'),
('SUP015', 'Nhà cung cấp B', '456 Đường Hoa', '0987654321', '0987654321'),
('SUP016', 'Nhà cung cấp A', '123 Đường Cây', '0123456789', '0123456789'),
('SUP017', 'Nhà cung cấp B', '456 Đường Hoa', '0987654321', '0987654321'),
('SUP018', 'Nhà cung cấp A', '123 Đường Cây', '0123456789', '0123456789'),
('SUP019', 'Nhà cung cấp B', '456 Đường Hoa', '0987654321', '0987654321'),
('SUP020', 'Nhà cung cấp A', '123 Đường Cây', '0123456789', '0123456789'),
('SUP021', 'Nhà cung cấp B', '456 Đường Hoa', '0987654321', '0987654321');

--
-- Triggers `supplier`
--
DELIMITER $$
CREATE TRIGGER `before_insert_supplier` BEFORE INSERT ON `supplier` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(mancc,4) AS UNSIGNED)),0) + 1 INTO new_id FROM supplier;
    SET NEW.mancc = CONCAT('SUP', LPAD(CAST(new_id AS CHAR),3,'0')); 
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`mabill`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`magiohang`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`macomment`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`macustomer`);

--
-- Indexes for table `detail_entry_form`
--
ALTER TABLE `detail_entry_form`
  ADD PRIMARY KEY (`maphieunhap`,`masp`);

--
-- Indexes for table `entry_form`
--
ALTER TABLE `entry_form`
  ADD PRIMARY KEY (`maphieunhap`);

--
-- Indexes for table `func`
--
ALTER TABLE `func`
  ADD PRIMARY KEY (`funcid`);

--
-- Indexes for table `image_library`
--
ALTER TABLE `image_library`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`maorder`);

--
-- Indexes for table `payby`
--
ALTER TABLE `payby`
  ADD PRIMARY KEY (`mapayby`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`permissionid`);

--
-- Indexes for table `powergroup`
--
ALTER TABLE `powergroup`
  ADD PRIMARY KEY (`powergroupid`);

--
-- Indexes for table `powergroup_func`
--
ALTER TABLE `powergroup_func`
  ADD PRIMARY KEY (`powergroupid`,`funcid`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`masp`);

--
-- Indexes for table `producttype`
--
ALTER TABLE `producttype`
  ADD PRIMARY KEY (`maloaisp`);

--
-- Indexes for table `product_cart`
--
ALTER TABLE `product_cart`
  ADD PRIMARY KEY (`masp`,`magiohang`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`mastaff`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`mancc`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `macomment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
