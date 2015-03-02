-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2015 at 07:03 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `purchase_suppliers`
--

DROP TABLE IF EXISTS `purchase_suppliers`;
CREATE TABLE IF NOT EXISTS `purchase_suppliers` (
`supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(75) NOT NULL,
  `supplier_address` text NOT NULL,
  `supplier_email` varchar(50) NOT NULL,
  `supplier_phone` varchar(20) NOT NULL,
  `is_active` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_suppliers`
--

INSERT INTO `purchase_suppliers` (`supplier_id`, `supplier_name`, `supplier_address`, `supplier_email`, `supplier_phone`, `is_active`) VALUES
(1, 'New supplier', 'baluwatar', 'new_supplier@test.com', '1234567890', '1'),
(2, 'New supplier1', 'baluwatar', 'new_supplier@test.com1', '1234567890', '0'),
(3, 'Supplier 3', 'address3', 'email@3@supplier3.com', '1234567890', '0'),
(4, 'Supplier 4', 'address 4', 'email4@supplier4.com', '1234567890123', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `purchase_suppliers`
--
ALTER TABLE `purchase_suppliers`
 ADD PRIMARY KEY (`supplier_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `purchase_suppliers`
--
ALTER TABLE `purchase_suppliers`
MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
