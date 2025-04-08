-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 04:15 PM
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
-- Database: `tree_shopping`
--

-- --------------------------------------------------------

--
-- Table structure for table `func`
--

CREATE TABLE `func` (
  `funcid` varchar(20) NOT NULL,
  `funcname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `powergroup`
--

CREATE TABLE `powergroup` (
  `powergroupid` varchar(20) NOT NULL,
  `powergroupname` varchar(50) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `powergroup_func`
--

CREATE TABLE `powergroup_func` (
  `powergroupid` varchar(20) NOT NULL,
  `funcid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `func`
--
ALTER TABLE `func`
  ADD PRIMARY KEY (`funcid`);

--
-- Indexes for table `powergroup`
--
ALTER TABLE `powergroup`
  ADD PRIMARY KEY (`powergroupid`);

--
-- Indexes for table `powergroup_func`
--
ALTER TABLE `powergroup_func`
  ADD PRIMARY KEY (`powergroupid`,`funcid`),
  ADD KEY `fk_powergroup_func_func` (`funcid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `powergroup_func`
--
ALTER TABLE `powergroup_func`
  ADD CONSTRAINT `fk_powergroup_func_func` FOREIGN KEY (`funcid`) REFERENCES `func` (`funcid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_powergroup_func_powergroup` FOREIGN KEY (`powergroupid`) REFERENCES `powergroup` (`powergroupid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Trigger
CREATE TRIGGER `before_insert_powergroup` BEFORE INSERT ON `powergroup`
 FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(powergroupid,4) AS UNSIGNED)),0) + 1 INTO new_id FROM powergroup;
    SET NEW.powergroupid = CONCAT('GRP', LPAD(CAST(new_id AS CHAR),3,'0')); 
END
