<?php
session_start();
include "../include/connect.php"; // Pfad zur connect.php anpassen!

// Nur POST-Anfragen erlauben
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    
    // 1. Login Check
    if (!isset($_SESSION['temp_user']['id'])) {
        header("Location: /Webprojekt/php/login/loginformular.php");
        exit;
    }

    $userId = $_SESSION['temp_user']['id'];
    $produktId = intval($_POST['produkt_id']);
    $rating = intval($_POST['rating']);
    $kommentar = trim($_POST['kommentar']);

    // 2. Validierung
    if ($rating < 1 || $rating > 5) {
        // Fehler zurückgeben
        header("Location: /Webprojekt/php/produkt-detail.php?id=$produktId&error=invalid_rating");
        exit;
    }

    // 3. Prüfen auf Doppelte
    $checkStmt = $conPDO->prepare("SELECT COUNT(*) FROM bewertungen WHERE user_id = ? AND artikel_id = ?");
    $checkStmt->execute([$userId, $produktId]);
    
    if ($checkStmt->fetchColumn() > 0) {
        header("Location: /Webprojekt/php/produkt-detail.php?id=$produktId&error=already_reviewed");
        exit;
    }

    // 4. Speichern
    try {
        $stmt = $conPDO->prepare("INSERT INTO bewertungen (user_id, artikel_id, wert, kommentar) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $produktId, $rating, $kommentar]);

        // Erfolg! Zurück zum Produkt
        header("Location: /Webprojekt/php/produkt-detail.php?id=$produktId&status=success#reviews-section");
        exit;

    } catch (Exception $e) {
        // DB Fehler
        header("Location: /Webprojekt/php/produkt-detail.php?id=$produktId&error=db_error");
        exit;
    }
} else {
    // Wenn jemand die Datei direkt aufruft, wegwerfen
    header("Location: /Webprojekt/index.php");
    exit;
}
?>