-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 16. Sep 2020 um 13:59
-- Server-Version: 10.1.30-MariaDB
-- PHP-Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `karteikarten_web`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `antwort`
--

CREATE TABLE `antwort` (
  `antwort_id` int(11) NOT NULL,
  `antwort_text` varchar(255) NOT NULL,
  `antwort_bild` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzer`
--

CREATE TABLE `benutzer` (
  `benutzer_id` int(11) NOT NULL,
  `benutzer_name` varchar(100) NOT NULL,
  `passwort_hash` varchar(255) NOT NULL,
  `klasse` int(11) NOT NULL,
  `admin` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bild`
--

CREATE TABLE `bild` (
  `bild_id` int(11) NOT NULL,
  `bild_daten` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fach`
--

CREATE TABLE `fach` (
  `fach_id` int(11) NOT NULL,
  `fach_name` varchar(255) NOT NULL,
  `klasse` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `frage`
--

CREATE TABLE `frage` (
  `frage_id` int(11) NOT NULL,
  `frage_text` varchar(255) NOT NULL,
  `frage_bild` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `karteikarte`
--

CREATE TABLE `karteikarte` (
  `karteikarte_id` int(11) NOT NULL,
  `thema` int(11) NOT NULL,
  `frage` int(11) NOT NULL,
  `antwort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klasse`
--

CREATE TABLE `klasse` (
  `klasse_id` int(11) NOT NULL,
  `klasse_name` varchar(255) NOT NULL,
  `uuid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `thema`
--

CREATE TABLE `thema` (
  `thema_id` int(11) NOT NULL,
  `thema_name` varchar(255) NOT NULL,
  `fach` int(11) NOT NULL,
  `code` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `antwort`
--
ALTER TABLE `antwort`
  ADD PRIMARY KEY (`antwort_id`),
  ADD KEY `antwort_bild` (`antwort_bild`);

--
-- Indizes für die Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  ADD PRIMARY KEY (`benutzer_id`),
  ADD UNIQUE KEY `benutzer_name` (`benutzer_name`),
  ADD KEY `klasse` (`klasse`);

--
-- Indizes für die Tabelle `bild`
--
ALTER TABLE `bild`
  ADD PRIMARY KEY (`bild_id`);

--
-- Indizes für die Tabelle `fach`
--
ALTER TABLE `fach`
  ADD PRIMARY KEY (`fach_id`),
  ADD KEY `klasse` (`klasse`);

--
-- Indizes für die Tabelle `frage`
--
ALTER TABLE `frage`
  ADD PRIMARY KEY (`frage_id`),
  ADD KEY `frage_bild` (`frage_bild`);

--
-- Indizes für die Tabelle `karteikarte`
--
ALTER TABLE `karteikarte`
  ADD PRIMARY KEY (`karteikarte_id`),
  ADD KEY `thema` (`thema`),
  ADD KEY `frage` (`frage`),
  ADD KEY `antwort` (`antwort`);

--
-- Indizes für die Tabelle `klasse`
--
ALTER TABLE `klasse`
  ADD PRIMARY KEY (`klasse_id`);

--
-- Indizes für die Tabelle `thema`
--
ALTER TABLE `thema`
  ADD PRIMARY KEY (`thema_id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `fach` (`fach`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `antwort`
--
ALTER TABLE `antwort`
  MODIFY `antwort_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT für Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  MODIFY `benutzer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `bild`
--
ALTER TABLE `bild`
  MODIFY `bild_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT für Tabelle `fach`
--
ALTER TABLE `fach`
  MODIFY `fach_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `frage`
--
ALTER TABLE `frage`
  MODIFY `frage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT für Tabelle `karteikarte`
--
ALTER TABLE `karteikarte`
  MODIFY `karteikarte_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT für Tabelle `klasse`
--
ALTER TABLE `klasse`
  MODIFY `klasse_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `thema`
--
ALTER TABLE `thema`
  MODIFY `thema_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `antwort`
--
ALTER TABLE `antwort`
  ADD CONSTRAINT `antwort_ibfk_1` FOREIGN KEY (`antwort_bild`) REFERENCES `bild` (`bild_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  ADD CONSTRAINT `benutzer_ibfk_1` FOREIGN KEY (`klasse`) REFERENCES `klasse` (`klasse_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fach`
--
ALTER TABLE `fach`
  ADD CONSTRAINT `fach_ibfk_1` FOREIGN KEY (`klasse`) REFERENCES `klasse` (`klasse_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `frage`
--
ALTER TABLE `frage`
  ADD CONSTRAINT `frage_ibfk_1` FOREIGN KEY (`frage_bild`) REFERENCES `bild` (`bild_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `karteikarte`
--
ALTER TABLE `karteikarte`
  ADD CONSTRAINT `karteikarte_ibfk_1` FOREIGN KEY (`thema`) REFERENCES `thema` (`thema_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `karteikarte_ibfk_2` FOREIGN KEY (`frage`) REFERENCES `frage` (`frage_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `karteikarte_ibfk_3` FOREIGN KEY (`antwort`) REFERENCES `antwort` (`antwort_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `thema`
--
ALTER TABLE `thema`
  ADD CONSTRAINT `thema_ibfk_1` FOREIGN KEY (`fach`) REFERENCES `fach` (`fach_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
