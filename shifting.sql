-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2015 at 03:40 PM
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
-- Table structure for table `shifted_rabbits`
--

DROP TABLE IF EXISTS `shifted_rabbits`;
CREATE TABLE IF NOT EXISTS `shifted_rabbits` (
`shift_id` int(11) NOT NULL,
  `rabbit_id` int(11) NOT NULL,
  `shifted_from` varchar(255) NOT NULL,
  `shifted_to` varchar(255) NOT NULL,
  `shifted_date` date NOT NULL,
  `shifting_reason` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `shifting_reasons`
--

DROP TABLE IF EXISTS `shifting_reasons`;
CREATE TABLE IF NOT EXISTS `shifting_reasons` (
`id` int(11) NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shifted_rabbits`
--
ALTER TABLE `shifted_rabbits`
 ADD PRIMARY KEY (`shift_id`);

--
-- Indexes for table `shifting_reasons`
--
ALTER TABLE `shifting_reasons`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shifted_rabbits`
--
ALTER TABLE `shifted_rabbits`
MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `shifting_reasons`
--
ALTER TABLE `shifting_reasons`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
