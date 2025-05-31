-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 31. Mai 2025 um 22:25
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `dbpilotenshop`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `artikel`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL,
  `screen_resolution` varchar(50) NOT NULL,
  `operating_system` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `login_time`, `screen_resolution`, `operating_system`) VALUES
(14, 38, '2025-05-31 21:43:09', '408x690', 'MacIntel'),
(15, 38, '2025-05-31 21:43:36', '1440x932', 'MacIntel'),
(16, 38, '2025-05-31 21:43:38', '1440x932', 'MacIntel'),
(17, 38, '2025-05-31 21:43:39', '1440x932', 'MacIntel'),
(18, 38, '2025-05-31 21:43:39', '1440x932', 'MacIntel'),
(19, 38, '2025-05-31 21:43:39', '1440x932', 'MacIntel'),
(20, 38, '2025-05-31 21:43:39', '1440x932', 'MacIntel'),
(21, 38, '2025-05-31 21:43:40', '1440x932', 'MacIntel'),
(22, 38, '2025-05-31 21:43:40', '1440x932', 'MacIntel'),
(23, 38, '2025-05-31 21:43:48', '1440x932', 'MacIntel'),
(24, 38, '2025-05-31 21:43:49', '1440x932', 'MacIntel'),
(25, 38, '2025-05-31 21:43:49', '1440x932', 'MacIntel'),
(26, 38, '2025-05-31 21:43:49', '1440x932', 'MacIntel'),
(27, 38, '2025-05-31 21:43:49', '1440x932', 'MacIntel'),
(28, 38, '2025-05-31 21:43:49', '1440x932', 'MacIntel'),
(29, 38, '2025-05-31 21:44:34', '408x690', 'MacIntel'),
(30, 38, '2025-05-31 21:44:50', '408x690', 'MacIntel'),
(31, 38, '2025-05-31 21:45:19', '408x690', 'MacIntel'),
(32, 38, '2025-05-31 21:58:59', '408x690', 'MacIntel'),
(33, 38, '2025-05-31 21:59:01', '408x690', 'MacIntel'),
(34, 38, '2025-05-31 22:03:03', '408x690', 'MacIntel'),
(35, 38, '2025-05-31 22:19:48', '1440x932', 'MacIntel'),
(36, 38, '2025-05-31 22:20:19', '1440x932', 'MacIntel');



CREATE TABLE `artikel` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `beschreibung` varchar(400) NOT NULL,
  `groesse` varchar(1) NOT NULL,
  `preis` int(10) NOT NULL,
  `lagerbestand` int(11) DEFAULT NULL,
  `kategorie` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `artikel`
--

