<?php
ob_start();
include "../include/connectcon.php";
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Punkte einlösen via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redeem_points']) && isset($_POST['ajax'])) {
  ob_end_clean();
  header('Content-Type: application/json');
  
  try {
    // Kunden-ID ermitteln
    $kundenId = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
    
    if (!$kundenId) {
      echo json_encode(['success' => false, 'message' => 'Sie müssen angemeldet sein.']);
      exit();
    }
    
    // Aktuelle Punkte des Users abrufen
    $stmt = $con->prepare("SELECT punktestand FROM punkte WHERE user_id = ? LIMIT 1");
    $stmt->bind_param('i', $kundenId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
      $userRow = $result->fetch_assoc();
      $verfuegbarePunkte = (int)$userRow['punktestand'];
      
      if ($verfuegbarePunkte < 50) {
        echo json_encode(['success' => false, 'message' => 'Sie benötigen mindestens 50 Punkte.']);
        exit();
      }
      
      // Berechne Menge: Für je 50 Punkte gibt es 0.1 Euro Rabatt
      $menge = floor($verfuegbarePunkte / 50);
      $verwendetePunkte = $menge * 50;
      $rabattBetrag = $menge * 0.1;
      
      // Warenkorb-ID ermitteln
      $stmt2 = $con->prepare("SELECT id FROM warenkorbkopf WHERE kunde_id = ? LIMIT 1");
      $stmt2->bind_param('i', $kundenId);
      $stmt2->execute();
      $res2 = $stmt2->get_result();
      
      if ($res2->num_rows > 0) {
        $warenkorbRow = $res2->fetch_assoc();
        $warenkorbId = (int)$warenkorbRow['id'];
        
        // Prüfen ob Punkte-Gutschein bereits im Warenkorb
        $gutscheinArtikelId = 1; // Artikel ID für Punkte-Gutschein
        $stmt3 = $con->prepare("SELECT artikel_id FROM warenkorbposition WHERE warenkorb_id = ? AND artikel_id = ?");
        $stmt3->bind_param('ii', $warenkorbId, $gutscheinArtikelId);
        $stmt3->execute();
        $res3 = $stmt3->get_result();
        
        if ($res3->num_rows > 0) {
          echo json_encode(['success' => false, 'message' => 'Punkte-Gutschein bereits eingelöst.']);
        } else {
          // Gutschein zum Warenkorb hinzufügen
          $stmt4 = $con->prepare("INSERT INTO warenkorbposition (warenkorb_id, artikel_id, menge) VALUES (?, ?, ?)");
          $stmt4->bind_param('iii', $warenkorbId, $gutscheinArtikelId, $menge);
          
          if ($stmt4->execute()) {
            // Punkte werden erst beim Bestellabschluss abgezogen
            
            echo json_encode([
              'success' => true,
              'message' => $verwendetePunkte . ' Punkte eingelöst! ' . number_format($rabattBetrag, 2, ',', '.') . ' € Rabatt',
              'reload' => true
            ]);
          } else {
            echo json_encode(['success' => false, 'message' => 'Fehler beim Einlösen: ' . $con->error]);
          }
          $stmt4->close();
        }
        $stmt3->close();
      } else {
        echo json_encode(['success' => false, 'message' => 'Warenkorb nicht gefunden.']);
      }
      $stmt2->close();
    } else {
      echo json_encode(['success' => false, 'message' => 'Benutzerdaten nicht gefunden.']);
    }
    $stmt->close();
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
  }
  exit();
}

