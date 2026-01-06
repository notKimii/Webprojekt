<?php
session_start();
include '../include/connectcon.php';

// Überprüfung ob Benutzer angemeldet ist
if (!isset($_SESSION['user']['id'])) {
    $_SESSION['error_message'] = 'Sie müssen angemeldet sein.';
    header('Location: /Webprojekt/php/kundenkonto.php');
    exit();
}

// Prüfen ob Reorder-Daten vorhanden sind
if (!isset($_SESSION['reorder_data'])) {
    $_SESSION['error_message'] = 'Keine Bestelldaten gefunden.';
    header('Location: /Webprojekt/php/kundenkonto.php');
    exit();
}

$data = $_SESSION['reorder_data'];
$bestellungId = $data['bestellung_id'];
$normaleArtikel = $data['normale_artikel'];
$rabattArtikel = $data['rabatt_artikel'];
$nichtVerfuegbarNormal = $data['nicht_verfuegbar_normal'];
$nichtVerfuegbarRabatt = $data['nicht_verfuegbar_rabatt'];
$versandartId = $data['versandart_id'];
$versandkosten = $data['versandkosten'];

$versandarten = [
    1 => 'LPD',
    2 => 'DHL',
    3 => 'DHL Express'
];
$versandartName = $versandarten[$versandartId] ?? 'DHL';

// Gesamtbetrag berechnen
$subtotal = 0;
foreach ($normaleArtikel as $artikel) {
    $subtotal += $artikel['menge'] * $artikel['preis'];
}

$gesamtbetrag = $subtotal;

// Rabatte berechnen
foreach ($rabattArtikel as $rabatt) {
    if ($rabatt['artikel_id'] == 1) {
        // Punkte
        $gesamtbetrag += $rabatt['menge'] * $rabatt['preis'];
    } elseif ($rabatt['rabatt'] > 0) {
        // Prozent-Rabatt
        $rabattBetrag = $subtotal * ($rabatt['rabatt'] / 100);
        $gesamtbetrag -= $rabattBetrag;
    }
}

