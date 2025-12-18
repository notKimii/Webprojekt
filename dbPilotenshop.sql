-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 18. Dez 2025 um 17:32
-- Server-Version: 10.4.28-MariaDB
-- PHP-Version: 8.2.4

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
-- Tabellenstruktur für Tabelle `bewertungen`
--

CREATE TABLE `bewertungen` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `artikel_id` int(11) NOT NULL,
  `wert` tinyint(1) NOT NULL COMMENT 'Bewertungswert von 1 bis 5',
  `kommentar` text DEFAULT NULL,
  `zeitstempel` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `bewertungen`
--

INSERT INTO `bewertungen` (`id`, `user_id`, `artikel_id`, `wert`, `kommentar`, `zeitstempel`) VALUES
(14, 53, 1001, 5, 'Das beste Headset, das ich je hatte. Das Active Noise Cancelling ist der Wahnsinn! Jeden Cent wert.', '2023-10-15 12:30:00'),
(15, 54, 1001, 5, 'Schnelle Lieferung, top Produkt. Gerne wieder.', '2023-10-20 07:15:00'),
(16, 55, 1001, 4, 'Qualität ist super, aber der Preis ist schon heftig. Deshalb einen Stern Abzug.', '2023-11-05 17:00:00'),
(17, 56, 1002, 5, 'Sehr bequem, drückt auch nach 4 Stunden Flug nicht. Die Bluetooth-Funktion ist super praktisch für Musik.', '2023-11-12 10:20:00'),
(19, 57, 1002, 5, 'Preis-Leistung ist hier unschlagbar. Sehr robustes Kabel.', '2023-12-02 07:30:00'),
(20, 58, 1003, 4, 'Der Klassiker. Unkaputtbar, aber halt nur passives Noise Cancelling. Für Schüler perfekt.', '2023-12-01 09:00:00'),
(21, 59, 1003, 2, 'Nach einer Stunde bekomme ich Kopfschmerzen. Es ist mir persönlich etwas zu schwer.', '2023-12-05 12:30:00'),
(22, 60, 1001, 5, 'Das beste Headset, das ich je hatte. Das ANC ist der Wahnsinn!', '2023-10-15 12:30:00');

--
-- Trigger `bewertungen`
--
DELIMITER $$
CREATE TRIGGER `nach_neuer_bewertung` AFTER INSERT ON `bewertungen` FOR EACH ROW BEGIN
    -- Berechnet AVG und COUNT des betroffenen Artikels und aktualisiert die Tabelle 'artikel'
    UPDATE artikel
    SET 
        bewertung = (
            SELECT AVG(wert)
            FROM bewertungen
            WHERE artikel_id = NEW.artikel_id
        ),
        anzahl_bewertungen = (
            SELECT COUNT(wert)
            FROM bewertungen
            WHERE artikel_id = NEW.artikel_id
        )
    WHERE 
        id = NEW.artikel_id;
END
$$
DELIMITER ;

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
(1, 45, '2025-06-03 11:36:36', '1920x1080', 'Win32'),
(2, 50, '2025-06-04 10:59:04', '1920x1080', 'Win32'),
(3, 50, '2025-06-04 12:05:12', '1920x1080', 'Win32'),
(4, 51, '2025-06-04 21:10:08', '1440x932', 'MacIntel'),
(5, 51, '2025-06-04 22:51:19', '1440x932', 'MacIntel'),
(6, 51, '2025-06-04 22:52:04', '1440x932', 'MacIntel'),
(7, 51, '2025-06-04 22:53:04', '1440x932', 'MacIntel'),
(8, 51, '2025-06-04 22:53:26', '1440x932', 'MacIntel'),
(9, 51, '2025-06-04 22:58:20', '1440x932', 'MacIntel'),
(10, 51, '2025-06-05 00:26:28', '1440x932', 'MacIntel'),
(11, 108, '2025-12-11 21:18:41', '1440x932', 'MacIntel'),
(12, 108, '2025-12-11 21:19:26', '1440x932', 'MacIntel'),
(13, 108, '2025-12-11 21:19:43', '1440x932', 'MacIntel'),
(14, 108, '2025-12-11 21:20:14', '1440x932', 'MacIntel'),
(15, 108, '2025-12-11 21:20:25', '1440x932', 'MacIntel'),
(16, 108, '2025-12-11 21:20:54', '1440x932', 'MacIntel'),
(17, 108, '2025-12-11 21:22:20', '1440x932', 'MacIntel'),
(18, 108, '2025-12-11 21:22:30', '1440x932', 'MacIntel'),
(19, 108, '2025-12-11 21:22:44', '1440x932', 'MacIntel'),
(20, 108, '2025-12-11 21:23:39', '1440x932', 'MacIntel'),
(21, 116, '2025-12-18 17:01:03', '1440x932', 'MacIntel');

