-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 30. Mai 2025 um 09:45
-- Server-Version: 10.4.28-MariaDB
-- PHP-Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `dbPilotenshop`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `artikel`
--

CREATE TABLE `artikel` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `beschreibung` varchar(400) NOT NULL,
  `groesse` varchar(1) NOT NULL,
  `preis` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `artikel`
--

INSERT INTO `artikel` (`id`, `name`, `beschreibung`, `groesse`, `preis`) VALUES
(1001, 'Halfter', 'Mit schicker Applikation am Nasenriemen. Das weiche Neopren-Polster bietet optimalen Scheuerschutz auf sensiblen Belastungspunkten. Das Halfter ist sowohl am Kinn- als auch am Genickstück durch stabile Dornschnallen individuelle verstellbar. Alle Metallbeschläge sind markant kupferfarbend veredelt.', 'U', 30),
(1002, 'Olivenkopfgebiss', 'Olivenkopfgebiss aus Edelstahl. Das ergonomisch geformte Mundstück des Olivenkopfgebisses liegt besonders weich und angenehm im Pferdemaul.', 'U', 20),
(1003, 'Fliegenhaube', 'Die Fliegenhaube Artwork aus der aktuellen Heritage Kollektion von ESKADRON ist ein gehäkelter Fliegenkopfschutz mit elastischen Ohren. Sie bietet nicht nur im Sommer Schutz vor Lästlingen, sondern in Kombination mit den drei umlaufenden Kordeln und der Stickerei auch perfekt mit der passenden Schabracke zu kombinieren.', 'U', 35),
(1004, 'Paradedecke', 'Wunderschöne, bestickbare Paradedecke von Horse-friends für tolle Auftritte. Das saugfähige Material leitet den Schweiß nach außen und schützt dabei Ihr Pferd vor Kälte und Zugluft. Es ist atmungsaktiv, schweißableitend und klimaregulierend. Die Besonderheiten der Paradecke sind der aufwendig gearbeitete Zierschweifriemen, die umlaufende Kontrastkordel und der Brustlatz. 125 cm ', 'S', 25),
(1005, 'Schabracke', 'Die Schabracke Big Square aus der aktuellen Heritage Kollektion von ESKADRON. Sie ist die absolute Topseller Schabracke in voluminöser Big-Square-Steppung. Die Abseite besteht aus fellfreundlichem Baumwollwaffel-Gewebe. Durch die schicke Doppelkordel und den beidseitig vertikalen Emblemstreifen wird die Big Square Schabracke zu einem echten Eyecatcher. 60 cm x 56 cm', '', 50),
(1006, 'Dressursattel', 'Der Dressursattel Prince ist aus strapazierfähigem, schwarzem Rindsleder mit stabilen Nähten gefertigt. Sitz, Sattelkissen und Knielage aus pflegeleichtem Kunstleder. Der Dressursattel für Großpferde verfügt über lange Gurtstrippen. Die gute Passform und der tiefer Sitz überzeugen. 17,5 Zoll', '', 200),
(2001, 'Outdoorjacke', 'Die Reit- und Outdoorjacke Sintra von black forest ist ein praktischer Allrounder für Sport und Freizeit. Aus angenehm zu tragendem Polyester-Material, das atmungsaktiv (Atmungsaktivität 3.000 g/m²) und wasserdicht (Wassersäule 3.000 mm) ist. ', 'S', 60),
(2002, 'Reithose', 'Unser Bestseller die Winterreithose Genua Winter von black forest in neuer Passform. Durch das Baumwoll-Material und die weich angeraute Fleece-Fütterung ist die Gesäßeinsatz-Reithose Genua auch bei kalten Temperaturen genau die richtige Wahl!', 'S', 55),
(2003, 'Unterziehrolli', 'Der klassische Unterziehrolli Abby Athleisure von Pikeur mit glattem Warenbild. Für besten Tragekomfort sorgt die bi-elastische Funktionsware und die angeraute Fleece-Abseite. Sehr schick ist der große Pikeur Athleisure Print am linken Ärmel.', 'S', 40),
(2004, 'Reithelm', 'Der Leichte und Luftige - Unschlagbar im Preis/Leistungsverhältnis! Praktisch und bequem im täglichen Gebrauch. Rundum sicher, mit 4-fach Monocoque™- Sicherheitstechnik. Effiziente Fresh-Air-Belüftung durch 13 Lüftungsöffnungen mit schönen Edelstahl Eyelets. 54-56 cm', '', 100),
(2005, 'Reitstiefeletten', 'Das robuste, rustikale Fettleder mit echtem Lammfellfutter ist die perfekte Kombination für die kalten Tage. Des Weiteren ist die Winter-Reitstiefelette mit Gummieinsätzen am Schaft für ein bquemes An- und Ausziehen sowie einer rutschfesten Gummiprofilsohle ausgestattet. Größe 38', '', 65),
(2006, 'Reithandschuhe', 'Unser Bestseller in der Wintervariante mit Fleecelaminierung! Die Winter-Reithandschuhe Light & Soft zeichnen sich besonders durch ihre Leichtigkeit und das weiche, griffige Kunstledermaterial aus. Für eine ausgezeichnete Passform und optimale Bewegungsfreiheit sorgen die Dehnungsfalten an Fingern und Handrücken sowie der Gummizug an Handrücken und Bündchen. ', 'S', 10);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cart`
--

CREATE TABLE `cart` (
  `bestellungID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `artikelID` varchar(200) NOT NULL,
  `produktname` varchar(200) NOT NULL,
  `anzahl` varchar(200) NOT NULL,
  `preis` varchar(200) NOT NULL,
  `gesamtsumme` int(10) NOT NULL,
  `versandkosten` int(5) NOT NULL,
  `datum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `online`
--

CREATE TABLE `online` (
  `userID` int(11) NOT NULL,
  `timeonline` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `online`
--

INSERT INTO `online` (`userID`, `timeonline`) VALUES
(3, '2025-05-15 19:22:23'),
(4, '2025-05-15 19:24:31'),
(5, '2025-05-15 19:28:35'),
(6, '2025-05-15 19:38:00'),
(7, '2025-05-15 19:44:27'),
(8, '2025-05-15 19:47:18'),
(9, '2025-05-15 20:21:58'),
(10, '2025-05-15 20:23:53'),
(11, '2025-05-15 20:39:10'),
(12, '2025-05-15 20:40:49'),
(13, '2025-05-15 20:58:54'),
(14, '2025-05-15 21:02:58'),
(15, '2025-05-15 21:06:19'),
(16, '2025-05-15 21:08:06'),
(17, '2025-05-15 21:09:00'),
(18, '2025-05-15 21:52:12'),
(19, '2025-05-15 22:13:46'),
(20, '2025-05-15 22:21:49'),
(21, '2025-05-29 21:52:27'),
(22, '2025-05-29 21:52:52'),
(23, '2025-05-29 21:59:10'),
(24, '2025-05-29 22:25:06'),
(25, '2025-05-29 22:25:49');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `vorname` varchar(100) NOT NULL,
  `nachname` varchar(100) NOT NULL,
  `mail` varchar(300) NOT NULL,
  `adresse` varchar(200) NOT NULL,
  `plz` decimal(5,0) NOT NULL,
  `ort` varchar(100) NOT NULL,
  `passwort` varchar(200) NOT NULL,
  `google_secret` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `vorname`, `nachname`, `mail`, `adresse`, `plz`, `ort`, `passwort`, `google_secret`) VALUES
(1, 'dndjd', 'djdjdn', 'dj@qdd.de', 'jdjdkik', 2234, 'djdjsnn', 'djdjj3jjns', NULL),
(3, 'hjbdjhswfj', 'jndsjnkd', 'esrr1979@outlook.com', 'djehjnfj', 23334, 'dujdjfn', 'f783e32fefe752acda784a8873cd6b9cd930e51208db7be726b9a3a1bfe4556e', NULL),
(10, '2', '23', 'cockpitcorner@mailbox.org', '2', 33, 'ed', '45a166adb8933284e4d4b29677d0b189e3ba1f6e49f5277f23100f48ccee3e1c', NULL),
(12, 'Florian', '23', 'tittl.florian@gmail.com', '2', 33, 'ed', '81e9e2f91bba47f7aa2ea999d5ab9e0c371e30be9982391bc541735074081c5c', NULL),
(29, 'test', 'test', 'snfhjnf@outlook.com', 'test', 12344, 'test', '$2y$10$MMfMO.MNFKJ1jwS0LLHlauyR.2CeGoMJpS1sF/zsjezr.jchb8SRi', 'EV2Z2EKGDM57O663'),
(31, 'edn', 'fkwejn', 'k.apaza@outlook.com', 'sjnw', 12345, 'wej hj', '$2y$10$PjEXN9W6iw9e47nEJN0fWOQkVCSeH1Zh1FlBSWlKxHsFj6U4zy/oO', 'WNCDJAIBPXS7YE2W'),
(32, 'hbunj', 'uhbn', 'hnure@outlook.com', 'ewdcuin', 23882, 'wejindiu', '$2y$10$9gxNTg9XsKsHzRl0gFLMvu8su9F6xjHXBsIqklXLdYbIoCJtXigJa', 'O35RLXDVU3BYNYKT');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`bestellungID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `artikel`
--
ALTER TABLE `artikel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2007;

--
-- AUTO_INCREMENT für Tabelle `cart`
--
ALTER TABLE `cart`
  MODIFY `bestellungID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
