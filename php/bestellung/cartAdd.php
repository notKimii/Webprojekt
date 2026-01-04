<?php
// Prüfe zuerst ob AJAX-Request - KEINE Includes oder Output vorher bei AJAX
$isAjax = isset($_POST['ajax']);

if ($isAjax) {
    header('Content-Type: application/json');
}

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

///Fixe DB connection
include __DIR__ . '/../include/connectcon.php';

///Prüfung ob User angemeldet ist
$kundenId = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
if ($kundenId === null) {
    if ($isAjax) {
        echo json_encode(['success' => false, 'error' => 'not_logged_in']);
        exit;
    }
    header('Location: /Webprojekt/php/login/loginformular.php');
    exit;
}

// Prüfung der Eingabe
$produktId = isset($_POST['produkt_id']) ? (int)$_POST['produkt_id'] : 0;
$menge     = isset($_POST['anzahl']) ? (int)$_POST['anzahl'] : 0;
if ($produktId <= 0 || $menge <= 0) {
    if ($isAjax) {
        echo json_encode(['success' => false, 'error' => 'invalid_input']);
        exit;
    }
    header('Location: /Webprojekt/php/produkt-detail.php?error=invalid_input');
    exit;
}



// Prüfung ob Produkt in db existiert
$stmt = $con->prepare('SELECT id FROM artikel WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $produktId);
$stmt->execute();
$stmt->bind_result($prodExistsId);
if (!$stmt->fetch()) {
    $stmt->close();
    if ($isAjax) {
        echo json_encode(['success' => false, 'error' => 'unknown_product']);
        exit;
    }
    header('Location: /Webprojekt/php/produkt-detail.php?error=unknown_product');
    exit;
}
$stmt->close();

// Prüfe ob Warenkorb schon vorhanden
$cartId = null;
$stmt = $con->prepare('SELECT id FROM warenkorbkopf WHERE kunde_id = ? LIMIT 1');
$stmt->bind_param('i', $kundenId);
$stmt->execute();
$stmt->bind_result($foundId);
if ($stmt->fetch()) {
    $cartId = (int)$foundId;
}
$stmt->close();

// Erstelle Warenkorb falls nicht vorhanden 
if ($cartId === null) {
    $stmt = $con->prepare('INSERT INTO warenkorbkopf (kunde_id) VALUES (?)');
    $stmt->bind_param('i', $kundenId);
    if (!$stmt->execute()) {
        $stmt->close();
        if ($isAjax) {
            echo json_encode(['success' => false, 'error' => 'cart_create_failed']);
            exit;
        }
        header('Location: /Webprojekt/php/produkt-detail.php?error=cart_create_failed');
        exit;
    }
    $cartId = $con->insert_id;
    $stmt->close();
}

// Wenn Position schon vorhanden, dann Menge anpassen, sonst neue Position anlegen
$stmt = $con->prepare('INSERT INTO warenkorbposition (warenkorb_id, artikel_id, menge) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE menge = VALUES(menge)');
$stmt->bind_param('iii', $cartId, $produktId, $menge);
$ok = $stmt->execute();
$stmt->close();

if (!$ok) {
    if ($isAjax) {
        echo json_encode(['success' => false, 'error' => 'position_upsert_failed']);
        exit;
    }
    header('Location: /Webprojekt/php/produkt-detail.php?error=position_upsert_failed');
    exit;
}

if ($isAjax) {
    // Get current cart count
    $countStmt = $con->prepare('SELECT COUNT(wp.artikel_id) AS cnt FROM warenkorbkopf wk LEFT JOIN warenkorbposition wp ON wp.warenkorb_id = wk.id WHERE wk.kunde_id = ?');
    $countStmt->bind_param('i', $kundenId);
    $countStmt->execute();
    $countStmt->bind_result($cartCount);
    $countStmt->fetch();
    $countStmt->close();
    
    echo json_encode(['success' => true, 'cart_id' => $cartId, 'count' => (int)($cartCount ?? 0)]);
    exit;
}

header('Location: /Webprojekt/php/bestellung/warenkorb.php');
exit;
