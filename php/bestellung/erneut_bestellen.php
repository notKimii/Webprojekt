<?php
// Erneut bestellen - Erstellt direkt eine neue Bestellung mit aktuellen Preisen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../include/connectcon.php';

// Überprüfung ob Benutzer angemeldet ist
if (!isset($_SESSION['user']['id'])) {
    $_SESSION['error_message'] = 'Sie müssen angemeldet sein, um eine Bestellung zu wiederholen.';
    header('Location: /Webprojekt/php/kundenkonto.php');
    exit();
}

$userID = (int)$_SESSION['user']['id'];
$bestellungId = isset($_GET['bestellung_id']) ? (int)$_GET['bestellung_id'] : 0;
$bestaetigt = isset($_GET['bestaetigt']) ? (int)$_GET['bestaetigt'] : 0;

if ($bestellungId <= 0) {
    $_SESSION['error_message'] = 'Ungültige Bestellungs-ID.';
    header('Location: /Webprojekt/php/kundenkonto.php');
    exit();
}

try {
    // Prüfen ob die Bestellung dem User gehört und Versandart abrufen
    $stmt = $con->prepare("SELECT user_id, versandart FROM bestellkopf WHERE id = ?");
    $stmt->bind_param('i', $bestellungId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error_message'] = 'Bestellung nicht gefunden.';
        header('Location: /Webprojekt/php/kundenkonto.php');
        exit();
    }
    
    $bestellung = $result->fetch_assoc();
    
    if ($bestellung['user_id'] != $userID) {
        $_SESSION['error_message'] = 'Sie haben keine Berechtigung für diese Bestellung.';
        header('Location: /Webprojekt/php/kundenkonto.php');
        exit();
    }
    
    $versandartId = (int)($bestellung['versandart'] ?? 2);
    $stmt->close();
    
    // Artikel aus der alten Bestellung holen (ALLE Artikel inkl. Rabattartikel)
    $stmtArtikel = $con->prepare("
        SELECT bp.artikel_id, bp.menge, a.preis, a.name, a.lagerbestand, a.kategorie, a.rabatt
        FROM bestellposition bp
        JOIN artikel a ON bp.artikel_id = a.id
        WHERE bp.bestellung_id = ?
    ");
    $stmtArtikel->bind_param('i', $bestellungId);
    $stmtArtikel->execute();
    $artikelResult = $stmtArtikel->get_result();
    
    $normaleArtikel = [];
    $rabattArtikel = [];
    $nichtVerfuegbareNormale = [];
    $nichtVerfuegbareRabatte = [];
    $ignoriertePunkte = [];
    $gesamtbetrag = 0;
    
    // Versandkosten
    $versandkosten = [1 => 11.90, 2 => 6.90, 3 => 16.90];
    $versandkostenBetrag = $versandkosten[$versandartId] ?? 6.90;
    
    while ($artikel = $artikelResult->fetch_assoc()) {
        $artikelId = (int)$artikel['artikel_id'];
        $menge = (int)$artikel['menge'];
        $aktuellerPreis = (float)$artikel['preis'];
        $lagerbestand = (int)$artikel['lagerbestand'];
        $name = $artikel['name'];
        $kategorie = $artikel['kategorie'];
        $rabatt = (int)($artikel['rabatt'] ?? 0);
        
        // Unterscheidung zwischen normalen Artikeln und Rabattartikeln
        if ($kategorie === 'Punkte') {
            // Punkteartikel ignorieren: diese dürfen nicht erneut bestellt werden
            $ignoriertePunkte[] = $name . ' (' . $menge . ' Einheiten)';
            continue;
        } elseif ($kategorie === 'Code') {
            // Prüfe in gutscheincodes-Tabelle ob noch aktiv
            $stmtGutschein = $con->prepare("SELECT aktiv, wert, art FROM gutscheincodes WHERE gutscheinCode = ? AND aktiv = 1");
            $stmtGutschein->bind_param('s', $name);
            $stmtGutschein->execute();
            $gutscheinResult = $stmtGutschein->get_result();
            
            if ($gutscheinResult->num_rows > 0) {
                $gutschein = $gutscheinResult->fetch_assoc();
                $rabattArtikel[] = [
                    'artikel_id' => $artikelId,
                    'menge' => $menge,
                    'preis' => $aktuellerPreis,
                    'name' => $name,
                    'rabatt' => (int)$gutschein['wert'],
                    'art' => (int)$gutschein['art']
                ];
            } else {
                $nichtVerfuegbareRabatte[] = $name . ' (nicht mehr aktiv)';
            }
            $stmtGutschein->close();
        } else {
            // Normale Artikel - Lagerbestand prüfen
            if ($lagerbestand > 0 && $lagerbestand >= $menge) {
                $normaleArtikel[] = [
                    'artikel_id' => $artikelId,
                    'menge' => $menge,
                    'preis' => $aktuellerPreis,
                    'name' => $name
                ];
                $gesamtbetrag += $menge * $aktuellerPreis;
            } else {
                $nichtVerfuegbareNormale[] = $name;
            }
        }
    }
    
    $stmtArtikel->close();
    
    // Wenn keine normalen Artikel verfügbar sind, Fehler
    if (count($normaleArtikel) === 0) {
        $_SESSION['error_message'] = 'Keine Artikel aus der Bestellung sind mehr verfügbar.';
        header('Location: /Webprojekt/php/kundenkonto.php');
        exit();
    }
    
    // Wenn Artikel fehlen und noch nicht bestätigt wurde, zur Bestätigungsseite
    $artikelFehlen = (count($nichtVerfuegbareNormale) > 0 || count($nichtVerfuegbareRabatte) > 0 || count($ignoriertePunkte) > 0);
    
    if ($artikelFehlen && $bestaetigt !== 1) {
        // Daten in Session speichern für Bestätigungsseite
        $_SESSION['reorder_data'] = [
            'bestellung_id' => $bestellungId,
            'normale_artikel' => $normaleArtikel,
            'rabatt_artikel' => $rabattArtikel,
            'nicht_verfuegbar_normal' => $nichtVerfuegbareNormale,
            'nicht_verfuegbar_rabatt' => $nichtVerfuegbareRabatte,
            'ignorierte_punkte' => $ignoriertePunkte,
            'versandart_id' => $versandartId,
            'versandkosten' => $versandkostenBetrag
        ];
        
        // Zur Bestätigungsseite weiterleiten
        header('Location: erneut_bestellen_bestaetigung.php');
        exit();
    }
    
    // Berechne Gesamtbetrag mit Rabatten (Punkteartikel wurden bereits ausgeschlossen)
    $subtotal = $gesamtbetrag;
    
    foreach ($rabattArtikel as $rabatt) {
        if ($rabatt['rabatt'] > 0) {
            // Prozent-Rabatt
            $rabattBetrag = $subtotal * ($rabatt['rabatt'] / 100);
            $gesamtbetrag -= $rabattBetrag;
        }
    }
    
    // Versandkosten zum Gesamtbetrag hinzufügen
    $gesamtbetrag += $versandkostenBetrag;
    
    // Neue Bestellung erstellen
    $stmt2 = $con->prepare("INSERT INTO bestellkopf (user_id, bestelldatum, gesamtbetrag, status, versandart) VALUES (?, NOW(), ?, 'bezahlt', ?)");
    $stmt2->bind_param('idi', $userID, $gesamtbetrag, $versandartId);
    $stmt2->execute();
    $neueBestellungId = $con->insert_id;
    $stmt2->close();
    
    // Normale Artikel als Bestellpositionen einfügen
    $stmtPos = $con->prepare("INSERT INTO bestellposition (bestellung_id, artikel_id, menge, einzelpreis) VALUES (?, ?, ?, ?)");
    
    foreach ($normaleArtikel as $item) {
        $artikelId = $item['artikel_id'];
        $menge = $item['menge'];
        $preis = $item['preis'];
        $stmtPos->bind_param('iiid', $neueBestellungId, $artikelId, $menge, $preis);
        $stmtPos->execute();
    }
    
    // Rabattartikel mit berechneten negativen Preisen einfügen (nur Gutscheine, keine Punkte)
    $aktuellerSubtotal = $subtotal;
    
    // Keine Punkte mehr abziehen beim erneuten Bestellen
    
    foreach ($rabattArtikel as $rabatt) {
        $artikelId_var = $rabatt['artikel_id'];
        
        if (isset($rabatt['rabatt']) && $rabatt['rabatt'] > 0) {
            // Prozent-Rabatt - berechne negativen Preis basierend auf aktuellem Subtotal
            $rabattProzent = $rabatt['rabatt'];
            $rabattBetrag = $aktuellerSubtotal * ($rabattProzent / 100);
            $negativerPreis_var = -$rabattBetrag;
            $mengeRabatt_var = 1;
            $stmtPos->bind_param('iiid', $neueBestellungId, $artikelId_var, $mengeRabatt_var, $negativerPreis_var);
            $stmtPos->execute();
            $aktuellerSubtotal -= $rabattBetrag; // Subtotal für nächsten Rabatt reduzieren
        }
    }
    
    $stmtPos->close();
    
    // Hinweis wenn einige Artikel nicht verfügbar waren
    if ($artikelFehlen) {
        $warnungen = [];
        if (count($nichtVerfuegbareNormale) > 0) {
            $warnungen[] = 'Folgende Artikel waren nicht mehr verfügbar: ' . implode(', ', $nichtVerfuegbareNormale);
        }
        if (count($nichtVerfuegbareRabatte) > 0) {
            $warnungen[] = 'Folgende Rabatte waren nicht mehr verfügbar: ' . implode(', ', $nichtVerfuegbareRabatte);
        }
        if (count($ignoriertePunkte) > 0) {
            $warnungen[] = 'Punkteartikel können bei erneuten Bestellungen nicht berücksichtigt werden: ' . implode(', ', $ignoriertePunkte);
        }
        $_SESSION['reorder_warning'] = implode(' ', $warnungen);
    }
    
    // Session-Daten löschen
    unset($_SESSION['reorder_data']);
    
    // Zur Dankesseite weiterleiten (diese triggert die Billing-Mail)
    header('Location: dank.php?bestellung_id=' . $neueBestellungId);
    exit();
    
} catch (Exception $e) {
    error_log('Fehler beim erneuten Bestellen: ' . $e->getMessage());
    $_SESSION['error_message'] = 'Ein Fehler ist aufgetreten beim Erstellen der Bestellung. Bitte versuchen Sie es später erneut.';
    header('Location: /Webprojekt/php/kundenkonto.php');
    exit();
}
?>
