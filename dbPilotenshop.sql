-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 03. Jun 2025 um 11:49
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
-- Tabellenstruktur für Tabelle `bestellkopf`
--

CREATE TABLE `bestellkopf` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bestelldatum` datetime DEFAULT current_timestamp(),
  `gesamtbetrag` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellposition`
--

CREATE TABLE `bestellposition` (
  `id` int(11) NOT NULL,
  `bestellung_id` int(11) DEFAULT NULL,
  `artikel_id` int(11) DEFAULT NULL,
  `menge` int(11) DEFAULT NULL,
  `einzelpreis` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Tabellenstruktur für Tabelle `logs`
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
(0, 45, '2025-06-03 11:36:36', '1920x1080', 'Win32');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `punkte`
--

CREATE TABLE `punkte` (
  `user_id` int(11) NOT NULL,
  `punktestand` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `punkte`
--

INSERT INTO `punkte` (`user_id`, `punktestand`) VALUES
(1, 0),
(45, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rechnungskopf`
--

CREATE TABLE `rechnungskopf` (
  `id` int(11) NOT NULL,
  `bestellung_id` int(11) DEFAULT NULL,
  `rechnungsdatum` datetime DEFAULT current_timestamp(),
  `betrag` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(45, 'Fabian', 'Andy', 'andre.reiff@online.de', 'Moltkestraße 32', 72805, 'Lichtenstein', 'bf78f0125180f365b24716b3cc2a5c1161c9c5fe977108e3307df95f4f5562c75369a9d605eb4c8453569d183b7ae7b045b48eddf86e02f4575e76f16d368ac4', '5VFCBX5CKYSG222T');

--
-- Trigger `user`
--
DELIMITER $$
CREATE TRIGGER `after_user_insert` AFTER INSERT ON `user` FOR EACH ROW BEGIN
    INSERT INTO punkte (user_id) VALUES (NEW.id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `warenkorbkopf`
--

CREATE TABLE `warenkorbkopf` (
  `id` int(11) NOT NULL,
  `kunde_id` int(11) DEFAULT NULL,
  `erstellt_am` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `warenkorbposition`
--

CREATE TABLE `warenkorbposition` (
  `id` int(11) NOT NULL,
  `warenkorb_id` int(11) DEFAULT NULL,
  `artikel_id` int(11) DEFAULT NULL,
  `menge` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `bestellkopf`
--
ALTER TABLE `bestellkopf`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `bestellposition`
--
ALTER TABLE `bestellposition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bestellung_id` (`bestellung_id`),
  ADD KEY `artikel_id` (`artikel_id`);

--
-- Indizes für die Tabelle `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`bestellungID`);

--
-- Indizes für die Tabelle `punkte`
--
ALTER TABLE `punkte`
  ADD PRIMARY KEY (`user_id`);

--
-- Indizes für die Tabelle `rechnungskopf`
--
ALTER TABLE `rechnungskopf`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bestellung_id` (`bestellung_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- Indizes für die Tabelle `warenkorbkopf`
--
ALTER TABLE `warenkorbkopf`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kunde_id` (`kunde_id`);

--
-- Indizes für die Tabelle `warenkorbposition`
--
ALTER TABLE `warenkorbposition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warenkorb_id` (`warenkorb_id`),
  ADD KEY `artikel_id` (`artikel_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `artikel`
--
ALTER TABLE `artikel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2007;

--
-- AUTO_INCREMENT für Tabelle `bestellkopf`
--
ALTER TABLE `bestellkopf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `bestellposition`
--
ALTER TABLE `bestellposition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `cart`
--
ALTER TABLE `cart`
  MODIFY `bestellungID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `rechnungskopf`
--
ALTER TABLE `rechnungskopf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT für Tabelle `warenkorbkopf`
--
ALTER TABLE `warenkorbkopf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `warenkorbposition`
--
ALTER TABLE `warenkorbposition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bestellkopf`
--
ALTER TABLE `bestellkopf`
  ADD CONSTRAINT `bestellkopf_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints der Tabelle `bestellposition`
--
ALTER TABLE `bestellposition`
  ADD CONSTRAINT `bestellposition_ibfk_1` FOREIGN KEY (`bestellung_id`) REFERENCES `bestellkopf` (`id`),
  ADD CONSTRAINT `bestellposition_ibfk_2` FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`);

--
-- Constraints der Tabelle `punkte`
--
ALTER TABLE `punkte`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `rechnungskopf`
--
ALTER TABLE `rechnungskopf`
  ADD CONSTRAINT `rechnungskopf_ibfk_1` FOREIGN KEY (`bestellung_id`) REFERENCES `bestellkopf` (`id`);

--
-- Constraints der Tabelle `warenkorbkopf`
--
ALTER TABLE `warenkorbkopf`
  ADD CONSTRAINT `warenkorbkopf_ibfk_1` FOREIGN KEY (`kunde_id`) REFERENCES `user` (`id`);

--
-- Constraints der Tabelle `warenkorbposition`
--
ALTER TABLE `warenkorbposition`
  ADD CONSTRAINT `warenkorbposition_ibfk_1` FOREIGN KEY (`warenkorb_id`) REFERENCES `warenkorbkopf` (`id`),
  ADD CONSTRAINT `warenkorbposition_ibfk_2` FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
