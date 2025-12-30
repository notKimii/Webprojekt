-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 30. Dez 2025 um 22:16
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
  `kategorie` varchar(30) DEFAULT NULL,
  `bewertung` tinyint(1) DEFAULT NULL,
  `anzahl_bewertungen` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `rabatt` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `artikel`
--

INSERT INTO `artikel` (`id`, `name`, `beschreibung`, `groesse`, `preis`, `lagerbestand`, `kategorie`, `bewertung`, `anzahl_bewertungen`, `rabatt`) VALUES
(2, 'NeuJahr2026', 'Gutschein: NeuJahr2026', '', 0, 0, 'Code', NULL, 0, 26),
(3, 'GutenRutsch', 'Gutschein: GutenRutsch', 'O', 0, 0, 'Code', NULL, 0, 10),
(1001, 'Bose A30 Aviation Headset', 'Premium ANR-Headset mit hohem Tragekomfort und exzellenter Lärmreduzierung.', 'O', 1299, 25, 'Headsets', 5, 7, NULL),
(1002, 'Lightspeed Zulu 3 ANR Headset', 'Beliebtes ANR-Headset, bekannt für Komfort, Haltbarkeit und klare Audioqualität.', 'O', 950, 30, 'Headsets', 4, 6, NULL),
(1003, 'David Clark H10-13.4 Aviation Headset', 'Klassisches, robustes PNR-Headset, ein Standard in der Allgemeinen Luftfahrt.', 'O', 389, 50, 'Headsets', 4, 4, NULL),
(1004, 'Yaesu FTA-550L Pro-X', 'Luftfahrt-Handfunkgerät mit NAV/COM und GPS-Empfänger.', 'O', 299, 15, 'Headsets', NULL, 0, NULL),
(1005, 'Sennheiser S1 Digital Aviation Headset', 'ANR-Headset mit adaptiver Lärmkompensation und individuell einstellbarem Anpressdruck.', 'O', 1050, 18, 'Headsets', NULL, 0, NULL),
(1006, 'Icom IC-A25NE (8.33/25 kHz)', 'Leistungsstarkes Handfunkgerät mit Navigation (VOR, GPS) und Bluetooth.', 'O', 489, 22, 'Headsets', 4, 1, NULL),
(1007, 'Garmin aera 660 Portable Aviation GPS', 'Tragbares GPS mit Touchscreen, 3D Vision und umfangreichen Navigationsfunktionen.', 'O', 849, 20, 'Navigation', NULL, 0, NULL),
(1008, 'ICAO Karte Deutschland (Set)', 'Offizielles Kartenset der Deutschen Flugsicherung für VFR-Flüge in Deutschland.', 'O', 25, 150, 'Navigation', NULL, 0, NULL),
(1009, 'Jeppesen CR-3 Circular Flight Computer', 'Klassischer mechanischer Flugrechner für Flugplanungsberechnungen.', 'O', 36, 70, 'Navigation', 1, 1, NULL),
(1010, 'ASA KB-3 Tri-Fold Kneeboard', 'Dreifach faltbares Kniebrett mit Klemmbrett, Stifthaltern und Kartentaschen.', 'O', 50, 40, 'Navigation', NULL, 0, NULL),
(1011, 'Garmin GDL 50 Portable ADS-B Receiver', 'Tragbarer Empfänger für ADS-B Wetter- und Verkehrsdaten, Anzeige auf kompatiblen Geräten.', 'O', 799, 15, 'Navigation', NULL, 0, NULL),
(1012, 'SkyDemon Lizenz (1 Jahr)', 'Umfassende Flugplanungs- und Navigationssoftware für VFR-Piloten in Europa.', 'O', 149, 0, 'Navigation', NULL, 0, NULL),
(1013, 'Randolph Engineering Aviator (55mm, Gold)', 'Klassische Piloten-Sonnenbrille, nach US Militärspezifikationen gefertigt.', 'O', 219, 35, 'Pilotenkleidung & Accessoires', NULL, 0, NULL),
(1014, 'Garmin D2 Mach 1 Aviator Smartwatch', 'Premium GPS-Smartwatch für Piloten mit umfassenden Flug-, Wetter- und Fitnessfunktionen.', 'O', 1199, 10, 'Pilotenkleidung & Accessoires', NULL, 0, NULL),
(1015, '\"Alpha\" Pilotenhemd, weiß, Kurzarm', 'Pilotenhemd aus pflegeleichtem Baumwollmischung, mit Schulterklappen.', 'O', 40, 100, 'Pilotenkleidung & Accessoires', NULL, 0, NULL),
(1016, 'Design4Pilots \"Pilot Case Daily\"', 'Kompakter und robuster Pilotentrolley für den täglichen Gebrauch oder kurze Reisen.', 'O', 189, 12, 'Pilotenkleidung & Accessoires', NULL, 0, NULL),
(1017, 'Ray-Ban Aviator Classic RB3025', 'Die originale Pilotenbrille, ein zeitloser Klassiker mit hervorragendem UV-Schutz.', 'O', 150, 60, 'Pilotenkleidung & Accessoires', NULL, 0, NULL),
(1018, 'Alpha Industries MA-1 Fliegerjacke', 'Kultige Nylon-Fliegerjacke mit orangem Innenfutter und robuster Verarbeitung.', 'O', 179, 45, 'Pilotenkleidung & Accessoires', NULL, 0, NULL),
(1019, 'Jeppesen Captain Flight Bag', 'Geräumige Flugtasche mit vielen Fächern für Headsets, Karten und Zubehör.', 'O', 129, 28, 'Flugtaschen & Koffer', NULL, 0, NULL),
(1020, 'Design4Pilots \"Pilot Weekend\" Tasche', 'Kompakte und stilvolle Tasche für Kurztrips oder als Alltagstasche für Piloten.', 'O', 99, 22, 'Flugtaschen & Koffer', NULL, 0, NULL),
(1021, 'Brightline Bags B7 Flight \"Echo\" Konfiguration', 'Hochgradig anpassbare, modulare Flugtasche.', 'O', 229, 19, 'Flugtaschen & Koffer', NULL, 0, NULL),
(1022, 'ASA AirClassics Flight Bag', 'Strapazierfähige und kompakte Tasche für die wichtigsten Pilotenutensilien.', 'O', 80, 33, 'Flugtaschen & Koffer', NULL, 0, NULL),
(1023, 'Lightspeed \"The Cann\" Flight Bag', 'Elegante Leder-Flugtasche, benannt nach dem berühmten Autor Ernest K. Gann.', 'O', 199, 14, 'Flugtaschen & Koffer', NULL, 0, NULL),
(1024, 'Aerocoast Pro EFB + Cooler II', 'Speziell für Airline-Piloten entwickelte Tasche mit EFB-Fach und integriertem Kühler.', 'O', 165, 17, 'Flugtaschen & Koffer', NULL, 0, NULL),
(1025, 'PPL-A Lehrbuch Set (z.B. Oxford Aviation)', 'Umfassendes Lehrbuchset für die EASA PPL(A) Theorie.', 'O', 249, 40, 'Flugbücher & Lernmaterial', NULL, 0, NULL),
(1026, 'ASA Standard Pilot Logbook (SP-30)', 'Standardisiertes Logbuch zur Erfassung von Flugzeiten und Erfahrungen.', 'O', 13, 200, 'Flugbücher & Lernmaterial', NULL, 0, NULL),
(1027, '\"Stick and Rudder\" von Wolfgang Langewiesche', 'Ein Klassiker über die Kunst des Fliegens, tiefgründig und zeitlos.', 'O', 23, 35, 'Flugbücher & Lernmaterial', NULL, 0, NULL),
(1028, 'Aviationexam PPL Fragensammlung (1 Jahr Zugang)', 'Online-Zugang zu einer umfangreichen Datenbank mit EASA PPL Prüfungsfragen.', 'O', 89, 0, 'Flugbücher & Lernmaterial', NULL, 0, NULL),
(1029, 'Jeppesen ATPL Training Set (E-Books)', 'Kompletter Satz an E-Books für die ATPL(A) Theorie gemäß EASA-Richtlinien.', 'O', 699, 0, 'Flugbücher & Lernmaterial', NULL, 0, NULL),
(1030, '\"Pilots Weather\" by Brian Cosgrove', 'Detailliertes Buch über Meteorologie speziell für Piloten.', 'O', 35, 25, 'Flugbücher & Lernmaterial', NULL, 0, NULL),
(1031, '\"Remove Before Flight\" Pitot Cover Universal', 'Schützt das Pitot-Rohr vor Verstopfung durch Insekten oder Schmutz am Boden.', 'O', 10, 300, 'Flugzeugzubehör (GA)', NULL, 0, NULL),
(1032, 'Flugzeug Radkeile, Gummi (Paar)', 'Robuste Gummikeile zur Sicherung von Leichtflugzeugen am Boden.', 'O', 30, 90, 'Flugzeugzubehör (GA)', NULL, 0, NULL),
(1033, 'GATS Jar Fuel Tester', 'Transparenter Treibstofftester zur Prüfung auf Wasser und Sedimente im Kraftstoff.', 'O', 19, 120, 'Flugzeugzubehör (GA)', NULL, 0, NULL),
(1034, 'Aircraft Tie-Down Kit (3x Spanngurte, Seile)', 'Set zur sicheren Verankerung von Flugzeugen im Freien.', 'O', 59, 40, 'Flugzeugzubehör (GA)', NULL, 0, NULL),
(1035, 'Aero Cosmetics Wash Wax ALL (Konzentrat, 1L)', 'Wasserloses Reinigungs- und Wachsystsem für Flugzeuge, umweltfreundlich.', 'O', 45, 55, 'Flugzeugzubehör (GA)', NULL, 0, NULL),
(1036, 'Tempest AA472 Oil Filter Cutter', 'Werkzeug zum sauberen Öffnen von Ölfiltern zur Inspektion auf Metallpartikel.', 'O', 89, 12, 'Flugzeugzubehör (GA)', NULL, 0, NULL),
(1037, 'Crewsaver Crewfit 165N Schwimmweste', 'Automatische Rettungsweste über Wasser, leicht und komfortabel.', 'O', 99, 20, 'Sicherheitsaustrüstung', NULL, 0, NULL),
(1038, 'ACR ResQLink 400 PLB', 'Kompakte und robuste persönliche Ortungsbake (PLB) für Notfälle.', 'O', 329, 8, 'Sicherheitsaustrüstung', NULL, 0, NULL),
(1039, 'Lufthansa Erste-Hilfe-Set DIN 13157 erweitert', 'Umfassendes Erste-Hilfe-Set, erweitert mit flugspezifischen Inhalten oder Empfehlungen.', 'O', 45, 38, 'Sicherheitsaustrüstung', NULL, 0, NULL),
(1040, 'H3R Aviation Halon 1211 Feuerlöscher (A344T)', 'Kompakter Halon 1211 Feuerlöscher, für den Einsatz im Cockpit zugelassen.', 'O', 289, 10, 'Sicherheitsaustrüstung', NULL, 0, NULL),
(1041, 'SOL Escape Bivvy Bag Orange', 'Isolierender Notfall-Biwacksack, reflektiert 70% der Körperwärme.', 'O', 36, 36, 'Sicherheitsaustrüstung', NULL, 0, NULL),
(1042, 'Forensics Detectors CO Detector for Aircraft', 'Tragbarer Kohlenmonoxid-Detektor speziell für Flugzeuge, mit Alarm.', 'O', 159, 7, 'Sicherheitsaustrüstung', NULL, 0, NULL);

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
(22, 60, 1001, 5, 'Das beste Headset, das ich je hatte. Das ANC ist der Wahnsinn!', '2023-10-15 12:30:00'),
(32, 64, 1003, 4, 'Gut aber stört beim tragen', '2025-12-17 23:38:47'),
(33, 65, 1006, 4, 'hört sich schlecht an', '2025-12-18 16:13:14'),
(0, 65, 1009, 1, 'Ist sehr schlecht', '2025-12-18 16:56:16'),
(0, 65, 1003, 5, 'Bestes Headset jemals', '2025-12-19 10:26:10');

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
-- Tabellenstruktur für Tabelle `gutscheincodes`
--

