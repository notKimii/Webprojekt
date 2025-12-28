<?php 
// include "include/loginpruef.php";
session_start(); // Session muss gestartet sein, falls nicht schon in headimport passiert
?>

<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../include/connectcon.php";

// --- NEU: LÖSCH-LOGIK START ---
// Prüfen, ob der Löschen-Button gedrückt wurde
if (isset($_POST['remove_item']) && isset($_POST['delete_artikel_id'])) {
    $del_artikel_id = (int)$_POST['delete_artikel_id'];
    $del_kunden_id = isset($_SESSION['temp_user']['id']) ? $_SESSION['temp_user']['id'] : null;

    if ($del_kunden_id) {
        // Wir löschen nur, wenn die Position auch wirklich zum Warenkorb des eingeloggten Users gehört
        // Das verhindert, dass man fremde Warenkörbe manipuliert
        $deleteSql = "DELETE wp FROM warenkorbposition wp
                      INNER JOIN warenkorbkopf wk ON wp.warenkorb_id = wk.id
                      WHERE wk.kunde_id = ? AND wp.artikel_id = ?";
        
        if ($delStmt = $con->prepare($deleteSql)) {
            $delStmt->bind_param('ii', $del_kunden_id, $del_artikel_id);
            $delStmt->execute();
            $delStmt->close();
            
            // Optional: Seite neu laden, um Formular-Resubmission zu verhindern
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}
// --- NEU: LÖSCH-LOGIK ENDE ---
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Warenkorb - Mein Shop</title>
    <link rel="stylesheet" href="/Webprojekt/style.css">
    <link rel="stylesheet" href="warenkorb.css">
    <?php include "../include/headimport.php"; ?> 
    
    <style>
        .btn-remove {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 0.9em;
            margin-top: 10px;
        }
        .btn-remove:hover {
            background-color: #cc0000;
        }
        .cart-item-actions {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <main>
        <?php
        $kundenId = isset($_SESSION['temp_user']['id']) ? $_SESSION['temp_user']['id'] : null;
        if ($kundenId === null) {
            echo '<p>Fehler: Kein Kunde angemeldet.</p>';
            exit;
        }
        // JOIN hinzugefügt, um 'artikelname' zu laden. LIMIT 1 für Sicherheit im Subselect.
        $sql = "SELECT wp.*, p.name, p.preis FROM warenkorbposition wp 
                LEFT JOIN artikel p ON wp.artikel_id = p.id
                WHERE wp.warenkorb_id = (
                    SELECT id FROM warenkorbkopf WHERE kunde_id = ? LIMIT 1
                )";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('i', $kundenId);
        $stmt->execute();
        $res = $stmt->get_result();
        $result = [];
        while ($row = $res->fetch_assoc()) {
            $result[] = $row;
        }
        ?>
        <section class="cart-section">
            <div class="container">
                <h1>Dein Warenkorb</h1>
                <div class="cart-items-list">
                    <?php if (empty($result)): ?>
                        <p>Dein Warenkorb ist leer.</p>
                    <?php else: ?>
                        <?php foreach ($result as $position): ?>
                            <?php 
                            // Bild-Pfad erstellen
                            $produktId = $position['artikel_id'];
                            $bildOrdner = "/Webprojekt/images/pictures/productids/" . $produktId . "/";
                            $standardBild = $bildOrdner . "main.jpg"; 
                            
                            // Prüfen ob Verzeichnis existiert und erstes Bild finden
                            $bildPfad = $standardBild;
                            $absoluterPfad = $_SERVER['DOCUMENT_ROOT'] . $bildOrdner;
                            if (is_dir($absoluterPfad)) {
                                $bilder = array_diff(scandir($absoluterPfad), array('.', '..'));
                                if (!empty($bilder)) {
                                    $erstesBild = reset($bilder);
                                    $bildPfad = $bildOrdner . $erstesBild;
                                }
                            }
                            ?>
                            <div class="cart-item">
                                <img src="<?php echo htmlspecialchars($bildPfad); ?>" 
                                     alt="<?php echo htmlspecialchars($position['name']); ?>" 
                                     class="cart-item-img"
                                     onerror="this.src='/Webprojekt/images/pictures/placeholder.jpg'">
                                <div class="cart-item-details">
                                    <h2><?php echo htmlspecialchars($position['name']); ?></h2>
                                    <p>Menge: <?php echo htmlspecialchars($position['menge']); ?></p>
                                    <p>Preis einzeln: <?php echo htmlspecialchars($position['preis']); ?> €</p>
                                    
                                    <div class="cart-item-actions">
                                        <form method="post" action="">
                                            <input type="hidden" name="delete_artikel_id" value="<?php echo $position['artikel_id']; ?>">
                                            <button type="submit" name="remove_item" class="btn-remove">🗑️ Entfernen</button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <div class="cart-summary">
                    <div class="cart-summary-row">
                        <span>Zwischensumme</span>
                        <span class="positionSum">0,00 €</span>
                    </div>
                    <div class="cart-summary-row">
                        <span>Versand</span>
                        <span class="shippingCost">0,00 €</span>
                    </div>
                    <div class="cart-summary-row cart-summary-total">
                        <strong>Gesamt</strong>
                        <strong class="totalSum">0,00 €</strong>
                    </div>
                    <?php if (!empty($result)): ?>
                        <form action="bestellung_abschliessen.php" method="post">
                            <button type="submit">✅ Jetzt kaufen</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
    <?php include "../include/footimport.php"; ?>
</body>
</html>

<script>
    let positionSum = 0;
    let shippingCost = 4.99;
    
    // PHP Daten an JS übergeben
    <?php if(!empty($result)): ?>
        <?php foreach ($result as $position): ?>
            positionSum += <?php echo $position['preis'] * $position['menge']; ?>;
        <?php endforeach; ?>
    <?php else: ?>
        shippingCost = 0; // Kein Versand bei leerem Warenkorb
    <?php endif; ?>

    document.querySelector(".positionSum").textContent = positionSum.toFixed(2) + " €";
    
    if(positionSum === 0) {
        shippingCost = 0;
    }
    
    document.querySelector(".shippingCost").textContent = shippingCost.toFixed(2) + " €";
    
    let totalSum = positionSum + shippingCost;
    document.querySelector(".totalSum").textContent = totalSum.toFixed(2) + " €";
</script>