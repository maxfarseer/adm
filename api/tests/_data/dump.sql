-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Oct 24, 2014 at 11:23 AM
-- Server version: 5.5.34
-- PHP Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `_adm`
--

-- --------------------------------------------------------

--
-- Table structure for table `adm_user`
--

CREATE TABLE `adm_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(20) NOT NULL,
  `pass` varchar(70) NOT NULL,
  `role` varchar(10) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `adm_user`
--

INSERT INTO `adm_user` (`id`, `email`, `pass`, `role`, `status`) VALUES
(1, '123@ya.ru', '123', '123', 123);