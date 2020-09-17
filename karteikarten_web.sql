SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `karteikarten_web` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `karteikarten_web`;

DROP TABLE IF EXISTS `antwort`;
CREATE TABLE IF NOT EXISTS `antwort` (
  `antwort_id` int(11) NOT NULL AUTO_INCREMENT,
  `antwort_text` varchar(255) NOT NULL,
  `antwort_bild` int(11) DEFAULT NULL,
  PRIMARY KEY (`antwort_id`),
  KEY `antwort_bild` (`antwort_bild`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `benutzer`;
CREATE TABLE IF NOT EXISTS `benutzer` (
  `benutzer_id` int(11) NOT NULL AUTO_INCREMENT,
  `benutzer_name` varchar(100) NOT NULL,
  `passwort_hash` varchar(255) NOT NULL,
  `klasse` int(11) NOT NULL,
  `admin` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`benutzer_id`),
  UNIQUE KEY `benutzer_name` (`benutzer_name`),
  KEY `klasse` (`klasse`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `bild`;
CREATE TABLE IF NOT EXISTS `bild` (
  `bild_id` int(11) NOT NULL AUTO_INCREMENT,
  `bild_daten` text NOT NULL,
  PRIMARY KEY (`bild_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `fach`;
CREATE TABLE IF NOT EXISTS `fach` (
  `fach_id` int(11) NOT NULL AUTO_INCREMENT,
  `fach_name` varchar(255) NOT NULL,
  `klasse` int(11) NOT NULL,
  PRIMARY KEY (`fach_id`),
  KEY `klasse` (`klasse`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `frage`;
CREATE TABLE IF NOT EXISTS `frage` (
  `frage_id` int(11) NOT NULL AUTO_INCREMENT,
  `frage_text` varchar(255) NOT NULL,
  `frage_bild` int(11) DEFAULT NULL,
  PRIMARY KEY (`frage_id`),
  KEY `frage_bild` (`frage_bild`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `karteikarte`;
CREATE TABLE IF NOT EXISTS `karteikarte` (
  `karteikarte_id` int(11) NOT NULL AUTO_INCREMENT,
  `thema` int(11) NOT NULL,
  `frage` int(11) NOT NULL,
  `antwort` int(11) NOT NULL,
  PRIMARY KEY (`karteikarte_id`),
  KEY `thema` (`thema`),
  KEY `frage` (`frage`),
  KEY `antwort` (`antwort`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `klasse`;
CREATE TABLE IF NOT EXISTS `klasse` (
  `klasse_id` int(11) NOT NULL AUTO_INCREMENT,
  `klasse_name` varchar(255) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  PRIMARY KEY (`klasse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `thema`;
CREATE TABLE IF NOT EXISTS `thema` (
  `thema_id` int(11) NOT NULL AUTO_INCREMENT,
  `thema_name` varchar(255) NOT NULL,
  `fach` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  PRIMARY KEY (`thema_id`),
  UNIQUE KEY `code` (`code`),
  KEY `fach` (`fach`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;


ALTER TABLE `antwort`
  ADD CONSTRAINT `antwort_ibfk_1` FOREIGN KEY (`antwort_bild`) REFERENCES `bild` (`bild_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `benutzer`
  ADD CONSTRAINT `benutzer_ibfk_1` FOREIGN KEY (`klasse`) REFERENCES `klasse` (`klasse_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `fach`
  ADD CONSTRAINT `fach_ibfk_1` FOREIGN KEY (`klasse`) REFERENCES `klasse` (`klasse_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `frage`
  ADD CONSTRAINT `frage_ibfk_1` FOREIGN KEY (`frage_bild`) REFERENCES `bild` (`bild_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `karteikarte`
  ADD CONSTRAINT `karteikarte_ibfk_1` FOREIGN KEY (`thema`) REFERENCES `thema` (`thema_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `karteikarte_ibfk_2` FOREIGN KEY (`frage`) REFERENCES `frage` (`frage_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `karteikarte_ibfk_3` FOREIGN KEY (`antwort`) REFERENCES `antwort` (`antwort_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `thema`
  ADD CONSTRAINT `thema_ibfk_1` FOREIGN KEY (`fach`) REFERENCES `fach` (`fach_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