$gesamtbetrag += $versandkosten;
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellung bestätigen - Cockpit Corner</title>
    <link rel="stylesheet" href="/Webprojekt/style.css">
    <style>
        .confirm-container {
            max-width: 800px;
            margin: 0 auto;
            padding: calc(var(--spacing-unit) * 3) calc(var(--spacing-unit) * 1.5);
        }

        .confirm-card {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: calc(var(--spacing-unit) * 3);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
        }

        .confirm-header {
            text-align: center;
            margin-bottom: calc(var(--spacing-unit) * 3);
        }

        .confirm-header h1 {
            color: var(--primary-color);
            margin-bottom: calc(var(--spacing-unit));
        }

        .warning-box {
            background: #fef3c7;
            border: 2px solid #d97706;
            border-radius: var(--border-radius);
            padding: calc(var(--spacing-unit) * 1.5);
            margin-bottom: calc(var(--spacing-unit) * 2);
        }

        .warning-box h3 {
            color: #d97706;
            margin-top: 0;
            margin-bottom: calc(var(--spacing-unit));
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .warning-list {
            margin: calc(var(--spacing-unit)) 0;
            padding-left: calc(var(--spacing-unit) * 2);
        }

        .warning-list li {
            margin-bottom: 6px;
            color: #92400e;
        }

        .available-box {
            background: #d1fae5;
            border: 2px solid #059669;
            border-radius: var(--border-radius);
            padding: calc(var(--spacing-unit) * 1.5);
            margin-bottom: calc(var(--spacing-unit) * 2);
        }

        .available-box h3 {
            color: #059669;
            margin-top: 0;
            margin-bottom: calc(var(--spacing-unit));
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .article-list {
            list-style: none;
            padding: 0;
            margin: calc(var(--spacing-unit)) 0;
        }

        .article-item {
            display: flex;
            justify-content: space-between;
            padding: calc(var(--spacing-unit) * 0.75);
            border-bottom: 1px solid var(--border-color);
            align-items: center;
        }

        .article-item:last-child {
            border-bottom: none;
        }

        .article-name {
            flex: 1;
            font-weight: 500;
        }

        .article-qty {
            color: var(--text-light);
            margin: 0 calc(var(--spacing-unit));
        }

        .article-price {
            font-weight: 600;
            color: var(--primary-color);
        }

        .total-section {
            border-top: 2px solid var(--border-color);
            padding-top: calc(var(--spacing-unit) * 1.5);
            margin-top: calc(var(--spacing-unit) * 2);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: calc(var(--spacing-unit) * 0.5) 0;
        }

        .total-row.final {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            padding-top: calc(var(--spacing-unit));
        }

        .button-container {
            display: flex;
            gap: calc(var(--spacing-unit));
            margin-top: calc(var(--spacing-unit) * 2);
        }

        .btn-confirm, .btn-cancel {
            flex: 1;
            padding: 16px 24px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-confirm {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-cancel {
            background: white;
            color: #ef4444;
            border: 2px solid #ef4444;
        }

        .btn-cancel:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<?php include '../include/headimport.php'; ?>
    <main>
        <div class="confirm-container">
            <div class="confirm-card">
                <div class="confirm-header">
                    <h1>⚠️ Bestellung bestätigen</h1>
                    <p>Einige Artikel aus der ursprünglichen Bestellung sind nicht mehr verfügbar.</p>
                </div>

                <?php if (count($nichtVerfuegbarNormal) > 0 || count($nichtVerfuegbarRabatt) > 0): ?>
                    <div class="warning-box">
                        <h3>❌ Nicht verfügbare Artikel:</h3>
                        <ul class="warning-list">
                            <?php foreach ($nichtVerfuegbarNormal as $artikel): ?>
                                <li><?php echo htmlspecialchars($artikel); ?></li>
                            <?php endforeach; ?>
                            <?php foreach ($nichtVerfuegbarRabatt as $rabatt): ?>
                                <li><?php echo htmlspecialchars($rabatt); ?> (Rabatt nicht mehr gültig)</li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="available-box">
                    <h3>✅ Verfügbare Artikel, die bestellt werden:</h3>
                    <ul class="article-list">
                        <?php foreach ($normaleArtikel as $artikel): ?>
                            <li class="article-item">
                                <span class="article-name"><?php echo htmlspecialchars($artikel['name']); ?></span>
                                <span class="article-qty">Menge: <?php echo $artikel['menge']; ?></span>
                                <span class="article-price"><?php echo number_format($artikel['menge'] * $artikel['preis'], 2, ',', '.'); ?> €</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <?php if (count($rabattArtikel) > 0): ?>
                        <h4 style="margin-top: 20px; margin-bottom: 10px; color: #059669;">Aktive Rabatte:</h4>
                        <ul class="article-list">
                            <?php foreach ($rabattArtikel as $rabatt): ?>
                                <li class="article-item">
                                    <span class="article-name"><?php echo htmlspecialchars($rabatt['name']); ?></span>
                                    <span class="article-qty">
                                        <?php 
                                        if ($rabatt['rabatt'] > 0) {
                                            echo $rabatt['rabatt'] . '% Rabatt';
                                        } else {
                                            echo 'Punkte';
                                        }
                                        ?>
                                    </span>
                                    <span class="article-price" style="color: #059669;">
                                        <?php 
                                        if ($rabatt['artikel_id'] == 1) {
                                            echo number_format($rabatt['menge'] * $rabatt['preis'], 2, ',', '.') . ' €';
                                        } else {
                                            $rabattBetrag = $subtotal * ($rabatt['rabatt'] / 100);
                                            echo '-' . number_format($rabattBetrag, 2, ',', '.') . ' €';
                                        }
                                        ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="total-section">
                        <div class="total-row">
                            <span>Zwischensumme:</span>
                            <span><?php echo number_format($subtotal, 2, ',', '.'); ?> €</span>
                        </div>
                        <div class="total-row">
                            <span>Versandkosten (<?php echo htmlspecialchars($versandartName); ?>):</span>
                            <span><?php echo number_format($versandkosten, 2, ',', '.'); ?> €</span>
                        </div>
                        <div class="total-row final">
                            <span>Gesamtbetrag:</span>
                            <span><?php echo number_format($gesamtbetrag, 2, ',', '.'); ?> €</span>
                        </div>
                    </div>
                </div>

                <p style="text-align: center; color: var(--text-light); margin-bottom: 20px;">
                    Möchten Sie diese Bestellung mit den verfügbaren Artikeln aufgeben?
                </p>

                <div class="button-container">
                    <a href="erneut_bestellen.php?bestellung_id=<?php echo $bestellungId; ?>&bestaetigt=1" class="btn-confirm">
                        ✓ Ja, Bestellung aufgeben
                    </a>
                    <a href="/Webprojekt/php/kundenkonto.php" class="btn-cancel">
                        ✗ Nein, abbrechen
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
