-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2015 at 11:38 AM
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
CREATE DATABASE IF NOT EXISTS `inventory` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `inventory`;

-- --------------------------------------------------------

--
-- Table structure for table `rabbit_still_birth`
--

DROP TABLE IF EXISTS `rabbit_still_birth`;
CREATE TABLE IF NOT EXISTS `rabbit_still_birth` (
`still_birth_id` int(11) NOT NULL,
  `rabbit_id` int(11) NOT NULL,
  `still_birth_date` date NOT NULL,
  `still_birth_reason` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rabbit_still_birth`
--

INSERT INTO `rabbit_still_birth` (`still_birth_id`, `rabbit_id`, `still_birth_date`, `still_birth_reason`) VALUES
(1, 15, '2015-02-22', 'Still birth by accident'),
(2, 15, '2015-02-22', 'Another Still Birth\r\n');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rabbit_still_birth`
--
ALTER TABLE `rabbit_still_birth`
 ADD PRIMARY KEY (`still_birth_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rabbit_still_birth`
--
ALTER TABLE `rabbit_still_birth`
MODIFY `still_birth_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
