-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 13, 2012 at 12:38 PM
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
  `orderDate` date NOT NULL,
  `customerID` int(11) NOT NULL,
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
(1, 'Ford', 21, 50, 'Cars', NULL, 'This is a Ford collectable'),
(2, 'Toyota', 22, 50, 'Cars', NULL, 'This is a Toyota collectable'),
(3, 'Mustang', 23, 50, 'Cars', NULL, 'This is a Mustang collectable'),
(4, 'Ferrari', 24, 50, 'Cars', NULL, 'This is a Ferrari collectable'),
(5, 'BMW', 25, 50, 'Cars', NULL, 'This is a BMW collectable'),
(6, 'Hummer', 26, 50, 'Cars', NULL, 'This is a Hummer collectable'),
(7, 'Nissan', 27, 50, 'Cars', NULL, 'This is a Nissan collectable'),
(8, 'Mazda', 28, 50, 'Cars', NULL, 'This is a Mazda collectable'),
(9, 'Lamborghini', 29, 50, 'Cars', NULL, 'This is a Lamborghini collectable'),
(10, 'Chrysler', 30, 50, 'Cars', NULL, 'This is a Chrysler collectable'),
(11, 'The Avengers', 30, 50, 'Comics', NULL, 'Comic about The Avengers'),
(12, 'Superman', 31, 50, 'Comics', NULL, 'Comic about Spiderman'),
(13, 'Cat Woman', 32, 50, 'Comics', NULL, 'Comic about Cat Woman'),
(14, 'Iron Man', 33, 50, 'Comics', NULL, 'Comic about Iron Man'),
(15, 'James Bond', 34, 50, 'Comics', NULL, 'Comic about James Bond'),
(16, 'Naruto Shippuuden', 35, 50, 'Comics', NULL, 'Comic about Naruto Shippuuden'),
(17, 'Bleach', 36, 50, 'Comics', NULL, 'Comic about Bleach'),
(18, 'Ninja Kid', 37, 50, 'Comics', NULL, 'Comic about Ninja Kid'),
(19, 'Spiderman', 38, 50, 'Comics', NULL, 'Comic about Spiderman'),
(20, 'Ninja Girl', 39, 50, 'Comics', NULL, 'Comic about Ninja Girl'),
(21, 'Superman', 40, 50, 'Action Figures', NULL, 'This is a Superman action figure'),
(22, 'Naruto', 41, 50, 'Action Figures', NULL, 'This is a Naruto action figure'),
(23, 'Bleach', 42, 50, 'Action Figures', NULL, 'This is a Bleach action figure'),
(24, 'Iron Man', 43, 50, 'Action Figures', NULL, 'This is a Iron Man action figure'),
(25, 'Ninja Kid', 44, 50, 'Action Figures', NULL, 'This is a Ninja Kid action figure'),
(26, 'Dragon Samurai', 45, 50, 'Action Figures', NULL, 'This is a Dragon Samurai action figure'),
(27, 'The Hulk', 46, 50, 'Action Figures', NULL, 'This is a The Hulk action figure'),
(28, 'Spiderman', 47, 50, 'Action Figures', NULL, 'This is a Spiderman action figure'),
(29, 'Chuck Norris', 48, 50, 'Action Figures', NULL, 'This is a Chuck Norris action figure'),
(30, 'Monkey', 49, 50, 'Action Figures', NULL, 'This is a Monkey action figure');

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