INSERT INTO `artikel` (`id`, `name`, `beschreibung`, `groesse`, `preis`, `lagerbestand`, `kategorie`) VALUES
(1001, 'Bose A30 Aviation Headset', 'Premium ANR-Headset mit hohem Tragekomfort und exzellenter Lärmreduzierung.', 'O', 1299, 25, 'Headsets'),
(1002, 'Lightspeed Zulu 3 ANR Headset', 'Beliebtes ANR-Headset, bekannt für Komfort, Haltbarkeit und klare Audioqualität.', 'O', 950, 30, 'Headsets'),
(1003, 'David Clark H10-13.4 Aviation Headset', 'Klassisches, robustes PNR-Headset, ein Standard in der Allgemeinen Luftfahrt.', 'O', 389, 50, 'Headsets'),
(1004, 'Yaesu FTA-550L Pro-X', 'Luftfahrt-Handfunkgerät mit NAV/COM und GPS-Empfänger.', 'O', 299, 15, 'Headsets'),
(1005, 'Sennheiser S1 Digital Aviation Headset', 'ANR-Headset mit adaptiver Lärmkompensation und individuell einstellbarem Anpressdruck.', 'O', 1050, 18, 'Headsets'),
(1006, 'Icom IC-A25NE (8.33/25 kHz)', 'Leistungsstarkes Handfunkgerät mit Navigation (VOR, GPS) und Bluetooth.', 'O', 489, 22, 'Headsets'),
(1007, 'Garmin aera 660 Portable Aviation GPS', 'Tragbares GPS mit Touchscreen, 3D Vision und umfangreichen Navigationsfunktionen.', 'O', 849, 20, 'Navigation'),
(1008, 'ICAO Karte Deutschland (Set)', 'Offizielles Kartenset der Deutschen Flugsicherung für VFR-Flüge in Deutschland.', 'O', 25, 150, 'Navigation'),
(1009, 'Jeppesen CR-3 Circular Flight Computer', 'Klassischer mechanischer Flugrechner für Flugplanungsberechnungen.', 'O', 36, 70, 'Navigation'),
(1010, 'ASA KB-3 Tri-Fold Kneeboard', 'Dreifach faltbares Kniebrett mit Klemmbrett, Stifthaltern und Kartentaschen.', 'O', 50, 40, 'Navigation'),
(1011, 'Garmin GDL 50 Portable ADS-B Receiver', 'Tragbarer Empfänger für ADS-B Wetter- und Verkehrsdaten, Anzeige auf kompatiblen Geräten.', 'O', 799, 15, 'Navigation'),
(1012, 'SkyDemon Lizenz (1 Jahr)', 'Umfassende Flugplanungs- und Navigationssoftware für VFR-Piloten in Europa.', 'O', 149, 0, 'Navigation'),
(1013, 'Randolph Engineering Aviator (55mm, Gold)', 'Klassische Piloten-Sonnenbrille, nach US Militärspezifikationen gefertigt.', 'O', 219, 35, 'Pilotenkleidung & Accessoires'),
(1014, 'Garmin D2 Mach 1 Aviator Smartwatch', 'Premium GPS-Smartwatch für Piloten mit umfassenden Flug-, Wetter- und Fitnessfunktionen.', 'O', 1199, 10, 'Pilotenkleidung & Accessoires'),
(1015, '\"Alpha\" Pilotenhemd, weiß, Kurzarm', 'Pilotenhemd aus pflegeleichtem Baumwollmischung, mit Schulterklappen.', 'O', 40, 100, 'Pilotenkleidung & Accessoires'),
(1016, 'Design4Pilots \"Pilot Case Daily\"', 'Kompakter und robuster Pilotentrolley für den täglichen Gebrauch oder kurze Reisen.', 'O', 189, 12, 'Pilotenkleidung & Accessoires'),
(1017, 'Ray-Ban Aviator Classic RB3025', 'Die originale Pilotenbrille, ein zeitloser Klassiker mit hervorragendem UV-Schutz.', 'O', 150, 60, 'Pilotenkleidung & Accessoires'),
(1018, 'Alpha Industries MA-1 Fliegerjacke', 'Kultige Nylon-Fliegerjacke mit orangem Innenfutter und robuster Verarbeitung.', 'O', 179, 45, 'Pilotenkleidung & Accessoires'),
(1019, 'Jeppesen Captain Flight Bag', 'Geräumige Flugtasche mit vielen Fächern für Headsets, Karten und Zubehör.', 'O', 129, 28, 'Flugtaschen & Koffer'),
(1020, 'Design4Pilots \"Pilot Weekend\" Tasche', 'Kompakte und stilvolle Tasche für Kurztrips oder als Alltagstasche für Piloten.', 'O', 99, 22, 'Flugtaschen & Koffer'),
(1021, 'Brightline Bags B7 Flight \"Echo\" Konfiguration', 'Hochgradig anpassbare, modulare Flugtasche.', 'O', 229, 19, 'Flugtaschen & Koffer'),
(1022, 'ASA AirClassics Flight Bag', 'Strapazierfähige und kompakte Tasche für die wichtigsten Pilotenutensilien.', 'O', 80, 33, 'Flugtaschen & Koffer'),
(1023, 'Lightspeed \"The Cann\" Flight Bag', 'Elegante Leder-Flugtasche, benannt nach dem berühmten Autor Ernest K. Gann.', 'O', 199, 14, 'Flugtaschen & Koffer'),
(1024, 'Aerocoast Pro EFB + Cooler II', 'Speziell für Airline-Piloten entwickelte Tasche mit EFB-Fach und integriertem Kühler.', 'O', 165, 17, 'Flugtaschen & Koffer'),
(1025, 'PPL-A Lehrbuch Set (z.B. Oxford Aviation)', 'Umfassendes Lehrbuchset für die EASA PPL(A) Theorie.', 'O', 249, 40, 'Flugbücher & Lernmaterial'),
(1026, 'ASA Standard Pilot Logbook (SP-30)', 'Standardisiertes Logbuch zur Erfassung von Flugzeiten und Erfahrungen.', 'O', 13, 200, 'Flugbücher & Lernmaterial'),
(1027, '\"Stick and Rudder\" von Wolfgang Langewiesche', 'Ein Klassiker über die Kunst des Fliegens, tiefgründig und zeitlos.', 'O', 23, 35, 'Flugbücher & Lernmaterial'),
(1028, 'Aviationexam PPL Fragensammlung (1 Jahr Zugang)', 'Online-Zugang zu einer umfangreichen Datenbank mit EASA PPL Prüfungsfragen.', 'O', 89, 0, 'Flugbücher & Lernmaterial'),
(1029, 'Jeppesen ATPL Training Set (E-Books)', 'Kompletter Satz an E-Books für die ATPL(A) Theorie gemäß EASA-Richtlinien.', 'O', 699, 0, 'Flugbücher & Lernmaterial'),
(1030, '\"Pilots Weather\" by Brian Cosgrove', 'Detailliertes Buch über Meteorologie speziell für Piloten.', 'O', 35, 25, 'Flugbücher & Lernmaterial'),
(1031, '\"Remove Before Flight\" Pitot Cover Universal', 'Schützt das Pitot-Rohr vor Verstopfung durch Insekten oder Schmutz am Boden.', 'O', 10, 300, 'Flugzeugzubehör (GA)'),
(1032, 'Flugzeug Radkeile, Gummi (Paar)', 'Robuste Gummikeile zur Sicherung von Leichtflugzeugen am Boden.', 'O', 30, 90, 'Flugzeugzubehör (GA)'),
(1033, 'GATS Jar Fuel Tester', 'Transparenter Treibstofftester zur Prüfung auf Wasser und Sedimente im Kraftstoff.', 'O', 19, 120, 'Flugzeugzubehör (GA)'),
(1034, 'Aircraft Tie-Down Kit (3x Spanngurte, Seile)', 'Set zur sicheren Verankerung von Flugzeugen im Freien.', 'O', 59, 40, 'Flugzeugzubehör (GA)'),
(1035, 'Aero Cosmetics Wash Wax ALL (Konzentrat, 1L)', 'Wasserloses Reinigungs- und Wachsystsem für Flugzeuge, umweltfreundlich.', 'O', 45, 55, 'Flugzeugzubehör (GA)'),
(1036, 'Tempest AA472 Oil Filter Cutter', 'Werkzeug zum sauberen Öffnen von Ölfiltern zur Inspektion auf Metallpartikel.', 'O', 89, 12, 'Flugzeugzubehör (GA)'),
(1037, 'Crewsaver Crewfit 165N Schwimmweste', 'Automatische Rettungsweste über Wasser, leicht und komfortabel.', 'O', 99, 20, 'Sicherheitsaustrüstung'),
(1038, 'ACR ResQLink 400 PLB', 'Kompakte und robuste persönliche Ortungsbake (PLB) für Notfälle.', 'O', 329, 8, 'Sicherheitsaustrüstung'),
(1039, 'Lufthansa Erste-Hilfe-Set DIN 13157 erweitert', 'Umfassendes Erste-Hilfe-Set, erweitert mit flugspezifischen Inhalten oder Empfehlungen.', 'O', 45, 38, 'Sicherheitsaustrüstung'),
(1040, 'H3R Aviation Halon 1211 Feuerlöscher (A344T)', 'Kompakter Halon 1211 Feuerlöscher, für den Einsatz im Cockpit zugelassen.', 'O', 289, 10, 'Sicherheitsaustrüstung'),
(1041, 'SOL Escape Bivvy Bag Orange', 'Isolierender Notfall-Biwacksack, reflektiert 70% der Körperwärme.', 'O', 36, 36, 'Sicherheitsaustrüstung'),
(1042, 'Forensics Detectors CO Detector for Aircraft', 'Tragbarer Kohlenmonoxid-Detektor speziell für Flugzeuge, mit Alarm.', 'O', 159, 7, 'Sicherheitsaustrüstung');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
