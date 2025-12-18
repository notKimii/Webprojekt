<?php
// C:\xampp\htdocs\Webprojekt\php\suche_live.php

include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/connect.php';

header('Content-Type: application/json');

$input = isset($_GET['term']) ? trim($_GET['term']) : '';

// Erst suchen, wenn mindestens 1 Zeichen da ist
if (strlen($input) > 0) {
    try {
        // Wir suchen im Namen (wir limitieren auf 5 Ergebnisse, damit die Liste nicht riesig wird)
        $stmt = $conPDO->prepare("SELECT id, name, preis FROM artikel WHERE name LIKE :term LIMIT 5");
        $stmt->execute(['term' => '%' . $input . '%']);
        
        $ergebnisse = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // JSON zurückgeben
        echo json_encode($ergebnisse);
    } catch (Exception $e) {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>