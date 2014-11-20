# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.34)
# Database: adm
# Generation Time: 2014-11-20 17:46:30 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table adm_present
# ------------------------------------------------------------

DROP TABLE IF EXISTS `adm_present`;

CREATE TABLE `adm_present` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from` int(11) DEFAULT NULL,
  `to` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `message` varchar(500) DEFAULT NULL,
  `comment` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `adm_present` WRITE;
/*!40000 ALTER TABLE `adm_present` DISABLE KEYS */;

INSERT INTO `adm_present` (`id`, `from`, `to`, `date`, `type`, `status`, `message`, `comment`)
VALUES
	(8,4,6,'2014-11-16 22:02:46','digit',0,NULL,NULL),
	(10,4,7,'2014-11-16 22:07:51','pkg',0,NULL,NULL);

/*!40000 ALTER TABLE `adm_present` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table adm_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `adm_user`;

CREATE TABLE `adm_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL DEFAULT '',
  `pass` varchar(70) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user',
  `status` int(11) NOT NULL DEFAULT '1',
  `ref` varchar(10) NOT NULL DEFAULT 'site',
  `date_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `f_name` varchar(50) NOT NULL,
  `s_name` varchar(50) NOT NULL,
  `address` varchar(300) NOT NULL,
  `id_pkg` int(11) NOT NULL,
  `status_pkg` tinyint(1) NOT NULL,
  `id_digit` int(11) NOT NULL,
  `status_digit` tinyint(1) NOT NULL,
  `good` int(11) NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `pkg` (`id_pkg`),
  KEY `digit` (`id_digit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `adm_user` WRITE;
/*!40000 ALTER TABLE `adm_user` DISABLE KEYS */;

INSERT INTO `adm_user` (`id`, `email`, `pass`, `role`, `status`, `ref`, `date_reg`, `date_login`, `f_name`, `s_name`, `address`, `id_pkg`, `status_pkg`, `id_digit`, `status_digit`, `good`, `nickname`)
VALUES
	(1,'123@ya.ru','123','123',1,'','0000-00-00 00:00:00','0000-00-00 00:00:00','Олег','','г.Орел',0,0,0,0,0,NULL),
	(2,'123@123.ru','$2y$13$SKiD1r3uxLUHbWQXMTN9FOMJoDqLdNRt2zZJL.IM434Vy957eOogG','moderator',1,'','0000-00-00 00:00:00','0000-00-00 00:00:00','Паша','','г.Пенза',0,0,0,0,0,NULL),
	(3,'1@1.ru','$2y$13$Nmn.x7BTSmCw2cpii4N10eDom5GpwAKVZ0lw8WoPrkddTfBlsSJRe','user',1,'','0000-00-00 00:00:00','0000-00-00 00:00:00','Саша','','г.Липецк',0,0,0,0,0,NULL),
	(4,'nikozor@ya.ru','$2y$13$lzGcqwRvJPARODAYjNUqFu7xlAWKI6Bi1n.778GAXlD.Rt1B841wi','user',1,'','0000-00-00 00:00:00','2014-11-20 21:25:26','Никита','Зорин','г.Киров',7,2,6,1,0,'Spiker!'),
	(5,'nikozor@yandex.ru','$2y$13$.7gKUWt00o.CXGjXHCHeRumYeYmnDAJ4//aabVXf0auR2c7yOMVSe','user',1,'site','2014-10-28 10:31:10','0000-00-00 00:00:00','Даша','','г.Краснодар',0,0,0,0,0,NULL),
	(6,'nikozor@bk.ru','$2y$13$DFyC8bLZLlQiQSUSnWu0lOQwTcsZjONN3onzYSYCZC3sNkLelnK.m','user',1,'site','2014-10-28 10:38:50','0000-00-00 00:00:00','Маша','','г.Ростов',0,2,0,1,0,'Маха'),
	(7,'nikozor@123.ru','$2y$13$KYexvZlo3s8.zYvGDc6dAeiA/d3MbQs2lIZYipF/HQF33sxLl8pyu','user',1,'site','2014-10-28 10:42:32','0000-00-00 00:00:00','Галя','','г.Екатеринбург',0,2,0,1,0,'Gala');

/*!40000 ALTER TABLE `adm_user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
