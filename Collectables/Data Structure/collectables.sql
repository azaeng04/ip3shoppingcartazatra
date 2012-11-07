-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 07, 2012 at 06:27 PM
-- Server version: 5.5.24
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `collectables`
--

-- --------------------------------------------------------

--
-- Table structure for table `orderline`
--

CREATE TABLE IF NOT EXISTS `orderline` (
  `orderLineID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `prodID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`orderLineID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `orderID` int(11) NOT NULL,
  `orderDesc` varchar(255) NOT NULL,
  `orderDate` date NOT NULL,
  PRIMARY KEY (`orderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `prodID` int(11) NOT NULL,
  `prodName` varchar(255) NOT NULL,
  `prodPrice` double NOT NULL,
  `inStock` bigint(20) NOT NULL,
  `storeID` varchar(30) DEFAULT NULL,
  `imageURL` varchar(50) DEFAULT NULL,
  `prodDesc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`prodID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prodID`, `prodName`, `prodPrice`, `inStock`, `storeID`, `imageURL`, `prodDesc`) VALUES
(1, 'Ford', 21, 50, 'Cars', NULL, NULL),
(2, 'Toyota', 22, 50, 'Cars', NULL, NULL),
(3, 'Mustang', 23, 50, 'Cars', NULL, NULL),
(4, 'Ferrari', 24, 50, 'Cars', NULL, NULL),
(5, 'BMW', 25, 50, 'Cars', NULL, NULL),
(6, 'Hummer', 26, 50, 'Cars', NULL, NULL),
(7, 'Nissan', 27, 50, 'Cars', NULL, NULL),
(8, 'Mazda', 28, 50, 'Cars', NULL, NULL),
(9, 'Lamborghini', 29, 50, 'Cars', NULL, NULL),
(10, 'Chrysler', 30, 50, 'Cars', NULL, NULL),
(11, 'Avengers', 30, 50, 'Comics', NULL, NULL),
(12, 'Superman', 31, 50, 'Comics', NULL, NULL),
(13, 'Cat Woman', 32, 50, 'Comics', NULL, NULL),
(14, 'Iron Man', 33, 50, 'Comics', NULL, NULL),
(15, 'James Bond', 34, 50, 'Comics', NULL, NULL),
(16, 'Naruto Shippuuden', 35, 50, 'Comics', NULL, NULL),
(17, 'Bleach', 36, 50, 'Comics', NULL, NULL),
(18, 'The one who got away', 37, 50, 'Comics', NULL, NULL),
(19, 'Spiderman', 38, 50, 'Comics', NULL, NULL),
(20, 'Ninja', 39, 50, 'Comics', NULL, NULL),
(21, 'Superman', 40, 50, 'Action Figures', NULL, NULL),
(22, 'Naruto', 41, 50, 'Action Figures', NULL, NULL),
(23, 'Bleach', 42, 50, 'Action Figures', NULL, NULL),
(24, 'Iron Man', 43, 50, 'Action Figures', NULL, NULL),
(25, 'Ninja', 44, 50, 'Action Figures', NULL, NULL),
(26, 'Dragon', 45, 50, 'Action Figures', NULL, NULL),
(27, 'The Hulk', 46, 50, 'Action Figures', NULL, NULL),
(28, 'Spiderman', 47, 50, 'Action Figures', NULL, NULL),
(29, 'Chuck Norris', 48, 50, 'Action Figures', NULL, NULL),
(30, 'Monkey', 49, 50, 'Action Figures', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `storeinfo`
--

CREATE TABLE IF NOT EXISTS `storeinfo` (
  `storeID` varchar(30) NOT NULL DEFAULT '',
  `storeName` varchar(255) DEFAULT NULL,
  `storeDesc` varchar(100) DEFAULT NULL,
  `welcomeMsg` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`storeID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `storeinfo`
--

INSERT INTO `storeinfo` (`storeID`, `storeName`, `storeDesc`, `welcomeMsg`) VALUES
('Action Figures', 'Figurines', 'Figurines provides you with the figurines in mint condition', 'Welcome to Figurines'),
('Cars', 'Car Collectables', 'Car Collectables provides you with the outstanding range of cars to collect', 'Welcome to Car Collectables'),
('Comics', 'Comicon', 'Comicon is provides you with the comic book with your favourite characters', 'Welcome to Comicon');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
