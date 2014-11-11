-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 18, 2013 at 03:07 PM
-- Server version: 5.5.31-0ubuntu0.12.10.1
-- PHP Version: 5.4.6-1ubuntu1.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `biblioteca_virtual`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE IF NOT EXISTS `author` (
  `idauthor` int(12) NOT NULL AUTO_INCREMENT,
  `author_name` char(120) DEFAULT NULL,
  `author_surname` char(120) DEFAULT NULL,
  `author_enabled` int(1) DEFAULT NULL,
  PRIMARY KEY (`idauthor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`idauthor`, `author_name`, `author_surname`, `author_enabled`) VALUES
(2, 'Eddy Oscar', 'Lecca Ricra', 1);

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE IF NOT EXISTS `book` (
  `idbook` int(12) NOT NULL,
  `book_data` text,
  `book_enabled` int(1) DEFAULT NULL,
  PRIMARY KEY (`idbook`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `editorial`
--

CREATE TABLE IF NOT EXISTS `editorial` (
  `ideditorial` int(12) NOT NULL,
  `editorial_name` char(120) DEFAULT NULL,
  `editorial_enabled` int(1) DEFAULT NULL,
  PRIMARY KEY (`ideditorial`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `exemplary`
--

CREATE TABLE IF NOT EXISTS `exemplary` (
  `idexemplary` int(12) NOT NULL,
  `exemplary_number` int(6) DEFAULT NULL,
  `exemplary_status` int(1) DEFAULT NULL,
  `idbook` int(12) NOT NULL,
  PRIMARY KEY (`idexemplary`,`idbook`),
  KEY `fk_exemplary_book1` (`idbook`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `languaje`
--

CREATE TABLE IF NOT EXISTS `languaje` (
  `idlanguaje` int(12) NOT NULL,
  `languaje_description` char(120) DEFAULT NULL,
  PRIMARY KEY (`idlanguaje`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE IF NOT EXISTS `loan` (
  `idloan` int(12) NOT NULL,
  `loan_date` datetime DEFAULT NULL,
  `loan_time` time DEFAULT NULL,
  `loan_theoretical` date DEFAULT NULL,
  `loan_real` date DEFAULT NULL,
  `loan_datereturn` date DEFAULT NULL,
  `loan_datemaxim` date DEFAULT NULL,
  `idusers` int(12) NOT NULL,
  `idexemplary` int(12) NOT NULL,
  PRIMARY KEY (`idloan`,`idusers`,`idexemplary`),
  KEY `fk_loan_users` (`idusers`),
  KEY `fk_loan_exemplary1` (`idexemplary`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `theme`
--

CREATE TABLE IF NOT EXISTS `theme` (
  `idtheme` int(12) NOT NULL,
  `theme_description` char(120) DEFAULT NULL,
  `theme_enabled` int(1) DEFAULT NULL,
  PRIMARY KEY (`idtheme`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `idusers` int(12) NOT NULL AUTO_INCREMENT,
  `users_name` char(100) DEFAULT NULL,
  `users_password` char(100) DEFAULT NULL,
  `users_email` char(210) DEFAULT NULL,
  `users_dni` char(8) DEFAULT NULL,
  `users_telefono` char(21) DEFAULT NULL,
  `users_domicilio` char(210) DEFAULT NULL,
  `users_state` int(1) DEFAULT NULL,
  `users_type` int(1) NOT NULL,
  PRIMARY KEY (`idusers`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idusers`, `users_name`, `users_password`, `users_email`, `users_dni`, `users_telefono`, `users_domicilio`, `users_state`, `users_type`) VALUES
(1, 'admin', '1142848c795b41537815301d7787ca9f', 'eddy.lecca3@gmail.com', '44285882', '3271447', 'calle cucarda 399', 1, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exemplary`
--
ALTER TABLE `exemplary`
  ADD CONSTRAINT `fk_exemplary_book1` FOREIGN KEY (`idbook`) REFERENCES `book` (`idbook`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `fk_loan_exemplary1` FOREIGN KEY (`idexemplary`) REFERENCES `exemplary` (`idexemplary`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_loan_users` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
