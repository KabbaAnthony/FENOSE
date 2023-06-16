-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 16, 2023 at 08:51 AM
-- Server version: 8.0.18
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fenose`
--

-- --------------------------------------------------------

--
-- Table structure for table `agent_table`
--

DROP TABLE IF EXISTS `agent_table`;
CREATE TABLE IF NOT EXISTS `agent_table` (
  `agent_id` int(20) NOT NULL AUTO_INCREMENT,
  `agent_name` varchar(20) NOT NULL,
  `agent_email` varchar(20) NOT NULL,
  `agent_phone` varchar(20) NOT NULL,
  `agent_bio` varchar(20) NOT NULL,
  PRIMARY KEY (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `booking_table`
--

DROP TABLE IF EXISTS `booking_table`;
CREATE TABLE IF NOT EXISTS `booking_table` (
  `booking_id` int(20) NOT NULL AUTO_INCREMENT,
  `property_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL,
  `booking_date` datetime NOT NULL,
  `visit_date` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `additional_notes` varchar(20) NOT NULL,
  PRIMARY KEY (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `listing_table`
--

DROP TABLE IF EXISTS `listing_table`;
CREATE TABLE IF NOT EXISTS `listing_table` (
  `listing_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `listing_date` datetime NOT NULL,
  `listing_status` varchar(20) NOT NULL,
  PRIMARY KEY (`listing_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `property_table`
--

DROP TABLE IF EXISTS `property_table`;
CREATE TABLE IF NOT EXISTS `property_table` (
  `property_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) NOT NULL,
  `property_title` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `property_description` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `property_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `property_price` decimal(20,0) NOT NULL,
  `property_bedrooms` int(20) NOT NULL,
  `property_bathrooms` int(20) NOT NULL,
  `property_area` varchar(20) NOT NULL,
  `property_location` varchar(20) NOT NULL,
  PRIMARY KEY (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `property_table`
--

INSERT INTO `property_table` (`property_id`, `user_id`, `property_title`, `property_description`, `property_type`, `property_price`, `property_bedrooms`, `property_bathrooms`, `property_area`, `property_location`) VALUES
(1, 1, 'Cozy Apartment', 'A beautiful and cozy apartment with stunning views.', 'Apartment', '150000', 2, 1, 'Western Area Rural', '10 Lumley Beach Road'),
(2, 2, 'Spacious Villa', 'A luxurious villa with ample space and modern amenities.', 'Villa', '500000', 4, 3, 'Western Area Urban', '12 Siaka Stevens Str'),
(3, 3, 'Charming Bungalow', 'A charming bungalow nestled in a peaceful neighborhood', 'Bungalow', '200000', 3, 2, 'Western Area Peninsu', 'Tokeh Beach Road');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `firstname` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `lastname` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `phone` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(50) NOT NULL,
  `reset_token` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `firstname`, `lastname`, `phone`, `password`, `reset_token`) VALUES
(4, 'admin', 'akbangura968@gmail.com', 'Kabba', 'Bangura', '078891732', 'D$T5^Tyo', ''),
(5, 'sad', 'samuelkabba182@gmail.com', 'Kabba', 'Bangura', '078891732', 'rpjem608', ''),
(6, 'natcom362', 'ateeq@webfulcreations.com', 'Kabba', 'Bangura', '078891732', 'nypB%bg@', ''),
(10, 'sad12323', 'bangurwsaakabba182@gmail.com', 'Kabba', 'Bangura', '078891732', 'oWy3WJ7o', ''),
(11, 'tony123', 'bangurakabba182@gmail.com', 'Kabba', 'Bangura', '078891732', '123456', ''),
(12, 'natcom1234', 'Nasir@gmail.com', 'Kabba', 'Bangura', '078891732', '654321', ''),
(14, 'Now223', 'now@gmail.com', 'Anthony', 'David', '0888123456', '654321', ''),
(15, 'Success123', 'akbangura98@gmail.com', 'Anthony  Kabba', 'Bangura', '074123456', '123456', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
