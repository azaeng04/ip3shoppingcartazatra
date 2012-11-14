-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 14, 2012 at 06:40 AM
-- Server version: 5.5.24-log
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
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `customerID` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `address` varchar(60) NOT NULL,
  PRIMARY KEY (`customerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerID`, `firstName`, `lastName`, `address`) VALUES
(1, 'John', 'Doe', '187 Klip Fontein'),
(2, 'James', 'Seacrest', '562 Johnson Street'),
(3, 'Anton', 'Davis', '432 Ravensmead Avenue'),
(4, 'Rick', 'Sanchez', '543 Oliver Street'),
(5, 'Dave', 'Jacobs', '543 Valley Day Avenue');

-- --------------------------------------------------------

--
-- Table structure for table `logininfo`
--

DROP TABLE IF EXISTS `logininfo`;
CREATE TABLE IF NOT EXISTS `logininfo` (
  `customerID` int(11) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`customerID`),
  UNIQUE KEY `password` (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logininfo`
--

INSERT INTO `logininfo` (`customerID`, `password`) VALUES
(1, 'customer1'),
(2, 'customer2'),
(3, 'customer3'),
(4, 'customer4'),
(5, 'customer5');

-- --------------------------------------------------------

--
-- Table structure for table `orderline`
--

DROP TABLE IF EXISTS `orderline`;
CREATE TABLE IF NOT EXISTS `orderline` (
  `orderLineID` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `prodID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`orderLineID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderline`
--

INSERT INTO `orderline` (`orderLineID`, `orderID`, `prodID`, `quantity`) VALUES
(1, 1, 21, 3),
(2, 1, 22, 2),
(3, 1, 23, 1),
(4, 2, 21, 1),
(5, 2, 22, 1),
(6, 2, 24, 1),
(7, 3, 21, 1),
(8, 3, 22, 1),
(9, 3, 23, 1),
(10, 4, 21, 1),
(11, 4, 22, 1),
(12, 4, 23, 1),
(13, 4, 1, 1),
(14, 4, 2, 1),
(15, 5, 21, 1),
(16, 5, 22, 1),
(17, 5, 23, 1),
(18, 5, 1, 1),
(19, 5, 2, 1),
(20, 6, 21, 1),
(21, 6, 22, 1),
(22, 6, 23, 1),
(23, 7, 1, 1),
(24, 7, 2, 1),
(25, 8, 21, 1),
(26, 8, 22, 1),
(27, 8, 23, 1),
(28, 9, 21, 1),
(29, 9, 22, 1),
(30, 9, 23, 1),
(31, 9, 24, 1),
(32, 9, 25, 1),
(33, 10, 21, 1),
(34, 10, 22, 1),
(35, 10, 23, 1),
(36, 10, 24, 1),
(37, 10, 25, 1),
(38, 11, 21, 1),
(39, 11, 22, 1),
(40, 12, 21, 1),
(41, 12, 22, 1),
(42, 12, 1, 1),
(43, 12, 2, 1),
(44, 13, 21, 1),
(45, 13, 22, 1),
(46, 13, 1, 1),
(47, 13, 2, 1),
(48, 14, 21, 1),
(49, 14, 22, 1),
(50, 14, 1, 1),
(51, 14, 2, 1),
(52, 15, 21, 1),
(53, 15, 22, 1),
(54, 16, 21, 1),
(55, 16, 22, 1),
(56, 16, 1, 1),
(57, 16, 2, 1),
(58, 17, 21, 1),
(59, 17, 22, 1),
(60, 17, 1, 1),
(61, 17, 2, 1),
(62, 18, 21, 1),
(63, 18, 22, 1),
(64, 18, 1, 1),
(65, 18, 2, 1),
(66, 19, 11, 1),
(67, 19, 12, 1),
(68, 19, 13, 1),
(69, 20, 21, 1),
(70, 20, 22, 1),
(71, 21, 21, 1),
(72, 21, 22, 1),
(73, 22, 21, 1),
(74, 22, 22, 1),
(75, 22, 14, 1),
(76, 22, 15, 1),
(77, 23, 21, 1),
(78, 23, 22, 1),
(79, 23, 14, 1),
(80, 23, 15, 1),
(81, 24, 21, 1),
(82, 24, 22, 1),
(83, 25, 21, 1),
(84, 25, 22, 1),
(85, 26, 21, 2),
(86, 26, 22, 1),
(87, 27, 21, 2),
(88, 27, 22, 1),
(89, 27, 23, 1),
(90, 28, 21, 2),
(91, 28, 22, 1),
(92, 28, 23, 1),
(93, 29, 21, 1),
(94, 29, 22, 1),
(95, 29, 23, 1),
(96, 30, 21, 1),
(97, 30, 22, 1),
(98, 30, 23, 1),
(99, 31, 7, 2),
(100, 31, 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `orderID` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `customerID` int(11) NOT NULL,
  PRIMARY KEY (`orderID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `orderDate`, `customerID`) VALUES
(1, '2012-11-13', 1),
(2, '2012-11-13', 1),
(3, '2012-11-13', 1),
(4, '2012-11-13', 1),
(5, '2012-11-13', 1),
(6, '2012-11-13', 1),
(7, '2012-11-13', 1),
(8, '2012-11-13', 1),
(9, '2012-11-13', 1),
(10, '2012-11-13', 1),
(11, '2012-11-13', 1),
(12, '2012-11-13', 1),
(13, '2012-11-13', 1),
(14, '2012-11-13', 1),
(15, '2012-11-13', 1),
(16, '2012-11-13', 1),
(17, '2012-11-13', 1),
(18, '2012-11-13', 1),
(19, '2012-11-13', 1),
(20, '2012-11-13', 1),
(21, '2012-11-13', 1),
(22, '2012-11-13', 1),
(23, '2012-11-13', 1),
(24, '2012-11-13', 1),
(25, '2012-11-13', 1),
(26, '2012-11-13', 1),
(27, '2012-11-13', 1),
(28, '2012-11-13', 1),
(29, '2012-11-13', 1),
(30, '2012-11-13', 1),
(31, '2012-11-13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
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
(1, 'Ford', 21, 41, 'Cars', 'ford_mondeo', 'This is a Ford collectable'),
(2, 'Toyota', 22, 41, 'Cars', 'toyota_yaris', 'This is a Toyota collectable'),
(3, 'Mustang', 23, 50, 'Cars', 'mustang', 'This is a Mustang collectable'),
(4, 'Ferrari', 24, 50, 'Cars', 'ferrari', 'This is a Ferrari collectable'),
(5, 'BMW', 25, 50, 'Cars', NULL, 'This is a BMW collectable'),
(6, 'Hummer', 26, 50, 'Cars', 'hummer', 'This is a Hummer collectable'),
(7, 'Nissan', 27, 48, 'Cars', NULL, 'This is a Nissan collectable'),
(8, 'Mazda', 28, 49, 'Cars', 'mazda', 'This is a Mazda collectable'),
(9, 'Lamborghini', 29, 50, 'Cars', 'lamborghini', 'This is a Lamborghini collectable'),
(10, 'Chrysler', 30, 50, 'Cars', 'chrysler', 'This is a Chrysler collectable'),
(11, 'The Avengers', 30, 49, 'Comics', NULL, 'Comic about The Avengers'),
(12, 'Superman', 31, 49, 'Comics', NULL, 'Comic about Spiderman'),
(13, 'Cat Woman', 32, 49, 'Comics', NULL, 'Comic about Cat Woman'),
(14, 'Iron Man', 33, 48, 'Comics', NULL, 'Comic about Iron Man'),
(15, 'James Bond', 34, 48, 'Comics', NULL, 'Comic about James Bond'),
(16, 'Naruto Shippuuden', 35, 50, 'Comics', NULL, 'Comic about Naruto Shippuuden'),
(17, 'Bleach', 36, 50, 'Comics', NULL, 'Comic about Bleach'),
(18, 'Ninja Kid', 37, 50, 'Comics', NULL, 'Comic about Ninja Kid'),
(19, 'Spiderman', 38, 50, 'Comics', 'spiderman', 'Comic about Spiderman'),
(20, 'Ninja Girl', 39, 50, 'Comics', NULL, 'Comic about Ninja Girl'),
(21, 'Superman', 40, 17, 'Action Figures', NULL, 'This is a Superman action figure'),
(22, 'Naruto', 41, 21, 'Action Figures', NULL, 'This is a Naruto action figure'),
(23, 'Bleach', 42, 38, 'Action Figures', NULL, 'This is a Bleach action figure'),
(24, 'Iron Man', 43, 47, 'Action Figures', 'iron_man', 'This is a Iron Man action figure'),
(25, 'Ninja Kid', 44, 48, 'Action Figures', NULL, 'This is a Ninja Kid action figure'),
(26, 'Dragon Samurai', 45, 50, 'Action Figures', NULL, 'This is a Dragon Samurai action figure'),
(27, 'The Hulk', 46, 50, 'Action Figures', NULL, 'This is a The Hulk action figure'),
(28, 'Spiderman', 47, 50, 'Action Figures', NULL, 'This is a Spiderman action figure'),
(29, 'Chuck Norris', 48, 50, 'Action Figures', 'chuck', 'This is a Chuck Norris action figure'),
(30, 'Monkey', 49, 50, 'Action Figures', NULL, 'This is a Monkey action figure');

-- --------------------------------------------------------

--
-- Table structure for table `storeinfo`
--

DROP TABLE IF EXISTS `storeinfo`;
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
