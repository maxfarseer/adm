-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Oct 24, 2014 at 06:02 PM
-- Server version: 5.5.34
-- PHP Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `adm`
--
CREATE DATABASE IF NOT EXISTS `adm` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `adm`;

-- --------------------------------------------------------

--
-- Table structure for table `adm_user`
--

DROP TABLE IF EXISTS `adm_user`;
CREATE TABLE `adm_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(20) NOT NULL,
  `pass` varchar(70) NOT NULL,
  `role` varchar(10) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `adm_user`
--

INSERT INTO `adm_user` (`id`, `email`, `pass`, `role`, `status`) VALUES
(1, '123@ya.ru', '123', '123', 123),
(2, '123@123.ru', '$2y$13$SKiD1r3uxLUHbWQXMTN9FOMJoDqLdNRt2zZJL.IM434Vy957eOogG', 'moderator', 0),
(3, '1@1.ru', '$2y$13$Nmn.x7BTSmCw2cpii4N10eDom5GpwAKVZ0lw8WoPrkddTfBlsSJRe', 'user', 0);
