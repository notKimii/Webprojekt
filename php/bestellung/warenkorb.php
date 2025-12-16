<?php 
// include "include/loginpruef.php"   
?>

<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../include/connectcon.php";
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Warenkorb - Mein Shop</title>
    <link rel="stylesheet" href="/Webprojekt/style.css">
    <link rel="stylesheet" href="warenkorb.css">
    <?php include "../include/headimport.php"; ?> 
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
                    SELECT id FROM warenkorbkopf WHERE kunde_id = ? AND aktiv = true LIMIT 1
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
                            $standardBild = $bildOrdner . "main.jpg"; // oder ein anderer Standard-Bildname
                            
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
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <!-- Cart Summary -->
                <div class="cart-summary">
                    <div class="cart-summary-row">
                        <span>Zwischensumme</span>
                        <span class="positionSum">99,98 €</span>
                    </div>
                    <div class="cart-summary-row">
                        <span>Versand</span>
                        <span class="shippingCost">4,99 €</span>
                    </div>
                    <div class="cart-summary-row cart-summary-total">
                        <strong>Gesamt</strong>
                        <strong class="totalSum">104,97 €</strong>
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
    <?php foreach ($result as $position): ?>
        positionSum += <?php echo $position['preis'] * $position['menge']; ?>;
    <?php endforeach; ?>
    document.querySelector(".positionSum").textContent = positionSum.toFixed(2) + " €";
    if(positionSum === 0) {
        shippingCost = 0;
    }
    document.querySelector(".shippingCost").textContent = shippingCost.toFixed(2) + " €";
    let totalSum = positionSum + shippingCost;
    document.querySelector(".totalSum").textContent = totalSum.toFixed(2) + " €";
</script>
