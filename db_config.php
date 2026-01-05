<?php
// 1. DATENBANK-ZUGANGSDATEN
$db_host = 'localhost';
$db_name = 'dbpilotenshop'; 
$db_user = 'root';
$db_pass = ''; 

// 2. MYSQLI-VERBINDUNGSAUFBAU
// Wir unterdrücken Fehlermeldungen mit @ oder nutzen try-catch für ein sauberes Design
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $mysqli->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    die("ZURZEIT NICHT ERREICHBAR: Bitte versuchen Sie es später erneut.");
}

// KEIN echo und KEIN SELECT mehr hier! 
// Die Datei stellt jetzt nur noch das Objekt $mysqli bereit.
?>