--
-- Trigger `logs`
--
DELIMITER $$
CREATE TRIGGER `punkte_update` AFTER INSERT ON `logs` FOR EACH ROW BEGIN
    UPDATE punkte
    SET punktestand = punktestand + 2
    WHERE user_id = NEW.user_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `punkte`
--

CREATE TABLE `punkte` (
  `user_id` int(11) NOT NULL,
  `punktestand` int(11) NOT NULL DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `punkte`
--

INSERT INTO `punkte` (`user_id`, `punktestand`) VALUES
(1, 0),
(46, 100),
(47, 100),
(49, 100),
(50, 102),
(53, 100),
(54, 100),
(55, 100),
(56, 100),
(57, 100),
(58, 100),
(59, 100),
(60, 100);

--
-- Trigger `punkte`
--
DELIMITER $$
CREATE TRIGGER `plog_update` AFTER UPDATE ON `punkte` FOR EACH ROW BEGIN
    -- Prüfen ob sich der Punktestand geändert hat
    IF NEW.punktestand <> OLD.punktestand THEN
        INSERT INTO punktelog 
            (user_id, datum, art, punkte_aenderung, neuer_punktestand, bemerkung)
        VALUES
            (NEW.user_id, NOW(), 'Automatisch', NEW.punktestand - OLD.punktestand, NEW.punktestand, 'Änderung am Punktestand');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `punktelog`
--

CREATE TABLE `punktelog` (
  `transaktions_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `datum` datetime NOT NULL DEFAULT current_timestamp(),
  `art` varchar(100) DEFAULT NULL,
  `punkte_aenderung` int(11) NOT NULL,
  `neuer_punktestand` int(11) NOT NULL,
  `bemerkung` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `punktelog`
--

INSERT INTO `punktelog` (`transaktions_id`, `user_id`, `datum`, `art`, `punkte_aenderung`, `neuer_punktestand`, `bemerkung`) VALUES
(1, 49, '2025-06-03 22:14:35', NULL, 0, 100, NULL),
(2, 50, '2025-06-04 10:57:28', NULL, 0, 100, NULL),
(3, 50, '2025-06-04 12:05:12', 'Automatisch', 2, 102, 'Änderung am Punktestand');

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
  `google_secret` varchar(255) DEFAULT NULL,
  `online` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `vorname`, `nachname`, `mail`, `adresse`, `plz`, `ort`, `passwort`, `google_secret`, `online`) VALUES
(1, 'dndjd', 'djdjdn', 'dj@qdd.de', 'jdjdkik', 2234, 'djdjsnn', 'djdjj3jjns', NULL, b'0'),
(46, 'monty', 'miner', 'monty.isid@h.de', 'Moltkestraße 32', 72805, 'Lichtenstein', '40e235dd0c7c50a3af4019e342f6046fd5bef9c96c2150f556238fd5d3977fd25b819ec7391178ed62da388f0bf979d90d566056e2808aaa57ce1c880d2aec0b', NULL, b'0'),
(47, 'hadfasd', 'dasdasas', 'monty.isisdd@hs.de', 'sasd 2', 31231, 'Lichtenstein', 'eb4872b5f8a88fe7bdbd52ee5ef10f9a74e937d061ec014be7eedb99f71921ed6e9983772e3dc16ec876b72f7cbdba49b2ce84c635b4d8a93f7ce92c1753e82d', NULL, b'0'),
(49, 'awawe', 'weae', 'montwey.isisdd@hs.de', 'seeasd 2', 31211, 'Lichtenstein', '71244f86f9e78b572b692bda0f11e010dda7930ff4097d331f243955d593779d5473e04de6853003923b2f49cd3df1726a77b3bcfce2bdd60db61fb07c1d3eb9', NULL, b'0'),
(53, 'Max', 'Mustermann', 'max.muster@example.com', 'Musterstraße 1', 10115, 'Berlin', '8c405ae1daf2575440a037284f934421', NULL, b'0'),
(54, 'Lisa', 'Müller', 'lisa.mueller@web.de', 'Bahnhofsweg 4', 20095, 'Hamburg', '8c405ae1daf2575440a037284f934421', NULL, b'0'),
(55, 'Johannes', 'Schmidt', 'j.schmidt@gmx.net', 'Schulstraße 12', 80331, 'München', '8c405ae1daf2575440a037284f934421', NULL, b'1'),
(56, 'Sarah', 'Weber', 'sarah.w@outlook.com', 'Gartenweg 7', 50667, 'Köln', '8c405ae1daf2575440a037284f934421', NULL, b'0'),
(57, 'Michael', 'Klein', 'm.klein@test.de', 'Hauptstraße 88', 60311, 'Frankfurt', '8c405ae1daf2575440a037284f934421', NULL, b'0'),
(58, 'Anna', 'Wagner', 'anna.wagner@pilot.com', 'Flughafenring 2', 70173, 'Stuttgart', '8c405ae1daf2575440a037284f934421', NULL, b'1'),
(59, 'Tom', 'Becker', 'tom.becker@aviation.org', 'Lindenallee 45', 4109, 'Leipzig', '8c405ae1daf2575440a037284f934421', NULL, b'0'),
(60, 'Laura', 'Hoffmann', 'laura.h@student.de', 'Uniplatz 1', 69117, 'Heidelberg', '8c405ae1daf2575440a037284f934421', NULL, b'0'),
(108, 'jnsdcnweifu', 'efoijwenfie', 'testestest@kom.omo', 'uhwefbuewf', 34567, 'wefiwfiuwfnuif', '97de176b0120fae310beec7a2dbcf08a0ac84bf05df571e94b9f9549247f6ccf3db6f8bb67ae5aab9407730d196bfd734050e5d36bc666ecc76fc45abf381138', 'TDALK42HNKQSIXB5', b'1'),
(116, 'qede', 'efewfeqwf', 'k.apaza@outlook.com', 'efnwfirn^', 72760, 'wjfnwejfnwfi', 'df64d06aa2fc25bf1e8464bb2e188346cb7ce02c020ee255bf65cf025508d11ca4218b75ff6e699567115dc4d41e84d8aa7df708f609c5898a911065d5b92014', 'EVT2MG55IUFVIFFV', b'1');

--
-- Trigger `user`
--
DELIMITER $$
CREATE TRIGGER `plog_erster_eintrag` AFTER INSERT ON `user` FOR EACH ROW BEGIN
	INSERT INTO punktelog (user_id, neuer_punktestand) VALUES (NEW.id, 100);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `punkte_erster_eintrag` AFTER INSERT ON `user` FOR EACH ROW BEGIN
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
  `kunde_id` int(11) NOT NULL,
  `erstellt_am` datetime DEFAULT current_timestamp(),
  `aktiv` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `warenkorbkopf`
--

INSERT INTO `warenkorbkopf` (`id`, `kunde_id`, `erstellt_am`, `aktiv`) VALUES
(1, 108, '2025-12-11 23:02:55', 1);

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
-- Daten für Tabelle `warenkorbposition`
--

INSERT INTO `warenkorbposition` (`id`, `warenkorb_id`, `artikel_id`, `menge`) VALUES
(1, 1, 1001, 2);

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
-- Indizes für die Tabelle `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `punkte`
--
ALTER TABLE `punkte`
  ADD PRIMARY KEY (`user_id`);

--
-- Indizes für die Tabelle `punktelog`
--
ALTER TABLE `punktelog`
  ADD PRIMARY KEY (`transaktions_id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `warenkorbkopf`
--
ALTER TABLE `warenkorbkopf`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT für Tabelle `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT für Tabelle `punktelog`
--
ALTER TABLE `punktelog`
  MODIFY `transaktions_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `rechnungskopf`
--
ALTER TABLE `rechnungskopf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT für Tabelle `warenkorbkopf`
--
ALTER TABLE `warenkorbkopf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `warenkorbposition`
--
ALTER TABLE `warenkorbposition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bestellposition`
--
ALTER TABLE `bestellposition`
  ADD CONSTRAINT `bestellposition_ibfk_1` FOREIGN KEY (`bestellung_id`) REFERENCES `bestellkopf` (`id`),
  ADD CONSTRAINT `bestellposition_ibfk_2` FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`);

--
-- Constraints der Tabelle `rechnungskopf`
--
ALTER TABLE `rechnungskopf`
  ADD CONSTRAINT `rechnungskopf_ibfk_1` FOREIGN KEY (`bestellung_id`) REFERENCES `bestellkopf` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
