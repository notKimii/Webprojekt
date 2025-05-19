<?php

// --------------------------------------------------------------------------------
// 1. DATENBANK-ZUGANGSDATEN: Bitte hier Ihre eigenen Daten eintragen!
// --------------------------------------------------------------------------------
$db_host = 'localhost';          // Oft 'localhost', oder was Ihr Hoster angibt
$db_name = 'cockpit corner';    // Der Name Ihrer Datenbank
$db_user = 'root';       // Ihr Datenbank-Benutzername
$db_pass = ''; // Ihr Datenbank-Passwort

// --------------------------------------------------------------------------------
// 2. MYSQLI-VERBINDUNGSAUFBAU (objektorientiert)
// --------------------------------------------------------------------------------
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verbindung prüfen
if ($mysqli->connect_error) {
    // Im Fehlerfall: Detaillierte Fehlermeldung nicht im Produktivbetrieb anzeigen!
    // Stattdessen Fehler loggen und eine allgemeine Meldung ausgeben.
    error_log("MySQLi Verbindungsfehler: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    die("FEHLER: Es konnte keine Verbindung zur Datenbank hergestellt werden. Bitte versuchen Sie es später erneut.");
}

// Zeichensatz für die Verbindung setzen (wichtig!)
if (!$mysqli->set_charset("utf8mb4")) {
    error_log("Fehler beim Laden des Zeichensatzes utf8mb4: " . $mysqli->error);
    // Fahren Sie fort, aber seien Sie sich bewusst, dass es Probleme geben könnte
}

echo "Erfolgreich mit der Datenbank '$db_name' verbunden!<br><br>";

// --------------------------------------------------------------------------------
// 3. BEISPIEL: DATEN AUS DER TABELLE 'produkte' AUSLESEN
// --------------------------------------------------------------------------------
echo "Produkte aus der Datenbank:<br>";
$sql = "SELECT id, name, preis FROM produkte";
$result = $mysqli->query($sql);

// Prüfen, ob Ergebnisse vorhanden sind
if ($result && $result->num_rows > 0) {
    echo "<ul>";
    // Daten aus jeder Zeile ausgeben
    while ($row = $result->fetch_assoc()) {
        echo "<li>ID: " . htmlspecialchars($row['id']) .
             " - Name: " . htmlspecialchars($row['name']) .
             " - Preis: " . htmlspecialchars($row['preis']) . " €</li>";
    }
    echo "</ul>";
    $result->free(); // Ergebnisobjekt freigeben
} else {
    echo "Keine Produkte in der Datenbank gefunden oder Fehler bei der Abfrage: " . $mysqli->error;
}

// --------------------------------------------------------------------------------
// 4. BEISPIEL: DATEN EINFÜGEN MIT PREPARED STATEMENTS (SICHER!)
// --------------------------------------------------------------------------------
/* // Kommentar entfernen, um Testdaten einzufügen
echo "<br>Füge neues Produkt hinzu...<br>";
$neuer_name = "Noch ein Artikel";
$neuer_preis = 33.90;

$sql_insert = "INSERT INTO produkte (name, preis) VALUES (?, ?)"; // Platzhalter ?
$stmt_insert = $mysqli->prepare($sql_insert);

if ($stmt_insert) {
    // Werte an die Platzhalter binden (s für String, d für Double/Decimal)
    $stmt_insert->bind_param("sd", $neuer_name, $neuer_preis);

    if ($stmt_insert->execute()) {
        $last_id = $mysqli->insert_id;
        echo "Neues Produkt erfolgreich mit ID " . $last_id . " eingefügt!<br>";
    } else {
        echo "Fehler beim Ausführen des Statements: " . $stmt_insert->error . "<br>";
    }
    $stmt_insert->close(); // Statement schließen
} else {
    echo "Fehler beim Vorbereiten des Statements: " . $mysqli->error . "<br>";
}
*/

// --------------------------------------------------------------------------------
// 5. VERBINDUNG SCHLIESSEN
// --------------------------------------------------------------------------------
$mysqli->close();

?>