CREATE TABLE `gutscheincodes` (
  `gutscheinCode` varchar(11) NOT NULL,
  `erstelltAm` date NOT NULL DEFAULT current_timestamp(),
  `aktiv` tinyint(1) NOT NULL,
  `wert` int(3) NOT NULL,
  `art` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `gutscheincodes`
--

INSERT INTO `gutscheincodes` (`gutscheinCode`, `erstelltAm`, `aktiv`, `wert`, `art`) VALUES
('GutenRutsch', '2025-12-30', 1, 10, 0),
('NeuJahr2026', '2025-12-30', 1, 10, 0);

--
-- Trigger `gutscheincodes`
--
DELIMITER $$
CREATE TRIGGER `GutscheinCodes als Artikel` AFTER INSERT ON `gutscheincodes` FOR EACH ROW BEGIN
    DECLARE next_id INT DEFAULT NULL;

    IF NOT EXISTS (SELECT 1 FROM artikel WHERE id = 1) THEN
        SET next_id = 1;
    ELSE
        SELECT MIN(a1.id + 1) INTO next_id
        FROM artikel a1
        WHERE a1.id < 1000
        AND NOT EXISTS (
            SELECT 1 FROM artikel a2 
            WHERE a2.id = a1.id + 1 AND a2.id < 1000
        );
    END IF;
    
    -- Prüfen ob ID gefunden und unter 1000
    IF next_id IS NULL OR next_id >= 1000 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Keine freie ID unter 1000 verfügbar für Gutscheincode';
    END IF;
    
    INSERT INTO `artikel` (`id`, `name`, `beschreibung`, `groesse`, `preis`, `rabatt`, `lagerbestand`, `kategorie`, `bewertung`, `anzahl_bewertungen`)
    VALUES (
        next_id,
        NEW.gutscheinCode,
        CONCAT('Gutschein: ', NEW.gutscheinCode),
        'O',
        0,
        NEW.wert,
        0,
        'Code',
        NULL,
        0
    );
END
$$
DELIMITER ;

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
(29, 65, '2025-12-28 05:05:44', '1920x1080', 'Win32'),
(30, 65, '2025-12-28 05:10:06', '1920x1080', 'Win32'),
(31, 65, '2025-12-28 05:22:21', '1920x1080', 'Win32'),
(32, 65, '2025-12-28 05:28:26', '1920x1080', 'Win32'),
(34, 65, '2025-12-28 05:42:20', '1920x1080', 'Win32'),
(35, 66, '2025-12-30 18:23:03', '1440x932', 'MacIntel');

--
-- Trigger `logs`
--
DELIMITER $$
CREATE TRIGGER `punkte_update` AFTER INSERT ON `logs` FOR EACH ROW UPDATE punkte
SET punktestand = punktestand + 2
WHERE user_id = NEW.user_id
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
(60, 100),
(64, 114),
(65, 110),
(66, 102);

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
(1, 65, '2025-12-28 05:42:20', 'Automatisch', 2, 110, 'Änderung am Punktestand'),
(2, 66, '2025-12-30 18:15:19', NULL, 0, 100, NULL),
(3, 66, '2025-12-30 18:23:03', 'Automatisch', 2, 102, 'Änderung am Punktestand');

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
-- Tabellenstruktur für Tabelle `rechnungsposition`
--

CREATE TABLE `rechnungsposition` (
  `id` int(11) NOT NULL,
  `rechnung_id` int(11) NOT NULL,
  `artikel_id` int(11) NOT NULL,
  `artikel_name` varchar(255) NOT NULL,
  `menge` int(11) NOT NULL DEFAULT 1,
  `preis` decimal(10,2) NOT NULL COMMENT 'Einzelpreis zum Zeitpunkt der Rechnung',
  `mwst_satz` decimal(5,2) NOT NULL DEFAULT 19.00
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
(64, 'Fabian', 'tittl', 'Andre.Reiff@Student.Reutlingen-University.DE', 'Moltkestraße 32', 72805, 'Lichtenstein', '47d658a097d490e0d26650c77ff4bf755fa8a3d86acc5df95b3844f4b3c9cb80b20ac1fe7e5b67564d4c15798a1b9b4c5bcfd2f7b5a6eeeb585381c95221fc1c', 'WOHLEZEIY7EBKYHJ', b'1'),
(65, 'Andre', 'Reiff', 'andre.reiff@online.de', 'Moltkestraße 32', 72805, 'Lichtenstein', '47d658a097d490e0d26650c77ff4bf755fa8a3d86acc5df95b3844f4b3c9cb80b20ac1fe7e5b67564d4c15798a1b9b4c5bcfd2f7b5a6eeeb585381c95221fc1c', 'CTO6DGUKUT44GWV5', b'1'),
(66, 'Kimi', 'Kimi', 'esrr1979@hotmail.com', 'Straße der Einheit 43', 80983, 'Niederschwalben', '588d191ff5118b793a93bfe317ed53f77490923747c07eeb9924a7c1168f0cddc4289803d47abb44f1b57f14fc47cab399c61351dbdd932db9feddb6aedfa0ce', 'V7XSGPSBLERGODIY', b'1');

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
  `kunde_id` int(11) DEFAULT NULL,
  `erstellt_am` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `warenkorbkopf`
--

INSERT INTO `warenkorbkopf` (`id`, `kunde_id`, `erstellt_am`) VALUES
(1, 65, '2025-12-11 23:02:55'),
(2, 66, '2025-12-30 18:24:01');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `warenkorbposition`
--

CREATE TABLE `warenkorbposition` (
  `warenkorb_id` int(11) NOT NULL,
  `artikel_id` int(11) NOT NULL,
  `menge` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `warenkorbposition`
--

INSERT INTO `warenkorbposition` (`warenkorb_id`, `artikel_id`, `menge`) VALUES
(1, 1001, 2),
(2, 3, 1),
(2, 1026, 5),
(2, 1038, 10);

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
-- Indizes für die Tabelle `gutscheincodes`
--
ALTER TABLE `gutscheincodes`
  ADD PRIMARY KEY (`gutscheinCode`);

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
-- Indizes für die Tabelle `rechnungsposition`
--
ALTER TABLE `rechnungsposition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rechnung_id` (`rechnung_id`),
  ADD KEY `artikel_id` (`artikel_id`);

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
  ADD PRIMARY KEY (`warenkorb_id`,`artikel_id`);

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
-- AUTO_INCREMENT für Tabelle `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

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
-- AUTO_INCREMENT für Tabelle `rechnungsposition`
--
ALTER TABLE `rechnungsposition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT für Tabelle `warenkorbkopf`
--
ALTER TABLE `warenkorbkopf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
