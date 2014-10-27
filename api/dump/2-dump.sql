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

-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- Хост: localhost:8889
-- Время создания: Окт 28 2014 г., 02:33
-- Версия сервера: 5.5.34
-- Версия PHP: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `adm`
--

-- --------------------------------------------------------

--
-- Структура таблицы `adm_user`
--

DROP TABLE IF EXISTS `adm_user`;
CREATE TABLE `adm_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(20) NOT NULL,
  `pass` varchar(70) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user',
  `status` int(11) NOT NULL DEFAULT '1',
  `ref` varchar(10) NOT NULL DEFAULT 'site',
  `date_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `f_name` varchar(50) NOT NULL,
  `s_name` varchar(50) NOT NULL,
  `address` varchar(300) NOT NULL,
  `id_fester` int(11) NOT NULL,
  `good` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Очистить таблицу перед добавлением данных `adm_user`
--

TRUNCATE TABLE `adm_user`;
--
-- Дамп данных таблицы `adm_user`
--

INSERT INTO `adm_user` (`id`, `email`, `pass`, `role`, `status`, `ref`, `date_reg`, `date_login`, `f_name`, `s_name`, `address`, `id_fester`, `good`) VALUES
(1, '123@ya.ru', '123', '123', 123, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', 0, 0),
(2, '123@123.ru', '$2y$13$SKiD1r3uxLUHbWQXMTN9FOMJoDqLdNRt2zZJL.IM434Vy957eOogG', 'moderator', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', 0, 0),
(3, '1@1.ru', '$2y$13$Nmn.x7BTSmCw2cpii4N10eDom5GpwAKVZ0lw8WoPrkddTfBlsSJRe', 'user', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '', 0, 0),
(4, 'nikozor@ya.ru', '$2y$13$lzGcqwRvJPARODAYjNUqFu7xlAWKI6Bi1n.778GAXlD.Rt1B841wi', 'user', 1, '', '0000-00-00 00:00:00', '2014-10-27 23:31:58', 'Никита', 'Зорин', 'г.Киров', 0, 0);
