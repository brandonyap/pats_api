# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.28)
# Database: pats
# Generation Time: 2020-02-05 00:23:25 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table beacons
# ------------------------------------------------------------

DROP TABLE IF EXISTS `beacons`;

CREATE TABLE `beacons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `name` varchar(26) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `description` text CHARACTER SET latin1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table beacons_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `beacons_group`;

CREATE TABLE `beacons_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(26) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table beacons_group_locations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `beacons_group_locations`;

CREATE TABLE `beacons_group_locations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `beacons_id` int(11) NOT NULL,
  `location_x` float NOT NULL,
  `location_y` float NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`group_id`,`beacons_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table beacons_group_restricted
# ------------------------------------------------------------

DROP TABLE IF EXISTS `beacons_group_restricted`;

CREATE TABLE `beacons_group_restricted` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(26) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_id` int(11) NOT NULL,
  `location_x_min` float NOT NULL,
  `location_y_min` float NOT NULL,
  `location_x_max` float NOT NULL,
  `location_y_max` float NOT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table map
# ------------------------------------------------------------

DROP TABLE IF EXISTS `map`;

CREATE TABLE `map` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table patients
# ------------------------------------------------------------

DROP TABLE IF EXISTS `patients`;

CREATE TABLE `patients` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sensors_id` int(11) DEFAULT NULL,
  `first_name` varchar(26) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(26) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `birthday` date NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `physician` varchar(26) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `caretaker` varchar(26) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `comments` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hospital_id` (`hospital_id`),
  UNIQUE KEY `sensors_id` (`sensors_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sensors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sensors`;

CREATE TABLE `sensors` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bluetooth_address` varchar(17) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(26) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bluetooth_address` (`bluetooth_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sensors_locations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sensors_locations`;

CREATE TABLE `sensors_locations` (
  `sensors_id` int(11) NOT NULL,
  `location_x` float NOT NULL,
  `location_y` float NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `sensors_id` (`sensors_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sensors_locations_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sensors_locations_history`;

CREATE TABLE `sensors_locations_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sensors_id` int(11) NOT NULL,
  `location_x` float NOT NULL,
  `location_y` float NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sensors_locations_restricted
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sensors_locations_restricted`;

CREATE TABLE `sensors_locations_restricted` (
  `sensors_id` int(11) NOT NULL,
  `restricted_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
