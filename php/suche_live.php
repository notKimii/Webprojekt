<?php
// C:\xampp\htdocs\Webprojekt\php\suche_live.php

include $_SERVER['DOCUMENT_ROOT'] . '/Webprojekt/php/include/connect.php';

header('Content-Type: application/json');

$input = isset($_GET['term']) ? trim($_GET['term']) : '';

// Erst suchen, wenn mindestens 1 Zeichen da ist
if (strlen($input) > 0) {
    try {
        // SQL angepasst: 'AND id > 99' hinzugefügt, um Gutscheine (1-99) auszublenden
        $sql = "SELECT id, name, preis 
                FROM artikel 
                WHERE name LIKE :term 
                AND id > 99 
                AND kategorie IS NOT NULL 
                AND kategorie != '' 
                AND kategorie != 'Code' 
                LIMIT 5";

        $stmt = $conPDO->prepare($sql);
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