// Promocode-Verarbeitung via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promo_code']) && isset($_POST['ajax'])) {
  ob_end_clean();
  header('Content-Type: application/json');
  
  try {
    $promoCode = trim($_POST['promo_code']);
    
    if (empty($promoCode)) {
      echo json_encode(['success' => false, 'message' => 'Bitte geben Sie einen Promocode ein.']);
      exit();
    }
    
    // Kunden-ID ermitteln
    $kundenId = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
    
    if (!$kundenId) {
      echo json_encode(['success' => false, 'message' => 'Sie müssen angemeldet sein.']);
      exit();
    }
    
    // Prüfen ob Gutscheincode in der gutscheincodes-Tabelle existiert und aktiv ist
    $stmt = $con->prepare("SELECT aktiv, wert, art FROM gutscheincodes WHERE gutscheinCode = ? AND aktiv = 1 LIMIT 1");
    if (!$stmt) {
      echo json_encode(['success' => false, 'message' => 'Datenbankfehler: ' . $con->error]);
      exit();
    }
    
    $stmt->bind_param('s', $promoCode);
    $stmt->execute();
    $resultGutschein = $stmt->get_result();
    
    if ($resultGutschein->num_rows > 0) {
      $gutschein = $resultGutschein->fetch_assoc();
      $rabatt = (int)$gutschein['wert'];
      
      // Prüfen ob Artikel existiert
      $stmt2 = $con->prepare("SELECT id, name, rabatt FROM artikel WHERE name = ? AND kategorie = 'Code' LIMIT 1");
      if (!$stmt2) {
        echo json_encode(['success' => false, 'message' => 'Datenbankfehler: ' . $con->error]);
        exit();
      }
      
      $stmt2->bind_param('s', $promoCode);
      $stmt2->execute();
      $result = $stmt2->get_result();
      
      if ($result->num_rows > 0) {
        $artikel = $result->fetch_assoc();
        $artikelId = (int)$artikel['id'];
        $rabatt = (int)$artikel['rabatt'];
        $stmt2->close();
        
        // Warenkorb-ID ermitteln
        $stmt3 = $con->prepare("SELECT id FROM warenkorbkopf WHERE kunde_id = ? LIMIT 1");
        $stmt3->bind_param('i', $kundenId);
        $stmt3->execute();
        $res2 = $stmt3->get_result();
        
        if ($res2->num_rows > 0) {
          $warenkorbRow = $res2->fetch_assoc();
          $warenkorbId = (int)$warenkorbRow['id'];
          
          // Prüfen ob Promocode bereits im Warenkorb
          $stmt4 = $con->prepare("SELECT artikel_id FROM warenkorbposition WHERE warenkorb_id = ? AND artikel_id = ?");
          $stmt4->bind_param('ii', $warenkorbId, $artikelId);
          $stmt4->execute();
          $res3 = $stmt4->get_result();
          
          if ($res3->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Promocode bereits verwendet.']);
          } else {
            // Promocode zum Warenkorb hinzufügen
            $stmt5 = $con->prepare("INSERT INTO warenkorbposition (warenkorb_id, artikel_id, menge) VALUES (?, ?, 1)");
            $stmt5->bind_param('ii', $warenkorbId, $artikelId);
            if ($stmt5->execute()) {
              echo json_encode([
                'success' => true, 
                'message' => 'Promocode angewendet! ' . $rabatt . '% Rabatt',
                'reload' => true
              ]);
            } else {
              echo json_encode(['success' => false, 'message' => 'Fehler beim Anwenden: ' . $con->error]);
            }
            $stmt5->close();
          }
          $stmt4->close();
        } else {
          echo json_encode(['success' => false, 'message' => 'Warenkorb nicht gefunden.']);
        }
        $stmt3->close();
      } else {
        echo json_encode(['success' => false, 'message' => 'Artikel nicht gefunden.']);
        $stmt2->close();
      }
    } else {
      echo json_encode(['success' => false, 'message' => 'Ungültiger oder inaktiver Promocode.']);
    }
    
    $stmt->close();
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
  }
  exit();
}

// Bestellverarbeitung - Formular wurde abgeschickt
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstName']) && !isset($_POST['ajax'])) {
  
  // Kunden-ID ermitteln
  $kundenId = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
  
  if ($kundenId) {
    try {
      // Warenkorb-ID ermitteln
      $stmt = $con->prepare("SELECT id FROM warenkorbkopf WHERE kunde_id = ? LIMIT 1");
      $stmt->bind_param('i', $kundenId);
      $stmt->execute();
      $result = $stmt->get_result();
      
      if ($result->num_rows > 0) {
        $warenkorbRow = $result->fetch_assoc();
        $warenkorbId = (int)$warenkorbRow['id'];
        
        // Warenkorbdaten laden für Gesamtbetrag und Versandart aus POST holen
        $versandMethode = isset($_POST['shipping_method']) ? $_POST['shipping_method'] : 'dhl';
        $cartData = loadCartData($con, $kundenId, $versandMethode);
        $gesamtbetrag = $cartData['total'];
        
        // Versandart ID ermitteln (1=LPD, 2=DHL, 3=DHL Express)
        $versandartMapping = ['lpd' => 1, 'dhl' => 2, 'dhl-express' => 3];
        $versandartId = isset($versandartMapping[$versandMethode]) ? $versandartMapping[$versandMethode] : 2;
        
        // Spalte versandart zu bestellkopf hinzufügen falls nicht vorhanden
        $con->query("ALTER TABLE bestellkopf ADD COLUMN IF NOT EXISTS versandart INT(1) DEFAULT 2");
        
        // 1. Bestellkopf erstellen mit Versandart
        $stmt2 = $con->prepare("INSERT INTO bestellkopf (user_id, bestelldatum, gesamtbetrag, status, versandart) VALUES (?, NOW(), ?, 'bezahlt', ?)");
        $stmt2->bind_param('idi', $kundenId, $gesamtbetrag, $versandartId);
        $stmt2->execute();
        $bestellungId = $con->insert_id;
        $stmt2->close();
        
        // 2. Warenkorbpositionen in Bestellpositionen kopieren
        // 2a. Normale Artikel (ohne Code/Punkte) - mit Berücksichtigung von Artikelrabatten
        $stmtNormaleArtikel = $con->prepare("
          SELECT wp.artikel_id, wp.menge, a.preis, a.rabatt
          FROM warenkorbposition wp
          JOIN artikel a ON wp.artikel_id = a.id
          WHERE wp.warenkorb_id = ? AND a.kategorie != 'Code' AND a.kategorie != 'Punkte'
        ");
        $stmtNormaleArtikel->bind_param('i', $warenkorbId);
        $stmtNormaleArtikel->execute();
        $resNormaleArtikel = $stmtNormaleArtikel->get_result();
        
        $stmtInsertArtikel = $con->prepare("
          INSERT INTO bestellposition (bestellung_id, artikel_id, menge, einzelpreis)
          VALUES (?, ?, ?, ?)
        ");
        
        while ($artikel = $resNormaleArtikel->fetch_assoc()) {
          $artikelId = (int)$artikel['artikel_id'];
          $menge = (int)$artikel['menge'];
          $preis = (float)$artikel['preis'];
          $rabatt = floatval($artikel['rabatt'] ?? 0);
          
          // Effektiven Preis nach Artikelrabatt berechnen
          $effektiverPreis = $preis;
          if ($rabatt > 0) {
            $effektiverPreis = $preis * (1 - $rabatt / 100);
          }
          
          $stmtInsertArtikel->bind_param('iiid', $bestellungId, $artikelId, $menge, $effektiverPreis);
          $stmtInsertArtikel->execute();
        }
        
        $stmtInsertArtikel->close();
        $stmtNormaleArtikel->close();
        
        // 2b. Rabatt-Artikel (Code & Punkte) mit berechnetem negativen Preis
        // Zuerst Subtotal der normalen Artikel berechnen (mit Artikelrabatten)
        $stmtSubtotal = $con->prepare("
          SELECT wp.menge, a.preis, a.rabatt
          FROM warenkorbposition wp
          JOIN artikel a ON wp.artikel_id = a.id
          WHERE wp.warenkorb_id = ? AND a.kategorie != 'Code' AND a.kategorie != 'Punkte'
        ");
        $stmtSubtotal->bind_param('i', $warenkorbId);
        $stmtSubtotal->execute();
        $resSubtotal = $stmtSubtotal->get_result();
        $subtotalArtikel = 0;
        while ($rowSub = $resSubtotal->fetch_assoc()) {
          $preis = (float)$rowSub['preis'];
          $rabatt = floatval($rowSub['rabatt'] ?? 0);
          $menge = (int)$rowSub['menge'];
          
          // Effektiven Preis nach Artikelrabatt berechnen
          $effektiverPreis = $preis;
          if ($rabatt > 0) {
            $effektiverPreis = $preis * (1 - $rabatt / 100);
          }
          
          $subtotalArtikel += $effektiverPreis * $menge;
        }
        $stmtSubtotal->close();
        
        // Promocodes: Rabatt in Prozent -> Negativer Betrag berechnen
        $stmtPromo = $con->prepare("
          SELECT wp.artikel_id, wp.menge, a.name, a.rabatt, a.preis
          FROM warenkorbposition wp
          JOIN artikel a ON wp.artikel_id = a.id
          WHERE wp.warenkorb_id = ? AND a.kategorie = 'Code'
        ");
        $stmtPromo->bind_param('i', $warenkorbId);
        $stmtPromo->execute();
        $resPromo = $stmtPromo->get_result();
        
        while ($rowPromo = $resPromo->fetch_assoc()) {
          $rabattProzent = (int)$rowPromo['rabatt'];
          $rabattBetrag = $subtotalArtikel * ($rabattProzent / 100);
          $negativerPreis = -$rabattBetrag; // Negativer Einzelpreis
          
          $stmtInsertPromo = $con->prepare("
            INSERT INTO bestellposition (bestellung_id, artikel_id, menge, einzelpreis)
            VALUES (?, ?, 1, ?)
          ");
          $stmtInsertPromo->bind_param('iid', $bestellungId, $rowPromo['artikel_id'], $negativerPreis);
          $stmtInsertPromo->execute();
          $stmtInsertPromo->close();
          
          // Subtotal für nächsten Promocode aktualisieren
          $subtotalArtikel -= $rabattBetrag;
        }
        $stmtPromo->close();
        
        // Punkte-Artikel: Preis ist bereits negativ (0.10 € pro Punkt)
        $stmtPunkteArtikel = $con->prepare("
          SELECT wp.artikel_id, wp.menge, a.preis
          FROM warenkorbposition wp
          JOIN artikel a ON wp.artikel_id = a.id
          WHERE wp.warenkorb_id = ? AND a.kategorie = 'Punkte'
        ");
        $stmtPunkteArtikel->bind_param('i', $warenkorbId);
        $stmtPunkteArtikel->execute();
        $resPunkteArtikel = $stmtPunkteArtikel->get_result();
        
        while ($rowPunkteArtikel = $resPunkteArtikel->fetch_assoc()) {
          $negativerPreis = -((float)$rowPunkteArtikel['preis']); // Negativ machen
          
          $stmtInsertPunkte = $con->prepare("
            INSERT INTO bestellposition (bestellung_id, artikel_id, menge, einzelpreis)
            VALUES (?, ?, ?, ?)
          ");
          $stmtInsertPunkte->bind_param('iiid', $bestellungId, $rowPunkteArtikel['artikel_id'], $rowPunkteArtikel['menge'], $negativerPreis);
          $stmtInsertPunkte->execute();
          $stmtInsertPunkte->close();
        }
        $stmtPunkteArtikel->close();
        
        // 2c. Punkte abziehen für Punkte-Artikel im Warenkorb
        $stmtPunkte = $con->prepare("
          SELECT SUM(wp.menge * 50) as verwendete_punkte
          FROM warenkorbposition wp
          JOIN artikel a ON wp.artikel_id = a.id
          WHERE wp.warenkorb_id = ? AND LOWER(a.kategorie) = 'punkte'
        ");
        $stmtPunkte->bind_param('i', $warenkorbId);
        $stmtPunkte->execute();
        $resPunkte = $stmtPunkte->get_result();
        if ($resPunkte->num_rows > 0) {
          $rowPunkte = $resPunkte->fetch_assoc();
          $verwendetePunkte = (int)$rowPunkte['verwendete_punkte'];
          if ($verwendetePunkte > 0) {
            $stmtUpdatePunkte = $con->prepare("UPDATE punkte SET punktestand = punktestand - ? WHERE user_id = ?");
            $stmtUpdatePunkte->bind_param('ii', $verwendetePunkte, $kundenId);
            $stmtUpdatePunkte->execute();
            $stmtUpdatePunkte->close();
          }
        }
        $stmtPunkte->close();
        
        // 3. Warenkorb leeren
        $stmt4 = $con->prepare("DELETE FROM warenkorbposition WHERE warenkorb_id = ?");
        $stmt4->bind_param('i', $warenkorbId);
        $stmt4->execute();
        $stmt4->close();
        
        // 4. Zur Dankesseite weiterleiten
        header('Location: dank.php?bestellung_id=' . $bestellungId);
        exit();
      }
      $stmt->close();
    } catch (Exception $e) {
      // Fehlerbehandlung
      error_log('Bestellfehler: ' . $e->getMessage());
      header('Location: checkout.php?error=1');
      exit();
    }
  }
}

$promoMessage = '';
$promoError = '';

// Versandarten mit Kosten
$shippingMethods = [
    'lpd' => ['name' => 'LPD', 'cost' => 11.90],
    'dhl' => ['name' => 'DHL', 'cost' => 6.90],
    'dhl-express' => ['name' => 'DHL Express', 'cost' => 16.90]
];

// Rabattstaffeln berechnen basierend auf der höchsten Menge aller Artikel
function getDiscountRate($maxQuantity) {
    if ($maxQuantity >= 10) {
        return 0.10; // 10% ab Menge 10
    } elseif ($maxQuantity >= 5) {
        return 0.05; // 5% ab Menge 5
    }
    return 0.00; // Kein Rabatt
}

// Versandart aus POST oder Standard
$shippingMethod = isset($_POST['shipping_method']) ? $_POST['shipping_method'] : 'dhl';
if (!isset($shippingMethods[$shippingMethod])) {
  $shippingMethod = 'dhl';
}

// determine customer id from session
$kundenId = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;

function loadCartData(mysqli $con, int $kundenId, string $shippingMethod = 'dhl'): array {
  global $shippingMethods;
  
  $sql = "SELECT wp.*, p.name, p.preis, p.kategorie, p.rabatt FROM warenkorbposition wp
      LEFT JOIN artikel p ON wp.artikel_id = p.id
      WHERE wp.warenkorb_id = (
        SELECT id FROM warenkorbkopf WHERE kunde_id = ? LIMIT 1
      )";
  $stmt = $con->prepare($sql);
  $stmt->bind_param('i', $kundenId);
  $stmt->execute();
  $res = $stmt->get_result();

  $items = [];
  $maxQuantity = 0;
  $subtotalVorRabatt = 0;
  $promoCodeItems = []; // Promocode-Artikel separat speichern
  $inaktiveCodeArtikel = []; // Artikel mit inaktiven Gutscheincodes zum Entfernen
  
  // Erste Iteration: Alle Items laden und maximale Menge ermitteln
  while ($row = $res->fetch_assoc()) {
    $row['name'] = $row['name'] ?? 'Unbekanntes Produkt';
    $row['preis'] = $row['preis'] ?? 0.0;
    $row['menge'] = $row['menge'] ?? 0;
    $row['kategorie'] = $row['kategorie'] ?? '';
    
    // Prüfen ob es ein Promocode-Artikel oder Punkte-Artikel ist
    if ($row['kategorie'] === 'Code' || $row['kategorie'] === 'Punkte') {
      // Bei Code-Artikeln prüfen, ob Gutschein noch aktiv ist
      if ($row['kategorie'] === 'Code') {
        $stmtCheck = $con->prepare("SELECT aktiv FROM gutscheincodes WHERE gutscheinCode = ? AND aktiv = 1 LIMIT 1");
        $stmtCheck->bind_param('s', $row['name']);
        $stmtCheck->execute();
        $checkResult = $stmtCheck->get_result();
        
        if ($checkResult->num_rows === 0) {
          // Gutscheincode ist nicht mehr aktiv - zur Entfernung vormerken
          $inaktiveCodeArtikel[] = (int)$row['artikel_id'];
          $stmtCheck->close();
          continue; // Überspringen, nicht zum Warenkorb hinzufügen
        }
        $stmtCheck->close();
      }
      
      $promoCodeItems[(int)$row['artikel_id']] = $row;
    } else {
      $items[(int)$row['artikel_id']] = $row;
      $maxQuantity = max($maxQuantity, (int)$row['menge']);
      $subtotalVorRabatt += $row['preis'] * $row['menge'];
    }
  }
  
  // Inaktive Code-Artikel aus Warenkorb entfernen
  if (!empty($inaktiveCodeArtikel)) {
    $warenkorb_id_for_delete = null;
    $stmtWk = $con->prepare("SELECT id FROM warenkorbkopf WHERE kunde_id = ? LIMIT 1");
    $stmtWk->bind_param('i', $kundenId);
    $stmtWk->execute();
    $resWk = $stmtWk->get_result();
    if ($resWk->num_rows > 0) {
      $warenkorb_id_for_delete = (int)$resWk->fetch_assoc()['id'];
    }
    $stmtWk->close();
    
    if ($warenkorb_id_for_delete) {
      foreach ($inaktiveCodeArtikel as $inaktiver_artikel_id) {
        $stmtDel = $con->prepare("DELETE FROM warenkorbposition WHERE warenkorb_id = ? AND artikel_id = ?");
        $stmtDel->bind_param('ii', $warenkorb_id_for_delete, $inaktiver_artikel_id);
        $stmtDel->execute();
        $stmtDel->close();
      }
    }
  }
  
  // Rabattsatz basierend auf maximaler Menge ermitteln
  $discountRate = getDiscountRate($maxQuantity);
  
  // Zweite Iteration: Artikelrabatt und Mengenrabatt auf alle normalen Items anwenden
  $subtotal = 0;
  $totalDiscount = 0;
  
  foreach ($items as &$item) {
    // Effektiver Preis nach Artikelrabatt
    $artikelRabatt = floatval($item['rabatt'] ?? 0);
    $effektiverPreis = $item['preis'];
    
    if ($artikelRabatt > 0) {
      $effektiverPreis = $item['preis'] * (1 - $artikelRabatt / 100);
      $item['hat_artikel_rabatt'] = true;
      $item['original_preis'] = $item['preis'];
      $item['artikel_rabatt_prozent'] = $artikelRabatt;
    }
    
    $zeilensummeVorRabatt = $effektiverPreis * $item['menge'];
    $rabattBetrag = $zeilensummeVorRabatt * $discountRate;
    $item['zeilensumme'] = $zeilensummeVorRabatt - $rabattBetrag;
    $item['rabatt_prozent'] = $discountRate * 100;
    $item['rabatt_betrag'] = $rabattBetrag;
    $item['effektiver_preis'] = $effektiverPreis;
    
    $subtotal += $item['zeilensumme'];
    $totalDiscount += $rabattBetrag;
  }
  
  // Promocode-Rabatte nacheinander anwenden
  $promoCodeDiscountAmount = 0;
  foreach ($promoCodeItems as &$promo) {
    if ($promo['kategorie'] === 'Code') {
      // Prozentuale Rabatte für Promocodes
      $rabattProzent = (int)$promo['rabatt'];
      $rabattBetrag = $subtotal * ($rabattProzent / 100);
    } else {
      // Fester Betrag für Punkte-Artikel (Preis * Menge)
      $rabattBetrag = $promo['preis'] * $promo['menge'];
    }
    $promo['rabatt_betrag'] = $rabattBetrag; // Speichere Betrag für Anzeige
    $subtotal -= $rabattBetrag;
    $promoCodeDiscountAmount += $rabattBetrag;
  }
  unset($promo); // Referenz aufheben
  
  $totalDiscount += $promoCodeDiscountAmount;
  
  // Versandkosten basierend auf Versandart
  $shipping = ($subtotal > 0 && isset($shippingMethods[$shippingMethod])) 
    ? $shippingMethods[$shippingMethod]['cost'] 
    : 0;
  $total = $subtotal + $shipping;

  return [
    'items' => $items,
    'promoCodeItems' => $promoCodeItems,
    'subtotal' => $subtotal,
    'totalDiscount' => $totalDiscount,
    'promoCodeDiscountAmount' => $promoCodeDiscountAmount,
    'shipping' => $shipping,
    'total' => $total,
    'count' => count($items),
    'shippingMethod' => $shippingMethod
  ];
}

$cartData = ['items'=>[], 'promoCodeItems'=>[], 'subtotal'=>0, 'totalDiscount'=>0, 'promoCodeDiscountAmount'=>0, 'shipping'=>0, 'total'=>0, 'count'=>0, 'shippingMethod'=>'dhl'];
if ($kundenId !== null) {
  $cartData = loadCartData($con, $kundenId, $shippingMethod);
}
// use a distinct variable name to avoid collisions with included files
$cartItems = array_values($cartData['items']);
$promoCodeItems = array_values($cartData['promoCodeItems']);
$subtotal = $cartData['subtotal'];
$totalDiscount = $cartData['totalDiscount'];
$promoCodeDiscountAmount = $cartData['promoCodeDiscountAmount'];
$shipping = $cartData['shipping'];
$total = $cartData['total'];
$count = $cartData['count'];

// Kundendaten aus Datenbank laden
$userData = [
  'vorname' => '',
  'nachname' => '',
  'mail' => '',
  'adresse' => '',
  'plz' => '',
  'ort' => ''
];

if ($kundenId !== null) {
  $stmt = $con->prepare("SELECT vorname, nachname, mail, adresse, plz, ort FROM user WHERE id = ? LIMIT 1");
  $stmt->bind_param('i', $kundenId);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
  }
  $stmt->close();
}

// Punkte aus separater Tabelle laden
$verfuegbarePunkte = 0;
if ($kundenId !== null) {
  $stmt = $con->prepare("SELECT punktestand FROM punkte WHERE user_id = ? LIMIT 1");
  $stmt->bind_param('i', $kundenId);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $verfuegbarePunkte = (int)$row['punktestand'];
  }
  $stmt->close();
}
$moeglicheGutscheinMenge = floor($verfuegbarePunkte / 50);
$moeglicheRabattBetrag = $moeglicheGutscheinMenge * 0.1;

?>

<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      /* Optional: Eigene Styles hier */
      .site-max{ max-width:1400px; margin:0 auto; padding:0 1rem; }
      /* Abstand zwischen Header (sticky) und Seiteninhalt */
      main{ padding-top: 50px; }
      @media (max-width: 768px){ main{ padding-top: 24px; } }
    </style>
  </head>
  <body class="bg-light">
  <?php include "../include/headimport.php"; ?>
  <div class="site-max">
    <div class="container">
      <main>

        <?php if (isset($_GET['debug']) && $_GET['debug']): ?>
          <pre style="background:#f8f9fa;padding:12px;border:1px solid #e6e6e6;overflow:auto;"><?php var_dump($cartItems); ?></pre>
        <?php endif; ?>

        <div class="row g-5">
          <div class="col-md-5 col-lg-4 order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-primary">Ihr Warenkorb</span>
              <span class="badge bg-primary rounded-pill"><?php echo $count; ?></span>
            </h4>
            <ul class="list-group mb-3">
              <?php if (empty($cartItems)): ?>
                <li class="list-group-item">Dein Warenkorb ist leer.</li>
              <?php else: ?>
                <?php foreach ($cartItems as $item): ?>
                  <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                      <h6 class="my-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                      <small class="text-muted">Artikel-Nr.: <?php echo (int)$item['artikel_id']; ?> &middot; Menge: <?php echo (int)$item['menge']; ?></small>
                      <?php if ($item['rabatt_betrag'] > 0): ?>
                        <small class="text-danger d-block">Rabatt: -<?php echo number_format($item['rabatt_betrag'], 2, ',', '.'); ?> € (<?php echo (int)$item['rabatt_prozent']; ?>%)</small>
                      <?php endif; ?>
                    </div>
                    <span class="text-muted"><?php echo number_format($item['zeilensumme'], 2, ',', '.'); ?> €</span>
                  </li>
                <?php endforeach; ?>
                <?php if ($totalDiscount > 0): ?>
                  <li class="list-group-item d-flex justify-content-between bg-light text-danger">
                    <span>Mengenrabatt</span>
                    <strong>-<?php echo number_format($totalDiscount - $promoCodeDiscountAmount, 2, ',', '.'); ?> €</strong>
                  </li>
                <?php endif; ?>
                <?php if ($promoCodeDiscountAmount > 0): ?>
                  <?php foreach ($promoCodeItems as $promo): ?>
                    <li class="list-group-item d-flex justify-content-between bg-success text-white">
                      <div>
                        <h6 class="my-0"><?php echo htmlspecialchars($promo['name']); ?></h6>
                        <?php if ($promo['kategorie'] === 'Code'): ?>
                          <small>Promocode-Rabatt: <?php echo (int)$promo['rabatt']; ?>%</small>
                        <?php else: ?>
                          <small>Punkte-Rabatt: <?php echo (int)$promo['menge']; ?> x 0,10 €</small>
                        <?php endif; ?>
                      </div>
                      <strong>-<?php echo number_format($promo['rabatt_betrag'], 2, ',', '.'); ?> €</strong>
                    </li>
                  <?php endforeach; ?>
                <?php endif; ?>
                <li class="list-group-item d-flex justify-content-between bg-light">
                  <div class="text-success">
                    <h6 class="my-0">Versand</h6>
                    <small>Standard</small>
                  </div>
                  <span class="text-success"><?php echo ($shipping > 0) ? number_format($shipping, 2, ',', '.') . ' €' : 'Gratis'; ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span>Gesamt</span>
                  <strong><?php echo number_format($total, 2, ',', '.'); ?> €</strong>
                </li>
              <?php endif; ?>
            </ul>

            <?php if (!empty($promoMessage)): ?>
              <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($promoMessage); ?></div>
            <?php endif; ?>
            <?php if (!empty($promoError)): ?>
              <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($promoError); ?></div>
            <?php endif; ?>

            <form class="card p-2" method="POST" id="promoForm">
              <div class="input-group">
                <input type="text" name="promo_code" class="form-control" placeholder="Gutscheincode" required>
                <button type="submit" class="btn btn-secondary">Einlösen</button>
              </div>
              <div id="promoMessage" class="mt-2" style="display: none;"></div>
            </form>

            <?php if ($verfuegbarePunkte >= 50): ?>
              <div class="card p-3 mt-3 bg-warning bg-opacity-10">
                <h6 class="mb-2">Punkte einlösen</h6>
                <p class="mb-2 small">
                  Sie haben <strong><?php echo $verfuegbarePunkte; ?> Punkte</strong><br>
                  Einlösbar: <strong><?php echo number_format($moeglicheRabattBetrag, 2, ',', '.'); ?> €</strong> Rabatt
                  <small class="text-muted">(<?php echo $moeglicheGutscheinMenge * 50; ?> Punkte)</small>
                </p>
                <form method="POST" id="pointsForm">
                  <button type="submit" class="btn btn-warning w-100">Alle Punkte einlösen</button>
                </form>
                <div id="pointsMessage" class="mt-2" style="display: none;"></div>
              </div>
            <?php elseif ($verfuegbarePunkte > 0): ?>
              <div class="card p-3 mt-3 bg-light">
                <h6 class="mb-1">Ihre Punkte</h6>
                <p class="mb-0 small text-muted">
                  Sie haben <strong><?php echo $verfuegbarePunkte; ?> Punkte</strong><br>
                  <small>Ab 50 Punkten können Sie diese einlösen (je 50 Punkte = 0,10 € Rabatt)</small>
                </p>
              </div>
            <?php endif; ?>
          </div>
          <div class="col-md-7 col-lg-8">
            <h4 class="mb-3">Rechnungsadresse & Lieferadresse</h4>
            <form class="needs-validation" method="POST" novalidate>
              <div class="row g-3">
                <div class="col-sm-6">
                  <label for="firstName" class="form-label">Vorname</label>
                  <input type="text" class="form-control" id="firstName" name="firstName" placeholder="" value="<?php echo htmlspecialchars($userData['vorname'] ?? ''); ?>" required readonly>
                  <div class="invalid-feedback">
                    Vorname ist erforderlich.
                  </div>
                </div>

                <div class="col-sm-6">
                  <label for="lastName" class="form-label">Nachname</label>
                  <input type="text" class="form-control" id="lastName" name="lastName" placeholder="" value="<?php echo htmlspecialchars($userData['nachname'] ?? ''); ?>" required readonly>
                  <div class="invalid-feedback">
                    Nachname ist erforderlich.
                  </div>
                </div>

                <div class="col-12">
                  <label for="email" class="form-label">E-Mail</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="ihre@email.de" value="<?php echo htmlspecialchars($userData['mail'] ?? ''); ?>" required readonly>
                  <div class="invalid-feedback">
                    Bitte geben Sie eine gültige E-Mail-Adresse ein.
                  </div>
                </div>

                <div class="col-12">
                  <label for="address" class="form-label">Adresse</label>
                  <input type="text" class="form-control" id="address" name="address" placeholder="Musterstraße 1" value="<?php echo htmlspecialchars($userData['adresse'] ?? ''); ?>" required readonly>
                  <div class="invalid-feedback">
                    Bitte geben Sie Ihre Adresse ein.
                  </div>
                </div>

                <div class="col-12">
                  <label for="address2" class="form-label">Adresszusatz <span class="text-muted">(Optional)</span></label>
                  <input type="text" class="form-control" id="address2" name="address2" placeholder="Wohnung, Stockwerk, etc.">
                </div>

                <div class="col-md-4">
                  <label for="zip" class="form-label">PLZ</label>
                  <input type="text" class="form-control" id="zip" name="zip" placeholder="" value="<?php echo htmlspecialchars($userData['plz'] ?? ''); ?>" required readonly>
                  <div class="invalid-feedback">
                    PLZ ist erforderlich.
                  </div>
                </div>

                <div class="col-md-8">
                  <label for="city" class="form-label">Ort</label>
                  <input type="text" class="form-control" id="city" name="city" placeholder="" value="<?php echo htmlspecialchars($userData['ort'] ?? ''); ?>" required readonly>
                  <div class="invalid-feedback">
                    Ort ist erforderlich.
                  </div>
                </div>
              </div>

              <hr class="my-4">

              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="privacy-policy" required>
                <label class="form-check-label" for="privacy-policy">
                  Ich akzeptiere die Datenschutzerklärung und Allgemeinen Geschäftsbedingungen
                </label>
                <div class="invalid-feedback">
                  Sie müssen den Datenschutzbedingungen zustimmen.
                </div>
              </div>

              <hr class="my-4">

              <input type="hidden" name="shipping_method" value="<?php echo htmlspecialchars($shippingMethod); ?>">
              <button class="w-100 btn btn-primary btn-lg" type="submit">Bestellung abschließen</button>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
  <?php include "../include/footimport.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Bootstrap form validation
      (function () {
        'use strict';
        window.addEventListener('load', function () {
          var forms = document.querySelectorAll('.needs-validation');
          Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
              if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        });
      })();
      
      // Promocode-Formular Handler
      document.addEventListener('DOMContentLoaded', function() {
        const promoForm = document.getElementById('promoForm');
        const promoMessage = document.getElementById('promoMessage');
        
        if (promoForm) {
          promoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(promoForm);
            formData.append('ajax', '1');
            
            fetch(window.location.pathname, {
              method: 'POST',
              body: formData
            })
            .then(response => {
              if (!response.ok) {
                return response.text().then(text => {
                  console.error('Server Error:', text);
                  throw new Error('Server-Fehler (Status: ' + response.status + ')');
                });
              }
              return response.json();
            })
            .then(data => {
              if (data.success) {
                promoMessage.textContent = data.message;
                promoMessage.className = 'mt-2 text-success fw-bold';
                promoMessage.style.display = 'block';
                
                // Seite neu laden wenn reload: true
                if (data.reload) {
                  setTimeout(function() {
                    window.location.reload();
                  }, 1000);
                }
              } else {
                promoMessage.textContent = data.message;
                promoMessage.className = 'mt-2 text-danger fw-bold';
                promoMessage.style.display = 'block';
              }
            })
            .catch(error => {
              console.error('Error:', error);
              promoMessage.textContent = 'Fehler: ' + error.message;
              promoMessage.className = 'mt-2 text-danger fw-bold';
              promoMessage.style.display = 'block';
            });
          });
        }
        
        // Punkte-Formular Handler
        const pointsForm = document.getElementById('pointsForm');
        const pointsMessage = document.getElementById('pointsMessage');
        
        if (pointsForm) {
          pointsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('redeem_points', '1');
            formData.append('ajax', '1');
            
            fetch(window.location.pathname, {
              method: 'POST',
              body: formData
            })
            .then(response => {
              if (!response.ok) {
                return response.text().then(text => {
                  console.error('Server Error:', text);
                  throw new Error('Server-Fehler (Status: ' + response.status + ')');
                });
              }
              return response.json();
            })
            .then(data => {
              if (data.success) {
                pointsMessage.textContent = data.message;
                pointsMessage.className = 'mt-2 text-success fw-bold';
                pointsMessage.style.display = 'block';
                
                // Seite neu laden wenn reload: true
                if (data.reload) {
                  setTimeout(function() {
                    window.location.reload();
                  }, 1000);
                }
              } else {
                pointsMessage.textContent = data.message;
                pointsMessage.className = 'mt-2 text-danger fw-bold';
                pointsMessage.style.display = 'block';
              }
            })
            .catch(error => {
              console.error('Error:', error);
              pointsMessage.textContent = 'Fehler: ' + error.message;
              pointsMessage.className = 'mt-2 text-danger fw-bold';
              pointsMessage.style.display = 'block';
            });
          });
        }
      });
    </script>
  </body>
</html>